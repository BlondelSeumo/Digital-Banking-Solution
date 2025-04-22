<div class="row g-4 justify-content-center">
    @foreach ($plans as $plan)
        <div class="col-xxl-4 col-sm-6">
            <div class="pricing-card text-center rounded">
                <div class="pricing-card__header">
                    <div class="pricing-card__overlay"></div>
                    <p class="pricing-card__title">{{ __(@$plan->name) }}</p>
                    <h2 class="pricing-card__price">
                        {{ getAmount($plan->per_installment) }}%
                        <span class="text-small">&nbsp;/ {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</span>
                    </h2>
                </div>
                <div class="pricing-card__content">
                    <ul>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Take Minimum')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ showAmount($plan->minimum_amount) }}</p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Take Maximum')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ showAmount($plan->maximum_amount) }}</p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name">@lang('Per Installment')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ getAmount($plan->per_installment) }}%</p>
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
                            <p class="pricing-card__name"> @lang('Total Installment')</p>
                            <p class="pricing-card_value fs-18 ms-auto">{{ __($plan->total_installment) }}</p>
                        </li>
                    </ul>
                    <button type="button" data-id="{{ $plan->id }}" data-minimum="{{ showAmount($plan->minimum_amount) }}" data-maximum="{{ showAmount($plan->maximum_amount) }}" class="btn btn--base loanBtn">@lang('Apply Now')</button>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.loanBtn').on('click', (e) => {
                var modal = $('#loanModal');
                let data = e.currentTarget.dataset;
                modal.find('.min-limit').text(`Minimum Amount ${data.minimum}`);
                modal.find('.max-limit').text(`Maximum Amount ${data.maximum}`);
                let form = modal.find('form')[0];
                form.action = `{{ route('user.loan.apply', '') }}/${data.id}`;
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('modal')
    <div class="modal fade custom--modal" id="loanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    @auth
                        <div class="modal-header">
                            <h5 class="modal-title method-name" id="exampleModalLabel">@lang('Apply for Loan')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="required">@lang('Amount')</label>
                                <div class="input-group custom-input-group">
                                    <input type="number" step="any" name="amount" class="form-control form--control" placeholder="@lang('Enter An Amount')" required>
                                    <span class="input-group-text"> {{ gs()->cur_text }} </span>
                                </div>
                                <p><small class="text--danger min-limit"></small></p>
                                <p><small class="text--danger max-limit"></small></p>
                            </div>
                            <button type="submit" class="btn btn--base w-100">@lang('Confirm')</button>
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
