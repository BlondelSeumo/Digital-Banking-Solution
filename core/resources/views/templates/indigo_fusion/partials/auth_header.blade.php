<li><a class="{{ menuActive('user.home') }}" href="{{ route('user.home') }}">@lang('Dashboard')</a></li>

@if (gs()->modules->deposit)
    <li> <a class="{{ menuActive('user.deposit*') }}" href="{{ route('user.deposit.history') }}">@lang('Deposit')</a></li>
@endif

@if (gs()->modules->withdraw)
    <li><a class="{{ menuActive('user.withdraw*') }}" href="{{ route('user.withdraw.history') }}">@lang('Withdraw')</a></li>
@endif

@if (gs()->modules->fdr)
    <li><a class="{{ menuActive('user.fdr*') }}" href="{{ route('user.fdr.plans') }}">@lang('FDR')</a></li>
@endif

@if (gs()->modules->dps)
    <li><a class="{{ menuActive('user.dps*') }}" href="{{ route('user.dps.plans') }}">@lang('DPS')</a></li>
@endif

@if (gs()->modules->loan)
    <li><a class="{{ menuActive('user.loan*') }}" href="{{ route('user.loan.plans') }}">@lang('Loan')</a></li>
@endif

@if (@gs()->modules->airtime)
    <li><a class="{{ menuActive('user.airtime*') }}" href="{{ route('user.airtime.form') }}">@lang('Mobile Top Up')</a></li>
@endif

@if (gs()->modules->own_bank || gs()->modules->other_bank || gs()->modules->wire_transfer)
    <li>
        <a class="{{ menuActive(['user.transfer*']) }}" href="{{ route('user.transfer.history') }}">@lang('Transfer')</a>
    </li>
@endif

<li>
    <a class="{{ menuActive(['user.profile.setting', 'user.twofactor', 'user.change.password', 'user.transaction.history', 'ticket', 'ticket.open', 'ticket. view']) }}"
        href="{{ route('user.profile.setting') }}">@lang('More')</a>
</li>
