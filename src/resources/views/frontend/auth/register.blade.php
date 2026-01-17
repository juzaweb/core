@extends('admin::layouts.auth')

@section('content')
    <div class="card">
        <div class="card-body login-card-body" id="register-form">
            <p class="login-box-msg">{{ __('admin::translation.sign_up_new_a_account') }}</p>

            <div id="jquery-message"></div>

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

            <form action="" class="form-ajax" method="post" data-success="handleRegisterSuccess">
                @csrf

                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" placeholder="{{ __('admin::translation.your_name') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="{{ __('admin::translation.email') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="{{ __('admin::translation.password') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('admin::translation.confirm_password') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" checked value="1" required>
                            <label for="remember">
                                {{ __('') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('admin::translation.sign_up') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            @if($socialLogins->isNotEmpty())
                <div class="social-auth-links text-center mb-3">
                    <p>- {{ __('admin::translation.or') }} -</p>

                    @foreach($socialLogins as $key => $name)
                        <a href="{{ route('auth.social.redirect', [$key]) }}" class="btn btn-block btn-primary">
                            <i class="fab fa-{{ $key }} mr-2"></i> {{ __('admin::translation.sign_in_using_name', ['name' => $name]) }}
                        </a>
                    @endforeach
                </div>
                <!-- /.social-auth-links -->
            @endif

            <p class="mb-1">
                <a href="{{ url('forgot-password') }}">{{ __('admin::translation.i_forgot_my_password') }}</a>
            </p>
            <p class="mb-0">
                <a href="{{ route('login') }}" class="text-center">{{ __('admin::translation.login_to_account') }}</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection

@section('scripts')
    <script type="text/html" id="must-verification-template">
        @component('admin::frontend.auth.components.must-verification')

        @endcomponent
    </script>

    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        function handleRegisterSuccess(form, response) {
            let temp = document.getElementById('must-verification-template').innerHTML;

            $('#register-form').html(temp);
        }
    </script>
@endsection
