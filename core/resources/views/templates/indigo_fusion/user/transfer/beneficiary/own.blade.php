@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card custom--card mb-4 @if (!old('account_number')) d-none @endif" id="addForm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title"><span class="title"></span> @lang(' Beneficiary to') @lang(gs()->site_name)</h5>
                        <button type="button" class="btn btn-sm btn--danger close-form-btn"><i class="la la-times"></i></button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.beneficiary.own.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label>@lang('Account Number')</label>
                            <input type="text" name="account_number" value="{{ old('account_number') }}" class="form--control" required>
                            <small class="text--danger error-message"></small>
                        </div>

                        <div class="form-group">
                            <label>@lang('Account Name')</label>
                            <input type="text" name="account_name" value="{{ old('account_name') }}" class="form--control" required>
                            <small class="text--danger error-message"></small>
                        </div>

                        <div class="form-group">
                            <label>@lang('Short Name')</label>
                            <input type="text" name="short_name" value="{{ old('short_name') }}" class="form--control" required>
                        </div>

                        <button type="submit" class="btn w-100 btn--base">@lang('Submit')</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive--md">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn--dark add-btn mb-3"><i class="la la-plus-circle"></i>
                        @lang('Add Beneficiary')</button>
                </div>

                <table class="table custom--table">
                    <thead>
                        <tr>
                            <th>@lang('Account Number')</th>
                            <th>@lang('Account Name')</th>
                            <th>@lang('Short Name')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($beneficiaries as $beneficiary)
                            <tr>
                                <td>{{ $beneficiary->account_number }}</td>
                                <td>{{ $beneficiary->account_name }}</td>
                                <td>{{ $beneficiary->short_name }}</td>
                                <td>
                                    <button class="btn btn-sm btn--base editBeneficiary" data-resources="{{ $beneficiary }}"><i class="la la-pen"></i></button>
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

            @if ($beneficiaries->hasPages())
                <div class="mt-3">
                    {{ paginateLinks($beneficiaries) }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            const addForm = $('#addForm');
            const editForm = $('#editForm');

            $('.add-btn').on('click', function() {
                $(this).parent().hide();
                addForm.find('.title').text(`@lang('Add')`);
                addForm.removeClass('d-none').hide().fadeIn(500);
            });

            $('.close-form-btn').on('click', function() {
                addForm.find('form').trigger("reset");
                $('.add-btn').parent().show();
                addForm.addClass('d-none');
                $('.add-btn').removeClass('d-none').hide().fadeIn(500);
            });


            addForm.on('focusout', '[name=account_number], [name=account_name]', function() {
                let $this = $(this);
                var type = "add";
                onwBeneficiaryChecking($this, type);

            });

            editForm.on('focusout', '[name=account_number], [name=account_name]', function() {
                let $this = $(this);
                var type = "edit";
                onwBeneficiaryChecking($this, type);

            });

            function onwBeneficiaryChecking($this, type) {
                if ($this.val()) {
                    let route = `{{ route('user.beneficiary.check.account') }}`;
                    let data = {}
                    if (type == 'add') {
                        data.account_name = addForm.find('[name=account_name]').val();
                        data.account_number = addForm.find('[name=account_number]').val();
                    } else {
                        data.account_name = editForm.find('[name=account_name]').val();
                        data.account_number = editForm.find('[name=account_number]').val();
                        data.edit = type;
                    }

                    $.get(route, data, function(response) {
                        if (response.error) {
                            $this.parent('.form-group').find('.error-message').text(response
                                .message);
                        } else {
                            if (type == 'add') {
                                addForm.find('[name=account_number]').val(response.data.account_number);
                                addForm.find('[name=account_name]').val(response.data.account_name);
                                addForm.find('.error-message').empty();
                            } else {
                                editForm.find('[name=account_number]').val(response.data.account_number);
                                editForm.find('[name=account_name]').val(response.data.account_name);
                                editForm.find('.error-message').empty();
                            }

                        }
                    });
                } else {
                    addForm.find('.error-message').empty();
                }
            }

            $('.editBeneficiary').on('click', function() {
                addForm.removeClass('d-none').hide().fadeIn(500);

                $('.add-btn').addClass('d-none').hide().fadeIn(500);

                let beneficiary = $(this).data('resources');
                addForm.find('[name="account_number"]').val(beneficiary.account_number);
                addForm.find('[name="account_name"]').val(beneficiary.account_name);
                addForm.find('[name="short_name"]').val(beneficiary.short_name);
                addForm.find('[name="id"]').val(beneficiary.id);
                addForm.find('.title').text(`@lang('Update')`);
            });
        })(jQuery)
    </script>
@endpush

<x-transfer-bottom-menu />
