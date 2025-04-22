@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="plan-area">@include($activeTemplate . 'partials.loan_plans')</div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.loan.plans') }}" class="active">@lang('Loan Plans')</a></li>
    <li><a href="{{ route('user.loan.list') }}">@lang('My Loan List')</a></li>
@endpush
