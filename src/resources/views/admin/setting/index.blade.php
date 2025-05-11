@extends('core::layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Settings</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    {{ Field::textarea() }}
                </div>
            </div>
        </div>
    </div>
@endsection
