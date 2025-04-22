<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\OTPManager;
use App\Models\BalanceTransfer;
use App\Models\Beneficiary;
use App\Models\OtpVerification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OwnBankTransferController extends Controller
{

    public function beneficiaries()
    {
        $beneficiaries = Beneficiary::where('user_id', auth()->id())->where('beneficiary_type', User::class)->paginate(getPaginate());
        $pageTitle     = 'Transfer Money Within ' . gs('site_name');
        return view('Template::user.transfer.own_bank.beneficiaries', compact('pageTitle', 'beneficiaries'));
    }

    public function transferRequest(Request $request, $id)
    {
        $beneficiary = Beneficiary::where('user_id', auth()->id())->findOrFail($id);
        $this->validation($request, $beneficiary);
        $this->checkTransferAvailability($request->amount);

        $additionalData = [
            'amount'         => $request->amount,
            'after_verified' => 'user.transfer.own.bank.confirm',
        ];

        $otpManager = new OTPManager();
        return $otpManager->newOTP($beneficiary, $request->auth_mode, 'OWN_BANK_TRANSFER_OTP', $additionalData);
    }

    public function confirm()
    {
        $verification = OtpVerification::find(sessionVerificationId());
        $beneficiary  = $verification->verifiable;

        OTPManager::checkVerificationData($verification, Beneficiary::class);

        if ($beneficiary->beneficiary_type != User::class) {
            $notify[] = ['error', 'Invalid session data'];
            return to_route('user.home')->withNotify($notify);
        }

        $sender = auth()->user();
        $amount = $verification->additional_data->amount;

        $this->checkTransferAvailability($amount);

        $general     = gs();
        $charge      = $this->charge($amount);
        $finalAmount = $amount + $charge;

        $transfer                 = new BalanceTransfer();
        $transfer->user_id        = $sender->id;
        $transfer->trx            = getTrx();
        $transfer->beneficiary_id = $beneficiary->id;
        $transfer->amount         = $amount;
        $transfer->charge         = $charge;
        $transfer->status         = Status::TRANSFER_COMPLETED;
        $transfer->save();

        $sender->balance -= $finalAmount;
        $sender->save();

        $this->sendingTransaction($transfer, $sender); // Insert Sending Transaction

        $recipient = $beneficiary->beneficiaryOf;
        $recipient->balance += $transfer->amount;
        $recipient->save();

        $this->receivingTransaction($transfer, $recipient); // Insert Receiving Transaction

        $shortCodes = $this->shortCodes($transfer, $sender, $recipient, $sender->balance);
        notify($sender, 'OWN_BANK_TRANSFER_MONEY_SEND', $shortCodes);

        $shortCodes = $this->shortCodes($transfer, $sender, $recipient, $recipient->balance);
        notify($recipient, 'OWN_BANK_TRANSFER_MONEY_RECEIVE', $shortCodes);

        session()->forget('otp_id');

        $notify[] = ['success', "$transfer->amount " . gs('cur_text') . " transferred successfully"];

        return to_route('user.transfer.details', $transfer->trx)->withNotify($notify);
    }

    private function sendingTransaction($transfer, $user)
    {
        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $transfer->final_amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = $transfer->charge;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Own bank transfer';
        $transaction->trx          = $transfer->trx;
        $transaction->remark       = "own_bank_transfer";
        $transaction->save();
    }

    private function receivingTransaction($transfer, $user)
    {
        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $transfer->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Received transferred money';
        $transaction->remark       = 'received_money';
        $transaction->trx          = $transfer->trx;
        $transaction->save();
    }

    private function checkTransferAvailability($amount)
    {

        $finalAmount = $amount + $this->charge($amount);
        $user        = auth()->user();

        if ($amount < gs('minimum_transfer_limit')) {
            throw ValidationException::withMessages(['error' => 'Sorry minimum transfer limit is ' . showAmount(gs('minimum_transfer_limit'))]);
        }

        if ($user->balance < $finalAmount) {
            throw ValidationException::withMessages(['error' => 'Sorry! You don\'t have sufficient balance']);
        }

        $todaysTotal = BalanceTransfer::completed()->where('user_id', $user->id)->ownBank()->whereDate('created_at', now())->sum('amount');

        if ($todaysTotal + $amount > gs('daily_transfer_limit')) {
            throw ValidationException::withMessages(['error' => 'Sorry you are exceeding the daily transfer limit']);
        }

        $thisMonthTotal = BalanceTransfer::completed()->where('user_id', $user->id)->ownBank()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');

        if ($thisMonthTotal + $amount > gs('monthly_transfer_limit')) {
            throw ValidationException::withMessages(['error' => 'Sorry you are exceeding the monthly transfer limit']);
        }
    }

    private function charge($amount)
    {
        $percentCharge = $amount * gs('percent_transfer_charge') / 100;
        return gs('fixed_transfer_charge') + $percentCharge;
    }

    private function validation($request, $beneficiary)
    {
        if ($beneficiary->beneficiary_type != User::class) {
            throw ValidationException::withMessages(['error' => 'Invalid beneficiary selected']);
        }

        $rules = ['amount' => "required|numeric|gt:0"];
        $rules = mergeOtpField($rules);
        $request->validate($rules);
    }

    private function shortCodes($transfer, $sender, $recipient, $postBalance)
    {
        return [
            'sender'       => $sender->username,
            'recipient'    => $recipient->username,
            'amount'       => showAmount($transfer->amount,currencyFormat:false),
            'charge'       => showAmount($transfer->charge,currencyFormat:false),
            'final_amount' => showAmount($transfer->final_amount,currencyFormat:false),
            'trx'          => $transfer->trx,
            'post_balance' => showAmount($postBalance,currencyFormat:false),
        ];
    }
}
