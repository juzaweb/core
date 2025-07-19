@extends('core::layouts.admin')

@section('head')
    <style>
        .profile-userpic img {
            float: none;
            margin: 0 auto;
            width: 50%;
            height: 50%;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
            display: block
        }

        .profile-usertitle {
            text-align: center;
            margin-top: 20px
        }

        .profile-usertitle-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 7px;
            text-transform: uppercase
        }

        .profile-usertitle-name a {
            color: #ff8a00
        }

        .profile-usertitle-job {
            text-transform: uppercase;
            color: #5b9bd1;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px
        }

        .profile-userbuttons {
            text-align: center;
            margin-top: 10px
        }

        .profile-userbuttons .btn {
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 15px;
            margin-right: 5px
        }

        .profile-usermenu {
            padding: 0;
        }

        .profile-usermenu .nav li {
            width: 100%;
        }

        .profile-usermenu .nav li a {
            display: block;
            width: 100%;
            padding: 12px 15px;
            color: #333;
            border-bottom: 1px solid #dee2e6;
            border-radius: 0;
            transition: background-color 0.3s ease;
        }

        .profile-usermenu .nav li a:hover,
        .profile-usermenu .nav li.active a {
            background-color: #e9ecef;
            color: #000;
            text-decoration: none;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        @include('core::admin.profile.components.sidebar')

        <div class="col-xs-12 col-sm-12 col-md-9">
            <div class="card">
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
