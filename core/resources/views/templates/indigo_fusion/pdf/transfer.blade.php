@extends('pdf.layouts.master')

@section('main-content')
    <div class="pdf-card">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-12">
                    <div class="custom--card">
                        <div class="card-body">
                            <h5 class="text-center">
                                @lang('Transfer Information') @if (@$otherBank)
                                    @lang('of Other Bank')
                                @elseif($transfer->wire_transfer_data)
                                    @lang('Via Wire')
                                @endif
                            </h5>

                            <ul class="caption-list-two mt-3">
                                <li>
                                    <span class="caption">@lang('Sender Account Number')</span>
                                    <span class="value">{{ $transfer->user->account_number }}</span>
                                </li>
                                @if ($transfer->wire_transfer_data)
                                    <li>
                                        <span class="caption">@lang('Recipient Account Number')</span>
                                        <span class="value">{{ $transfer->wireTransferAccountNumber() }}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Recipient Name')</span>
                                        <span class="value">{{ $transfer->wireTransferAccountName() }}</span>
                                    </li>
                                @else
                                    <li>
                                        <span class="caption">@lang('Recipient Account Number')</span>
                                        <span class="value">{{ $transfer->beneficiary->account_number }}</span>
                                    </li>
                                @endif

                                @if ($otherBank)
                                    <li>
                                        <span class="caption">@lang('Recipient Bank Name')</span>
                                        <span class="value">{{ __($transfer->beneficiary->beneficiaryOf->name) }} </span>
                                    </li>
                                @endif

                                <li>
                                    <span class="caption">@lang('Transfer Amount')</span>
                                    <span class="value">{{showAmount($transfer->amount) }} </span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Transfer Date')</span>
                                    <span class="value">{{ showDateTime($transfer->created_at) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    body {
        background-color: #00a6f712;
    }
</style>
