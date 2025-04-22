@extends($activeTemplate . 'layouts.frontend')
@php
    $contact = getContent('contact_us.content', true);
    $elemenets = getContent('contact_us.element');
@endphp
@section('content')
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="feature-overlay-section mb-60">
                        <div class="features bg-white">
                            <div class="container">
                                <div class="row gy-4 justify-content-center">
                                    <div class="col-md-4">
                                        <div class="feature-card flex-center flex-column">
                                            <div class="feature-card__overlay"></div>
                                            <div class="feature-card__icon">
                                                <img src="{{ asset($activeTemplateTrue . 'images/shapes/hexagon-shap.png') }}" alt="@lang('image')" />
                                                <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            <div class="feature-card__content text-center">
                                                <h4 class="feature-card__title">@lang('Office Address')</h4>
                                                <p class="feature-card__desc">{{ __(@$contact->data_values->contact_address) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="feature-card flex-center flex-column">
                                            <div class="feature-card__overlay"></div>
                                            <div class="feature-card__icon">
                                                <img src="{{ asset($activeTemplateTrue . 'images/shapes/hexagon-shap.png') }}" alt="@lang('image')" />
                                                <span class="icon"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <div class="feature-card__content text-center">
                                                <h4 class="feature-card__title">@lang('Phone Number')</h4>
                                                <p class="feature-card__desc">
                                                    <a class="link" href="tel:{{ @$contact->data_values->contact_number }}">{{ @$contact->data_values->contact_number }}</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="feature-card flex-center flex-column">
                                            <div class="feature-card__overlay"></div>
                                            <div class="feature-card__icon">
                                                <img src="{{ asset($activeTemplateTrue . 'images/shapes/hexagon-shap.png') }}" alt="@lang('image')" />
                                                <span class="icon"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <div class="feature-card__content text-center">
                                                <h4 class="feature-card__title">@lang('Email Address')</h4>
                                                <p class="feature-card__desc">
                                                    <a class="link" href="mailto:{{ @$contact->data_values->email_address }}">{{ @$contact->data_values->email_address }}</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-heading">
                                    <h6 class="section-heading__subtitle">{{ __(@$contact->data_values->heading) }}</h6>
                                    <h2 class="section-heading__title">{{ __(@$contact->data_values->subheading) }}</h2>
                                </div>
                            </div>
                            <form action="" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form--group">
                                            <label class="form--label">@lang('Name')</label>
                                            <input name="name" type="text" class="form-control form--control" value="{{ old('name',@$user->fullname) }}" @if($user && $user->profile_complete) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form--group">
                                            <label class="form--label">@lang('Email')</label>
                                            <input type="email" class="form--control" name="email" value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form--group">
                                            <label class="form--label">@lang('Subject')</label>
                                            <input type="text" name="subject" class="form--control" value="{{ old('subject') }}" required />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form--group">
                                            <label class="form--label">@lang('Message')</label>
                                            <textarea name="message" class="form--control" rows="4" cols="50">{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <x-captcha />

                                    <div class="col-12">
                                        <div class="form--group">
                                            <button class="btn btn--base" id="recaptcha" type="submit">@lang('Send Message')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="map">
        <iframe src="{{ @$contact->data_values->map_source }}" class="google-map" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
