@can(['admin.staff.index', 'admin.roles.index'])
    <ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
        <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
        @can('admin.staff.index')
            <li class="nav-item {{ menuActive('admin.staff*') }}" role="presentation">
                <a href="{{ route('admin.staff.index') }}" class="nav-link text-dark" type="button">
                    <i class="las la-user"></i> @lang('Staff')
                </a>
            </li>
        @endcan

        @can('admin.roles.index')
            <li class="nav-item {{ menuActive('admin.roles*') }}" role="presentation">
                <a href="{{ route('admin.roles.index') }}" class="nav-link text-dark" type="button">
                    <i class="las la-list"></i> @lang('Roles')
                </a>
            </li>
        @endcan
    </ul>
@endcan
