@php
    $content = getContent('why_choose.content', true);
    $elements = getContent('why_choose.element');
@endphp

@if ($content)
    <section class="pt-100 pb-100 bg_img dark--overlay-two" style="background-image: url('{{ getImage('assets/images/frontend/why_choose/' . @$content->data_values->image) }}');">
        <div class="bottom-wave d-lg-block d-none">
            <img src="{{ asset($activeTemplateTrue . 'images/elements/white-wave-1.png') }}" alt="wave image">
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-3 text-xl-start text-center">
                    <div class="section-header">
                        <div class="section-top-title border-left text--base">{{ __(@$content->data_values->title) }}</div>
                        <h2 class="section-title text-white">{{ __(@$content->data_values->heading) }}</h2>
                        <a href="{{ @$content->data_values->btn_link }}" class="btn mt-4 bg--base text-white">
                            {{ __(@$content->data_values->btn_text) }}
                        </a>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="row gy-4">
                        @foreach ($elements as $element)
                            <div class="col-md-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                                <div class="choose-card rounded-3">
                                    <div class="choose-card__icon">
                                        @php echo @$element->data_values->icon @endphp
                                    </div>
                                    <div class="choose-card__content">
                                        <h3 class="title">{{ __(@$element->data_values->heading) }}</h3>
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
