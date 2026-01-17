<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Juzaweb</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css">
    <link rel="stylesheet" href="{{ mix('assets/css/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/css/admin.min.css') }}">

    @yield('head')
</head>
<body class="hold-transition layout-top-nav layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <div id="admin-overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div id="jquery-message"></div>

                @if(session()->has('message'))
                    <div class="alert alert-{{ session()->get('status') != 'error' ? session()->get('status') : 'danger' }} jw-message">
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

<x-js-var/>

<script src="{{ mix('assets/js/vendor.min.js', 'juzaweb') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ mix('assets/js/admin.min.js', 'juzaweb') }}"></script>

@yield('scripts')
</body>
</html>
