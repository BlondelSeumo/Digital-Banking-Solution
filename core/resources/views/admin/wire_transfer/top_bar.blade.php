@can(['admin.wire.transfer.setting', 'admin.wire.transfer.form'])
    <ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
        <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>

        @can('admin.wire.transfer.setting')
            <li class="nav-item {{ menuActive(['admin.wire.transfer.setting']) }}" role="presentation">
                <a href="{{ route('admin.wire.transfer.setting') }}" class="nav-link text-dark" type="button">
                    <i class="las la-globe"></i> @lang('Setting')
                </a>
            </li>
        @endcan

        @can('admin.wire.transfer.form')
            <li class="nav-item {{ menuActive('admin.wire.transfer.form') }}" role="presentation">
                <a href="{{ route('admin.wire.transfer.form') }}" class="nav-link text-dark" type="button">
                    <i class="las la-envelope"></i> @lang('Transfer Form')
                </a>
            </li>
        @endcan
    </ul>
@endcan
