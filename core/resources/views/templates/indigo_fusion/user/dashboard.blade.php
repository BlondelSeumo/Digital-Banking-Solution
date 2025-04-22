@extends($activeTemplate . 'layouts.master')
@php
    $kyc = getContent('kyc_content.content', true);
@endphp
@section('content')
    <div class="row justify-content-center gy-4">
        @if ($user->kv != Status::KYC_VERIFIED)
            <div class="col-lg-12">
                @php
                    $kyc = getContent('kyc.content', true);
                @endphp
                @if ($user->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
                    <div class="card-widget section--bg2" role="alert">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text--danger">@lang('KYC Documents Rejected')</h4>
                            <button class="btn btn--base btn-sm" data-bs-toggle="modal" data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
                        </div>
                        <hr>
                        <p class="text-white mb-2">{{ __(@$kyc->data_values->reject) }}</p>

                        <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Re-submit Documents')</a>
                        <br>
                        <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                    </div>
                @elseif(auth()->user()->kv == Status::KYC_UNVERIFIED)
                    <div class="card-widget section--bg2" role="alert">
                        <h4 class="text--base">@lang('KYC Verification required')</h4>
                        <hr>
                        <p class="mb-0 text-white">{{ __(@$kyc->data_values->required) }} <a href="{{ route('user.kyc.form') }}" class="text--base">@lang('Click Here to Verify')</a></p>
                    </div>
                @elseif(auth()->user()->kv == Status::KYC_PENDING)
                    <div class="card-widget section--bg2" role="alert">
                        <h4 class="text--base">@lang('KYC Verification pending')</h4>
                        <hr>
                        <p class="mb-0 text-white">{{ __(@$kyc->data_values->pending) }} <a href="{{ route('user.kyc.data') }}" class="text--base">@lang('See KYC Data')</a></p>
                    </div>
                @endif
            </div>
        @endif

        <div class="col-lg-6">
            <div class="card-widget section--bg2 text-center bg_img" style="background-image: url(' {{ asset($activeTemplateTrue . 'images/elements/card-bg.png') }} ');">
                <span class="caption text-white mb-3">@lang('Account Number')</span>
                <h3 class="d-number text-white">{{ $user->account_number }}</h3>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-widget section--bg2 text-center bg_img" style="background-image: url(' {{ asset($activeTemplateTrue . 'images/elements/card-bg.png') }} ');">
                <span class="caption text-white mb-3">@lang('Available Balance')</span>
                <h3 class="d-number text-white">{{ showAmount($user->balance) }}</h3>
            </div>
        </div>

        @if (@gs()->modules->deposit)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.deposit.history') }}" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue . 'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                            <h3 class="d-number text-white">
                                {{ showAmount(@$widget['total_deposit']) }}
                            </h3>
                            <span class="caption text-white">@lang('Pending Deposits')</span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                            <i class="las la-wallet"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (@gs()->modules->withdraw)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.withdraw.history') }}" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue . 'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                            <h3 class="d-number text-white">{{ showAmount(@$widget['total_withdraw']) }}</h3>
                            <span class="caption text-white">@lang('Pending Withdrawals')</span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                            <i class="las la-money-check"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('user.transaction.history') }}" class="w-100 h-100">
                <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue . 'images/elements/card-bg.png') }} ');">
                    <div class="d-widget__content">
                        <h3 class="d-number text-white">{{ @$widget['total_trx'] }}</h3>
                        <span class="caption text-white">@lang('Today Transactions')</span>
                    </div>
                    <div class="d-widget__icon border-radius--100">
                        <i class="las la-exchange-alt"></i>
                    </div>
                </div>
            </a>
        </div>
        @if (gs()->modules->fdr)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.fdr.list') }}" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue . 'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                            <h3 class="d-number text-white">{{ @$widget['total_fdr'] }}</h3>
                            <span class="caption text-white">@lang('Running FDR')</span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                            <i class="las la-money-bill"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (gs()->modules->dps)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.dps.list') }}" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url('{{ asset($activeTemplateTrue . 'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                            <h3 class="d-number text-white">{{ @$widget['total_dps'] }}</h3>
                            <span class="caption text-white">@lang('Running DPS')</span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                            <i class="las la-box-open"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (gs()->modules->loan)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.loan.list') }}" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url('{{ asset($activeTemplateTrue . 'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                            <h3 class="d-number text-white">{{ @$widget['total_loan'] }}</h3>
                            <span class="caption text-white">@lang('Running Loan')</span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                            <i class="las la-hand-holding-usd"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>

    @if (gs()->modules->referral_system)
        <div class="row gy-4 mt-3">
            <div class="col-12">
                <div class="d-widget d-flex flex-wrap align-items-center rounded-3">
                    <label for="lastname" class="col-form-label">@lang('My Referral Link'):</label>
                    <div class="input-group">
                        <input type="url" id="ref" value="{{ route('home') . '?reference=' . auth()->user()->username }}" class="form--control bg-transparent" readonly>
                        <button type="button" class="input-group-text bg--base copyBtn border-0 text-white"><i class="fa fa-copy"></i> &nbsp; @lang('Copy')</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row gy-4 mt-3">
        <div class="col-lg-6">
            <h4 class="mb-3">@lang('Latest Credits')</h3>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table mb-0">
                                <thead>
                                    <tr>
                                        <th>@lang('TRX')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Time')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($credits as $credit)
                                        <tr>
                                            <td>#{{ $credit->trx }}</td>
                                            <td class="fw-bold">{{ showAmount($credit->amount) }}</td>
                                            <td>{{ showDateTime($credit->created_at, 'd M, Y h:i A') }}</td>
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
        <div class="col-lg-6">
            <h4 class="mb-3">@lang('Latest Debits')</h3>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table mb-0">
                                <thead>
                                    <tr>
                                        <th>@lang('Trx')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Time')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($debits as $debit)
                                        <tr>
                                            <td>#{{ $debit->trx }}</td>
                                            <td class="fw-bold">{{ showAmount($debit->amount) }}</td>
                                            <td>{{ showDateTime($debit->created_at, 'd M, Y h:i A') }}</td>
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
            $('.copyBtn').on('click', function() {
                var copyText = $(this).siblings('#ref')[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                copyText.blur();
                $(this).addClass('copied');
                setTimeout(() => {
                    $(this).removeClass('copied');
                }, 1500);
            });
        })(jQuery);
    </script>
@endpush
