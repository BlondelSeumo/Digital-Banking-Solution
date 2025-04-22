<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    public function withdrawLog()
    {
        $withdrawals = Withdrawal::where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE)->searchable(['trx'])->with('method')->apiQuery();
        $notify[]    = 'Withdrawal History';
        $path        = getFilePath('verify');
        return response()->json([
            'remark'  => 'withdrawal_history',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'withdrawals' => $withdrawals,
                'path'        => $path,
            ],
        ]);
    }

    public function withdrawMethod()
    {
        $withdrawMethod = WithdrawMethod::where('status', Status::ENABLE)->get();
        $notify[]       = 'Withdrawals methods';
        return response()->json([
            'remark'  => 'withdrawals_methods',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'withdraw_method' => $withdrawMethod,
                'verification'    => verification(),
            ],
        ]);
    }

    public function apply(Request $request)
    {
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', Status::ENABLE)->first();
        if (!$method) {
            $notify[] = 'Withdraw method not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $validator = $this->validation($request, $method);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $additionalData = [
            'after_verified' => 'api.withdraw.store',
            'amount'         => $request->amount,
        ];
        $otpManager = new OTPManager();
        return $otpManager->newOTP($method, $request->auth_mode, 'WITHDRAW_OTP', $additionalData, true);
    }

    public function withdrawStore($id)
    {
        $verification = OtpVerification::find($id);
        if (!$verification) {
            $notify[] = 'Verification not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $validator = Validator::make(request()->all(), []);
        OTPManager::checkVerificationData($verification, WithdrawMethod::class, true, $validator);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $method = $verification->verifiable;
        $amount = $verification->additional_data->amount;
        $user   = auth()->user();

        if ($user->balance < $amount) {
            $notify[] = 'Sorry! You don\'t have sufficient balance';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
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

        $notify[] = 'Withdraw store successfully';
        return response()->json([
            'remark'  => 'withdraw_store',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'trx' => $withdraw->trx,
            ],
        ]);
    }

    public function withdrawPreview($trx)
    {
        $withdraw = Withdrawal::with('method', 'user')->where('trx', $trx)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->first();
        if (!$withdraw) {
            $notify[] = 'Invalid Request';
            return response()->json([
                'remark'  => 'trx_invalid',
                'status'  => 'success',
                'message' => ['success' => $notify],
            ]);
        }
        $notify[] = 'Withdraw Preview';
        return response()->json([
            'remark'  => 'withdraw_preview',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'withdraw' => $withdraw,
                'form'     => $withdraw->method->form,
            ],
        ]);
    }

    public function withdrawSubmit(Request $request, $trx)
    {
        $withdraw = Withdrawal::with('method', 'user')->where('trx', $trx)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->first();
        if (!$withdraw) {
            $notify[] = 'Invalid Request';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $method = $withdraw->method;

        if ($method->status == Status::DISABLE) {
            if (!$withdraw) {
                $notify[] = 'Invalid Request';
                return response()->json([
                    'remark'  => 'validation_error',
                    'status'  => 'error',
                    'message' => ['error' => $notify],
                ]);
            }
        }

        $userData = null;
        if (@$method->form->form_data) {
            $formData           = $method->form->form_data;
            $formProcessor      = new FormProcessor();
            $validationRule     = $formProcessor->valueValidation($formData);
            $formDataValidation = Validator::make($request->all(), $validationRule);

            if ($formDataValidation->fails()) {
                return response()->json([
                    'remark'  => 'validation_error',
                    'status'  => 'error',
                    'message' => ['error' => $formDataValidation->errors()->all()],
                ]);
            }
            $userData = $formProcessor->processFormData($request, $formData);
        }

        $user = auth()->user();

        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = 'Wrong verification code';
                return response()->json([
                    'remark'  => 'validation_error',
                    'status'  => 'error',
                    'message' => ['error' => $notify],
                ]);
            }
        }

        if ($withdraw->amount > $user->balance) {
            $notify[] = 'Insufficient balance';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
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
        $transaction->details      = showAmount($withdraw->final_amount, currencyFormat:false) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
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

        $notify[] = 'Withdraw request sent successfully';
        return response()->json([
            'remark'  => 'withdraw_confirm',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    private function validation($request, $method)
    {

        $min = getAmount($method->min_limit);
        $max = getAmount($method->max_limit);

        $rules = [
            'method_code' => 'required',
            'amount'      => "required|numeric|min:$min|max:$max",
        ];
        $rules     = mergeOtpField($rules);
        $validator = Validator::make($request->all(), $rules);

        if ($request->amount > auth()->user()->balance) {
            return addCustomValidation($validator, 'balance', 'Insufficient balance');
        }
        return $validator;
    }
}
