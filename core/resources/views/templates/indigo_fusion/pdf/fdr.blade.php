@extends('pdf.layouts.master')

@section('main-content')
    <div class="pdf-card">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-12">
                    <div class="custom--card">
                        <div class="card-body">
                            <h5 class="text-center">
                                @lang('Your FDR invest information')
                            </h5>
                            <ul class="caption-list-two mt-3">
                                <li>
                                    <span class="caption">@lang('FDR No.')</span>
                                    <span class="value">{{ __($fdr->fdr_number) }}</span>
                                </li>
                                <li>
                                    <span class="caption">@lang('Plan')</span>
                                    <span class="value">{{ __($fdr->plan->name) }}</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Profit Rate')</span>
                                    <span class="value">{{ getAmount($fdr->plan->interest_rate) }}%</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Your Amount')</span>
                                    <span class="value">{{ showAmount($fdr->amount) }}</span>
                                </li>

                                <li>
                                    <span class="caption">@lang('Profit in Every') {{ $fdr->plan->installment_interval }} {{ __(Str::plural('Day', $fdr->plan->installment_interval)) }}</span>
                                    <span class="value">{{ showAmount(($fdr->amount * $fdr->plan->interest_rate) / 100) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        body {
            background-color: #00a6f712;
        }
    </style>
@endpush
