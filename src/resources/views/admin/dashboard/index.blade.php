@extends('core::layouts.admin')

@section('content')
    <div class="row">
        @foreach ($boxes as $box)
            {{ $box->render() }}
        @endforeach
    </div>

    @do_action('admin.dashboard.index')
@endsection