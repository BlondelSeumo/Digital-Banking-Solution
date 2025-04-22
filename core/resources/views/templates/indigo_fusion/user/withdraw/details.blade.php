@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-12">

            <div class="card custom--card">
                <div class="card-body">

                    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-3">@lang('Withdrawal Info')</h5>
                        @php echo $withdraw->statusBadge @endphp
                    </div>

                    <ul class="caption-list-two p-0">
                        <li>
                            <span class="caption">@lang('TRX No.')</span>
                            <span class="value">#{{ $withdraw->trx }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Amount')</span>
                            <span class="value fw-bold">{{ showAmount($withdraw->amount) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Charge')</span>
                            <span class="value text--danger">{{ showAmount($withdraw->charge) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('After Charge')</span>
                            <span class="value fw-bold">{{ showAmount($withdraw->after_charge) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('Conversion Rate')</span>
                            <span class="value">1 {{ __(gs()->cur_text) }} = {{ showAmount($withdraw->rate, currencyFormat: false) }}
                                {{ __($withdraw->currency) }}</span>
                        </li>

                        <li>
                            <span class="caption">@lang('After Convert')</span>
                            <span class="value fw-bold text--info">
                                {{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}
                            </span>
                        </li>

                        <li>
                            <span class="caption">@lang('Created At')</span>
                            <span class="value">{{ showDateTime($withdraw->created_at) }}</span>
                        </li>

                        <li>
                            @if ($withdraw->branch)
                                <span class="caption">@lang('Branch')</span>
                                <span class="value text-primary">{{ __(@$withdraw->branch->name) }}</span>
                            @else
                                <span class="caption">@lang('Withdraw Method')</span>
                                <span class="value text-primary fw-bold">{{ __(@$withdraw->method->name) }}</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            @if ($withdraw->status == Status::PAYMENT_REJECT)
                <div class="card custom--card mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">@lang('Reason for Rejection')</h5>
                        {{ $withdraw->admin_feedback }}
                    </div>
                </div>
            @endif
            @php
                $details = $withdraw->withdraw_information ? $withdraw->withdraw_information : null;
            @endphp

            @if ($details)
                <div class="card custom--card mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">@lang('Submitted Data')</h5>

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
                                            <p>{{ $detail->value }}</p>
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
