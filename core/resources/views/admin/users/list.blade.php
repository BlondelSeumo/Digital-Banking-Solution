@extends('admin.layouts.app')
@section('panel')
    @php
        $request = request();
        $tableName = 'users_list';
        $branches = App\Models\Branch::orderBy('name')->get()->pluck('name')->toArray();
        $branches[] = 'Online';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $countries = collect($countries)->map(function($country){
            return $country->country;
        });

        $countryOptions = array_values($countries->toArray());

        $columns = collect([
            prepareTableColumn('account_number', 'Account No.'),
            prepareTableColumn('username', 'Username'),
            prepareTableColumn('fullname', 'Name'),
            prepareTableColumn('email', 'Email'),
            prepareTableColumn('mobile', 'Mobile'),
            prepareTableColumn('country_name', 'Country', filter: 'select', filterOptions: $countryOptions),
            prepareTableColumn('state', 'State', filter: 'text'),
            prepareTableColumn('city', 'City', filter: 'text'),
            prepareTableColumn('zip', 'Zip', filter: 'text'),
            prepareTableColumn('branch_name', 'Branch', filter: 'select', filterColumn: 'branch_name', filterOptions: $branches),
            prepareTableColumn('balance', 'Balance', 'showAmount($item->balance)', filter: 'range'),
            prepareTableColumn('created_at', 'Registered At', 'showDateTime("$item->created_at", "d M, Y")', filter: 'date')
        ]);

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => can('admin.users.detail') || can('admin.users.kyc.details') || can('admin.users.login') || can('admin.report.login.history') || can('admin.users.notification.log') || can('admin.users.notification.single'),
            'buttons' => [
                [
                    'name' => 'View Details',
                    'link' => 'route("admin.users.detail", $item->id)',
                    'show' => can('admin.users.detail'),
                ],
                [
                    'name' => 'View KYC Data',
                    'link' => 'route("admin.users.kyc.details", $item->id)',
                    'show' => can('admin.users.kyc.details'),
                ],
                [
                    'name' => 'Login As User',
                    'link' => 'route("admin.users.login", $item->id)',
                    'show' => can('admin.users.login'),
                    'attributes' => [
                        'target' => "json_encode('blank')"
                    ]
                ],
                [
                    'name' => 'Login History',
                    'link' => 'route("admin.report.login.history", $item->id)',
                    'show' => can('admin.report.login.history'),
                ],
                [
                    'name' => 'Send Notification',
                    'link' => 'route("admin.users.notification.single", $item->id)',
                    'show' => can('admin.users.notification.single'),
                ],
                [
                    'name' => 'All Notifications',
                    'link' => 'route("admin.users.notification.log", $item->id)',
                    'show' => can('admin.users.notification.log'),
                ],
            ],
        ];

        if($tableConfiguration){
            $visibleColumns = $tableConfiguration->visible_columns;
        }else{
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$users" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" />
@endsection

@if($users->total() > 0 && can('admin.users.notification.all.send'))
@push('breadcrumb-plugins')
    <a href="{{appendQuery('notify', 1)}}" class="btn btn--dark">
        <i class="fas fa-bell"></i>
        @lang('Notify') <strong class="mx-1">{{$users->total()}}</strong> {{__(str_replace('All', '' ,$pageTitle))}} @lang('Holders')
        @if($request->has('filter'))(@lang('Filtered'))@endif
    </a>
    @endpush
@endif
