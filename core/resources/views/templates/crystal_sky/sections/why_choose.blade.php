@php
    $chooseContent = getContent('why_choose.content', true);
    $chooseElements = getContent('why_choose.element', orderById: true);
@endphp

@if ($chooseContent)
    <section class="py-120 chose-us">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-5">
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle">{{ __(@$chooseContent->data_values->heading) }}</h6>
                        <h2 class="section-heading__title">
                            {{ __(@$chooseContent->data_values->subheading) }}
                        </h2>
                    </div>
                    <div class="chose-us__content d-flex flex-wrap flex-column">
                        @foreach ($chooseElements as $choose)
                            <div class="service-list d-flex flex-wrap">
                                <h6 class="service-list__number">{{ $loop->iteration }}</h6>
                                <div class="service-list__content">
                                    <h5 class="service-list__title">{{ __(@$choose->data_values->heading) }}</h5>
                                    <p class="service-list__desc">
                                        {{ __(@$choose->data_values->description) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="right-section">
                        <img class="chose-us__img" src="{{ getImage('assets/images/frontend/why_choose/' . @$chooseContent->data_values->image_one, '470x425') }}" alt="@lang('image')" />
                        <div class="chose-icon-card flex-align gap-2">

                            <span class="icon flex-algin flex-center">
                                @php echo $chooseContent->data_values->icon @endphp
                            </span>

                            <div class="chose-icon-card__conent">
                                <h4 class="chose-icon-card__title">{{ __(@$chooseContent->data_values->title) }}</h4>
                                <h6 class="chose-icon-card__desc">{{ __(@$chooseContent->data_values->subtitle) }}</h6>
                            </div>
                        </div>

                        <div class="rotation-animation d-flex flex-wrap gap-3">
                            <div class="rotation-animation-content">
                                <div class="rotation-animation-content__bg bg-img" data-background-image="{{ getImage('assets/images/frontend/why_choose/' . @$chooseContent->data_values->circle_image, '470x425') }}"></div>
                                <div class="rotation-animation-content__text">
                                    <p>{{ __(@$chooseContent->data_values->slogan) }}</p>
                                </div>
                            </div>
                        </div>
                        <img class="bottom-img" src="{{ getImage('assets/images/frontend/why_choose/' . @$chooseContent->data_values->image_two, '330x330 ') }}" alt="@lang('image')" />
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
