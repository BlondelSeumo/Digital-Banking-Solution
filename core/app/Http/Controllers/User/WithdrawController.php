<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\OTPManager;
use App\Models\AdminNotification;
use App\Models\OtpVerification;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WithdrawController extends Controller
{

    public function withdrawLog(Request $request)
    {
        $pageTitle = "Withdrawal History";
        $withdraws = auth()->user()->withdrawals();
        if (request()->search) {
            $withdraws = $withdraws->where('trx', request()->search);
        }
        if (request()->status) {
            $withdraws = $withdraws->where('status', request()->status);
        }
        $withdraws = $withdraws->with('method', 'branch:id,name')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.withdraw.log', compact('pageTitle', 'withdraws'));
    }

    public function withdrawMoney()
    {
        $withdrawMethod = WithdrawMethod::where('status', Status::ENABLE)->get();
        $pageTitle      = 'Withdraw Money';
        return view('Template::user.withdraw.methods', compact('pageTitle', 'withdrawMethod'));
    }

    public function apply(Request $request)
    {
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', Status::ENABLE)->first();
        $this->validation($request, $method);

        $additionalData = [
            'after_verified' => 'user.withdraw.money',
            'amount'         => $request->amount,
        ];

        if (auth()->user()->balance < $request->amount) {
            $notify[] = ['error', 'Sorry! You don\'t have sufficient balance'];
            return to_route('user.withdraw')->withNotify($notify);
        }

        $otpManager = new OTPManager();
        return $otpManager->newOTP($method, $request->auth_mode, 'WITHDRAW_OTP', $additionalData);
    }

    public function withdrawStore()
    {
        $verification = OtpVerification::find(sessionVerificationId());
        OTPManager::checkVerificationData($verification, WithdrawMethod::class);

        $method = $verification->verifiable;
        $amount = $verification->additional_data->amount;
        $user   = auth()->user();

        if ($user->balance < $amount) {
            $notify[] = ['error', 'Sorry! You don\'t have sufficient balance'];
            return to_route('user.withdraw')->withNotify($notify);
        }

        $charge      = $method->fixed_charge + ($amount * $method->percent_charge / 100);
        $afterCharge = $amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id;
        $withdraw->user_id      = $user->id;
        $withdraw->amount       = $amount;
        $withdraw->currency     = $method->currency;
        $withdraw->rate         = $method->rate;
        $withdraw->charge       = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx          = getTrx();
        $withdraw->save();

        session()->put('wtrx', $withdraw->trx);
        return to_route('user.withdraw.preview');
    }

    public function withdrawPreview()
    {
        $withdraw  = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->firstOrFail();
        $pageTitle = 'Withdraw Preview';
        return view('Template::user.withdraw.preview', compact('pageTitle', 'withdraw'));
    }

    public function withdrawSubmit(Request $request)
    {
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->firstOrFail();
        $method   = $withdraw->method;

        if ($method->status == Status::DISABLE) {
            abort(404);
        }

        $formData       = $method->form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $user = auth()->user();


        if ($withdraw->amount > $user->balance) {
            $notify[] = ['error', 'Insufficient balance'];
            return back()->withNotify($notify);
        }

        $withdraw->status               = Status::PAYMENT_PENDING;
        $withdraw->withdraw_information = $userData;
        $withdraw->save();
        $user->balance -= $withdraw->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = $withdraw->charge;
        $transaction->trx_type     = '-';
        $transaction->details      = showAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw';
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.data.details', $withdraw->id);
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount' => showAmount($withdraw->amount, currencyFormat: false),
            'charge' => showAmount($withdraw->charge, currencyFormat: false),
            'rate' => showAmount($withdraw->rate, currencyFormat: false),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($user->balance, currencyFormat: false),
        ]);

        $notify[] = ['success', 'Withdraw request sent successfully'];
        return to_route('user.withdraw.history')->withNotify($notify);
    }


    public function details($trxNumber){
        $pageTitle = 'Withdraw Details';
        $withdraw = auth()->user()->withdrawals()->where('trx', $trxNumber)->with(['method'])->orderBy('id', 'desc')->firstOrFail();
        return view('Template::user.withdraw.details', compact('pageTitle', 'withdraw'));
    }

    private function validation($request, $method)
    {
        if (!$method) {
            throw ValidationException::withMessages(['error' => 'No such plan found']);
        }

        $min = getAmount($method->min_limit);
        $max = getAmount($method->max_limit);

        $rules = [
            'method_code' => 'required',
            'amount'      => "required|numeric|min:$min|max:$max",
        ];

        $rules = mergeOtpField($rules);

        $request->validate($rules);

        if ($request->amount > auth()->user()->balance) {
            $notify[] = ['error', 'Insufficient balance'];
            return back()->withNotify($notify);
        }
    }
}
