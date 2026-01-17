@extends('admin::layouts.auth')

@section('title', __('admin::translation.forgot_password'))

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

                        <h2 class="auth-title">{{ __('admin::translation.forgot_password') }}</h2>
                        <p class="auth-subtitle">{{ __('admin::translation.enter_your_email_to_reset_password') }}</p>
                    </div>
                    <!-- End Title -->

                    <form method="post" action="" class="form-ajax" data-notify="false" data-jw-token="true">
                        @csrf

                        <div class="jquery-message mb-2"></div>

                        <!-- Input Group -->
                        <div class="form-group">
                            <label for="forgotEmail">{{ __('admin::translation.email') }}</label>
                            <input type="email" name="email" id="forgotEmail" class="form-control"
                                   placeholder="{{ __('admin::translation.email') }}" required>
                            <span class="error-email text-danger"></span>
                        </div>
                        <!-- End Input Group -->

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('admin::translation.send_reset_link') }}
                            </button>
                        </div>
                    </form>

                    <div class="text-center auth-footer">
                        <p><a href="{{ home_url('user/login') }}" class="auth-link-bold">{{ __('admin::translation.back_to_login') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
