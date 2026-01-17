<div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="upload-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs mb-0" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="upload-tab" data-toggle="tab" href="#upload" role="tab">
                                <i class="fa fa-cloud-upload-alt"></i> {{ __('core::translation.upload_media') }}
                            </a>
                        </li>
                        @if (config('media.upload_from_url'))
                            <li class="nav-item">
                                <a class="nav-link" id="import-tab" data-toggle="tab" href="#import" role="tab">
                                    <i class="fa fa-link"></i> {{ __('core::translation.upload_from_url') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="{{ __('core::translation.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">
                        <div class="upload-container p-2">
                            <form action="{{ route('media.upload', [$websiteId, 'public']) }}" role="form"
                                id="uploadForm" name="uploadForm" method="post" class="dropzone"
                                enctype='multipart/form-data'>
                                <input type="hidden" name="working_dir" id='working_dir' value="{{ $folderId }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="dz-message text-center">
                                    <i class="fa fa-cloud-upload-alt fa-4x mb-3 text-muted"></i>
                                    <h4>{{ __('core::browser.message-drop') }}</h4>
                                    <p class="text-muted">{{ __('core::browser.message-choose') }}</p>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="import" role="tabpanel" aria-labelledby="import-tab">
                        <div class="upload-container p-4">
                            <form action="{{ route('media.import', [$websiteId, 'public']) }}" role="form"
                                method="post" class="form-ajax" data-success="add_folder_success">

                                {{ Field::text(__('core::translation.url'), 'url', ['required' => true]) }}

                                <div class="form-check">
                                    <input type="checkbox" name="download" class="form-check-input" value="1"
                                        id="download-checkbox" checked>
                                    <label class="form-check-label"
                                        for="download-checkbox">{{ __('core::translation.download_to_server') }}</label>
                                </div>

                                <input type="hidden" name="working_dir" id='working_dir' value="{{ $folderId }}">

                                <button type="submit" class="btn btn-success mt-2">
                                    <i class="fa fa-cloud-upload"></i> {{ __('core::translation.upload_file') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
