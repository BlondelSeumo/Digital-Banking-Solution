@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $banner = getContent('banner.content', true);
    @endphp
    <section class="hero bg_img" style="background-image: url('{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->image, '1920x1280') }}');">
        <div class="hero__wave-shape">
            <img src="{{ asset($activeTemplateTrue . 'images/elements/white-wave-1.png') }}" alt="wave image">
        </div>
        <div class="hero__wave-shape two">
            <img src="{{ asset($activeTemplateTrue . 'images/elements/white-wave-1.png') }}" alt="wave image">
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9 text-center">
                    <h2 class="hero__title wow fadeInUp text-white" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        {{ __(@$banner->data_values->heading) }}
                    </h2>
                    <p class="wow fadeInUp mt-4 text-white" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        {{ __(@$banner->data_values->subheading) }}
                    </p>
                    <a class="btn btn--base wow fadeInUp mt-4 text-white" data-wow-duration="0.5s" data-wow-delay="0.3s" href="{{ @$banner->data_values->button_link }}">
                        {{ __(@$banner->data_values->button_text) }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
