@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('user.loan.list') }}" class="btn btn-outline--base {{ menuActive('user.loan.list')}}">@lang('My Loan List')</a>
            <a href="{{ route('user.loan.plans') }}" class="btn btn-outline--base {{ menuActive('user.loan.plans')}}">@lang('Loan Plans')</a>
        </div>
    </div>

    @yield('loan-content')

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
