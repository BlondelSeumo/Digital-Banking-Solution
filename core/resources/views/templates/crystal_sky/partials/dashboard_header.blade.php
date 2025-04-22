<div class="dashboard-header">
    <div class="row gy-3 gy-lg-4 align-items-center">

        <div class="col-7 d-flex align-items-center gap-3">
            <div class="d-lg-none d-inline">
                <span class="dashboard-body__bar-icon"><i class="fas fa-bars"></i></span>
            </div>
            <div class="dashboard-header__details">
                <h4 class="dashboard-header__title mb-0">{{ __(@$pageTitle) }}</h4>
            </div>
        </div>

        <div class="col-5 text-end">
            @if (gs('multi_language'))
                @php
                    $language = App\Models\Language::all();
                    $selectLang = $language->where('code', config('app.locale'))->first();
                    $currentLang = session('lang') ? $language->where('code', session('lang'))->first() : $language->where('is_default', Status::YES)->first();
                @endphp

                @if ($language->count())
                    <div class="language_switcher">

                        <div class="language_switcher__caption">
                            <span class="icon">
                                <img src="{{ getImage(getFilePath('language') . '/' . $currentLang->image, getFileSize('language')) }}" alt="@lang('image')">
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
        </div>

        @stack('bottom-menu')
    </div>
</div>
