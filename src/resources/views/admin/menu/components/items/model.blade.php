<div class="form-group">
    <label class="col-form-label">{{ __('core::translation.label') }}</label>
    <input type="text" class="form-control change-label menu-data" data-name="label" value="{{ $item->label }}">
</div>

<div class="form-group">
    <label class="col-form-label">Model</label>
    <p><b><a href="{{ $item->edit_url }}">{{ $item->menuable_class_name }}</a></b></p>
</div>
