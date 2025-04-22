@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 justify-content-center">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">
                        @if ($deposit->branch)
                            @lang('Deposited from') {{ __(@$deposit->branch->name) }}
                        @else
                            @lang('Deposit Via') @if ($deposit->method_code < 5000)
                                {{ __(@$deposit->gateway->name) }}
                            @else
                                @lang('Google Pay')
                            @endif
                        @endif
                    </h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Date')
                            <span class="fw-bold">{{ showDateTime($deposit->created_at) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Transaction Number')
                            <span class="fw-bold">{{ $deposit->trx }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')

                            <span class="fw-bold">
                                @can('admin.users.detail')
                                    <a href="{{ route('admin.users.detail', $deposit->user_id) }}">{{ @$deposit->user->username }}</a>
                                @else
                                    {{ @$deposit->user->username }}
                                @endcan
                            </span>
                        </li>

                        @if ($deposit->branch)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Branch')
                                @can('admin.branch.details')
                                    <a href="{{ route('admin.branch.details', $deposit->branch_id) }}" class="fw-bold text--primary">{{ __(@$deposit->branch->name) }}</a>
                                @else
                                    {{ __(@$deposit->branch->name) }}
                                @endcan
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Deposited By')
                                @can('admin.branch.staff.details')
                                    <a href="{{ route('admin.branch.staff.details', $deposit->branch_staff_id) }}" class="fw-bold text--primary">{{ __(@$deposit->branchStaff->name) }}</a>
                                @else
                                    {{ __(@$deposit->branchStaff->name) }}
                                @endcan
                            </li>
                        @else
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Method')
                                <span class="fw-bold">
                                    @if ($deposit->method_code < 5000)
                                        {{ __(@$deposit->gateway->name) }}
                                    @else
                                        @lang('Google Pay')
                                    @endif
                                </span>
                            </li>
                        @endif

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Amount')
                            <span class="fw-bold">{{ showAmount($deposit->amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Charge')
                            <span class="fw-bold">{{ showAmount($deposit->charge) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('After Charge')
                            <span class="fw-bold">{{ showAmount($deposit->amount + $deposit->charge) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Rate')
                            <span class="fw-bold">1 {{ __(gs('cur_text')) }}
                                = {{ showAmount($deposit->rate, currencyFormat: false) }} {{ __($deposit->baseCurrency()) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('After Rate Conversion')
                            <span class="fw-bold">{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @php echo $deposit->statusBadge @endphp
                        </li>
                        @if ($deposit->admin_feedback)
                            <li class="list-group-item">
                                <strong>@lang('Admin Response')</strong>
                                <br>
                                <p>{{ __($deposit->admin_feedback) }}</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @if ($details || $deposit->status == Status::PAYMENT_PENDING)
            <div class="col-xl-8 col-md-6 mb-30">
                <div class="card overflow-hidden box--shadow1">
                    <div class="card-body">
                        <h5 class="card-title border-bottom pb-2">@lang('User Deposit Information')</h5>
                        @if ($details != null)
                            @foreach (json_decode($details) as $val)
                                @if ($deposit->method_code >= 1000)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h6>{{ __($val->name) }}</h6>
                                            @if ($val->type == 'checkbox')
                                                {{ implode(',', $val->value) }}
                                            @elseif($val->type == 'file')
                                                @if ($val->value)
                                                    <a href="{{ route('admin.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}" class="me-3"><i class="fa-regular fa-file"></i> @lang('Attachment') </a>
                                                @else
                                                    @lang('No File')
                                                @endif
                                            @else
                                                <p>{{ __($val->value) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if ($deposit->method_code < 1000)
                                @include('admin.deposit.gateway_data', ['details' => json_decode($details)])
                            @endif
                        @endif
                        @if ($deposit->status == Status::PAYMENT_PENDING)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    @can('admin.deposit.approve')
                                        <button class="btn btn-outline--success btn-sm ms-1 confirmationBtn" data-action="{{ route('admin.deposit.approve', $deposit->id) }}" data-question="@lang('Are you sure to approve this transaction?')"><i class="las la-check"></i>
                                            @lang('Approve')
                                        </button>
                                    @endcan

                                    @can('admin.deposit.reject')
                                        <button class="btn btn-outline--danger btn-sm ms-1 rejectBtn" data-id="{{ $deposit->id }}" data-info="{{ $details }}" data-amount="{{ showAmount($deposit->amount) }}" data-username="{{ @$deposit->user->username }}"><i class="las la-ban"></i> @lang('Reject')
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    @can('admin.deposit.reject')
        {{-- REJECT MODAL --}}
        <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Reject Deposit Confirmation')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.deposit.reject') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="modal-body">
                            <p>@lang('Are you sure to') <span class="fw-bold">@lang('reject')</span> <span class="fw-bold withdraw-amount text--success"></span> @lang('deposit of') <span class="fw-bold withdraw-user"></span>?</p>

                            <div class="form-group">
                                <label class="mt-2">@lang('Reason for Rejection')</label>
                                <textarea name="message" maxlength="255" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <x-confirmation-modal />
@endsection

@can('admin.deposit.reject')
    @push('script')
        <script>
            (function($) {
                "use strict";

                $('.rejectBtn').on('click', function() {
                    var modal = $('#rejectModal');
                    modal.find('input[name=id]').val($(this).data('id'));
                    modal.find('.withdraw-amount').text($(this).data('amount'));
                    modal.find('.withdraw-user').text($(this).data('username'));
                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush
@endcan
