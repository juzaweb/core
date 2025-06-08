@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ admin_url('users/create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('Add User') }}</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="form-row align-items-end">
                            <div class="col-md-3 jw-datatable_filters">
                                {{ Field::select(__('Status'), 'status')->dropDownList(
                                    [
                                        '' => __('All'),
                                        ...\Juzaweb\Core\Models\Enums\UserStatus::all(),
                                    ]
                                )->selected(request('status')) }}
                            </div>

                            <div class="form-group col-md-1">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>

                            <div class="form-group col-md-1">
                                <a href="{{ request()->url() }}" class="btn btn-light border btn-block">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
