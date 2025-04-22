@extends('admin.layouts.app')

@push('topBar')
    @include('admin.airtime.top_bar')
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--lg table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="check-all">
                                        <label for="check-all" class="ms-1 mb-0">@lang('Name')</label>
                                    </th>
                                    <th>@lang('ISO')</th>
                                    <th>@lang('Continent')</th>
                                    <th>@lang('Calling Codes')</th>
                                    <th>@lang('Currency Name')</th>
                                    <th>@lang('Currency Code')</th>
                                    <th>@lang('Currency Symbol')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $counter = 0;
                                @endphp
                                @foreach ($apiCountries as $item)
                                    @if (!$countries->where('iso_name', $item->isoName)->first())
                                        @php
                                            $counter++;
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="countries[]" class="isoName"  value="{{ $item->isoName }}" id="country-{{ $item->isoName }}" form="confirmation-form">
                                                <label for="country-{{ $item->isoName }}" class="ms-1 mb-0">{{ $item->name }}</label>
                                            </td>
                                            <td>{{ $item->isoName }}</td>
                                            <td>{{ $item->continent }}</td>
                                            <td>{{ implode(', ', $item->callingCodes) }}</td>
                                            <td>{{ $item->currencyName }}</td>
                                            <td>{{ $item->currencyCode }}</td>
                                            <td>{{ $item->currencySymbol }}</td>
                                        </tr>
                                    @endif
                                @endforeach

                                @if ($counter == 0)
                                    <tr>
                                        <td class="text-center" colspan="100%">@lang('No country available')</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('admin.airtime.countries.save')
        <x-confirmation-modal />
        <button type="button" class="btn btn-sm btn--success d-none confirmationBtn" data-question="@lang('Are you sure to add this countries?')" data-action="{{ route('admin.airtime.countries.save') }}"> <i class="lab la-telegram-plane"></i>@lang('Add Selected Countries')</button>
    @endcan

@endsection

@can('admin.airtime.countries')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.airtime.countries') }}" />
    @endpush
@endcan

@push('script')
    <script>
        "use strict";

        (function($) {

            $("#check-all").on('click', function() {

                if ($(this).is(':checked')) {
                    $(".isoName").prop('checked', true);
                } else {
                    $(".isoName").prop('checked', false);
                }
                updateDOM();
            });

            $(".isoName").on('change', function() {
                updateDOM();
            })

            function updateDOM() {
                if ($('.isoName:checked').length > 0) {
                    $('.confirmationBtn').removeClass('d-none');
                } else {
                    $('.confirmationBtn').addClass('d-none');
                }
            }

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .confirmationBtn {
            bottom: 50px;
            max-width: 224px;
            left: 50%;
            height: 47px;
            font-size: 15px;
            font-weight: 600;
            position: fixed;
            transform: translateX(-50%);
        }
    </style>
@endpush
