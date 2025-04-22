<ul class="caption-list-two p-0">
    <li>
        <span class="caption">@lang('From Account')</span>
        <span class="value text-end">
            {{ $transfer->user->username }}
            <br>
            {{ $transfer->user->account_number }}
        </span>
    </li>

    @if ($transfer->wire_transfer_data)
        <li>
            <span class="caption">@lang('To Account Number')</span>
            <span class="value"></span>

            <span class="value text-end">
                {{ $transfer->wireTransferAccountName() }}
                <br>
                {{ $transfer->wireTransferAccountNumber() }}
            </span>
        </li>
    @else
        <li>
            <span class="caption">@lang('To Account')</span>
            <span class="value text-end">
                {{ $transfer->beneficiary->account_name }}
                <br>
                {{ $transfer->beneficiary->account_number }}
            </span>
        </li>
    @endif

    @if ($transfer->beneficiary && $transfer->beneficiary->beneficiary_type == OtherBank::class)
        <li>
            <span class="caption">@lang('Recipient Bank Name')</span>
            <span class="value">{{ __($transfer->beneficiary->beneficiaryOf->name) }} </span>
        </li>
    @endif

    <li>
        <span class="caption">@lang('Transfer Amount')</span>
        <span class="value text-end">
            <small>{{ __(gs('cur_text')) }}</small>
            <br>
            {{ showAmount($transfer->amount, currencyFormat: false) }}
        </span>
    </li>

    <li>
        <span class="caption">@lang('Time')</span>
        <span class="value text-end">
            {{ showDateTime($transfer->created_at, 'F d, Y') }}
            <br>
            {{ showDateTime($transfer->created_at, 'h:i A') }}
        </span>
    </li>

    <li>
        <span class="caption">@lang('TRX Number')</span>
        <span class="value">#{{ $transfer->trx }}</span>
    </li>
</ul>

