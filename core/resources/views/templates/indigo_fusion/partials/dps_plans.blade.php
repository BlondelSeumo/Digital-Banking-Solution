<div class="row justify-content-center gy-4 gx-sm-3 gx-md-4">
    @foreach ($plans as $plan)
        <div class="col-lg-4 col-sm-6">
            <div class="plan-card rounded-3 wow fadeInUp">
                <div class="plan-card__header">
                    <div class="wave-shape">
                        <img src="{{ asset($activeTemplateTrue . 'images/elements/wave.png') }}" alt="img">
                    </div>
                    <h4 class="plan-name">{{ __(@$plan->name) }}</h4>
                    <div class="plan-price">
                        {{ gs()->cur_sym . getAmount(@$plan->per_installment) }}
                        <sub>/{{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</sub>
                    </div>
                </div>
                <div class="plan-card__body text-center">
                    <ul class="plan-feature-list">
                        <li class="d-flex flex-wrap justify-content-between">
                            <span> @lang('Interest Rate') </span>
                            {{ getAmount($plan->interest_rate) }}%
                        </li>

                        <li class="d-flex flex-wrap justify-content-between">
                            <span> @lang('Per Installment') </span>
                            {{showAmount($plan->per_installment) }}
                        </li>

                        <li class="d-flex flex-wrap justify-content-between">
                            <span> @lang('Installment Interval')</span>
                            {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}
                        </li>
                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Total Installment')</span>
                            {{ @$plan->total_installment }}
                        </li>
                        <li class="d-flex flex-wrap justify-content-between">
                            <span> @lang('Deposit')</span>
                            {{showAmount(@$plan->total_installment * @$plan->per_installment) }}
                        </li>
                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('You Will Get')</span>
                            {{showAmount($plan->final_amount) }}
                        </li>
                    </ul>
                </div>
                <div class="plan-card__footer text-center">
                    <button type="button" data-id="{{ $plan->id }}" class="btn btn-md w-100 btn--base dpsBtn">@lang('Apply Now')</button>
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
    <div class="modal fade" id="dpsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    @auth
                        <div class="modal-header">
                            <h5 class="modal-title method-name">@lang('Apply to Open a DPS')</h5>
                            <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="las la-times"></i>
                            </span>
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
                            <button type="button" class="btn btn-sm btn--dark" data-bs-dismiss="modal" aria-label="Close">@lang('Close')</button>
                        </div>
                    @endauth
                </form>
            </div>
        </div>
    </div>
@endpush
