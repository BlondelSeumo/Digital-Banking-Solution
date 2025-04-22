@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card custom--card">
                <div class="card-body">
                    <form action="{{ route('user.withdraw.submit') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        @if (strip_tags($withdraw->method->description))
                            <div class="bg--light text--info mb-2 rounded p-3 text-center">
                                @php
                                    echo $withdraw->method->description;
                                @endphp
                            </div>
                        @endif

                        <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form->id }}" />

                        <button class="btn btn-md btn--base w-100" type="submit">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        "use strict";
        (function($) {
            $('label').removeClass('form-label fw-bold');
        })(jQuery);
    </script>
@endpush
