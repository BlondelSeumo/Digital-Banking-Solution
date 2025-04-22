@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="plan-area">
        @include($activeTemplate . 'partials.dps_plans')
    </div>
@endsection

@push('bottom-menu')
    <li>
        <a href="{{ route('user.dps.plans') }}" class="active">@lang('DPS Plans')</a>
    </li>
    <li>
        <a href="{{ route('user.dps.list') }}">@lang('My DPS List')</a>
    </li>
@endpush
