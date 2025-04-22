@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container py-120">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper custom--card">
                <div class="verification-area">
                    <form action="{{ route('user.2fa.verify') }}" method="POST" class="submit-form">
                        @csrf
                        <p class="mb-3">@lang('Take the code from your google authenticator app.')</p>

                        @include($activeTemplate . 'partials.verification_code')
                        <button type="submit" class="btn btn-md btn--base w-100">@lang('Submit')</button>
                    </form>
                    
                    <div class="text-end mt-3">
                        <a href="{{ route('user.logout') }}" class="btn btn--sm btn-outline--danger">
                            <span class="icon"><i class="las la-sign-out-alt"></i></span>
                            <span class="text">@lang('Log Out')</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
