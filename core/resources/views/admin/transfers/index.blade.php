@extends('admin.layouts.app')
@section('panel')

    @php
        $request = request();
        $tableName = 'transfers_list';
        $otherBanks = App\Models\OtherBank::get()->pluck('name')->toArray();
        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);

        $otherBanks[] = gs('site_name');
        $otherBanks[] = 'Wire Transfer';

        sort($otherBanks);

        $columns = collect([
            prepareTableColumn('trx', 'TRX No.'),
            prepareTableColumn('sender', 'Sender', link:'route("admin.users.detail", $item->user_id)'),
            prepareTableColumn('receiver', 'Receiver'),
            prepareTableColumn('receiver_bank', 'Receiver Bank', filter:'select', filterOptions:$otherBanks),
            prepareTableColumn('amount', 'Amount', 'showAmount("$item->amount")', filter: 'range'),
            prepareTableColumn('charge', 'Charge', 'showAmount("$item->charge")', filter: 'range'),
            prepareTableColumn('final_amount', 'Final Amount', 'showAmount("$item->final_amount")', filter: 'range'),
            prepareTableColumn('created_at', 'Created At', filter: 'date'),
            prepareTableColumn('status', 'Status', '$item->status_badge', echoable:true)
        ]);

        $action = [
            'name' => 'Action',
            'style' => '',

            'show' => can('admin.transfers.details'),
            'buttons' => [
                [
                    'name' => 'Details',
                    'link' => 'route("admin.transfers.details", $item->id)',
                    'show' => can('admin.transfers.details'),
                    'class' => 'btn-outline--primary',
                    'icon' => 'la la-desktop',

                ],
            ]
        ];


        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$transfers" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" searchPlaceholder="Trx / Sender / Receiver" />
@endsection
