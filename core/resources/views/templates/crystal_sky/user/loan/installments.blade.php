@extends($activeTemplate . 'user.loan.layout')
@section('loan-content')
    <div class="row gy-4">
        <div class="col-xl-4">
            <div class="card custom--card">
                <div class="card-body">
                    <ul>
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">{{ $loan->loan_number }}</span>
                            <span>@lang('Loan Number')</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">{{ $loan->plan->name }}</span>
                            <span>@lang('Plan')</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">{{ showAmount($loan->amount) }}</span>
                            <span>@lang('Loan Amount')</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold text--base">{{ showAmount($loan->per_installment) }}</span>
                            <span>@lang('Per Installment')</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">{{ $loan->total_installment }}</span>
                            <span>@lang('Total Installment')</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">{{ $loan->given_installment }}</span>
                            <span>@lang('Given Installment')</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold text--warning">{{showAmount($loan->payable_amount) }}</span>
                            <span>@lang('Needs to Pay')</span>
                        </li>

                        @if (getAmount($loan->charge_per_installment))
                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">{{ showAmount($loan->charge_per_installment) }} /@lang('Day')</span>
                                <span>@lang('Delay Charge')</span>
                                <small class="text--danger mt-2">@lang('Charge will be applied if an installment delayed for') {{ $loan->delay_value }} @lang(' or more days')</small>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            @include($activeTemplate . 'partials.installment_table')
        </div>
    </div>
@endsection
