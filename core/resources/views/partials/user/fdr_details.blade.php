<ul class="caption-list-two mt-3 p-0">
    <li>
        <span class="caption">@lang('FDR No.')</span>
        <span class="value fw-bold">#{{ __($fdr->fdr_number) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Plan')</span>
        <span class="value fw-bold">{{ __($fdr->plan->name) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Profit Rate')</span>
        <span class="value fw-bold">{{ getAmount($fdr->plan->interest_rate) }}%</span>
    </li>

    <li>
        <span class="caption">@lang('Opened On')</span>
        <span class="value">{{ showDateTime($fdr->created_at, 'F d, Y') }}</span>
    </li>

    <li>
        <span class="caption">@lang('Lock In Period')</span>
        <span class="value">{{ showDateTime($fdr->locked_date, 'F d, Y') }}</span>
    </li>

    <li>
        <span class="caption">@lang('Installment Interval')</span>
        <span class="value fw-bold">{{ $fdr->installment_interval }} {{ __(Str::plural('Day', $fdr->installment_interval)) }}</span>
    </li>



    <li>
        <span class="caption">@lang('Per Installment')</span>
        <span class="value fw-bold">{{ showAmount(($fdr->amount * $fdr->plan->interest_rate) / 100) }}</span>
    </li>


    <li>
        <span class="caption">@lang('FDR Amount')</span>
        <span class="value fw-bold">{{ showAmount($fdr->amount) }}</span>
    </li>

    @if ($fdr->profit > 0)
        <li>
            <span class="caption">@lang('Profit Received')</span>
            <span class="value fw-bold">{{ showAmount($fdr->profit) }}</span>
        </li>
    @endif

</ul>
