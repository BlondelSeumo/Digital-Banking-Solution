@extends('admin.layouts.app')
@section('panel')
    @php
        $request = request();
        $tableName = 'fdr_list';
        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);

        $columns = collect([
            prepareTableColumn('fdr_number', 'FDR No.'),
            prepareTableColumn('account_number', 'Account No.', link:'route("admin.users.detail", $item->user_id)'),
            prepareTableColumn('plan_name', 'Plan'),
            prepareTableColumn('interest_rate', 'Rate', '$item->interest_rate."%"', filter: 'range'),
            prepareTableColumn('per_installment', 'Installment', 'showAmount($item->per_installment)'),
            prepareTableColumn('installment_interval', 'Interval', '$item->installment_interval ." Days"'),
            prepareTableColumn('next_installment_date', 'Next Installment', 'showDateTime("$item->next_installment_date", "d M, Y")', filter: 'date'),
            prepareTableColumn('created_at', 'Opened At', 'showDateTime("$item->created_at", "d M, Y")', filter: 'date'),
            prepareTableColumn('locked_date', 'Lock-In Period', 'showDateTime("$item->locked_date", "d M, Y")', filter: 'date'),
            prepareTableColumn('due_installment', 'Due Installments', '$item->dueInstallment()', sortable:false),
            prepareTableColumn('closed_at', 'Closed On', '$item->closed_at ? showDateTime("$item->closed_at", "d M, Y") : "..."', filter: 'date'),
            prepareTableColumn('status', 'Status', '$item->status_badge', echoable:true)
        ]);

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => can('admin.fdr.due.pay') || can('admin.fdr.installments'),
            'buttons' => [
                [
                    'name' => 'Pay Due',
                    'show' => 'can("admin.fdr.due.pay") && $item->status != Status::FDR_CLOSED &&  $item->next_installment_date < today()',
                    'class' => 'paymentBtn',
                    'attributes' => [
                        'data-per_installment' => 'showAmount($item->per_installment)',
                        'data-installments' => '$item->dueInstallment()',
                        'data-amount' => 'showAmount($item->dueAmount())',
                        'data-action' => 'route("admin.fdr.due.pay", $item->id)'
                    ]
                ],
                [
                    'name' => 'Installments',
                    'link' => 'route("admin.fdr.installments", $item->id)',
                    'show' => "can('admin.fdr.installments')",
                ],
            ],
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$fdrs" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" searchPlaceholder="FDR No. / Account No." />

    <div class="modal fade" id="paymentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">@lang('Pay Due Installments')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="la la-times"></i>
                    </button>
                </div>

                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between flex-wrap">
                                <span>@lang('Delayed Installments')</span>
                                <span class="delayed-installments"></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between flex-wrap">
                                <span>@lang('Per Installment')</span>
                                <span class="per-installment"></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between flex-wrap">
                                <span>@lang('Total Amount')</span>
                                <span class="installment-amount"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--dark btn-sm" data-bs-dismiss="modal" type="button">@lang('Cancel')</button>
                        <button class="btn btn--primary btn-sm" type="submit">@lang('Pay All')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.paymentBtn').on('click', function() {
                let modal = $('#paymentModal');
                let data = $(this).data();
                let form = modal.find('form')[0];
                form.action = data.action;
                modal.find('.delayed-installments').text(data.installments);
                modal.find('.per-installment').text(data.per_installment);
                modal.find('.installment-amount').text(data.amount);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
