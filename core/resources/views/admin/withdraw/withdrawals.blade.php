@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        @if (request()->routeIs('admin.withdraw.data.all'))
            <div class="col-12">
                @include('admin.withdraw.widget')
            </div>
        @endif
    </div>

    @php
        $request = request();
        $tableName = 'withdrawals_list';
        $tableConfiguration = tableConfiguration($tableName);

        $gateways   = App\Models\WithdrawMethod::orderBy('name')->get()->pluck('name')->toArray();

        $gateways[] = 'Branch Withdrawal';

        sort($gateways);
        $branches    = App\Models\Branch::orderBy('name')->get()->pluck('name')->toArray();
        $branches[]  = 'Online';
        $branchStaff = App\Models\BranchStaff::orderBy('name')->get()->pluck('name')->toArray();

        $columns = collect([
            prepareTableColumn('trx', 'TRX No.'),
            prepareTableColumn('account_number', 'Account No.', link: 'route("admin.users.detail", $item->user_id)'),
            prepareTableColumn('method_name', 'Method', filter: 'select', filterOptions: $gateways),
            prepareTableColumn('branch_name', 'Branch', filter: 'select', filterOptions: $branches),
            prepareTableColumn('staff_name', 'Staff', filter: 'select', filterOptions: $branchStaff),
            prepareTableColumn('created_at', 'Initiated At', 'showDateTime($item->created_at)', filter: 'date'),
            prepareTableColumn('amount', 'Amount', 'showAmount($item->amount)', filter: 'range'),
            prepareTableColumn('charge', 'Charge', 'showAmount($item->charge)', filter: 'range'),
            prepareTableColumn('total_amount', 'After Charge', 'showAmount($item->total_amount)', filter: 'range'),
            prepareTableColumn('rate', 'Conversion Rate', 'showAmount($item->rate, currencyFormat: false) ." ". __($item->currency)'),
            prepareTableColumn('final_amount', 'Payable Amount', 'showAmount($item->final_amount, currencyFormat: false) ." ". __($item->currency)'),
            prepareTableColumn('status', 'Status', '$item->status_badge', echoable:true),
        ]);

        $action = [
            'name' => 'Action',
            'style' => '',
            'show' => can('admin.withdraw.data.details'),
            'buttons' => [
                [
                    'name' => 'Details',
                    'icon' => 'la la-desktop',
                    'link' => 'route("admin.withdraw.data.details", $item->id)',
                    'show' => "can('admin.withdraw.data.details')",
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


    <x-viser_table.table :data="$withdrawals" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns"
        class="table-responsive--md table-responsive" />
@endsection
