@extends('installer::layouts.master-update')

@section('title', trans('installer::message.updater.final.title'))
@section('container')
    <p class="paragraph text-center">{{ session('message')['message'] }}</p>
    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('installer::message.updater.final.exit') }}</a>
    </div>
@stop
