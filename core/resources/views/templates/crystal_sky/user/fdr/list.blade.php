@extends($activeTemplate . 'user.fdr.layout')
@section('fdr-content')
    <div class="card custom--card overflow-hidden">
        <div class="card-header">
            <div class="header-nav mb-0">
                <x-search-form placeholder="FDR No." dateSearch='yes' btn="btn--base" />
                @if (request()->date || request()->search)
                    <a class="btn btn-outline--info" href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}"><i class="la la-download"></i> @lang('Download PDF')</a>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table  table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('FDR No.')</th>
                            <th>@lang('Rate')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Installment')</th>
                            <th>@lang('Next Installment')</th>
                            <th>@lang('Lock In Period')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Opened At')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($allFdr as $fdr)
                            <tr>

                                <td>
                                    #{{ $fdr->fdr_number }}
                                </td>

                                <td>{{ getAmount($fdr->interest_rate) }}%</td>

                                <td>
                                    <span class="fw-semibold">{{ showAmount($fdr->amount) }}</span>
                                </td>

                                <td>{{ showAmount($fdr->per_installment) }} /{{ $fdr->installment_interval }} {{ __(Str::plural('Day', $fdr->installment_interval)) }}</td>

                                <td>
                                    @if ($fdr->status != 2)
                                        {{ showDateTime($fdr->next_installment_date, 'd M, Y') }}
                                    @else
                                        @lang('N/A')
                                    @endif
                                </td>

                                <td>
                                    {{ showDateTime($fdr->locked_date->endOfDay(), 'd M, Y') }}
                                </td>

                                <td>@php echo $fdr->statusBadge; @endphp</td>

                                <td>
                                    {{ showDateTime($fdr->created_at->endOfDay(), 'd M, Y h:i A') }}
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button aria-expanded="false" class="btn btn--sm btn--base" data-bs-toggle="dropdown" type="button">
                                            <i class="las la-ellipsis-v m-0"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('user.fdr.details', $fdr->fdr_number) }}" class="dropdown-item">
                                                <i class="las la-list"></i> @lang('Details')
                                            </a>

                                            <a href="{{ route('user.fdr.instalment.logs', $fdr->fdr_number) }}" class="dropdown-item">
                                                <i class="las la-wallet"></i> @lang('Installments')
                                            </a>
                                        </div>
                                    </div>
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
        @if ($allFdr->hasPages())
            <div class="card-footer">
                {{ paginateLinks($allFdr) }}
            </div>
        @endif
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="closeFdr" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Close FDR')</h5>
                    <button type="button" class="bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <form action="" method="post">
                    @csrf
                    <input type="hidden" name="user_token" required>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id" class="transferId" required>
                        </div>
                        <div class="content">
                            <p>@lang('Are you sure to close this FDR?')</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn--danger text-white" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn-md btn--base text-white">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.closeBtn').on('click', function() {
                let modal = $('#closeFdr');
                let form = modal.find('form')[0];
                form.action = `{{ route('user.fdr.close', '') }}/${$(this).data('id')}`
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
