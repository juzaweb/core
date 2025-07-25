<!-- Sidebar Menu -->
<nav class="mt-2">
    @php
        $menus = \Juzaweb\Core\Facades\Menu::get('admin-left');
        $roots = $menus->whereNull('parent')->sortBy('priority');
    @endphp

    <ul class="nav nav-pills nav-sidebar flex-column"
        data-widget="treeview"
        role="menu"
        data-accordion="false"
    >
        @foreach($roots as $root)
            @php
                $children = $menus->where('parent', $root['key'])->sortBy('priority');
                $active = request()->is(ltrim($root['url'], '/'))
                    || ($root['url'] != '/' . config('core.admin_prefix') && request()->is(ltrim($root['url'], '/') . '/*'))
                    || $children->filter(
                        fn ($child) => request()->is(ltrim($child['url'], '/'))
                            || request()->is(ltrim($child['url'], '/') . '/*')
                    )->isNotEmpty();
            @endphp
            <li class="nav-item @if($active) menu-is-opening menu-open @endif">
                <a href="{{ $root['url'] }}"
                   target="{{ $root['target'] }}"
                   class="nav-link @if($active) active @endif"
                >
                    <i class="nav-icon {{ $root['icon'] }}"></i>
                    <p>
                        {{ $root['title'] }}
                        @if($children->isNotEmpty())
                            <i class="right fas fa-angle-left"></i>
                        @endif
                    </p>
                </a>
                @if($children->isNotEmpty())
                    <ul class="nav nav-treeview">
                        @foreach($children as $child)
                            @php
                            $active = request()->is(ltrim($child['url'], '/'))
                                || request()->is(ltrim($child['url'], '/') . '/*');
                            @endphp
                            <li class="nav-item">
                                <a href="{{ $child['url'] }}" class="nav-link @if($active) active @endif">
                                    <i class="nav-icon far {{ $child['icon'] ?? 'fa-circle' }}"></i>
                                    <p>{{ $child['title'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
<!-- /.sidebar-menu -->