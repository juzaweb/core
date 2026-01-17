@extends('core::layouts.admin')

@section('content')
    <form action="{{ admin_url('settings') }}" class="form-ajax" method="post">
        @method('PUT')

        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('core::translation.save_change') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            @foreach($drivers as $name => $driver)
                <div class="col-md-6">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>{{ __('core::translation.login_with_name', ['name' => $driver]) }}</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $redirectUrl = route('auth.social.redirect', [$name]);
                                $callbackUrl = route('auth.social.callback', [$name]);
                            @endphp

                            {{ Field::security(__('core::translation.client_id'), "{$name}_client_id", ['value' => setting("{$name}_client_id")]) }}

                            {{ Field::security(__('core::translation.client_secret'), "{$name}_client_secret", ['value' => setting("{$name}_client_secret")]) }}

                            {{ Field::text(__('core::translation.redirect_url'), "{$name}_redirect", ['value' => $redirectUrl])->disabled() }}

                            {{ Field::text(__('core::translation.callback_url'), "{$name}_callback", ['value' => $callbackUrl])->disabled() }}

                            {{ Field::checkbox(__('core::translation.enable_login_with_name', ['name' => $driver]), "{$name}_login", ['value' => setting("{$name}_login")]) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
@endsection
