@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container py-120">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-5">
                <div class="card custom--card">

                    <div class="card-body">

                        <div class="mb-4 text-center">
                            <p>@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                        </div>

                        <form method="POST" action="{{ route('user.password.update') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group">
                                <label class="form-label required">@lang('Password')</label>
                                <input type="password" class="form--control @if (gs()->secure_password) secure-password @endif" name="password" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">@lang('Confirm Password')</label>
                                <input type="password" class="form--control" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn--base w-100"> @lang('Submit')</button>
                        </form>
                    </div>
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

@push('style')
    <style>
        .input-popup {
            bottom: 80% !important;
        }
    </style>
@endpush
