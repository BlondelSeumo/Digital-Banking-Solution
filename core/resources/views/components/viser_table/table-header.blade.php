@props(['renderColumns', 'action'])


<thead>
    <tr>
        @foreach ($renderColumns as $column)
            <th data-key="{{ $column['id'] }}">{{ __($column['name']) }}</th>
        @endforeach

        @if ($action['show'])
            <th>
                {{ __($action['name']) }}
            </th>
        @endif
    </tr>
</thead>
