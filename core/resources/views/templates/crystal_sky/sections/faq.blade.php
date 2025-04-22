@php
    $faq = getContent('faq.content', true);
    $faqs = getContent('faq.element', orderById: true);
@endphp
@if ($faq)
    <section class="py-120 faq section-bg-light bg-img" data-background-image="{{ asset($activeTemplateTrue . 'images/thumbs/faq-bg.png') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle">{{ __(@$faq->data_values->heading) }}</h6>
                        <h2 class="section-heading__title">
                            {{ __(@$faq->data_values->subheading) }}
                        </h2>
                    </div>
                    <div class="faq-content">
                        <p class="faq-content__desc">{{ __(@$faq->data_values->description) }}</p>
                        <a href="{{ @$faq->data_values->button_link }}" class="btn btn--base">{{ __(@$faq->data_values->button_text) }}</a>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12 right-section">
                    <div class="accordion accordion-flush custom--accordion" id="viserBank-faq">
                        @foreach ($faqs as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button  @if (!$loop->first) collapsed @endif" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#id-{{ $loop->iteration }}-faq"
                                        @if ($loop->first) aria-expanded="true" @else aria-expanded="false" @endif
                                        aria-controls="id-{{ $loop->iteration }}-faq">
                                        {{ __(@$faq->data_values->question) }}
                                    </button>
                                </h2>
                                <div id="id-{{ $loop->iteration }}-faq"
                                    class="accordion-collapse collapse @if ($loop->first) show @endif" data-bs-parent="#viserBank-faq">
                                    <p class="accordion-body">
                                        {{ __(@$faq->data_values->answer) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
