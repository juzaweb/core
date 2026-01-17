<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('core::components.theme-head')

    <title>{{ $title }}{{ setting('sitename') ? ' - ' . setting('sitename') : '' }}</title>

    <link rel="stylesheet" href="{{ mix('css/main.min.css', 'themes/main') }}">
    <link rel="stylesheet" href="{{ mix('css/home.min.css', 'themes/main') }}">

    <script src="{{ mix('js/vendor.min.js', 'themes/main') }}"></script>

    <x-theme-js-var />

    @yield('head')
</head>

<body class="juzaweb-theme homepage1-body @yield('body-classes')">
    <!--===== PRELOADER STARTS =======-->
    <div class="preloader">
        <div class="loading-container">
            <div class="loading"></div>
            <div id="loading-icon">
                <img src="{{ asset('assets/images/logo.png?v=2') }}" alt="">
            </div>
        </div>
    </div>
    <!--===== PRELOADER ENDS =======-->

    <!--===== PROGRESS STARTS=======-->
    <div class="paginacontainer">
        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>
    </div>
    <!--===== PROGRESS ENDS=======-->

    @yield('content')

    {{-- Auth Modals --}}
    {{-- @include('main::components.auth-modals') --}}

    <x-theme-init />

    <script src="{{ mix('js/main.min.js', 'themes/main') }}"></script>

    @yield('scripts')

    @if(setting('custom_footer_script'))
        {!! setting('custom_footer_script') !!}
    @endif
</body>

</html>
