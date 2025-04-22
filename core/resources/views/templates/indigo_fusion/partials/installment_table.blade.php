<div class="row gy-3">
    <div class="col-lg-12">
        <div class="custom--card">
            <div class="card-body p-0">
                <div class="table-responsive--md">
                    <table class="custom--table table">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Installment Date')</th>
                                <th>@lang('Given On')</th>
                                <th>@lang('Delay')</th>
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

                                    <td>
                                        @if ($installment->given_at)
                                            {{ $installment->delayInDays() }} @lang('Day')
                                        @else
                                            ...
                                        @endif
                                    </td>
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
                <div class="card-footer py-2">
                    {{ paginateLinks($installments) }}
                </div>
            @endif
        </div>
    </div>
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
