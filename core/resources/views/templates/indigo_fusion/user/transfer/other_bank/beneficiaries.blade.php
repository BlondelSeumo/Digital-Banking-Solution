@extends($activeTemplate . 'layouts.master')
@section('content')
    @if (gs()->modules->other_bank)
        <div class="d-flex flex-wrap justify-content-end mb-3">
            <a class="btn btn-sm btn--dark" href="{{ route('user.beneficiary.other') }}"> <i class="la la-users"></i> @lang('Manage Beneficiaries')</a>
        </div>
    @endif

    <div class="table-responsive--md">
        <table class="custom--table table">
            <thead>
                <tr>
                    <th>@lang('Name')</th>
                    <th>@lang('Account Name')</th>
                    <th>@lang('Account Number')</th>
                    <th>@lang('Bank')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($beneficiaries as $beneficiary)
                    @php
                        $bank = $beneficiary->beneficiaryOf;
                    @endphp
                    <tr>
                        <td>{{ $beneficiary->short_name }}</td>
                        <td>{{ $beneficiary->account_name }}</td>
                        <td>{{ $beneficiary->account_number }}</td>
                        <td>{{ $bank->name }}</td>
                        <td>
                            <span title="@lang('Details')">
                                <button class="btn btn-sm btn-outline--base seeDetails" data-id="{{ $beneficiary->id }}"><i class="la la-desktop"></i> @lang('Details')</button>
                            </span>
                            <span title="@lang('Transfer Money')">
                                <button class="btn btn-sm btn-outline--success sendBtn" data-name="{{ $beneficiary->short_name }}" data-processing_time="{{ $bank->processing_time }}" data-transfer_charge="{{ $bank->charge_text }}" data-bank_name="{{ $bank->name }}" data-id="{{ $beneficiary->id }}" data-minimum_amount="{{ showAmount($bank->minimum_limit) }}" data-maximum_amount="{{ showAmount($bank->maximum_limit) }}" data-daily_limit="{{ showAmount($bank->daily_maximum_limit) }}" data-monthly_limit="{{ showAmount($bank->monthly_maximum_limit) }}" data-daily_count="{{ $bank->daily_total_transaction }}" data-monthly_count="{{ $bank->monthly_total_transaction }}" type="button">
                                    <i class="las la-hand-holding-usd"></i> @lang('Transfer')
                                </button>
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="100%">@lang($emptyMessage)</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($beneficiaries->hasPages())
        <div class="mt-3">
            {{ paginateLinks($beneficiaries) }}
        </div>
    @endif
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.sendBtn').on('click', function() {
                let modal = $('#sendModal');
                let data = $(this).data();
                modal.find('.minimum_amount').text(data.minimum_amount);
                modal.find('.maximum_amount').text(data.maximum_amount);
                modal.find('.daily_limit').text(data.daily_limit);
                modal.find('.monthly_limit').text(data.monthly_limit);
                modal.find('.daily_count').text(data.daily_count);
                modal.find('.monthly_count').text(data.monthly_count);
                modal.find('.bank-name').val(data.bank_name);
                modal.find('.short-name').val(data.name);
                modal.find('.processing_time').text(data.processing_time);
                if (data.transfer_charge) {
                    modal.find('.transfer_charge').html(`<small class="text--danger">* @lang('Charge'): ${data.transfer_charge}</small>`);
                }
                modal.find('form')[0].action = `{{ route('user.transfer.other.bank.request', '') }}/${data.id}`;
                modal.modal('show');
            });

            $('.seeDetails').on('click', function() {
                let modal = $('#detailsModal');
                modal.find('.loading').removeClass('d-none');
                let action = `{{ route('user.beneficiary.details', ':id') }}`;
                let id = $(this).attr('data-id');
                $.ajax({
                    url: action.replace(':id', id),
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response.success) {
                            modal.find('.loading').addClass('d-none');
                            modal.find('.modal-body').html(response.html);
                            modal.modal('show');
                        } else {
                            notify('error', response.message || `@lang('Something went the wrong')`)
                        }
                    },
                    error: function(e) {
                        notify(`@lang('Something went the wrong')`)
                    }
                });
            });

        })(jQuery)
    </script>
@endpush

@push('modal')
    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Benficiary Details')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <x-ajax-loader />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendModal">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Transfer Money')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xl-5 mb-3">

                                <h6 class="mb-2 text-center">@lang('Transfer Limit')</h6>
                                <ul class="caption-list-two my-3 p-0">
                                    <li>
                                        <span class="caption">@lang('Minimum Per Transaction')</span>
                                        <span class="value minimum_amount"></span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Maximum Per Transaction')</span>
                                        <span class="value maximum_amount"></span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Daily Maximum')</span>
                                        <span class="value daily_limit"></span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Monthly Maximum')</span>
                                        <span class="value monthly_limit"></span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Daily Maximum Transaction')</span>
                                        <span class="value daily_count"></span>
                                    </li>
                                    <li>
                                        <span class="caption"> @lang('Monthly Maximum Transaction')</span>
                                        <span class="value monthly_count"></span>
                                    </li>
                                </ul>

                                <small class="text--danger">* @lang('Processing Time'): <span class="processing_time"></span></small>
                                <div class="transfer_charge"></div>

                            </div>

                            <div class="col-xl-7">

                                <div class="form-group">
                                    <label class="required fw-bold">@lang('Bank')</label>
                                    <input class="bank-name form--control" class="form--control" type="text" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="required fw-bold">@lang('Recipient')</label>
                                    <input class="short-name form--control" class="form--control" type="text" readonly>
                                </div>

                                <div class="form-group">
                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                        <label class="required fw-bold">@lang('Amount')</label>
                                        <span class="fw-bold">@lang('Current Balance'): {{ showAmount(auth()->user()->balance) }}</span>
                                    </div>

                                    <div class="input-group">
                                        <input class="form--control" name="amount" type="number" step="any" placeholder="@lang('Enter an Amount')" required>
                                        <span class="input-group-text">@lang(gs()->cur_text)</span>
                                    </div>
                                </div>
                                @include($activeTemplate . 'partials.otp_field')
                                <button class="btn w-100 btn--base" type="submit">@lang('Submit')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

<x-transfer-bottom-menu />

@push('style')
    <style>
        hr {
            height: 1px;
            background-color: #dee2e6;
            opacity: 0.8;
        }
    </style>
@endpush
