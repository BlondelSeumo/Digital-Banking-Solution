@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Deposit via') {{ __($deposit->gateway->name) }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ $data->url }}" method="{{ $data->method }}">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">@lang('You have to pay ')</span>
                                <span>{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">@lang('You will get ')</span>
                                <span>{{ showAmount($deposit->amount) }}</span>
                            </li>
                        </ul>
                        <script src="{{ $data->src }}" class="stripe-button" @foreach ($data->val as $key => $value)
                            data-{{ $key }}="{{ $value }}" @endforeach></script>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .h-45 {
            height: 45px;
        }

        .stripe-button-el {
            background-image: unset !important;
        }
    </style>
@endpush

@push('script-lib')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('button[type="submit"]').addClass("btn btn--base h-45 w-100 mt-3").text("Pay Now");
        })(jQuery);
    </script>
@endpush
