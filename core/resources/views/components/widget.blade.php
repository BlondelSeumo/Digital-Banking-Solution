@props([
    'style' => 1,
    'link' => null,
    'title' => null,
    'value' => null,
    'heading' => null,
    'subheading' => null,
    'icon' => null,
    'bg' => null,
    'color' => null,
    'icon_color' => null,
    'icon_style' => 'outline',
    'overlay_icon' => 1,
    'cover_cursor' => 0,
    'outline' => false,
    'type' => 1,
    'viewMoreIcon' => true,
    'query_string' => null,
    'parameters' => null,
    'box_shadow' => true
])

@php
    $iconColor = $icon_color ?? $color;
    $widget = 'x-widget-' . $style;

    if ($link && can($link) && $link != 'javascript:void(0)') {
        $link = route($link, $parameters);
        $link = $query_string ? $link . '?' . $query_string : $link;
    } else {
        $link = null;
    }
@endphp

@if ($style == 1)
    <x-widget-1 :link=$link :title=$title :value=$value :icon=$icon :bg=$bg :color=$color :icon_color=$icon_color />
@endif

@if ($style == 2)
    <x-widget-2 :link=$link :title=$title :box_shadow=$box_shadow :value=$value :heading=$heading :subheading=$subheading :icon=$icon :bg=$bg :color=$color :icon_color=$icon_color :icon_style=$icon_style :overlay_icon=$overlay_icon :cover_cursor=$cover_cursor />
@endif

@if ($style == 3)
    <x-widget-3 :link=$link :title=$title :value=$value :icon=$icon :bg=$bg :color=$color />
@endif
@if ($style == 4)
    <x-widget-4 :link=$link :title=$title :value=$value :bg=$bg :color=$color />
@endif
@if ($style == 5)
    <x-widget-5 :link=$link :title=$title :value=$value :icon=$icon :bg=$bg />
@endif
@if ($style == 6)
    <x-widget-6 :link=$link :title=$title :value=$value :icon=$icon :bg=$bg :outline=$outline :heading=$heading :subheading=$subheading :viewMoreIcon=$viewMoreIcon />
@endif
@if ($style == 7)
    <x-widget-7 :link=$link :title=$title :value=$value :icon=$icon :bg=$bg :outline=$outline :type=$type />
@endif
