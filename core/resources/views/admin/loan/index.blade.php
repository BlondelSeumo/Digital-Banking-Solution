@extends('admin.layouts.app')
@section('panel')
    @php
        $request = request();
        $tableName = 'loan_list';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);

        $columns = collect([
            prepareTableColumn('loan_number', 'Loan No.'),
            prepareTableColumn('account_number', 'Account No.', link:'route("admin.users.detail", $item->user_id)'),
            prepareTableColumn('plan_name', 'Plan'),
            prepareTableColumn('profit_rate', 'Rate', 'getAmount($item->profit_rate)."%"', filter: 'range'),
            prepareTableColumn('installment_interval', 'Interval', '$item->installment_interval ." Days"', filter: 'range'),
            prepareTableColumn('per_installment', 'Installment', 'showAmount($item->per_installment)', filter: 'range'),
            prepareTableColumn('total_installment', 'Total Inst.', 'getAmount($item->total_installment)', filter: 'range'),
            prepareTableColumn('given_installment', 'Given Inst.', 'getAmount($item->given_installment)', filter: 'range'),
            prepareTableColumn('late_installments_count', 'Late Inst.', '$item->late_installments_count', filter: 'range'),
            prepareTableColumn('next_installment_date', 'Next Inst. Date', 'showDateTime("$item->next_installment_date", "d M, Y")', filter: 'date'),
            prepareTableColumn('amount', 'Deposit Amount', 'showAmount($item->amount)', filter: 'range'),
            prepareTableColumn('payable_amount', 'Receivable Amount', 'showAmount($item->payable_amount)', filter: 'range'),
            prepareTableColumn('created_at', 'Initiated On', 'showDateTime("$item->created_at", "d M, Y")', filter: 'date'),
            prepareTableColumn('approved_at', 'Approved On', 'showDateTime("$item->approved_at", "d M, Y")', filter: 'date'),
            prepareTableColumn('status', 'Status', '$item->status_badge', echoable:true)
        ]);

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => can('admin.loan.details') || can('admin.loan.installments'),
            'buttons' => [
                [
                    'icon' => 'las la-desktop',
                    'name' => 'Details',
                    'link' => 'route("admin.loan.details", $item->id)',
                    'show' => "can('admin.loan.details')",
                ],
                [
                    'icon' => 'las la-history',
                    'name' => 'Installments',
                    'link' => 'route("admin.loan.installments", $item->id)',
                    'show' => "can('admin.loan.installments')",
                ],
            ],
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$loans" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" searchPlaceholder="Loan No. / Account No. / Plan" />
@endsection
