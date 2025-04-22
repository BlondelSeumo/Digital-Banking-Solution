@extends($activeTemplate . 'layouts.frontend')
@section('content') 
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                @php
                    echo $cookie->data_values->description;
                @endphp
            </div>
        </div>
    </section>
@endsection
