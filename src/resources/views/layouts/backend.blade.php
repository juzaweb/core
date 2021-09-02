<!DOCTYPE html>
<html lang="en" data-kit-theme="default">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="turbolinks-cache-control" content="no-cache">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? '' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('vendor/juzaweb/styles/images/icon.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Mukta:400,700,800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/juzaweb/styles/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/juzaweb/styles/css/backend.css') }}">

    @include('juzaweb::components.juzaweb_langs')

    <script src="{{ asset('vendor/juzaweb/styles/js/vendor.js') }}"></script>
    <script src="{{ asset('vendor/juzaweb/styles/js/backend.js') }}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
    <script src="{{ asset('vendor/juzaweb/styles/ckeditor/ckeditor.js') }}"></script>

    @do_action('juzaweb_header')
    @yield('header')
</head>

<body class="juzaweb__menuLeft--dark juzaweb__topbar--fixed juzaweb__menuLeft--unfixed">
<div class="juzaweb__layout juzaweb__layout--hasSider">

    <div class="juzaweb__menuLeft">
        <div class="juzaweb__menuLeft__mobileTrigger"><span></span></div>

        <div class="juzaweb__menuLeft__outer">
            <div class="juzaweb__menuLeft__logo__container">
                <a href="/{{ config('juzaweb.admin_prefix') }}/dashboard">
                <div class="juzaweb__menuLeft__logo">
                    <img src="{{ asset('vendor/juzaweb/styles/images/logo.svg') }}" class="mr-2" alt="Juzaweb">
                    <div class="juzaweb__menuLeft__logo__name">Juzaweb</div>
                    <div class="juzaweb__menuLeft__logo__descr">Cms</div>
                </div>
                </a>
                {{--<div class="juzaweb__menuLeft__logo">
                    <div class="juzaweb__menuLeft__logo__name">
                        <a href="/{{ config('juzaweb.admin_prefix') }}">
                            <img src="{{ asset('vendor/juzaweb/styles/images/logo.png') }}" alt="">
                        </a>
                    </div>
                </div>--}}
            </div>

            <div class="juzaweb__menuLeft__scroll jw__customScroll">
                @include('juzaweb::backend.menu_left')
            </div>
        </div>
    </div>
    <div class="juzaweb__menuLeft__backdrop"></div>

    <div class="juzaweb__layout">
        <div class="juzaweb__layout__header">
            @include('juzaweb::backend.menu_top')
        </div>

        <div class="juzaweb__layout__content">
            @if(!request()->is(config('juzaweb.admin_prefix') . '/dashboard'))
                {{ jw_breadcrumb('admin', [
                        [
                            'title' => $title
                        ]
                    ]) }}
            @else
                <div class="mb-3"></div>
            @endif

            <h4 class="font-weight-bold ml-3 text-capitalize">{{ $title }}</h4>

            <div class="juzaweb__utils__content">

                @if(session()->has('message'))
                    <div class="alert alert-{{ session()->get('status') == 'success' ? 'success' : 'danger' }}">{{ session()->get('message') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
        <div class="juzaweb__layout__footer">
            <div class="juzaweb__footer">
                <div class="juzaweb__footer__inner">
                    <a href="https://juzaweb.com/cms" target="_blank" rel="noopener noreferrer" class="juzaweb__footer__logo">
                        Juzaweb CMS - The Best for Laravel Project
                        <span></span>
                    </a>
                    <br />
                    <p class="mb-0">
                        Copyright © {{ date('Y') }} {{ get_config('sitename') }} - Provided by Juzaweb CMS
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $.extend( $.validator.messages, {
        required: "{{ trans('juzaweb::app.this_field_is_required') }}",
    });

    $(".form-ajax").validate();
</script>

@do_action('juzaweb_footer')
@yield('footer')
</body>
</html>