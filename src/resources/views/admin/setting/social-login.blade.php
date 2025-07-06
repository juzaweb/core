@extends('core::layouts.admin')

@section('content')
    <form action="{{ admin_url('settings') }}" class="form-ajax" method="post">
        @method('PUT')

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save Change') }}
                </button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ __('Social Login') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        {{ Field::text(__('Site Title'), 'title', ['placeholder' => __('Site Title')]) }}


                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
