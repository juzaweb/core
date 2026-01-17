<div class="theme-setting theme-setting--text editor-item">
    <label class="next-label">{{ __('core::translation.home_title') }}</label>
    <input name="setting[title]" class="next-input" value="{{ setting('title') }}">
</div>

<div class="theme-setting theme-setting--text editor-item">
    <label class="next-label">{{ __('core::translation.home_description') }}</label>
    <textarea name="setting[description]" class="next-input">{{ setting('description') }}</textarea>
</div>

<div class="theme-setting theme-setting--text editor-item">
    <label class="next-label" for="input-logo">{{ __('core::translation.logo') }}</label>
    <div class="review" id="review-logo">
        <img src="{{ upload_url(setting('logo')) }}" alt="">
    </div>

    <p><a href="javascript:void(0)" class="load-media" data-input="input-logo" data-preview="review-logo"><i
                    class="fa fa-edit"></i> {{ __('core::translation.change') }}</a></p>
    <input type="hidden" name="setting[logo]" id="input-logo" value="{{ setting('logo') }}">
</div>

<div class="theme-setting theme-setting--text editor-item">
    <label class="next-label" for="input-icon">{{ __('core::translation.icon') }}</label>
    <div class="review" id="review-icon">
        <img src="{{ upload_url(setting('icon')) }}" alt="">
    </div>

    <p><a href="javascript:void(0)" class="load-media" data-input="input-icon" data-preview="review-icon"><i
                    class="fa fa-edit"></i> {{ __('core::translation.change') }}</a></p>
    <input type="hidden" name="setting[icon]" id="input-icon" value="{{ setting('icon') }}">
</div>
