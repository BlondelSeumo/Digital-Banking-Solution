@props(['action', 'item'])

@isset($action['buttons'])
    @if ($action['style'] == 'dropdown')
        <div class="dropdown">
            <button aria-expanded="false" class="btn btn-sm btn--light" data-bs-toggle="dropdown" type="button">
                <i class="las la-ellipsis-v m-0"></i>
            </button>
            <div class="dropdown-menu">
                @foreach (@$action['buttons'] as $button)
                    @if (eval('return ' . @$button['show'] . ';'))
                        <a href="@if (isset($button['link'])) {{ eval('echo ' . $button['link'] . ';') }}@else # @endif" class="dropdown-item {{ @$button['class'] }}"

                        @foreach ($button['attributes'] ?? [] as $attribute => $value)
                            {{ $attribute}}="{{ eval("return $value;")}}"
                        @endforeach

                        >
                            @isset($button['icon'])
                                <i class="{{ $button['icon'] }}"></i>
                            @endisset
                            {{ __($button['name']) }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @else
        @foreach ($action['buttons'] as $button)
            @if (eval('return ' . $button['show'] . ';'))
                <a
                    href="@if (isset($button['link'])) {{ eval('echo ' . $button['link'] . ';') }}@else # @endif"
                    class="btn btn-sm {{ @$button['class'] }}"
                    @foreach ($button['attributes'] ?? [] as $attribute => $value)
                        {{ $attribute}}="{{ eval("return $value;")}}"
                    @endforeach
                >

                    @isset($button['icon'])
                        <i class="{{ $button['icon'] }}"></i>
                    @endisset

                    {{ __($button['name']) }}
                </a>
            @endif
        @endforeach
    @endif
@endisset
