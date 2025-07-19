<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link text-primary" target="_blank">{{ __('View Website') }}</a>
        </li>
        {{--<li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>--}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        {{--<li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>--}}

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-language"></i>
                <span class="badge badge-success navbar-badge text-uppercase">
                    {{ app()->getLocale() }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                @foreach(\Juzaweb\Core\Models\Language::languages() as $locale => $language)
                    @if(! $loop->first)
                        <div class="dropdown-divider"></div>
                    @endif

                    <a href="{{ request()->fullUrlWithQuery(['hl' => $locale]) }}" class="dropdown-item">
                        <i class="fi fi-{{ $language->country }} mr-2"></i> {{ $language->name }}
                    </a>
                @endforeach
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">
                    {{ auth()->user()->unreadNotifications()->count() }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    {{ __(':num Notifications', ['num' => auth()->user()->notifications()->count()]) }}
                </span>

                @foreach(auth()->user()->notifications()->limit(5)->get() as $notification)
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-{{ $notification->data['icon'] ?? 'info' }} mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">
                            {{ $notification->created_at?->diffForHumans() }}
                        </span>
                    </a>
                @endforeach

                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">{{ __('See All Notifications') }}</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                <img
                    src="https://1.gravatar.com/avatar/7162c5aa667c497c4d1b90b36c60eaea?s=32&d=mm&r=g"
                    alt="User Avatar"
                    class="img-size-32 img-circle"
                >
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ admin_url('/profile') }}" class="dropdown-item">
                    <i class="fas fa-user-cog mr-2"></i> {{ __('Profile') }}
                </a>
                <a href="{{ admin_url('/settings') }}" class="dropdown-item">
                    <i class="fas fa-cog mr-2"></i> {{ __('Settings') }}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="javascript:void(0)"
                   onclick="$('.form-logout').submit()">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Logout') }}
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        {{-- <li class="nav-item">
             <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                 <i class="fas fa-th-large"></i>
             </a>
         </li>--}}
    </ul>
</nav>
<!-- /.navbar -->