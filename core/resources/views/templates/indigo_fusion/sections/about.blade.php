@php
    $content = getContent('about.content', true);
    $elements = getContent('about.element');
@endphp
@if ($content)
    <section id="about" class="pt-100 pb-100 section--bg">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-6">
                    <div class="about-thumb rounded-3">
                        <img src="{{ getImage('assets/images/frontend/about/' . @$content->data_values->image, '650x485') }}" alt="image">
                        <a href="{{ @$content->data_values->video_link }}" data-rel="lightcase:myCollection" class="video-icon wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.3s">
                            <i class="las la-play"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <div class="section-header">
                        <div class="section-top-title border-left text--base">{{ __(@$content->data_values->title) }}</div>
                        <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                    </div>
                    <div class="row gy-4">
                        @foreach ($elements as $element)
                            <div class="col-xxl-8 col-xl-10 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.3s">
                                <div class="about-card">
                                    <div class="about-card__icon rounded-3 bg--base">
                                        @php echo @$element->data_values->icon @endphp
                                    </div>
                                    <div class="about-card__content">
                                        <h4 class="title">{{ __(@$element->data_values->heading) }}</h4>
                                        <p>{{ __(@$element->data_values->subheading) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@push('style')
    <style>
        .lightcase-icon-spin:before {
            font-family: 'Line Awesome Free';
            font-weight: 900;
            content: "\f1ce";
        }

        .lightcase-icon-close:before {
            content: "\f00d";
            font-family: 'Line Awesome Free';
            font-weight: 900;
        }

        [class*='lightcase-icon-']:before {}
    </style>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/lightcase.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/lightcase.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('a[data-rel^=lightcase]').lightcase();
        })(jQuery)
    </script>
@endpush
