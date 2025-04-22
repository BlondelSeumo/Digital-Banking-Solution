@php
    $subscribe = getContent('subscribe.content', true);
@endphp

@if ($subscribe)
    <div class="py-60 newsletter">
        <div class="container">
            <div class="row gy-3">
                <div class="col-md-12 col-lg-7 d-flex flex-wrap align-center">
                    <h4 class="newsletter__title m-0 ">
                        {{ __(@$subscribe->data_values->heading) }}
                    </h4>
                </div>
                <div class="col-md-12 col-lg-5">
                    <form class="newsletter-from d-flex flex-wrap items-center" id="subscribeForm">
                        @csrf
                        <input required type="email" class="form--control flex-grow-1" name="email" id="leadEmail" placeholder="@lang('Your email address')" />
                        <button class="btn btn--base" type="submit">@lang('Subscribe')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@push('script')
    <script>
        "use strict";
        (function($) {
            var form = $("#subscribeForm");
            form.on('submit', function(e) {
                e.preventDefault();
                var data = form.serialize();
                $.ajax({
                    url: `{{ route('subscribe') }}`,
                    method: 'post',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            form.find('input[name=email]').val('');
                            notify('success', response.message);
                        } else {
                            notify('error', response.error);
                            form.find('button[type=submit]').removeAttr('disabled');
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
