@aware(['columns', 'tableName', 'visibleColumns', 'columnConfig', 'searchPlaceholder'])
@props(['filterable', 'rows', 'sortable', 'exportable', 'searchable'])

@php
    $request = request();
@endphp

<div class="align-items-start d-flex flex-wrap flex-md-nowrap justify-content-between gap-3 table-options">
    <div class="d-flex flex-wrap gap-2 gap-md-3 align-items-center">

        {{-- Dynamic Pagination / Per Page --}}
        @if ($rows)
            @php
                $perPage = $request->per_page?? gs('paginate_number');
            @endphp

            <div class="d-inline-flex align-items-center">
                <select name="per_page" class="form-select">
                    <option value="" disabled selected>@lang('Rows')</option>
                    @for ($i = 5; $i <= 200; $i+=5)
                        <option value="{{$i}}" @selected($perPage == $i)>{{$i}}</option>
                    @endfor
                </select>
            </div>
        @endif

        {{-- Order Data --}}
        @if ($sortable)
            <div class="d-inline-flex align-items-center">
                @php
                    $orderByColumns = $columns->whereIn('id', $visibleColumns)->where('sortable', true)->all();
                @endphp

                <div class="input-group flex-nowrap">
                    <select name="order_by_column" class="order-data form-select">
                        <option value="" disabled selected><i class="la la-sort"></i>@lang('Sort By')</option>
                        @foreach ($orderByColumns as $column)
                            <option value="{{ $column['id'] }}" @selected(@$request->order_by_column == $column['id'])>{{ __($column['name']) }}</option>
                        @endforeach
                    </select>

                    <select name="order_by" class="order-data form-select">
                        <option value="desc" @selected(@$request->order_by == 'desc')>@lang('Desc')</option>
                        <option value="asc" @selected(@$request->order_by == 'asc')>@lang('Asc')</option>
                    </select>

                    @if($request->has('order_by_column') || $request->has('order_by'))
                        <button class="input-group-text clearOrderBy" href=""><i class="la la-redo-alt"></i></button>
                    @endif
                </div>
            </div>


        @endif

        @if ($exportable)
            <button class="table-option-btn" id="exportBtn"> <i class="la la-download"></i> @lang('Export')</button>
        @endif

        @if ($columnConfig)
            <div class="dropdown text--14">
                <button aria-expanded="false" class="table-option-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="la la-eye"></i>
                    @lang('Columns')
                </button>

                <div class="dropdown-menu p-3">
                    <form action="{{ route('admin.table.configure') }}" method="post">
                        @csrf
                        <input type="hidden" name="name" value="{{ @$tableName }}">

                        @foreach ($columns as $column)
                            <div class="form-check">
                                <input type="checkbox" name="visible_columns[]" id="column-{{ $column['id'] }}" class="form-check-input" value="{{ $column['id'] }}" checked>
                                <label class="form-check-label" for="column-{{ $column['id'] }}">{{ $column['name'] }}</label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn--primary px-3 w-100 mt-2">@lang('Apply')</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    @if ($columnConfig || $searchable || $filterable)
        <div class="d-flex gap-2 gap-md-3 justify-content-between align-items-center flex-grow-1 flex-md-grow-0 flex-shrink-0 flex-row-reverse flex-md-row">

            @if ($searchable)
                <x-search-form btn="input-group-text py-0 btn--light border" :placeholder="@$searchPlaceholder" />
            @endif

            @if ($filterable)
                <button class="table-option-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="la la-filter"></i> @lang('Filter')</button>
            @endif
        </div>
    @endif
</div>

@if ($filterable)
    <x-viser_table.table-filter />
@endif

