@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center mt-4">
        <div class="col-xl-7 col-lg-12">
            <div class="card custom--card">
                <div class="card-body">
                    <h5 class="text-center">
                        @lang('You have requested to invest in DPS')
                    </h5>
                    <p class="text--danger text-center">(@lang('Be Sure Before Confirm'))</p>

                    <ul class="caption-list-two mt-3">
                        <li>
                            <span class="caption">@lang('Plan')</span>
                            <span class="value">{{ __($plan->name) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Installment Interval')</span>
                            <span class="value">{{ $plan->installment_interval }} {{ __(Str::plural('Day', $plan->installment_interval)) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Total Installment')</span>
                            <span class="value">{{ $plan->total_installment }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Per Installment')</span>
                            <span class="value">{{ showAmount($plan->per_installment) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Total Deposit')</span>
                            <span class="value">{{ showAmount($plan->per_installment * $plan->total_installment) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Profit Rate')</span>
                            <span class="value">{{ getAmount($plan->interest_rate) }}%</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Withdrawable Amount')</small></span>
                            <span class="value fw-bold">{{ showAmount($plan->final_amount) }}</span>
                        </li>
                    </ul>

                    <p class="px-2">
                        @if ($plan->delay_value && $plan->delay_charge)
                            <small class="text--danger">*
                                @lang('If an installment is delayed for')
                                <span class="fw-bold">{{ $plan->delay_value }}</span> @lang('or more days then, an amount of'), <span class="fw-bold">{{ gs()->cur_sym . $plan->delayCharge }}</span> @lang('will be applied for each day.')
                            </small>

                            <br>

                            <small class="text--danger">
                                * @lang('The total charge amount will be subtracted from the withdrawable amount.')
                            </small>
                        @endif
                    </p>

                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a class="btn btn-md btn--dark" href="{{ route('user.home') }}">@lang('Cancel')</a>

                        <form action="{{ route('user.dps.apply.confirm', $verificationId) }}" method="POST">
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
    <li><a href="{{ route('user.dps.plans') }}">@lang('DPS Plans')</a></li>
    <li><a href="{{ route('user.dps.list') }}">@lang('My DPS List')</a></li>
@endpush
