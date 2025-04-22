@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-12">
            <div class="custom--card">
                <div class="card-body">
                    <h5 class="text-center">
                        @lang('Your Loan information')
                    </h5>
                    <ul>
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Loan No.')</span>
                            <span>{{ $loan->loan_number }}</span>
                        </li>
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('loan Name')</span>
                            <span>{{ $loan->plan->name }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Loan Amount')</span>
                            <span>{{showAmount($loan->amount) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Total Installment')</span>
                            <span>{{ $loan->plan->total_installment }}</span>
                        </li>

                        @php $per_intallment = $loan->amount * $loan->plan->per_installment / 100; @endphp

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Per Installment')</span>
                            <span>{{showAmount($per_intallment) }}</span>
                        </li>

                        <li class="pricing-card__list flex-between text--danger">
                            <span class="fw-bold">@lang('You Will Pay')</span>
                            <span class="fw-bold">{{showAmount($per_intallment * $loan->plan->total_installment) }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('user.loan.download', $loan->id) }}" type="button" class="btn btn--base btn--sm">
                            <i class="las la-file-download"></i> @lang('Download')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom-menu')
    <div class="col-12 order-lg-3 order-4">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('user.loan.plans') }}" class="btn btn--base active">@lang('Loan Plans')</a>
            <a href="{{ route('user.loan.list') }}" class="btn btn-outline--base">@lang('My Loan List')</a>
        </div>
    </div>
@endpush
