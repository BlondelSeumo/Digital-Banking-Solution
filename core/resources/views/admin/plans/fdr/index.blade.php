@extends('admin.layouts.app')

@push('topBar')
    @include('admin.plans.top_bar')
@endpush

@section('panel')

    <div class="card b-radius--10">
        <div class="card-body p-0">
            <div class="table-responsive--md table-responsive">
                <table class="table table--light style--two">
                    <thead>
                        <tr>
                            <th>@lang('Plan')</th>
                            <th>@lang('Lock-in Days')</th>
                            <th>@lang('Rate')</th>
                            <th>@lang('Interval')</th>
                            <th>@lang('Minimum Amount')</th>
                            <th>@lang('Maximum Amount')</th>
                            <th>@lang('Status')</th>
                            @if (can('admin.plans.fdr.save') || can('admin.plans.fdr.status'))
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
                                    {{ $plan->locked_days }}
                                </td>

                                <td>
                                    {{ getAmount($plan->interest_rate) }}%
                                </td>

                                <td>
                                    {{ $plan->installment_interval }} {{__(Str::plural('Day', $plan->installment_interval))}}
                                </td>

                                <td>
                                    {{ showAmount($plan->minimum_amount) }}
                                </td>

                                <td>
                                    {{ showAmount($plan->maximum_amount) }}
                                </td>

                                <td> @php echo $plan->statusBadge; @endphp </td>

                                @if (can('admin.plans.fdr.save') || can('admin.plans.fdr.status'))
                                    <td>
                                        @can('admin.plans.fdr.save')
                                            <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-resource="{{ $plan }}" data-modal_title="@lang('Edit Plan')" data-has_status="1"><i class="la la-pencil"></i>@lang('Edit')
                                            </button>
                                        @endcan

                                        @can('admin.plans.fdr.status')
                                            @if ($plan->status)
                                                <button type="button" data-action="{{ route('admin.plans.fdr.status', $plan->id) }}" data-question="@lang('Are you sure to disable this plan?')" class="btn btn-sm confirmationBtn btn-outline--danger">
                                                    <i class="la la-la la-eye-slash"></i>@lang('Disable')
                                                </button>
                                            @else
                                                <button type="button" data-action="{{ route('admin.plans.fdr.status', $plan->id) }}" data-question="@lang('Are you sure to enable this plan?')" class="btn btn-sm confirmationBtn btn-outline--success">
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
    </div>

    <x-confirmation-modal />
    @can('admin.plans.fdr.save')
        @include('admin.plans.fdr.form')
    @endcan
@endsection

@can('admin.plans.fdr.save')
    @push('breadcrumb-plugins')
        <!-- Modal Trigger Button -->
        <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add Plan')">
            <i class="las la-plus"></i>@lang('Add Plan')
        </button>
    @endpush
@endcan

@push('script-lib')
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let modal = $("#cuModal");


            $('[name=interest_rate], [name=minimum_amount], [name=maximum_amount]').on('input', () => calculateProfit());

            function calculateProfit() {
                let minAmount = Number($('[name=minimum_amount]').val());
                let maxAmount = Number($('[name=maximum_amount]').val());
                let interest = Number($('[name=interest_rate]').val()) / 100;
                let interval = $('[name=installment_interval]').val();
                let totalMinAmount = minAmount * interest;
                let totalMaxAmount = maxAmount * interest;

                if (minAmount && maxAmount && interest) {
                    modal.find('#minAmount').text(`${showAmount(totalMinAmount)} @lang(gs()->cur_text)`);
                    modal.find('#maxAmount').text(`${showAmount(totalMaxAmount)} @lang(gs()->cur_text)`);
                    modal.find('#perInterval').text(interval);
                    modal.find('.final-amount').removeClass('d-none');
                }
            }

            $('#cuModal').on('show.bs.modal', function(e) {
                calculateProfit();
            });

            $('#cuModal').on('hidden.bs.modal', function(e) {
                modal.find('.final-amount').addClass('d-none');
            });

            if (new URLSearchParams(window.location.search).has('addnew')) {
                let cuModal = new bootstrap.Modal(document.getElementById('cuModal'));
                cuModal.show();
            }

            @if ($errors->any())
                let cuModal = new bootstrap.Modal(document.getElementById('cuModal'));
                cuModal.show();
            @endif


        })(jQuery);
    </script>
@endpush
