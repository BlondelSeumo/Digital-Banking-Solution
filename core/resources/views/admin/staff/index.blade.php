@extends('admin.layouts.app')
@push('topBar')
    @include('admin.staff.top_bar')
@endpush

@section('panel')

    @php
        $request = request();
        $tableName = 'staff_list';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);
        $roleOptions = $roles->pluck('name')->toArray();
        sort($roleOptions);

        $statusOptions = ['Active', 'Banned'];

        $columns = collect([
            prepareTableColumn('username', 'Username'),
            prepareTableColumn('name', 'Name'),
            prepareTableColumn('email', 'Email'),
            prepareTableColumn('role_name', 'Role', filter: 'select', filterOptions: $roleOptions),
            prepareTableColumn('status_text', 'Status', '$item->status_badge', filter:'select', filterOptions:$statusOptions, echoable:true),
            prepareTableColumn('created_at', 'Added On', 'showDateTime("$item->created_at", "d M, Y")', filter: 'date')
        ]);

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => can("admin.staff.save") && can("admin.staff.status") && can("admin.staff.login"),
            'buttons' => [
                [
                    'name' => 'Edit',
                    'show' => 'can("admin.staff.save") && $item->id > 1',
                    'class' => 'cuModalBtn',
                    'icon'=> 'la la-pencil',
                    'attributes' => [
                        'data-resource' => 'json_encode($item) ',
                        'data-modal_title' => 'trans("Update Staff")'
                    ]
                ],
                [
                    'name' => 'Ban',
                    'show' => 'can("admin.staff.status") && $item->status && $item->id > 1',
                    'class' => 'confirmationBtn',
                    'icon'=> 'la la-user-times',
                    'attributes' => [
                        'data-action' => 'route(\'admin.staff.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to ban this staff?")'
                    ]
                ],
                [
                    'name' => 'Unban',
                    'show' => 'can("admin.staff.status") && !$item->status && $item->id > 1',
                    'class' => 'confirmationBtn',
                    'icon'=> 'la la-user-check',
                    'attributes' => [
                        'data-action' => 'route(\'admin.staff.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to ban this staff?")'
                    ]
                ],
                [
                    'name' => 'Login As Staff',
                    'icon' => 'la la-sign-in-alt',
                    'link' => 'route("admin.staff.login", $item->id)',
                    'show' => 'can("admin.staff.login") && $item->id > 1',
                ]
            ],
        ];

        if($tableConfiguration){
            $visibleColumns = $tableConfiguration->visible_columns;
        }else{
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp



    <x-viser_table.table :data="$allStaff" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" />

    <x-confirmation-modal />

    <!-- Create Update Modal -->
    <div class="modal fade" id="cuModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Staff')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.staff.save') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Username')</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Role')</label>
                            <select name="role_id" class="form-control" required>
                                <option value="" disabled selected>@lang('Select One')</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('Password')</label>
                            <div class="input-group">
                                <input class="form-control" name="password" type="text" required>
                                <button class="input-group-text generatePassword" type="button">@lang('Generate')</button>
                            </div>
                        </div>
                    </div>
                    @can('admin.staff.save')
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @can('admin.staff.save')
        <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add New Staff')">
            <i class="las la-plus"></i>@lang('Add New')
        </button>
    @endcan
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.generatePassword').on('click', function() {
                $(this).siblings('[name=password]').val(generatePassword());
            });

            $('.cuModalBtn').on('click', function() {
                let passwordField = $('#cuModal').find($('[name=password]'));
                let label = passwordField.parents('.form-group').find('label')
                if ($(this).data('resource')) {
                    passwordField.removeAttr('required');
                    label.removeClass('required')
                } else {
                    passwordField.attr('required', 'required');
                    label.addClass('required')
                }
            });


            function generatePassword(length = 12) {
                let charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+<>?/";
                let password = '';
                for (var i = 0, n = charset.length; i < length; ++i) {
                    password += charset.charAt(Math.floor(Math.random() * n));
                }

                return password
            }

            if (new URLSearchParams(window.location.search).has('addnew')) {
                let cuModal = new bootstrap.Modal(document.getElementById('cuModal'));
                cuModal.show();
            }

        })(jQuery);
    </script>
@endpush
