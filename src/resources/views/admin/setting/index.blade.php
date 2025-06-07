@extends('core::layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Settings') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    {{ Field::text(__('Site Title'), 'title', ['placeholder' => __('Site Title')]) }}

                    {{ Field::textarea(__('Site Description'), 'description', ['placeholder' => __('Site Description')]) }}

                    {{ Field::text(__('Site Name'), 'sitename', ['placeholder' => __('Ex: Juzaweb')]) }}

                    {{ Field::select(__('Language'), 'language', ['value' => config('language', 'en')])
                        ->dropDownList(collect(config('locales'))->pluck('name', 'code')->toArray()) }}

                    {{ Field::checkbox(__('User Registration'), 'user_registration', ['value' => setting('user_registration')]) }}

                    {{ Field::checkbox(__('User Verification'), 'user_verification', ['value' => setting('user_verification')]) }}
                </div>

                <div class="col-md-3">
                    {{ Field::image(__('Logo'), 'logo', ['value' => setting('icon')]) }}

                    {{ Field::image(__('Icon'), 'icon', ['value' => setting('icon')]) }}

                    {{ Field::image(__('Banner (for social)'), 'banner', ['value' => setting('banner')]) }}
                </div>
            </div>
        </div>
    </div>
@endsection
