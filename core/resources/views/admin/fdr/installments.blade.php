@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-sm-5 col-lg-3">
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">@lang('FDR Summary')</h6>
                    @php echo $fdr->status_badge;@endphp
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            @can('admin.users.detail')
                                <a href="{{ route('admin.users.detail', $fdr->user_id) }}">
                                    <span class="value">{{ $fdr->user->account_number }}</span>
                                </a>
                            @else
                                <span class="value">{{ $fdr->user->account_number }}</span>
                            @endcan
                            <span class="caption">@lang('Account')</span>
                        </li>

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
                            <span class="value text--primary">{{ showAmount($fdr->per_installment) }}</span>
                            <span class="caption">@lang('Per Installment')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ $fdr->installments->count() }}</span>
                            <span class="caption">@lang('Given Installments')</span>
                        </li>

                        <li class="list-group-item">
                            <span class="value">{{ getAmount($fdr->profit) }} {{ gs()->cur_text }}</span>
                            <span class="caption">@lang('Profit Given')</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-7 col-lg-9">
            @include('admin.partials.installments_table')
        </div>
    </div>
@endsection
