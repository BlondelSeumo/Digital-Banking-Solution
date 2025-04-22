<header class="header">
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-lg flex-wrap align-items-center justify-content-between p-0">
                <a class="site-logo site-title" href="{{ route('home') }}">
                    <img src="{{ siteLogo() }}" alt="logo">
                </a>
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">

                    <ul class="navbar-nav main-menu m-auto" id="linkItem">
                        @if (auth()->user() && request()->routeIs('ticket*'))
                            @include($activeTemplate . 'partials.auth_header')
                        @elseif (!request()->routeIs('user.*') || !auth()->user())
                            @include($activeTemplate . 'partials.guest_header')
                        @else
                            @include($activeTemplate . 'partials.auth_header')
                        @endif
                    </ul>

                    @if (!(Route::is('user.*') && auth()->user()))
                        <div class="nav-right">
                            @if (gs('multi_language'))

                                @php
                                    $language = App\Models\Language::all();
                                    $selectLang = $language->where('code', config('app.locale'))->first();
                                    $currentLang = session('lang') ? $language->where('code', session('lang'))->first() : $language->where('is_default', Status::YES)->first();
                                @endphp
                                @if ($language->count())
                                    <div class="language_switcher me-3">
                                        <div class="language_switcher__caption">
                                            <span class="icon">
                                                <img src="{{ getImage(getFilePath('language') . '/' . @$currentLang->image, getFileSize('language')) }}" alt="@lang('image')">
                                            </span>
                                            <span class="text"> {{ __(@$selectLang->name) }} </span>
                                        </div>
                                        <div class="language_switcher__list">
                                            @foreach ($language as $item)
                                                <div class="language_switcher__item    @if (session('lang') == $item->code) selected @endif" data-value="{{ $item->code }}">
                                                    <a href="{{ route('lang', $item->code) }}" class="thumb">
                                                        <span class="icon">
                                                            <img src="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}" alt="@lang('image')">
                                                        </span>
                                                        <span class="text"> {{ __($item->name) }}</span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if (auth()->user() && !request()->routeIs('user.*'))
                                <a class="btn btn-sm header-base-button me-3 py-2" href="{{ route('user.home') }}">@lang('Dashboard')</a>
                            @endif

                            @guest
                                <a class="btn btn-sm header-base-button me-3 py-2" href="{{ route('user.login') }}">@lang('Sign In')</a>
                                @if (gs('registration'))
                                    <a class="btn btn-sm btn--base py-2 text-white" href="{{ route('user.register') }}">@lang('Sign Up')</a>
                                @endif
                            @else
                                <a class="btn btn-sm btn--base py-2 text-white logout-btn" href="{{ route('user.logout') }}">@lang('Logout')</a>
                            @endguest
                        </div>
                    @else
                        <div class="nav-right">
                            <a class="btn btn-sm btn--base py-2 text-white logout-btn" href="{{ route('user.logout') }}">@lang('Logout')</a>
                        </div>
                    @endif
                </div>

            </nav>
        </div>
    </div>
</header>
