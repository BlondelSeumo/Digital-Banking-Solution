@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-4">
        <div class="col-lg-12">
            <div class="d-flex justify-content-end align-content-center mb-4 gap-2">
                <x-search-form placeholder="Loan No." dateSearch='yes' btn="btn--base" />
                @if (request()->date || request()->search)
                    <a class="btn btn-outline--info" href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}"><i class="la la-download"></i> @lang('Download PDF')</a>
                @endif
            </div>
            <div class="custom--card">
                <div class="card-body p-0">
                    <div class="table-responsive--md">
                        <table class="custom--table table">
                            <thead>
                                <tr>
                                    <th>@lang('Loan No.')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Installment')</th>
                                    <th>@lang('Given')</th>
                                    <th>@lang('Total')</th>
                                    <th>@lang('Next Installment')</th>
                                    <th>@lang('Total Payable')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($loans as $loan)
                                    <tr>

                                        <td>
                                            #{{ $loan->loan_number }}
                                        </td>

                                        <td>{{ getAmount($loan->interestRate()) }}%</td>

                                        <td>
                                            {{ showAmount($loan->amount) }}

                                        </td>

                                        <td>
                                            {{ showAmount($loan->per_installment) }}
                                        </td>

                                        <td>{{ $loan->given_installment }}</td>

                                        <td>{{ $loan->total_installment }}</td>

                                        <td>
                                            @if ($loan->nextInstallment)
                                                {{ showDateTime($loan->nextInstallment->installment_date, 'd M, Y') }}
                                            @else
                                                @lang('N/A')
                                            @endif
                                        </td>

                                        <td>{{ showAmount($loan->payable_amount) }}</td>

                                        <td>
                                            @php echo $loan->statusBadge; @endphp
                                            @if ($loan->status == 3)
                                                <span class="admin-feedback" data-feedback="{{ __($loan->admin_feedback) }}">
                                                    <i class="la la-info-circle"></i>
                                                </span>
                                            @endif
                                        </td>

                                        <td>

                                            <div class="dropdown">
                                                <button aria-expanded="false" class="btn btn-sm btn--light" data-bs-toggle="dropdown" type="button">
                                                    <i class="las la-ellipsis-v m-0"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('user.loan.details', $loan->loan_number) }}" class="dropdown-item">
                                                        <i class="las la-list"></i> @lang('Details')
                                                    </a>

                                                    <a class="dropdown-item @disabled(!$loan->nextInstallment)" href="{{ route('user.loan.instalment.logs', $loan->loan_number) }}">
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
                @if ($loans->hasPages())
                    <div class="card-footer py-2">
                        {{ paginateLinks($loans) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.admin-feedback').on('click', function() {
                var modal = $('#adminFeedback');
                modal.find('.modal-body').text($(this).data('feedback'));
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush

@push('bottom-menu')
    <li><a href="{{ route('user.loan.plans') }}">@lang('Loan Plans')</a></li>
    <li><a class="active" href="{{ route('user.loan.list') }}">@lang('My Loan List')</a></li>
@endpush

@push('modal')
    <!-- Modal -->
    <div class="modal fade" id="adminFeedback">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reason of Rejection')!</h5>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn--dark" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('style')
    <style>
        .btn[type=submit] {
            height: unset !important;
        }

        .btn {
            padding: 12px 1.875rem;
        }
    </style>
@endpush
