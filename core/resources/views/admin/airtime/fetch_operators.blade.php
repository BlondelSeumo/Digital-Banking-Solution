@extends('admin.layouts.app')

@push('topBar')
    @include('admin.airtime.top_bar')
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card viser--table b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--lg table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="check-all">
                                        <label for="check-all" class="ms-1 mb-0">@lang('Name')</label>
                                    </th>
                                    <th>@lang('Bundle')</th>
                                    <th>@lang('Data')</th>
                                    <th>@lang('Pin')</th>
                                    <th>@lang('Local Amount')</th>
                                    <th>@lang('Denomination Type')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $counter = 0;
                                @endphp
                                @foreach ($reloadlySupportedOperators as $item)
                                    @if (!$country->operators->where('unique_id', $item->operatorId)->first())
                                        @php
                                            $counter++;
                                            unset($item->id);
                                            unset($item->country);
                                        @endphp

                                        <tr>
                                            <td>
                                                <input type="checkbox" name="operators[]" class="operatorId"  value="{{ $item->operatorId }}" id="operator-{{ $item->operatorId }}" form="confirmation-form">
                                                <label for="operator-{{ $item->operatorId }}" class="ms-1 mb-0">{{ $item->name }}</label>
                                            </td>
                                            <td>@php echo showBadge($item->bundle) @endphp</td>
                                            <td>@php echo showBadge($item->data) @endphp</td>
                                            <td>@php echo showBadge($item->pin) @endphp</td>
                                            <td>@php echo showBadge($item->supportsLocalAmounts) @endphp</td>
                                            <td>{{ $item->denominationType }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline--dark detailBtn" data-resource="{{ json_encode($item) }}">
                                                    <i class="las la-eye me-0"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                @if ($counter == 0)
                                    <tr>
                                        <td class="text-center" colspan="100%">@lang('No more operators available for this country')</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                @lang('International Discount')
                                <i class="las la-info-circle text--info" title="@lang('These are discounts applied when user are making a top-up to a mobile number registered in any country besides the country your Reloadly account.')"></i>
                            </span>
                            <span class="internationalDiscount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Local Discount')
                                <i class="las la-info-circle text--info" title="@lang('These discounts are applicable to top-ups made to a mobile number that is registered in the same country of origin as your Reloadly account.')"></i>
                            </span>
                            <span class="localDiscount"></span>
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
                                @lang('Most Popular Local Amount')
                                <i class="las la-info-circle text--info" title="@lang('The most popular local top-up amount for this specific operator.')"></i>
                            </span>
                            <span class="mostPopularLocalAmount"></span>
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
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Local Minimum Amount')
                                <i class="las la-info-circle text--info" title="@lang('If the denomination type is set to a range and users select the same origin number as your Reloadly account, they will need to top up at least the minimum amount specified.')"></i>
                            </span>

                            <span class="localMinAmount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Local Max Amount')
                                <i class="las la-info-circle text--info" title="@lang('If the denomination type is set to a range and users select the same origin number as your Reloadly account, they can top up the minimum amount specified.')"></i>
                            </span>
                            <span class="localMaxAmount"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Geographical Recharge Plans')</span>
                            <span class="geographicalRechargePlans"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Status')</span>
                            <span class="status"></span>
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

    @can('admin.airtime.operators.save')
        <x-confirmation-modal />
    @endcan
@endsection

@can(['admin.airtime.operators', 'admin.airtime.operators.save'])
    @push('breadcrumb-plugins')
        @can('admin.airtime.operators.save')
            <button type="button" class="btn btn-sm btn--success d-none confirmationBtn" data-question="@lang('Are You sure to add this operators?')" data-action="{{ route('admin.airtime.operators.save', $country->iso_name) }}"> <i class="lab la-telegram-plane"></i>@lang('Add Selected Operators')</button>
        @endcan
        @can('admin.airtime.operators')
            <x-back route="{{ route('admin.airtime.operators', $country->iso_name) }}" />
        @endcan
    @endpush
@endcan

@push('script')
    <script>
        "use strict";

        (function($) {
            $("#check-all").on('click', function() {
                if ($(this).is(':checked')) {
                    $(".operatorId").prop('checked', true);
                } else {
                    $(".operatorId").prop('checked', false);
                }
                updateDOM();
            });

            $(".operatorId").on('change', function() {
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
                let senderCur = resource.senderCurrencyCode;
                let destinationCur = resource.destinationCurrencyCode;

                modal.find('.name').text(resource.name);

                modal.find('.bundle').html(showBadge(resource.bundle));
                modal.find('.data').html(showBadge(resource.data));
                modal.find('.pin').html(showBadge(resource.pin));

                modal.find('.supportsLocalAmounts').html(showBadge(resource.supportsLocalAmounts));

                modal.find('.supportsGeographicalRechargePlans').html(showBadge(resource.supportsGeographicalRechargePlans));

                modal.find('.denominationType').text(resource.denominationType);

                modal.find('.destinationCurrencyCode').text(destinationCur);
                modal.find('.destinationCurrencySymbol').text(resource.destinationCurrencySymbol);

                modal.find('.internationalDiscount').text(`${resource.internationalDiscount}%`);
                modal.find('.localDiscount').text(`${resource.localDiscount}%`);
                modal.find('.mostPopularAmount').text(resource.mostPopularAmount ? `${resource.mostPopularAmount} ${senderCur}` : '--');
                modal.find('.mostPopularLocalAmount').text(resource.mostPopularLocalAmount ? `${resource.mostPopularLocalAmount} ${destinationCur}` : '--');

                modal.find('.minAmount').text(resource.minAmount ? `${resource.minAmount} ${senderCur}` : '--');
                modal.find('.maxAmount').text(resource.maxAmount ? `${resource.maxAmount} ${senderCur}` : '--');

                modal.find('.localMinAmount').text(resource.localMinAmount ? `${resource.localMinAmount} ${destinationCur}` : '--');
                modal.find('.localMaxAmount').text(resource.localMaxAmount ? `${resource.localMaxAmount} ${destinationCur}` : '--');

                modal.find('.fixedAmounts').html(showAmountData(resource.fixedAmountsDescriptions, resource.fixedAmounts, senderCur));
                modal.find('.localFixedAmounts').html(showAmountData(resource.localFixedAmountsDescriptions, resource.localFixedAmounts, destinationCur));
                modal.find('.suggestedAmounts').html(showAmountData(resource.suggestedAmountsMap, resource.suggestedAmounts, senderCur));

                modal.find('.geographicalRechargePlans').text(showArrayData(resource.geographicalRechargePlans));
                modal.find('.status').text(resource.status);

                modal.find('.modal-title').text(resource.name);
                modal.modal('show');
            });

            function showAmountData(obj, arr, curText) {
                var html = '';
                if (!jQuery.isEmptyObject(obj)) {
                    html += `<li class="list-group-item px-0 d-flex justify-content-between flex-wrap gap-1">
                            <span>@lang('Amount')</span>
                            <span>@lang('Description')</span>
                        </li>`;

                    $.each(obj, function(key, value) {
                        html += `<li class="list-group-item px-0 d-flex justify-content-between flex-wrap gap-1">
                                <span>${key} ${curText}</span>
                                <span>${value}</span>
                            </li>`;
                    });
                } else if (arr.length > 0) {
                    html += `<li class="list-group-item px-0"><span>${arr.join(` ${curText}, `)} ${curText}</span></li>`;

                } else {
                    html = '--';
                }

                return html;
            }


            function showArrayData(arr, curText = null) {
                if (arr.length < 1) {

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
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        tr.already-exist {
            background-color: #ebebeb;
        }

        .amount_descriptions {
            padding: 10px 0;
            border-top: 1px solid #ebebeb;
        }

        .amount_descriptions:last-child {
            border-bottom: none;
        }
    </style>
@endpush
