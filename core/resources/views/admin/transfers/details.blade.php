@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-5 col-md-6 mb-30">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <h5 class="card-title">@lang('Sender\'s Information')</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-3">
                            <span class="fw-bold">@lang('Username')</span>
                            <span class="text--primary fw-bold">

                                @can('admin.users.detail')
                                @<a class="text--primary" href="{{ route('admin.users.detail', $transfer->user_id) }}" target="_blank">{{ $transfer->user->username }}</a>
                                @else
                                    {{ $transfer->user->username }}
                                @endcan
                            </span>
                        </li>

                        <li class="list-group-item px-3">
                            <span class="fw-bold"> @lang('Account Number')</span>
                            <span>{{ __(@$transfer->user->account_number) }}</span>
                        </li>

                        <li class="list-group-item px-3">
                            <span class="fw-bold"> @lang('Account Name')</span>
                            <span>{{ __(@$transfer->user->fullname) }}</span>
                        </li>

                        <li class="list-group-item px-3">
                            <span class="fw-bold">@lang('Amount')</span>
                            <span class="fw-bold">{{ showAmount($transfer->amount) }}</span>
                        </li>

                        <li class="list-group-item px-3">
                            <span class="fw-bold">@lang('Charge')</span>
                            <span class="text--danger"> {{ showAmount($transfer->charge) }}</span>
                        </li>

                        <li class="list-group-item px-3">
                            <span class="fw-bold"> @lang('Including Charge')</span>
                            <span class="fw-bold">{{ showAmount($transfer->final_amount) }} </span>
                        </li>

                        <li class="list-group-item px-3">
                            <span class="fw-bold">@lang('Send at')</span>
                            <span>{{ showDateTime($transfer->user->created_at, 'd M, Y h:i A') }}</span>
                        </li>

                        <li class="list-group-item px-3">
                            <span class="fw-bold">@lang('TRX No.')</span>
                            <span>#{{ $transfer->trx }}</span>
                        </li>

                        <li class="list-group-item px-3">
                            <span class="fw-bold">@lang('Status')</span>
                            @php echo $transfer->statusBadge @endphp
                        </li>

                        @if ($transfer->reject_reason)
                            <li class="list-group-item px-3">
                                <span class="fw-bold">@lang('Reject Reason')</span>
                                <span class="text--danger fw-bold">{{ __(@$transfer->reject_reason) }} </span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-7 col-md-6 mb-30">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> @lang('Receiver\'s Information')</h5>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <h3 class="text--success fw-bold">{{ showAmount($transfer->amount) }}</h3>
                        <small class="text-muted">@lang('Amount')</small>
                    </div>

                    @if ($transfer->beneficiary)
                        @php
                            $bank = $transfer->beneficiary->beneficiaryOf;
                            $bankName = $bank->name ?? gs()->site_name;
                        @endphp
                        <div class="mb-3">
                            <h6 class="text--info fw-bold">{{ __($bankName) }}</h6>
                            <small class="text-muted">@lang('Bank Name')</small>
                        </div>

                        <x-view-form-data :data="$transfer->beneficiary->details" />
                    @else
                        <x-view-form-data :data="@$transfer->wire_transfer_data" />
                    @endif

                </div>
                @if ($transfer->status == 0 && (can('admin.transfers.reject') || can('admin.transfers.complete')))
                    <div class="card-footer d-flex flex-wrap gap-2 p-3">

                        @can('admin.transfers.complete')
                            <button class="btn btn-outline--success confirmationBtn" data-action="{{ route('admin.transfers.complete', $transfer->id) }}" data-question="@lang('Are you sure to complete this transfer?')">
                                <i class="las la-check"></i>
                                @lang('Complete')
                            </button>
                        @endcan

                        @can('admin.transfers.reject')
                            <button class="btn btn-outline--danger rejectBtn" data-id="{{ $transfer->id }}" data-bs-toggle="modal" data-bs-target="#rejectModal" type="button">
                                <i class="fas fa-ban"></i>
                                @lang('Reject')
                            </button>
                        @endcan


                    </div>
                @endif
            </div>
        </div>
    </div>

    @can('admin.transfers.reject')
        <div class="modal fade" id="rejectModal" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                        <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.transfers.reject') }}" method="POST">
                        @csrf
                        <input name="id" type="hidden">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="fw-bold mt-2">@lang('Reason of Rejection')</label>
                                <textarea class="form-control" name="reject_reason" maxlength="255" rows="5" required>{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.find('input[name=id]').val($(this).data('id'));
            });
        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .list-group-item {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            border: 1px solid rgba(0, 0, 0, 0.068);
            padding: 10px 5px;
        }
    </style>
@endpush
