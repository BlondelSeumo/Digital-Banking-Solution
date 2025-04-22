@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.bank.store', $bank->id ?? 0) }}" method="POST">
                @csrf
                <div class="row gy-4">
                    <div class="col-12">
                        <div class="card b-radius--10">
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label>@lang('Bank Name')</label>
                                            <input class="form-control" name="name" type="text" value="{{ old('name', @$bank->name) }}" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label>@lang('Processing Time') </label>
                                            <div class="input-group">
                                                <input class="form-control" name="processing_time" type="text" value="{{ old('processing_time', @$bank->processing_time) }}" required />
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Transfer Limit')</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>@lang('Minimum Amount')</label>
                                    <div class="input-group">
                                        @php $value = isset($bank) ? getAmount($bank->minimum_limit) : null; @endphp
                                        <input class="form-control" name="minimum_amount" type="number" value="{{ old('minimum_amount', $value) }}" step="any" required />
                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Maximum Amount')</label>
                                    <div class="input-group">
                                        @php $value = isset($bank) ? getAmount($bank->maximum_limit) : null; @endphp
                                        <input class="form-control" name="maximum_amount" type="number" value="{{ old('maximum_amount', $value) }}" step="any" required />
                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Transfer Charge')</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>@lang('Fixed Charge')</label>
                                    <div class="input-group">
                                        @php $value = isset($bank) ? getAmount($bank->fixed_charge) : null; @endphp
                                        <input class="form-control" name="fixed_charge" type="number" value="{{ old('fixed_charge', $value) }}" step="any" required />

                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Percent Charge')</label>
                                    <div class="input-group">
                                        @php $value = isset($bank) ? getAmount($bank->percent_charge) : null; @endphp
                                        <input class="form-control" name="percent_charge" type="number" value="{{ old('percent_charge', $value) }}" step="any" required />

                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Daily Limit')</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>@lang('Maximum Transaction Amount')</label>
                                    <div class="input-group">
                                        @php $value = isset($bank) ? getAmount($bank->daily_maximum_limit) : null; @endphp
                                        <input class="form-control" name="daily_maximum_amount" type="number" value="{{ old('daily_maximum_amount', $value) }}" step="any" required />
                                        <span class="input-group-text"> {{ __(gs()->cur_text) }} </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="daily_transaction_count">@lang('Maximum Transaction Count')</label>
                                    @php $value = isset($bank) ? getAmount($bank->daily_total_transaction) : null; @endphp
                                    <input class="form-control" name="daily_transaction_count" type="number" value="{{ old('daily_transaction_count', $value) }}" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Monthly Limit')</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>@lang('Maximum Transaction Amount')</label>
                                    <div class="input-group">
                                        @php $value = isset($bank) ? getAmount($bank->monthly_maximum_limit) : null; @endphp

                                        <input class="form-control" name="monthly_maximum_amount" type="number" value="{{ old('monthly_maximum_amount', $value) }}" step="any" required />
                                        <span class="input-group-text"> {{ __(gs()->cur_text) }} </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Maximum Transaction Count')</label>
                                    @php $value = isset($bank) ? getAmount($bank->monthly_total_transaction) : null; @endphp
                                    <input class="form-control" name="monthly_transaction_count" type="number" value="{{ old('monthly_transaction_count', $value) }}" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Instruction to Transfer') <i class="fa fa-info-circle text--primary" title="@lang('Users will see this instruction while he/she transferring money to this bank.')"></i></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <textarea class="form-control nicEdit" name="instruction" rows="8">@php echo old('instruction', @$bank->instruction) @endphp</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title">@lang('Form for User')</h5>
                                <button type="button" class="btn btn-sm btn-outline--primary float-end form-generate-btn">
                                    <i class="la la-fw la-plus"></i>@lang('Add New')
                                </button>
                            </div>

                            <div class="card-body">
                                <x-generated-form :form="@$form"></x-generated-form>
                            </div>
                        </div>
                    </div>
                </div>


                @can('admin.bank.store')
                    <button class="btn btn--primary w-100 h-45 mt-3" type="submit">@lang('Submit')</button>
                @endcan
            </form>
        </div>
    </div>
    <x-form-generator-modal />
@endsection

@can('admin.bank.index')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.bank.index') }}" />
    @endpush
@endcan
