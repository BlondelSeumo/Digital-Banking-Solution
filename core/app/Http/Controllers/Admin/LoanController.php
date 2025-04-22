<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    protected $pageTitle;

    public function index()
    {
        $this->pageTitle = 'All Loans';
        return $this->loanData();
    }

    public function runningLoans()
    {
        $this->pageTitle = 'Running Loans';
        return $this->loanData('running');
    }

    public function pendingLoans()
    {
        $this->pageTitle = 'Pending Loans';
        return $this->loanData('pending');
    }

    public function paidLoans()
    {
        $this->pageTitle = 'Paid Loans';
        return $this->loanData('paid');
    }

    public function rejectedLoans()
    {
        $this->pageTitle = 'Rejected Loans';
        return $this->loanData("rejected");
    }

    public function dueInstallment()
    {
        $this->pageTitle = 'Due Installment Loans';
        return $this->loanData("due");
    }

    public function details($id)
    {
        $loan      = Loan::where('id', $id)->with('plan', 'user')->firstOrFail();
        $pageTitle = 'Loan Details';
        return view('admin.loan.details', compact('pageTitle', 'loan'));
    }

    public function approve($id)
    {
        $loan              = Loan::with('user', 'plan')->findOrFail($id);
        $loan->status      = Status::LOAN_RUNNING;
        $loan->approved_at = now();
        $loan->save();
        Installment::saveInstallments($loan, now()->addDays($loan->installment_interval));

        $user = $loan->user;
        $user->balance += getAmount($loan->amount);
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $loan->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Loan taken';
        $transaction->trx          = getTrx();
        $transaction->remark       = 'loan_taken';
        $transaction->save();

        $shortCodes                          = $loan->shortCodes();
        $shortCodes['next_installment_date'] = now()->addDays($loan->installment_interval);

        notify($user, "LOAN_APPROVE", $loan->shortCodes());

        $notify[] = ['success', 'Loan approved successfully'];
        return back()->withNotify($notify);
    }

    public function reject(Request $request, $id)
    {

        $request->validate([
            'reason' => 'required|string',
        ]);

        $loan                 = Loan::where('id', $request->id)->with('user', 'plan')->firstOrFail();
        $loan->status         = 3;
        $loan->admin_feedback = $request->reason;
        $loan->save();

        notify($loan->user, "LOAN_REJECT", $loan->shortCodes());

        $notify[] = ['success', 'Loan rejected successfully'];
        return back()->withNotify($notify);
    }

    protected function loanData($scope = null)
    {
        $query = Loan::selectRaw('loans.*,
        users.account_number,
        loans.total_installment * loans.per_installment as payable_amount,
        ((loans.total_installment * loans.per_installment - loans.amount) / loans.amount) * 100 AS profit_rate,
        loan_plans.name AS plan_name,
        (
            SELECT installments.installment_date
            FROM installments
            WHERE installments.installmentable_id = loans.id
            AND installments.installmentable_type = ?
            AND installments.given_at IS NULL
            ORDER BY installments.id ASC
            LIMIT 1
        ) AS next_installment_date', [Loan::class]);
        if ($scope) {
            $query->$scope();
        }

        $pageTitle = $this->pageTitle;
        $loans     = $query
            ->leftJoin('users', 'loans.user_id', '=', 'users.id')
            ->leftJoin('loan_plans', 'loans.plan_id', '=', 'loan_plans.id')
            ->searchable(['loan_number', 'account_number', 'loan_plans.name',"user:username"])
            ->withCount('lateInstallments')
            ->filterable()
            ->orderable();

        if (request()->download == 'pdf') {
            $loans = $loans->get();
            return downloadPdf('admin.pdf.loan.index', compact('pageTitle', 'loans'));
        }
        if (request()->download == 'csv') {
            $filename = $this->downloadCsv($pageTitle, $loans->get());
            return response()->download(...$filename);
        }

        $pdfCsvButton = false;

        if (request()->date) {
            $pdfCsvButton = true;
        }

        $loans = $loans->dynamicPaginate();

        return view('admin.loan.index', compact('pageTitle', 'loans', 'pdfCsvButton'));
    }
    protected function downloadCsv($pageTitle, $data)
    {
        $filename = "assets/files/csv/example.csv";
        $myFile   = fopen($filename, 'w');
        $column   = "Loan No,Plan,A/C No., Username,Amount,Receivable Amount, Installment Amount,Installment For, Total Installment, Given Installment, Next Installment,Created,Status\n";
        $curSym   = gs('cur_sym');

        foreach ($data as $loan) {
            $planName          = @$loan->plan->name;
            $userName          = @$loan->user->username;
            $accountNumber     = @$loan->user->account_number;
            $amount            = $curSym . getAmount($loan->amount);
            $receivableAmount  = $curSym . getAmount($loan->payable_amount);
            $installmentAmount = $curSym . getAmount($loan->per_installment);
            $installmentFor    = $loan->installment_interval . ' Days';
            $nextInstallment   = showDateTime(@$loan->nextInstallment->installment_date, 'd-m-Y') ?? 'N/A';
            $createdAt         = showDateTime($loan->created_at, 'd-m-Y');
            if ($loan->status == Status::LOAN_PENDING) {
                $status = 'Pending';
            } elseif ($loan->status == Status::LOAN_RUNNING) {
                $status = 'Running';
            } elseif ($loan->status == Status::LOAN_PAID) {
                $status = 'Paid';
            } else {
                $status = 'Rejected';
            }
            $column .= "$loan->loan_number,$planName,$userName,$accountNumber,$amount,$receivableAmount,$installmentAmount,$installmentFor,$loan->total_installment,$loan->given_installment,$nextInstallment,$createdAt,$status\n";
        }
        fwrite($myFile, $column);
        $headers = [
            'Content-Type' => 'application/csv',
        ];
        $name  = $pageTitle . time() . '.csv';
        $array = [$filename, $name, $headers];
        return $array;
    }

    public function installments($id)
    {
        $loan         = Loan::with('installments')->findOrFail($id);
        $installments = $loan->installments()->paginate(getPaginate());
        $pageTitle    = "Installments";
        return view('admin.loan.installments', compact('pageTitle', 'installments', 'loan'));
    }
}
