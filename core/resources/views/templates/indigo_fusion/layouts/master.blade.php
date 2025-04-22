@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @include($activeTemplate . 'partials.header')
    <div class="main-wrapper">
        @include($activeTemplate . 'partials.breadcrumb')
        @include($activeTemplate . 'partials.bottom_menu')
        <div class="pt-100 pb-100 bg_img" style="background-image: url(' {{ asset($activeTemplateTrue . 'images/elements/bg1.jpg') }} ');">
            <div class="container">
                @yield('content')
            </div>
        </div>
        @include($activeTemplate . 'partials.footer')
    </div>

    @push('script')
        <script>
            (function($) {
                "use strict";
                $.each($('.select2'), function() {
                    $(this)
                        .wrap(`<div class="position-relative"></div>`)
                        .select2({
                            dropdownParent: $(this).parent()
                        });
                });
            })(jQuery);
        </script>
    @endpush
@endsection
