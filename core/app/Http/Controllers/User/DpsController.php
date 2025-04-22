<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Models\AdminNotification;
use App\Models\Dps;
use App\Models\DpsPlan;
use App\Models\Installment;
use App\Models\OtpVerification;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DpsController extends Controller {
    public function list() {
        $pageTitle = 'My DPS List';
        $allDps    = Dps::where('user_id', auth()->id())->searchable(['dps_number', 'plan:name'])
            ->withCount('lateInstallments')->with(['plan', 'nextInstallment']);
        if (request()->status) {
            $allDps->where('status', request()->status);
        }
        if (request()->date) {
            $date      = explode('-', request()->date);
            $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d');
            $endDate = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;

            request()->merge(['start_date' => $startDate, 'end_date' => $endDate]);
            request()->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date'   => 'nullable|date_format:Y-m-d',
            ]);
            $allDps->whereHas('nextInstallment', function ($query) use ($startDate, $endDate) {
                $query->whereDate('installment_date', '>=', $startDate)->whereDate('installment_date', '<=', $endDate);
            });
        }

        if (request()->download == 'pdf') {
            $allDps = $allDps->get();
            return downloadPDF('Template::pdf.dps_list', compact('pageTitle', 'allDps'));
        }
        $allDps = $allDps->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('Template::user.dps.list', compact('pageTitle', 'allDps'));
    }

    public function plans() {
        $pageTitle = 'Deposit Pension Scheme Plans';
        $plans     = DpsPlan::active()->orderBy('per_installment')->get();
        return view('Template::user.dps.plans', compact('pageTitle', 'plans'));
    }

    public function apply(Request $request, $id) {
        $plan = DpsPlan::active()->find($id);
        $this->validation($request, $plan);
        $additionalData = ['after_verified' => 'user.dps.apply.preview'];
        $otpManager     = new OTPManager();
        return $otpManager->newOTP($plan, $request->auth_mode, 'DPS_OTP', $additionalData);
    }

    public function preview() {
        $verification = OtpVerification::find(sessionVerificationId());
        OTPManager::checkVerificationData($verification, DpsPlan::class);
        $plan           = $verification->verifiable;
        $verificationId = $verification->id;
        $pageTitle      = 'DPS Application Preview';
        return view('Template::user.dps.preview', compact('pageTitle', 'plan', 'verificationId'));
    }

    public function confirm($id) {
        $verification = OtpVerification::find($id);
        OTPManager::checkVerificationData($verification, DpsPlan::class);
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

        session()->forget('otp');
        session()->forget('otp_id');

        $shortCodes                          = $dps->shortCodes();
        $shortCodes['next_installment_date'] = now()->addDays($dps->installment_interval);

        notify($user, 'DPS_OPENED', $shortCodes);

        $notify[] = ['success', 'DPS opened successfully'];
        return to_route('user.dps.details', $dps->dps_number)->withNotify($notify);
    }

    public function installments($dpsNumber) {
        $dps          = Dps::where('dps_number', $dpsNumber)->where('user_id', auth()->id())->firstOrFail();
        $installments = $dps->installments()->paginate(getPaginate());
        $pageTitle    = 'DPS Installments';
        return view('Template::user.dps.installments', compact('pageTitle', 'installments', 'dps'));
    }

    public function withdraw($id) {
        $dps = Dps::where('user_id', auth()->id())->with('plan')->findOrFail($id);

        if ($dps->status == Status::DPS_RUNNING) {
            $notify[] = ['error', 'You can\'t withdraw a DPS before mature'];
            return back()->withNotify($notify);
        }

        if ($dps->status == Status::DPS_CLOSED) {
            $notify[] = ['error', 'You have already withdrawn the DPS amount'];
            return back()->withNotify($notify);
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

        $notify[] = ['success', 'DPS closed successfully'];
        return back()->withNotify($notify);
    }

    private function validation($request, $plan) {
        if (!$plan) {
            throw ValidationException::withMessages(['error' => 'No such plan found']);
        }

        $rules = mergeOtpField();

        $request->validate($rules);

        if (auth()->user()->balance < $plan->per_installment) {
            throw ValidationException::withMessages(['error' => 'You must have at least one installment amount in your account']);
        }
    }

    public function details($dpsNumber) {
        $dps = auth()->user()->dps()->where('dps_number', $dpsNumber)->with('plan')->orderBy('id', 'DESC')->firstOrFail();
        $pageTitle = "DPS Details";
        if (request()->has('download')) {
            return downloadPDF('pdf.dps_details', compact('pageTitle', 'dps'));
        }
        return view('Template::user.dps.details', compact('pageTitle', 'dps'));
    }

}
