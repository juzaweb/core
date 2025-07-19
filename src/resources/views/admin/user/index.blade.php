@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @can('users.create')
            <a href="{{ admin_url('users/create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('Add User') }}
            </a>
            @endcan
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            @component('core::components.datatables.filters')
                <div class="col-md-3 jw-datatable_filters">
                    {{ Field::select(__('Status'), 'status')->dropDownList(
                        [
                            '' => __('All'),
                            ...\Juzaweb\Core\Models\Enums\UserStatus::all(),
                        ]
                    )->selected(request('status')) }}
                </div>
            @endcomponent
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Users') }}</h3>
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
