<div class="card b-radius--10">
    @if (@$staff)
        <div class="card-header">
            <h5 class="card-title">@lang('Update Staff Details')</h5>
        </div>
    @endif
    <form id="staffForm" action="{{ route('admin.branch.staff.save', @$staff->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Name')</label>
                        <input class="form-control" name="name" type="text" value="{{ old('name', @$staff->name) }}" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Mobile')</label>
                        <input class="form-control" name="mobile" type="tel" value="{{ old('mobile', @$staff->mobile) }}" required>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Email')</label>
                        <input class="form-control" name="email" type="email" value="{{ old('email', @$staff->email) }}" required>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Password')</label>
                        <div class="input-group">
                            <input class="form-control" name="password" type="text" value="{{ old('password') }}" @if (!isset($staff)) required @endif>
                            <button class="input-group-text generatePassword" type="button">@lang('Generate')</button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Staff Resume/CV')</label>
                        <input class="form-control" name="resume" type="file" accept=".pdf, .docx">
                        <small class="mt-2">@lang('Supported Files'): <b>@lang('pdf'), @lang('docx').</b> </small>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Designation')</label>
                        <select class="form-control" name="designation" required>
                            <option value="" selected disabled>@lang('Select One')</option>
                            <option value="{{ Status::ROLE_MANAGER }}" @selected(@$staff->designation == Status::ROLE_MANAGER)>@lang('Manager')</option>
                            <option value="{{ Status::ROLE_ACCOUNT_OFFICER }}" @selected(@$staff->designation == Status::ROLE_ACCOUNT_OFFICER)>@lang('Staff')</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label>@lang('Branch')</label> @can('admin.branch.add')<a href="{{ route('admin.branch.add') }}">@lang('Add New Branch')</a>@endcan
                        </div>
                        <select class="form-control select2-basic" id="branch" name="branch[]" required>
                            <option value="" disabled selected>@lang('Select One')</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ __($branch->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>@lang('Address')</label>
                        <input class="form-control" name="address" type="text" value="{{ old('address', @$staff->name) }}" required>
                    </div>
                </div>

            </div>
        </div>

        @can('admin.branch.staff.save')
            <div class="card-footer">
                <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
            </div>
        @endcan
    </form>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.select2-basic').select2({
                dropdownParent: $(".card-body")
            });

            $('select[name=designation]').on('change', function(e) {

                if ($(this).val() == `{{ Status::ROLE_MANAGER }}`) {
                    $("#branch").attr('multiple', true);
                } else {
                    $("#branch").attr('multiple', false);
                }
                $('#branch').select2({
                    dropdownParent: $('#branch').parent()
                });
            }).change();

            @if (@$staff)
                $('#branch').val(@json($staff->branch_id)).select2({
                    dropdownParent: $('#branch').parent()
                });
            @endif

            $('.generatePassword').on('click', function() {
                $(this).siblings('[name=password]').val(generatePassword());
            });

            function generatePassword(length = 12) {
                let charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+<>?/";
                let password = '';

                for (var i = 0, n = charset.length; i < length; ++i) {
                    password += charset.charAt(Math.floor(Math.random() * n));
                }

                return password
            }

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .select2-container .select2-selection--multiple .select2-selection__rendered li[title="Select One"] {
            display: none;
        }
    </style>
@endpush
