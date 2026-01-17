@extends('core::layouts.auth')

@section('title', __('core::translation.reset_password'))

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
                        <img src="{{ logo_url() }}"
                             alt="Logo"
                             class="img-fluid mb-3"
                             style="max-height: 50px;">

                        <h2 class="auth-title">{{ __('core::translation.reset_password') }}</h2>
                        <p class="auth-subtitle">{{ __('core::translation.enter_your_new_password') }}</p>
                    </div>
                    <!-- End Title -->

                    <form method="post" action="" class="form-ajax" data-notify="false" data-jw-token="true">
                        @csrf

                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="resetPassword">{{ __('core::translation.new_password') }}</label>
                            <input type="password" name="password" id="resetPassword" class="form-control"
                                   placeholder="{{ __('core::translation.new_password') }}" required>
                            <small class="form-text text-muted">{{ __('core::translation.minimum_8_characters') }}</small>
                            <span class="error-password text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="resetConfirmPassword">{{ __('core::translation.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" id="resetConfirmPassword" class="form-control"
                                   placeholder="{{ __('core::translation.confirm_password') }}" required>
                            <span class="error-password_confirmation text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('core::translation.reset_password') }}
                            </button>
                        </div>
                    </form>

                    <div class="text-center auth-footer">
                        <p><a href="{{ home_url('user/login') }}" class="auth-link-bold">{{ __('core::translation.back_to_login') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
