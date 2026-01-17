<div class="form-group">
    @php
        $path = $options['value'] ?? null;
        $path = is_url($path) ? upload_path_format($path) : $path;
    @endphp
    <label class="col-form-label">{{ $label ?? $name }}</label>
    <div class="form-image-modal text-center @if ($path) previewing @endif" data-type="image">

        <a href="javascript:void(0)" class="image-clear">
            <i class="fa fa-times-circle fa-2x"></i>
        </a>

        <input type="hidden" name="{{ $name }}" class="input-path" value="{{ $path }}">

        <div class="dropify-preview image-hidden" @if ($path) style="display: block" @endif>
            <span class="dropify-render">
                @if (!empty($path))
                    <img src="{{ proxy_image(upload_url($path), 250, 250) }}" alt="{{ $name }}">
                @endif
            </span>
            <div class="dropify-infos">
                <div class="dropify-infos-inner">
                    <p class="dropify-filename">
                        <span class="dropify-filename-inner"></span>
                    </p>
                </div>
            </div>
        </div>

        <div class="icon-choose">
            <i class="fa fa-cloud-upload fa-5x"></i>
            <p>{{ trans('admin::translation.click_here_to_select_file') }}</p>
        </div>
    </div>
</div>
