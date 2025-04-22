<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ gs()->siteName($pageTitle ?? '') }}</title>
    <!-- favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">

    <link rel="stylesheet" href="{{ asset('assets/global/css/pdf.css') }}">

    @stack('style')
</head>

<body>
    <main>
        <div class="list--row">
            <div class="address-to float-left">
                <h5 class="page-title">{{ @$pageTitle }}</h5>
                @if (request()->date)
                    <p class="text-small">@lang('Date'): {{ request()->date }}</p>
                @endif

                @if (request()->search)
                    <p class="text-small">@lang('Search Key'): {{ request()->search }}</p>
                @endif
            </div>

            <div class="address-form float-right">
                <ul class="text-center">
                    <li>
                        <img src="{{ siteLogo('dark') }}" class="logo" alt="Logo">
                    </li>
                </ul>
            </div>
        </div>

        <div class="body">
            @yield('main-content')
        </div>
    </main>

    <footer>
        <div class="d-block text-center">
            @lang('Powered by') {{ __(gs()->site_name) }}
        </div>
    </footer>
</body>

</html>
