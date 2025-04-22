<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\BalanceTransfer;
use App\Models\OtherBank;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class MoneyTransferController extends Controller
{
    private $pageTitle;

    public function index($userId = null)
    {
        $this->pageTitle = 'All Transfer';
        return $this->transferData(userId: $userId);
    }

    public function pending($userId = null)
    {
        $this->pageTitle = 'Pending Transfers';
        return $this->transferData('pending', userId: $userId);
    }

    public function rejected($userId = null)
    {
        $this->pageTitle = 'Rejected Transfers';
        return $this->transferData('rejected', userId: $userId);
    }

    public function ownBank()
    {
        $this->pageTitle = 'Own Bank Transfers';
        return $this->transferData('ownBank');
    }

    public function otherBank()
    {
        $this->pageTitle = 'Other Bank Transfers';
        return $this->transferData('otherBank');
    }

    public function wireTransfer()
    {
        $this->pageTitle = 'Wire Transfers';
        return $this->transferData('wireTransfer');
    }

    protected function transferData($scope = null, $userId = null)
    {
        $pageTitle      = $this->pageTitle;
        $senderColumn   = 'users.account_number';
        $receiverColumn = 'CASE WHEN balance_transfers.beneficiary_id = 0 THEN JSON_UNQUOTE(JSON_EXTRACT(wire_transfer_data, "$[1].value")) ELSE beneficiaries.account_number END';

        $receiverBankColumn = 'CASE WHEN balance_transfers.beneficiary_id = 0 THEN "Wire Transfer" WHEN beneficiaries.beneficiary_type = ' . json_encode(User::class) . ' THEN ? WHEN beneficiaries.beneficiary_type = ' . json_encode(OtherBank::class) . ' THEN other_banks.name END';

        $finalAmountColumn = '(balance_transfers.amount + balance_transfers.charge)';

        $query = BalanceTransfer::searchable(['trx', $senderColumn, $receiverColumn]);

        if ($scope) {
            $query = $query->$scope();
        }

        $transfers = $query->selectRaw('balance_transfers.*, ' . $senderColumn . ' AS sender, ' . $receiverColumn . ' AS receiver,
        ' . $finalAmountColumn . '  AS final_amount, beneficiaries.beneficiary_type,
        ' . $receiverBankColumn . ' AS receiver_bank ', [gs('site_name')])

            ->leftJoin('users', 'balance_transfers.user_id', '=', 'users.id')
            ->leftJoin('beneficiaries', 'balance_transfers.beneficiary_id', '=', 'beneficiaries.id')
            ->leftJoin('users as users_beneficiary', function ($join) {
                $join->on('beneficiaries.beneficiary_id', '=', 'users_beneficiary.id')->where('beneficiaries.beneficiary_type', '=', User::class);
            })
            ->leftJoin('other_banks', function ($join) {
                $join->on('beneficiaries.beneficiary_id', '=', 'other_banks.id')->where('beneficiaries.beneficiary_type', OtherBank::class);
            })
            ->filterable()
            ->orderable();

        if ($userId) {
            $transfers = $transfers->where('balance_transfers.user_id', $userId);
        }


        if(request()->has('username')) {
            $transfers->where('users.username', request()->username);
        }


        $transfers = $transfers->dynamicPaginate();

        return view('admin.transfers.index', compact('pageTitle', 'transfers'));
    }

    public function details($id)
    {
        $transfer  = BalanceTransfer::where('id', $id)->with('user', 'beneficiary.beneficiaryOf')->firstOrFail();
        $pageTitle = 'Transfer Details';
        return view('admin.transfers.details', compact('pageTitle', 'transfer'));
    }

    public function complete($id)
    {
        $transfer = BalanceTransfer::where('id', $id)->with('beneficiary.beneficiaryOf')->firstOrFail();

        if ($transfer->status == Status::TRANSFER_COMPLETED) {
            $notify[] = ['error', 'This transfer has already been completed'];
            return back()->withNotify($notify);
        }

        $transfer->status = Status::TRANSFER_COMPLETED;
        $transfer->save();

        if ($transfer->beneficiary_id) {
            $shortCodes = $this->bankTransferShortCodes($transfer);
            $template   = 'OTHER_BANK_TRANSFER_COMPLETE';
        } else {
            $shortCodes = $this->wireTransferShortCodes($transfer);
            $template   = 'WIRE_TRANSFER_COMPLETED';
        }

        notify($transfer->user, $template, $shortCodes);

        $notify[] = ['success', 'Transfer completed successfully'];
        return back()->withNotify($notify);
    }

    public function reject(Request $request)
    {

        $request->validate([
            'reject_reason' => 'required',
            'id'            => 'required',
        ]);

        $transfer = BalanceTransfer::where('id', $request->id)->with('user', 'beneficiary.beneficiaryOf')->firstOrFail();

        if ($transfer->status != Status::TRANSFER_PENDING) {
            $notify[] = ['error', 'This transfer can\'t be rejected'];
            return back()->withNotify($notify);
        }

        $transfer->status        = Status::TRANSFER_REJECTED;
        $transfer->reject_reason = $request->reject_reason;
        $transfer->save();

        $user = $transfer->user;
        $user->balance += $transfer->final_amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $transfer->final_amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->remark       = 'transfer_amount_refund';
        $transaction->details      = 'Transferred amount refunded';
        $transaction->trx          = $transfer->trx;
        $transaction->save();

        if ($transfer->beneficiary_id) {
            $shortCodes = $this->bankTransferShortCodes($transfer);
            $template   = 'OTHER_BANK_TRANSFER_REJECT';
        } else {
            $shortCodes = $this->wireTransferShortCodes($transfer);
            $template   = 'WIRE_TRANSFER_REJECTED';
        }

        notify($transfer->user, $template, $shortCodes);

        $notify[] = ['success', 'Transfer rejected successfully'];
        return back()->withNotify($notify);
    }

    private function bankTransferShortCodes($transfer)
    {
        $bank = $transfer->beneficiary->beneficiaryOf;
        return [
            "sender_account_number"    => $transfer->user->account_number,
            "sender_account_name"      => $transfer->user->username,
            "recipient_account_number" => $transfer->beneficiary->account_number,
            "recipient_account_name"   => $transfer->beneficiary->account_name,
            "sending_amount"           => showAmount($transfer->amount, currencyFormat: false),
            "charge"                   => showAmount($transfer->charge, currencyFormat: false),
            "final_amount"             => showAmount($transfer->final_amount, currencyFormat: false),
            "bank_name"                => $bank->name,
            "reject_reason"            => $transfer->reject_reason,
        ];
    }

    private function wireTransferShortCodes($transfer)
    {
        $accountName   = $transfer->wireTransferAccountName();
        $accountNumber = $transfer->wireTransferAccountNumber();

        return [
            "sender_account_number"    => $transfer->user->account_number,
            "sender_account_name"      => $transfer->user->username,
            "recipient_account_number" => $accountNumber,
            "recipient_account_name"   => $accountName,
            "sending_amount"           => showAmount($transfer->amount, currencyFormat: false),
            "charge"                   => showAmount($transfer->charge, currencyFormat: false),
            "final_amount"             => showAmount($transfer->final_amount, currencyFormat: false),
            "reject_reason"            => $transfer->reject_reason,
        ];
    }
}
