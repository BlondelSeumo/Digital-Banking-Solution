@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper custom--card">
                    <div class="verification-area">
                        @if ($verification->send_via != '2fa')
                            @include($activeTemplate . 'user.otp.email_sms')
                        @endif
                        @if ($verification->send_via == '2fa')
                            <p class="text-center mb-3">@lang('Get the OTP code from your google authenticator app')</p>
                        @endif
                        <form action="{{ route('user.otp.submit', $verification->id) }}" method="post" class="submit-form">
                            @csrf
                            <div class="mb-3">
                                <div class="verification-code">
                                    <input type="text" name="otp" id="verification-code" class="form--control overflow-hidden" required autocomplete="off">
                                    <div class="boxes">
                                        <span>-</span>
                                        <span>-</span>
                                        <span>-</span>
                                        <span>-</span>
                                        <span>-</span>
                                        <span>-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-md btn--base w-100">@lang('Verify')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/verification-code.css') }}">
@endpush

@push('script')
    <script>
        $('#verification-code').on('input', function() {
            $(this).val(function(i, val) {
                if (val.length >= 6) {
                    $('.submit-form').find('button[type=submit]').html('<i class="las la-spinner fa-spin"></i>');
                    $('.submit-form').submit()
                }

                if (val.length > 6) {
                    return val.substring(0, val.length - 1);
                }

                return val;
            });

            for (let index = $(this).val().length; index >= 0; index--) {
                $($('.boxes span')[index]).html('');
            }
        });
    </script>
@endpush
