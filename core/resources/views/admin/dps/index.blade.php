@extends('admin.layouts.app')
@section('panel')

    @php
        $request = request();
        $tableName = 'dps_list';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);

        $columns = collect([
            prepareTableColumn('dps_number', 'DPS No.'),
            prepareTableColumn('account_number', 'Account No.', link:'route("admin.users.detail", $item->user_id)'),
            prepareTableColumn('plan_name', 'Plan'),
            prepareTableColumn('interest_rate', 'Rate', 'getAmount($item->interest_rate)."%"', filter: 'range'),
            prepareTableColumn('installment_interval', 'Interval', '$item->installment_interval ." Days"', filter: 'range'),
            prepareTableColumn('per_installment', 'Installment', 'showAmount($item->per_installment)', filter: 'range'),
            prepareTableColumn('total_installment', 'Total Inst.', 'getAmount($item->total_installment)', filter: 'range'),
            prepareTableColumn('given_installment', 'Given Inst.', 'getAmount($item->given_installment)', filter: 'range'),
            prepareTableColumn('due_installments_count', 'Late Inst.', '$item->late_installments_count', filter: 'range'),
            prepareTableColumn('next_installment_date', 'Next Inst. Date', 'showDateTime("$item->next_installment_date", "d M, Y")', filter: 'date'),
            prepareTableColumn('deposit_amount', 'Deposit Amount', 'showAmount($item->deposit_amount)', filter: 'range'),
            prepareTableColumn('profit_amount', 'Profit Amount', 'showAmount($item->profit_amount)', filter: 'range'),
            prepareTableColumn('total_amount', 'Total Amount', 'showAmount($item->total_amount)', filter: 'range'),
            prepareTableColumn('created_at', 'Opened At', 'showDateTime("$item->created_at", "d M, Y")', filter: 'date'),
            prepareTableColumn('status', 'Status', '$item->status_badge', echoable:true)
        ]);

        $action = [
            'name' => 'Action',
            'style' => '',
            'show' => can('admin.dps.installments'),
            'buttons' => [
                [
                    'icon' => 'las la-history',
                    'name' => 'Installments',
                    'link' => 'route("admin.dps.installments", $item->id)',
                    'show' => "can('admin.dps.installments')",
                    'class' => 'btn-outline--primary',
                ],
            ],
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$data" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" searchPlaceholder="DPS No. / Account No." />
@endsection
