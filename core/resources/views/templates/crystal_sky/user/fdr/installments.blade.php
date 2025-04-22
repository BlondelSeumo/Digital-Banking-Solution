@extends($activeTemplate . 'user.fdr.layout')
@section('fdr-content')
    <div class="row gy-4">
        <div class="col-lg-12 col-xl-4">
            <div class="card custom--card">
                <div class="card-body">
                    <ul>
                        <li class="pricing-card__list flex-between">
                            <span>@lang('FDR Number')</span>
                            <span class="fw-bold">{{ $fdr->fdr_number }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Plan')</span>
                            <span class="fw-bold">{{ $fdr->plan->name }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Deposited')</span>
                            <span class="fw-bold">{{ showAmount($fdr->amount) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Interest Rate')</span>
                            <span class="fw-bold">{{ getAmount($fdr->interest_rate) }}%</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Per Installment')</span>
                            <span class="fw-bold text--base">{{ showAmount($fdr->per_installment) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Received Installment')</span>
                            <span class="fw-bold">{{ $fdr->installments->count() }} </span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span>@lang('Profit Received')</span>
                            <span class="fw-bold">{{ showAmount($fdr->profit) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-xl-8">
            @include($activeTemplate . 'partials.installment_table')
        </div>
    </div>
@endsection
