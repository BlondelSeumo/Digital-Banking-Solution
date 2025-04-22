@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <form action="{{ route('user.airtime.apply') }}" method="POST">
                @csrf

                <div class="card custom--card topup-card">
                    <div class="card-body">
                        <h6 class="text-center mb-0">@lang('Current Balance'): {{ showAmount(auth()->user()->balance) }}</h6>
                        <div class="form-group">
                            <label class="form-label">@lang('Country')</label>
                            <select name="country_id" class="form--control" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" data-calling_codes="{{ json_encode($country->calling_codes) }}" @selected(old('country_id') == $country->id)>{{ __($country->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group operatorDiv d-none">
                            <label class="required form-label">@lang('Operator')</label>
                            <div class="operator-wrapper"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">@lang('Mobile Number')</label>
                            <div class="input--group">
                                <span class="input--group-text">
                                    <select name="calling_code" id="" class="form--control p-0 h-auto"></select>
                                </span>
                                <input type="tel" class="form-control form--control mobileNumber" name="mobile_number" value="{{ old('mobile_number') }}" required>
                                <div class="input--group-border"></div>
                            </div>
                        </div>

                        <div class="form-group amount-wrapper d-none">
                            <label class="form-label">@lang('Amount') <span class="topupLimit text--info d-none"></span></label>
                            <div class="input-group">
                                <input type="number" step="any" class="form-control form--control amount" name="amount" value="{{ old('amount') }}" required>
                                <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                            </div>
                        </div>

                        <div class="form-group fixed-amounts-wrapper d-none">
                            <label class="form-label">@lang('Amount')</label>
                            <div class="fixed-amount-input-wrapper"></div>
                        </div>

                        @include($activeTemplate . 'partials.otp_field')

                        <div class="form-group suggested-amounts-wrapper d-none">
                            <label class="form-label">@lang('Suggested Amounts')</label>
                            <div class="suggested-amounts"></div>
                        </div>

                        <button type="submit" class="btn btn--base w-100">@lang('Top Up')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@include('partials.operator_modal')
@include('partials.top_up')
