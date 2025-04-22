@extends($activeTemplate . 'layouts.frontend')
@php
    $contact = getContent('contact_us.content', true);
    $elemenets = getContent('contact_us.element');
@endphp
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row gy-4 justify-content-center pb-50">
                <div class="col-xl-6 col-lg-5 order-lg-1 order-2">
                    <div class="map-wrapper">
                        <iframe src="{{ @$contact->data_values->map_source }}" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-7 order-lg-2 order-1">
                    <div class="contact-form-wrapper">
                        <h2 class="title">{{ __(@$contact->data_values->heading) }}</h2>
                        <form action="" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('Name')</label>
                                        <div class="custom-icon-field">
                                            <i class="las la-user"></i>
                                            <input name="name" type="text" class="form-control form--control" placeholder="@lang('Your Name')" value="{{ old('name',@$user->fullname) }}" @if($user && $user->profile_complete) readonly @endif required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('Email')</label>
                                        <div class="custom-icon-field">
                                            <i class="las la-envelope"></i>
                                            <input name="email" id="email" type="text" placeholder="@lang('Enter E-Mail Address')" class="form--control" value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>@lang('Subject')</label>
                                        <div class="custom-icon-field">
                                            <i class="las la-clipboard-list"></i>
                                            <input name="subject" id="subject" type="text" placeholder="@lang('Write your subject')" class="form--control" value="{{ old('subject') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 form-group">
                                    <label>@lang('Message')</label>
                                    <div class="custom-icon-field">
                                        <textarea name="message" wrap="off" placeholder="@lang('Write your message')" class="form--control" required>{{ old('message') }}</textarea>
                                        <i class="las la-comment"></i>
                                    </div>
                                </div>
                            </div>
                            <x-captcha />
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
            <h3 class="fw-bold mb-4">@lang('Quick Information')</h3>
            <div class="row gy-4 justify-content-center">
                @foreach ($elemenets as $elemenet)
                    <div class="col-lg-4 col-md-6">
                        <div class="contact-info-card section--bg2">
                            <div class="contact-info-card__icon">
                                @php echo $elemenet->data_values->icon @endphp
                            </div>
                            <div class="contact-info-card__content">
                                <h4 class="title">{{ __($elemenet->data_values->address_type) }}</h4>
                                <a href="javascript:void(0)">{{ __($elemenet->data_values->address) }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
