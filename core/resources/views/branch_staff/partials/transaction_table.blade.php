<div class="table-responsive--sm table-responsive">
    <table class="table--light style--two table">
        <thead>
            <tr>
                <th>@lang('TRX No.')</th>
                <th>@lang('Account No.')</th>
                <th>@lang('Account Name')</th>
                @if (isManager())
                    <th>@lang('Account Officer')</th>
                @endif
                <th>@lang('Initiated')</th>
                <th>@lang('Remark')</th>
                <th>@lang('Amount')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->trx }}</td>

                    <td>
                        <a href="{{ route('staff.account.detail', $transaction->user->account_number) }}">
                            {{ @$transaction->user->account_number }}
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('staff.account.detail', $transaction->user->account_number) }}">
                            {{ @$transaction->user->fullname }}
                        </a>
                    </td>
                    @if (isManager())
                        <td>
                            <a href="{{ route('staff.profile.other', $transaction->branchStaff->id) }}">
                                {{ @$transaction->branchStaff->name }}
                            </a>
                        </td>
                    @endif
                    <td>{{ showDateTime($transaction->created_at, 'd M Y, h:i A') }}</td>
                    <td>{{ __(keyToTitle($transaction->remark)) }}</td>
                    <td>
                        <span class="fw-bold @if ($transaction->trx_type == '+') text--success @else text--danger @endif">
                            {{ showAmount($transaction->amount) }} 
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
