@extends('core::layouts.auth')

@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('core::translation.enter_your_email_to_reset_password') }}</p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            <form action="{{ route('forgot-password') }}" class="form-ajax" method="post">
                @csrf

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="{{ __('core::translation.email') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">{{ __('core::translation.forgot_password') }}</button>

            </form>

            <p class="mb-0 mt-3">
                <a href="{{ route('login') }}" class="text-center">{{ __('core::translation.login_to_account') }}</a>
            </p>

            @if(setting('user_registration'))
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">{{ __('core::translation.register_a_new_account') }}</a>
                </p>
            @endif
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
