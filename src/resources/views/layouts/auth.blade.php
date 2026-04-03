<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Juzaweb</title>

    <link href="//cdnjs.cloudflare.com" rel="dns-prefetch"/>
    <link href="//fonts.gstatic.com" rel="dns-prefetch"/>
    <link href="//fonts.googleapis.com" rel="dns-prefetch"/>
    <link href="//pagead2.googlesyndication.com" rel="dns-prefetch"/>
    <link href="//www.googletagmanager.com" rel="dns-prefetch"/>
    <link href="//www.google-analytics.com" rel="dns-prefetch"/>

    @if($favicon = setting('favicon'))
    <link rel="shortcut icon" href="{{ upload_url(setting('favicon')) }}" />
    @endif

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'juzaweb') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.min.css', 'juzaweb') }}">
    <style>
        .auth-layout {
            display: flex;
            min-height: 100vh;
            background-color: var(--bg-primary, #f4f6f9);
        }
        .auth-image {
            display: none;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        @media (min-width: 992px) {
            .auth-image {
                display: flex;
                flex: 1;
            }
            .auth-form-wrapper {
                flex: 0 0 500px;
            }
        }
        .auth-form-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            background-color: var(--bg-secondary, #ffffff);
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.05);
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .auth-logo a {
            color: var(--text-primary, #333);
            text-decoration: none;
        }
        .auth-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
            z-index: 1;
        }
        .auth-image-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            color: white;
            width: 100%;
        }
        .auth-image-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .auth-image-content p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        .login-box .card {
            box-shadow: none !important;
            border: none !important;
            background: transparent;
        }
        .login-box .card-body {
            padding: 0;
        }
        .login-box-msg {
            padding: 0 0 20px 0;
            margin: 0;
            text-align: center;
            font-size: 1.1rem;
            color: var(--text-secondary, #6c757d);
        }
    </style>
</head>
<body class="hold-transition">
<div id="admin-overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>

<div class="auth-layout">
    @php
        $bgImage = setting('auth_image') ? upload_url(setting('auth_image')) : asset('juzaweb/images/auth-bg.jpg');
    @endphp
    <div class="auth-image" style="background-image: url('{{ $bgImage }}');">
        <div class="auth-image-overlay"></div>
        <div class="auth-image-content">
            <h1>{{ setting('title', 'Juzaweb CMS') }}</h1>
            <p>{{ setting('description', 'The best CMS for Laravel') }}</p>
        </div>
    </div>

    <div class="auth-form-wrapper">
        <div class="login-box">
            <div class="auth-logo">
                <a href="/">
                    @if($logo = setting('logo'))
                        <img src="{{ upload_url($logo) }}" alt="{{ setting('title') }}" style="max-height: 60px;">
                    @else
                        <b>Juza</b>web
                    @endif
                </a>
            </div>

            @yield('content')
        </div>
    </div>
</div>

<x-js-var />

<script src="{{ mix('js/vendor.min.js', 'juzaweb') }}"></script>
<script src="{{ mix('js/admin.min.js', 'juzaweb') }}"></script>

@php
    $captcha = setting('captcha');
    $captchaSiteKey = setting('captcha_site_key') ?: config('network.recaptcha.site_key');
    if (is_null($captcha) && $captchaSiteKey) {
        $captcha = 'recaptcha-v2-invisible';
    }
@endphp
@if($captcha == 'recaptcha-v2-invisible' && $captchaSiteKey)
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        const recaptchaSiteKey = "{{ $captchaSiteKey }}";
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaLoadCallback&render=explicit" async defer></script>
@endif

@yield('scripts')

</body>
</html>
