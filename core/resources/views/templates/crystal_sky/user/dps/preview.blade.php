@extends($activeTemplate . 'user.dps.layout')
@section('dps-content')
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-xl-7 col-lg-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <h5 class="text-center">
                            @lang('You have requested to invest in DPS')
                        </h5>
                        <p class="text--danger text-center">(@lang('Be Sure Before Confirm'))</p>

                        <ul class="mt-3">
                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Plan')</span>
                                <span>{{ __($plan->name) }}</span>
                            </li>

                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Installment Interval')</span>
                                <span>{{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</span>
                            </li>

                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Total Installment')</span>
                                <span>{{ $plan->total_installment }}</span>
                            </li>

                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Per Installment')</span>
                                <span>{{showAmount($plan->per_installment) }}</span>
                            </li>

                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Total Deposit')</span>
                                <span>{{showAmount($plan->per_installment * $plan->total_installment) }}</span>
                            </li>

                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Profit Rate')</span>
                                <span>{{ getAmount($plan->interest_rate) }}%</span>
                            </li>

                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Withdrawable Amount')</small></span>
                                <span>{{showAmount($plan->final_amount) }}</span>
                            </li>
                        </ul>

                        <p class="px-2">
                            @if ($plan->delay_value && $plan->delay_charge)
                                <small class="text--danger">*
                                    @lang('If an installment is delayed for')
                                    <span class="fw-bold">{{ $plan->delay_value }}</span> @lang('or more days then, an amount of'), <span class="fw-bold">{{$plan->delayCharge }}</span> @lang('will be applied for each day.')
                                </small>

                                <br>

                                <small class="text--danger">
                                    * @lang('The total charge amount will be subtracted from the withdrawable amount.')
                                </small>
                            @endif
                        </p>

                        <div class="d-flex justify-content-end mt-3 gap-3">
                            <a class="btn btn--dark" href="{{ route('user.home') }}">@lang('Cancel')</a>
                            <form action="{{ route('user.dps.apply.confirm', $verificationId) }}" method="POST">
                                @csrf
                                <button class="btn btn--base" type="submit">@lang('Confirm')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
