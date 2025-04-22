@props(['renderColumns', 'item'])

@foreach ($renderColumns as $index => $column)
    <td data-key="{{ $column['id'] }}" @if ($column['className']) class="{{ eval('echo ' . $column['className'] . ';') }}" @endif>

        @if (isset($column['link']))
            @php
                preg_match('/route\("([^"]+)",/', $column['link'], $matches);
            @endphp

            @can($matches[1])
                <a href="{{ eval('echo ' . $column['link'] . ';') }}">{{ eval($column['value'] . ';') }}</a>
            @else
                {{ eval($column['value'] . ';') }}
            @endcan
        @elseif(isset($column['url']))
            <a target="blank" href="{{ eval('return ' . $column['url'] . ';') }}">{{ eval($column['value'] . ';') }}</a>
        @else
            {{ eval($column['value'].';') }}
        @endif

    </td>
@endforeach
