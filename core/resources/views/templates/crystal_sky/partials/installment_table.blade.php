<div class="card custom--card overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Installment Date')</th>
                        <th>@lang('Given On')</th>

                        @if (!Route::is('user.fdr.instalment.logs'))
                            <th>@lang('Delay')</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($installments as $installment)
                        <tr>
                            <td>{{ $loop->index + $installments->firstItem() }}</td>

                            <td class="{{ !$installment->given_at && $installment->installment_date < today() ? 'text--danger' : '' }}">
                                {{ showDateTime($installment->installment_date, 'd M, Y') }}
                            </td>

                            <td>
                                @if ($installment->given_at)
                                    {{ showDateTime($installment->given_at, 'd M, Y') }}
                                @else
                                    <small>@lang('Not yet')</small>
                                @endif
                            </td>
                            @if (!Route::is('user.fdr.instalment.logs'))
                            <td>
                                @if ($installment->given_at)
                                    {{ $installment->delayInDays() }} @lang('Day')
                                @else
                                    ...
                                @endif
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($installments->hasPages())
        <div class="card-footer">
            {{ paginateLinks($installments) }}
        </div>
    @endif
</div>

@push('style')
    <style>
        .list-group {
            gap: 0.8rem;
        }

        .list-group-item {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            border: 0;
            padding: 0;
        }

        .caption {
            font-size: 0.8rem;
            color: #b1b1b1;
            line-height: 1;
        }

        .value {
            color: #686a81;
            font-weight: 500;
            line-height: 1.8;
        }
    </style>
@endpush
