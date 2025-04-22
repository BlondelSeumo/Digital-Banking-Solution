@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-12">
            <div class="custom--card">
                <div class="card-body">
                    <h5 class="text-center">
                        @lang('You have requested to invest in FDR')
                    </h5>
                    <p class="text--danger text-center">(@lang('Be Sure Before Confirm'))</p>

                    <ul class="caption-list-two mt-3">
                        <li>
                            <span class="caption">@lang('Plan')</span>
                            <span class="value">{{ __($plan->name) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Profit Rate')</span>
                            <span class="value">{{ getAmount($plan->interest_rate) }}%</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Your Amount')</span>
                            <span class="value">{{ showAmount($amount) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Profit in Every') {{ $plan->installment_interval }} {{ __(Str::plural('Day', $plan->installment_interval)) }}</span>
                            <span class="value">{{ showAmount(($amount * $plan->interest_rate) / 100) }}</span>
                        </li>

                        <li class="text--danger">
                            <span class="caption">@lang('Can\'t Be Withdrawn Till')</span>
                            <span class="value">{{ showDateTime(now()->addDays($plan->locked_days), 'd M, Y') }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a class="btn btn-md btn--dark" href="{{ route('user.home') }}">@lang('Cancel')</a>
                        <form action="{{ route('user.fdr.apply.confirm', $verificationId) }}" method="POST">
                            @csrf
                            <button class="btn btn-md btn--base" type="submit">@lang('Confirm')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.fdr.plans') }}">@lang('FDR Plans')</a></li>
    <li><a href="{{ route('user.fdr.list') }}">@lang('My FDR List')</a></li>
@endpush
