@extends('core::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if($model->exists)
            @method('PUT')
        @endif

            <div class="row">
                <div class="col-md-12">
                    <a href="{{ admin_url('roles') }}" class="btn btn-warning">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>

                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Save') }}
                    </button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Roles') }}</h3>
                        </div>
                        <div class="card-body">
                            {{ Field::text($model, 'name') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Permissions') }}</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Permission') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Allow') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groups as $group)
                                        <tr>
                                            <th colspan="3">{{ $group->name }}</th>
                                        </tr>
                                        @foreach($permissions->get($group->code) as $permission)
                                            <tr>
                                                <td>{{ $permission->name }}</td>
                                                <td>{{ $permission->description }}</td>
                                                <td>

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </form>
@endsection
