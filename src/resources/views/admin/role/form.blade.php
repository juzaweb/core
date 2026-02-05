@extends('core::layouts.admin')

@section('title', $model->exists ? __('core::translation.edit_role') : __('core::translation.add_role'))

@section('content')
    <form action="{{ $model->exists ? admin_url('roles/' . $model->id) : admin_url('roles') }}" method="post"
        class="form-ajax">
        @csrf
        @if ($model->exists)
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="btn-group">
                    <a href="{{ admin_url('roles') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> {{ __('core::translation.back') }}
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> {{ __('core::translation.save') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        {{ Field::text($model, 'name', ['label' => __('core::translation.name'), 'required' => true]) }}
                        {{ Field::text($model, 'code', ['label' => __('core::translation.code'), 'required' => true]) }}
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                @foreach ($permissions as $group => $items)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title text-capitalize">{{ $group }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($items as $permission)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                value="{{ $permission['code'] }}" id="perm-{{ $permission['code'] }}"
                                                @if ($model->exists && $model->hasPermissionTo($permission['code'])) checked @endif>
                                            <label class="form-check-label" for="perm-{{ $permission['code'] }}">
                                                {{ $permission['name'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
@endsection
