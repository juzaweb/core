@extends('core::layouts.admin')

@section('content')

        <div class="row mt-3">
            <div class="col-md-8">
                <form action="{{ admin_url('settings') }}" class="form-ajax" method="post">
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('core::translation.smtp_settings') }}</h5>
                        </div>
                        <div class="card-body">
                            {{ Field::text(__('core::translation.smtp_host'), 'mail_host', [
                                'value' => setting('mail_host'),
                                'placeholder' => 'smtp.gmail.com'
                            ]) }}

                            {{ Field::text(__('core::translation.smtp_port'), 'mail_port', [
                                'value' => setting('mail_port'),
                                'placeholder' => '587'
                            ]) }}

                            {{ Field::text(__('core::translation.smtp_username'), 'mail_username', [
                                'value' => setting('mail_username'),
                                'placeholder' => 'your-email@example.com'
                            ]) }}

                            {{ Field::security(__('core::translation.smtp_password'), 'mail_password', [
                                'placeholder' => setting('mail_password') ? '••••••••' : __('core::translation.enter_password'),
                                'type' => 'password'
                            ]) }}

                            {{ Field::select(__('core::translation.encryption'), 'mail_encryption', [
                                'value' => setting('mail_encryption')
                            ])->dropDownList([
                                '' => __('core::translation.none'),
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                            ]) }}
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>{{ __('core::translation.from_address') }}</h5>
                        </div>
                        <div class="card-body">
                            {{ Field::text(__('core::translation.from_email_address'), 'mail_from_address', [
                                'value' => setting('mail_from_address'),
                            ]) }}

                            {{ Field::text(__('core::translation.from_name'), 'mail_from_name', [
                                'value' => setting('mail_from_name'),
                            ]) }}
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('core::translation.save_change') }}
                    </button>
                </form>
            </div>

            <div class="col-md-4">
                <x-card title="{{ __('core::translation.test_email_settings') }}">
                    <form method="POST" action="{{ admin_url('settings/test-email') }}" class="card mt-3 p-3 form-ajax">
                        <p>{{ __('core::translation.send_a_test_email_to_ensure_your_email_settings_are_correct') }}</p>

                        <div class="form-group">
                            <input type="email" name="email" class="form-control" id="test_email" placeholder="{{ __('core::translation.enter_email_address') }}" value="{{ $user->email }}">
                        </div>

                        <button type="submit" class="btn btn-secondary mt-2" id="send_test_email">
                            <i class="fas fa-paper-plane"></i> {{ __('core::translation.send_test_email') }}
                        </button>
                    </form>
                </x-card>
            </div>
        </div>

@endsection
