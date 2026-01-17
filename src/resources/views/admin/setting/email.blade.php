@extends('admin::layouts.admin')

@section('content')

        <div class="row mt-3">
            <div class="col-md-8">
                <form action="{{ admin_url('settings') }}" class="form-ajax" method="post">
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('admin::translation.smtp_settings') }}</h5>
                        </div>
                        <div class="card-body">
                            {{ Field::text(__('admin::translation.smtp_host'), 'mail_host', [
                                'value' => setting('mail_host'),
                                'placeholder' => 'smtp.gmail.com'
                            ]) }}

                            {{ Field::text(__('admin::translation.smtp_port'), 'mail_port', [
                                'value' => setting('mail_port'),
                                'placeholder' => '587'
                            ]) }}

                            {{ Field::text(__('admin::translation.smtp_username'), 'mail_username', [
                                'value' => setting('mail_username'),
                                'placeholder' => 'your-email@example.com'
                            ]) }}

                            {{ Field::security(__('admin::translation.smtp_password'), 'mail_password', [
                                'placeholder' => setting('mail_password') ? '••••••••' : __('admin::translation.enter_password'),
                                'type' => 'password'
                            ]) }}

                            {{ Field::select(__('admin::translation.encryption'), 'mail_encryption', [
                                'value' => setting('mail_encryption')
                            ])->dropDownList([
                                '' => __('admin::translation.none'),
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                            ]) }}
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>{{ __('admin::translation.from_address') }}</h5>
                        </div>
                        <div class="card-body">
                            {{ Field::text(__('admin::translation.from_email_address'), 'mail_from_address', [
                                'value' => setting('mail_from_address'),
                            ]) }}

                            {{ Field::text(__('admin::translation.from_name'), 'mail_from_name', [
                                'value' => setting('mail_from_name'),
                            ]) }}
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('admin::translation.save_change') }}
                    </button>
                </form>
            </div>

            <div class="col-md-4">
                <x-card title="{{ __('admin::translation.test_email_settings') }}">
                    <form method="POST" action="{{ admin_url('settings/test-email') }}" class="card mt-3 p-3 form-ajax">
                        <p>{{ __('admin::translation.send_a_test_email_to_ensure_your_email_settings_are_correct') }}</p>

                        <div class="form-group">
                            <input type="email" name="email" class="form-control" id="test_email" placeholder="{{ __('admin::translation.enter_email_address') }}" value="{{ $user->email }}">
                        </div>

                        <button type="submit" class="btn btn-secondary mt-2" id="send_test_email">
                            <i class="fas fa-paper-plane"></i> {{ __('admin::translation.send_test_email') }}
                        </button>
                    </form>
                </x-card>
            </div>
        </div>

@endsection
