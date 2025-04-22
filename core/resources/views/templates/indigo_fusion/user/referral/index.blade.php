@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="justify-content-center gy-4">
        <div class="card custom--card">
            <div class="card-body">
                @if ($user->referrer)
                    <div class="d-flex flex-wrap justify-content-center">
                        <h5><span class="mb-2">@lang('You are referred by')</span> {{ $user->referrer->username }}</h5>
                    </div>
                @endif
                <div class="treeview-container">
                    <ul class="treeview">
                        @if ($user->allReferees->count() > 0 && $maxLevel > 0)
                            <li class="items-expanded"> {{ $user->username }}
                                @include($activeTemplate . 'partials.under_tree', ['user' => $user, 'layer' => 0, 'isFirst' => true])
                            </li>
                        @else
                            <li class="items-expanded">@lang('No user found')</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
    <li><a class="active" href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
    <li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket.index') }}">@lang('Support Tickets')</a></li>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/tree-view.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/tree-view.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"
            $('.treeview').treeView();
        })(jQuery);
    </script>
@endpush
