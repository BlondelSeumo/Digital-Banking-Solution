@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card custom--card">
                <div class="card-header bg-white">
                    <h5 class="card-title text-center">@lang('Deposit via') {{ __($deposit->gateway->name) }}</h5>
                </div>

                <div class="card-body">
                    <div class="card-wrapper mb-3"></div>
                    <form role="form" class="disableSubmission payment appPayment" id="payment-form" method="{{ $data->method }}" action="{{ $data->url }}">
                        @csrf
                        <input type="hidden" value="{{ $data->track }}" name="track">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">@lang('Name on Card')</label>
                                <div class="input-group">
                                    <input type="text" class="form--control" name="name" value="{{ old('name') }}" required autocomplete="off" autofocus />
                                    <span class="input-group-text"><i class="la la-font"></i></span>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">@lang('Card Number')</label>
                                <div class="input-group">
                                    <input type="tel" class="form--control" name="cardNumber" autocomplete="off" value="{{ old('cardNumber') }}" required autofocus />
                                    <span class="input-group-text"><i class="la la-credit-card"></i></span>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">@lang('Expiration Date')</label>
                                <input type="tel" class="form--control" name="cardExpiry" value="{{ old('cardExpiry') }}" autocomplete="off" required />
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">@lang('CVC Code')</label>
                                <input type="tel" class="form--control" name="cardCVC" value="{{ old('cardCVC') }}" autocomplete="off" required />
                            </div>
                        </div>

                        <button class="btn btn--base w-100 " type="submit"> @lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/global/js/card.js') }}"></script>

    <script>
        (function($) {
            "use strict";
            var card = new Card({
                form: '#payment-form',
                container: '.card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="cardNumber"]',
                    expiryInput: 'input[name="cardExpiry"]',
                    cvcInput: 'input[name="cardCVC"]',
                    nameInput: 'input[name="name"]'
                }
            });

            @if ($deposit->from_api)
                $('.appPayment').on('submit', function() {
                    $(this).find('[type=submit]').html('<i class="las la-spinner fa-spin"></i>');
                })
            @endif
        })(jQuery);
    </script>
@endpush
