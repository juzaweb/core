@extends('core::layouts.auth')

@section('title', __('core::translation.email_verification'))

@section('head')
    <link rel="stylesheet" href="{{ mix('css/auth.min.css', 'themes/main') }}">
@endsection

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="auth-container text-center">
                    <!-- Icon -->
                    <div class="verification-icon mb-4">
                        <i class="fas fa-envelope-open-text fa-4x text-theme-primary"></i>
                    </div>
                    <!-- End Icon -->

                    <!-- Title -->
                    <div class="mb-4">
                        <h2 class="auth-title">{{ __('core::translation.verify_your_email_address') }}</h2>
                        <p class="auth-subtitle">{{ __('core::translation.before_proceeding_please_check_your_email_for_a_verification_link') }}</p>
                    </div>
                    <!-- End Title -->

                    <p class="mb-4">
                        {{ __('core::translation.if_you_did_not_receive_the_email') }},
                    </p>

                    <form method="post" action="{{ route('verification.resend') }}" class="d-inline form-ajax" data-notify="false" data-jw-token="true">
                        <div class="jquery-message mb-2"></div>

                        <button type="submit" class="btn btn-primary">
                            {{ __('core::translation.click_here_to_request_another') }}
                        </button>
                    </form>

                    <div class="text-center auth-footer mt-4">
                        <p><a href="{{ url('user/login') }}" class="auth-link-bold">{{ __('main::translation.back_to_login') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
