@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @php
        $loginBg = getContent('login_bg.content', true);
    @endphp

    <section class="account-section bg_img" style="background-image: url('{{ getImage('assets/images/frontend/login_bg/' . @$loginBg->data_values->image, '1920x1280') }}');">
        <div class="account-section-left">
            <div class="account-section-left-inner">
                <h4 class="title text-white mb-2">{{ __(@$loginBg->data_values->heading) }}</h4>
                <p class="text-white">{{ __(@$loginBg->data_values->subheading) }}</p>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline--base mt-3"> <i class="la la-reply" aria-hidden="true"></i> @lang('Back to Home')</a>
            </div>
        </div>
        <div class="account-section-right">
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
                <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha account-form">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label required">@lang('Username or Email')</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="form--control" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label required">@lang('Password')</label>
                        <input id="password" type="password" class="form--control" name="password" required>
                    </div>

                    <x-captcha />

                    <div class="form-group form-check d-flex justify-content-between">
                        <div>
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                        </div>

                        <a class="forgot-pass" href="{{ route('user.password.request') }}">
                            @lang('Forgot password?')
                        </a>
                    </div>

                    <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Login')</button>
                </form>

                @if (gs('registration'))
                    <p class="text-white mt-3">@lang("Don't have an account?")
                        <a href="{{ route('user.register') }}" class="text--base">@lang('Create New')</a>
                    </p>
                @endif
            </div>
        </div>
    </section>
@endsection
