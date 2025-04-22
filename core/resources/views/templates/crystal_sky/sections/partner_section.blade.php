@php
    $partnerContent = getContent('partner_section.content', true);
    $partners = getContent('partner_section.element', orderById: true);
@endphp

@if ($partnerContent)
    <div class="brand">
        <div class="container">
            <h4 class="brand-section-heading">{{ __($partnerContent->data_values->heading) }}</h4>
            <div class="brand-logos brand-slider">
                @foreach ($partners as $partner)
                    <img class="brand-logo__item" src="{{ getImage('assets/images/frontend/partner_section/' . @$partner->data_values->image, '180x35') }}" alt="brands">
                @endforeach
            </div>
        </div>
    </div>
@endif

@push('script')
    <script>
        (function($) {
            "use strict";
            $(".brand-slider").slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 1000,
                pauseOnHover: true,
                speed: 2000,
                dots: false,
                arrows: false,
                prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
                responsive: [{
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 4,
                        },
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 4,
                        },
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 3,
                        },
                    },
                    {
                        breakpoint: 400,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                ],
            });
        })(jQuery);
    </script>
@endpush
