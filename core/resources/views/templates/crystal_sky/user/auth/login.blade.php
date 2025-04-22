@extends($activeTemplate . 'layouts.app')
@section('app')
    @php
        $loginBg = getContent('login_bg.content', true);
    @endphp
    <section class="account">
        <div class="account__left flex-align bg-img" data-background-image="{{ asset($activeTemplateTrue . 'images/thumbs/account-bg.png') }}">
            <div class="account__thumb">
                <img class="" src="{{ getImage('assets/images/frontend/login_bg/' . @$loginBg->data_values->image, '820x730') }}" alt="@lang('image')">
            </div>
        </div>
        <div class="d-flex flex-wrap account__right flex-align">
            <div class="account__form">
                <div class="account-form">
                    <div class="site-logo">
                        <a href="{{ route('home') }}"> <img src="{{ siteLogo('dark') }}" alt="@lang('logo')"></a>
                    </div>
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle">{{ __(@$loginBg->data_values->heading) }}</h6>
                        <h3 class="section-heading__title">{{ __(@$loginBg->data_values->subheading) }}</h3>
                    </div>

                    @if (@gs('socialite_credentials')->google->status == Status::ENABLE || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->linkedin->status == Status::ENABLE)
                        @include($activeTemplate . 'partials.social_login')
                    @endif

                    <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="username" class="form-label">@lang('Username or Email')</label>
                                    <input type="text" name="username" class="form--control" id="username" value="{{ old('username') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="your-password" class="form-label">@lang('Password')</label>
                                    <input id="your-password" type="password" class="form--control" name="password" required>
                                </div>
                            </div>

                            <x-captcha />

                            <div class="col-12">
                                <div class="d-flex form-group flex-wrap justify-content-between">
                                    <div class="form--check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">@lang('Remember me') </label>
                                    </div>
                                    <a href="{{ route('user.password.request') }}" class="forgot-password text--base">@lang('Forgot Password?')</a>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Sign In')</button>
                                </div>
                            </div>

                            @if (gs('registration'))
                                <div class="col-12">
                                    <div class="have-account text-center">
                                        <p class="have-account__text">@lang('Don\'t Have An Account?')
                                            <a href="{{ route('user.register') }}" class="have-account__link text--base">@lang('Create an Account')</a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
