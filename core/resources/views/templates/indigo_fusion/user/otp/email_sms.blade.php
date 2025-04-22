@php
    $finishTime = \Carbon\Carbon::parse($verification->expired_at);
    $currentTime = now();
    $totalDuration = $currentTime > $finishTime ? 0 : floor($currentTime->diffInSeconds($finishTime));
@endphp

<div class="text-center mb-4 card-img-top bg--dark p-3">
    @if ($verification->send_via == 'email')
        <p class="text-white">@lang('Please check your email to get a six digit OTP')</p>
    @else
        <p class="text-white">@lang('Please check your mobile to get a six digit OTP')</p>
    @endif

    @if ($totalDuration)
        <p class="mt-2 text--warning otp-warning">@lang('OTP will be expired in the next')</p>
    @endif

    <div class="d-flex justify-content-center mb-3">
        <div class="expired-time-circle @if (!$totalDuration) danger-border @endif">
            <div class="exp-time">{{ $totalDuration }}</div>
            @lang('Seconds')
            <div class="animation-circle"></div>
        </div>
        <div class="border-circle"></div>
    </div>

    <div class="try-btn-wrapper mt-2 d-none">
        <p class="text-danger ">@lang('Your OTP has been expired') </p>
        <form method="POST" action="{{ route('user.otp.resend', $verification->id) }}" class="w-100 mt-2">
            @csrf
            <button type="submit" class="rounded btn--success text-white">@lang('Resend OTP')</button>
        </form>
    </div>
</div>

@push('script')
    <script>
        'use strict';
        (function($) {

            let secondsLeft = `{{ $totalDuration }}` * 1;
            setInterval(function() {
                if (secondsLeft) {
                    secondsLeft--;
                }

                if (secondsLeft == 0) {
                    $('.try-btn-wrapper').removeClass('d-none');
                    $('.otp-warning').addClass('d-none');
                    $('.expired-time-circle').addClass('danger-border')
                }

                $(".exp-time").text(secondsLeft);
            }, 1000);

        })(jQuery)
    </script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/otp_timer.css') }}" rel="stylesheet">
@endpush

@push('style')
    <style>
        .animation-circle {
            position: absolute;
            top: 0;
            left: 0;
            border: 4px solid #f44336;
            height: 100%;
            width: 100%;
            border-radius: 150px;
            box-shadow: 1px 1px 1px 1px rgba(255, 0, 0, 0.5);
            transform: rotateY(180deg);
            animation-name: clipCircle;
            animation-duration: {{ @$totalDuration }}s;
            animation-iteration-count: 1;
            animation-timing-function: cubic-bezier(0, 0, 1, 1);
            z-index: 1;
        }
    </style>
@endpush
