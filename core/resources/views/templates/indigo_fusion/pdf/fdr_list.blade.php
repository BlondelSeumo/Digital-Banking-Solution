@extends('pdf.layouts.master')

@section('main-content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>@lang('FDR No.') | @lang('Plan')</th>
                <th>@lang('Amount')</th>
                <th>@lang('Profit')</th>
                <th>@lang('Next Installment')</th>
                <th>@lang('Lock In Period')</th>
                <th>@lang('Status')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allFdr as $fdr)
                <tr>
                    <td>
                        <strong>{{ $fdr->fdr_number }}</strong>
                        <small class="d-block text--success">{{ __($fdr->plan->name) }}</small>
                    </td>
                    <td>
                        <strong>{{showAmount($fdr->amount) }}</strong>
                        <small class="d-block">
                            @lang('With') {{ getAmount($fdr->interest_rate) }}%
                            @lang('Profit Rate')
                        </small>
                    </td>
                    <td>
                        {{showAmount($fdr->per_installment) }}
                        <small class="d-block">@lang('Per') {{ $fdr->installment_interval }} {{__(Str::plural('Day', $fdr->installment_interval))}}</small>
                    </td>
                    <td>
                        @if ($fdr->status != 2)
                            {{ showDateTime($fdr->next_installment_date, 'd M, Y') }}
                        @else
                            @lang('N/A')
                        @endif
                    </td>
                    <td>
                        {{ showDateTime($fdr->locked_date->endOfDay(), 'd M, Y h:i A') }}
                        <small class="d-block">{{ diffForHumans($fdr->locked_date, 'd M, Y') }}</small>
                    </td>

                    <td>@php echo $fdr->statusBadge; @endphp</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
