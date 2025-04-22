@php
    $footer = getContent('footer.content', true);
    $datas = getContent('footer.element');
    $contacts = getContent('contact_us.element');
    $about = getContent('about.content', true);
    $links = getContent('policy_pages.element', false, null, true);
@endphp

<footer class="footer position-relative z-index-2">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-sm-6 order-lg-1 order-1">
                <div class="footer-widget">
                    <h3 class="footer-widget__title">@lang('About Us')</h3>
                    <p>{{ __(@$about->data_values->subheading) }}</p>
                    <ul class="social-media-links d-flex align-items-center mt-3">
                        @foreach ($datas as $data)
                            <li>
                                <a href="{{ $data->data_values->social_link }}" target="_blank">
                                    @php echo $data->data_values->social_icon; @endphp
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 order-lg-2 order-3">
                <div class="footer-widget">
                    <h3 class="footer-widget__title">@lang('Quick Links')</h3>
                    <ul class="short-link-list">
                        @auth
                            <li><a href="{{ route('user.home') }}">@lang('User Dashboard')</a></li>
                        @else
                            @if (gs('registration'))
                                <li><a href="{{ route('user.register') }}">@lang('Register')</a></li>
                            @endif
                        @endauth
                        <li><a href="{{ route('branches') }}">@lang('Our Branches')</a></li>
                        <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 order-lg-3 order-4">
                <div class="footer-widget">
                    <h3 class="footer-widget__title">@lang('Page')</h3>
                    <ul class="short-link-list">
                        @foreach ($links as $link)
                            <li>
                                <a href="{{ route('policy.pages',$link->slug)}}">
                                    {{ __($link->data_values->title) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 order-lg-4 order-2">
                <div class="footer-widget">
                    <h3 class="footer-widget__title">@lang('Contact Us')</h3>
                    <ul class="footer-info-list">
                        @foreach ($contacts as $contact)
                            <li>
                                @php echo $contact->data_values->icon; @endphp
                                <p>{{ $contact->data_values->address }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-3 col-sm-6 order-lg-1 text-sm-start order-1 text-center">
                    <a class="footer-logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="@lang('Logo')"></a>
                </div>

                <div class="col-lg-9 col-sm-6 order-lg-3 text-sm-end order-2 text-center">
                    <p>{{ __(@$footer->data_values->text) }}</p>
                </div>
            </div>
        </div>
    </div>
</footer>

@php
    $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
@endphp

@if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
    <div class="cookies-card hide text-center">
        <div class="cookies-card__icon bg--base">
            <i class="las la-cookie-bite"></i>
        </div>
        <p class="cookies-card__content mt-4">{{ @$cookie->data_values->short_desc }} <a href="{{ route('cookie.policy') }}" target="_blank">@lang('Learn more')</a></p>
        <div class="cookies-card__btn mt-4">
            <a class="btn btn--base w-100 policy" href="javascript:void(0)">@lang('Allow')</a>
        </div>
    </div>
@endif
