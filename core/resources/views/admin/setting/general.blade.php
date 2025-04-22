@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <form method="POST" action="{{ route('admin.setting.update') }}">
                <div class="card">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Site Title')</label>
                                    <input class="form-control" type="text" name="site_name" required value="{{ gs('site_name') }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Currency')</label>
                                    <input class="form-control" type="text" name="cur_text" required value="{{ gs('cur_text') }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Currency Symbol')</label>
                                    <input class="form-control" type="text" name="cur_sym" required value="{{ gs('cur_sym') }}">
                                </div>
                            </div>
                            <div class="form-group col-xl-3 col-sm-6">
                                <label class="required"> @lang('Timezone')</label>
                                <select class="select2 form-control" name="timezone">
                                    @foreach ($timezones as $key => $timezone)
                                        <option value="{{ @$key }}" @selected(@$key == $currentTimezone)>{{ __($timezone) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xl-3 col-sm-6">
                                <label cla> @lang('Site Base Color')</label>
                                <div class="input-group">
                                    <span class="input-group-text p-0 border-0">
                                        <input type='text' class="form-control colorPicker" value="{{ gs('base_color') }}">
                                    </span>
                                    <input type="text" class="form-control colorCode" name="base_color" value="{{ gs('base_color') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-xl-3 col-sm-6">
                                <label cla> @lang('Site Secondary Color')</label>
                                <div class="input-group">
                                    <span class="input-group-text p-0 border-0">
                                        <input type='text' class="form-control colorPicker" value="{{ gs('secondary_color') }}">
                                    </span>
                                    <input type="text" class="form-control colorCode" name="secondary_color" value="{{ gs('secondary_color') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-xl-3 col-sm-6">
                                <label> @lang('Record to Display Per page')</label>
                                <select class="select2 form-control" name="paginate_number" data-minimum-results-for-search="-1" required>

                                    @for ($i = 5; $i <= 100; $i += 5)
                                        <option value="{{ $i }}" @selected(gs('paginate_number') == $i)> {{ $i }} @lang('items per page')</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group col-xl-3 col-sm-6 ">
                                <label> @lang('Currency Showing Format')</label>
                                <select class="select2 form-control" name="currency_format" data-minimum-results-for-search="-1" required>
                                    <option value="1" @selected(gs('currency_format') == Status::CUR_BOTH)>@lang('Show Currency Text and Symbol Both')</option>
                                    <option value="2" @selected(gs('currency_format') == Status::CUR_TEXT)>@lang('Show Currency Text Only')</option>
                                    <option value="3" @selected(gs('currency_format') == Status::CUR_SYM)>@lang('Show Currency Symbol Only')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3 col-sm-6">
                                <label>@lang('Account Number Prefix') <i class="fa fa-info-circle text--primary" title="@lang('This text will be added with every Account Number as a prefix.')"></i></label>
                                <input class="form-control" name="account_no_prefix" type="text" value="{{ gs()->account_no_prefix }}">
                            </div>

                            <div class="form-group col-md-3 col-sm-6">
                                <label>@lang('Account Number Length') <i class="fa fa-info-circle text--primary" title="@lang('The number of digits for an account number without the prefix.')"></i></label>
                                <input class="form-control" name="account_no_length" type="number" value="{{ gs()->account_no_length }}">
                            </div>

                            <div class="form-group col-md-3 col-sm-12">
                                <label>@lang('OTP Expiration Time') <i class="fa fa-info-circle text--primary" title="@lang('How long an OTP is valid. The Users need to verify the OTP code for any money-out transaction from this system if the OTP module is enabled.')"></i></label>
                                <div class="input-group">
                                    <input class="form-control" name="otp_time" type="number" value="{{ getAmount(gs()->otp_time) }}">
                                    <span class="input-group-text"> @lang('Seconds')</span>
                                </div>
                            </div>

                            <div class="form-group col-md-3 col-sm-12">
                                <label>
                                    @lang('User Idle Time')
                                    <i class="fa fa-info-circle text--primary" title="@lang('How long a user can stay idle without any interaction. After that certain period the user will be logged out from the system')"></i>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="idle_time_threshold" type="number" value="{{ gs()->idle_time_threshold }}" />
                                    <span class="input-group-text"> @lang('Seconds')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title text-center">@lang('Transfer Limits within') {{ __(gs()->site_name) }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-4">
                                <label>
                                    @lang('Minimum Limit') <i class="fas fa-info-circle text--primary" title="@lang('For each Money Transfer within ' . gs()->site_name . ', Users can\'t transfer money less than the Minimum Transfer Limit.')"></i>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="minimum_transfer_limit" type="number" value="{{ getAmount(gs()->minimum_transfer_limit) }}" step="any">
                                    <span class="input-group-text currency-text">@lang(gs()->cur_text)</span>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 col-md-4">
                                <label>
                                    @lang('Daily Limit')
                                    <i class="fas fa-info-circle text--primary" title="@lang('The maximum amount that can be transferred on a particular date.')"></i>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="daily_transfer_limit" type="number" value="{{ getAmount(gs()->daily_transfer_limit) }}" step="any">
                                    <span class="input-group-text currency-text">@lang(gs()->cur_text)</span>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label>
                                    @lang('Monthly Limit')
                                    <i class="fas fa-info-circle text--primary" title="@lang('The maximum amount that can be transferred on a particular month.')"></i>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="monthly_transfer_limit" type="number" value="{{ getAmount(gs()->monthly_transfer_limit) }}" step="any">
                                    <span class="input-group-text currency-text">@lang(gs()->cur_text)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title text-center">@lang('Transfer Charges within') {{ __(gs()->site_name) }}</h6>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>@lang('Fixed Charge')</label>
                                <div class="input-group">
                                    <input class="form-control" name="fixed_transfer_charge" type="number" value="{{ getAmount(gs()->fixed_transfer_charge) }}" step="any">
                                    <span class="input-group-text currency-text">@lang(gs()->cur_text) </span>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label>@lang('Percent Charge')</label>
                                <div class="input-group">
                                    <input class="form-control" name="percent_transfer_charge" type="number" value="{{ getAmount(gs()->percent_transfer_charge) }}" step="any">
                                    <span class="input-group-text currency-text">%</span>
                                </div>
                            </div>
                        </div>

                        <small>
                            <i class="la la-info-circle text--primary"></i>
                            <i class="text-muted">@lang('Fixed + Percent charge amount will be applied on each transfer within') {{ __(gs()->site_name) }}</i>
                        </small>
                    </div>
                </div>

                @can('admin.setting.update')
                    <button class="btn btn--primary w-100 h-45 mt-3" type="submit">@lang('Submit')</button>
                @endcan
            </form>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });
        })(jQuery);
    </script>
@endpush
