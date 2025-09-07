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
    <link rel="shortcut icon" href="{{ setting('favicon') ? upload_url(setting('favicon')) : '/favicon.ico' }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'vendor/core') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.min.css', 'vendor/core') }}">

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
            <img src="https://cdn.juzaweb.com/jw-styles/themes/juzaweb/assets/images/logo.png" alt="Juzaweb Logo" style="opacity: .8" height="57"/>
            {{--<span class="brand-text font-weight-light">Juzaweb</span>--}}
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

<x-js-var />

<script src="{{ mix('js/vendor.min.js', 'vendor/core') }}"></script>
<script src="{{ asset('vendor/core/plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ mix('js/admin.min.js', 'vendor/core') }}"></script>

<script>
    $(function () {
        $(document).on('click', '.translate-model', function (e) {
            e.preventDefault();
            var $this = $(this);
            var id = $this.data('id');
            var model = $this.data('model');
            var locale = $this.data('locale');

            if (id && model && locale) {
                $.ajax({
                    url: '{{ route('admin.translations.translate-model') }}',
                    type: 'POST',
                    data: {
                        id: id,
                        model: model,
                        locale: locale,
                    },
                    beforeSubmit: function () {
                        toggle_global_loading(true);
                    },
                    success: function (response) {
                        show_notify(response.message);
                        window.location.reload();
                    },
                    error: function (xhr) {
                        show_notify(xhr);
                    }
                }).done(function () {
                    toggle_global_loading(false);
                });
            }
        });
    });
</script>

@yield('scripts')
</body>
</html>
