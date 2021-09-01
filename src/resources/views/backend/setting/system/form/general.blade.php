<form method="post" action="{{ route('admin.setting.save') }}" class="form-ajax">
    <input type="hidden" name="form" value="general">
    @php
        $registration = get_config('user_registration');
        $verification = get_config('user_verification');
    @endphp

    <div class="row mt-3">
        <div class="col-md-6"></div>

        <div class="col-md-6">
            <div class="btn-group float-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> @lang('juzaweb::app.save')
                </button>

                <button type="reset" class="btn btn-default">
                    <i class="fa fa-refresh"></i> @lang('juzaweb::app.reset')
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h5>{{ trans('juzaweb::app.general') }}</h5>

            <div class="form-group">
                <label class="col-form-label" for="title">@lang('juzaweb::app.site_title')</label>
                <input type="text" name="title" class="form-control" id="title" value="{{ get_config('title') }}" autocomplete="off">
            </div>

            <div class="form-group">
                <label class="col-form-label" for="description">@lang('juzaweb::app.tagline')</label>
                <textarea class="form-control" name="description" id="description" rows="4">{{ get_config('description') }}</textarea>
                <p class="description">{{ trans('juzaweb::app.site_description_note') }}</p>
            </div>

            <div class="form-group">
                <label class="col-form-label" for="timezone">@lang('juzaweb::app.timezone')</label>
                <select name="timezone" class="form-control"></select>
                <p class="timezone">{{ trans('juzaweb::app.timezone_description') }}</p>
            </div>

            <div class="form-group">
                <label class="col-form-label" for="language">@lang('juzaweb::app.site_language')</label>
                <select name="language" class="form-control"></select>
            </div>

            <div class="form-group">
                <label class="col-form-label" for="language">@lang('juzaweb::app.Date Format')</label>

            </div>

            <div class="form-group">
                <label class="col-form-label" for="language">@lang('juzaweb::app.Time Format')</label>

            </div>

        </div>

        <div class="col-md-4">
            <h5>{{ trans('juzaweb::app.analytics') }}</h5>

            <div class="form-group">
                <label class="col-form-label" for="fb_app_id">@lang('juzaweb::app.fb_app_id')</label>
                <input type="text" name="fb_app_id" class="form-control" id="fb_app_id" value="{{ get_config('fb_app_id') }}" autocomplete="off">
            </div>

            <div class="form-group">
                <label class="col-form-label" for="google_analytics">@lang('juzaweb::app.google_analytics_id')</label>
                <input type="text" name="google_analytics" class="form-control" id="google_analytics" value="{{ get_config('google_analytics') }}" autocomplete="off">
            </div>

            <h5>{{ trans('juzaweb::app.registration') }}</h5>

            <div class="form-group">
                <label class="col-form-label" for="user_registration">@lang('juzaweb::app.user_registration')</label>
                <select name="user_registration" id="user_registration" class="form-control">
                    <option value="1" @if($registration == 1) selected @endif>@lang('juzaweb::app.enabled')</option>
                    <option value="0" @if($registration == 0) selected @endif>@lang('juzaweb::app.disabled')</option>
                </select>
            </div>

            <div class="form-group">
                <label class="col-form-label" for="user_verification">@lang('juzaweb::app.user_e_mail_verification')</label>
                <select name="user_verification" id="user_verification" class="form-control">
                    <option value="1" @if($verification == 1) selected @endif>@lang('juzaweb::app.enabled')</option>
                    <option value="0" @if($verification == 0) selected @endif>@lang('juzaweb::app.disabled')</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"></div>

        <div class="col-md-6">
            <div class="btn-group float-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> @lang('juzaweb::app.save')
                </button>

                <button type="reset" class="btn btn-default">
                    <i class="fa fa-refresh"></i> @lang('juzaweb::app.reset')
                </button>
            </div>
        </div>
    </div>
</form>