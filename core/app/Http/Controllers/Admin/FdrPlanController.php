<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FdrPlan;
use Illuminate\Http\Request;

class FdrPlanController extends Controller
{
    public function index()
    {
        $pageTitle = 'FDR Plans (Fixed Deposit Receipt)';
        $plans     = FdrPlan::latest()->paginate(getPaginate());
        return view('admin.plans.fdr.index', compact('pageTitle', 'plans'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'                 => 'required|max:40',
            'interest_rate'        => 'required|integer|gt:0',
            'installment_interval' => 'required|integer|gt:0',
            'locked_days'          => 'required|numeric|gt:0',
            'minimum_amount'       => 'required|numeric|gt:0',
            'maximum_amount'       => 'required|numeric|gt:minimum_amount',
        ]);

        if ($id) {
            $plan    = FdrPlan::findOrFail($id);
            $message = "Plan updated successfully";
        } else {
            $plan    = new FdrPlan();
            $message = "Plan added successfully";
        }
        $plan->name                 = $request->name;
        $plan->installment_interval = $request->installment_interval;
        $plan->interest_rate        = $request->interest_rate;
        $plan->locked_days          = $request->locked_days;
        $plan->minimum_amount       = $request->minimum_amount;
        $plan->maximum_amount       = $request->maximum_amount;
        $plan->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function changeStatus($id)
    {
        return FdrPlan::changeStatus($id);
    }
}
