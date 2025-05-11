<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @yield('head')
</head>
<body class="juzweb-theme @yield('body-classes')">
    @yield('content')

    @yield('scripts')
</body>
</html>
