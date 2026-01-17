<div class="form-group">
    <label class="col-form-label">{{ __('admin::translation.link_text') }}</label>
    <input type="text" class="form-control change-label menu-data" data-name="label" required value="{{ $item->label }}">
</div>

<div class="form-group">
    <label class="col-form-label">{{ __('admin::translation.url') }}</label>
    <input type="text" class="form-control menu-data" data-name="link" placeholder="https://" value="{{ $item->link }}">
</div>
