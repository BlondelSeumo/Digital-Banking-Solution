<div class="row g-4 justify-content-center">
    @foreach ($plans as $plan)
        <div class="col-xxl-4 col-sm-6">
            <div class="pricing-card text-center rounded">
                <div class="pricing-card__header">
                    <div class="pricing-card__overlay"></div>
                    <p class="pricing-card__title">{{ __(@$plan->name) }}</p>
                    <h2 class="pricing-card__price">
                        {{ getAmount(@$plan->per_installment) }}
                        <span class="text-small">&nbsp;/ {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</span>
                    </h2>
                </div>
                <div class="pricing-card__content">
                    <ul>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Interest Rate') </p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ getAmount($plan->interest_rate) }}%</p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Per Installment')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ showAmount($plan->per_installment) }}</p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Installment Interval')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</p>
                        </li>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Total Installment')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ @$plan->total_installment }}</p>
                        </li>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Deposit')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ showAmount(@$plan->total_installment * @$plan->per_installment) }}</p>
                        </li>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('You Will Get')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ showAmount($plan->final_amount) }}</p>
                        </li>
                    </ul>
                    <button type="button" data-id="{{ $plan->id }}" class="btn btn--base dpsBtn">@lang('Apply Now')</button>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.dpsBtn').on('click', (e) => {
                let modal = $('#dpsModal');
                let data = e.currentTarget.dataset;
                let form = modal.find('form')[0];
                form.action = `{{ route('user.dps.apply', '') }}/${data.id}`;
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('modal')
    <div class="modal fade custom--modal" id="dpsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    @auth
                        <div class="modal-header">
                            <h5 class="modal-title method-name">@lang('Apply to Open a DPS')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        @csrf
                        <div class="modal-body">
                            @if (checkIsOtpEnable())
                                @include($activeTemplate . 'partials.otp_field')
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            @else
                                @lang('Are you sure to apply for this plan?')
                            @endif
                        </div>
                        @if (!checkIsOtpEnable())
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn--dark" data-bs-dismiss="modal" aria-label="Close">@lang('No')</button>
                                <button type="submit" class="btn btn-sm btn--base h-auto">@lang('Yes')</button>
                            </div>
                        @endif
                    @else
                        <div class="modal-body">
                            <div class="text-center"><i class="la la-times-circle text--danger la-6x" aria-hidden="true"></i></div>
                            <h3 class="text-center mt-3">@lang('You are not logged in!')</h3>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn--sm btn-dark" data-bs-dismiss="modal" aria-label="Close">@lang('Close')</button>
                        </div>
                    @endauth
                </form>
            </div>
        </div>
    </div>
@endpush
