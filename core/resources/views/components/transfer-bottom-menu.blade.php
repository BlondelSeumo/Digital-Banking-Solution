@push('bottom-menu')
    @if (gs()->modules->own_bank || gs()->modules->other_bank || gs()->modules->wire_transfer)
        <li>
            <a href="{{ route('user.transfer.history') }}" class="{{ menuActive('user.transfer.history') }}">
                @lang('History')
            </a>
        </li>

    
        @if (gs()->modules->own_bank)
            <li>
                <a href="{{ route('user.transfer.own.bank.beneficiaries') }}" class="{{ menuActive('user.transfer.own.bank.beneficiaries') }}">
                    @lang('Within') @lang(gs()->site_name)</a>
            </li>
        @endif

        @if (gs()->modules->other_bank)
            <li><a href="{{ route('user.transfer.other.bank.beneficiaries') }}" class="{{ menuActive('user.transfer.other.bank.beneficiaries') }}">
                    @lang('Other Bank')
                </a>
            </li>
        @endif

        @if (gs()->modules->wire_transfer)
            <li>
                <a href="{{ route('user.transfer.wire.index') }}" class="{{ menuActive('user.transfer.wire.index') }}">
                    @lang('Wire Transfer')
                </a>
            </li>
        @endif
    @endif
@endpush
