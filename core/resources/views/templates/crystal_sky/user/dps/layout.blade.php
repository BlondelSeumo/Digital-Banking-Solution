@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('user.dps.list') }}" class="btn btn-outline--base {{ menuActive('user.dps.list')}}">@lang('My DPS List')</a>
            <a href="{{ route('user.dps.plans') }}" class="btn btn-outline--base {{ menuActive('user.dps.plans')}}">@lang('DPS Plans')</a>
        </div>
    </div>

    @yield('dps-content')

@endsection

@push('style')
    <style>
        .btn[type=submit] {
            height: unset !important;
        }

        .btn {
            padding: 12px 1.875rem;
        }
    </style>
@endpush
