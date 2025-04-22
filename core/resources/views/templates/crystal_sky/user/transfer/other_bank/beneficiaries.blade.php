@extends($activeTemplate . 'user.transfer.layout')
@section('transfer-content')
    <div class="card custom--card overflow-hidden">
        @if (gs()->modules->other_bank)
            <div class="card-header">
                <div class="header-nav mb-0">
                    <a class="btn btn-sm btn--dark" href="{{ route('user.beneficiary.other') }}"> <i class="la la-users"></i> @lang('Manage Beneficiaries')</a>
                </div>
            </div>
        @endif

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md">
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

                                    <div class="d-flex gap-2 justify-content-end">
                                        <button class="btn btn--sm btn-outline--base seeDetails" data-id="{{ $beneficiary->id }}"><i class="la la-desktop"></i> @lang('Details')</button>

                                        <button class="btn btn--sm btn-outline--success sendBtn" data-name="{{ $beneficiary->short_name }}" data-processing_time="{{ $bank->processing_time }}" data-transfer_charge="{{ $bank->charge_text }}" data-bank_name="{{ $bank->name }}" data-id="{{ $beneficiary->id }}" data-minimum_amount="{{ showAmount($bank->minimum_limit) }}" data-maximum_amount="{{ showAmount($bank->maximum_limit) }}" data-daily_limit="{{ showAmount($bank->daily_maximum_limit) }}" data-monthly_limit="{{ showAmount($bank->monthly_maximum_limit) }}" data-daily_count="{{ $bank->daily_total_transaction }}" data-monthly_count="{{ $bank->monthly_total_transaction }}" type="button">
                                            <i class="las la-hand-holding-usd"></i> @lang('Transfer')
                                        </button>
                                    </div>

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
        </div>

        @if ($beneficiaries->hasPages())
            <div class="card-footer">
                {{ paginateLinks($beneficiaries) }}
            </div>
        @endif
    </div>
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

    <div class="modal fade custom--modal" id="sendModal">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Transfer Money')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row  gx-5">
                            <div class="col-xl-5 mb-3">

                                <h6 class="mb-2 text-center">@lang('Transfer Limit')</h6>
                                <hr>
                                <ul class="caption-list-two my-3 p-0">
                                    <li class="pricing-card__list flex-between">
                                        <span class="fw-bold">@lang('Minimum Per Transaction')</span>
                                        <span class="minimum_amount"></span>
                                    </li>
                                    <li class="pricing-card__list flex-between">
                                        <span class="fw-bold">@lang('Maximum Per Transaction')</span>
                                        <span class="maximum_amount"></span>
                                    </li>
                                    <li class="pricing-card__list flex-between">
                                        <span class="fw-bold">@lang('Daily Maximum')</span>
                                        <span class="daily_limit"></span>
                                    </li>
                                    <li class="pricing-card__list flex-between">
                                        <span class="fw-bold">@lang('Monthly Maximum')</span>
                                        <span class="monthly_limit"></span>
                                    </li>
                                    <li class="pricing-card__list flex-between">
                                        <span class="fw-bold">@lang('Daily Maximum Transaction')</span>
                                        <span class="daily_count"></span>
                                    </li>
                                    <li class="pricing-card__list flex-between">
                                        <span class="fw-bold"> @lang('Monthly Maximum Transaction')</span>
                                        <span class="monthly_count"></span>
                                    </li>
                                </ul>

                                <small class="text--danger">* @lang('Processing Time'): <span class="processing_time"></span></small>
                                <div class="transfer_charge"></div>

                            </div>

                            <div class="col-xl-7">
                                <div class="form-group">
                                    <label class="required form-label">@lang('Bank')</label>
                                    <input class="bank-name form--control" class="form--control" type="text" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="required form-label">@lang('Recipient')</label>
                                    <input class="short-name form--control" class="form--control" type="text" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                        <label class="required form-label">@lang('Amount')</label>
                                        <span class="text--info">@lang('Current Balance'): {{ showAmount(auth()->user()->balance) }}</span>
                                    </div>

                                    <div class="input-group custom-input-group">
                                        <input class="form-control form--control" name="amount" type="number" step="any" placeholder="@lang('Enter an Amount')" required>
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

@push('style')
    <style>
        hr {
            height: 1px;
            background-color: #dee2e6;
            opacity: 0.8;
        }
    </style>
@endpush
