<li><a class="{{ menuActive('home') }}" href="{{ route('home') }}">@lang('Home')</a></li>
@php
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', 0)
        ->get();
@endphp
@foreach ($pages as $k => $data)
    <li>
        <a class="@if ($data->slug == Request::segment(1)) active @endif" href="{{ route('pages', [$data->slug]) }}">
            {{ __($data->name) }}
        </a>
    </li>
@endforeach
<li><a class="{{ menuActive('contact') }}" href="{{ route('contact') }}">@lang('Contact')</a></li>
