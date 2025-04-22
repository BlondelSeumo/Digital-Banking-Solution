@php
    $content = getContent('registration_disabled.content', true);
@endphp
@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @if ($content)
        <section id="about" class="pt-100 pb-100 section--bg">
            <div class="container">
                <div class="row gy-5 justify-content-center">
                    <div class="col-lg-8 text-center">
                        <div class="section-top-title border-left text--base">{{ __(@$content->data_values->heading) }}</div>
                        <h2 class="">{{ __(@$content->data_values->subheading) }}</h2>
                        <a href="{{ @$content->data_values->button_link }}" class="btn btn--base mt-3">{{ __(@$content->data_values->button_text) }}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
