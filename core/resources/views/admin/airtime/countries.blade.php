@extends('admin.layouts.app')

@push('topBar')
    @include('admin.airtime.top_bar')
@endpush

@section('panel')
    @php
        $request = request();
        $tableName = 'airtime_countries';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);
        $continents         = App\Models\Country::select('continent')->distinct('continent')->get()->pluck('continent')->toArray();
        $statusOptions      = ['1' => 'Active', '0' => 'Banned'];

        $columns = collect([
            prepareTableColumn('name', 'Name'),
            prepareTableColumn('iso_name', 'ISO Name'),
            prepareTableColumn('continent', 'Continent', filter: 'select', filterOptions: $continents),
            prepareTableColumn('currency_code', 'Currency Code'),
            prepareTableColumn('currency_name', 'Currency Name'),
            prepareTableColumn('currency_symbol', 'Currency Symbol'),
            prepareTableColumn('calling_codes', 'Calling Codes', 'implode(",", $item->calling_codes)'),
            prepareTableColumn('operators_count', 'Operators', filter: 'range'),
            prepareTableColumn('status', 'Status', '$item->status_badge', filter: 'select', filterOptions: $statusOptions, echoable:true),
        ]);

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => can('admin.airtime.operators') || can('admin.airtime.country.status'),
            'buttons' => [
                [
                    'name' => 'Operators',
                    'icon' => 'la la-list',
                    'link' => 'route("admin.airtime.operators", $item->iso_name)',
                    'show' => can('admin.airtime.operators'),
                ],
                [
                    'name' => 'Disable',
                    'show' => 'can("admin.airtime.country.status") && $item->status',
                    'class' => 'confirmationBtn',
                    'icon' => 'la la-eye-slash',
                    'attributes' => [
                        'data-action' => 'route(\'admin.airtime.country.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to disable this country?")',
                    ],
                ],
                [
                    'name' => 'Enable',
                    'show' => 'can("admin.airtime.country.status") && !$item->status',
                    'class' => 'confirmationBtn',
                    'icon' => 'la la-eye',
                    'attributes' => [
                        'data-action' => 'route(\'admin.airtime.country.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to enable this country?")',
                    ],
                ],
            ],
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$countries" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns"
        class="table-responsive--md table-responsive" />

    @can('admin.airtime.country.status')
        <x-confirmation-modal />
    @endcan
@endsection

@push('breadcrumb-plugins')
    @can('admin.airtime.countries.fetch')
        <a href="{{ route('admin.airtime.countries.fetch') }}" class="btn btn--dark"> <i class="lab la-telegram-plane"></i>
            @if ($countries->count())
                @lang('Fetch More Countries')
            @else
                @lang('Fetch Countries')
            @endif
        </a>
    @endcan
@endpush
