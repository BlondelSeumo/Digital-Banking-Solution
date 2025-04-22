@extends('admin.layouts.app')

@section('panel')

    @php
        $request = request();
        $tableName = 'branch_staff_list';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);
        $statusOptions = ['Enabled', 'Disabled'];

        $columns = collect([
            prepareTableColumn('name', 'Name'),
            prepareTableColumn('code', 'Code'),
            prepareTableColumn('email', 'Email'),
            prepareTableColumn('mobile', 'Mobile'),
            prepareTableColumn('phone', 'Phone'),
            prepareTableColumn('fax', 'Fax'),
            prepareTableColumn('routing_number', 'Routing No.'),
            prepareTableColumn('swift_code', 'Swift Code'),
            prepareTableColumn('address', 'Address'),
            prepareTableColumn('status_text', 'Status', '$item->status_badge', filter: 'select', filterOptions: $statusOptions, echoable:true),
            prepareTableColumn('created_at', 'Added On', 'showDateTime("$item->created_at", "d M, Y")', filter: 'date')
        ]);

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => can('admin.branch.details') || can('admin.branch.status'),
            'buttons' => [
                [
                    'name' => 'Details',
                    'show' => 'can("admin.branch.details")',
                    'link' => 'route("admin.branch.details", $item->id)',
                    'icon' => 'la la-desktop',
                ],
                [
                    'name' => 'Disable',
                    'show' => 'can("admin.branch.status") && $item->status',
                    'class' => 'confirmationBtn',
                    'icon' => 'la la-eye-slash',
                    'attributes' => [
                        'data-action' => 'route(\'admin.branch.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to disable this Branch?")',
                    ],
                ],
                [
                    'name' => 'Enable',
                    'show' => 'can("admin.branch.status") && !$item->status',
                    'class' => 'confirmationBtn',
                    'icon' => 'la la-eye',
                    'attributes' => [
                        'data-action' => 'route(\'admin.branch.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to enable this branch?")',
                    ],
                ]

            ],
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

<x-viser_table.table :data="$branches" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" />

<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    @can('admin.branch.add')
        <a href="{{ route('admin.branch.add') }}" class="btn btn-outline--primary">
            <i class="las la-plus"></i>@lang('Add New')
        </a>
    @endcan
@endpush
