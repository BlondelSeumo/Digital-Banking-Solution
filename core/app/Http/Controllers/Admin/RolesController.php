<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller {

    public function index() {
        $roles = Role::searchable(['name'])->get();
        $pageTitle = "All Roles";
        return view('admin.roles.index', compact('roles', 'pageTitle'));
    }

    public function add() {
        $pageTitle = "Add New Role";
        $permissionGroups = Permission::all()->groupBy('group');
        return view('admin.roles.add', compact('pageTitle', 'permissionGroups'));
    }

    public function edit($id) {
        $pageTitle = "Edit Role";
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = $role->permissions->pluck('pivot.permission_id');
        $permissionGroups = Permission::all()->groupBy('group');
        return view('admin.roles.add', compact('pageTitle', 'permissionGroups', 'role', 'permissions'));
    }

    public function save(Request $request, $id = 0) {
        $request->validate([
            'name'          => 'required|string',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'required|integer',
        ]);

        if (!$id) {
            $role = new Role();
            $notification = 'New role added successfully';
        } else {
            $role = Role::findOrFail($id);
            $notification = 'New role updated successfully';
        }
        $role->name = $request->name;
        $role->save();

        $role->permissions()->sync($request->permissions);
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}

