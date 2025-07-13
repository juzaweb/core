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

            <form action="{{ route('password.reset', [$email, $token]) }}" class="form-ajax" method="post">
                @csrf

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="{{ __('New password') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="{{ __('Confirm password') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">{{ __('Reset password') }}</button>

            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
