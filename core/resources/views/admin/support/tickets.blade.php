@extends('admin.layouts.app')

@section('panel')
    @php
        $request = request();
        $tableName = 'support_ticket_list';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);

        $priorityOptions = [
            Status::PRIORITY_HIGH =>'High',
            Status::PRIORITY_MEDIUM =>'Medium',
            Status::PRIORITY_LOW =>'Low',
        ];

        $columns = collect([
            prepareTableColumn('ticket', 'Ticket No.', '"#".$item->ticket'),
            prepareTableColumn('subject', 'Subject', 'strLimit($item->subject, 50)'),
            prepareTableColumn('account_number', 'Account Number', link:'$item->user_id ? route("admin.users.detail", $item->user_id) : "#"'),
            prepareTableColumn('name', 'Name'),
            prepareTableColumn('email', 'Email'),
            prepareTableColumn('priority', 'Priority', '$item->priority_badge', filter: 'select', filterOptions:$priorityOptions, echoable:true),
            prepareTableColumn('status', 'Status', '$item->status_badge', echoable:true),
            prepareTableColumn('created_at', 'Opened At', 'showDateTime("$item->created_at", "d M, Y, h:i A")', filter: 'date'),
            prepareTableColumn('last_reply', 'Last Reply On', 'showDateTime("$item->last_reply", "d M, Y h:i A")', filter: 'date')
        ]);

        $action = [
            'name' => 'Action',
            'style' => '',
            'show' => can('admin.ticket.view'),
            'buttons' => [
                [
                    'name' => 'Details',
                    'show' => 'can("admin.ticket.view")',
                    'link' => 'route("admin.ticket.view", $item->id)',
                    'icon' => 'la la-desktop',
                    'class' => 'btn btn-outline--primary'
                ]
            ],
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

<x-viser_table.table :data="$items" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" />
@endsection
