<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $breadcrumbs = \Juzaweb\Core\Facades\Breadcrumb::getItems();
        $title = $breadcrumbs ? last($breadcrumbs)['title'] : __('Dashboard');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Juzaweb</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'vendor/core') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.min.css', 'vendor/core') }}">
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
        <a href="/admin-cp" class="brand-link">
            <img src="https://cdn.juzaweb.com/jw-styles/themes/juzaweb/assets/images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8" />
            <span class="brand-text font-weight-light">Juzaweb</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            @include('core::layouts.components.sidebar')
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
                            @if($breadcrumbs)
                            <li class="breadcrumb-item">
                                <a href="{{ admin_url() }}">{{ __('Dashboard') }}</a>
                            </li>
                            @endif

                            @foreach($breadcrumbs as $breadcrumb)
                                @if($breadcrumb['url'])
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

<script type="text/html" id="form-images-template">
    @component('core::fields.components.image-item', [
        'name' => '{name}',
        'path' => '{path}',
        'url' => '{url}',
    ])

    @endcomponent
</script>

<script type="text/javascript">
    const juzaweb = {
        adminPrefix: "{{ config('core.admin_prefix') }}",
        documentBaseUrl: "{{ url('/storage') }}/",
        lang: {
            successfully: '{{ __('Successfully') }}',
            error: '{{ __('Error') }}',
            warning: '{{ __('Warning') }}',
            confirm: '{{ __('Are you sure?') }}',
            cancel: '{{ __('Cancel') }}',
            ok: '{{ __('OK') }}',
            yes: '{{ __('Yes') }}',
            remove_question: '{{ __('Are you sure you want to remove?') }}',
            please_wait: '{{ __('Please wait...') }}',
        },
    }
</script>
<script src="{{ mix('js/vendor.min.js', 'vendor/core') }}"></script>
<script src="{{ asset('vendor/core/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ mix('js/admin.min.js', 'vendor/core') }}"></script>

@yield('scripts')
</body>
</html>
