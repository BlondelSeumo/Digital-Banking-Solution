@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            @if (@gs()->modules->own_bank || @$gs()->modules->other_bank || @gs()->modules->wire_transfer)
                <a href="{{ route('user.transfer.history') }}" class="btn btn-outline--base {{ menuActive('user.transfer.history') }}">
                    @lang('Transfer History')
                </a>

                @if (@gs()->modules->own_bank)
                    <a href="{{ route('user.transfer.own.bank.beneficiaries') }}" class="btn btn-outline--base {{ menuActive('user.transfer.own.bank.beneficiaries') }}">
                        @lang('Transfer Within') @lang(gs()->site_name)</a>
                @endif

                @if (@gs()->modules->other_bank)
                    <a href="{{ route('user.transfer.other.bank.beneficiaries') }}" class="@if (request()->routeIs('user.transfer.other.bank.beneficiaries')) btn btn--base active @else btn btn-outline--base @endif">
                        @lang('Transfer to Other Bank')
                    </a>
                @endif
                @if (@gs()->modules->wire_transfer)
                    <a href="{{ route('user.transfer.wire.index') }}" class="@if (request()->routeIs('user.transfer.wire.index')) btn btn--base active @else btn btn-outline--base @endif">
                        @lang('Wire Transfer')
                    </a>
                @endif
            @endif
        </div>

        <div class="header-nav mb-0 flex-grow-1">
            @stack('header-nav')
        </div>
    </div>

    @yield('transfer-content')

@endsection

@push('style')
    <style>
        .btn[type=submit] {
            height: unset !important;
        }

       .nav-buttons .btn {
            padding: 12px 16px;
        }

        @media (max-width: 650px) {
            .nav-buttons .btn {
                padding: 8px 10px;
            }
        }
        @media (max-width: 550px) {
            .nav-buttons .btn {
               flex-grow: 1;
            }
        }
    </style>
@endpush
