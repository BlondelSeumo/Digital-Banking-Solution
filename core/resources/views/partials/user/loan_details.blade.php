<ul class="caption-list-two mt-3 p-0">
    <li>
        <span class="caption">@lang('Loan No.')</span>
        <span class="value fw-bold">#{{ $loan->loan_number }}</span>
    </li>

    <li>
        <span class="caption">@lang('Plan')</span>
        <span class="value">{{ $loan->plan->name }}</span>
    </li>

    <li>
        <span class="caption">@lang('Interest Rate')</span>
        <span class="value">{{ getAmount($loan->interestRate()) }}%</span>
    </li>

    <li>
        <span class="caption">@lang('Installment Interval')</span>
        <span class="value">{{ $loan->plan->installment_interval }} {{ __(Str::plural('Day', $loan->plan->installment_interval)) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Applied At')</span>
        <span class="value">{{ showDateTime($loan->created_at) }}</span>
    </li>

    @if ($loan->approved_at)
        <li>
            <span class="caption">@lang('Approved At')</span>
            <span class="value">{{ showDateTime($loan->approved_at) }}</span>
        </li>
    @endif

    <li>
        <span class="caption">@lang('Loan Amount')</span>
        <span class="value fw-bold text--info fs--18px">{{ showAmount($loan->amount) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Per Installment')</span>
        <span class="value fw-bold">{{ showAmount($loan->per_installment) }}</span>
    </li>

    <li>
        <span class="caption">@lang('Total Installment')</span>
        <span class="value fw-bold">{{ $loan->total_installment }}</span>
    </li>

    <li>
        <span class="caption">@lang('Given Installment')</span>
        <span class="value fw-bold">{{ $loan->given_installment }}</span>
    </li>

    @if ($loan->nextInstallment)
        <li>
            <span class="caption">@lang('Next Installment Date')</span>
            <span class="value fw-bold">{{ showDateTime($loan->nextInstallment->installment_date, 'd M, Y') }}</span>
        </li>
    @endif

    @if ($loan->paid_amount)
        <li class="fw-bold">
            <span class="caption">@lang('Total Paid')</span>
            <span class="value">{{ showAmount($loan->paid_amount) }}</span>
        </li>
    @endif

    <li class="fw-bold">
        <span class="caption">@lang('Total Payable')</span>
        <span class="value @if ($loan->total_installment == $loan->given_installment) text--success @else text--danger @endif">{{ showAmount($loan->payable_amount) }}</span>
    </li>
</ul>
