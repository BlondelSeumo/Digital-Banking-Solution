@php
    $featureContent = getContent('feature.content', true);
    $features = getContent('feature.element', false, 3, true);
@endphp
@if (!blank($features))
    <div class="py-120 features section-bg-light">
        <div class="container">

            <div class="section-heading ">
                <h6 class="section-heading__subtitle">{{ __(@$featureContent->data_values->heading) }}</h6>
                <h2 class="section-heading__title">
                    {{ __(@$featureContent->data_values->subheading) }}
                </h2>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach ($features as $feature)
                    <div class="col-lg-4 col-sm-6 col-xsm-6">
                        <div class="feature-card flex-center">
                            <div class="feature-card__overlay"></div>
                            <div class="feature-card__icon">
                                <img src="{{ asset($activeTemplateTrue . 'images/shapes/hexagon-shap.png') }}" alt="@lang('image')" />
                                <span class="icon">
                                    @php echo @$feature->data_values->icon @endphp
                                </span>
                            </div>
                            <div class="feature-card__content text-center">
                                <h4 class="feature-card__title">{{ __(@$feature->data_values->heading) }}</h4>
                                <p class="feature-card__desc">
                                    {{ __(@$feature->data_values->subheading) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
