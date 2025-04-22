@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-12">

            <div class="card custom--card">
                <div class="card-body">

                    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-3">@lang('Deposit Info')</h5>
                        @php echo $deposit->statusBadge @endphp
                    </div>

                    <ul class="caption-list-two p-0">
                        <li>
                            <span class="caption">@lang('TRX No.')</span>
                            <span class="value">#{{ $deposit->trx }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Amount')</span>
                            <span class="value fw-bold">{{ showAmount($deposit->amount) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Charge')</span>
                            <span class="value text--danger">{{ showAmount($deposit->charge) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('After Charge')</span>
                            <span class="value fw-bold">{{ showAmount($deposit->amount + $deposit->charge) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Conversion Rate')</span>
                            <span class="value">1 {{ __(gs()->cur_text) }} = {{ showAmount($deposit->rate, currencyFormat: false) }} {{ __($deposit->method_currency) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('After Convert')</span>
                            <span class="value fw-bold text--info">
                                {{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}
                            </span>
                        </li>

                        <li>
                            <span class="caption">@lang('Created At')</span>
                            <span class="value">{{ showDateTime($deposit->created_at) }}</span>
                        </li>

                        <li>
                            @if ($deposit->branch)
                                <span class="caption">@lang('Branch')</span>
                                <span class="value text-primary">{{ __(@$deposit->branch->name) }}</span>
                            @else
                                <span class="caption">@lang('Gateway')</span>
                                <span class="value text-primary fw-bold">{{ __(@$deposit->gateway->name) }}</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            @if ($deposit->status == Status::PAYMENT_REJECT)
                <div class="card custom--card mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">@lang('Reason for Rejection')</h5>
                        {{ $deposit->admin_feedback }}
                    </div>
                </div>

            @endif

            @if ($deposit->method_code >= 1000)
                <div class="card custom--card mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">@lang('Submitted Data')</h5>
                        @php
                            $details = $deposit->detail != null ? $deposit->detail : null;
                        @endphp
                        <ul class="caption-list-two p-0">
                            @foreach ($details as $detail)
                                <li>
                                    <span class="caption">{{ $detail->name }}</span>
                                    <span class="value">
                                        @if ($detail->type == 'checkbox')
                                            {{ implode(',', $detail->value) }}
                                        @elseif($detail->type == 'file')
                                            @if ($detail->value)
                                                <a href="{{ route('admin.download.attachment', encrypt(getFilePath('verify') . '/' . $detail->value)) }}"><i class="fa-regular fa-file"></i> @lang('Attachment') </a>
                                            @else
                                                @lang('No File')
                                            @endif
                                        @else
                                            <p>{{ __($detail->value) }}</p>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
