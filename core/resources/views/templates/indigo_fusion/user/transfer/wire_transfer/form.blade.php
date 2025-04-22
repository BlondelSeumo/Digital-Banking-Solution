@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 justify-content-center">
        <div class="col-xl-4">
            <div class="card custom--card">
                <div class="card-body">
                    <h6 class="card-title text-center">@lang('Transfer Limit')</h6>
                    <ul class="caption-list-two">
                        <li>
                            <span class="caption">@lang('Minimum Per Transaction')</span>
                            <span class="value">{{ showAmount(@$setting->minimum_limit) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Maximum Per Transaction')</span>
                            <span class="value">{{ showAmount(@$setting->maximum_limit) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Daily Maximum')</span>
                            <span class="value">{{ showAmount(@$setting->daily_maximum_limit) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Monthly Maximum')</span>
                            <span class="value">{{ showAmount(@$setting->monthly_maximum_limit) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Daily Maximum Transaction')</span>
                            <span class="value">{{ @$setting->daily_total_transaction }}</span>
                        </li>

                        <li>
                            <span class="caption"> @lang('Monthly Maximum Transaction')</span>
                            <span class="value">{{ @$setting->monthly_total_transaction }}</span>
                        </li>
                    </ul>

                    @php $transferCharge = $setting->chargeText(); @endphp

                    @if ($transferCharge)
                        <small class="text--danger">* @lang('Charge') {{ $transferCharge }}</small>
                    @endif
                </div>
            </div>

            @if ($setting->instruction)
                <div class="card custom--card mt-3">
                    <div class="card-body">
                        <h6 class="card-title text-center">@lang('Instruction')</h6>
                        <p>@php echo $setting->instruction; @endphp</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-xl-8">
            <div class="card custom--card">
                <div class="card-body">
                    @if (@$setting->instruction)
                        <div class="text-center">
                            @php echo @$setting->instruction;  @endphp
                        </div>
                    @endif
                    <form method="POST" action="{{ route('user.transfer.wire.request') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" class="form--control" name="amount">
                                <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                            </div>
                        </div>
                        <x-viser-form identifier="act" identifierValue="wire_transfer" />
                        @include($activeTemplate . 'partials.otp_field')
                        <button type="submit" class="btn btn--base w-100 ">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<x-transfer-bottom-menu />
