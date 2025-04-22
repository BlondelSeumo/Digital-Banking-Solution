@php
    $staff = auth()->guard('branch_staff')->user();
@endphp

<nav class="navbar-wrapper bg--dark d-flex">
    <div class="navbar__left">
        <button type="button" class="res-sidebar-open-btn me-3"><i class="las la-bars"></i></button>
    </div>
    <div class="navbar__right">
        <ul class="navbar__action-list">

            @if (isManager())
                @php
                    $branches = authStaff()->assignBranch;
                    $selectedBranch = $branches->where('id', session('branchId'))->first();
                @endphp

                <li class="dropdown">
                    <button type="button" data-bs-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                        <span class="navbar-user">
                            <span class="navbar-user__thumb w-auto">
                                <i class="la la-map-marker" aria-hidden="true"></i>
                            </span>

                            <span class="navbar-user__info ps-0">
                                <span class="navbar-user__name">{{ $selectedBranch->name }} @lang('Branch')</span>
                            </span>
                            <span class="icon"><i class="las la-chevron-circle-down"></i></span>
                        </span>
                    </button>

                    <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                        @foreach ($branches as $branch)
                            <a href="{{ route('staff.branch.set', $branch->id) }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2 @if ($branch->id == $selectedBranch->id) active pe-none @endif">
                                <span class="dropdown-menu__caption">{{ __($branch->name) }}</span>
                            </a>
                        @endforeach
                    </div>
                </li>
            @endif

            <li class="dropdown">
                <button type="button" class="" data-bs-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                    <span class="navbar-user">
                        <span class="navbar-user__thumb">
                            <img src="{{ getImage(getFilePath('branchStaffProfile') . '/' . $staff->image, null, true) }}" alt="image">
                        </span>
                        <span class="navbar-user__info">
                            <span class="navbar-user__name">{{ __($staff->name) }}</span>
                        </span>
                        <span class="icon"><i class="las la-chevron-circle-down"></i></span>
                    </span>
                </button>

                <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                    <a href="{{ route('staff.profile') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-user-circle"></i>
                        <span class="dropdown-menu__caption">@lang('Profile')</span>
                    </a>

                    <a href="{{ route('staff.password') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-key"></i>
                        <span class="dropdown-menu__caption">@lang('Password')</span>
                    </a>

                    <a href="{{ route('staff.logout') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                        <span class="dropdown-menu__caption">@lang('Logout')</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

@push('style')
    <style>
        .dropdown-menu__item.active .dropdown-menu__caption {
            color: #979797 !important;
        }

        .navbar__action-list {
            justify-content: end;
        }

        @media (max-width: 767px) {

            .navbar__left,
            .navbar__right {
                width: unset;
            }

            .navbar__right {
                margin-top: 0px
            }
        }

        @media (max-width: 1199px){
            table thead th:first-child {
                border-radius: 5px 0px  0px 0px !important;
            }
            table thead th:last-child {
                border-radius: 0 5px  0px 0px !important;
            }
        }

    </style>
@endpush
