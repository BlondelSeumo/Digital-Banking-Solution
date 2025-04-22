@php
    $content = getContent('how_it_work.content', true);
    $elements = getContent('how_it_work.element', false, null, true);
@endphp

<section id="how-work" class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <div class="section-top-title border-left text--base">{{ __(@$content->data_values->title) }}</div>
                    <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            @foreach ($elements as $element)
                <div class="col-lg-3 col-sm-6 how-work-item wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <div class="how-work-card">
                        <div class="how-work-card__step">{{ __($loop->iteration) }}</div>
                        <h3 class="title mt-4">{{ __(@$element->data_values->heading) }}</h3>
                        <p class="mt-2">{{ __(@$element->data_values->subheading) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
