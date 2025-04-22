@extends('pdf.layouts.master')

@section('main-content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>@lang('TRX')</th>
                <th>@lang('Account')</th>
                <th>@lang('Bank')</th>
                <th>@lang('Amount')</th>
                <th>@lang('Charge')</th>
                <th>@lang('Paid Amount')</th>
                <th>@lang('Status')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transfers as $transfer)
                <tr>
                    <td>
                        <span class="fw-bold">{{ $transfer->trx }}</span>
                        <br>
                        <small>{{ showDateTime($transfer->created_at, 'd M, Y h:i A') }}</small>
                    </td>

                    <td>
                        @if ($transfer->beneficiary)
                            <span class="fw-bold text--primary">{{ $transfer->beneficiary->short_name }}</span>
                            <br>
                            {{ @$transfer->beneficiary->account_number }}
                        @else
                            <span class="text--primary fw-bold">{{ $transfer->wireTransferAccountName() }}</span>
                            <br>
                            {{ $transfer->wireTransferAccountNumber() }}
                        @endif
                    </td>

                    <td>
                        @if ($transfer->beneficiary)
                            {{ $transfer->beneficiary->beneficiaryOf->name ?? gs()->site_name }}
                        @else
                            <span class="text--warning fw-bold">@lang('Wire Transfer')</span>
                        @endif
                    </td>

                    <td>{{ showAmount($transfer->amount) }}</td>

                    <td>{{ showAmount($transfer->charge) }}</td>

                    <td>{{ showAmount($transfer->final_amount) }}</td>

                    <td>
                        @if ($transfer->status == 1)
                            <span class="badge badge--success">@lang('Completed')</span>
                        @elseif($transfer->status == 0)
                            <span class="badge badge--warning">@lang('Pending')</span>
                        @elseif($transfer->status == 2)
                            <span class="badge badge--danger">@lang('Rejected')</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
