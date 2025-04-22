@php
    $serviceContent = getContent('service.content', true);
    $services = getContent('service.element', false, 3, true);
@endphp

@if ($serviceContent)
    <section class="services-section py-120">
        <div class="services-section-overlay overlay">
            <div class="img-full d-flex">
                <div class="img-full__left">
                    <img src="{{ asset($activeTemplateTrue . 'images/thumbs/service-bg-left.png') }}" alt="@lang('image')">
                </div>
                <div class="img-full__right">
                    <img src="{{ getImage('assets/images/frontend/service/' . @$serviceContent->data_values->image, '665x760') }}" alt="@lang('image')">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle">{{ __(@$serviceContent->data_values->heading) }}</h6>
                        <h2 class="section-heading__title">{{ __(@$serviceContent->data_values->subheading) }}</h2>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-sm-6 col-xsm-6">
                        <div class="service-card text-center rounded">
                            <span class="service-card__icon">
                                @php echo @$service->data_values->icon @endphp
                            </span>
                            <div class="service-card__content">
                                <h5 class="service-card__heading">{{ __(@$service->data_values->heading) }}</h5>
                                <p class="service-card__desc">{{ __(@$service->data_values->description) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
