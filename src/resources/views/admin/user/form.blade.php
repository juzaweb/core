@extends('core::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if ($model->exists)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-12">
                <a href="{{ back_form_url() }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> {{ __('core::translation.back') }}
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('core::translation.save') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('core::translation.users') }}</h3>
                    </div>
                    <div class="card-body">
                        {!! Field::text($model, 'name') !!}

                        {!! Field::text($model, 'email') !!}

                        {{ Field::select($model, 'roles', [
                            'label' => __('core::translation.roles'),
                            'placeholder' => __('core::translation.select_a_role'),
                            'multiple' => true,
                        ])->dropDownList($roles, 'id', 'name') }}

                        <hr class="my-4">

                        {!! Field::password(__('core::translation.password'), 'password', ['autocomplete' => 'new-password']) !!}

                        {!! Field::password(__('core::translation.password_confirmation'), 'password_confirmation', [
                            'autocomplete' => 'new-password',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
@endsection
