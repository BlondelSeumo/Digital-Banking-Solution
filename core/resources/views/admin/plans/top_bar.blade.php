@can(['admin.plans.fdr.index', 'admin.plans.dps.index', 'admin.plans.loan.index'])
    <ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
        <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
        @can('admin.plans.fdr.index')
            <li class="nav-item {{ menuActive('admin.plans.fdr*') }}" role="presentation">
                <a href="{{ route('admin.plans.fdr.index') }}" class="nav-link text-dark" type="button">
                    @lang('FDR Plans')
                </a>
            </li>
        @endcan
        @can('admin.plans.dps.index')
            <li class="nav-item {{ menuActive('admin.plans.dps*') }}" role="presentation">
                <a href="{{ route('admin.plans.dps.index') }}" class="nav-link text-dark" type="button">
                    @lang('DPS Plans')
                </a>
            </li>
        @endcan

        @can('admin.plans.loan.index')
            <li class="nav-item {{ menuActive('admin.plans.loan*') }}" role="presentation">
                <a href="{{ route('admin.plans.loan.index') }}" class="nav-link text-dark" type="button">
                    @lang('Loan Plans')
                </a>
            </li>
        @endcan
    </ul>
@endcan
