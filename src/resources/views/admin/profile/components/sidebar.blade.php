<aside id="sidebar" class="col-xs-12 col-sm-12 col-md-3">
    <div class="card">
        <div class="card-body">
            <div class="section-bar clearfix">
                <div class="profile-sidebar">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <img alt="Avatar"
                             src='{{ auth()->user()->getAvatarUrl(200) }}'
                             srcset='{{ auth()->user()->getAvatarUrl(400) }} 2x'
                             class='avatar avatar-200 photo'/>
                    </div>

                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            <a href="{{ url('profile') }}">{{ auth()->user()->name }}</a>
                        </div>
                        <div class="profile-usertitle-job">
                            {{ __('core::translation.join_at') }} {{ auth()->user()->created_at?->format('H:i d/m/Y') }}
                        </div>
                    </div>

                    <div class="profile-usermenu">
                        <ul class="nav flex-column">
                            <li class="nav-item {{ request()->is(config('core.admin_prefix') . '/profile') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ admin_url('profile') }}">
                                    <i class="fas fa-user"></i> {{ __('core::translation.profile') }}
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is((config('core.admin_prefix') . '/profile/notifications')) ? 'active' : '' }}">
                                <a class="nav-link"
                                   href="{{ admin_url('profile/notifications') }}">
                                    <i class="fas fa-bell"></i> {{ __('core::translation.notifications') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

</aside>
