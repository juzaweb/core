<!-- Sidebar Menu -->
<nav class="mt-2">
    @php
        $menus = \Juzaweb\Modules\Core\Facades\Menu::getByPosition($menu);
        $roots = $menus->whereNull('parent')->sortBy('priority');
        $dashboardPath = request()->is('network/*') ? '/network' : parse_url(admin_url(), PHP_URL_PATH);
    @endphp

    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach ($roots as $root)
            @php
                $children = $menus->where('parent', $root['key'])->sortBy('priority');

                // Extract path from URL for comparison
                $rootPath = ltrim(parse_url($root['url'], PHP_URL_PATH) ?: '', '/');

                $active =
                    request()->is($rootPath) ||
                    ($rootPath && $rootPath != ltrim($dashboardPath, '/') && request()->is($rootPath . '/*')) ||
                    $children
                        ->filter(function ($child) {
                            $childPath = ltrim(parse_url($child['url'], PHP_URL_PATH) ?: '', '/');
                            return request()->is($childPath) || ($childPath && request()->is($childPath . '/*'));
                        })
                        ->isNotEmpty();
            @endphp
            <li class="nav-item @if ($active) menu-is-opening menu-open @endif">
                <a href="{{ $root['url'] }}" target="{{ $root['target'] }}"
                   class="nav-link @if ($active) active @endif">
                    <i class="nav-icon {{ $root['icon'] }}"></i>
                    <p>
                        {{ $root['title'] }}
                        @if ($children->isNotEmpty())
                            <i class="right fas fa-angle-left"></i>
                        @endif
                    </p>
                </a>
                @if ($children->isNotEmpty())
                    <ul class="nav nav-treeview">
                        @foreach ($children as $child)
                            @php
                                $childPath = ltrim(parse_url($child['url'], PHP_URL_PATH) ?: '', '/');
                                $active = request()->is($childPath) || ($childPath && request()->is($childPath . '/*'));
                            @endphp
                            <li class="nav-item">
                                <a href="{{ $child['url'] }}"
                                   class="nav-link @if ($active) active @endif">
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
