<form method="post" action="{{ route('admin.setting.save') }}" class="form-ajax">
    <input type="hidden" name="form" value="general">
    @php
        $registration = get_config('user_registration');
        $verification = get_config('user_verification');
        //$only_member_view = get_config('only_member_view');
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
            <h5>@lang('juzaweb::app.general')</h5>

            <div class="form-group">
                <label class="col-form-label" for="title">@lang('juzaweb::app.home_title')</label>

                <input type="text" name="title" class="form-control" id="title" value="{{ get_config('title') }}" autocomplete="off" required>
            </div>

            <div class="form-group">
                <label class="col-form-label" for="keywords">@lang('juzaweb::app.keywords')</label>

                <input type="text" name="keywords" id="keywords" class="form-control" value="{{ get_config('keywords') }}" autocomplete="off">
                <em class="description">@lang('juzaweb::app.use_comma_to_separate_keyword')</em>
            </div>

            <div class="form-group">
                <label class="col-form-label" for="description">@lang('juzaweb::app.home_description')</label>
                <textarea class="form-control" name="description" id="description" rows="5">{{ get_config('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="col-form-label" for="logo">@lang('juzaweb::app.logo') <span class="float-right"><a href="javascript:void(0)" data-input="logo" data-preview="preview-logo" class="file-manager"><i class="fa fa-edit"></i> @lang('juzaweb::app.change_image')</a></span></label>
                <div id="preview-logo">
                    <img src="{{ image_url(get_config('logo')) }}" alt="" class="w-25">
                </div>
                <input id="logo" class="form-control" type="hidden" name="logo" value="{{ get_config('logo') }}">
            </div>

            <div class="form-group">
                <label class="col-form-label" for="icon">@lang('juzaweb::app.icon') <span class="float-right"><a href="javascript:void(0)" data-input="icon" data-preview="preview-icon" class="file-manager"><i class="fa fa-edit"></i> @lang('juzaweb::app.change_image')</a></span></label>
                <div id="preview-icon">
                    <img src="{{ image_url(get_config('icon')) }}" alt="" class="w-25">
                </div>
                <input id="icon" class="form-control" type="hidden" name="icon" value="{{ get_config('icon') }}">
            </div>

            <div class="form-group">
                <label class="col-form-label" for="banner">@lang('juzaweb::app.banner') <span class="float-right"><a href="javascript:void(0)" data-input="banner" data-preview="preview-banner" class="file-manager"><i class="fa fa-edit"></i> @lang('juzaweb::app.change_image')</a></span></label>
                <div id="preview-banner">
                    <img src="{{ image_url(get_config('banner')) }}" alt="" class="w-25">
                </div>
                <input id="banner" class="form-control" type="hidden" name="banner" value="{{ get_config('banner') }}">
            </div>
        </div>

        <div class="col-md-4">
            <h5>@lang('juzaweb::app.social')</h5>

            <div class="form-group">
                <label class="col-form-label" for="fb_app_id">@lang('juzaweb::app.fb_app_id')</label>

                <input type="text" name="fb_app_id" class="form-control" id="fb_app_id" value="{{ get_config('fb_app_id') }}" autocomplete="off">
            </div>

            <div class="form-group">
                <label class="col-form-label" for="google_analytics">@lang('juzaweb::app.google_analytics_id')</label>

                <input type="text" name="google_analytics" class="form-control" id="google_analytics" value="{{ get_config('google_analytics') }}" autocomplete="off">
            </div>

            <h5>@lang('juzaweb::app.registration')</h5>

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

    {{--<h5>@lang('juzaweb::app.tmdb')</h5>
    <div class="form-group">
        <label class="col-form-label" for="tmdb_api_key">@lang('juzaweb::app.tmdb_api_key')</label>

        <input type="text" name="tmdb_api_key" class="form-control" id="tmdb_api_key" value="{{ get_config('tmdb_api_key') }}" autocomplete="off">
    </div>--}}

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