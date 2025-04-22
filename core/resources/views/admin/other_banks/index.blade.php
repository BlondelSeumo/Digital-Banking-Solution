@extends('admin.layouts.app')
@section('panel')
    @php
        $request = request();
        $tableName = 'other_banks';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);

        $statusOptions = ['1'=>'Active', '0'=>'Banned'];

        $columns = collect([
            prepareTableColumn('name', 'Bank'),
            prepareTableColumn('minimum_limit', 'Min Limit', 'showAmount("$item->minimum_limit")', filter: 'range'),
            prepareTableColumn('maximum_limit', 'Max Limit', 'showAmount("$item->maximum_limit")', filter: 'range'),
            prepareTableColumn('daily_maximum_limit', 'Daily Max Limit', 'showAmount("$item->daily_maximum_limit")', filter: 'range'),
            prepareTableColumn('monthly_maximum_limit', 'Monthly Max Limit', 'showAmount("$item->monthly_maximum_limit")', filter: 'range'),
            prepareTableColumn('daily_total_transaction', 'Daily Total Trx.', 'showAmount("$item->daily_total_transaction")', filter: 'range'),
            prepareTableColumn('monthly_total_transaction', 'Daily Total Trx.', 'showAmount("$item->monthly_total_transaction")', filter: 'range'),
            prepareTableColumn('fixed_charge', 'Fixed Charge', 'showAmount("$item->fixed_charge")', filter: 'range'),
            prepareTableColumn('percent_charge', 'Percent Charge', 'getAmount("$item->percent_charge")."%"', filter: 'range'),
            prepareTableColumn('processing_time', 'Processing Time', filter:'text'),
            prepareTableColumn('status', 'Status', '$item->status_badge', filter:'select', filterOptions:$statusOptions, echoable:true),
        ]);

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => can('admin.bank.edit') || can('admin.bank.change.status'),
            'buttons' => [
                [
                    'name' => 'Edit',
                    'icon' => 'la la-pencil',
                    'link' => 'route("admin.bank.edit", $item->id)',
                    'show' => can('admin.bank.edit'),
                ],
                [
                    'name' => 'Disable',
                    'show' => 'can("admin.bank.change.status") && $item->status',
                    'class' => 'confirmationBtn',
                    'icon'=> 'la la-eye-slash',
                    'attributes' => [
                        'data-action' => 'route(\'admin.bank.change.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to disable this bank?")'
                    ]
                ],
                [
                    'name' => 'Enable',
                    'show' => 'can("admin.bank.change.status") && !$item->status',
                    'class' => 'confirmationBtn',
                    'icon'=> 'la la-eye',
                    'attributes' => [
                        'data-action' => 'route(\'admin.bank.change.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to enable this bank?")'
                    ]
                ]

            ],
        ];

        if($tableConfiguration){
            $visibleColumns = $tableConfiguration->visible_columns;
        }else{
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$banks" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" searchPlaceholder="Bank Name" />


    @can('admin.bank.change.status')
        <x-confirmation-modal />
    @endcan
@endsection

@push('breadcrumb-plugins')
    @can('admin.bank.create')
        <a class="btn btn-outline--primary" href="{{ route('admin.bank.create') }}">
            <i class="las la-plus"></i>@lang('Add New')
        </a>
    @endcan
@endpush
