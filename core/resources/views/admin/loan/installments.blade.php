@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-sm-5 col-lg-3">
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title">@lang('Loan Summary')</h6>
                    @php echo $loan->status_badge;@endphp
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            @can('admin.users.detail')
                                <a href="{{ route('admin.users.detail', $loan->user_id) }}">
                                    <span class="value">{{ $loan->user->account_number }}</span>
                                </a>
                            @else
                                <span class="value">{{ $loan->user->account_number }}</span>
                            @endcan
                            <span class="caption">@lang('Account')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">#{{ $loan->loan_number }}</span>
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
                            <span class="caption">@lang('Receivable')</span>
                        </li>

                        @if (getAmount($loan->charge_per_installment))
                            <li class="list-group-item">
                                <span class="value">{{ showAmount($loan->charge_per_installment) }} /@lang('Day')</span>
                                <span class="caption">@lang('Delay Charge')</span>
                                <small class="text--warning">@lang('Charge will be applied if an installment delayed for') {{ $loan->delay_value }} @lang(' or more days')</small>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-7 col-lg-9">
            @include('admin.partials.installments_table')
        </div>
    </div>
@endsection

@can('admin.loan.index')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.loan.index') }}" />
    @endpush
@endcan
