@extends($activeTemplate . 'user.transfer.layout')
@section('transfer-content')
    <div class="card custom--card overflow-hidden">
        @if (gs()->modules->own_bank)
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h6 class="card-title mb-0">@lang('Beneficiaries')</h6>

                <div class="header-nav mb-0">
                    <a class="btn btn-sm btn--dark" href="{{ route('user.beneficiary.own') }}"> <i class="la la-users"></i> @lang('Manage Beneficiaries')</a>
                </div>
            </div>
        @endif

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Account No.')</th>
                            <th>@lang('Account Name')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($beneficiaries as $beneficiary)
                            <tr>
                                <td> {{ $beneficiary->short_name }}</td>
                                <td> {{ $beneficiary->account_number }} </td>
                                <td>{{ $beneficiary->account_name }}</td>
                                <td>
                                    <button class="btn btn--sm btn-outline--base sendBtn" data-id="{{ $beneficiary->id }}">
                                        <i class="las la-hand-holding-usd"></i> @lang('Transfer Money')
                                    </button>
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

@push('modal')
    <div class="modal fade custom--modal" id="sendModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Transfer Money')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="form-label required">@lang('Amount')</label>
                            <div class="input-group custom-input-group">
                                <input class="form-control form--control" name="amount" type="text" required>
                                <span class="input-group-text">@lang(gs()->cur_text)</span>
                            </div>
                        </div>

                        @include($activeTemplate . 'partials.otp_field')

                        <div class="my-4">
                            <ul>
                                <li class="pricing-card__list flex-between">
                                    <span class="fw-bold">@lang('Limit Per Transaction')</span>
                                    <span>{{ showAmount(gs()->minimum_transfer_limit) }} (@lang('Min'))</span>
                                </li>

                                <li class="pricing-card__list flex-between">
                                    <span class="fw-bold">@lang('Daily Limit')</span>
                                    <span>{{ showAmount(gs()->daily_transfer_limit) }} (@lang('Max'))</span>
                                </li>

                                <li class="pricing-card__list flex-between">
                                    <span class="fw-bold">@lang('Monthly Limit')</span>
                                    <span>{{ showAmount(gs()->monthly_transfer_limit) }} (@lang('Max'))</span>
                                </li>

                                @php $transferCharge = gs()->transferCharge(); @endphp

                                @if ($transferCharge)
                                    <li class="pricing-card__list flex-between">
                                        <span class="fw-bold">@lang('Charge Per Transaction')</span>
                                        <span class="text--danger"> {{ $transferCharge }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.sendBtn').on('click', function() {
                let modal = $('#sendModal');
                let route = `{{ route('user.transfer.own.bank.request', ':id') }}`;
                modal.find('form')[0].action = route.replace(':id', $(this).data('id'))
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
