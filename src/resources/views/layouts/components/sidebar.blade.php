<!-- Sidebar Menu -->
<nav class="mt-2">
    @php
        $menus = \Juzaweb\Core\Facades\Menu::get('admin-left');
        $roots = $menus->whereNull('parent');
    @endphp
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
        <li class="nav-item">
            @foreach($roots as $root)
                @php
                    $children = $menus->where('parent', $root['key']);
                @endphp
                <a href="{{ $root['url'] }}" class="nav-link">
                    <i class="nav-icon fas {{ $root['icon'] }}"></i>
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
                        <li class="nav-item">
                            <a href="{{ $child['url'] }}" class="nav-link">
                                <i class="nav-icon fas {{ $child['icon'] }}"></i>
                                <p>{{ $child['title'] }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @endif
            @endforeach
        </li>

    </ul>
</nav>
<!-- /.sidebar-menu -->