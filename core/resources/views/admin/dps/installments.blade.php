@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">@lang('DPS Summary')</h6>
                    @php echo $dps->status_badge;@endphp
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            @can('admin.users.detail')
                                <a href="{{ route('admin.users.detail', $dps->user_id) }}">
                                    <span class="value">{{ $dps->user->account_number }}</span>
                                </a>
                            @else
                                <span class="value">{{ $dps->user->account_number }}</span>
                            @endcan
                            <span class="caption">@lang('Account')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value fw-bold">{{ $dps->dps_number }}</span>
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
                            <span class="value text--base text--primary fw-bold">{{ showAmount($dps->per_installment) }}</span>
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
                            <span class="value">{{ showAmount($dps->depositedAmount()) }} </span>
                            <span class="caption">@lang('Total Deposit')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ showAmount($dps->profitAmount()) }} </span>
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
        <div class="col-sm-6 col-lg-8 col-xl-9">
            @include('admin.partials.installments_table')
        </div>
    </div>
@endsection

@can('admin.dps.index')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.dps.index') }}" />
    @endpush
@endcan
