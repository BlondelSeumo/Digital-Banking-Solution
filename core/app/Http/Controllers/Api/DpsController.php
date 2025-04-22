<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Models\AdminNotification;
use App\Models\Dps;
use App\Models\DpsPlan;
use App\Models\Installment;
use App\Models\OtpVerification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DpsController extends Controller {
    public function list() {
        $allDps   = Dps::where('user_id', auth()->id())->with('nextInstallment')->withCount('lateInstallments')->with('plan')->apiQuery();
        $notify[] = 'User DPS Data';
        return response()->json([
            'remark'  => 'dps',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'all_dps' => $allDps,
            ],
        ]);
    }

    public function plans() {
        $plans = DpsPlan::active()->apiQuery();

        $notify[] = 'Deposit Pension Scheme Plans';
        return response()->json([
            'remark'  => 'dps_plans',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'dps_plans' => $plans,
            ],
        ]);
    }

    public function apply(Request $request, $id) {
        $plan      = DpsPlan::active()->find($id);
        $validator = $this->validation($request, $plan);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $additionalData = ['after_verified' => 'api.dps.apply.preview'];
        $otpManager     = new OTPManager();
        return $otpManager->newOTP($plan, $request->auth_mode, 'DPS_OTP', $additionalData, true);
    }

    private function validation($request, $plan) {
        $rules     = mergeOtpField();
        $validator = Validator::make($request->all(), $rules);

        if (!$plan) {
            return addCustomValidation($validator, 'plan', 'No such plan found');
        }

        if (auth()->user()->balance < $plan->per_installment) {
            return addCustomValidation($validator, 'balance', 'You must have at least one installment amount in your account');
        }
        return $validator;
    }

    public function preview($id) {
        $verification = OtpVerification::find($id);
        if (!$verification) {
            $notify[] = 'Invalid request';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        OTPManager::checkVerificationData($verification, DpsPlan::class);
        $plan           = $verification->verifiable;
        $delayCharge    = $plan->delayCharge;
        $verificationId = $verification->id;
        $notify[]       = 'DPS Application Preview';
        return response()->json([
            'remark'  => 'dps_preview',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'plan'           => $plan,
                'verificationId' => $verificationId,
                'delay_charge'   => $delayCharge,
            ],
        ]);
    }

    public function confirm($id) {
        $verification = OtpVerification::find($id);
        if (!$verification) {
            $notify = 'OTP verification not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $validator = Validator::make(request()->all(), []);
        OTPManager::checkVerificationData($verification, DpsPlan::class, true, $validator);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $plan   = $verification->verifiable;
        $amount = $plan->per_installment + 0;
        $user   = auth()->user();

        if ($user->balance < $amount) {
            $notify[] = ['error', 'You must have at least one installment amount in your account'];
            return redirect()->route('user.dps.plans')->withNotify($notify);
        }

        $percentCharge = $plan->per_installment * $plan->percent_charge / 100;
        $charge        = $plan->fixed_charge + $percentCharge;

        $dps                         = new Dps();
        $dps->user_id                = $user->id;
        $dps->plan_id                = $plan->id;
        $dps->dps_number             = getTrx();
        $dps->interest_rate          = $plan->interest_rate;
        $dps->per_installment        = $plan->per_installment;
        $dps->total_installment      = $plan->total_installment;
        $dps->given_installment      = 1;
        $dps->installment_interval   = $plan->installment_interval;
        $dps->delay_value            = $plan->delay_value;
        $dps->charge_per_installment = $charge;
        $dps->save();

        $user->balance -= $amount;
        $user->save();

        Installment::saveInstallments($dps);
        $nextInstallment           = $dps->nextInstallment()->first();
        $nextInstallment->given_at = now();
        $nextInstallment->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'DPS installment given';
        $transaction->trx          = $dps->dps_number;
        $transaction->remark       = "dps_installment";
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New DPS opened';
        $adminNotification->click_url = urlPath('admin.dps.index') . '?search=' . $dps->dps_number;
        $adminNotification->save();

        $shortCodes                          = $dps->shortCodes();
        $shortCodes['next_installment_date'] = now()->addDays($dps->installment_interval);

        notify($user, 'DPS_OPENED', $shortCodes);

        $notify[] = 'DPS request confirm successfully';
        return response()->json([
            'remark'  => 'dsp_confirm',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function withdraw($id) {
        $dps = Dps::where('user_id', auth()->id())->with('plan')->find($id);

        if (!$dps) {
            $notify[] = 'Dps not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        if ($dps->status == Status::DPS_RUNNING) {
            $notify[] = 'You can\'t withdraw a DPS before mature';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        if ($dps->status == Status::DPS_CLOSED) {
            $notify[] = 'You have already withdrawn the DPS amount';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $user        = auth()->user();
        $dps->status = Status::DPS_CLOSED;
        $dps->save();

        $withdrawableAmount = $dps->withdrawableAmount();

        $user->balance += $withdrawableAmount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $withdrawableAmount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'DPS mature amount received';
        $transaction->remark       = 'dps_matured';
        $transaction->trx          = getTrx();
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'A matured DPS has been withdrawn';
        $adminNotification->click_url = urlPath('admin.dps.index') . '?search=' . $dps->dps_number;
        $adminNotification->save();

        notify($user, 'DPS_CLOSED', $dps->shortCodes());

        $notify[] = 'DPS closed successfully';
        return response()->json([
            'remark'  => 'dps_closed',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function installments($dpsNumber) {
        $dps = Dps::where('dps_number', $dpsNumber)->where('user_id', auth()->id())->with('plan:id,name')->first();
        if (!$dps) {
            $notify[] = 'Dps not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $installments  = $dps->installments()->paginate(getPaginate());
        $depositAmount = $dps->depositedAmount();
        $profitAmount  = $dps->profitAmount();
        $notify[]      = 'Dps Installments';
        return response()->json([
            'remark'  => 'dps_installments',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'installments'  => $installments,
                'dps'           => $dps,
                'depositAmount' => $depositAmount,
                'profitAmount'  => $profitAmount,
            ],
        ]);
    }

}
