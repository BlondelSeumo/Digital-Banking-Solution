<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Loan;
use App\Models\LoanPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller {
    public function list() {
        $loans    = Loan::where('user_id', auth()->id())->with('nextInstallment')->with('plan')->apiQuery();
        $notify[] = 'My Loan List';
        return response()->json([
            'remark'  => 'loan_list',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'loans' => $loans,
            ],
        ]);
    }
    public function plans() {

        $notify[] = 'Loan Plans';
        $plans    = LoanPlan::active()->latest()->apiQuery();

        return response()->json([
            'remark'  => 'loan_plans',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'loan_plans' => $plans,
            ],
        ]);
    }

    public function applyLoan(Request $request, $id) {
        $plan = LoanPlan::active()->where('id', $id)->first();

        if (!$plan) {
            $notify[] = 'Plan not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $validator = Validator::make($request->all(), [
            'amount' => "required|numeric|min:$plan->minimum_amount|max:$plan->maximum_amount",
        ]);
        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $delayCharge = $plan->delay_charge;
        $notify[]    = 'Plan Information';
        return response()->json([
            'remark'  => 'plan',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'plan'        => $plan,
                'amount'      => $request->amount,
                'form_data'   => $plan->form,
                'delayCharge' => $delayCharge,
            ],
        ]);
    }

    public function loanConfirm(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $plan = LoanPlan::active()->where('id', $id)->first();
        if (!$plan) {
            $notify[] = 'No such plan found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $amount = $request->amount;
        if ($plan->minimum_amount > $amount || $amount > $plan->maximum_amount) {
            $notify[] = 'Please follow the minium & maximum limit for this plan';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $applicationForm = null;
        if (@$plan->form->form_data) {
            $formData           = $plan->form->form_data;
            $formProcessor      = new FormProcessor();
            $validationRule     = $formProcessor->valueValidation($formData);
            $formDataValidation = Validator::make($request->all(), $validationRule);

            if ($formDataValidation->fails()) {
                return response()->json([
                    'remark'  => 'validation_error',
                    'status'  => 'error',
                    'message' => ['error' => $formDataValidation->errors()->all()],
                ]);
            }
            $applicationForm = $formProcessor->processFormData($request, $formData);
        }

        $user           = auth()->user();
        $perInstallment = $amount * $plan->per_installment / 100;

        $percentCharge = $plan->per_installment * $plan->percent_charge / 100;
        $charge        = $plan->fixed_charge + $percentCharge;

        $loan                         = new Loan();
        $loan->loan_number            = getTrx();
        $loan->user_id                = $user->id;
        $loan->plan_id                = $plan->id;
        $loan->amount                 = $amount;
        $loan->per_installment        = $perInstallment;
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

        $notify[] = 'Loan request successfully';
        return response()->json([
            'remark'  => 'loan_success',
            'status'  => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function installments($loanNumber) {
        $loan = Loan::where('loan_number', $loanNumber)->with('plan:id,name')->where('user_id', auth()->id())->first();
        if (!$loan) {
            $notify[] = 'Loan not found';
            return response()->json([
                'remark'  => 'loan_not_found',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $installments  = $loan->installments()->paginate(getPaginate());
        $payableAmount = @$loan->payable_amount;
        $notify[]      = 'Loan Installments';
        return response()->json([
            'remark'  => 'loan_installment',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'installments'  => $installments,
                'loan'          => $loan,
                'payableAmount' => $payableAmount,
            ],
        ]);
    }
}
