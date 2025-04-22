@extends('admin.layouts.app')
@push('topBar')
    @include('admin.plans.top_bar')
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.plans.loan.save', $plan->id ?? 0) }}" method="POST">
                @csrf
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Plan Name')</label>
                                    <input class="form-control" name="name" type="text" value="{{ @$plan->name }}" required />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Minimum Amount')</label>
                                    <div class="input-group">
                                        @php $minAmount = isset($plan) ? getAmount($plan->minimum_amount) : null; @endphp
                                        <input class="form-control" name="minimum_amount" type="number" value="{{ old('number', $minAmount) }}" step="any" required />
                                        <span class="input-group-text"> {{ __(gs()->cur_text) }} </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Maximum Amount')</label>
                                    <div class="input-group">
                                        @php $maxAmount = isset($plan) ? getAmount($plan->maximum_amount) : null; @endphp
                                        <input class="form-control" name="maximum_amount" type="number" value="{{ old('number', $maxAmount) }}" step="any" required />
                                        <span class="input-group-text"> {{ __(gs()->cur_text) }} </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Per Installment')</label>
                                    <div class="input-group">
                                        @php $perInstallment = isset($plan) ? getAmount($plan->per_installment) : null; @endphp
                                        <input class="form-control" name="per_installment" type="number" value="{{ old('per_installment', $perInstallment) }}" step="any" required />
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Installment Interval')</label>
                                    <div class="input-group">
                                        @php $installmentInterval = isset($plan) ? getAmount($plan->installment_interval) : null; @endphp
                                        <input class="form-control" name="installment_interval" type="number" value="{{ old('installment_interval', $installmentInterval) }}" required />
                                        <span class="input-group-text">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Total Installments')</label>
                                    <div class="input-group">
                                        @php $totalInstallment = isset($plan) ? getAmount($plan->total_installment) : null; @endphp
                                        <input class="form-control" name="total_installment" type="number" value="{{ old('total_installment', $totalInstallment) }}" required />
                                        <span class="input-group-text">@lang('Times')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Admin\'s Profit')</label>
                                    <div class="input-group">
                                        @php $installmentInterval = isset($plan) ? getAmount($plan->installment_interval) : null; @endphp
                                        <input class="form-control admins_profit" type="number" disabled />
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label>@lang('Instruction')</label>
                                <div class="form-group">
                                    <textarea class="form-control border-radius-5 nicEdit" name="instruction" rows="8">@php echo @$plan->instruction @endphp</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title text-center">
                            @lang('Installment Delay Charge') <i class="fa fa-info-circle text--primary" title="@lang('This charge will be apply for each delayed installment. The user needs to pay the charge with the installment amount.')"></i>
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>@lang('Charge Will Apply If Delay')</label>
                                <div class="input-group">
                                    <input class="form-control" name="delay_value" type="number" value="{{ old('delay_value', @$plan->delay_value) }}" required>
                                    <span class="input-group-text">@lang('Day')</span>
                                </div>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Fixed Charge')</label>
                                <div class="input-group">
                                    <input class="form-control" name="fixed_charge" type="number" value="{{ old('fixed_charge', @$plan->fixed_charge) }}" step="any" required>
                                    <span class="input-group-text">@lang(gs()->cur_text)</span>
                                </div>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Percent Charge')</label>
                                <div class="input-group">
                                    <input class="form-control" name="percent_charge" type="number" value="{{ old('percent_charge', @$plan->percent_charge) }}" step="any" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>@lang('Loan Application Form Fields')</h5>
                                <button type="button" class="btn btn--primary float-end form-generate-btn"> <i class="la la-fw la-plus"></i>@lang('Add New')</button>
                            </div>

                            <div class="card-body">
                                <x-generated-form :form="@$plan->form"></x-generated-form>
                            </div>
                        </div>
                    </div>
                </div>
                @can('admin.plans.loan.save')
                    <button class="btn btn--primary w-100 h-45 mt-3" type="submit">@lang('Submit')</button>
                @endcan
            </form>
        </div><!-- card end -->
    </div>
    <x-form-generator-modal />
@endsection

@can('admin.plans.loan.index')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.plans.loan.index') }}" />
    @endpush
@endcan

@push('script')
    <script>
        "use strict";
        (function($) {
            $('[name=per_installment], [name=total_installment]').on('input ', (e) => displayProfit());

            function displayProfit() {
                let totalInstallment = parseFloat($('[name=total_installment]').val());
                let perInstallment = parseFloat($('[name=per_installment]').val());
                let profit = (totalInstallment * perInstallment).toFixed(2);
                profit -= 100;
                $('.admins_profit').val(profit);
                if (profit <= 0) {
                    $('.admins_profit').css('border-color', 'red');
                    $('.admins_profit').siblings('.input-group-text').css('border-color', 'red');
                } else {
                    $('.admins_profit').removeAttr('style');
                    $('.admins_profit').siblings('.input-group-text').removeAttr('style');
                }
            }
            displayProfit();
        })(jQuery);
    </script>
@endpush
