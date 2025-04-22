@php
    $testimonials = getContent('testimonial.element');
@endphp

@if ($testimonials->count())
    <section class="testimonial-section pt-100 pb-100">
        <div class="container">
            <div class="testimonial-slider wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                @foreach ($testimonials as $testimonial)
                    <div class="single-slide">
                        <div class="testimonial-item">
                            <div class="ratings">
                                @php echo displayRating(floatval(@$testimonial->data_values->rating)) @endphp
                            </div>
                            <p class="text-white mt-2">{{ __(@$testimonial->data_values->quote) }}</p>
                            <div class="d-flex align-items-center mt-4">
                                <h4 class="name text-white">{{ __(@$testimonial->data_values->name) }}</h4>
                                <span class="designation text-white-50 ms-3 fs--14px">
                                    {{ __(@$testimonial->data_values->designation) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
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
            $('.testimonial-slider').slick({
                autoplay: false,
                autoplaySpeed: 2000,
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 3,
                arrows: false,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        })(jQuery);
    </script>
@endpush
