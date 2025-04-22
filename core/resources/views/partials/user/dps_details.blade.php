<ul class="caption-list-two mt-3 p-0">
    <li>
        <span class="caption">@lang('DPS No.')</span>
        <span class="value fw-bold">#{{ $dps->dps_number }}</span>
    </li>

    <li>
        <span class="caption">@lang('Plan')</span>
        <span class="value">{{ __($dps->plan->name) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Interest Rate')</span>
        <span class="value">{{ getAmount($dps->plan->interest_rate) }}%</span>
    </li>

    <li>
        <span class="caption">@lang('Opened On')</span>
        <span class="value">{{ showDateTime($dps->created_at, 'd M, Y') }}</span>
    </li>

    <li>
        <span class="caption">@lang('Installment Interval')</span>
        <span class="value">{{ $dps->installment_interval }} {{ __(Str::plural('Day', $dps->installment_interval)) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Per Installment')</span>
        <span class="value fw-bold">{{ showAmount($dps->per_installment) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Total Installment')</span>
        <span class="value fw-bold">{{ $dps->total_installment }}</span>
    </li>

    <li>
        <span class="caption">@lang('Given Installment')</span>
        <span class="value fw-bold">{{ $dps->given_installment }}</span>
    </li>

    <li>
        <span class="caption">@lang('Deposited Amount')</span>
        <span class="value fw-bold">{{ showAmount($dps->per_installment * $dps->given_installment) }}</span>
    </li>

    @if ($dps->nextInstallment)
        <li>
            <span class="caption">@lang('Next Installment Date')</span>
            <span class="value fw-bold text--warning">{{ showDateTime($dps->nextInstallment->installment_date, 'd M, Y') }}</span>
        </li>
    @endif

    <li>
        <span class="caption">@lang('DPS Amount')</span>
        <span class="value fw-bold">{{ showAmount($dps->depositedAmount()) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Profit Amount')</span>
        <span class="value fw-bold">{{ showAmount($dps->plan->final_amount - $dps->depositedAmount()) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Maturity Amount')</small></span>
        <span class="value fw-bold">{{ showAmount($dps->plan->final_amount) }}</span>
    </li>

</ul>
