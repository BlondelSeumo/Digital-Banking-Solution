@extends('admin.layouts.app')
@push('topBar')
    @include('admin.plans.top_bar')
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Interval')</th>
                                    <th>@lang('Per Installment')</th>
                                    <th>@lang('Total Installment')</th>
                                    <th>@lang('After Mature')</th>
                                    <th>@lang('Status')</th>
                                    @if (can('admin.plans.dps.edit') || can('admin.plans.dps.status'))
                                        <th>@lang('Action')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>
                                            {{ __($plan->name) }}
                                        </td>

                                        <td>
                                            {{ getAmount($plan->interest_rate) }}%
                                        </td>

                                        <td>
                                            {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}
                                        </td>

                                        <td>
                                            {{ showAmount($plan->per_installment) }}
                                        </td>

                                        <td>
                                           {{ $plan->total_installment }}</span>
                                        </td>

                                        <td>
                                            <span>{{ showAmount($plan->final_amount) }}</span>
                                        </td>

                                        <td> @php echo $plan->statusBadge; @endphp </td>

                                        @if (can('admin.plans.dps.edit') || can('admin.plans.dps.status'))
                                            <td>
                                                @can('admin.plans.dps.edit')
                                                    <a href="{{ route('admin.plans.dps.edit', $plan->id) }}" class="btn btn-sm btn-outline--primary">
                                                        <i class="la la-pencil"></i>@lang('Edit')
                                                    </a>
                                                @endcan

                                                @can('admin.plans.dps.status')
                                                    @if ($plan->status)
                                                        <button type="button" data-action="{{ route('admin.plans.dps.status', $plan->id) }}" data-question="@lang('Are you sure to disable this plan?')" class="btn btn-sm confirmationBtn btn-outline--danger">
                                                            <i class="la la-la la-eye-slash"></i>@lang('Disable')
                                                        </button>
                                                    @else
                                                        <button type="button" data-action="{{ route('admin.plans.dps.status', $plan->id) }}" data-question="@lang('Are you sure to enable this plan?')" class="btn btn-sm confirmationBtn btn-outline--success">
                                                            <i class="la la-la la-eye"></i>@lang('Enable')
                                                        </button>
                                                    @endif
                                                @endcan
                                            </td>
                                        @endif

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($plans->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($plans) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@can('admin.plans.dps.add')
    @push('breadcrumb-plugins')
        <a href="{{ route('admin.plans.dps.add') }}" class="btn btn-sm btn-outline--primary">
            <i class="las la-plus"></i>@lang('Add Plan')
        </a>
    @endpush
@endcan
