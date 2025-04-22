<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a class="sidebar__main-logo" href="{{ route('staff.dashboard') }}">
                <img src="{{ siteLogo() }}" alt="@lang('image')">
            </a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('staff.dashboard') }}">
                    <a class="nav-link" href="{{ route('staff.dashboard') }}">
                        <i class="menu-icon la la-home"></i>
                        <span class="menu-title">@lang('Home')</span>
                    </a>
                </li>

                @if (authStaff()->designation == Status::ROLE_ACCOUNT_OFFICER && gs()->modules->branch_create_user)
                    <li class="sidebar-menu-item {{ menuActive('staff.account.open') }}">
                        <a class="nav-link" href="{{ route('staff.account.open') }}">
                            <i class="menu-icon las la-user-circle"></i>
                            <span class="menu-title">@lang('Open Account')</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-menu-item {{ menuActive(['staff.account.all', 'staff.account.find', 'staff.account.detail']) }}">
                    <a class="nav-link" href="{{ route('staff.account.all') }}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Accounts')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ menuActive('staff.deposits') }}">
                    <a class="nav-link" href="{{ route('staff.deposits') }}">
                        <i class="menu-icon las la-wallet"></i>
                        <span class="menu-title">@lang('Deposits')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ menuActive('staff.withdrawals') }}">
                    <a class="nav-link" href="{{ route('staff.withdrawals') }}">
                        <i class="menu-icon las la-hand-holding-usd"></i>
                        <span class="menu-title">@lang('Withdrawals')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ menuActive('staff.transactions') }}">
                    <a class="nav-link" href="{{ route('staff.transactions') }}">
                        <i class="menu-icon las la-exchange-alt"></i>
                        <span class="menu-title">@lang('Transactions')</span>
                    </a>
                </li>

                @if (isManager())
                    <li class="sidebar-menu-item {{ menuActive('staff.branches') }}">
                        <a class="nav-link" href="{{ route('staff.branches') }}">
                            <i class="menu-icon las la-project-diagram"></i>
                            <span class="menu-title">@lang('My Branches')</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="text-uppercase mb-3 text-center">
                <span class="text--primary"></span>
                <span class="text--success"> </span>
            </div>
        </div>

        <div class="version-info text-center text-uppercase">
            <span class="text--primary">{{ __(systemDetails()['name']) }}</span>
            <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
