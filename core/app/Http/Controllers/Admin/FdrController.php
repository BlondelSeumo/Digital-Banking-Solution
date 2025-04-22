<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CronController;
use App\Models\Fdr;

class FdrController extends Controller
{
    protected $pageTitle;

    public function index()
    {
        $this->pageTitle = 'All FDR (Fixed Deposit Receipt)';
        return $this->fdrData();
    }

    public function runningFdr()
    {
        $this->pageTitle = 'Running FDR (Fixed Deposit Receipt)';
        return $this->fdrData('running');
    }

    public function closedFdr()
    {
        $this->pageTitle = 'Closed FDR (Fixed Deposit Receipt)';
        return $this->fdrData('closed');
    }

    public function dueInstallment()
    {
        $this->pageTitle = 'Due Installment FDR (Fixed Deposit Receipt)';
        return $this->fdrData('due');
    }

    protected function fdrData($scope = null)
    {
        $query = Fdr::selectRaw('fdrs.*, users.account_number, fdr_plans.name as plan_name, (per_installment / amount * 100) as interest_rate');
        if ($scope) {
            $query->$scope();
        }

        $pageTitle = $this->pageTitle;

       $data = $query
        ->leftJoin('users', 'fdrs.user_id', '=', 'users.id')
        ->leftJoin('fdr_plans', 'fdrs.plan_id', '=', 'fdr_plans.id')
        ->searchable(['fdr_number', 'user:account_number,username', 'plan:name'])
        ->filterable()
        ->orderable();

        if (request()->download == 'pdf') {
            $fdr = $data->get();
            return downloadPdf('admin.pdf.fdr.index', compact('pageTitle', 'fdr'));
        }

        if (request()->download == 'csv') {
            $filename = $this->downloadCsv($pageTitle, $data->get());
            return response()->download(...$filename);
        }

        $fdrs = $data->dynamicPaginate();

        $pdfCsvButton = false;
        if (request()->date) {
            $pdfCsvButton = true;
        }

        return view('admin.fdr.index', compact('pageTitle', 'fdrs', 'pdfCsvButton'));
    }

    protected function downloadCsv($pageTitle, $data)
    {
        $filename = "assets/files/csv/example.csv";

        $myFile = fopen($filename, 'w');
        $column = "FDR No,Plan,Username,Amount,Profit Percent,Profit,Profit For,Created,Lock-In Period,Next-Installment,Status\n";
        $curSym = gs('cur_sym');

        $fdrStatusCLosed  = Status::FDR_CLOSED;
        $fdrStatusRunning = Status::FDR_RUNNING;

        foreach ($data as $fdr) {
            $planName      = @$fdr->plan->name;
            $userName      = @$fdr->user->username;
            $amount        = $curSym . getAmount($fdr->amount);
            $profitPercent = getAmount($fdr->interest_rate) . '%';
            $profit        = $curSym . getAmount($fdr->per_installment);
            $profitPerDays = $fdr->installment_interval . ' Days';

            if ($fdr->status != $fdrStatusCLosed) {
                $nextInstallment = showDateTime($fdr->next_installment_date, 'd-m-Y');
            } else {
                $nextInstallment = 'N/A';
            }

            $lockedDate = showDateTime($fdr->locked_date, 'd-m-y');
            $createdAt  = showDateTime($fdr->created_at, 'd-m-Y');

            if ($fdr->status == $fdrStatusRunning && $fdr->next_installment_date < today()) {
                $status = 'Due';
            } elseif ($fdr->status == $fdrStatusRunning) {
                $status = 'Running';
            } else {
                $status = 'Closed';
            }

            $column .= "$fdr->fdr_number,$planName,$userName,$amount,$profitPercent,$profit,$profitPerDays,$createdAt,$lockedDate,$nextInstallment,$status\n";
        }
        fwrite($myFile, $column);
        $headers = [
            'Content-Type' => 'application/csv',
        ];
        $name = $pageTitle . time() . '.csv';
        $array = [$filename, $name, $headers];
        return $array;
    }

    public function installments($id)
    {
        $fdr          = Fdr::with('installments')->findOrFail($id);
        $installments = $fdr->installments()->paginate(getPaginate());
        $pageTitle    = "FDR Installments";
        return view('admin.fdr.installments', compact('pageTitle', 'installments', 'fdr'));
    }

    public function payDue($id)
    {
        $fdr = Fdr::findOrFail($id);
        $dueInstallment = $fdr->dueInstallment();

        if ($dueInstallment <= 0) {
            $notify[] = ['error', 'No due installment found for this FDR'];
            return back()->withNotify($notify);
        }

        for ($i = 0; $i < $dueInstallment; $i++) {
            CronController::payFdrInstallment($fdr);
        }

        $notify[] = ['success', 'Installment paid successfully'];
        return back()->withNotify($notify);
    }
}
