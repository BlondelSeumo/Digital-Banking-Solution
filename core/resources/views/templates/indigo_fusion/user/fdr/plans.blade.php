@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="plan-area">
        @include($activeTemplate . 'partials.fdr_plans')
    </div>
@endsection

@push('bottom-menu')
    <li>
        <a href="{{ route('user.fdr.plans') }}" class="active">@lang('FDR Plans')</a>
    </li>
    <li>
        <a href="{{ route('user.fdr.list') }}">@lang('My FDR List')</a>
    </li>
@endpush
