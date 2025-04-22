@extends('admin.layouts.app')
@section('panel')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Interval')</th>
                                    <th>@lang('Status')</th>

                                    @can(['admin.cron.schedule.store', 'admin.cron.schedule.status'])
                                        <th>@lang('Actions')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td>{{ __($schedule->name) }}</td>
                                        <td>{{ __($schedule->interval) }} @lang('Seconds')</td>
                                        <td> @php echo $schedule->statusBadge; @endphp </td>
                                        @can(['admin.cron.schedule.store', 'admin.cron.schedule.status'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.cron.schedule.store')
                                                        <button type="button" class="btn btn-sm btn-outline--primary updateSchedule" data-id="{{ $schedule->id }}" data-name="{{ $schedule->name }}" data-interval="{{ $schedule->interval }}"><i class="las la-pen"></i>
                                                            @lang('Edit')</button>
                                                    @endcan

                                                    @can('admin.cron.schedule.status')
                                                        @if (!$schedule->status)
                                                            <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.cron.schedule.status', $schedule->id) }}" data-question="@lang('Are you sure to enable this schedule?')">
                                                                <i class="la la-eye"></i> @lang('Enable')
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.cron.schedule.status', $schedule->id) }}" data-question="@lang('Are you sure to disable this schedule?')">
                                                                <i class="la la-eye-slash"></i> @lang('Disable')
                                                            </button>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($schedules->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($schedules) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    @can('admin.cron.schedule.status')
        <x-confirmation-modal />
    @endcan

    @can('admin.cron.schedule.store')
        <div class="modal fade" id="addSchedule" tabindex="-1" role="dialog" a aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('Add Cron Schedule')</h4>
                        <button type="button" class="close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
                    </div>
                    <form class="form-horizontal disableSubmission resetForm" method="post" action="{{ route('admin.cron.schedule.store') }}">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Interval')</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="interval" required>
                                        <span class="input-group-text">@lang('Seconds')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

@endsection

@can(['admin.cron.index', 'admin.cron.schedule.store'])
    @push('breadcrumb-plugins')
        @can('admin.cron.schedule.store')
            <button class="btn btn-sm btn-outline--primary addSchedule"><i class="las la-plus"></i> @lang('Add New')</button>
        @endcan

        @can('admin.cron.index')
            <x-back route="{{ route('admin.cron.index') }}" />
        @endcan
    @endpush
@endcan

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.updateSchedule').on('click', function() {
                let title = "@lang('Update Schedule')";
                var modal = $('#addSchedule');
                let id = $(this).data('id');
                let name = $(this).data('name');
                let interval = $(this).data('interval');
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.find('input[name=interval]').val(interval);
                modal.find('.modal-title').text(title)
                modal.modal('show');
            });

            $('.addSchedule').on('click', function() {
                let title = "@lang('Add Schedule')";
                let modal = $('#addSchedule');
                $('.resetForm').trigger('reset');
                modal.find('input[name=id]').val('');
                modal.find('.modal-title').text(title)
                modal.modal('show');
            })
        })(jQuery);
    </script>
@endpush
