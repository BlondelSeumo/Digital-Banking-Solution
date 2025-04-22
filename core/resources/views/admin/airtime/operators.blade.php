@extends('admin.layouts.app')
@push('topBar')
    @include('admin.airtime.top_bar')
@endpush
@section('panel')

    @php
        $request = request();
        $tableName = 'airtime_operators';

        $tableConfiguration = $tableConfiguration = tableConfiguration($tableName);
        $statusOptions = ['1' => 'Enabled', '0' => 'Disabled'];
        $binaryOptions = ['1' => 'Yes', '0' => 'No'];
        $denominationTypes = ['FIXED' => 'FIXED', 'RANGE' => 'RANGE'];

        if(!$iso){
            $countries = App\Models\Country::active()->get('name')->pluck('name')->toArray();
        }else {
            $countries = [];
        }

        $columns = collect(array_filter([
            prepareTableColumn('name', 'Name'),
            prepareTableColumn('group_name', 'Group', filter:'select', filterOptions: $operatorGroups),
            !$iso ? prepareTableColumn('country', 'Country', '$item->country', filter: 'select', filterOptions: $countries):null,
            prepareTableColumn('bundle', 'Bundle', 'showBadge($item->bundle)', filter: 'select', filterOptions: $binaryOptions, echoable:true),
            prepareTableColumn('data', 'Data', 'showBadge($item->data)', filter: 'select', filterOptions: $binaryOptions, echoable:true),
            prepareTableColumn('pin', 'Pin', 'showBadge($item->pin)', filter: 'select', filterOptions: $binaryOptions, echoable:true),
            prepareTableColumn('denomination_type', 'Denomination Type', filter: 'select', filterOptions: $denominationTypes),
            prepareTableColumn('status', 'Status', '$item->status_badge', filter: 'select', filterOptions: $statusOptions, echoable: true)
        ]));

        $action = [
            'name' => 'Action',
            'style' => 'dropdown',
            'show' => true,
            'buttons' => [
                [
                    'name' => 'Details',
                    'show' => true,
                    'icon' => 'la la-desktop',
                    'class' => 'detailBtn',
                    'attributes' => [
                        'data-resource'=> 'json_encode($item)'
                    ]
                ],
                [
                    'name' => 'Disable',
                    'show' => 'can("admin.airtime.operator.status") && $item->status',
                    'class' => 'confirmationBtn',
                    'icon' => 'la la-eye-slash',
                    'attributes' => [
                        'data-action' => 'route(\'admin.airtime.operator.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to disable this operator?")',
                    ],
                ],
                [
                    'name' => 'Enable',
                    'show' => 'can("admin.airtime.operator.status") && !$item->status',
                    'class' => 'confirmationBtn',
                    'icon' => 'la la-eye',
                    'attributes' => [
                        'data-action' => 'route(\'admin.airtime.operator.status\', $item->id)',
                        'data-question' => 'trans("Are you sure to enable this operator?")',
                    ],
                ],
            ],
        ];

        if ($tableConfiguration) {
            $visibleColumns = $tableConfiguration->visible_columns;
        } else {
            $visibleColumns = $columns->pluck('id')->toArray();
        }
    @endphp

    <x-viser_table.table :data="$operators" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive" />

    @can('admin.airtime.country.status')
        <x-confirmation-modal />
    @endcan



    <div class="modal" id="infoModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Name')</span>
                            <span class="name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Bundle')</span>
                            <span class="bundle"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Data')</span>
                            <span class="data"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Pin')</span>
                            <span class="pin"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Denomination Type')</span>
                            <span class="denominationType"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Destination Currency Code')</span>
                            <span class="destinationCurrencyCode"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Destination Currency Symbol')</span>
                            <span class="destinationCurrencySymbol"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Most Popular Amount')
                                <i class="las la-info-circle text--info" title="@lang('The most popular international top-up amount for this specific operator.')"></i>
                            </span>
                            <span class="mostPopularAmount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Minimum Amount')
                                <i class="las la-info-circle text--info" title="@lang('If the denomination type is set to a range and users select different origin number from your Reloadly account, they will need to top up at least the minimum amount specified.')"></i>
                            </span>
                            <span class="minAmount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Maximum Amount')
                                <i class="las la-info-circle text--info" title="@lang('If the denomination type is set to a range and users select different origin number from your Reloadly account, they can top up the maximum amount specified.')"></i>
                            </span>
                            <span class="maxAmount"></span>
                        </li>
                    </ul>

                    <div class="amount_descriptions">
                        <div class="heading">
                            <h6>@lang('Fixed Amounts')</h6>
                        </div>
                        <ul class="list-group list-group-flush fixedAmounts"></ul>
                    </div>
                    <div class="amount_descriptions">
                        <div class="heading">
                            <h6>@lang('Local Fixed Amounts')</h6>
                        </div>
                        <ul class="list-group list-group-flush localFixedAmounts"></ul>
                    </div>

                    <div class="amount_descriptions">
                        <div class="heading">
                            <h6>@lang('Suggested Amounts')</h6>
                        </div>
                        <ul class="list-group list-group-flush suggestedAmounts"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('admin.airtime.operator.status')
        <x-confirmation-modal />
    @endcan
