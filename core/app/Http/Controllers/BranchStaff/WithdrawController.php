<?php

namespace App\Http\Controllers\BranchStaff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WithdrawController extends Controller
{

    public function withdrawals()
    {
        $staff       = authStaff();
        $withdrawals = Withdrawal::approved()->where('branch_id', session('branchId'));

        if ($staff->designation == Status::ROLE_ACCOUNT_OFFICER) {
            $pageTitle = 'All Withdrawals by ' . $staff->name;
            $withdrawals->where('branch_staff_id', $staff->id);
        }else{
            $branch = Branch::active()->findOrFail(session('branchId'));
            $pageTitle = 'All Withdrawals from ' . $branch->name . ' Branch';
        }

        $withdrawals = $withdrawals->searchable(['trx', 'user:account_number', 'branchStaff:name'])->dateFilter()->with('user', 'branchStaff')->latest()->paginate(getPaginate());
        return view('branch_staff.withdrawals', compact('pageTitle', 'withdrawals', 'staff'));
    }

    public function save(Request $request, $accountNumber)
    {

        $user = User::where('account_number', $accountNumber)->firstOrFail();
        $this->validation($request, $user);

        $amount = $request->amount;
        $staff  = authStaff();
        $branch = $staff->branch();

        $withdraw                  = new Withdrawal();
        $withdraw->method_id       = 0;
        $withdraw->user_id         = $user->id;
        $withdraw->amount          = $amount;
        $withdraw->currency        = gs('cur_text');
        $withdraw->rate            = 1;
        $withdraw->charge          = 0;
        $withdraw->final_amount    = $amount;
        $withdraw->after_charge    = $amount;
        $withdraw->status          = 1;
        $withdraw->branch_id       = $branch->id;
        $withdraw->branch_staff_id = $staff->id;
        $withdraw->trx             = getTrx();
        $withdraw->save();

        $user->balance -= $withdraw->amount;
        $user->save();

        $transaction                  = new Transaction();
        $transaction->user_id         = $withdraw->user_id;
        $transaction->amount          = $withdraw->amount;
        $transaction->post_balance    = $user->balance;
        $transaction->charge          = $withdraw->charge;
        $transaction->trx_type        = '-';
        $transaction->details         = showAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via Branch';
        $transaction->trx             = $withdraw->trx;
        $transaction->branch_id       = $branch->id;
        $transaction->branch_staff_id = $staff->id;
        $transaction->remark          = 'withdraw';
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw from ' . $branch->name;
        $adminNotification->click_url = urlPath('admin.withdraw.data.details', $withdraw->id) . '?search=' . $withdraw->trx;
        $adminNotification->save();

        notify($user, 'WITHDRAW_VIA_BRANCH', [
            'branch_name'  => @$branch->name,
            'amount'       => showAmount($withdraw->amount,currencyFormat:false),
            'charge'       => showAmount($withdraw->charge,currencyFormat:false),
            'trx'          => $withdraw->trx,
            'post_balance' => showAmount($user->balance,currencyFormat:false),
        ]);

        $notify[] = ['success', 'Withdrawn successfully'];
        return back()->withNotify($notify);
    }

    private function validation($request, $user)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);

        if (!$user->status) {
            throw ValidationException::withMessages(['error' => 'This account is currently banned']);
        }

        if (!$user->profile_complete) {
            throw ValidationException::withMessages(['error' => 'This account is not completed yet']);
        }

        if ($request->amount > $user->balance) {
            throw ValidationException::withMessages(['error' => 'User don\'t have sufficient balance']);
        }
    }
}
