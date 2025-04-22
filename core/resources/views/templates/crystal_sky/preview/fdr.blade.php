@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-12">
            <div class="custom--card">
                <div class="card-body">
                    <h5 class="text-center">
                        @lang('Your FDR invest information')
                    </h5>

                    <ul class="mt-3">
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Plan')</span>
                            <span>{{ __($fdr->plan->name) }}</span>
                        </li>
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Profit Rate')</span>
                            <span>{{ getAmount($fdr->plan->interest_rate) }}%</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Your Amount')</span>
                            <span>{{showAmount($fdr->amount) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Profit in Every') {{ $fdr->plan->installment_interval }} {{__(Str::plural('Day', $fdr->plan->installment_interval))}}</span>
                            <span>{{showAmount(($fdr->amount * $fdr->plan->interest_rate) / 100) }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('user.fdr.details', $fdr->fdr_number) }}?download" type="button" class="btn btn--base btn--sm"><i class="las la-file-download"></i>@lang('Download')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom-menu')
    <div class="col-12 order-lg-3 order-4">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('user.fdr.plans') }}" class="btn btn--base active">@lang('FDR Plans')</a>
            <a href="{{ route('user.fdr.list') }}" class="btn btn-outline--base">@lang('My FDR List')</a>
        </div>
    </div>
@endpush
