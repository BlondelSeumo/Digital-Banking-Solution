@extends($activeTemplate . 'layouts.master')
@section('content')
    @if ($user->kv != Status::KYC_VERIFIED)
        @php
            $kyc = getContent('kyc.content', true);
        @endphp

        @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
            <div class="alert mb-4 alert-danger" role="alert">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text--danger">@lang('KYC Documents Rejected')</h4>
                    <button class="btn btn--dark btn--sm" data-bs-toggle="modal" data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
                </div>
                <hr>
                <p class="mb-2">{{ __(@$kyc->data_values->reject) }} <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Re-submit Documents').</a></p>
                <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
            </div>
        @elseif(auth()->user()->kv == Status::KYC_UNVERIFIED)
            <div class="alert mb-4 alert--danger" role="alert">
                <h4 class="text--primary mb-0">@lang('KYC Verification required')</h4>
                <hr>
                <p>{{ __(@$kyc->data_values->required) }} <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Submit Documents')</a></p>
            </div>
        @elseif(auth()->user()->kv == Status::KYC_PENDING)
            <div class="alert mb-4 alert--warning" role="alert">
                <h4 class="text--warning mb-0">@lang('KYC Verification pending')</h4>
                <hr>
                <p>{{ __(@$kyc->data_values->pending) }} <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
            </div>
        @endif
    @endif

    <div class="row gy-lg-4 gy-md-3 gy-3 align-items-center">
        <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6">
            <a href="{{ route('user.transaction.history') }}" class="d-block">
                <div class="dashboard-widget user-account-card">
                    <div class="card-body">
                        <h5 class="user-account-card__name text--info text-uppercase">{{ $user->username }}</h5>
                        <h6 class="user-account-card__number text--black">{{ $user->account_number }}</h6>
                        <div class="user-account-card__balance text-center pt-2">
                            <span class="user-account-card__text">@lang('Available Balance')</span>
                            <h3 class="user-account-card__amount">{{ showAmount($user->balance) }}</h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @if (gs()->modules->referral_system)
            <div class="col-xl-8 col-lg-12 col-md-8 order-xl-0 order-lg-first order-md-0 order-sm-first">
                <div class="dashboard-widget refer">
                    <div class="custom-border flex-align flex-between">
                        <div class="refer__content">
                            <h5 class="refer__title">@lang('My Referral Link'):</h5>
                            <h5 class="refer__link" id="ref">{{ route('home') . '?reference=' . $user->username }}
                            </h5>
                        </div>
                        <span class="refer__icon dashboard-widget__icon flex-center copy-icon copyBtn">
                            <i class="icon-copy"></i>
                        </span>
                    </div>
                </div>
            </div>
        @endif

        @if (@gs()->modules->deposit)
            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                <a href="{{ route('user.deposit.history') }}?status={{ Status::PAYMENT_PENDING }}" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-wallet"></i>
                            </span>
                            <span class="dashboard-widget__text">@lang('Pending Deposits')</span>
                        </div>
                        <h4 class="dashboard-widget__number">
                            {{ showAmount(@$widget['total_deposit']) }}</h4>
                    </div>
                </a>
            </div>
        @endif

        @if (@gs()->modules->withdraw)
            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                <a href="{{ route('user.withdraw.history') }}?status={{ Status::PAYMENT_PENDING }}" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-money-check"></i>
                            </span>
                            <span class="dashboard-widget__text">@lang('Pending Withdrawals')</span>
                        </div>
                        <h4 class="dashboard-widget__number">
                            {{ showAmount(@$widget['total_withdraw']) }}</h4>
                    </div>
                </a>
            </div>
        @endif

        <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
            <a href="{{ route('user.transaction.history') }}?today=1" class="d-block">
                <div class="dashboard-widget">
                    <div class="dashboard-widget__content flex-align">
                        <span class="dashboard-widget__icon flex-center">
                            <i class="las la-exchange-alt"></i>
                        </span>
                        <span class="dashboard-widget__text">@lang('Today Transactions')</span>
                    </div>
                    <h4 class="dashboard-widget__number">{{ @$widget['total_trx'] }}</h4>
                </div>
            </a>
        </div>

        @if (gs()->modules->fdr)
            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                <a href="{{ route('user.fdr.list') }}?status={{ Status::FDR_RUNNING }}" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-money-bill"></i>
                            </span>
                            <span class="dashboard-widget__text">@lang('Running FDR')</span>
                        </div>
                        <h4 class="dashboard-widget__number">{{ @$widget['total_fdr'] }}</h4>
                    </div>
                </a>
            </div>
        @endif
        @if (gs()->modules->dps)
            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                <a href="{{ route('user.dps.list') }}?status={{ Status::FDR_RUNNING }}" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-box-open"></i>
                            </span>
                            <span class="dashboard-widget__text">@lang('Running DPS')</span>
                        </div>
                        <h4 class="dashboard-widget__number">{{ @$widget['total_dps'] }}</h4>
                    </div>
                </a>
            </div>
        @endif

        @if (gs()->modules->loan)
            <div class="col-xl-4 col-lg-12 col-md-4 col-sm-12 col-xsm-6">
                <a href="{{ route('user.loan.list') }}?status={{ Status::LOAN_RUNNING }}" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-hand-holding-usd"></i>
                            </span>
                            <span class="dashboard-widget__text">@lang('Running Loan')</span>
                        </div>
                        <h4 class="dashboard-widget__number">{{ @$widget['total_loan'] }}</h4>
                    </div>
                </a>
            </div>
        @endif
    </div>

    <div class="pt-60">
        <div class="row gy-4 justify-content-center">
            <div class="col-xxl-6">
                <div class="dashboard-table">
                    <h5 class="dashboard-table__title card-header__title text-dark">
                        @lang('Latest Credits')
                    </h5>
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('TRX No.')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($credits as $credit)
                                <tr>
                                    <td>{{ $credit->trx }}</td>
                                    <td>
                                        {{ showDateTime($credit->created_at, 'd M, Y h:i A') }}
                                    </td>
                                    <td class="fw-bold">
                                        {{ showAmount($credit->amount) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="dashboard-table">
                    <h5 class="dashboard-table__title card-header__title text-dark">
                        @lang('Latest Debits')
                    </h5>
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('TRX No.')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debits as $debit)
                                <tr>
                                    <td>{{ $debit->trx }}</td>
                                    <td>{{ showDateTime($debit->created_at, 'd M, Y h:i A') }}</td>
                                    <td class="fw-bold">
                                        {{ showAmount($debit->amount) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
    @push('modal')
        <div class="modal fade" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ auth()->user()->kyc_rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.copyBtn').click(function() {
                const urlText = $('#ref').text();
                const tempTextArea = $('<textarea>');
                tempTextArea.val(urlText);
                $('body').append(tempTextArea);
                tempTextArea.select();
                document.execCommand('copy');
                tempTextArea.remove();
                notify('success', `Copied - ${urlText}`)
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .user-account-card {
            padding: 15px 16px !important;
            transition: 0.3s;
        }

        .user-account-card__name {
            font-size: 0.875rem;
            font-weight: 400;
            margin-bottom: 5px;
        }

        .user-account-card__number {
            font-size: 0.875rem;
            color: #a0aec0;
            margin-bottom: 0px;
            font-weight: 400;
        }

        .user-account-card__text {
            color: #a0aec0;
            font-size: 0.875rem;
        }

        .user-account-card__amount {
            margin-bottom: 0px;
            /* font-size: 1.25rem; */
        }

        .user-account-card:hover .user-account-card__name,
        .user-account-card:hover .user-account-card__number,
        .user-account-card:hover .user-account-card__text,
        .user-account-card:hover .user-account-card__amount {
            color: #fff !important;
        }

        .dashboard .refer__link {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
@endpush
