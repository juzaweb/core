<div class="form-group">
    @php
        $path = $options['value'] ?? null;
    @endphp
    <label class="col-form-label">{{ $label ?? $name }}</label>
    <div class="form-image text-center @if($path) previewing @endif">

        <a href="javascript:void(0)" class="image-clear">
            <i class="fa fa-times-circle fa-2x"></i>
        </a>

        <input type="hidden" name="{{ $name }}" class="input-path" value="{{ $path }}">

        <div class="dropify-preview image-hidden" @if($path) style="display: block" @endif>
            <span class="dropify-render">
                @if(!empty($path))
                    <img src="{{ upload_url($path) }}" alt="{{ $name }}">
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
            <p>{{ trans('Click here to select file') }}</p>
        </div>
    </div>
</div>