@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card viser--table b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Account Name')</th>
                                    <th>@lang('Short Name')</th>
                                    <th>@lang('Account No.')</th>
                                    <th>@lang('Bank')</th>
                                    @can('admin.users.beneficiary.details')
                                        <th>@lang('Action')</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($beneficiaries as $beneficiary)
                                        <tr>
                                            <td>{{ $loop->index + $beneficiaries->firstItem() }}</td>
                                            <td>{{ $beneficiary->account_name }}</td>
                                            <td> {{ $beneficiary->short_name }} </td>
                                            <td>{{ $beneficiary->account_number }} </td>
                                            <td>{{ __($beneficiary->beneficiaryOf->name ?? gs('site_name')) }}
                                            </td>
                                            @can('admin.users.beneficiary.details')
                                                <td>
                                                    <button class="btn btn-sm btn-outline--primary seeDetails" data-id="{{ $beneficiary->id }}" type="button" @disabled($beneficiary->beneficiary_type == App\Models\User::class)>
                                                        <i class="las la-desktop"></i> @lang('Details')
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table><!-- table end -->
                        </div>
                    </div>
                    @if ($beneficiaries->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($beneficiaries) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade" id="detailsModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Beneficiary Details')</h5>
                        <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <x-ajax-loader />
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @can('admin.users.beneficiary.details')
        @push('script')
            <script>
                'use strict';
                (function($) {
                    $('.seeDetails').on('click', function() {
                        let modal = $('#detailsModal');
                        modal.find('.loading').removeClass('d-none');
                        let action = `{{ route('admin.users.beneficiary.details', ':id') }}`;
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
    @endcan
