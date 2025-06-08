@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ admin_url('roles/create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('Add Role') }}</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Roles') }}</h3>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
