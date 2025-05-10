<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | Juzaweb</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ mix('assets/core/css/admin.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>Juza</b>web</a>
    </div>
    <!-- /.login-logo -->

    @yield('content')
</div>
<!-- /.login-box -->

<script src="{{ mix('assets/core/js/admin.min.js') }}"></script>

</body>
</html>