@endsection

@push('breadcrumb-plugins')
    @can('admin.airtime.operators.fetch')
        @if ($iso)
            <a href="{{ route('admin.airtime.operators.fetch', $iso) }}" class="btn btn--dark"> <i class="lab la-telegram-plane"></i>
                @if ($operators->count())
                    @lang('Fetch More Operators')
                @else
                    @lang('Fetch Operators')
                @endif
            </a>
        @endif
    @endcan
@endpush

@push('script')
    <script>
        "use strict";

        (function($) {
            $("#check-all").on('click', function() {
                if ($(this).is(':checked')) {
                    $(".operatorId").prop('checked', true);
                    $('.hidden-field').prop('checked', true);
                } else {
                    $(".operatorId").prop('checked', false);
                    $('.hidden-field').prop('checked', false);
                }

                updateDOM();
            });

            $(".operatorId").on('change', function() {
                let operatorId = $(this).data('operator_id');
                let hiddenField = $(`.hidden-field[data-operator_id="${operatorId}"]`);

                if ($(this).is(":checked")) {
                    hiddenField.prop('checked', true);
                } else {
                    hiddenField.prop('checked', false);
                }

                updateDOM();
            })

            function updateDOM() {
                if ($('.operatorId:checked').length > 0) {
                    $('.confirmationBtn').removeClass('d-none');
                } else {
                    $('.confirmationBtn').addClass('d-none');
                }
            }

            $('.detailBtn').on('click', function() {
                let resource = $(this).data('resource');

                let modal = $('#infoModal');
                let systemCurrency = "{{ __(gs('cur_text')) }}";
                let destinationCur = resource.destination_currency_code;

                modal.find('.name').text(resource.name);

                modal.find('.bundle').html(showBadge(resource.bundle));
                modal.find('.data').html(showBadge(resource.data));
                modal.find('.pin').html(showBadge(resource.pin));
                modal.find('.denominationType').text(resource.denomination_type);

                modal.find('.destinationCurrencyCode').text(destinationCur);
                modal.find('.destinationCurrencySymbol').text(resource.destination_currency_symbol);
                modal.find('.mostPopularAmount').text(resource.most_popular_amount ? `${showAmount(resource.most_popular_amount)} ${systemCurrency}` : '--');

                modal.find('.minAmount').text(resource.min_amount ? `${showAmount(resource.min_amount)} ${systemCurrency}` : '--');
                modal.find('.maxAmount').text(resource.max_amount ? `${showAmount(resource.max_amount)} ${systemCurrency}` : '--');

                modal.find('.fixedAmounts').html(showAmountData(resource.fixed_amounts_descriptions, resource.fixed_amounts, systemCurrency));
                modal.find('.localFixedAmounts').html(showAmountData(resource.local_fixed_amounts_descriptions, resource.local_fixed_amounts, destinationCur));
                modal.find('.suggestedAmounts').html(showArrayData(resource.suggested_amounts, systemCurrency));

                modal.find('.modal-title').text(resource.name);
                modal.modal('show');
            });

            function showAmountData(obj, arr, curText) {

                if (obj == null && arr == null) {
                    return '--';
                }

                var html = '';
                if (obj != null && !jQuery.isEmptyObject(obj)) {
                    html += `<li class="list-group-item px-0 d-flex justify-content-between flex-wrap gap-1">
                            <span>@lang('Amount')</span>
                            <span>@lang('Description')</span>
                        </li>`;

                    $.each(obj, function(key, value) {
                        html += `<li class="list-group-item px-0 d-flex justify-content-between flex-wrap gap-1">
                                <span>${showAmount(key)} ${curText}</span>
                                <span>${value}</span>
                            </li>`;
                    });
                } else if (arr != null && arr.length > 0) {
                    html += `<li class="list-group-item px-0"><span>${arr.join(` ${curText}, `)} ${curText}</span></li>`;

                } else {
                    html = '--';
                }

                return html;
            }


            function showArrayData(arr, curText = null) {
                if (arr == null || arr.length < 1) {

                    return '--';
                }

                var html = arr.join(` ${curText}, `);
                html += ' ' + curText;
                return html;
            }

            function showBadge(status) {
                var cls, badgeText;
                if (status) {
                    cls = 'badge badge--success';
                    badgeText = "@lang('Yes')";

                } else {
                    cls = 'badge badge--danger';
                    badgeText = "@lang('No')";
                }

                return `<span class="${cls}">${badgeText}</span>`;
            }

            function showAmount(amount, delimiter = 2) {
                amount = parseFloat(amount);
                if (amount < 1) {
                    return 0;
                }

                return amount.toFixed(delimiter);
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .amount_descriptions {
            padding: 10px 0;
            border-top: 1px solid #ebebeb;
        }

        .amount_descriptions:last-child {
            border-bottom: none;
        }
    </style>
@endpush
