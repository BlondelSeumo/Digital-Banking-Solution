@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex justify-content-end align-content-center mb-4 gap-2 flex-wrap">
                <x-search-form placeholder="FDR No." dateSearch='yes' btn="btn--base" />
                @if (request()->date || request()->search)
                    <a class="btn btn-outline--info" href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}"><i class="la la-download"></i> @lang('Download PDF')</a>
                @endif
            </div>
            <div class="custom--card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('FDR No.')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Installment')</th>
                                    <th>@lang('Next Installment')</th>
                                    <th>@lang('Lock In Period')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Opened At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($allFdr as $fdr)
                                    <tr>

                                        <td>
                                            #{{ $fdr->fdr_number }}
                                        </td>

                                        <td>{{ getAmount($fdr->interest_rate) }}%</td>

                                        <td>
                                            <span class="fw-semibold">{{ showAmount($fdr->amount) }}</span>
                                        </td>

                                        <td>{{ showAmount($fdr->per_installment) }} /{{ $fdr->installment_interval }} {{ __(Str::plural('Day', $fdr->installment_interval)) }}</td>

                                        <td>
                                            @if ($fdr->status != 2)
                                                {{ showDateTime($fdr->next_installment_date, 'd M, Y') }}
                                            @else
                                                @lang('N/A')
                                            @endif
                                        </td>

                                        <td>
                                            {{ showDateTime($fdr->locked_date->endOfDay(), 'd M, Y') }}
                                        </td>

                                        <td>@php echo $fdr->statusBadge; @endphp</td>

                                        <td>
                                            {{ showDateTime($fdr->created_at->endOfDay(), 'd M, Y h:i A') }}
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" class="btn btn-sm btn--light" data-bs-toggle="dropdown" type="button">
                                                    <i class="las la-ellipsis-v m-0"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('user.fdr.details', $fdr->fdr_number) }}" class="dropdown-item">
                                                        <i class="las la-list"></i> @lang('Details')
                                                    </a>

                                                    <a href="{{ route('user.fdr.instalment.logs', $fdr->fdr_number) }}" class="dropdown-item">
                                                        <i class="las la-wallet"></i> @lang('Installments')
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($allFdr->hasPages())
                    <div class="card-footer py-2">
                        {{ paginateLinks($allFdr) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.fdr.plans') }}">@lang('FDR Plans')</a></li>
    <li><a href="{{ route('user.fdr.list') }}" class="active">@lang('My FDR List')</a></li>
@endpush

@push('style')
    <style>
        .btn[type=submit] {
            height: unset !important;
        }

        .btn {
            padding: 12px 0.875rem;
        }
    </style>
@endpush
