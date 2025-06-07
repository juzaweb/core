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

                    {{ Field::image(__('Language'), 'language') }}

                    {{ Field::editor(__('Terms of Service'), 'terms_of_service', ['placeholder' => __('Terms of Service')]) }}
                </div>
            </div>
        </div>
    </div>
@endsection
