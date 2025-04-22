@php
    $content = getContent('partner_section.content', true);
    $elements = getContent('partner_section.element');
@endphp

@if ($content)

    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-header">
                        <h2 class="section-title">{{ __($content->data_values->heading) }}</h2>
                    </div>
                </div>
            </div>
            <div class="row wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="col-lg-12">
                    <div class="brand-slider">
                        @foreach ($elements as $element)
                            <div class="single-slide">
                                <div class="brand-item">
                                    <img src="{{ getImage('assets/images/frontend/partner_section/' . @$element->data_values->image, '300x300') }}" alt="image">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@if (!app()->offsetExists('slick_asset'))
    @push('style-lib')
        <link href="{{ asset('assets/global/css/slick.css') }}" rel="stylesheet">
    @endPush

    @push('script-lib')
        <script src="{{ asset('assets/global/js/slick.min.js') }}"></script>
    @endPush

    @php app()->offsetSet('slick_asset',true) @endphp
@endif

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.brand-slider').slick({
                autoplay: true,
                autoplaySpeed: 2000,
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 8,
                arrows: false,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 7,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 5,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 3,
                        }
                    }
                ]
            });

        })(jQuery);
    </script>
@endpush
