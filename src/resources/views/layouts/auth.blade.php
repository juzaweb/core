<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Juzaweb</title>

    @if($favicon = setting('favicon'))
    <link rel="shortcut icon" href="{{ upload_url(setting('favicon')) }}" />
    @endif

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'juzaweb') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.min.css', 'juzaweb') }}">
</head>
<body class="hold-transition login-page">
<div id="admin-overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>Juza</b>web</a>
    </div>
    <!-- /.login-logo -->

    @yield('content')
</div>
<!-- /.login-box -->

<x-js-var />

<script src="{{ mix('js/vendor.min.js', 'juzaweb') }}"></script>
<script src="{{ mix('js/admin.min.js', 'juzaweb') }}"></script>

@if(config("network.recaptcha.site_key"))
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        const recaptchaSiteKey = "{{ config("network.recaptcha.site_key") }}";
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaLoadCallback&render=explicit" async defer></script>
@endguest

@yield('scripts')

</body>
</html>
