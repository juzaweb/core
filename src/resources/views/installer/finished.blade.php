@extends('installer::layouts.master')

@section('template_title')
    {{ trans('installer::message.final.template_title') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer::message.final.title') }}
@endsection

@section('container')

    <div class="buttons">
        <a href="{{ url(config('juzaweb.admin_prefix')) }}" class="button" data-turbolinks="false">{{ trans('installer::message.final.exit') }}</a>
    </div>

@endsection
