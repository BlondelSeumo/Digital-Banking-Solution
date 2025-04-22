@php
    $features = getContent('feature.element');
@endphp
@if ($features->count())
<div class="feature-section pb-100">
    <div class="container">
        <div class="row gy-4">
            @foreach($features as $feature)
            <div class="col-xl-3 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="feature-card rounded-3">
                    <div class="icon">
                        @php echo $feature->data_values->icon @endphp
                    </div>
                    <h3 class="title">{{ __(@$feature->data_values->heading) }}</h3>
                    <p>{{ __(@$feature->data_values->subheading) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
