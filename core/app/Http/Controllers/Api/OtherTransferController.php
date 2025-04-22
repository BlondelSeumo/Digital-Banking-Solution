<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Models\AdminNotification;
use App\Models\BalanceTransfer;
use App\Models\Beneficiary;
use App\Models\OtherBank;
use App\Models\OtpVerification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherTransferController extends Controller {
    public function transferRequest(Request $request, $id) {
        $beneficiary = Beneficiary::where('user_id', auth()->id())->with('beneficiaryOf')->find($id);
        if (!$beneficiary) {
            $notify[] = 'Beneficiary not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $validator = $this->validation($request, $beneficiary);
        $this->checkTransferAvailability($request->amount, $beneficiary->beneficiaryOf, $validator);
        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $additionalData = [
            'amount'         => $request->amount,
            'after_verified' => 'api.other.transfer.confirm',
        ];

        $otpManager = new OTPManager();

        return $otpManager->newOTP($beneficiary, $request->auth_mode, 'OTHER_BANK_TRANSFER_OTP', $additionalData, true);
    }

    private function validation($request, $beneficiary) {
        $rules     = ['amount' => "required|numeric|gt:0"];
        $rules     = mergeOtpField($rules);
        $validator = Validator::make($request->all(), $rules);

        if ($beneficiary->beneficiary_type != OtherBank::class) {
            return addCustomValidation($validator, 'balance', 'Invalid beneficiary selected');
        }
        return $validator;
    }

    private function checkTransferAvailability($amount, $bank, $validator) {

        if($bank->status == 0) {
            return addCustomValidation($validator, 'error', 'Sorry! transfers to ' . $bank->name . ' are currently unavailable.');
        }

        $user        = auth()->user();
        $charge      = $this->charge($amount, $bank);
        $finalAmount = $amount + $charge;

        if ($user->balance < $finalAmount) {
            return addCustomValidation($validator, 'error', 'Sorry! You don\'t have sufficient balance');
        }

        if ($amount < $bank->minimum_limit) {
            return addCustomValidation($validator, 'error', 'Sorry minimum transfer limit is ' . showAmount($bank->minimum_limit));

        }
        if ($amount > $bank->maximum_limit) {
            return addCustomValidation($validator, 'error', 'Sorry maximum transfer limit is ' . showAmount($bank->maximum_limit));
        }

        $todaysData = BalanceTransfer::otherBank()
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

        if ($todaysTotalAmount + $amount > $bank->daily_maximum_limit) {
            return addCustomValidation($validator, 'error', 'Sorry you are exceeding the daily transfer limit');
        }
        if ($todaysTotalCount > $bank->daily_total_transaction) {
            return addCustomValidation($validator, 'error', 'Sorry you have already reached the daily transfer limit of ' . $bank->daily_total_transaction . 'times');
        }

        $thisMonthData = BalanceTransfer::otherBank()
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

        if ($thisMonthTotalAmount + $amount > $bank->monthly_maximum_limit) {
            return addCustomValidation($validator, 'error', 'Sorry you are exceeding the monthly transfer limit');
        }

        if ($thisMonthTotalCount > $bank->monthly_total_transaction) {
            return addCustomValidation($validator, 'error', 'Sorry you have already reached the monthly transfer limit of ' . $bank->monthly_total_transaction . 'times');
        }
    }

    private function charge($amount, $bank) {
        $percentCharge = $amount * $bank->percent_charge / 100;
        return $bank->fixed_charge + $percentCharge;
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
        $beneficiary = $verification->verifiable;

        $validator = Validator::make(request()->all(), []);
        OTPManager::checkVerificationData($verification, Beneficiary::class, true, $validator);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        if ($beneficiary->beneficiary_type != OtherBank::class) {
            $notify[] = ['error', 'Invalid session data'];
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $bank   = $beneficiary->beneficiaryOf;
        $sender = auth()->user();
        $amount = $verification->additional_data->amount;
        $this->checkTransferAvailability($amount, $bank, $validator);

        $charge      = $this->charge($amount, $bank);
        $finalAmount = $amount + $charge;

        $transfer                 = new BalanceTransfer();
        $transfer->user_id        = $sender->id;
        $transfer->trx            = getTrx();
        $transfer->beneficiary_id = $beneficiary->id;
        $transfer->amount         = $amount;
        $transfer->charge         = $charge;
        $transfer->status         = Status::TRANSFER_PENDING;
        $transfer->save();

        $sender->balance -= $finalAmount;
        $sender->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $sender->id;
        $transaction->post_balance = $sender->balance;
        $transaction->amount       = $transfer->amount;
        $transaction->charge       = $transfer->charge;
        $transaction->trx          = $transfer->trx;
        $transaction->trx_type     = '-';
        $transaction->remark       = "other_bank_transfer";
        $transaction->details      = 'Other bank transfer';
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $sender->id;
        $adminNotification->title     = 'New bank transfer request';
        $adminNotification->click_url = urlPath('admin.transfers.details', $transfer->id);
        $adminNotification->save();

        notify($sender, 'OTHER_BANK_TRANSFER_REQUEST_SEND', [
            "sender_account_number"    => $transfer->user->account_number,
            "sender_account_name"      => $transfer->user->username,
            "recipient_account_number" => $transfer->beneficiary->account_number,
            "recipient_account_name"   => $transfer->beneficiary->account_name,
            "sending_amount"           => $transfer->amount,
            "charge"                   => $transfer->charge,
            "final_amount"             => $finalAmount,
            "bank_name"                => $bank->name,
        ]);

        $notify[] = "Request submitted successfully";
        return response()->json([
            'remark'  => 'other_transferred',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }
}
