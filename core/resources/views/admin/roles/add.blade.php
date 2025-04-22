@extends('admin.layouts.app')
@push('topBar')
    @include('admin.staff.top_bar')
@endpush

@section('panel')
    @php
        $settingId = App\Models\Permission::where('code', 'admin.setting.system')->first()?->id;

        $settings = json_decode(file_get_contents(resource_path('views/admin/setting/settings.json')));
        $settingRoutes = collect($settings)->pluck('route_name')->flatten()->toArray();
        $permissionIds = App\Models\Permission::where(function ($q) use ($settingRoutes, $settingId) {
            $q->whereIn('code', $settingRoutes)->orWhere('group', 'SystemSettingsController');
        })
            ->where('id', '!=', $settingId)
            ->get('id')
            ->pluck('id')
            ->toArray();

    @endphp

    <form action="{{ route('admin.roles.save', @$role->id) }}" method="post">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">@lang('Name')</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', @$role->name) }}">
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Set Permissions')</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($permissionGroups as $key => $permissionGroup)
                            <div class="permission-item">
                                @php
                                    $group = Str::replaceLast('Controller', '', $key);
                                @endphp
                                <div class="d-flex gap-2 align-items-center mb-4">
                                    <div class="form-switch form-switch-success permission-check">
                                        <input type="checkbox" class="form-check-input">
                                    </div>
                                    <p class="fw-bold permission-item-title">
                                        {{ camelCaseToTitleCase($group) }}
                                    </p>
                                </div>
                                <div class="permission-item-wrapper">
                                    @foreach ($permissionGroup as $permission)
                                        <div class="form-switch form-switch-success">
                                            <input type="checkbox" class="form-check-input exclude" name="permissions[]" value="{{ $permission->id }}" id="customCheck{{ $permission->id }}">
                                            <label class="form-check-label" for="customCheck{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @can('admin.roles.save')
                <div class="col-lg-12 floating-button d-flex justify-content-end">
                    <button type="submit" class="btn btn--primary h-45" id="submitButton">@lang('Submit')</button>
                </div>
            @endcan
        </div>
    </form>
@endsection

@can('admin.roles.index')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.roles.index') }}" />
    @endpush
@endcan

@push('style')
    <style>
        .floating-button {
            position: sticky;
            bottom: 15px;
        }

        #submitButton {
            width: 160px !important;
        }

        .permission-item .form-check-label {
            margin-bottom: 0;
        }

        .permission-item {
            background: #00000005;
            border: 1px solid #f7f7f7;
            padding: 1rem;
            border-radius: 5px;
        }

        .permission-item:not(:last-child) {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid hsl(var(--black) / .05);
        }

        .permission-item .form-switch {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .permission-check {
            padding: 0;
            min-height: unset;
            margin: 0;
        }

        .permission-check .form-check-input {
            margin: 0;
        }

        .permission-item-title {
            font-size: 1rem !important;
            line-height: 1;
        }

        .permission-item-wrapper {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px 10px;
        }

        @media(max-width:1400px) {
            .permission-item-wrapper {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width: 1300px) {
            .permission-item-wrapper {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 768px) {
            .permission-item-wrapper {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        .form-switch .form-check-input {
            margin-top: -1px;
        }

        .form-check-input:focus {
            box-shadow: none;
        }

        .permission-item-wrapper .form-switch {
            padding-left: 2.2em !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const settingIds = @json($permissionIds);
            const settingId = @json($settingId)


            @isset($permissions)
                $('input[name="permissions[]"]').val(@json($permissions));
            @endif

            $('.permission-check input').on('click', function() {
                $(this).parents('.permission-item').find('.permission-item-wrapper input').prop('checked', $(this).prop('checked'));

            });

            $('.permission-item-wrapper input').on('click', function() {
                let permissions = $(this).parents('.permission-item-wrapper').find('input');
                let checkedPermissions = $(this).parents('.permission-item-wrapper').find('input:checked');

                if (permissions.length == checkedPermissions.length) {
                    $(this).parents('.permission-item').find('.permission-check input').prop('checked', true);
                } else {
                    $(this).parents('.permission-item').find('.permission-check input').prop('checked', false);
                }
            });

            $('.permission-item input').on('change', function() {
                const selectedValues = [];

                if ($(this).val() == settingId) {
                    return;
                }

                $('.permission-item-wrapper input:checked').each((i, element) => {
                    selectedValues.push(element.value * 1);

                    const hasCommonValue = selectedValues.some(value => settingIds.includes(value));

                    $(`[name="permissions[]"][value="${settingId}"]`).prop('checked', hasCommonValue)
                });
            });

            $(document).on('scroll', function() {
                floatSubmitButton();
            });

            function floatSubmitButton() {
                let isAtBottom = $(window).scrollTop() + $(window).height() >= $(document).height();

                if (isAtBottom) {
                    $('#submitButton').parent().removeClass('floating-button');
                } else {
                    $('#submitButton').parent().addClass('floating-button');
                }
            }

            floatSubmitButton();

        })(jQuery);
    </script>
@endpush
