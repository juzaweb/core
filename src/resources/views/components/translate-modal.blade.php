@php
    $languages = languages();
@endphp

<div class="modal fade" id="translate-modal" tabindex="-1" role="dialog" aria-labelledby="translateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="translateModalLabel">{{ __('admin::translation.translate') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('admin::translation.source_language') }}</label>
                    <select name="source_locale" class="form-control" id="translate-source-locale">
                        @foreach ($languages as $language)
                            <option value="{{ $language->code }}" @selected($language->code === app()->getLocale())>
                                {{ $language->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('admin::translation.target_language') }}</label>
                    <select name="target_locale" class="form-control" id="translate-target-locale">
                        @foreach ($languages as $language)
                            <option value="{{ $language->code }}">{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('admin::translation.close') }}</button>
                <button type="button" class="btn btn-primary"
                        id="translate-submit" data-model="{{ encrypt($translateModel) }}">{{ __('admin::translation.translate') }}</button>
            </div>
        </div>
    </div>
</div>