@extends('core::layouts.admin')

@section('content')
    <form action="{{ admin_url('settings') }}" class="form-ajax" method="post">
        @method('PUT')

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save Change') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            @foreach($drivers as $name => $driver)
                <div class="col-md-6">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>{{ __('Login with :name', ['name' => $driver]) }}</h5>
                        </div>
                        <div class="card-body">

                            {{ Field::text(__('Client ID'), "{$name}_client_id", ['value' => setting("{$name}_client_id")]) }}

                            {{ Field::text(__('Client Secret'), "{$name}_client_secret", ['value' => setting("{$name}_client_secret")]) }}

                            {{ Field::text(__('Redirect URL'), "{$name}_redirect", ['value' => route('auth.social.redirect', [$name])])->disabled() }}

                            {{ Field::text(__('Callback URL'), "{$name}_callback", ['value' => route('auth.social.callback', [$name])])->disabled() }}

                            {{ Field::checkbox(__('Enable login with :name', ['name' => $driver]), "{$name}_login", ['value' => setting("{$name}_login")]) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
@endsection
