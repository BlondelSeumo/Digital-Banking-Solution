@php
    $faq = getContent('faq.content', true);
    $faqs = getContent('faq.element', false, null, true);
    $totalEelements = $faqs->count();
@endphp

@if ($faq)
    <section id="faq" class="pt-100 pb-100 section--bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-lg-7 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <div class="section-header text-center">
                        <h2 class="section-title">{{ __(@$faq->data_values->heading) }}</h2>
                        <p class="mt-2">{{ __(@$faq->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            <div class="accordion custom--accordion" id="faqAccordion">
                <div class="row gy-4 justify-content-center">
                    @foreach ($faqs as $element)
                        <div class="col-lg-6 wow fadeInRight @if ($totalEelements % 2 != 0 && $loop->last) col-lg-12 @endif" data-wow-duration="0.5s" data-wow-delay="0.3s">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="h-{{ $element->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c-{{ @$element->id }}" aria-expanded="false" aria-controls="c-{{ @$element->id }}">
                                        {{ __(@$element->data_values->question) }}
                                    </button>
                                </h2>
                                <div id="c-{{ $element->id }}" class="accordion-collapse collapse" aria-labelledby="h-{{ $element->id }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>{{ __(@$element->data_values->answer) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
