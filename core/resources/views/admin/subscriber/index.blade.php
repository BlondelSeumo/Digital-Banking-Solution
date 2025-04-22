@extends('admin.layouts.app')

@section('panel')
    @php
        $request = request();
        $tableName = 'subscribers_list';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);

        $columns = collect([
            prepareTableColumn('email', 'Email'),
            prepareTableColumn('created_at', 'Subscribed On', 'showDateTime("$item->created_at", "d M, Y")', filter: 'date')
        ]);

        $action = [
            'name' => 'Action',
            'style' => '',
            'show' => can('admin.subscriber.remove'),
            'buttons' => [
                [
                    'name' => 'Remove',
                    'show' => can("admin.subscriber.remove"),
                    'icon' => 'la la-trash',
                    'class' => 'btn btn-outline--danger confirmationBtn',
                    'attributes' => [
                        'data-action' => 'route(\'admin.subscriber.remove\', $item->id)',
                        'data-question' => 'trans("Are you sure to remove this subscriber?")',
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

    <x-viser_table.table :data="$subscribers" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" />

    <x-confirmation-modal />
@endsection

@if($subscribers->count())
    @push('breadcrumb-plugins')
        <a href="{{ route('admin.subscriber.send.email') }}" class="btn btn-sm btn-outline--primary" ><i class="las la-paper-plane"></i>@lang('Send Email')</a>
    @endpush
@endif
