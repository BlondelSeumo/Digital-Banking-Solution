<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\BranchStaff;
use Illuminate\Http\Request;

class DepositController extends Controller {


    public function pending($userId = null) {
        $pageTitle = 'Pending Deposits';
        $deposits = $this->depositData('pending', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function approved($userId = null) {
        $pageTitle = 'Approved Deposits';
        $deposits = $this->depositData('approved', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function successful($userId = null) {
        $pageTitle = 'Successful Deposits';
        $deposits = $this->depositData('successful', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function rejected($userId = null) {
        $pageTitle = 'Rejected Deposits';
        $deposits = $this->depositData('rejected', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function initiated($userId = null) {
        $pageTitle = 'Initiated Deposits';
        $deposits = $this->depositData('initiated', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function deposit($userId = null) {
        $pageTitle = 'Deposit History';
        $depositData = $this->depositData($scope = null, $summary = true, userId: $userId);
        $deposits = $depositData['data'];
        $summary = $depositData['summary'];
        $successful = $summary['successful'];
        $pending = $summary['pending'];
        $rejected = $summary['rejected'];
        $initiated = $summary['initiated'];
        return view('admin.deposit.log', compact('pageTitle', 'deposits', 'successful', 'pending', 'rejected', 'initiated'));
    }

    protected function depositData($scope = null, $summary = false, $userId = null) {
        $gatewayName = 'CASE WHEN deposits.branch_id != 0 THEN "Branch Deposit" WHEN deposits.method_code < 5000 THEN gateway_currencies.name ELSE "Google Pay" END';
        $branchName = 'CASE WHEN deposits.branch_id != 0 THEN branches.name ELSE "Online" END';
        $staffName = 'CASE WHEN deposits.branch_staff_id != 0 THEN branch_staff.name ELSE "N/A" END';

        $deposits = Deposit::selectRaw('
            deposits.*,
            deposits.amount + deposits.charge AS total_amount,
            users.account_number AS account_number,
            '.$gatewayName.' AS gateway_name,
            '.$branchName. ' AS branch_name,
            '.$staffName .' AS staff_name
        ')
        ->leftJoin('users', 'deposits.user_id', '=', 'users.id')
        ->leftJoin('gateway_currencies', 'deposits.method_code', '=', 'gateway_currencies.method_code')
        ->leftJoin('branches', 'deposits.branch_id', '=', 'branches.id')
        ->leftJoin('branch_staff', 'deposits.branch_staff_id', '=', 'branch_staff.id')
        ->searchable([
            'trx', 'account_number',
            $gatewayName,
            $branchName
        ]);

        if ($scope) {
            $deposits = $deposits->$scope();
        }

        if(request()->has('username')) {
            $deposits->where('users.username', request()->username);
        }

        if(request()->has('branch_id')) {
            $deposits->where('deposits.branch_id', request()->branch_id);
        }

        if(request()->has('staff_id')) {
            $deposits->where('deposits.branch_staff_id', request()->staff_id);
        }

        if ($userId) {
            $deposits = $deposits->where('user_id', $userId);
        }

        $request = request();

        if (request()->has('staff')) {
            $staff = BranchStaff::findOrFail($request->staff);

            if ($staff->designation == Status::ROLE_MANAGER) {
                $deposits->whereIn('branch_id', $staff->branch_id);
            } else {
                $deposits->where('branch_staff_id', $request->staff);
            }
        }

        if ($request->method) {
            if ($request->method != Status::GOOGLE_PAY) {
                $method = Gateway::where('alias', $request->method)->firstOrFail();
                $deposits = $deposits->where('method_code', $method->code);
            } else {
                $deposits = $deposits->where('method_code', Status::GOOGLE_PAY);
            }
        }

        $deposits->filterable()->orderable();

        if (!$summary) {
            return $deposits->dynamicPaginate();
        } else {
            $successful = clone $deposits;
            $pending = clone $deposits;
            $rejected = clone $deposits;
            $initiated = clone $deposits;

            $successfulSummary = $successful->where('deposits.status', Status::PAYMENT_SUCCESS)->sum('amount');
            $pendingSummary = $pending->where('deposits.status', Status::PAYMENT_PENDING)->sum('amount');
            $rejectedSummary = $rejected->where('deposits.status', Status::PAYMENT_REJECT)->sum('amount');
            $initiatedSummary = $initiated->where('deposits.status', Status::PAYMENT_INITIATE)->sum('amount');

            return [
                'data' => $deposits->dynamicPaginate(),
                'summary' => [
                    'successful' => $successfulSummary,
                    'pending' => $pendingSummary,
                    'rejected' => $rejectedSummary,
                    'initiated' => $initiatedSummary,
                ]
            ];
        }
    }

    public function details($id) {
        $deposit = Deposit::where('id', $id)->with(['user', 'gateway'])->firstOrFail();
        $pageTitle = $deposit->user->username . ' requested ' . showAmount($deposit->amount);
        $details = ($deposit->detail != null) ? json_encode($deposit->detail) : null;
        return view('admin.deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }


    public function approve($id) {
        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        PaymentController::userDataUpdate($deposit, true);

        $notify[] = ['success', 'Deposit request approved successfully'];

        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request) {
        $request->validate([
            'id' => 'required|integer',
            'message' => 'required|string|max:255'
        ]);
        $deposit = Deposit::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        $deposit->admin_feedback = $request->message;
        $deposit->status = Status::PAYMENT_REJECT;
        $deposit->save();

        notify($deposit->user, 'DEPOSIT_REJECT', [
            'method_name' => $deposit->methodName(),
            'method_currency' => $deposit->method_currency,
            'method_amount' => showAmount($deposit->final_amount, currencyFormat: false),
            'amount' => showAmount($deposit->amount, currencyFormat: false),
            'charge' => showAmount($deposit->charge, currencyFormat: false),
            'rate' => showAmount($deposit->rate, currencyFormat: false),
            'trx' => $deposit->trx,
            'rejection_message' => $request->message
        ]);

        $notify[] = ['success', 'Deposit request rejected successfully'];
        return  to_route('admin.deposit.pending')->withNotify($notify);
    }
}
