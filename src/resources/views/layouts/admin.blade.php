<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $breadcrumbs = \Juzaweb\Modules\Core\Facades\Breadcrumb::getItems();
        $title = $breadcrumbs ? last($breadcrumbs)['title'] : __('core::translation.dashboard');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Juzaweb</title>

    <link href="//dnjs.cloudflare.com" rel="dns-prefetch"/>
    <link href="//fonts.gstatic.com" rel="dns-prefetch"/>
    <link href="//fonts.googleapis.com" rel="dns-prefetch"/>
    <link href="//cdn.juzaweb.com" rel="dns-prefetch"/>
    <link href="//img2.juzaweb.com" rel="dns-prefetch"/>
    <link href="//img2.juzaweb.com" rel="dns-prefetch"/>
    <link href="//pagead2.googlesyndication.com" rel="dns-prefetch"/>
    <link href="//www.googletagmanager.com" rel="dns-prefetch"/>
    <link href="//www.google-analytics.com" rel="dns-prefetch"/>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css">
    <link rel="stylesheet" href="{{ mix('/assets/css/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ mix('/assets/css/admin.min.css') }}">

    @yield('head')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <div id="admin-overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>

    @include('core::layouts.components.navbar')

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ admin_url('/') }}" class="brand-link">
            <img src="{{ asset('assets/images/logo-white.png?v=2') }}" alt="Juzaweb Logo" style="opacity: .8"
                 height="45"/>
            {{-- <span class="brand-text font-weight-light">Juzaweb</span> --}}
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            @include('core::layouts.components.sidebar', ['menu' => 'admin-left'])
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-6">
                        <h1>{{ $title }}</h1>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @if ($breadcrumbs)
                                <li class="breadcrumb-item">
                                    <a href="{{ admin_url() }}">{{ __('core::translation.dashboard') }}</a>
                                </li>
                            @endif

                            @foreach ($breadcrumbs as $breadcrumb)
                                @if ($breadcrumb['url'])
                                    <li class="breadcrumb-item">
                                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                    </li>
                                @else
                                    <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div id="jquery-message"></div>

                @if (session()->has('message'))
                    <div
                            class="alert alert-{{ session()->get('status') != 'error' ? session()->get('status') : 'danger' }} jw-message">
                        {{ session()->get('message') }}

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0
        </div>
        <strong>Copyright &copy; 2025 <a href="https://juzaweb.com">Juzaweb.com</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<form action="{{ url('user/logout') }}" method="post" style="display: none" class="form-logout">
    @csrf
</form>

<script type="text/html" id="form-images-template">
    @component('core::fields.components.image-item', [
        'name' => '{name}',
        'path' => '{path}',
        'url' => '{url}',
    ])

    @endcomponent
</script>

<x-js-var/>

@if(isset($translateModel))
    @component('core::components.translate-modal', [
        'translateModel' => $translateModel,
    ])
    @endcomponent
@endif

<div class="modal fade" id="translate-progress-modal" tabindex="-1" role="dialog" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('core::translation.translation_in_progress') }}</h5>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                </div>
                <p id="translate-progress-status">{{ __('core::translation.translation_processing') }}</p>
                <div class="progress mt-3" style="height: 25px;">
                    <div id="translate-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated"
                         role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0%
                    </div>
                </div>
                <p class="mt-2 text-muted">
                    <small id="translate-progress-detail"></small>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('assets/js/vendor.min.js', 'juzaweb') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ mix('assets/js/admin.min.js', 'juzaweb') }}"></script>

<script type="text/javascript" nonce="{{ csp_script_nonce() }}">
    $(function () {
        $(document).on('click', '.logout-link', function (e) {
            e.preventDefault();
            $('.form-logout').submit();
        });
    });
</script>

@yield('scripts')
</body>

</html>
