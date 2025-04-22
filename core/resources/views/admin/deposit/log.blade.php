@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        @if(request()->routeIs('admin.deposit.list'))
            <div class="col-12">
                @include('admin.deposit.widget')
            </div>
        @endif
    </div>

    @php
        $request = request();
        $tableName = 'deposits_list';
        $tableConfiguration = tableConfiguration($tableName);

        $gateways = App\Models\GatewayCurrency::orderBy('name')->get()->pluck('name')->toArray();

        $gateways[] = 'Google Pay';
        $gateways[] = 'Branch Deposit';

        sort($gateways);

        $branches = App\Models\Branch::orderBy('name')->get()->pluck('name')->toArray();

        $branches[] = 'Online';

        $branchStaff = App\Models\BranchStaff::orderBy('name')->get()->pluck('name')->toArray();


        $columns = collect([
            prepareTableColumn('trx', 'TRX No.'),
            prepareTableColumn('account_number', 'Account No.', link:'route("admin.users.detail", $item->user_id)'),
            prepareTableColumn('gateway_name', 'Gateway', filter:'select', filterOptions:$gateways),
            prepareTableColumn('branch_name', 'Branch', filter:'select', filterOptions:$branches),
            prepareTableColumn('staff_name', 'Staff', filter:'select', filterOptions:$branchStaff),
            prepareTableColumn('created_at', 'Initiated At', 'showDateTime($item->created_at)', filter: 'date'),
            prepareTableColumn('amount', 'Amount', 'showAmount($item->amount)', filter: 'range'),
            prepareTableColumn('charge', 'Charge', 'showAmount($item->charge)', filter: 'range'),
            prepareTableColumn('total_amount', 'Total', 'showAmount($item->total_amount)', filter: 'range'),
            prepareTableColumn('rate', 'Conversion Rate', 'showAmount($item->rate, currencyFormat: false) ." ". __($item->method_currency)'),
            prepareTableColumn('final_amount', 'Final Amount', 'showAmount($item->final_amount, currencyFormat: false) ." ". __($item->method_currency)'),
            prepareTableColumn('status', 'Status', '$item->status_badge', echoable:true)
        ]);

        $action = [
            'name' => 'Action',
            'style' => '',
            'show' => can('admin.deposit.details'),
            'buttons' => [
                [
                    'name' => 'Details',
                    'icon' => 'la la-desktop',
                    'link' => 'route("admin.deposit.details", $item->id)',
                    'show' => "can('admin.deposit.details')",
                    'class' => 'btn-outline--primary'
                ]
            ]
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp


    <x-viser_table.table :data="$deposits" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive"/>
@endsection

