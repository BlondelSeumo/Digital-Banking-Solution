@php
    $content = getContent('service.content', true);
    $elements = getContent('service.element');
@endphp

@if ($content)
    <section id="services" class="service-section position-relative z-index-2 pt-100 pb-100">
        <div class="top-wave">
            <img src="{{ asset($activeTemplateTrue . 'images/elements/white-wave-2.png') }}" alt="wave image">
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-7 col-xl-8 col-lg-10">
                    <div class="section-header text-center">
                        <div class="section-top-title border-left text--base">{{ __(@$content->data_values->title) }}</div>
                        <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center gy-4">
                @foreach ($elements as $element)
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <div class="service-card rounded-3">
                            <div class="service-card__icon rounded-2 text--base">
                                @php echo @$element->data_values->icon @endphp
                            </div>
                            <div class="service-card__content">
                                <h3 class="title">{{ __(@$element->data_values->heading) }}</h3>
                                <p class="mt-2">{{ __(@$element->data_values->subheading) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
