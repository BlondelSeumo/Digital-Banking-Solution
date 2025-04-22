@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonials = getContent('testimonial.element', orderById: true);
@endphp

@if ($testimonials->count())
    <section class="testimonials py-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h6 class="section-heading__subtitle">{{ __(@$testimonialContent->data_values->heading) }}</h6>
                        <h2 class="section-heading__title">{{ __(@$testimonialContent->data_values->subheading) }}</h2>
                    </div>
                </div>
            </div>
            <div class="testimonial-slider">
                @foreach ($testimonials as $testimonial)
                    <div class="testimonails-card">
                        <div class="testimonial-item">
                            <div class="testimonial-item__content">
                                <div class="testimonial-item__info">
                                    <img class="testimonial-item__thumb"
                                        src="{{ getImage('assets/images/frontend/testimonial/' . @$testimonial->data_values->image, '75x75') }}"
                                        alt="@lang('image')" />
                                    <div class="testimonial-item__details">
                                        <h5 class="testimonial-item__name">{{ __(@$testimonial->data_values->name) }}</h5>
                                        <span class="testimonial-item__designation"> {{ __(@$testimonial->data_values->designation) }}</span>
                                        <div class="testimonial-item__testimonials">
                                            @php echo displayRating(floatval(@$testimonial->data_values->rating)) @endphp
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-item__quote-icon">
                                    <img src="{{ asset($activeTemplateTrue . 'images/icons/quote-icon.png') }}" alt="@lang('image')" />
                                </div>
                            </div>
                            <p class="testimonial-item__desc">
                                {{ __(@$testimonial->data_values->quote) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@push('script')
    <script>
        (function($) {
            "use strict";
            $(".testimonial-slider").slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                autoplay: false, //TODO: need auto paly true
                autoplaySpeed: 2000,
                speed: 1500,
                dots: true,
                pauseOnHover: true,
                arrows: false,
                prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
                responsive: [{
                        breakpoint: 1199,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                            dots: true,
                        },
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 490,
                        settings: {
                            arrows: false,
                            slidesToShow: 1,
                        },
                    },
                ],
            });
        })(jQuery);
    </script>
@endpush
