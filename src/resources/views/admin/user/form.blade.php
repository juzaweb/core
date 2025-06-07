@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ admin_url('users') }}" class="btn btn-warning">
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
                    <h3 class="card-title">{{ __('Users') }}</h3>
                </div>
                <div class="card-body">
                    {!! Field::text($model, 'name') !!}

                    {!! Field::text($model, 'email') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
