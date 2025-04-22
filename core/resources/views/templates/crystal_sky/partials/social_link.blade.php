@php
    $socialLinks = getContent('social_link.element', orderById: true);
@endphp
@foreach ($socialLinks as $social)
    <li class="follow-social-list__item">
        <a target="_blank" href="{{ $social->data_values->social_link }}" class="follow-social-list__link">
            @php echo $social->data_values->social_icon; @endphp
        </a>
    </li>
@endforeach
