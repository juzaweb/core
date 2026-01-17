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
            <a href="{{ website()->url }}" class="nav-link text-primary"
                target="_blank">{{ __('core::translation.view_website') }}</a>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        {{-- <li class="nav-item">
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
        </li> --}}

        @if (!website()->isMainWebsite())
            <li class="nav-item d-flex align-items-center ml-2">
                <a href="{{ admin_url('/upgrade') }}" class="btn btn-warning btn-sm rounded-pill font-weight-bold px-3">
                    @if (($plan = website()->currentPlan('network')) && !$plan->is_free)
                        <i class="fas fa-star mr-1"></i> {{ $plan->name }} {{ __('core::translation.plan') }}
                    @else
                        <i class="fas fa-rocket mr-1"></i> {{ __('core::translation.upgrade_to_pro') }}
                    @endif
                </a>
            </li>
        @endif

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-language"></i>
                <span class="badge badge-success navbar-badge text-uppercase">
                    {{ app()->getLocale() }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                @php
                    $languages = collect(config('app.languages', []))
                        ->map(function ($code) {
                            $localeConfig = config("locales.{$code}");
                            if (!$localeConfig) {
                                return null;
                            }

                            $regional = $localeConfig['regional'] ?? '';
                            $country = explode('_', strtolower($regional))[1] ?? null;

                            return (object) [
                                'code' => $code,
                                'name' => $localeConfig['name'] ?? $code,
                                'country' => $country,
                                'regional' => $regional,
                            ];
                        })
                        ->filter()
                        ->keyBy('code');
                @endphp

                @foreach ($languages as $locale => $language)
                    @if (!$loop->first)
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
                    {{ __('core::translation.num_notifications', ['num' => auth()->user()->notifications()->count()]) }}
                </span>

                @foreach (auth()->user()->notifications()->limit(5)->get() as $notification)
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-{{ $notification->data['icon'] ?? 'info' }} mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">
                            {{ $notification->created_at?->diffForHumans() }}
                        </span>
                    </a>
                @endforeach

                <div class="dropdown-divider"></div>
                <a href="{{ admin_url('/profile/notifications') }}"
                    class="dropdown-item dropdown-footer">{{ __('core::translation.see_all_notifications') }}</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                <img src="https://1.gravatar.com/avatar/7162c5aa667c497c4d1b90b36c60eaea?s=32&d=mm&r=g"
                    alt="User Avatar" class="img-size-32 img-circle">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ admin_url('/profile') }}" class="dropdown-item">
                    <i class="fas fa-user-cog mr-2"></i> {{ __('core::translation.profile') }}
                </a>

                <a href="{{ admin_url('my-support-tickets') }}" class="dropdown-item">
                    <i class="fas fa-ticket-alt mr-2"></i> {{ __('core::translation.support_tickets') }}
                </a>

                <a href="{{ admin_url('my-referrals') }}" class="dropdown-item">
                    <i class="fas fa-user-friends mr-2"></i> {{ __('core::translation.my_referrals') }}
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger logout-link" href="javascript:void(0)">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('core::translation.logout') }}
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
         </li> --}}
    </ul>
</nav>
<!-- /.navbar -->
