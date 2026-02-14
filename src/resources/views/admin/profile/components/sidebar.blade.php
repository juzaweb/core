<aside id="sidebar" class="col-xs-12 col-sm-12 col-md-3">
    <div class="card">
        <div class="card-body">
            <div class="section-bar clearfix">
                <div class="profile-sidebar">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <div class="avatar-upload-container">
                            <img alt="Avatar" src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=='
                                 data-src='{{ auth()->user()->getAvatarUrl(200) }}'
                                 class='avatar avatar-200 photo lazyload'
                                 id="user-avatar-img"
                            />

                            <input type="file" id="avatar-upload" name="avatar" accept="image/*"
                                   style="display: none;">
                        </div>
                    </div>

                    <div class="profile-usertitle">
                        <div class="profile-userbuttons mt-2">
                            <button type="button" class="btn btn-success btn-sm btn-change-avatar">
                                {{ __('Change Avatar') }}
                            </button>
                        </div>
                        <div class="profile-usertitle-name">
                            <a href="{{ admin_url('profile') }}">{{ auth()->user()->name }}</a>
                        </div>
                        <div class="profile-usertitle-job">
                            {{ __('core::translation.join_at') }} {{ auth()->user()->created_at?->format('H:i d/m/Y') }}
                        </div>
                    </div>

                    <div class="profile-usermenu">
                        <ul class="nav flex-column">
                            <li
                                    class="nav-item {{ request()->is(config('core.admin_prefix') . '/profile') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ admin_url('profile') }}">
                                    <i class="fas fa-user"></i> {{ __('core::translation.profile') }}
                                </a>
                            </li>
                            <li
                                    class="nav-item {{ request()->is(config('core.admin_prefix') . '/profile/notifications') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ admin_url('profile/notifications') }}">
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

<script nonce="{{ csp_script_nonce() }}">
    document.addEventListener('DOMContentLoaded', function () {
        const uploadContainer = document.querySelector('.avatar-upload-container');
        const fileInput = document.getElementById('avatar-upload');
        const avatarImg = document.getElementById('user-avatar-img');

        uploadContainer.addEventListener('click', function () {
            fileInput.click();
        });

        const changeAvatarBtn = document.querySelector('.btn-change-avatar');
        if (changeAvatarBtn) {
            changeAvatarBtn.addEventListener('click', function () {
                fileInput.click();
            });
        }

        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const formData = new FormData();
                formData.append('avatar', this.files[0]);
                // Add CSRF token
                formData.append('_token', "{{ csrf_token() }}");

                // Show loading or optimistic update?
                // For now, let's just upload
                fetch("{{ route('admin.profile.change-avatar') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            avatarImg.src = data.file_path;
                            avatarImg.srcset = data.file_path + ' 2x';
                            show_notify('success', data.message);
                        } else {
                            show_notify('error', 'Upload failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        show_notify('error', 'An error occurred');
                    });
            }
        });
    });
</script>
