@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center gy-4">
                @php
                    echo @$cookie->data_values->description;
                @endphp
            </div>
        </div>
    </section>
@endsection
