<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Support\Facades\Validator;

class FdrController extends Controller
{

    function list() {
        $allFdr   = Fdr::where('user_id', auth()->id())->with('plan:id,name')->apiQuery();
        $notify[] = 'User FDR Data';
        return response()->json([
            'remark'  => 'fdr_data',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'fdr' => $allFdr,
            ],
        ]);
    }

    public function plans()
    {

        $notify[] = 'Fixed Deposit Receipt Plans';
        $plans    = FdrPlan::active()->orderBy('interest_rate')->get();

        return response()->json([
            'remark'  => 'fdr_plans',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'fdr_plans' => $plans,
            ],
        ]);
    }

    public function apply(Request $request, $id)
    {
        $plan = FdrPlan::active()->find($id);
        if (!$plan) {
            $notify[] = 'Plan not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $validator = $this->validation($request, $plan);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }
        $additionalData = [
            'amount'         => $request->amount,
            'after_verified' => 'api.fdr.apply.preview',
        ];

        $otpManager = new OTPManager();
        return $otpManager->newOTP($plan, $request->auth_mode, 'FDR_OTP', $additionalData, true);
    }

    public function preview($id)
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
        OTPManager::checkVerificationData($verification, FdrPlan::class, true, $validator);
        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $plan           = $verification->verifiable;
        $amount         = $verification->additional_data->amount;
        $verificationId = $verification->id;

        $withdrawAvailable = showDateTime(now()->addDays($plan->locked_days), 'd M, Y');

        $notify[] = 'FDR Application Preview';
        return response()->json([
            'remark'  => 'fdr_preview',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'plan'              => $plan,
                'amount'            => $amount,
                'verificationId'    => $verificationId,
                'withdrawAvailable' => $withdrawAvailable,
            ],
        ]);
    }

    public function confirm($id)
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
        OTPManager::checkVerificationData($verification, FdrPlan::class, true, $validator);
        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }
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

        $plan = $verification->verifiable;

        if ($plan->status != Status::ENABLE) {
            $notify[] = 'This plan is currently disabled';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
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

        $notify[] = 'FDR opened successfully';
        return response()->json([
            'remark'  => 'fdr_opened',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function close($id)
    {
        $fdr = Fdr::where('user_id', auth()->id())->find($id);
        if (!$fdr) {
            $notify[] = 'FDR not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        if ($fdr->status == Status::FDR_CLOSED) {
            $notify[] = 'This FDR has already been closed';
            return response()->json([
                'remark'  => 'fdr_already_closed',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        if ($fdr->locked_date->endOfDay() > Carbon::now()) {
            $notify[] = 'Sorry! You cant close this FDR before ' . showDateTime($fdr->locked_date, 'd M, Y');
            return response()->json([
                'remark'  => 'close_fdr_before',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
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

        $notify[] = 'FDR closed successfully';
        return response()->json([
            'remark'  => 'fdr_closed',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function installments($fdrNumber)
    {
        $fdr = Fdr::where('user_id', auth()->id())->where('fdr_number', $fdrNumber)->with('plan:id,name')->first();
        if (!$fdr) {
            $notify[] = 'FDR not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $installments = $fdr->installments()->paginate(getPaginate());
        $interestRate = $fdr->interestRate;
        $notify[]     = 'FDR Installments';
        return response()->json([
            'remark'  => 'fdr_installments',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'installments'  => $installments,
                'fdr'           => $fdr,
                'interest_rate' => $interestRate,
            ],
        ]);
    }

    private function validation($request, $plan)
    {
        $rules     = ['amount' => "required|numeric|min:$plan->minimum_amount|max:$plan->maximum_amount"];
        $rules     = mergeOtpField($rules);
        $validator = Validator::make($request->all(), $rules);
        if (auth()->user()->balance < $request->amount) {
            return addCustomValidation($validator, 'balance', 'Sorry! You don\'t have sufficient balance');
        }
        return $validator;
    }
}
