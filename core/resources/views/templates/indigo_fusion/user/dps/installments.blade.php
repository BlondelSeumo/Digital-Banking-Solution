@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4">
        <div class="col-sm-5 col-lg-3">
            <div class="card custom--card">

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span class="value">{{ $dps->dps_number }}</span>
                            <span class="caption">@lang('DPS Number')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $dps->plan->name }}</span>
                            <span class="caption">@lang('Plan')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ getAmount($dps->interest_rate) }}%</span>
                            <span class="caption">@lang('Interest Rate')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value text--base text--base fw-bold">{{ getAmount($dps->per_installment) }}</span>
                            <span class="caption">@lang('Per Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $dps->given_installment }}</span>
                            <span class="caption">@lang('Given Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $dps->total_installment }}</span>
                            <span class="caption">@lang('Total Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ showAmount($dps->depositedAmount()) }}</span>
                            <span class="caption">@lang('Total Deposit')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ showAmount($dps->profitAmount()) }}</span>
                            <span class="caption">@lang('Profit')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ showAmount($dps->depositedAmount() + $dps->profitAmount()) }}</span>
                            <span class="caption">@lang('Including Profit')</span>
                        </li>

                        @if (getAmount($dps->charge_per_installment))
                            <li class="list-group-item">
                                <span class="value">{{ showAmount($dps->charge_per_installment) }} /@lang('Day')</span>
                                <span class="caption">@lang('Delay Charge')</span>
                                <small class="text--danger mt-2">@lang('Charge will be applied if an installment delayed for') {{ $dps->delay_value }} @lang(' or more days')</small>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-7 col-lg-9">
            @include($activeTemplate . 'partials.installment_table')
        </div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.dps.plans') }}">@lang('DPS Plans')</a></li>
    <li><a href="{{ route('user.dps.list') }}" class="active">@lang('My DPS List')</a></li>
@endpush
