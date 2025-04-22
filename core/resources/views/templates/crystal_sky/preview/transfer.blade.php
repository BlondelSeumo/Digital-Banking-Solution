@extends($activeTemplate . 'user.transfer.layout')
@section('transfer-content')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-12">
            <div class="custom--card">
                <div class="card-body">
                    <h5 class="text-center">
                        @lang('Transfer Information')
                        @if (@$otherBank)
                            @lang('of Other Bank')
                        @elseif($transfer->wire_transfer_data)
                            @lang('Via Wire')
                        @endif
                    </h5>
                    <ul>
                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Sender Account Number')</span>
                            <span>{{ $transfer->user->account_number }}</span>
                        </li>
                        @if ($transfer->wire_transfer_data)
                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Recipient Account Number')</span>
                                <span>{{ $transfer->wireTransferAccountNumber() }}</span>
                            </li>

                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Recipient Name')</span>
                                <span>{{ $transfer->wireTransferAccountName() }}</span>
                            </li>
                        @else
                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Recipient Account Number')</span>
                                <span>{{ $transfer->beneficiary->account_number }}</span>
                            </li>
                        @endif

                        @if ($otherBank)
                            <li class="pricing-card__list flex-between">
                                <span class="fw-bold">@lang('Recipient Bank Name')</span>
                                <span>{{ __($transfer->beneficiary->beneficiaryOf->name) }} </span>
                            </li>
                        @endif

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Transfer Amount')</span>
                            <span>{{showAmount($transfer->amount) }} </span>
                        </li>

                        <li class="pricing-card__list flex-between">
                            <span class="fw-bold">@lang('Transfer Date')</span>
                            <span>{{ showDateTime($transfer->created_at) }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a href="{{ route('user.transfer.details', $transfer->trx) }}?download" type="button" class="btn btn--base btn--sm">
                            <i class="las la-file-download"></i> @lang('Download')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