@if ($exportable)
    <div class="modal fade" id="exportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exportModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">@lang('Export Data')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="tableExportForm">
                        <input type="hidden" name="file_name" value="{{ $tableName }}">
                        <div class="mb-3">
                            <label>@lang('Select Columns')</label>
                            <div class="columns-container"></div>
                        </div>
                        <div class="mb-3">
                            <label for="export-as">@lang('Export As')</label>
                            <select class="form-control" name="export_type" id="export-as" required>
                                <option value="" hidden selected>@lang('Select One')</option>
                                <option type="option" value="excel">@lang('Excel')</option>
                                <option type="option" value="csv">@lang('CSV')</option>
                                <option type="option" value="pdf">@lang('PDF')</option>
                                <option type="button" value="print">@lang('Print')</option>
                            </select>
                        </div>

                        <div class="pdf-configuration d-none">
                            <div class="mb-3">
                                <label for="export-as">
                                    @lang('Heading') <i class="la la-info-circle" title="@lang('The Heading will be displayed in the top of the first page')"></i>
                                </label>
                                <input type="text" class="form-control" name="heading">
                            </div>

                            <div class="mb-3">
                                <label for="page-size">@lang('Page Size')</label>
                                <select class="form-control" name="page_size" id="page-size" required>
                                    <option value="" hidden selected>@lang('Select One')</option>
                                    <option type="option" value="a0">@lang('A0')</option>
                                    <option type="option" value="a1">@lang('A1')</option>
                                    <option type="option" value="a2">@lang('A2')</option>
                                    <option type="option" value="a3">@lang('A3')</option>
                                    <option type="button" value="a4" selected>@lang('A4')</option>
                                    <option type="button" value="a5">@lang('A5')</option>
                                    <option type="button" value="a6">@lang('A6')</option>
                                    <option type="button" value="a7">@lang('A7')</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="export-as">@lang('Table Header Color')</label>
                                <input type="color" name="heading_color" class="form-control" value="#4634ff">
                            </div>

                            <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <label for="export-as">@lang('Orientation')</label>
                                    <div class="form-check">
                                        <input type="radio" name="orientation" value="portrait" class="form-check-input" id="portrait-orientation" checked>
                                        <label class="form-check-label" for="portrait-orientation">
                                            @lang('Portrait')
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio" name="orientation" value="landscape" class="form-check-input" id="landscape-orientation">
                                        <label class="form-check-label" for="landscape-orientation">
                                            @lang('Landscape')
                                        </label>
                                    </div>

                                </div>
                                <div class="orientation-sample portrait"></div>
                            </div>

                            <div class="mb-3">
                                <label for="export-as">@lang('Font Size')</label>
                                <input type="range" name="font_size" min="6" max="30" step="1" value="12" class="form-range">
                                <div class="sample-text"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Export')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/xlsx.full.min.js') }} "></script>
    <script src="{{ asset('assets/admin/js/vendor/jspdf.umd.min.js') }} "></script>
    <script src="{{ asset('assets/admin/js/vendor/jspdf.plugin.autotable.min.js') }} "></script>
    <script src="{{ asset('assets/admin/js/visertable.js') }}" type="module"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $(`[name="visible_columns[]"]`).val(@json($visibleColumns));
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .table-option-btn {
            padding: 8px 8px;
            margin: 0;
            background: transparent;
            border: 1px solid #ced4da;
            font-size: 14px;
            border-radius: 5px;
            color: #6c757d;
            line-height: 1;
            transition: all linear 0.3s;
        }

        .table-options input.form-control,
        .table-options select.form-control {
            height: 32px !important;
            border: 1px solid #ced4da !important;
            padding-inline: 12px;
            font-size: 14px;
            color: #6c757d;
        }

        .table-option-btn:hover {
            border-color: #4634ff;
        }

        .table-options select {
            font-size: 14px;
            padding: 8px 8px;
            line-height: 1;
            padding-right: 30px;
            width: max-content !important;
            color: #6c757d;
            background-position: right .5rem center;
            background-blend-mode: hard-light;
        }

        .table-options select:focus,
        .table-options input.form-control:focus {
            box-shadow: none;
            border-color: #4634ff !important;
        }

        .top-filter-title {
            font-size: 13px;
        }

        input[type="range"]:active,
        input[type="range"]:focus {
            box-shadow: none;
        }

        .orientation-sample.portrait {
            width: 40px;
            height: 65px;
            border: 1px solid #cecece;
        }

        .orientation-sample.landscape {
            height: 40px;
            width: 65px;
            border: 1px solid #cecece;
        }

        @media(max-width: 1199px){
            .table-options select{
                font-size: 12px;
                padding: 6px 6px;
                padding-right: 24px;
                background-position: right .3rem center;
            }

            .table-option-btn {
                padding: 6px 6px;
                font-size: 12px;
            }

            .table-options input.form-control, .table-options select.form-control {
                height: 30px !important;
                max-width: 140px;
            }
        }

    </style>
@endpush
