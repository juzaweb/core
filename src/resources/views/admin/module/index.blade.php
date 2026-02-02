@extends('core::layouts.admin')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="float-right">
                <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#upload-module-modal">
                    <i class="fas fa-cloud-upload-alt"></i> {{ __('core::translation.upload_module') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row" id="module-list">
        @foreach($modules as $module)
            <div class="col-md-4 col-lg-3 p-2 module-list-item">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $module->get('title', $module->getStudlyName()) }}</h5>
                        <p class="card-text">{{ $module->getDescription() }}</p>
                        <p class="card-text"><small class="text-muted">Version: {{ $module->get('version') }}</small></p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge badge-{{ $module->isEnabled() ? 'success' : 'secondary' }}">
                                    {{ $module->isEnabled() ? __('core::translation.active') : __('core::translation.inactive') }}
                                </span>
                                <button class="btn btn-danger btn-sm ml-2 delete-module" data-module="{{ $module->getLowerName() }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input toggle-module"
                                       id="module-toggle-{{ $module->getLowerName() }}"
                                       data-module="{{ $module->getLowerName() }}"
                                       {{ $module->isEnabled() ? 'checked' : '' }}>
                                <label class="custom-control-label" for="module-toggle-{{ $module->getLowerName() }}"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <!-- Upload Module Modal -->
    <div class="modal fade" id="upload-module-modal" tabindex="-1" role="dialog" aria-labelledby="upload-module-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="upload-module-modal-label">
                        <i class="fa fa-cloud-upload-alt"></i> {{ __('core::translation.upload_module') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="{{ __('core::translation.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="upload-container p-2">
                        <form action="{{ route('upload.temp') }}" role="form" id="uploadModuleForm" name="uploadModuleForm"
                            method="post" class="dropzone" enctype='multipart/form-data'>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="dz-message text-center">
                                <i class="fa fa-cloud-upload-alt fa-4x mb-3 text-muted"></i>
                                <h4>{{ __('core::browser.message-drop') }}</h4>
                                <p class="text-muted">{{ __('core::browser.message-choose') }}</p>
                                <p class="text-info"><small>{{ __('core::translation.accepted_file_types') }}: .zip</small>
                                </p>
                            </div>
                        </form>
                    </div>
                    <div class="loading-container p-5 text-center" style="display: none;">
                        <i class="fas fa-spinner fa-spin fa-3x mb-3 text-primary"></i>
                        <h4>{{ __('core::translation.installing_module') }}</h4>
                        <p class="text-muted">{{ __('core::translation.please_wait') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        Dropzone.autoDiscover = false;

        $(function () {
            // Initialize Dropzone for module upload
            let dropzoneInstance = new Dropzone("#uploadModuleForm", {
                paramName: "file",
                uploadMultiple: false,
                parallelUploads: 1,
                timeout: 0,
                acceptedFiles: ".zip",
                maxFilesize: 100, // 100MB
                dictDefaultMessage: "{{ __('core::browser.message-drop') }}",
                chunking: true,
                forceChunking: true,
                chunkSize: 1000000, // 1MB chunks
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'accept': 'application/json',
                },
                init: function() {
                    this.on('success', function(file, res) {
                        let response = JSON.parse(file.xhr.response);

                        if (response.status && response.path) {
                            $('.upload-container').hide();
                            $('.loading-container').show();

                            $.ajax({
                                type: 'POST',
                                url: '{{ route('admin.modules.install-from-zip') }}',
                                data: {
                                    path: response.path,
                                    _token: '{{ csrf_token() }}'
                                },
                                dataType: 'json',
                                success: function(installResponse) {
                                    if (installResponse && installResponse.success) {
                                        show_message({
                                            success: true,
                                            message: installResponse.message || '{{ __('core::translation.module_installed_successfully') }}'
                                        });

                                        $('#upload-module-modal').modal('hide');
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 1500);
                                    } else {
                                        dropzoneInstance.emit('error', file,
                                            installResponse.message ||
                                            '{{ __('core::translation.module_installation_failed') }}'
                                        );
                                        $('.loading-container').hide();
                                        $('.upload-container').show();
                                    }
                                },
                                error: function(xhr) {
                                    $('.loading-container').hide();
                                    $('.upload-container').show();
                                    dropzoneInstance.emit('error', file,
                                        xhr.responseJSON.message ||
                                        '{{ __('core::translation.module_installation_failed') }}'
                                    );
                                }
                            });
                        } else {
                            show_notify({
                                success: false,
                                message: response.message || '{{ __('Response does not have status and path') }}',
                            });
                        }
                    });

                    this.on('error', function(file, errorMessage, xhr) {
                        show_message(errorMessage.message || errorMessage, 'error');
                    });
                }
            });

            $('#upload-module-modal').on('hidden.bs.modal', function() {
                $('.loading-container').hide();
                $('.upload-container').show();
            });

            $('.toggle-module').on('change', function () {
                let checkbox = $(this);
                let module = checkbox.data('module');
                let status = checkbox.is(':checked') ? 1 : 0;

                checkbox.prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.modules.toggle') }}",
                    dataType: 'json',
                    data: {
                        module: module,
                        status: status
                    }
                }).done(function (response) {
                    checkbox.prop('disabled', false);

                    if (response.success === false) {
                        show_message(response.data.message);
                        checkbox.prop('checked', !status); // Revert status
                        return false;
                    }

                    show_message(response.data.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                }).fail(function (response) {
                    checkbox.prop('disabled', false);
                    checkbox.prop('checked', !status); // Revert status
                    show_message(response);
                    return false;
                });
            });

            $('.delete-module').on('click', function() {
                let btn = $(this);
                let module = btn.data('module');

                if (!confirm("{{ __('core::message.are_you_sure') }}")) {
                    return false;
                }

                btn.prop("disabled", true);
                btn.find('i').attr('class', 'fa fa-spinner fa-spin');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.modules.delete') }}",
                    dataType: 'json',
                    data: {
                        module: module,
                        _token: "{{ csrf_token() }}"
                    }
                }).done(function(response) {
                    if (response.success === false) {
                        show_message(response.message);
                        btn.prop("disabled", false);
                        btn.find('i').attr('class', 'fa fa-trash');
                        return false;
                    }

                    show_message(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }).fail(function(response) {
                    btn.prop("disabled", false);
                    btn.find('i').attr('class', 'fa fa-trash');
                    show_message(response.responseJSON.message);
                });
            });
        });
    </script>
@endsection
