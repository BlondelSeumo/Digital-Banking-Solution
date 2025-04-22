@extends('admin.layouts.app')
@section('panel')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two bg-white">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Schedule')</th>
                                    <th>@lang('Next Run')</th>
                                    <th>@lang('Last Run')</th>
                                    <th>@lang('Is Running')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($crons as $cron)
                                    @php
                                        $dateTime = now()->parse($cron->next_run);
                                        $formattedDateTime = showDateTime($dateTime, 'Y-m-d\TH:i');
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ __($cron->name) }} @if ($cron->logs->where('error', '!=', null)->count())
                                                <i class="fas fa-exclamation-triangle text--danger"></i>
                                            @endif <br>
                                            <code>{{ __($cron->alias) }}</code>
                                        </td>
                                        <td>{{ __($cron->schedule->name) }}</td>
                                        <td>
                                            @if ($cron->next_run)
                                                {{ __($cron->next_run) }} @if ($cron->next_run > now())
                                                    <br> {{ diffForHumans($cron->next_run) }}
                                                @endif
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cron->last_run)
                                                {{ __($cron->last_run) }}
                                                <br> {{ diffForHumans($cron->last_run) }}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cron->is_running)
                                                <span class="badge badge--success">@lang('Running')</span>
                                            @else
                                                <span class="badge badge--dark">@lang('Pause')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cron->is_default)
                                                <span class="badge badge--success">@lang('Default')</span>
                                            @else
                                                <span class="badge badge--primary">@lang('Customizable')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline--primary" id="actionButton" data-bs-toggle="dropdown">
                                                    <i class="las la-ellipsis-v"></i>@lang('Action')
                                                </button>
                                                <div class="dropdown-menu p-0">
                                                    <a href="{{ route('cron') }}?alias={{ $cron->alias }}" class="dropdown-item"><i class="las la-check-circle"></i> @lang('Run Now')</a>

                                                    @can('admin.cron.schedule.pause')
                                                        @if ($cron->is_running)
                                                            <a href="{{ route('admin.cron.schedule.pause', $cron->id) }}" class="dropdown-item"><i class="las la-pause"></i> @lang('Pause')</a>
                                                        @else
                                                            <a href="{{ route('admin.cron.schedule.pause', $cron->id) }}" class="dropdown-item"><i class="las la-play"></i> @lang('Play')</a>
                                                        @endif
                                                    @endcan

                                                    @can('admin.cron.update')
                                                        <a href="javascript:void(0)" data-id="{{ $cron->id }}" data-name="{{ $cron->name }}" data-url="{{ $cron->url }}" data-next_run="{{ $formattedDateTime }}" data-cron_schedule_id="{{ $cron->cron_schedule_id }}" data-default="{{ $cron->is_default }}" class="dropdown-item updateCron"><i class="las la-pen"></i> @lang('Edit')</a>
                                                    @endcan

                                                    @can('admin.cron.schedule.logs')
                                                        <a href="{{ route('admin.cron.schedule.logs', $cron->id) }}" class="dropdown-item"><i class="las la-history"></i> @lang('Logs')</a>
                                                    @endcan

                                                    @can('admin.cron.delete')
                                                        @if (!$cron->is_default)
                                                            <a href="javascript:void(0)" data-action="{{ route('admin.cron.delete', $cron->id) }}" data-question="@lang('Are you sure to delete this cron?')" class="dropdown-item confirmationBtn"><i class="las la-trash"></i> @lang('Delete')</a>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </div>
                                        </td>
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
            </div><!-- card end -->
        </div>
    </div>
    @can('admin.cron.delete')
        <x-confirmation-modal />
    @endcan

    @can('admin.cron.store')
        <div class="modal fade" id="addCron" tabindex="-1" role="dialog" a aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('Add Cron Job')</h4>
                        <button type="button" class="close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
                    </div>
                    <form class="form-horizontal disableSubmission resetForm" method="post" action="{{ route('admin.cron.store') }}">
                        @csrf

                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Next Run')</label>
                                <input type="datetime-local" name="next_run" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Schedule')</label>
                                <select name="cron_schedule_id" class="form-control select2" data-minimum-results-for-search="-1" required>
                                    @foreach ($schedules as $schedule)
                                        <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Url')</label>
                                <input type="text" name="url" class="form-control" required>
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

    @can('admin.cron.update')
        <div class="modal fade" id="updateCron" tabindex="-1" role="dialog" a aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('Edit Cron Job')</h4>
                        <button type="button" class="close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
                    </div>
                    <form class="form-horizontal resetForm" method="post" action="{{ route('admin.cron.update') }}">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Next Run')</label>
                                <input type="datetime-local" name="next_run" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Schedule')</label>
                                <select name="cron_schedule_id" class="form-control select2" data-minimum-results-for-search="-1" required>
                                    @foreach ($schedules as $schedule)
                                        <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group urlGroup">
                                <label>@lang('Url')</label>
                                <input type="text" name="url" class="form-control">
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

@can(['admin.cron.store', 'admin.cron.schedule'])
    @push('breadcrumb-plugins')
        @can('admin.cron.store')
            <button type="btn" class="btn btn-outline--primary addCron"><i class="las la-plus"></i>@lang('Add')</button>
        @endcan

        @can('admin.cron.schedule')
            <a href="{{ route('admin.cron.schedule') }}" class="btn btn-outline--info"><i class="las la-clock"></i>@lang('Cron Schedule')</a>
        @endcan
    @endpush
@endcan

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addCron').on('click', function() {
                let modal = $('#addCron');
                $('.resetForm').trigger('reset');
                modal.modal('show');
            });

            $('.updateCron').on('click', function(e) {
                e.preventDefault();
                var modal = $('#updateCron');
                let id = $(this).data('id');
                let name = $(this).data('name');
                let next_run = $(this).data('next_run');
                let cron_schedule_id = $(this).data('cron_schedule_id');
                let isDefault = $(this).data('default');
                if (isDefault) {
                    modal.find('[name=url]').attr('required', false);
                    $('.urlGroup').hide();
                } else {
                    modal.find('[name=url]').parent().find('label').addClass('required');
                    modal.find('[name=url]').attr('required', true);
                    modal.find('[name=url]').val($(this).data('url'));
                    $('.urlGroup').show();
                }
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.find('input[name=next_run]').val(next_run);
                modal.find('select[name=cron_schedule_id]').val(cron_schedule_id);
                modal.find(".select2").val(cron_schedule_id).change();
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }
    </style>
@endpush
