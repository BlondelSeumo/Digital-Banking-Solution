<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{

    public function index()
    {
        $pageTitle = 'All Admin Staff';
        $allStaff  = Admin::where('admins.id', '!=', 1)->selectRaw(
            'admins.*,
            CASE WHEN admins.status  = 0 THEN "Banned" ELSE "Active" END AS status_text,
            CASE WHEN admins.role_id = 0 THEN "Super Admin" ELSE roles.name END AS role_name'
        )
            ->leftJoin('roles', 'admins.role_id', '=', 'roles.id')
            ->searchable(['username', 'name', 'email', 'roles.name'])
            ->filterable()
            ->orderable();

        $allStaff = $allStaff->dynamicPaginate();
        $roles    = Role::get();

        return view('admin.staff.index', compact('pageTitle', 'allStaff', 'roles'));
    }

    public function status($id)
    {
        return Admin::changeStatus($id);
    }

    public function save(Request $request, $id = 0)
    {

        $this->validation($request, $id);

        if ($id) {
            $staff   = Admin::where('id', '!=', 1)->findOrFail($id);
            $message = "Staff updated successfully";
        } else {
            $staff   = new Admin();
            $message = "New staff added successfully";
        }

        $staff->name     = $request->name;
        $staff->username = $request->username;
        $staff->email    = $request->email;
        $staff->role_id  = $request->role_id;
        $staff->password = $request->password ? Hash::make($request->password) : $staff->password;
        $staff->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    private function validation($request, $id)
    {
        $request->validate([
            'username' => 'required|unique:admins,username,' . $id,
            'name'     => 'required',
            'email'    => 'required|unique:admins,email,' . $id,
            'role_id'  => 'required|integer|gt:0',
            'password' => !$id ? 'required|min:6' : 'nullable',
        ]);
    }

    public function login($id)
    {
        Auth::guard('admin')->loginUsingId($id);
        return to_route('admin.dashboard');
    }
}
