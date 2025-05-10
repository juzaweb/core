@extends('core::layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Settings</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="site_name">Site Name</label>
                        <input type="text" name="site_name" class="form-control" id="site_name" placeholder="Site Name">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
