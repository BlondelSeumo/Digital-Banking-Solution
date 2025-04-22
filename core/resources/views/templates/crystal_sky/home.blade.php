@extends($activeTemplate . 'layouts.frontend')
@section('content')

    @if (request()->routeIs('home'))
        @include($activeTemplate . 'sections.banner')
    @endif

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
