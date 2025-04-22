@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $signupBg = getContent('signup_bg.content', true);
    @endphp

    <section class="account-section registration-section bg_img" style="background-image: url(' {{ getImage('assets/images/frontend/signup_bg/' . @$signupBg->data_values->image, '1920x1280') }}');">
        <div class="account-section-left">
            <div class="account-section-left-inner d-none d-sm-block">
                <h4 class="title text-white mb-2">{{ __(@$signupBg->data_values->heading) }}</h4>
                <p class="text-white">{{ __(@$signupBg->data_values->subheading) }}</p>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline--base mt-3"> <i class="la la-reply" aria-hidden="true"></i> @lang('Back to Home')</a>
            </div>
        </div>
        <div class="account-section-right  @if (!gs('registration')) form-disable @endif">
            @if (!gs('registration'))
                <span class="form-disabled-text">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="80" height="80" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <g>
                            <path d="M255.999 0c-79.044 0-143.352 64.308-143.352 143.353v70.193c0 4.78 3.879 8.656 8.659 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193c0-42.998 34.981-77.98 77.979-77.98s77.979 34.982 77.979 77.98v70.193c0 4.78 3.88 8.656 8.661 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193C399.352 64.308 335.044 0 255.999 0zM382.04 204.89h-30.748v-61.537c0-52.544-42.748-95.292-95.291-95.292s-95.291 42.748-95.291 95.292v61.537h-30.748v-61.537c0-69.499 56.54-126.04 126.038-126.04 69.499 0 126.04 56.541 126.04 126.04v61.537z" fill="rgb(0 0 0 / 60%)" opacity="1" data-original="rgb(0 0 0 / 60%)" class=""></path>
                            <path d="M410.63 204.89H101.371c-20.505 0-37.188 16.683-37.188 37.188v232.734c0 20.505 16.683 37.188 37.188 37.188H410.63c20.505 0 37.187-16.683 37.187-37.189V242.078c0-20.505-16.682-37.188-37.187-37.188zm19.875 269.921c0 10.96-8.916 19.876-19.875 19.876H101.371c-10.96 0-19.876-8.916-19.876-19.876V242.078c0-10.96 8.916-19.876 19.876-19.876H410.63c10.959 0 19.875 8.916 19.875 19.876v232.733z" fill="rgb(0 0 0 / 60%)" opacity="1" data-original="rgb(0 0 0 / 60%)" class=""></path>
                            <path d="M285.11 369.781c10.113-8.521 15.998-20.978 15.998-34.365 0-24.873-20.236-45.109-45.109-45.109-24.874 0-45.11 20.236-45.11 45.109 0 13.387 5.885 25.844 16 34.367l-9.731 46.362a8.66 8.66 0 0 0 8.472 10.436h60.738a8.654 8.654 0 0 0 8.47-10.434l-9.728-46.366zm-14.259-10.961a8.658 8.658 0 0 0-3.824 9.081l8.68 41.366h-39.415l8.682-41.363a8.655 8.655 0 0 0-3.824-9.081c-8.108-5.16-12.948-13.911-12.948-23.406 0-15.327 12.469-27.796 27.797-27.796 15.327 0 27.796 12.469 27.796 27.796.002 9.497-4.838 18.246-12.944 23.403z" fill="rgb(0 0 0 / 60%)" opacity="1" data-original="rgb(0 0 0 / 60%)" class=""></path>
                        </g>
                    </svg>
                    <br><br>
                    <span class="text--danger">@lang('Registration is currently disabled')</span>
                </span>
            @endif
            <div class="top text-center mb-5">
                <a href="{{ route('home') }}" class="account-logo">
                    <img src="{{ siteLogo() }}" alt="@lang('Logo')">
                </a>
            </div>

            @if (@gs('socialite_credentials')->google->status == Status::ENABLE || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->linkedin->status == Status::ENABLE)
                <div class="social_login">
                    @include($activeTemplate . 'partials.social_login')
                </div>
            @endif

            <div class="middle">

                <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha account-form">
                    @csrf
                    <div class="row">
                        @if (session()->get('reference') != null && gs()->modules->referral_system)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="referenceBy" class="form-label label">@lang('Referred by')</label>
                                    <input type="text" name="referBy" id="referenceBy" class="form--control" value="{{ session()->get('reference') }}" readonly>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form--label">@lang('First Name')</label>
                                <input type="text" class="form--control" name="firstname" value="{{ old('firstname') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form--label">@lang('Last Name')</label>
                                <input type="text" class="form--control" name="lastname" value="{{ old('lastname') }}" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label required">@lang('E-Mail Address')</label>
                                <input type="email" class="form--control checkUser" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required">@lang('Password')</label>
                                <input type="password" class="form--control @if (gs()->secure_password) secure-password @endif" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required">@lang('Confirm Password')</label>
                                <input type="password" class="form--control" name="password_confirmation" required>
                            </div>
                        </div>
                        <x-captcha />
                    </div>
                    @if (gs()->agree)
                        <div class="form-group">
                            <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                            <label for="agree">@lang('I agree with')</label>
                            <span>
                                @foreach ($policyPages as $policy)
                                    <a href="{{ route('policy.pages', $policy->slug) }}" target="_blank">{{ __($policy->data_values->title) }}</a>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    @endif

                    <button type="submit" id="recaptcha" class="btn btn--base w-100"> @lang('Register')</button>

                </form>

                <p class="mt-3 text-white">@lang('Already haver an account?')
                    <a href="{{ route('user.login') }}" class="text--base">@lang('Login Now')</a>
                </p>
            </div>
        </div>
    </section>

    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base btn-sm">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (gs()->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .country-code .input-group-text {
            background: #fff !important;
        }

        .country-code select {
            border: none;
        }

        .country-code select:focus {
            border: none;
            outline: none;
        }

        .input-popup {
            bottom: 80% !important;
        }


        .form-disabled-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 991;
            font-size: 24px;
            height: auto;
            width: 100%;
            text-align: center;
            font-weight: 800;
            line-height: 1.2;
            user-select: none;
        }

        .form-disabled-text svg path {
            fill: #fff;
        }
    </style>
@endpush
