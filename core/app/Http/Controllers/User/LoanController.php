<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Loan;
use App\Models\LoanPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller {

    public function list() {
        $pageTitle = 'My Loan List';
        $loans     = Loan::where('user_id', auth()->id())->searchable(['loan_number', 'plan:name'])->with(['plan', 'nextInstallment']);

        if (request()->has('status')) {
            $loans->where('status', request()->status);
        }

        if (request()->date) {
            $date      = explode('-', request()->date);
            $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d');
            $endDate = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
            request()->merge(['start_date' => $startDate, 'end_date' => $endDate]);
            request()->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date'   => 'nullable|date_format:Y-m-d',
            ]);
            $loans->whereHas('nextInstallment', function ($query) use ($startDate, $endDate) {
                $query->whereDate('installment_date', '>=', $startDate)->whereDate('installment_date', '<=', $endDate);
            });
        }
        if (request()->download == 'pdf') {
            $loans = $loans->get();
            return downloadPDF('Template::pdf.loan_list', compact('pageTitle', 'loans'));
        }
        $loans = $loans->orderBy('id', 'DESC')->paginate(getPaginate());

        return view('Template::user.loan.list', compact('pageTitle', 'loans'));
    }

    public function plans() {
        $pageTitle = 'Loan Plans';
        $plans     = LoanPlan::active()->latest()->get();
        return view('Template::user.loan.plans', compact('pageTitle', 'plans'));
    }

    public function applyLoan(Request $request, $id) {

        $plan = LoanPlan::active()->findOrFail($id);
        $request->validate(['amount' => "required|numeric|min:$plan->minimum_amount|max:$plan->maximum_amount"]);
        session()->put('loan', ['plan' => $plan, 'amount' => $request->amount]);
        return redirect()->route('user.loan.apply.form');
    }

    public function loanPreview() {
        $loan = session('loan');
        if (!$loan) {
            return redirect()->route('user.loan.plans');
        }
        $plan      = $loan['plan'];
        $amount    = $loan['amount'];
        $pageTitle = 'Apply For Loan';
        return view('Template::user.loan.form', compact('pageTitle', 'plan', 'amount'));
    }

    public function confirm(Request $request) {
        $loan = session('loan');
        if (!$loan) {
            return redirect()->route('user.loan.plans');
        }

        $plan   = $loan['plan'];
        $amount = $loan['amount'];
        $plan   = LoanPlan::active()->where('id', $plan->id)->firstOrFail();

        $formData       = $plan->form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $applicationForm = $formProcessor->processFormData($request, $formData);

        $user            = auth()->user();
        $per_installment = $amount * $plan->per_installment / 100;

        $percentCharge = $plan->per_installment * $plan->percent_charge / 100;
        $charge        = $plan->fixed_charge + $percentCharge;

        $loan                         = new Loan();
        $loan->loan_number            = getTrx();
        $loan->user_id                = $user->id;
        $loan->plan_id                = $plan->id;
        $loan->amount                 = $amount;
        $loan->per_installment        = $per_installment;
        $loan->installment_interval   = $plan->installment_interval;
        $loan->delay_value            = $plan->delay_value;
        $loan->charge_per_installment = $charge;
        $loan->total_installment      = $plan->total_installment;
        $loan->application_form       = $applicationForm;
        $loan->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New loan request';
        $adminNotification->click_url = urlPath('admin.loan.index') . '?search=' . $loan->loan_number;
        $adminNotification->save();

        session()->forget('loan');
        session()->forget('otp_id');

        $notify[] = ['success', 'Loan application submitted successfully'];
        return to_route('user.loan.details',$loan->loan_number)->withNotify($notify);
    }

    public function installments($loanNumber) {
        $loan         = Loan::where('loan_number', $loanNumber)->where('user_id', auth()->id())->firstOrFail();
        $installments = $loan->installments()->paginate(getPaginate());
        $pageTitle    = 'Loan Installments';
        return view('Template::user.loan.installments', compact('pageTitle', 'installments', 'loan'));
    }

    public function details($loanNumber) {
        $loan = auth()->user()->loan()->where('loan_number', $loanNumber)->with('plan')->orderBy('id', 'DESC')->firstOrFail();
        $pageTitle = "Loan Details";
        if(request()->has('download')){
            return downloadPDF('pdf.loan_details', compact('pageTitle', 'loan'));
        }
        return view('Template::user.loan.details', compact('pageTitle', 'loan'));
    }
}
