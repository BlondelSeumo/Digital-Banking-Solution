@extends('admin.layouts.app')

@push('topBar')
    @include('admin.wire_transfer.top_bar')
@endpush

@section('panel')
    <form method="POST">
        @csrf
        <div class="row gy-4">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">@lang('Transfer Limit')</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Minimum Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="minimum_limit" value="{{ old('minimum_limit', @$setting->minimum_limit) }}" required>
                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Maximum Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="maximum_limit" value="{{ old('maximum_limit', @$setting->maximum_limit) }}" required>
                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">@lang('Tranfer Charge')</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Fixed')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="fixed_charge" value="{{ old('fixed_charge', @$setting->fixed_charge) }}" required>
                                        <div class="input-group-text">{{ __(gs()->cur_text) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Percent')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="percent_charge" value="{{ old('percent_charge', @$setting->percent_charge) }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">@lang('Daily Limit')</h6>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Maximum Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="daily_maximum_limit" value="{{ old('daily_maximum_limit', @$setting->daily_maximum_limit) }}" required>
                                        <div class="input-group-text">{{ __(gs()->cur_text) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Maximum Count')</label>
                                    <input type="number" class="form-control" name="daily_total_transaction" value="{{ old('daily_total_transaction', @$setting->daily_total_transaction) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">@lang('Monthly Limit')</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Maximum Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="monthly_maximum_limit" value="{{ old('monthly_maximum_limit', @$setting->monthly_maximum_limit) }}" required>
                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Maximum Count')</label>
                                    <input type="number" class="form-control" name="monthly_total_transaction" value="{{ old('monthly_total_transaction', @$setting->monthly_total_transaction) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">@lang('Instruction')</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea rows="8" class="form-control nicEdit" name="instruction">
                                @php echo old('instruction', @$setting->instruction); @endphp
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @can('admin.wire.transfer.setting.save')
            <button class="btn btn--primary w-100 h-45 mt-3">@lang('Submit')</button>
        @endcan
    </form>
@endsection
