@extends('admin.layouts.app')
@section('panel')

    @php
        $request = request();
        $tableName = 'notification_history';
        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);

        $types = App\Models\NotificationLog::notificationTypes();
        $senders = App\Models\NotificationLog::select('sender')->distinct('sender')->get()->pluck('sender')->toArray();

        $columns = collect([prepareTableColumn('created_at', 'Sent At', 'showDateTime("$item->created_at", "d M, Y h:i A")', filter: 'date'), prepareTableColumn('account_number', 'Account No.', link: '$item->user_id ? route("admin.users.detail", $item->user_id) : "#"'), prepareTableColumn('username', 'Username', link: 'route("admin.users.detail", $item->user_id)'), prepareTableColumn('notification_type', 'Type', filter: 'select', filterOptions: $types), prepareTableColumn('sender', 'Sender', filter: 'select', filterOptions: $senders), prepareTableColumn('subject', 'Subject')]);

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }

        $action = [
            'name' => 'Action',
            'show' => can('admin.report.email.details')
        ];
    @endphp

    <x-viser_table.table :data="$logs" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive">
        @slot('tbody')
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <x-viser_table.table-data-columns :renderColumns="$columns->whereIn('id', $visibleColumns)" :item="$log" />

                            @can('admin.report.email.details')
                        <td>
                            <button class="btn btn-sm btn-outline--primary notifyDetail" data-type="{{ $log->notification_type }}" @if ($log->notification_type == 'email') data-message="{{ route('admin.report.email.details', $log->id) }}" @else data-message="{{ $log->message }}" @if ($log->image) data-image="{{ asset(getFilePath('push') . '/' . $log->image) }}" @endif @endif data-sent_to="{{ $log->sent_to }}"><i class="las la-desktop"></i> @lang('Detail')</button>

                        </td>
                        @endif
                    </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            @endslot
        </x-table>

        <div class="modal fade" id="notifyDetailModal" tabindex="-1" aria-labelledby="notifyDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notifyDetailModalLabel">@lang('Notification Details')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <h3 class="text-center mb-3">@lang('To'): <span class="sent_to"></span></h3>
                        <div class="detail"></div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('breadcrumb-plugins')
        @if (@$user)
            <a href="{{ route('admin.users.notification.single', $user->id) }}" class="btn btn-outline--primary btn-sm"><i class="las la-paper-plane"></i> @lang('Send Notification')</a>
        @endif
    @endpush

    @push('script')
        <script>
            $('.notifyDetail').on('click', function() {
                var message = ''
                if ($(this).data('image')) {
                    message += `<img src="${$(this).data('image')}" class="w-100 mb-2" alt="image">`;
                }
                message += $(this).data('message');
                var sent_to = $(this).data('sent_to');
                var modal = $('#notifyDetailModal');
                if ($(this).data('type') == 'email') {
                    var message = `<iframe src="${message}" height="500" width="100%" title="Iframe Example"></iframe>`
                }
                $('.detail').html(message)
                $('.sent_to').text(sent_to)
                modal.modal('show');
            });
        </script>
    @endpush
