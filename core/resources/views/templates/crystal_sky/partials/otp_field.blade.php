@if (checkIsOtpEnable())
    <div class="form-group mt-0">
        <label for="verification" class="form-label">@lang('Authorization Mode')</label>
        <select name="auth_mode" id="verification" class="form--control select" required>
            <option disabled selected value="">@lang('Select One')</option>
            @if (auth()->user()->ts)
                <option value="2fa">@lang('Google Authenticator')</option>
            @endif
            @if (gs()->modules->otp_email)
                <option value="email">@lang('Email')</option>
            @endif
            @if (gs()->modules->otp_sms)
                <option value="sms">@lang('SMS')</option>
            @endif
        </select>
    </div>
@endif
