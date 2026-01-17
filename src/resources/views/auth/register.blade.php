@extends('admin::layouts.auth')

@section('title', __('admin::translation.register'))

@section('head')
    <link rel="stylesheet" href="{{ mix('css/auth.min.css', 'themes/main') }}">
@endsection

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-container" id="register-form">
                    <!-- Title -->
                    <div class="text-center mb-4">
                        <img src="{{ logo_url() }}"
                             alt="Logo"
                             class="img-fluid mb-3"
                             style="max-height: 50px;">

                        <h2 class="auth-title">{{ __('admin::translation.register') }}</h2>
                        <p class="auth-subtitle">{{ __('admin::translation.create_your_account') }}</p>
                    </div>
                    <!-- End Title -->

                    <div id="jquery-message"></div>

                    <form method="post" action="" class="form-ajax" data-success="handleRegisterSuccess" data-notify="false" data-jw-token="true">

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="registerName">{{ __('admin::translation.name') }}</label>
                            <input type="text" name="name" id="registerName" class="form-control"
                                   placeholder="{{ __('admin::translation.your_name') }}" required>
                            <span class="error-name text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="registerEmail">{{ __('admin::translation.email') }}</label>
                            <input type="email" name="email" id="registerEmail" class="form-control"
                                   placeholder="{{ __('admin::translation.email') }}" required>
                            <span class="error-email text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="registerPassword">{{ __('admin::translation.password') }}</label>
                            <input type="password" name="password" id="registerPassword" class="form-control"
                                   placeholder="{{ __('admin::translation.password') }}" required>
                            <small class="form-text text-muted">{{ __('admin::translation.minimum_8_characters') }}</small>
                            <span class="error-password text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="registerConfirmPassword">{{ __('admin::translation.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" id="registerConfirmPassword" class="form-control"
                                   placeholder="{{ __('admin::translation.confirm_password') }}" required>
                            <span class="error-password_confirmation text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block" data-loading-text="{{ __('admin::translation.registering') }}">
                                {{ __('admin::translation.register') }}
                            </button>
                        </div>
                    </form>

                    @if($socialLogins->isNotEmpty())
                    <div class="social-auth-separator">
                        <span>{{ __('admin::translation.or_continue_with') }}</span>
                    </div>

                    <div class="social-auth-links">
                        @foreach($socialLogins as $key => $name)
                            <a href="{{ route('auth.social.redirect', [$key]) }}" class="btn btn-social btn-{{ $key }}">
                                <i class="fab fa-{{ $key }}"></i> {{ $name }}
                            </a>
                        @endforeach
                    </div>
                    @endif

                    <div class="text-center auth-footer">
                        <p>{{ __('admin::translation.already_have_an_account') }} <a href="{{ home_url('user/login') }}" class="auth-link-bold">{{ __('admin::translation.login') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/html" id="must-verification-template">
        <p class="login-box-msg">
            {{ __('admin::translation.your_account_has_been_created_successfully') }}<br />
            {{ __('admin::translation.please_click_link_email_activate_account') }}<br />
            <em>{{ __('admin::translation.check_box_mail_spam') }}</em>
        </p>
    </script>

    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        function handleRegisterSuccess(form, response) {
            let temp = document.getElementById('must-verification-template').innerHTML;
            $('#register-form').html(temp);
        }
    </script>
@endsection
