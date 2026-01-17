@extends('core::layouts.auth')

@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('core::translation.please_verify_your_email_address') }}</p>

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

            <form action="{{ route('login') }}" class="form-ajax" method="post">
                @csrf

                <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('core::translation.re_send_email') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mt-1">
                <a href="{{ route('login') }}" class="text-center">{{ __('core::translation.login_to_account') }}</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
