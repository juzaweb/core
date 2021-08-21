@extends('installer::layouts.master-update')

@section('title', trans('installer::message.updater.welcome.title'))
@section('container')
    <p class="paragraph text-center">
    	{{ trans('installer::message.updater.welcome.message') }}
    </p>
    <div class="buttons">
        <a href="{{ route('LaravelUpdater::overview') }}" class="button">{{ trans('installer::message.next') }}</a>
    </div>
@stop
