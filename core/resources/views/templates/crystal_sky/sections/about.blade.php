@php
    $aboutContent = getContent('about.content', true);
    $aboutElement = getContent('about.element', orderById: true);
@endphp
@if ($aboutContent)
    <section class="py-120 about-section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div class="about-thumb text-center">
                        <div class="thumb-card flex-align gap-3 text-start">
                            <span class="thumb-card__icon flex-center">
                                @php echo @$aboutContent->data_values->image_popup_icon @endphp
                            </span>
                            <div class="thumb-card__content">
                                <h5 class="thumb-card__title">{{ __(@$aboutContent->data_values->image_popup_digit) }}</h5>
                                <p class="thumb-card__desc">{{ __(@$aboutContent->data_values->image_popup_title) }}</p>
                            </div>
                        </div>
                        <img class="thumbnail" src="{{ getImage('assets/images/frontend/about/' . @$aboutContent->data_values->image, '385x460') }}"
                            alt="@lang('image')" />
                        <span class="shape">
                            <i class="icon-element-55-1"></i>
                        </span>
                    </div>
                </div>
                <div class="col-lg-5 order-lg-2 order-1">
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle">{{ __(@$aboutContent->data_values->heading) }}</h6>
                        <h2 class="section-heading__title">
                            {{ __(@$aboutContent->data_values->subheading) }}
                        </h2>
                    </div>
                    <div class="about-section__content">
                        <ul class="nav custom--tab nav-pills mb-3" id="about-tab" role="tablist">
                            @foreach ($aboutElement as $about)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if ($loop->first) active @endif" id="pills-{{ $loop->iteration }}-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-{{ $loop->iteration }}" type="button" role="tab"
                                        aria-controls="pills-{{ $loop->iteration }}" aria-selected="true">
                                        {{ __(@$about->data_values->heading) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="about-tabContent">
                            @foreach ($aboutElement as $about)
                                <div class="tab-pane fade @if ($loop->first) show active @endif" id="pills-{{ $loop->iteration }}"
                                    role="tabpanel" aria-labelledby="pills-{{ $loop->iteration }}-tab" tabindex="0">
                                    <p class="tab-pane__text">
                                        {{ __(@$about->data_values->description) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
