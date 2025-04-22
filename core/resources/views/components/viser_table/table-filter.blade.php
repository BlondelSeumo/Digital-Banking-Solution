@aware(['columns'])

@php
    $request = request();
@endphp

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">@lang('Filter Data')</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        @if (request()->has('filter') || request()->has('range_filter') || request()->has('date_filter'))
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn--dark" id="clearFilter">
                    @lang('Clear Filter')
                </button>
            </div>
        @endif

        <form action="" method="get" id="filterForm">
            @foreach (collect($columns)->whereNotNull('filter') as $column)
                @php
                    $column = (object) $column;
                @endphp

                @if ($column->filter == 'text' || $column->filter == 'number')
                    <div class="mb-3 filter-field position-relative">
                        <label>{{ __($column->name) }}</label>
                        <input type="{{ $column->filter }}" name="filter[{{ $column->id }}]" value="{{ @$request->filter[$column->id] }}" class="form-control">
                    </div>
                @elseif($column->filter == 'select' && !blank($column->filter_options))
                    <div class="mb-3 filter-field position-relative">
                        <label>{{ __($column->name) }}</label>
                        <select name="filter[{{ $column->filter_column }}]" class="form-control">
                            <option value="">@lang('All')</option>
                            @if ($column->filter_options && is_assoc(@$column->filter_options))
                                @foreach ($column->filter_options as $key => $option)
                                    <option value="{{ @$key }}" @selected($request->has('filter.' . $column->filter_column) && $request->filter[$column->filter_column] == $key)>{{ __($option) }}</option>
                                @endforeach
                            @else
                                @foreach ($column->filter_options as $option)
                                    <option value="{{ @$option }}" @if ($request->filter && @$request->filter[$column->filter_column]) @selected(@$request->filter[$column->filter_column] == $option) @endif>{{ __($option) }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @elseif($column->filter == 'range')
                    <div class="mb-3 filter-field position-relative">
                        <label>{{ __($column->name) }}</label>
                        <div class="input-group">
                            <input type="number" step="any" name="range_filter[{{ $column->id }}][min]" value="{{ @$request['range_filter'][$column->id]['min'] }}" class="form-control" placeholder="@lang('Min')">

                            <input type="number" step="any" name="range_filter[{{ $column->id }}][max]" value="{{ @$request['range_filter'][$column->id]['max'] }}" class="form-control" placeholder="@lang('Max')">
                        </div>
                    </div>
                @elseif($column->filter == 'date')
                    <div class="mb-3 filter-field position-relative">
                        <label>{{ __($column->name) }}</label>
                        <input type="text" name="date_filter[{{ $column->id }}]" value="{{ @$request->date_filter[$column->id] }}" class="form-control date-range" autocomplete="off">
                    </div>
                @endif
            @endforeach
        </form>
    </div>

    <div class="position-sticky p-3">
        <button type="submit" class="btn btn--primary w-100 h-45" form="filterForm">@lang('Apply Filter')</button>
    </div>
</div>

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush
