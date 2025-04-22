@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card custom--card">
                <div class="card-body">
                    <form class="register" action="" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">@lang('Current Password')</label>
                            <input class="form-control form--control" name="current_password" type="password" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Password')</label>
                            <input class="form-control form--control @if (gs()->secure_password) secure-password @endif" name="password" type="password" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Confirm Password')</label>
                            <input type="password" class="form-control form--control" name="password_confirmation" required>
                        </div>
                        <input class="btn btn--base w-100" type="submit" value="@lang('Change Password')">
                    </form>
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

@push('bottom-menu')
    <div class="col-12 order-lg-3 order-4">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('user.profile.setting') }}" class="btn btn-outline--base">@lang('Profile Setting')</a>
            <a href="{{ route('user.change.password') }}" class="btn btn--base active">@lang('Change Password')</a>
            <a href="{{ route('user.twofactor') }}" class="btn btn-outline--base">@lang('2FA Security')</a>
        </div>
    </div>
@endpush
