<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Juzaweb</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'vendor/core') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.min.css', 'vendor/core') }}">
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

<script src="{{ mix('js/vendor.min.js', 'vendor/core') }}"></script>
<script src="{{ asset('vendor/core/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ mix('js/admin.min.js', 'vendor/core') }}"></script>

@yield('scripts')

</body>
</html>
