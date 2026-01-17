@extends('admin::layouts.auth')

@section('title', __('admin::translation.login'))

@section('head')
    <link rel="stylesheet" href="{{ mix('css/auth.min.css', 'themes/main') }}">
@endsection

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-container">
                    <!-- Title -->
                    <div class="text-center mb-4">
                        <img src="{{ logo_url(asset('assets/images/logo.png?v=2')) }}"
                             alt="Logo"
                             class="img-fluid mb-3"
                             style="max-height: 50px;">

                        <h2 class="auth-title">{{ __('admin::translation.login') }}</h2>
                        <p class="auth-subtitle">{{ __('admin::translation.sign_in_to_your_account') }}</p>
                    </div>
                    <!-- End Title -->

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif

                    <form method="post" action="{{ route('login') }}" class="form-ajax" data-notify="false" data-jw-token="true">
                        <div class="jquery-message mb-2"></div>

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="loginEmail">{{ __('admin::translation.email') }}</label>
                            <input type="email" name="email" id="loginEmail" class="form-control"
                                   placeholder="{{ __('admin::translation.email') }}" required>
                            <span class="error-email text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="loginPassword">{{ __('admin::translation.password') }}</label>
                            <input type="password" name="password" id="loginPassword" class="form-control"
                                   placeholder="{{ __('admin::translation.password') }}" required>
                            <span class="error-password text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <div class="auth-options">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember" value="1" checked>
                                <label class="form-check-label" for="rememberMe">
                                    {{ __('admin::translation.remember_me') }}
                                </label>
                            </div>
                            <a href="{{ url('user/forgot-password') }}" class="auth-link">
                                {{ __('admin::translation.forgot_password') }}
                            </a>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block" data-loading-text="{{ __('admin::translation.logging_in') }}">
                                {{ __('admin::translation.login') }}
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

                    @if(setting('user_registration'))
                    <div class="text-center auth-footer">
                        <p>{{ __("admin::translation.dont_have_an_account") }} <a href="{{ home_url('user/register') }}" class="auth-link-bold">{{ __('admin::translation.register') }}</a></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
