@extends('core::layouts.admin')

@section('head')
    <link rel="stylesheet" href="{{ url('vendor/core/plugins/chartjs/Chart.min.css') }}">
@endsection

@section('content')
    <div class="row">
        @foreach ($boxes as $box)
            {{ $box->render() }}
        @endforeach
    </div>

    @do_action('admin.dashboard.index')

    <div class="row">
        @foreach ($charts as $chart)
            @include('core::components.dashboard.line-chart', ['chart' => $chart])
        @endforeach
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ url('vendor/core/plugins/chartjs/Chart.min.js') }}"></script>
@endsection
