@extends('core::layouts.auth')

@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('core::translation.sign_in_to_start_your_session') }}</p>

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

            <form action="" class="form-ajax" method="post">
                @csrf

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" checked value="1">
                            <label for="remember">
                                {{ __('core::translation.remember_me') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('core::translation.sign_in') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            @if($socialLogins->isNotEmpty())
            <div class="social-auth-links text-center mb-3">
                <p>- {{ __('core::translation.or') }} -</p>

                @foreach($socialLogins as $key => $name)
                    <a href="{{ route('auth.social.redirect', [$key]) }}" class="btn btn-block btn-primary">
                        <i class="fab fa-{{ $key }} mr-2"></i> {{ __('core::translation.sign_in_using_name', ['name' => $name]) }}
                    </a>
                @endforeach
            </div>
            <!-- /.social-auth-links -->
            @endif

            <p class="mb-1">
                <a href="{{ url('forgot-password') }}">{{ __('core::translation.i_forgot_my_password') }}</a>
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
