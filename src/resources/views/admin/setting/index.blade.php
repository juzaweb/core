@extends('core::layouts.admin')

@section('content')
    <form action="{{ route('admin.settings.update', [$websiteId]) }}" class="form-ajax" method="post">
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('core::translation.save_change') }}
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('core::translation.general') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::text(__('core::translation.site_title'), 'title', ['placeholder' => __('core::translation.site_title'), 'value' => setting('title')]) }}

                        {{ Field::textarea(__('core::translation.site_description'), 'description', [
                            'placeholder' => __('core::translation.site_description'),
                            'value' => setting('description'),
                        ]) }}

                        {{ Field::text(__('core::translation.site_name'), 'sitename', ['value' => setting('sitename'), 'placeholder' => __('core::translation.ex_juzaweb')]) }}

                        {{ Field::checkbox(__('core::translation.user_registration'), 'user_registration', ['value' => setting('user_registration')]) }}

                        {{ Field::checkbox(__('core::translation.user_verification'), 'user_verification', ['value' => setting('user_verification')]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('core::translation.multiple_language') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="multiple_language" name="multiple_language" value="none" @checked(setting('multiple_language') == 'none')>
                                <label class="form-check-label" for="multiple_language">
                                    {{ __('core::translation.disable_multiple_language') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="multiple_language_session" name="multiple_language" value="session" @checked(setting('multiple_language') == 'session')>
                                <label class="form-check-label" for="multiple_language_session">
                                    {{ __('core::translation.use_session_to_store_language') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="multiple_language_prefix" name="multiple_language" value="prefix" @checked(setting('multiple_language') == 'prefix')>
                                <label class="form-check-label" for="multiple_language_prefix">
                                    {{ __('core::translation.use_prefix_in_slug_ex_viabout_us') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="multiple_language_subdomain" name="multiple_language" value="subdomain" @checked(setting('multiple_language') == 'subdomain')>
                                <label class="form-check-label" for="multiple_language_subdomain">
                                    {{ __('core::translation.use_subdomain_for_each_language_ex_viexamplecom') }}
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('core::translation.custom_scripts') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('core::translation.warning_these_scripts_will_be_injected_directly_into_your_website_only_trusted_administrators_should_have_access_to_these_settings') }}
                        </div>

                        {{ Field::textarea(__('core::translation.custom_header_script'), 'custom_header_script', [
                            'placeholder' => __('core::translation.htmljavascript_code_to_inject_in_head_section'),
                            'value' => setting('custom_header_script'),
                            'rows' => 5,
                        ]) }}

                        {{ Field::textarea(__('core::translation.custom_footer_script'), 'custom_footer_script', [
                            'placeholder' => __('core::translation.htmljavascript_code_to_inject_before_body_tag'),
                            'value' => setting('custom_footer_script'),
                            'rows' => 5,
                        ]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('core::translation.cookie_consent') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::checkbox(__('core::translation.cookie_consent_enabled'), 'cookie_consent_enabled', ['value' => setting('cookie_consent_enabled')]) }}

                        {{ Field::textarea(__('core::translation.cookie_consent_message'), 'cookie_consent_message', [
                            'placeholder' => __('core::translation.cookie_consent_message_default'),
                            'value' => setting('cookie_consent_message'),
                            'rows' => 3,
                        ]) }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        {{ Field::language(__('core::translation.language'), 'locale', ['value' => $locale]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('core::translation.logo_icon') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::image(__('core::translation.logo'), 'logo', ['value' => setting('logo')]) }}

                        {{ Field::image(__('core::translation.favicon'), 'favicon', ['value' => setting('favicon')]) }}

                        {{ Field::image(__('core::translation.banner_for_social'), 'banner', ['value' => setting('banner')]) }}
                    </div>
                </div>

                <x-card title="{{ __('core::translation.analytics') }}">
                    {{ Field::text(__('core::translation.google_analytics'), 'google_analytics_id', [
                        'placeholder' => __('core::translation.eg_ua_xxxxx_y'),
                        'value' => setting('google_analytics_id'),
                    ]) }}
                </x-card>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('core::translation.save_change') }}
                </button>
            </div>
        </div>
        <br />
    </form>
@endsection
