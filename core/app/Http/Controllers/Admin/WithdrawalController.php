<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\BranchStaff;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller {

    public function pending($userId = null) {
        $pageTitle = 'Pending Withdrawals';
        $withdrawals = $this->withdrawalData('pending', userId: $userId);
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function approved($userId = null) {
        $pageTitle = 'Approved Withdrawals';
        $withdrawals = $this->withdrawalData('approved', userId: $userId);
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function rejected($userId = null) {
        $pageTitle = 'Rejected Withdrawals';
        $withdrawals = $this->withdrawalData('rejected', userId: $userId);
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function all($userId = null) {
        $pageTitle = 'All Withdrawals';
        $withdrawalData = $this->withdrawalData($scope = null, $summary = true, userId: $userId);
        $withdrawals = $withdrawalData['data'];
        $summary = $withdrawalData['summary'];
        $successful = $summary['successful'];
        $pending = $summary['pending'];
        $rejected = $summary['rejected'];

        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals', 'successful', 'pending', 'rejected'));
    }

    protected function withdrawalData($scope = null, $summary = false, $userId = null) {

        $methodName = 'CASE WHEN withdrawals.branch_id != 0 THEN "Branch Withdrawal" ELSE withdraw_methods.name END';
        $branchName = 'CASE WHEN withdrawals.branch_id != 0 THEN branches.name ELSE "Online" END';
        $staffName = 'CASE WHEN withdrawals.branch_staff_id != 0 THEN branch_staff.name ELSE "N/A" END';

        $withdrawals = Withdrawal::where('withdrawals.status', '!=', Status::PAYMENT_INITIATE)->selectRaw('
            withdrawals.*,
            withdrawals.amount - withdrawals.charge AS total_amount,
            users.account_number AS account_number,
            ' . $methodName . ' AS method_name,
            ' . $branchName . ' AS branch_name,
            ' . $staffName . ' AS staff_name
        ')
        ->leftJoin('users', 'withdrawals.user_id', '=', 'users.id')
        ->leftJoin('withdraw_methods', 'withdrawals.method_id', '=', 'withdraw_methods.id')
        ->leftJoin('branches', 'withdrawals.branch_id', '=', 'branches.id')
        ->leftJoin('branch_staff', 'withdrawals.branch_staff_id', '=', 'branch_staff.id')
        ->searchable([
            'trx',
            'account_number',
            'user:username',
            $methodName,
            $branchName
        ]);

        if ($scope) {
            $withdrawals->$scope();
        }


        if(request()->has('username')) {
            $withdrawals->where('users.username', request()->username);
        }

        if(request()->has('branch_id')) {
            $withdrawals->where('withdrawals.branch_id', request()->branch_id);
        }

        if(request()->has('staff_id')) {
            $withdrawals->where('withdrawals.branch_staff_id', request()->staff_id);
        }

        if ($userId) {
            $withdrawals = $withdrawals->where('user_id', $userId);
        }

        $withdrawals = $withdrawals->dateFilter();

        $request = request();

        if (request()->has('branch')) {
            $withdrawals->where('branch_id', $request->branch);
        }

        if (request()->has('staff')) {
            $staff = BranchStaff::findOrFail($request->staff);
            if ($staff->designation == Status::ROLE_MANAGER) {
                $withdrawals->whereIn('branch_id', $staff->branch_id);
            } else {
                $withdrawals->where('branch_staff_id', $request->staff);
            }
        }

        if ($request->method) {
            $withdrawals = $withdrawals->where('method_id', $request->method);
        }

        $withdrawals->filterable()->orderable();

        if (!$summary) {
            return $withdrawals->dynamicPaginate();
        } else {

            $successful = clone $withdrawals;
            $pending = clone $withdrawals;
            $rejected = clone $withdrawals;

            $successfulSummary = $successful->where('withdrawals.status', Status::PAYMENT_SUCCESS)->sum('amount');
            $pendingSummary = $pending->where('withdrawals.status', Status::PAYMENT_PENDING)->sum('amount');
            $rejectedSummary = $rejected->where('withdrawals.status', Status::PAYMENT_REJECT)->sum('amount');

            return [
                'data' => $withdrawals->dynamicPaginate(),
                'summary' => [
                    'successful' => $successfulSummary,
                    'pending' => $pendingSummary,
                    'rejected' => $rejectedSummary,
                ]
            ];
        }
    }

    public function details($id) {
        $withdrawal = Withdrawal::where('id', $id)->where('status', '!=', Status::PAYMENT_INITIATE)->with(['user', 'method'])->firstOrFail();
        $pageTitle = 'Withdrawal Details';
        $details = $withdrawal->withdraw_information ? json_encode($withdrawal->withdraw_information) : null;

        return view('admin.withdraw.detail', compact('pageTitle', 'withdrawal', 'details'));
    }

    public function approve(Request $request) {
        $request->validate(['id' => 'required|integer']);
        $withdraw = Withdrawal::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->with('user')->firstOrFail();
        $withdraw->status = Status::PAYMENT_SUCCESS;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        notify($withdraw->user, 'WITHDRAW_APPROVE', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount' => showAmount($withdraw->amount, currencyFormat: false),
            'charge' => showAmount($withdraw->charge, currencyFormat: false),
            'rate' => showAmount($withdraw->rate, currencyFormat: false),
            'trx' => $withdraw->trx,
            'admin_details' => $request->details
        ]);

        $notify[] = ['success', 'Withdrawal approved successfully'];
        return to_route('admin.withdraw.data.pending')->withNotify($notify);
    }


    public function reject(Request $request) {
        $request->validate(['id' => 'required|integer']);
        $withdraw = Withdrawal::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->with('user')->firstOrFail();

        $withdraw->status = Status::PAYMENT_REJECT;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        $user = $withdraw->user;
        $user->balance += $withdraw->amount;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $withdraw->user_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '+';
        $transaction->remark = 'withdraw_refund';
        $transaction->details = 'Refunded for withdrawal rejection';
        $transaction->trx = $withdraw->trx;
        $transaction->save();

        notify($user, 'WITHDRAW_REJECT', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount' => showAmount($withdraw->amount, currencyFormat: false),
            'charge' => showAmount($withdraw->charge, currencyFormat: false),
            'rate' => showAmount($withdraw->rate, currencyFormat: false),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($user->balance, currencyFormat: false),
            'admin_details' => $request->details
        ]);

        $notify[] = ['success', 'Withdrawal rejected successfully'];
        return to_route('admin.withdraw.data.pending')->withNotify($notify);
    }
}
