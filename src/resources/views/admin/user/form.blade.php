@extends('core::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if($model->exists)
            @method('PUT')
        @endif

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

                        {{ Field::select($model, 'roles', [
                            'label' => __('Roles'),
                            'placeholder' => __('Select a role'),
                        ])->dropDownList($roles, 'id', 'name') }}

                        <hr class="my-4">

                        {!! Field::password(__('Password'), 'password', ['autocomplete' => 'new-password']) !!}

                        {!! Field::password(__('Password Confirmation'), 'password_confirmation', ['autocomplete' => 'new-password']) !!}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')

@endsection
