@extends('admin.layouts.app')

@section('panel')
@php
    $request = request();
    $tableName = 'transaction_report';
    $tableConfiguration = tableConfiguration($tableName);

    $remarks = $remarks->pluck('remark_text')->toArray();

    $columns = collect([
        prepareTableColumn('trx', 'TRX No.'),
        prepareTableColumn('account_number', 'Account No.', link:'route("admin.users.detail", $item->user_id)'),
        prepareTableColumn('username', 'Username', link:'route("admin.users.detail", $item->user_id)'),
        prepareTableColumn('created_at', 'Transacted At', 'showDateTime($item->created_at)', filter: 'date'),
        prepareTableColumn('remark_text', 'Remark', filter:'select', filterOptions: $remarks),
        prepareTableColumn('transaction_type', 'Transaction Type', filter: 'select', filterOptions: ['Debited', 'Credited'], className: '$item->trx_type=="+"? "text--success fw-bold": "text--danger fw-bold"'),
        prepareTableColumn('amount', 'Amount', 'showAmount($item->amount)', filter: 'range'),
        prepareTableColumn('details', 'Details', '__($item->details)'),
    ]);

    if ($tableConfiguration) {
        $visibleColumns = $tableConfiguration->visible_columns;
    } else {
        $visibleColumns = $columns->pluck('id')->toArray();
    }

    $action = ['show' => false];
@endphp


<x-viser_table.table :data="$transactions" :action="$action" :columns="$columns" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive"/>

@endsection
