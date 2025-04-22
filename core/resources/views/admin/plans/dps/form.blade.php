@extends('admin.layouts.app')

@push('topBar')
    @include('admin.plans.top_bar')
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <form action="{{ route('admin.plans.dps.save', $plan->id ?? 0) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>@lang('Name')</label>
                                <input class="form-control" name="name" type="text" value="{{ old('name', @$plan->name) }}" required>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Installment Interval')</label>
                                <div class="input-group">
                                    <input class="form-control" name="installment_interval" type="number" value="{{ old('installment_interval', @$plan->installment_interval) }}" required>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Total Installment')</label>
                                <input class="form-control" name="total_installment" type="number" value="{{ old('total_installment', @$plan->total_installment) }}" required>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Per Installment')</label>
                                <div class="input-group">
                                    <input class="form-control" name="per_installment" type="number" value="{{ old('per_installment', @$plan->per_installment) }}" step="any" required>
                                    <span class="input-group-text">@lang(gs()->cur_text)</span>
                                </div>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Interest Rate')</label>
                                <div class="input-group">
                                    <input class="form-control" name="interest_rate" type="number" value="{{ old('interest_rate', @$plan->interest_rate) }}" step="any" required>
                                    <span class="input-group-text">@lang('%')</span>
                                </div>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Total Deposit')</label>
                                <div class="input-group">
                                    <input class="form-control total_deposit" type="text" readonly>
                                    <span class="input-group-text">@lang(gs()->cur_text)</span>
                                </div>
                            </div>

                            <div class="form-group col-lg-6">
                                <label>@lang('User\'s Profit')</label>
                                <div class="input-group">
                                    <input class="form-control profit-amount" type="text" readonly>
                                    <span class="input-group-text">@lang(gs()->cur_text)</span>
                                </div>
                            </div>

                            <div class="form-group col-lg-6">
                                <label>@lang('Total Mature Amount')</label>
                                <div class="input-group">
                                    <input class="form-control mature-amount" type="text" readonly>
                                    <span class="input-group-text">@lang(gs()->cur_text)</span>
                                </div>
                            </div>
                        </div>

                        <h6 class="border-bottom my-3 pb-3 text-center">
                            @lang('Installment Delay Charge') <i class="fa fa-info-circle text--primary" title="@lang('This charge will be apply for each delayed installment. The total amount of charge will be subtracted from the mature amount.')"></i>
                        </h6>

                        <div class="row">

                            <div class="form-group col-lg-4">
                                <label>@lang('Charge will Apply If Delay')</label>
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
                    @can('admin.plans.dps.save')
                        <div class="card-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>
                    @endcan
                </form>
            </div><!-- card end -->
        </div>
    </div>
@endsection

@can('admin.plans.dps.index')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.plans.dps.index') }}" />
    @endpush
@endcan

@push('script')
    <script>
        (function($) {
            "use strict";
            $('[name=per_installment], [name=total_installment], [name=interest_rate]').on('input', () => calculateProfit());

            function calculateProfit() {
                let perInstallment = Number($('[name=per_installment]').val());
                let totalInstallment = Number($('[name=total_installment]').val());
                let interestRate = Number($('[name=interest_rate]').val());
                let totalAmount = perInstallment * totalInstallment;
                let interest = totalAmount * interestRate / 100;

                if (perInstallment && totalInstallment && interestRate) {
                    $('.total_deposit').val(showAmount(totalAmount));
                    $('.profit-amount').val(showAmount(interest));
                    $('.mature-amount').val(showAmount(totalAmount + interest));
                }
            };

            calculateProfit();
        })(jQuery);
    </script>
@endpush
