@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4">
        <div class="col-sm-5 col-lg-3">
            <div class="card custom--card">
                <div class="card-body">
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            <span class="value">{{ $fdr->fdr_number }}</span>
                            <span class="caption">@lang('FDR Number')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $fdr->plan->name }}</span>
                            <span class="caption">@lang('Plan')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ showAmount($fdr->amount) }}</span>
                            <span class="caption">@lang('Deposited')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ getAmount($fdr->interest_rate) }}%</span>
                            <span class="caption">@lang('Interest Rate')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value text--base">{{ showAmount($fdr->per_installment) }}</span>
                            <span class="caption">@lang('Per Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $fdr->installments->count() }} </span>
                            <span class="caption">@lang('Received Installment')</span>
                        </li>
                        <li class="list-group-item">
                            <span class="value">{{ showAmount($fdr->profit) }}</span>
                            <span class="caption">@lang('Profit Received')</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-7 col-lg-9">
            @include($activeTemplate . 'partials.installment_table')
        </div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.fdr.plans') }}">@lang('FDR Plans')</a></li>
    <li><a href="{{ route('user.fdr.list') }}" class="active">@lang('My FDR List')</a></li>
@endpush
