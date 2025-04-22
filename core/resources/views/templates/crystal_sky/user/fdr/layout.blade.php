@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('user.fdr.list') }}" class="btn btn-outline--base {{ menuActive('user.fdr.list')}}">@lang('My FDR List')</a>
            <a href="{{ route('user.fdr.plans') }}" class="btn btn-outline--base {{ menuActive('user.fdr.plans')}}">@lang('FDR Plans')</a>
        </div>
    </div>

    @yield('fdr-content')

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
