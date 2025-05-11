@extends('core::layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Settings') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    {{ Field::text(__('Site Name'), 'site_name', ['placeholder' => __('Site Name')]) }}

                    {{ Field::text(__('Site Name'), 'site_name', ['placeholder' => __('Site Name')]) }}
                </div>
            </div>
        </div>
    </div>
@endsection
