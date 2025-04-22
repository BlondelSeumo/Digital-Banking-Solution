@extends($activeTemplate . 'layouts.app')
@section('app')
    <div class="maintenance-page flex-column justify-content-center">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-8 text-center">
                    <div class="row justify-content-center">

                        <div class="col-xl-10 mb-3">
                            <img src="{{ getImage('assets/images/maintenance/' . @$maintenance->data_values->image, '660x325') }}" alt="maintenance-mode">
                            <h4 class="text--danger">{{ __(@$maintenance->data_values->heading) }}</h4>
                        </div>
                    </div>
                    <p class="mx-auto text-center">@php echo $maintenance->data_values->description @endphp</p>
                </div>
            </div>
        </div>
    </div>
@endsection
