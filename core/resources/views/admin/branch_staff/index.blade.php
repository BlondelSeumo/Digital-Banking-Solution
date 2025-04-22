@extends('admin.layouts.app')

@section('panel')
    @php
        $request = request();
        $tableName = 'branch_staff_list';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);
        $statusOptions = ['Active', 'Banned'];
        $designationOptions = ['Account Officer', 'Branch Manager'];

        $branchOptions = $branches->pluck('name')->toArray();

        $columns = collect([prepareTableColumn('name', 'Name'), prepareTableColumn('email', 'Email'), prepareTableColumn('mobile', 'Mobile'), prepareTableColumn('branch_names', 'Branch', filter: 'select', filterOptions: $branchOptions), prepareTableColumn('designation_name', 'Designation', filter: 'select', filterOptions: $designationOptions), prepareTableColumn('status_text', 'Status', '$item->status_badge', filter: 'select', filterOptions: $statusOptions, echoable: true), prepareTableColumn('created_at', 'Added On', 'showDateTime("$item->created_at", "d M, Y")', filter: 'date')]);

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => can('admin.branch.staff.details') && can('admin.branch.staff.status') && can('admin.branch.staff.status'),
            'buttons' => [
                [
                    'name' => 'View Details',
                    'show' => 'can("admin.branch.staff.details")',
                    'link' => 'route("admin.branch.staff.details", $item->id)',
                    'icon' => 'la la-desktop',
                    'attributes' => [
                        'data-resource' => 'json_encode($item) ',
                        'data-modal_title' => 'trans("Update Staff")',
                    ],
                ],
                [
                    'name' => 'Ban Staff',
                    'show' => 'can("admin.branch.staff.status") && $item->status',
                    'class' => 'confirmationBtn',
                    'icon' => 'la la-user-times',
                    'attributes' => [
                        'data-action' => 'route(\'admin.branch.staff.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to ban this staff?")',
                    ],
                ],
                [
                    'name' => 'Unban Staff',
                    'show' => 'can("admin.branch.staff.status") && !$item->status',
                    'class' => 'confirmationBtn',
                    'icon' => 'la la-user-check',
                    'attributes' => [
                        'data-action' => 'route(\'admin.branch.staff.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to unban this staff?")',
                    ],
                ],
                [
                    'name' => 'Login as Staff',
                    'show' => 'can("admin.branch.staff.login")',
                    'link' => 'route("admin.branch.staff.login", $item->id)',
                    'icon' => 'la la-sign-in',
                ],
            ],
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$staffs" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" />

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    @can('admin.branch.staff.add')
        <a class="btn btn-outline--info" href="{{ route('admin.branch.index') }}">
            <i class="la la-list"></i>@lang('All Branches')
        </a>

        <a class="btn btn-outline--primary" href="{{ route('admin.branch.staff.add') }}">
            <i class="la la-plus"></i>@lang('Add New')
        </a>
    @endcan
@endpush
