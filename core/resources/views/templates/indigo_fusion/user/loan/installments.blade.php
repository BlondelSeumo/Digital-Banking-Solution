@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4">
        <div class="col-sm-5 col-lg-3">
            <div class="card custom--card">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span class="value">{{ $loan->loan_number }}</span>
                            <span class="caption">@lang('Loan Number')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $loan->plan->name }}</span>
                            <span class="caption">@lang('Plan')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ showAmount($loan->amount) }}</span>
                            <span class="caption">@lang('Loan Amount')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value text--base">{{ showAmount($loan->per_installment) }}</span>
                            <span class="caption">@lang('Per Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $loan->total_installment }}</span>
                            <span class="caption">@lang('Total Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $loan->given_installment }}</span>
                            <span class="caption">@lang('Given Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value text--warning">{{ showAmount($loan->payable_amount) }}</span>
                            <span class="caption">@lang('Needs to Pay')</span>
                        </li>

                        @if (getAmount($loan->charge_per_installment))
                            <li class="list-group-item">
                                <span class="value">{{ showAmount($loan->charge_per_installment) }} /@if ($loan->delay_value == 1)
                                        @lang('Day') @else{{ $loan->delay_value }}@lang('Days')
                                    @endif
                                </span>

                                <span class="caption">@lang('Delay Charge')</span>
                                <small class="text--danger mt-2">@lang('Charge will be applied if an installment delayed for') {{ $loan->delay_value }} @lang(' or more days')</small>
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
    <li><a href="{{ route('user.loan.plans') }}">@lang('Loan Plans')</a></li>
    <li><a href="{{ route('user.loan.list') }}" class="active">@lang('My Loan List')</a></li>
@endpush
