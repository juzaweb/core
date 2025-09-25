@extends('core::layouts.admin')

@section('content')
    <form action="{{ admin_url('settings') }}" class="form-ajax" method="post">
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-12">
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save Change') }}
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('General') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::text(__('Site Title'), 'title', ['placeholder' => __('Site Title'), 'value' => setting('title')]) }}

                        {{ Field::textarea(__('Site Description'), 'description', [
                            'placeholder' => __('Site Description'),
                            'value' => setting('description'),
                        ]) }}

                        {{ Field::text(__('Site Name'), 'sitename', ['value' => setting('sitename'), 'placeholder' => __('Ex: Juzaweb')]) }}

                        {{ Field::select(__('Default Language'), 'language', ['value' => config('language', 'en')])
                            ->dropDownList(collect(config('locales'))->pluck('name', 'code')->toArray()) }}

                        {{ Field::checkbox(__('User Registration'), 'user_registration', ['value' => setting('user_registration')]) }}

                        {{ Field::checkbox(__('User Verification'), 'user_verification', ['value' => setting('user_verification')]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Recaptcha v2') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::text(__('Site Key'), 'recaptcha2_site_key', ['value' => setting('recaptcha2_site_key')]) }}

                        {{ Field::text(__('Secret'), 'recaptcha2_secret_key', ['value' => setting('recaptcha2_secret_key')]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Multiple Language') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="multiple_language" name="multiple_language" value="none" @checked(setting('multiple_language') == 'none')>
                                <label class="form-check-label" for="multiple_language">
                                    {{ __('Disable multiple language') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="multiple_language_session" name="multiple_language" value="session" @checked(setting('multiple_language') == 'session')>
                                <label class="form-check-label" for="multiple_language_session">
                                    {{ __('Use session to store language') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="multiple_language_prefix" name="multiple_language" value="prefix" @checked(setting('multiple_language') == 'prefix')>
                                <label class="form-check-label" for="multiple_language_prefix">
                                    {{ __('Use prefix in slug (Ex: /vi/about-us)') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="multiple_language_subdomain" name="multiple_language" value="subdomain" @checked(setting('multiple_language') == 'subdomain')>
                                <label class="form-check-label" for="multiple_language_subdomain">
                                    {{ __('Use Subdomain for each language (Ex: vi.example.com)') }}
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        {{ Field::language(__('Language'), 'locale', ['value' => $locale]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Logo & Icon') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::image(__('Logo'), 'logo', ['value' => setting('logo')]) }}

                        {{ Field::image(__('Favicon'), 'favicon', ['value' => setting('favicon')]) }}

                        {{ Field::image(__('Banner (for social)'), 'banner', ['value' => setting('banner')]) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save Change') }}
                </button>
            </div>
        </div>
        <br />
    </form>
@endsection
