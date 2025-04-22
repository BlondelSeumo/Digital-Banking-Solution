@props([
    'data' => collect([]),
    'columns' => [],
    'action' => [
        'show' => true,
        'name' => 'Action',
        'style' => 'dropdown',
        'buttons' => [],
    ],

    'rows' => true,
    'sortable' => true,
    'exportable' => true,
    'searchable' => true,
    'columnConfig' => true,
    'visibleColumns' => [],
])

<div class="card viser--table">
    @php
        $filterable = collect($columns)->whereNotNull('filter')->count() > 0;
        $renderColumns = $columns->whereIn('id', $visibleColumns);
    @endphp

    @if ($rows || $sortable || $exportable || $searchable || $filterable)
        <div class="card-header py-3">
            <x-viser_table.table-options :filterable="$filterable" :rows="$rows" :sortable="$sortable" :exportable="$exportable" :searchable="$searchable" />
        </div>
    @endif

    <div class="card-body p-0 ">
        <div {{ $attributes }}>
            <table class="table--light style--two table" id="viserTable">
                <x-viser_table.table-header :renderColumns="$renderColumns" :action="$action" />

                @if (@$tbody)
                    {{ $tbody }}
                @else
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <x-viser_table.table-data-columns :renderColumns="$renderColumns" :item="$item" />

                                @if ($action['show'])
                                    <td>
                                        <x-viser_table.table-actions :action="$action" :item="$item" />
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </div>
    </div>

    @if (!blank($data) && $data->hasPages())
        <div class="card-footer py-4">
            {{ paginateLinks($data) }}
        </div>
    @endif
</div>
