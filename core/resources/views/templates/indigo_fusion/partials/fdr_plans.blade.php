<div class="row justify-content-center gy-4 gx-sm-3 gx-md-4">
    @foreach ($plans as $plan)
        <div class="col-lg-4 col-sm-6">
            <div class="plan-card rounded-3 wow fadeInUp gy-3">
                <div class="plan-card__header">
                    <div class="wave-shape">
                        <img src="{{ asset($activeTemplateTrue . 'images/elements/wave.png') }}" alt="img">
                    </div>
                    <h4 class="plan-name">{{ __($plan->name) }}</h4>
                    <div class="plan-price">{{ getAmount($plan->interest_rate) }}%<sub>/ {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</sub></div>
                </div>
                <div class="plan-card__body text-center">
                    <ul class="plan-feature-list">
                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Lock in Period')</span>
                            {{ $plan->locked_days }} {{__(Str::plural('Day', $plan->locked_days))}}
                        </li>
                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Get Profit') @lang('Every')</span>
                            {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}
                        </li>
                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Profit Rate')</span>
                            {{ getAmount($plan->interest_rate) }}%
                        </li>
                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Minimum') </span>
                            {{ showAmount($plan->minimum_amount) }}
                        </li>
                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Maximum')</span>
                            {{ showAmount($plan->maximum_amount) }}
                        </li>
                    </ul>
                </div>
                <div class="plan-card__footer text-center">
                    <button type="button" data-id="{{ $plan->id }}" data-minimum="{{ showAmount($plan->minimum_amount) }}" data-maximum="{{ showAmount($plan->maximum_amount) }}" class="btn btn-md w-100 btn--base fdrBtn">@lang('Apply Now')</button>
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
    <div class="modal fade" id="fdrModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    @auth
                        <div class="modal-header">
                            <h5 class="modal-title method-name">@lang('Apply to Open an FDR')</h5>
                            <button type="button" class="bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mt-0">
                                <label>@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" class="form--control" placeholder="@lang('Enter An Amount')" required>
                                    <span class="input-group-text"> {{ gs()->cur_text }} </span>
                                </div>
                                <p><small class="text--danger min-limit mt-2"></small></p>
                                <p><small class="text-danger max-limit"></small></p>
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
                            <button type="button" class="btn btn-sm btn--dark" data-bs-dismiss="modal" aria-label="Close">@lang('Close')</button>
                        </div>
                    @endauth

                </form>
            </div>
        </div>
    </div>
@endpush
