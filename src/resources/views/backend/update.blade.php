@extends('juzaweb::layouts.backend')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <p>You are using Juzaweb CMS Version: {{ \Juzaweb\Core\Version::getVersion() }}</p>
                <p>View CMS change logs: <a href="https://github.com/juzawebcms/juzawebcms/blob/master/CHANGELOG.md" target="_blank">click here</a></p>
            </div>

            <div class="alert alert-warning">Important: Before updating, please back up your database and files.</div>
            <form method="post" action="">
                @csrf

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-cloud-upload"></i>
                    Update now
                </button>
            </form>

            {{--@if($updater->source()->isNewVersionAvailable())
                @php
                    $versionAvailable = $updater->source()->getVersionAvailable();
                @endphp

                <div class="alert alert-success">Version {{ $versionAvailable }} ready to update.</div>
                <form method="post" action="">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-cloud-upload"></i>
                        Update now
                    </button>
                </form>

            @else
                <div class="alert alert-secondary">{{ trans('juzaweb::app.no_new_version_available') }}</div>
            @endif--}}
        </div>
    </div>
@endsection