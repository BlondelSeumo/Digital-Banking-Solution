<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DpsPlan;
use Illuminate\Http\Request;

class DpsPlanController extends Controller {
    public function index() {
        $pageTitle = 'All Plans for DPS (Deposit Pension Scheme)';
        $plans     = DpsPlan::latest()->paginate(getPaginate());
        return view('admin.plans.dps.index', compact('pageTitle', 'plans'));
    }

    public function addNew() {
        $pageTitle = 'Add New Plan';
        return view('admin.plans.dps.form', compact('pageTitle'));
    }

    public function edit($id) {
        $pageTitle = 'Edit Plan';
        $plan = DpsPlan::findOrFail($id);
        return view('admin.plans.dps.form', compact('pageTitle', 'plan'));
    }

    public function store(Request $request, $id = 0) {
        $this->validation($request);

        if ($id) {
            $plan    = DpsPlan::findOrFail($id);
            $message = "Plan updated successfully";
        } else {
            $plan    = new DpsPlan();
            $message = "Plan added successfully";
        }

        $totalDeposit = $request->total_installment * $request->per_installment;
        $finalAmount  = $totalDeposit + ($totalDeposit * $request->interest_rate / 100);

        $plan->name                 = $request->name;
        $plan->total_installment    = $request->total_installment;
        $plan->installment_interval = $request->installment_interval;
        $plan->per_installment      = $request->per_installment;
        $plan->interest_rate        = $request->interest_rate;
        $plan->final_amount         = getAmount($finalAmount);
        $plan->delay_value          = $request->delay_value;
        $plan->fixed_charge         = $request->fixed_charge;
        $plan->percent_charge       = $request->percent_charge;
        $plan->save();

        $notify[] = ['success', $message];
        if(!$id) {
            return to_route('admin.plans.dps.index')->withNotify($notify);
        }
        return back()->withNotify($notify);
    }

    public function changeStatus($id) {
        return DpsPlan::changeStatus($id);
    }

    protected function validation($request) {
        $request->validate([
            'name'                 => 'required|max:100',
            'installment_interval' => 'required|integer|gt:0',
            'total_installment'    => 'required|integer|gt:0',
            'per_installment'      => 'required|numeric|gt:0',
            'interest_rate'        => 'required|numeric|gte:0',
            'delay_value'          => 'required|integer|gt:0',
            'fixed_charge'         => 'required|numeric|gte:0',
            'percent_charge'       => 'required|numeric|gte:0',
        ]);
    }
}
