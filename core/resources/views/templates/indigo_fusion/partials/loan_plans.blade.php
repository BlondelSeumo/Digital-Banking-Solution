<div class="row justify-content-center gy-4 gx-sm-2 gx-md-4">
    @forelse($plans as $plan)
        <div class="col-lg-4 col-sm-6">
            <div class="plan-card rounded-3 wow fadeInUp">
                <div class="plan-card__header">
                    <div class="wave-shape">
                        <img src="{{ asset($activeTemplateTrue . 'images/elements/wave.png') }}" alt="img">
                    </div>
                    <h4 class="plan-name">{{ __($plan->name) }}</h4>
                    <div class="plan-price">
                        {{ getAmount($plan->per_installment) }}% <sub>/{{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}</sub>
                    </div>
                </div>

                <div class="plan-card__body text-center">
                    <ul class="plan-feature-list">
                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Interest Rate')</span>
                            {{ ($plan->total_installment - (100 / $plan->per_installment)) * $plan->per_installment }}%
                        </li>

                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Take Minimum')</span>
                            {{ showAmount($plan->minimum_amount) }}
                        </li>

                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Take Maximum')</span>
                            {{ showAmount($plan->maximum_amount) }}
                        </li>

                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Per Installment')</span>
                            {{ getAmount($plan->per_installment) }}%
                        </li>

                        <li class="d-flex flex-wrap justify-content-between">
                            <span>@lang('Installment Interval')</span>
                            {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}
                        </li>

                        <li class="d-flex flex-wrap justify-content-between">
                            <span> @lang('Total Installment')</span>
                            {{ $plan->total_installment }}
                        </li>


                    </ul>
                </div>

                <div class="plan-card__footer text-center">
                    <button type="button" data-id="{{ $plan->id }}" data-minimum="{{ showAmount($plan->minimum_amount) }}" data-maximum="{{ showAmount($plan->maximum_amount) }}" class="btn btn-md w-100 btn--base loanBtn">@lang('Apply Now')
                    </button>
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
    <div class="modal fade" id="loanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    @auth
                        <div class="modal-header">
                            <h5 class="modal-title method-name" id="exampleModalLabel">@lang('Apply for Loan')</h5>
                            <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="las la-times"></i>
                            </span>
                        </div>

                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="required">@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" class="form--control" placeholder="@lang('Enter An Amount')" required>
                                    <span class="input-group-text"> {{ gs()->cur_text }} </span>
                                </div>
                                <p><small class="text--danger min-limit"></small></p>
                                <p><small class="text-danger max-limit"></small></p>
                            </div>
                            <button type="submit" class="btn btn--base w-100">@lang('Confirm')</button>
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
