@php
    $workContent = getContent('how_it_work.content', true);
    $workElement = getContent('how_it_work.element', orderById: true);
@endphp
<section class="py-120 work-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h6 class="section-heading__subtitle">{{ __(@$workContent->data_values->title) }}</h6>
                    <h2 class="section-heading__title">{{ __(@$workContent->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row gx-5 gy-3">
            @foreach ($workElement as $element)
                <div class="col-lg-3 col-sm-6 col-xsm-6">
                    <div class="work-card text-center">
                        @if (!$loop->last)
                            <img class="work-card__line" src="{{ asset($activeTemplateTrue . 'images/shapes/line.png') }}" alt="@lang('image')" />
                        @endif
                        <span class="work-card__number rounded-circle mx-auto">{{ $loop->iteration }}</span>
                        <div class="work-card__content">
                            <h4 class="work-card__title">{{ __(@$element->data_values->heading) }}</h4>
                            <p class="work-card__desc">{{ __(@$element->data_values->subheading) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
