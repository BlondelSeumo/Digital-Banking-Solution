@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Deposit via') {{ __($deposit->gateway->name) }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush text-center">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            @lang('You have to pay')
                            <span>{{ showAmount($deposit->final_amount, currencyFormat:false) }} {{ __($deposit->method_currency) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            @lang('You will get')
                            <span>{{ showAmount($deposit->amount) }}</span>
                        </li>
                    </ul>
                    <form action="{{ $data->url }}" method="{{ $data->method }}">
                        <input type="hidden" custom="{{ $data->custom }}" name="hidden">
                        <script src="{{ $data->checkout_js }}" @foreach ($data->val as $key => $value)
                                data-{{ $key }}="{{ $value }}" @endforeach></script>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('input[type="submit"]').addClass("mt-3 btn h-45 btn--base w-100");
        })(jQuery);
    </script>
@endpush
