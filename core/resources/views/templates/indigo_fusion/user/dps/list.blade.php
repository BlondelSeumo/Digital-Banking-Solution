@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex justify-content-end align-content-center mb-4 gap-2">
                <x-search-form placeholder="DPS No." dateSearch='yes' btn="btn--base" />
                @if (request()->date || request()->search)
                    <a class="btn btn-outline--info" href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}"><i class="la la-download"></i> @lang('Download PDF')</a>
                @endif
            </div>
            <div class="custom--card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="custom--table table">
                            <thead>
                                <tr>
                                    <th>@lang('DPS No.')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Per Installment')</th>
                                    <th>@lang('Total')</th>
                                    <th>@lang('Given')</th>
                                    <th>@lang('Next Installment')</th>
                                    <th>@lang('DPS Amount')</th>
                                    <th>@lang('Maturity Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allDps as $dps)
                                    <tr>
                                        <td>
                                            #{{ $dps->dps_number }}
                                        </td>

                                        <td>{{ getAmount($dps->interest_rate) }}%</td>

                                        <td>{{ showAmount($dps->per_installment) }} /{{ $dps->installment_interval }} {{ __(Str::plural('Day', $dps->installment_interval)) }}</td>

                                        <td>{{ $dps->total_installment }}</td>
                                        <td>{{ $dps->given_installment }}</td>
                                        <td>{{ showDateTime(@$dps->nextInstallment->installment_date, 'd M, Y') }}</td>
                                        <td>{{ showAmount($dps->depositedAmount()) }}</td>
                                        <td>{{ showAmount($dps->depositedAmount() + $dps->profitAmount()) }}</td>
                                        <td>@php echo $dps->statusBadge; @endphp</td>

                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" class="btn btn-sm btn--light" data-bs-toggle="dropdown" type="button">
                                                    <i class="las la-ellipsis-v m-0"></i>
                                                </button>
                                                <div class="dropdown-menu">

                                                    <a href="{{ route('user.dps.details', $dps->dps_number) }}" class="dropdown-item">
                                                        <i class="las la-list"></i> @lang('Details')
                                                    </a>

                                                    <a class="dropdown-item" href="{{ route('user.dps.instalment.logs', $dps->dps_number) }}">
                                                        <i class="las la-wallet"></i> @lang('Installments')
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($allDps->hasPages())
                    <div class="card-footer py-2">
                        {{ paginateLinks($allDps) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.dps.plans') }}">@lang('DPS Plans')</a></li>
    <li><a class="active" href="{{ route('user.dps.list') }}">@lang('My DPS List')</a></li>
@endpush
