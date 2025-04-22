@php
    $text = isset($register) ? 'Register' : 'Login';
@endphp
<div class="social_login__wrapper">
    @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
        <a href="{{ route('user.social.login', 'google') }}" class="social_login__link">
            <span class="google-icon">
                <img src="{{ asset('assets/images/google.svg') }}" alt="Google">
            </span> @lang("$text with Google")
        </a>
    @endif
    @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
        <a href="{{ route('user.social.login', 'facebook') }}" class="social_login__link">
            <span class="facebook-icon">
                <img src="{{ asset('assets/images/facebook.svg') }}" alt="Facebook">
            </span> @lang("$text with Facebook")
        </a>
    @endif
    @if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
        <a href="{{ route('user.social.login', 'linkedin') }}" class="social_login__link">
            <span class="linkedin-icon">
                <img src="{{ asset('assets/images/linkedin.svg') }}" alt="Linkedin">
            </span> @lang("$text with Linkedin")
        </a>
    @endif
</div>
<div class="text-center auth-divide">
    <span>@lang('OR')</span>
</div>

@push('style')
    <style>
        .social_login__wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .social_login__link {
            color: #fff;
            border: 1px solid rgb(255 255 255 / 10%);
            padding: 12px;
            border-radius: 8px;
            transition: 0.3s linear;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            line-height: 1;
            flex-grow: 1;
            background: hsl(var(--base), .3);
        }

        .social_login__link span {
            margin-right: 10px;
        }

        .social_login__link:hover {
            color: #fff;
            background: hsl(var(--base));
            border-color: hsl(var(--base));
        }

        .auth-divide {
            position: relative;
            z-index: 1;
            margin: 24px 0px;
        }

        .auth-divide::after {
            content: "";
            position: absolute;
            height: 1px;
            width: 100%;
            top: 50%;
            left: 0px;
            background-color: rgb(255 255 255 / 10%);
            z-index: -1;
        }

        .auth-divide span {
            background-color: #063760;
            padding-inline: 6px;
            color: #fff;
        }
    </style>
@endpush
