@extends('juzaweb::layouts.auth')

@section('content')
    @if(get_config('google_recaptcha'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ get_config('google_recaptcha_key') }}"></script>
    @endif

    <div class="juzaweb__layout__content">
        <div class="juzaweb__utils__content">
            <div class="juzaweb__auth__authContainer">
                <div class="juzaweb__auth__containerInner">
                    <div class="card juzaweb__auth__boxContainer">
                        <div class="text-dark font-size-24 mb-4">
                            <strong>Create your account</strong>
                        </div>
                        <div class="mb-4">
                            <p>
                                And start spending more time on your projects and less time managing your infrastructure.
                            </p>
                        </div>

                        <form action="{{ route('auth.register') }}" method="post" class="mb-4 form-ajax">
                            <div class="form-group mb-4">
                                <input type="text" name="name" class="form-control" placeholder="@lang('juzaweb::app.full-name')" autocomplete="off"/>
                            </div>
                            <div class="form-group mb-4">
                                <input type="text" name="email" class="form-control" placeholder="@lang('juzaweb::app.email-address')" autocomplete="off"/>
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" name="password" class="form-control" placeholder="@lang('juzaweb::app.password')" autocomplete="off"/>
                            </div>
                            <button type="submit" class="btn btn-primary text-center w-100" data-loading-text="@lang('juzaweb::app.please-wait')">
                                <strong>Sign Up</strong>
                            </button>
                        </form>
                    </div>
                    <div class="text-center pt-2 mb-auto">
                        <span class="mr-2">Already have an account?</span>
                        <a href="{{ route('auth.login') }}" class="jw__utils__link font-size-16">
                            Sign in
                        </a>
                    </div>
                </div>
                <div class="mt-auto pb-5 pt-5">
                    <div class="text-center">
                        Copyright ?? {{ date('Y') }} {{ get_config('sitename') }} - Provided by JUZAWEB CMS
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
