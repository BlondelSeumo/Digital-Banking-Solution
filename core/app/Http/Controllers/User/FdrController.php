<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Models\AdminNotification;
use App\Models\Fdr;
use App\Models\FdrPlan;
use App\Models\OtpVerification;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FdrController extends Controller
{
    public function list()
    {
        $pageTitle = 'My FDR List';
        $allFdr    = Fdr::where('user_id', auth()->id())->searchable(['fdr_number', 'plan:name'])
        ->dateFilter('created_at')->with('plan:id,name')->orderBy('id', 'DESC');

        if (request()->status) {
            $allFdr->where('status', request()->status);
        }

        if (request()->download == 'pdf') {
            $allFdr = $allFdr->get();
            return downloadPDF('Template::pdf.fdr_list', compact('pageTitle', 'allFdr'));
        }
        $allFdr = $allFdr->paginate(getPaginate());

        return view('Template::user.fdr.list', compact('pageTitle', 'allFdr'));
    }

    public function plans()
    {
        $pageTitle = 'Fixed Deposit Receipt Plans';
        $plans     = FdrPlan::active()->orderBy('interest_rate')->get();
        return view('Template::user.fdr.plans', compact('pageTitle', 'plans'));
    }

    public function apply(Request $request, $id)
    {
        $plan = FdrPlan::active()->find($id);
        $this->validation($request, $plan);

        $additionalData = [
            'amount'         => $request->amount,
            'after_verified' => 'user.fdr.apply.preview',
        ];

        $otpManager = new OTPManager();
        return $otpManager->newOTP($plan, $request->auth_mode, 'FDR_OTP', $additionalData);
    }

    public function preview()
    {

        $verification = OtpVerification::find(sessionVerificationId());
        OTPManager::checkVerificationData($verification, FdrPlan::class);
        $plan           = $verification->verifiable;
        $amount         = $verification->additional_data->amount;
        $verificationId = $verification->id;
        $pageTitle      = 'FDR Application Preview';
        return view('Template::user.fdr.preview', compact('pageTitle', 'plan', 'amount', 'verificationId'));
    }

    public function confirm($id)
    {
        $verification = OtpVerification::find($id);
        OTPManager::checkVerificationData($verification, FdrPlan::class);
        $amount = $verification->additional_data->amount;
        $user   = auth()->user();

        if ($user->balance < $amount) {
            $notify[] = ['error', 'Sorry! You don\'t have sufficient balance'];
            return to_route('user.fdr.plans')->withNotify($notify);
        }

        $plan = $verification->verifiable;

        if ($plan->status != Status::ENABLE) {
            $notify[] = ['error', 'This plan is currently disabled'];
            return to_route('user.fdr.plans')->withNotify($notify);
        }

        $fdr                        = new Fdr();
        $fdr->user_id               = $user->id;
        $fdr->plan_id               = $plan->id;
        $fdr->fdr_number            = getTrx();
        $fdr->amount                = $amount;
        $fdr->per_installment       = getAmount($amount * $plan->interest_rate / 100);
        $fdr->installment_interval  = $plan->installment_interval;
        $fdr->next_installment_date = now()->addDays($plan->installment_interval);
        $fdr->locked_date           = now()->addDays($plan->locked_days);
        $fdr->save();

        $user->balance -= $amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'New FDR opened';
        $transaction->remark       = "fdr_open";
        $transaction->trx          = $fdr->fdr_number;
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'A new FDR opened';
        $adminNotification->click_url = urlPath('admin.fdr.index') . "?search=" . $fdr->fdr_number;
        $adminNotification->save();

        $shortCodes = [
            'plan_name'             => $plan->name,
            'fdr_number'            => $fdr->fdr_number,
            'amount'                => $amount,
            'locked_date'           => $fdr->locked_date,
            'per_installment'       => $fdr->per_installment,
            'interest_rate'         => getAmount($plan->interest_rate) . '%',
            'installment_interval'  => $fdr->installment_interval,
            'next_installment_date' => $fdr->next_installment_date,
        ];

        notify($user, 'FDR_OPENED', $shortCodes);

        session()->forget('otp_data');
        session()->forget('otp_id');

        $notify[] = ['success', 'FDR opened successfully'];
        return to_route('user.fdr.details', $fdr->fdr_number)->withNotify($notify);
    }

    public function close($id)
    {
        $fdr = Fdr::where('id', $id)->where('user_id', auth()->id())->findOrFail($id);

        if ($fdr->status == Status::FDR_CLOSED) {
            $notify[] = ['error', 'This FDR has already been closed'];
            return back()->withNotify($notify);
        }

        if ($fdr->locked_date->endOfDay() > Carbon::now()) {
            $notify[] = ['error', 'Sorry! You cant close this FDR before ' . showDateTime($fdr->locked_date, 'd M, Y')];
            return back()->withNotify($notify);
        }

        $fdr->status    = Status::FDR_CLOSED;
        $fdr->closed_at = now();
        $fdr->save();

        $user = auth()->user();
        $user->balance += $fdr->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $fdr->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Received main amount of FDR';
        $transaction->trx          = getTrx();
        $transaction->remark       = "fdr_closed";
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'FDR closed';
        $adminNotification->click_url = urlPath('admin.fdr.index') . "?search=" . $fdr->fdr_number;
        $adminNotification->save();

        notify($user, 'FDR_CLOSED', [
            "fdr_number"      => $fdr->fdr_number,
            "amount"          => $fdr->amount,
            "profit"          => $fdr->profit,
            "per_installment" => $fdr->per_installment,
            "currency"        => gs('cur_text'),
            "plan_name"       => $fdr->plan->name,
            "post_balance"    => $user->balance,
        ]);

        $notify[] = ['success', 'FDR closed successfully'];
        return back()->withNotify($notify);
    }

    public function installments($fdr_number)
    {
        $fdr          = Fdr::where('user_id', auth()->id())->where('fdr_number', $fdr_number)->firstOrFail();
        $installments = $fdr->installments()->paginate(getPaginate());
        $pageTitle    = 'FDR Installments';
        return view('Template::user.fdr.installments', compact('pageTitle', 'installments', 'fdr'));
    }

    private function validation($request, $plan)
    {
        if (!$plan) {
            throw ValidationException::withMessages(['error' => 'No such plan found']);
        }

        $rules = ['amount' => "required|numeric|min:$plan->minimum_amount|max:$plan->maximum_amount"];
        $rules = mergeOtpField($rules);

        $request->validate($rules);

        if (auth()->user()->balance < $request->amount) {
            throw ValidationException::withMessages(['error' => 'Sorry! You don\'t have sufficient balance']);
        }
    }


    public function details($fdrNumber)
    {
        $fdr = auth()->user()->fdr()->where('fdr_number', $fdrNumber)->with('plan:id,name,interest_rate')->orderBy('id', 'DESC')->firstOrFail();
        $pageTitle = "FDR Details";
        if(request()->has('download')) {
            return downloadPDF('pdf.fdr_details', compact('pageTitle', 'fdr'));
        }
        return view('Template::user.fdr.details', compact('pageTitle', 'fdr'));
    }

}
