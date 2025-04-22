@extends('pdf.layouts.master')
@section('main-content')
    <div class="pdf-card">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-12">
                    <div class="custom--card">
                        <div class="card-body">
                            <h5 class="text-center">
                                @lang('Your DPS invest information')
                            </h5>

                            <ul class="caption-list-two mt-3">
                                <li>
                                    <span class="caption">@lang('DPS No.')</span>
                                    <span class="value">{{ $dps->dps_number }}</span>
                                </li>
                                <li>
                                    <span class="caption">@lang('Plan')</span>
                                    <span class="value">{{ __($dps->plan->name) }}</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Installment Interval')</span>
                                    <span class="value">{{ $dps->plan->installment_interval }} {{__(Str::plural('Day', $dps->plan->installment_interval))}}</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Total Installment')</span>
                                    <span class="value">{{ $dps->plan->total_installment }}</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Per Installment')</span>
                                    <span class="value">{{ showAmount($dps->plan->per_installment) }}</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Total Deposit')</span>
                                    <span class="value">{{ showAmount($dps->plan->per_installment * $dps->plan->total_installment) }}</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Profit Rate')</span>
                                    <span class="value">{{ getAmount($dps->plan->interest_rate) }}%</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Withdrawable Amount')</small></span>
                                    <span class="value fw-bold">{{ showAmount($dps->plan->final_amount) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
