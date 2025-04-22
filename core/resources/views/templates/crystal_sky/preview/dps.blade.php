@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-12">
            <div class="card custom--card">
                <div class="card-body">
                    <h5 class="text-center">
                        @lang('Your DPS invest information')
                    </h5>
                    <ul class="caption-list-two mt-3">
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('DPS No.')</span>
                            <span>{{ $dps->dps_number }}</span>
                        </li>
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Plan')</span>
                            <span>{{ __($dps->plan->name) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Installment Interval')</span>
                            <span>{{ $dps->plan->installment_interval }} {{__(Str::plural('Day', $dps->plan->installment_interval))}}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Total Installment')</span>
                            <span>{{ $dps->plan->total_installment }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Per Installment')</span>
                            <span>{{showAmount($dps->plan->per_installment) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Total Deposit')</span>
                            <span>{{showAmount($dps->plan->per_installment * $dps->plan->total_installment) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Profit Rate')</span>
                            <span>{{ getAmount($dps->plan->interest_rate) }}%</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Withdrawable Amount')</small></span>
                            <span>{{showAmount($dps->plan->final_amount) }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('user.dps.download', $dps->id) }}" type="button" class="btn btn--base btn--sm"><i class="las la-file-download"></i>@lang('Download')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-menu')
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('user.dps.plans') }}" class="btn btn--base active">@lang('DPS Plans')</a>
            <a href="{{ route('user.dps.list') }}" class="btn btn-outline--base">@lang('My DPS List')</a>
        </div>
@endpush
