@extends($activeTemplate . 'user.transfer.layout')
@section('transfer-content')

<div class="card custom--card overflow-hidden">
    <div class="card-header">
        <div class="header-nav mb-0">
            <x-search-form placeholder="TRX No." dateSearch='yes' btn="btn--base" />
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
                        <th>@lang('TRX No.')</th>
                        <th>@lang('Time')</th>
                        <th>@lang('Recipient')</th>
                        <th>@lang('Account No.')</th>
                        <th>@lang('Bank')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Paid Amount')</th>
                        <th>@lang('Status')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transfers as $transfer)
                        <tr>
                            <td>
                                <span class="text--dark fw-bold">#{{ $transfer->trx }}</span>
                            </td>

                            <td>
                                <em>{{ showDateTime($transfer->created_at, 'd M, Y h:i A') }}</em>
                            </td>

                            <td>
                                @if ($transfer->beneficiary)
                                    <span class="text--base fw-bold">{{ $transfer->beneficiary->short_name }}</span>
                                @else
                                    <span class="text--base fw-bold">{{ $transfer->wireTransferAccountName() }}</span>
                                @endif
                            </td>

                            <td>
                                @if ($transfer->beneficiary)
                                    {{ @$transfer->beneficiary->account_number }}
                                @else
                                    {{ $transfer->wireTransferAccountNumber() }}
                                @endif
                            </td>

                            <td>
                                @if ($transfer->beneficiary)
                                    {{ $transfer->beneficiary->beneficiaryOf->name ?? gs()->site_name }}
                                @else
                                    <span class="text--warning fw-bold">@lang('Wire Transfer')</span>
                                    <br>
                                    <button class="badge badge--info wire-transfer" data-id="{{ $transfer->id }}" type="button"> <i class="la la-eye"></i> @lang('Recipient Info')</button>
                                @endif
                            </td>

                            <td>{{ showAmount($transfer->amount) }}</td>

                            <td>{{ showAmount($transfer->charge) }}</td>

                            <td>{{ showAmount($transfer->final_amount) }}</td>

                            <td>
                                @if ($transfer->status == 1)
                                    <span class="badge badge--success">@lang('Completed')</span>
                                @elseif($transfer->status == 0)
                                    <span class="badge badge--warning">@lang('Pending')</span>
                                @elseif($transfer->status == 2)
                                    <span class="badge badge--danger">@lang('Rejected')</span>
                                @endif
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
    @if ($transfers->hasPages())
        <div class="card-footer">
            {{ paginateLinks($transfers) }}
        </div>
    @endif
</div>


@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.wire-transfer').on('click', function(e) {
                let id = $(this).data('id');
                let modal = $('#detailsModal');
                modal.find('.loading').removeClass('d-none');
                let action = `{{ route('user.transfer.wire.details', ':id') }}`;

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
        })(jQuery);
    </script>
@endpush

@push('modal')
    <div class="modal fade custom--modal" id="detailsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Wire Transfer Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-ajax-loader />
                </div>
            </div>
        </div>
    </div>
@endpush
