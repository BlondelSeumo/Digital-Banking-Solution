@extends($activeTemplate . 'user.dps.layout')
@section('dps-content')
    <div class="row gy-4">
        <div class="col-lg-12 col-xl-4">
            <div class="card custom--card">
                <div class="card-body">
                    <ul>
                        <li class="pricing-card__list flex-between">
                            <span>@lang('DPS Number')</span>
                            <span class="fw-bold">{{ $dps->dps_number }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Plan')</span>
                            <span class="fw-bold">{{ $dps->plan->name }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Interest Rate')</span>
                            <span class="fw-bold">{{ getAmount($dps->interest_rate) }}%</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Per Installment')</span>
                            <span class="value text--base text--base fw-bold">{{ getAmount($dps->per_installment) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Given Installment')</span>
                            <span class="fw-bold">{{ $dps->given_installment }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Total Installment')</span>
                            <span class="fw-bold">{{ $dps->total_installment }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Total Deposit')</span>
                            <span class="fw-bold">{{ showAmount($dps->depositedAmount()) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Profit')</span>
                            <span class="fw-bold">{{ showAmount($dps->profitAmount()) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Including Profit')</span>
                            <span class="fw-bold">{{ showAmount($dps->depositedAmount() + $dps->profitAmount()) }}</span>
                        </li>

                        @if (getAmount($dps->charge_per_installment))
                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">{{ showAmount($dps->charge_per_installment) }} /@lang('Day')</span>
                                <span>@lang('Delay Charge')</span>
                                <small class="text--danger mt-2">@lang('Charge will be applied if an installment delayed for') {{ $dps->delay_value }} @lang(' or more days')</small>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-xl-8">
            @include($activeTemplate . 'partials.installment_table')
        </div>
    </div>
@endsection

