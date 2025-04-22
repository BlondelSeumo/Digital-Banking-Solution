@extends($activeTemplate . 'layouts.master')
@push('style')
    <style>
        @media (max-width: 504px) {
            a.btn.h-45.btn--base {
                font-size: 10px;
            }
        }
    </style>
@endpush
@section('content')

    <div class="card custom--card overflow-hidden">
        <div class="card-header">
            <div class="header-nav flex-sm-nowrap mb-0">
                <x-search-form placeholder="TRX No." btn="btn--base" />
                <a class="btn btn--base" href="{{ route('user.deposit.index') }}">
                    <i class="las la-plus"></i> @lang('Deposit Now')
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('TRX No.')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Charge')</th>
                            <th>@lang('After Charge')</th>
                            <th>@lang('Initiated At')</th>
                            <th>@lang('Method')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                            <tr>
                                <td>#{{ $deposit->trx }}</td>

                                <td>{{ showAmount($deposit->amount) }}</td>

                                <td>{{ showAmount($deposit->charge) }}</td>

                                <td>{{ showAmount($deposit->amount + $deposit->charge) }}</td>

                                <td><em>{{ showDateTime($deposit->created_at) }}</em></td>

                                <td>
                                    @if ($deposit->branch)
                                        <span class="text-primary" title="@lang('Branch Name')">{{ __(@$deposit->branch->name) }}</span>
                                    @else
                                        <span class="text-primary" title="@lang('Gateway Name')">{{ __(@$deposit->gateway->name) }}</span>
                                    @endif
                                </td>

                                <td>@php echo $deposit->statusBadge @endphp</td>

                                @php
                                    $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                                @endphp

                                <td>
                                    <a href="{{ route('user.deposit.details', $deposit->trx) }}" class="btn btn--sm btn-outline--base"><i class="la la-desktop"></i> @lang('Details')</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($deposits->hasPages())
            <div class="card-footer">
                {{ $deposits->links() }}
            </div>
        @endif
    </div>
@endsection
