@php
    $counterContent = getContent('counter.content', true);
    $counters = getContent('counter.element', orderById: true);
@endphp
<section class="py-120 counter section-overlay bg-img"
    data-background-image="{{ getImage('assets/images/frontend/counter/' . @$counterContent->data_values->image, '1920x400') }}">
    <span class="counter-overlay"></span>
    <div class="container">
        <div class="row g-4 ">
            @foreach ($counters as $counter)
                <div class="col-6 col-md-3">
                    <div class="counter-card counterup-item">
                        <h5 class="counter-card__title">
                            <span class="odometer" data-odometer-final="{{ @$counter->data_values->digit }}"></span>
                            <span class="extra">{{ @$counter->data_values->symbol }}</span>
                        </h5>
                        <p class="counter-card__desc">
                            {{ __(@$counter->data_values->title) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
@endpush
