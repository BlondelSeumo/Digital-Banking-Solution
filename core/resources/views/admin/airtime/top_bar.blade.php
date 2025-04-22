@can(['admin.airtime.countries', 'admin.airtime.countries'])
    <ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
        <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>

        @can('admin.airtime.countries')
            <li class="nav-item {{ menuActive('admin.airtime.countries*') }}" role="presentation">
                <a href="{{ route('admin.airtime.countries') }}" class="nav-link text-dark" type="button">
                    <i class="las la-globe"></i> @lang('Countries')
                </a>
            </li>
        @endcan

        @can('admin.airtime.operators')
            <li class="nav-item {{ menuActive('admin.airtime.operators*') }}" role="presentation">
                <a href="{{ route('admin.airtime.operators') }}" class="nav-link text-dark" type="button">
                    <i class="las la-mobile"></i> @lang('Operators')
                </a>
            </li>
        @endcan
    </ul>
@endcan
