<div class="card b-radius--10 viser--table">
    <div class="card-body p-0">
        <div class="table-responsive--md table-responsive">
            <table class="table table--light style--two">
                @php $isNotFdr = !request()->routeIs('admin.fdr.installments'); @endphp
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Installment Date')</th>
                        <th>@lang('Given On')</th>
                        @if ($isNotFdr)
                            <th>@lang('Delay')</th>
                            <th>@lang('Charge')</th>
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
                                    @lang('Not yet')
                                @endif
                            </td>

                            @if ($isNotFdr)
                                <td>
                                    @if ($installment->given_at)
                                        @php
                                            $delay = $installment->installment_date->startOfDay()->diffInDay ($installment->given_at->startOfDay())
                                        @endphp
                                        {{ $delay }} {{__(Str::plural('Day', $delay))}}
                                    @else
                                        ...
                                    @endif
                                </td>

                                <td>
                                    {{ showAmount($installment->delay_charge) }}
                                </td>
                            @endif

                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($installments->hasPages())
        <div class="card-footer py-4">
            {{ paginateLinks($installments) }}
        </div>
    @endif
</div>

@push('style')
    <style>
        .list-group {
            gap: 1rem;
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
            color: #a8a8a8;
        }

        .value {
            font-size: 1rem;
            color: #787d85;
            font-weight: 500;
        }
    </style>
@endpush
