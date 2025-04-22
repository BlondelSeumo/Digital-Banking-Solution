@php
    $content = getContent('overview.content', true);
    $elements = getContent('overview.element', false, 4, true);
@endphp

@if ($content)
    <section class="overview-section pt-100 pb-50">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="section-header text-center">
                        <h2 class="section-title text-white">{{ __(@$content->data_values->heading) }}</h2>
                        <p class="mt-3 text-white">{{ __(@$content->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            <div class="overview-area wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="row gy-4 justify-content-center">
                    @foreach ($elements as $element)
                        <div class="col-lg-3 col-6">
                            <div class="overview-card">
                                <div class="overview-card__icon">
                                    @php echo @$element->data_values->icon @endphp
                                </div>
                                <div class="overview-card__content">
                                    <h4 class="couter-number">{{ __(@$element->data_values->heading) }}</h4>
                                    <p>{{ __(@$element->data_values->subheading) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
