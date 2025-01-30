@props(['active' => false, 'menuHeading' => null, 'isNew' => null, 'hasSub' => null])

@isset($menuHeading)
    <li class="nk-menu-heading">
        <h6 class="overline-title text-primary-alt">{{ $menuHeading }}</h6>
    </li>
@endisset
<li
    class="nk-menu-item {{ $active ? 'active current-page' : '' }} @isset($hasSub) has-sub @endisset">
    <a class="nk-menu-link @isset($hasSub) nk-menu-toggle @endisset"
        aria-current="{{ $active ? 'page' : 'false' }}" {{ $attributes }}>
        <span class="nk-menu-icon">
            {{ $icon }}
        </span>
        <span class="nk-menu-text">
            {{ $slot }}
        </span>
        @isset($isNew)
            <span class="nk-menu-badge badge-warning">New</span>
        @endisset
    </a>
    @isset($hasSub)
        <ul class="nk-menu-sub">
            {{ $singleNavItem }}
        </ul>
    @endisset
</li>

