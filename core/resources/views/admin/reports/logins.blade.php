@extends('admin.layouts.app')

@section('panel')
    @php
        $request = request();
        $tableName = 'users_login_history';
        $tableConfiguration = tableConfiguration($tableName);

        $columns = collect([
            prepareTableColumn('created_at', 'Login At', 'showDateTime($item->created_at)', filter: 'date'),
            prepareTableColumn('account_number', 'Account No.', link:'route("admin.users.detail", $item->user_id)'),
            prepareTableColumn('username', 'Username', link:'route("admin.users.detail", $item->user_id)'),
            prepareTableColumn('user_ip', 'IP Address', url:'"https://www.ip2location.com/".$item->user_ip'),
            prepareTableColumn('country', 'Country', filter:'select', filterOptions: $countries),
            prepareTableColumn('city', 'City', filter:'select', filterOptions: $cities),
            prepareTableColumn('os', 'OS', filter:'select', filterOptions: $allOs),
            prepareTableColumn('browser', 'Browser', filter:'select', filterOptions: $browsers),
            prepareTableColumn('longitude', 'Longitude'),
            prepareTableColumn('latitude', 'Latitude'),
        ]);

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }

        $action = ['show' => false];
    @endphp


    <x-viser_table.table :data="$loginLogs" :action="$action" :columns="$columns" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive"/>
@endsection


