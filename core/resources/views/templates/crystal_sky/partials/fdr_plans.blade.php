<div class="row g-4 justify-content-center">
    @foreach ($plans as $plan)
        <div class="col-xxl-4 col-sm-6">
            <div class="pricing-card text-center rounded">
                <div class="pricing-card__header">
                    <div class="pricing-card__overlay"></div>
                    <p class="pricing-card__title">{{ __(@$plan->name) }}</p>
                    <h2 class="pricing-card__price">
                        {{ getAmount($plan->interest_rate) }}%
                        <span class="text-small">&nbsp;/ {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</span>
                    </h2>
                </div>
                <div class="pricing-card__content">
                    <ul>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Lock in Period')</p>
                            <p class="pricing-card_value fs-18 ms-auto">
                                {{ $plan->locked_days }} {{__(Str::plural('Day', $plan->locked_days))}}
                            </p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Get Profit') @lang('Every')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Profit Rate')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ getAmount($plan->interest_rate) }}%</p>
                        </li>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Minimum') </p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ showAmount($plan->minimum_amount) }}</p>
                        </li>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Maximum')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ showAmount($plan->maximum_amount) }}</p>
                        </li>
                    </ul>
                    <button type="button" data-id="{{ $plan->id }}" data-minimum="{{ showAmount($plan->minimum_amount) }}" data-maximum="{{ showAmount($plan->maximum_amount) }}" class="btn btn--base fdrBtn">@lang('Apply Now')</button>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.fdrBtn').on('click', (e) => {
                let modal = $('#fdrModal');
                let data = e.currentTarget.dataset;
                let form = modal.find('form')[0];
                modal.find('.min-limit').text(`Minimum Amount ${data.minimum}`);
                modal.find('.max-limit').text(`Maximum Amount ${data.maximum}`);
                form.action = `{{ route('user.fdr.apply', '') }}/${data.id}`;
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('modal')
    <div class="modal fade custom--modal" id="fdrModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    @auth
                        <div class="modal-header">
                            <h5 class="modal-title method-name">@lang('Apply to Open an FDR')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mt-0">
                                <label class="form-label">@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" class="form-control form--control" placeholder="@lang('Enter An Amount')" required>
                                    <span class="input-group-text"> {{ gs()->cur_text }} </span>
                                </div>
                                <p><small class="text--danger min-limit mt-2"></small></p>
                                <p><small class="text--danger max-limit"></small></p>
                            </div>
                            @include($activeTemplate . 'partials.otp_field')
                            <button type="submit" class="btn btn-md btn--base w-100">@lang('Submit')</button>
                        </div>
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
