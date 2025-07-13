@extends('core::layouts.auth')

@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('Enter your email to reset password') }}</p>

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

            <form action="{{ route('auth.forgot-password') }}" class="form-ajax" method="post">
                @csrf

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">{{ __('Forgot password') }}</button>

            </form>

            <p class="mb-0 mt-3">
                <a href="{{ url('user/login') }}" class="text-center">{{ __('Login to account') }}</a>
            </p>

            <p class="mb-0">
                <a href="{{ url('user/register') }}" class="text-center">{{ __('Register a new account') }}</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
