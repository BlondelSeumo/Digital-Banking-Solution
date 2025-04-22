<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchStaff;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class BranchController extends Controller {

    public function index() {
        $pageTitle = "All Branches";
        $branches  = Branch::selectRaw('
            branches.*,
            CASE WHEN branches.status = 0 THEN "Disabled" ELSE "Enabled" END AS status_text
        ')
        ->searchable(['code', 'name', 'email', 'mobile', 'phone', 'fax', 'routing_number', 'swift_code' ,'address'])
        ->orderable()
        ->filterable()
        ->dynamicPaginate();
        return view('admin.branch.index', compact('pageTitle', 'branches'));
    }

    public function addNew() {
        $pageTitle = "Add New Branch";
        return view('admin.branch.add', compact('pageTitle'));
    }

    public function details($id) {

        $branch                      = Branch::findOrFail($id);
        $pageTitle                   = "$branch->name Branch Details";
        $widget['total_deposited']   = Deposit::successful()->where('branch_id', $branch->id)->sum('amount');
        $widget['total_withdrawals'] = Withdrawal::approved()->where('branch_id', $branch->id)->sum('amount');
        $widget['total_staff']       = $branch->assignStaff->count();
        $widget['total_account']     = $branch->users()->count();
        return view('admin.branch.details', compact('pageTitle', 'branch', 'widget'));
    }

    public function save(Request $request, $id = 0) {

        $this->validation($request, $id);

        if ($id) {
            $branch  = Branch::findOrFail($id);
            $message = "Branch updated successfully";
        } else {
            $branch  = new Branch();
            $message = "Branch added successfully";
        }

        $branch->name           = $request->name;
        $branch->code           = $request->code;
        $branch->routing_number = $request->routing_number;
        $branch->swift_code     = $request->swift_code;
        $branch->phone          = $request->phone;
        $branch->mobile         = $request->mobile;
        $branch->email          = $request->email;
        $branch->fax            = $request->fax;
        $branch->address        = $request->address;
        $branch->map_location   = $request->map_location;
        $branch->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function changeStatus($id) {
        return Branch::changeStatus($id);
    }

    protected function validation($request, $id) {
        $request->validate([
            'name'           => 'required|string:40|unique:branches,name,' . $id,
            'code'           => 'required|string:40|unique:branches,code,' . $id,
            'email'          => 'nullable|email',
            'mobile'         => 'nullable|string|max:40',
            'phone'          => 'nullable|string|max:40',
            'fax'            => 'nullable|string|max:40',
            'routing_number' => 'nullable|string|max:40',
            'swift_code'     => 'nullable|string|max:40',
            'address'        => 'required|string|max:255',
            'map_location'   => 'nullable|string|max:64000',
        ]);
    }
}
