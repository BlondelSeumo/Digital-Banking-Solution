@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container pt-100 pb-100">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper custom--card">
                <div class="verification-area">
                    <form action="{{ route('user.2fa.verify') }}" method="POST" class="submit-form">
                        @csrf
                        <p class="mb-3 text-muted">@lang('Take the code from your google authenticator app.')</p>

                        @include($activeTemplate . 'partials.verification_code')
                        <button type="submit" class="btn btn-md btn--base w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
