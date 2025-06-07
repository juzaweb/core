@extends('core::layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Settings') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    {{ Field::text(__('Site Title'), 'site_title', ['placeholder' => __('Site Title')]) }}

                    {{ Field::textarea(__('Site Description'), 'description', ['placeholder' => __('Site Description')]) }}

                    {{ Field::select(__('Language'), 'language')->dropDownList(['en' => 'Eng']) }}

                    {{ Field::checkbox(__('Enable User Registration'), 'user_registration', ['value' => setting('user_registration')]) }}

                    {{ Field::checkbox(__('Enable User Verification'), 'user_verification', ['value' => setting('user_verification')]) }}
                </div>

                <div class="col-md-4">

                </div>
            </div>
        </div>
    </div>
@endsection
