<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Juzaweb</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link href="https://cdn.datatables.net/v/bs4/jq-3.7.0/jszip-3.10.1/dt-2.3.0/af-2.7.0/b-3.2.3/b-colvis-3.2.3/b-html5-3.2.3/b-print-3.2.3/datatables.min.css" rel="stylesheet" integrity="sha384-qDfn09R/hNBbMsK48pEFcvpZr2u0sa+owVHZaoYuTEQvzrDWdXkEbmC34ST5CaBx" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('assets/core/css/admin.min.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
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
                        @php
                        $breadcrumbs = \Juzaweb\Core\Facades\Breadcrumb::getItems();
                        @endphp
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

<!-- jQuery -->
<script src="{{ mix('assets/core/js/admin.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs4/jq-3.7.0/jszip-3.10.1/dt-2.3.0/af-2.7.0/b-3.2.3/b-colvis-3.2.3/b-html5-3.2.3/b-print-3.2.3/datatables.min.js" integrity="sha384-J7p4zLxX5RxiVwyCbBJc0kKzeMCSeYhZWpNQKtoJCnefbLILktRG2qnTnb8j1u1Q" crossorigin="anonymous"></script>

@yield('scripts')
</body>
</html>
