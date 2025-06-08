@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ admin_url('users/create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('Add User') }}</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Users') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">

                        </div>
                        <div class="col-md-3">
                            <div id="jw-datatable_filter" class="jw-datatable_filter">
                                <label>Search: <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="jw-datatable"></label>
                            </div>
                        </div>
                    </div>

                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
