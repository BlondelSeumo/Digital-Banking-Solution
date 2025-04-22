@extends($activeTemplate . 'user.dps.layout')
@section('dps-content')
    <div class="card custom--card overflow-hidden">
        <div class="card-header">
            <div class="header-nav mb-0">
                <x-search-form placeholder="DPS No." dateSearch='yes' btn="btn--base" />
                @if (request()->date || request()->search)
                    <a class="btn btn-outline--info" href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}"><i class="la la-download"></i> @lang('Download PDF')</a>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('DPS No.')</th>
                            <th>@lang('Rate')</th>
                            <th>@lang('Per Installment')</th>
                            <th>@lang('Total')</th>
                            <th>@lang('Given')</th>
                            <th>@lang('Next Installment')</th>
                            <th>@lang('DPS Amount')</th>
                            <th>@lang('Maturity Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allDps as $dps)
                            <tr>
                                <td>
                                    #{{ $dps->dps_number }}
                                </td>

                                <td>{{ getAmount($dps->interest_rate) }}%</td>

                                <td>{{ showAmount($dps->per_installment) }} /{{ $dps->installment_interval }} {{ __(Str::plural('Day', $dps->installment_interval)) }}</td>

                                <td>{{ $dps->total_installment }}</td>
                                <td>{{ $dps->given_installment }}</td>
                                <td>{{ showDateTime(@$dps->nextInstallment->installment_date, 'd M, Y') }}</td>
                                <td>{{ showAmount($dps->depositedAmount()) }}</td>
                                <td>{{ showAmount($dps->depositedAmount() + $dps->profitAmount()) }}</td>
                                <td>@php echo $dps->statusBadge; @endphp</td>

                                <td>
                                    <div class="dropdown">
                                        <button aria-expanded="false" class="btn btn--sm btn--base" data-bs-toggle="dropdown" type="button">
                                            <i class="las la-ellipsis-v m-0"></i>
                                        </button>
                                        <div class="dropdown-menu">

                                            <a href="{{ route('user.dps.details', $dps->dps_number) }}" class="dropdown-item">
                                                <i class="las la-list"></i> @lang('Details')
                                            </a>

                                            <a class="dropdown-item" href="{{ route('user.dps.instalment.logs', $dps->dps_number) }}">
                                                <i class="las la-wallet"></i> @lang('Installments')
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        @if ($allDps->hasPages())
            <div class="card-footer">
                {{ paginateLinks($allDps) }}
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.withdrawBtn').on('click', function() {
                let modal = $('#wihtdrawModal');
                let data = $(this).data();
                $.each(data, function(i, value) {
                    $(`.${i}`).text(value);
                });
                let form = modal.find('form')[0];
                form.action = `{{ route('user.dps.withdraw', '') }}/${$(this).data('id')}`
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('modal')
    <div class="modal fade custom--modal" id="wihtdrawModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">@lang('Withdrawal Preview')</h6>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close"><i class="las la-times"></i></span>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                @lang('DPS Number')
                                <span class="dps_number"></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                @lang('Per Installment')
                                <span class="per_installment">14</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                @lang('Total Installment')
                                <span class="total_installment">14</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                @lang('Total Deposited')
                                <span class="total_deposited">2</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                @lang('Interest Rate')
                                <span class="interest_rate">2</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                @lang('Profit Amount')
                                <span class="profit_amount">2</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                @lang('Installment Delay Charge')
                                <span class="delay_charge">2</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                @lang('Withdrawable Amount')
                                <span class="withdrawable_amount">1</span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn--dark" data-bs-dismiss="modal" type="button">@lang('Cancel')</button>
                        <button class="btn btn-sm btn--base" type="submit">@lang('Withdraw')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
