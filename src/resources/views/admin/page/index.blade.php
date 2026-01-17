@extends('admin::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @can('pages.create')
                <a href="{{ admin_url('pages/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('admin::translation.add_page') }}
                </a>
            @endcan
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            @component('admin::components.datatables.filters')
                <div class="col-md-3 jw-datatable_filters">
                    {{ Field::select(__('admin::translation.status'), 'status')->dropDownList(
                        [
                            '' => __('admin::translation.all'),
                            ...\Juzaweb\Modules\Core\Enums\PageStatus::all(),
                        ]
                    )->selected(request('status')) }}
                </div>
            @endcomponent
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin::translation.pages') }}</h3>
                </div>
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
