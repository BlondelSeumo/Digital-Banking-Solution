<?php

namespace App\Http\Controllers\User;

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
use Illuminate\Validation\ValidationException;

class OtherBankTransferController extends Controller
{

    public function beneficiaries()
    {
        $beneficiaries = Beneficiary::where('user_id', auth()->id())->where('beneficiary_type', OtherBank::class)->with('beneficiaryOf')->paginate(getPaginate());
        $pageTitle     = 'Transfer Money to Other Bank';
        return view('Template::user.transfer.other_bank.beneficiaries', compact('pageTitle', 'beneficiaries'));
    }

    public function transferRequest(Request $request, $id)
    {
        $beneficiary = Beneficiary::where('user_id', auth()->id())->with('beneficiaryOf')->findOrFail($id);

        $this->validation($request, $beneficiary);
        $this->checkTransferAvailability($request->amount, $beneficiary->beneficiaryOf);

        $additionalData = [
            'amount'         => $request->amount,
            'after_verified' => 'user.transfer.other.bank.confirm',
        ];

        $otpManager = new OTPManager();

        return $otpManager->newOTP($beneficiary, $request->auth_mode, 'OTHER_BANK_TRANSFER_OTP', $additionalData);
    }

    private function validation($request, $beneficiary)
    {
        if ($beneficiary->beneficiary_type != OtherBank::class) {
            throw ValidationException::withMessages(['error' => 'Invalid beneficiary selected']);
        }
        $rules = ['amount' => "required|numeric|gt:0"];
        $rules = mergeOtpField($rules);
        $request->validate($rules);
    }

    public function confirm()
    {

        $verification = OtpVerification::find(sessionVerificationId());
        $beneficiary  = $verification->verifiable;

        OTPManager::checkVerificationData($verification, Beneficiary::class);

        if ($beneficiary->beneficiary_type != OtherBank::class) {
            $notify[] = ['error', 'Invalid session data'];
            return to_route('user.home')->withNotify($notify);
        }

        $bank   = $beneficiary->beneficiaryOf;
        $sender = auth()->user();
        $amount = $verification->additional_data->amount;
        $this->checkTransferAvailability($amount, $bank);

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
        $transaction->amount       = $finalAmount;
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

        session()->forget('otp_id');

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

        $notify[] = ['success', "Request submitted successfully"];
        return to_route('user.transfer.details', $transfer->trx)->withNotify($notify);
    }

    private function checkTransferAvailability($amount, $bank)
    {

        if($bank->status == 0) {
            throw ValidationException::withMessages(['error' => 'Sorry! transfers to ' . $bank->name . ' are currently unavailable.']);
        }

        $user        = auth()->user();
        $charge      = $this->charge($amount, $bank);
        $finalAmount = $amount + $charge;

        if ($user->balance < $finalAmount) {
            throw ValidationException::withMessages(['error' => 'Sorry! You don\'t have sufficient balance']);
        }

        if ($amount < $bank->minimum_limit) {
            throw ValidationException::withMessages(['error' => 'Sorry minimum transfer limit is ' . showAmount($bank->minimum_limit)]);
        }
        if ($amount > $bank->maximum_limit) {
            throw ValidationException::withMessages(['error' => 'Sorry maximum transfer limit is ' . showAmount($bank->maximum_limit)]);
        }

        $todaysData = BalanceTransfer::otherBank()
            ->notRejected()
            ->where('user_id', $user->id)
            ->whereDate('created_at', now())
            ->selectRaw('count(id) as total_transfer, sum(amount) as total_amount')
            ->first();

        $todaysTotalAmount = $todaysData['total_amount'] ?? 0;
        $todaysTotalCount  = $todaysData['total_transfer'];

        if ($todaysTotalAmount + $amount > $bank->daily_maximum_limit) {
            throw ValidationException::withMessages(['error' => 'Sorry you are exceeding the daily transfer limit']);
        }

        if ($todaysTotalCount > $bank->daily_total_transaction) {
            throw ValidationException::withMessages(['error' => 'Sorry you have already reached the daily transfer limit of ' . $bank->daily_total_transaction . 'times']);
        }

        $thisMonthData = BalanceTransfer::otherBank()
            ->notRejected()
            ->where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('count(id) as total_transfer, sum(amount) as total_amount')
            ->first();

        $thisMonthTotalAmount = $thisMonthData['total_amount'] ?? 0;
        $thisMonthTotalCount  = $thisMonthData['total_transfer'];

        if ($thisMonthTotalAmount + $amount > $bank->monthly_maximum_limit) {
            throw ValidationException::withMessages(['error' => 'Sorry you are exceeding the monthly transfer limit']);
        }

        if ($thisMonthTotalCount > $bank->monthly_total_transaction) {
            throw ValidationException::withMessages(['error' => 'Sorry you have already reached the monthly transfer limit of ' . $bank->monthly_total_transaction . 'times']);
        }
    }

    private function charge($amount, $bank)
    {
        $percentCharge = $amount * $bank->percent_charge / 100;
        return $bank->fixed_charge + $percentCharge;
    }
}
