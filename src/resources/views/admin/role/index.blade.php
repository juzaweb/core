@extends('core::layouts.admin')

@section('title', __('core::translation.roles'))

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="btn-group">
                @can('roles.create')
                    <a href="{{ admin_url('roles/create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> {{ __('core::translation.add_role') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts(null, ['nonce' => csp_script_nonce()]) }}
@endsection
