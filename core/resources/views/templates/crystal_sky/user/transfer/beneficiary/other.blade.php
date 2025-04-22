@extends($activeTemplate . 'user.transfer.layout')
@section('transfer-content')
    <div class="card custom--card mb-4 @if (!old('account_number') || !old('id')) d-none @endif" id="addForm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">@lang('Add Beneficiary to Other Banks')</h5>

                <button class="btn btn--sm btn--danger close-form" type="button"><i class="la la-times"></i></button>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('user.beneficiary.other.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                <div class="form-group">
                    <label class="form-label">@lang('Select Bank')</label>
                    <select class="form--control" name="bank" required>
                        <option value="" disabled selected>@lang('Select One')</option>
                        @foreach ($otherBanks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">@lang('Short Name')</label>
                    <input class="form--control" name="short_name" type="text" required>
                </div>
                <div id="user-fields">
                </div>
                <button class="btn w-100 btn--base" type="submit">@lang('Submit')</button>
            </form>
        </div>
    </div>

    <div class="card custom--card overflow-hidden">
        <div class="card-header">
            <div class="header-nav mb-0">
                <button type="button" class="btn btn-dark add-btn"><i class="la la-plus-circle"></i> @lang('Add New Beneficiary')</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('Bank')</th>
                            <th>@lang('Account No.')</th>
                            <th>@lang('Account Name')</th>
                            <th>@lang('Short Name')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($beneficiaries as $beneficiary)
                            <tr>
                                <td>{{ $beneficiary->beneficiaryOf->name }}</td>
                                <td>{{ $beneficiary->account_number }}</td>
                                <td>{{ $beneficiary->account_name }}</td>
                                <td>{{ $beneficiary->short_name }}</td>
                                <td>
                                    <div class="button-group">
                                        <button class="btn btn--sm btn--primary seeDetails" data-id="{{ $beneficiary->id }}"><i class="la la-desktop"></i></button>
                                        <button class="btn btn--sm btn--base EditBeneficiary" data-resources="{{ $beneficiary }}"><i class="la la-pen"></i></button>
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
        @if ($beneficiaries->hasPages())
            <div class="card-footer">
                {{ paginateLinks($beneficiaries) }}
            </div>
        @endif
    </div>
@endsection

@push('modal')
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
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            const addForm = $('#addForm');

            $('.add-btn').on('click', function() {
                $(this).parent().hide();
                addForm.find('.card-title').text(`@lang('Add Beneficiary to Other Banks')`);
                addForm.find('form').trigger("reset");
                $('input:radio').removeAttr('checked');
                addForm.removeClass('d-none').hide().fadeIn(500);
            });

            $('.close-form').on('click', function() {
                $('.add-btn').parent().fadeIn(500);
                addForm.addClass('d-none');
                $('.add-btn').removeClass('d-none').hide().fadeIn(500);
            });

            addForm.find('select[name=bank]').on('change', function() {
                let bankId = $(this).val();
                bankFormProcess(bankId)
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

            function bankFormProcess(bankId) {
                let action = `{{ route('user.beneficiary.other.bank.form.data', ':id') }}`;
                $.ajax({
                    url: action.replace(':id', bankId),
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response.success) {
                            $('#user-fields').html(response.html).hide().fadeIn(500);
                        } else {
                            notify('error', response.message || `@lang('Something went the wrong')`)
                        }
                    },
                    error: function(e) {
                        notify(`@lang('Something went the wrong')`)
                    }
                });
            }

            $('.EditBeneficiary').on('click', function() {

                let beneficiary = $(this).data('resources');
                let bankId = beneficiary.beneficiary_of.id;
                bankFormProcess(bankId);
                setTimeout(() => {
                    addForm.find('.card-title').text(`@lang('Update Beneficiary to Other Banks')`);
                    addForm.find('[name=id]').val(beneficiary.id);
                    addForm.find('[name=bank]').val(bankId);
                    addForm.find('input[name="short_name"]').val(beneficiary.short_name);
                    addForm.find('input[name="account_number"]').val(beneficiary.account_number);
                    addForm.find('input[name="account_name"]').val(beneficiary.account_name);

                    if (beneficiary.details.length > 0) {
                        let details = beneficiary.details.slice(2);

                        $.each(beneficiary.details, function(index, value) {
                            var lowerName = value.name.replace(/\s+/g, '_').toLowerCase();
                            if (value.type == 'radio') {
                                addForm.find('input:radio[name="' + lowerName + '"]').filter(
                                    '[value="' + value.value + '"]').attr('checked', true);
                            } else if (value.type == 'textarea') {
                                addForm.find('textarea[name="' + lowerName + '"]').text(value
                                    .value);
                            } else if (value.type == 'select') {
                                addForm.find('select[name="' + lowerName + '"]').val(value
                                    .value);
                            } else if (value.type == 'file') {
                                addForm.find('input[name="' + lowerName + '"]').val('');
                            } else {
                                addForm.find('input[name="' + lowerName + '"]').val(value.value);
                            }
                        });
                    }
                    addForm.removeClass('d-none');
                }, 500);
                $('.add-btn').addClass('d-none').hide().fadeIn(500);
            });


        })(jQuery)
    </script>
@endpush
