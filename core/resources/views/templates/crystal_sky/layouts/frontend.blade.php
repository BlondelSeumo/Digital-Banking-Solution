@extends($activeTemplate . 'layouts.app')
@section('app')
    @include($activeTemplate . 'partials.header_top')
    @include($activeTemplate . 'partials.header')

    @if (!request()->RouteIs('home'))
        @include($activeTemplate . 'partials.breadcrumb')
    @endif

    @yield('content')

    @include($activeTemplate . 'partials.footer')
@endsection
