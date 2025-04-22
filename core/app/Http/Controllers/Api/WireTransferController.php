<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Support\Facades\Validator;

class WireTransferController extends Controller {
    public function wireTransfer() {
        $setting = WireTransferSetting::first();
        if (!$setting) {
            $notify[] = 'The wire transfer system is not currently available';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $form = Form::where('act', 'wire_transfer')->first();
        if (!$form) {
            $notify[] = 'Wire transfer form data not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $notify[] = 'Wire Transfer';
        return response()->json([
            'remark'  => 'wire_transfer',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'setting' => $setting,
                'form'    => $form,
            ],
        ]);
    }

    public function transferRequest(Request $request) {

        $wireTransferSetting = WireTransferSetting::first();
        if (!$wireTransferSetting) {
            $notify[] = 'The wire transfer system is not currently available';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $formProcessor = new FormProcessor();
        $form          = Form::where('act', 'wire_transfer')->first();
        if (!$form) {
            $notify[] = 'Wire transfer form data not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);

        $userData = null;
        if (@$form->form_data) {
            $formData           = $form->form_data;
            $formProcessor      = new FormProcessor();
            $validationRule     = $formProcessor->valueValidation($formData);
            $validationRule     = mergeOtpField($validationRule);
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

        $this->checkTransferAvailability($request->amount, $wireTransferSetting, $validator);
        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $additionalData = [
            'amount'           => $request->amount,
            'application_form' => $formProcessor->processFormData($request, $formData),
            'after_verified'   => 'api.transfer.wire.confirm',
        ];

        $otpManager = new OTPManager();
        return $otpManager->newOTP($wireTransferSetting, $request->auth_mode, 'WIRE_TRANSFER_OTP', $additionalData, true);
    }

    private function checkTransferAvailability($amount, $setting, $validator) {

        $charge      = $this->charge($amount, $setting);
        $finalAmount = $amount + $charge;
        $user        = auth()->user();

        if ($user->balance < $finalAmount) {
            return addCustomValidation($validator, 'error', 'Sorry! You don\'t have sufficient balance');
        }

        if ($amount < $setting->minimum_limit) {
            return addCustomValidation($validator, 'error', 'Sorry minimum transfer limit is ' . showAmount($setting->minimum_limit, currencyFormat:false));
        }

        if ($amount > $setting->maximum_limit) {
            return addCustomValidation($validator, 'error', 'Sorry maximum transfer limit is ' . showAmount($setting->maximum_limit, currencyFormat:false));
        }

        $todaysData = BalanceTransfer::wireTransfer()
            ->notRejected()
            ->where('user_id', $user->id)
            ->whereDate('created_at', now())
            ->selectRaw('count(id) as total_transfer, sum(amount) as total_amount')
            ->first();

        if (!$todaysData) {
            return addCustomValidation($validator, 'error', 'Today\'s data not found');
        }
        $todaysTotalAmount = $todaysData['total_amount'] ?? 0;
        $todaysTotalCount  = $todaysData['total_transfer'];

        if ($todaysTotalAmount + $amount > $setting->daily_maximum_limit) {
            return addCustomValidation($validator, 'error', 'Sorry you are exceeding the daily transfer limit');
        }

        if ($todaysTotalCount > $setting->daily_total_transaction) {
            return addCustomValidation($validator, 'error', 'Sorry you have already reached the daily transfer limit of ' . $setting->daily_total_transaction . 'times');
        }

        $thisMonthData = BalanceTransfer::wireTransfer()
            ->notRejected()
            ->where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('count(id) as total_transfer, sum(amount) as total_amount')
            ->first();
        if (!$thisMonthData) {
            return addCustomValidation($validator, 'error', 'This month data not found');
        }
        $thisMonthTotalAmount = $thisMonthData['total_amount'] ?? 0;
        $thisMonthTotalCount  = $thisMonthData['total_transfer'];

        if ($thisMonthTotalAmount + $amount > $setting->monthly_maximum_limit) {
            return addCustomValidation($validator, 'error', 'Sorry you are exceeding the monthly transfer limit');
        }

        if ($thisMonthTotalCount > $setting->monthly_total_transaction) {
            return addCustomValidation($validator, 'error', 'Sorry you have already reached the monthly transfer limit of ' . $setting->monthly_total_transaction . 'times');
        }
    }

    private function charge($amount, $setting) {
        $percentCharge = $amount * $setting->percent_charge / 100;
        return $setting->fixed_charge + $percentCharge;
    }

    public function confirm($id) {
        $verification = OtpVerification::find($id);
        if (!$verification) {
            $notify[] = 'Verification not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $setting   = $verification->verifiable;
        $amount    = $verification->additional_data->amount;
        $user      = auth()->user();
        $validator = Validator::make(request()->all(), []);

        OTPManager::checkVerificationData($verification, WireTransferSetting::class, true, $validator);
        $this->checkTransferAvailability($amount, $setting, $validator);

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

        $notify[] = "Transfer request sent successfully";
        return response()->json([
            'remark'  => 'validation_error',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function details($id) {
        $transfer = BalanceTransfer::wireTransfer()->where('user_id', auth()->id())->where('id', $id)->first();
        if (!$transfer) {
            $notify[] = "Wire Transfer not found";
            return response()->json([
                'remark'  => 'transfer_not_found',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $data     = @$transfer->wire_transfer_data;
        $html     = view('components.view-form-data', compact('data'))->render();
        $notify[] = 'Transfer Detail';
        return response()->json([
            'remark'  => 'transfer_detail',
            'status'  => 'error',
            'message' => ['error' => $notify],
            'data'    => [
                'html' => $html,
            ],
        ]);
    }
}
