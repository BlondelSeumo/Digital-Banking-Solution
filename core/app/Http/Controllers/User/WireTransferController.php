<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\OTPManager;
use App\Models\AdminNotification;
use App\Models\BalanceTransfer;
use App\Models\Form;
use App\Models\OtpVerification;
use App\Models\Transaction;
use App\Models\WireTransferSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WireTransferController extends Controller {

    public function wireTransfer() {
        $pageTitle = "Wire Transfer";
        $setting   = WireTransferSetting::first();

        if (!$setting) {
            $notify[] = ['error', 'The wire transfer system is not currently available'];
            return back()->withNotify($notify);
        }

        return view('Template::user.transfer.wire_transfer.form', compact('pageTitle', 'setting'));
    }

    public function transferRequest(Request $request) {
        $wireTransferSetting = WireTransferSetting::firstOrFail();
        $formProcessor       = new FormProcessor();
        $form                = Form::where('act', 'wire_transfer')->first();
        $formData            = $form->form_data;
        $validationRule      = $formProcessor->valueValidation($formData);
        $validationRule      = mergeOtpField($validationRule);
        $request->validate($validationRule);

        $this->checkTransferAvailability($request->amount, $wireTransferSetting);

        $additionalData = [
            'amount'           => $request->amount,
            'after_verified'   => 'user.transfer.wire.confirm',
            'application_form' => $formProcessor->processFormData($request, $formData),
        ];

        $otpManager = new OTPManager();
        return $otpManager->newOTP($wireTransferSetting, $request->auth_mode, 'WIRE_TRANSFER_OTP', $additionalData);
    }

    public function confirm() {
        $verification = OtpVerification::find(sessionVerificationId());
        $setting      = $verification->verifiable;
        $amount       = $verification->additional_data->amount;
        $user         = auth()->user();

        OTPManager::checkVerificationData($verification, WireTransferSetting::class);

        $this->checkTransferAvailability($amount, $setting);

        $charge      = $this->charge($amount, $setting);
        $finalAmount = $amount + $charge;

        $transfer                     = new BalanceTransfer();
        $transfer->user_id            = $user->id;
        $transfer->trx                = getTrx();
        $transfer->beneficiary_id     = 0;
        $transfer->amount             = $amount;
        $transfer->charge             = $charge;
        $transfer->status             = Status::TRANSFER_PENDING;
        $transfer->wire_transfer_data = $verification->additional_data->application_form;
        $transfer->save();

        $user->balance -= $finalAmount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $finalAmount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = $transfer->charge;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Wire Transfer';
        $transaction->trx          = $transfer->trx;
        $transaction->remark       = "wire_transfer";
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New wire transfer request';
        $adminNotification->click_url = urlPath('admin.transfers.details', $transfer->id);
        $adminNotification->save();

        session()->forget('otp_id');

        $accountName   = $transfer->wireTransferAccountName();
        $accountNumber = $transfer->wireTransferAccountNumber();

        notify($user, 'WIRE_TRANSFER_REQUEST_SEND', [
            "sender_account_number"    => $transfer->user->account_number,
            "sender_account_name"      => $transfer->user->username,
            "recipient_account_number" => @$accountNumber->value,
            "recipient_account_name"   => @$accountName->value,
            "sending_amount"           => $transfer->amount,
            "charge"                   => $transfer->charge,
            "final_amount"             => $finalAmount,
        ]);

        $notify[] = ['success', "Transfer request sent successfully"];
        return to_route('user.transfer.details', $transfer->trx)->withNotify($notify);
    }

    public function details($id) {
        $transfer = BalanceTransfer::wireTransfer()->where('user_id', auth()->id())->where('id', $id)->first();
        if (!$transfer) {
            return response()->json([
                'success' => false,
                'message' => "Wire Transfer not found",
            ]);
        }

        $data = @$transfer->wire_transfer_data;
        $html = view('components.view-form-data', compact('data'))->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
        ]);
    }

    private function checkTransferAvailability($amount, $setting) {

        $charge      = $this->charge($amount, $setting);
        $finalAmount = $amount + $charge;
        $user        = auth()->user();

        if ($user->balance < $finalAmount) {
            throw ValidationException::withMessages(['error' => 'Sorry! You don\'t have sufficient balance']);
            throw ValidationException::withMessages(['error' => 'Sorry! You don\'t have sufficient balance']);
        }

        if ($amount < $setting->minimum_limit) {
            throw ValidationException::withMessages(['error' => 'Sorry minimum transfer limit is ' . showAmount($setting->minimum_limit)]);
        }

        if ($amount > $setting->maximum_limit) {
            throw ValidationException::withMessages(['error' => 'Sorry maximum transfer limit is ' . showAmount($setting->maximum_limit)]);
        }

        $todaysData = BalanceTransfer::wireTransfer()
            ->notRejected()
            ->where('user_id', $user->id)
            ->whereDate('created_at', now())
            ->selectRaw('count(id) as total_transfer, sum(amount) as total_amount')
            ->first();

        $todaysTotalAmount = $todaysData['total_amount'] ?? 0;
        $todaysTotalCount  = $todaysData['total_transfer'];

        if ($todaysTotalAmount + $amount > $setting->daily_maximum_limit) {
            throw ValidationException::withMessages(['error' => 'Sorry you are exceeding the daily transfer limit']);
        }

        if ($todaysTotalCount > $setting->daily_total_transaction) {
            throw ValidationException::withMessages(['error' => 'Sorry you have already reached the daily transfer limit of ' . $setting->daily_total_transaction . 'times']);
        }

        $thisMonthData = BalanceTransfer::wireTransfer()
            ->notRejected()
            ->where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('count(id) as total_transfer, sum(amount) as total_amount')
            ->first();

        $thisMonthTotalAmount = $thisMonthData['total_amount'] ?? 0;
        $thisMonthTotalCount  = $thisMonthData['total_transfer'];

        if ($thisMonthTotalAmount + $amount > $setting->monthly_maximum_limit) {
            throw ValidationException::withMessages(['error' => 'Sorry you are exceeding the monthly transfer limit']);
        }

        if ($thisMonthTotalCount > $setting->monthly_total_transaction) {
            throw ValidationException::withMessages(['error' => 'Sorry you have already reached the monthly transfer limit of ' . $setting->monthly_total_transaction . 'times']);
        }
    }

    private function charge($amount, $setting) {
        $percentCharge = $amount * $setting->percent_charge / 100;
        return $setting->fixed_charge + $percentCharge;
    }
}
