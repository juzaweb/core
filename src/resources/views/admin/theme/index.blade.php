@extends('core::layouts.admin')

@section('head')
    <style>
        #theme-list .theme-list-item .card-bottom {
            position: absolute;
            background: rgb(255 255 255 / 88%);
            width: 100%;
            bottom: 0;
            display: none;
        }

        #theme-list .theme-list-item:hover .card-bottom {
            display: block;
        }

        #theme-list .theme-list-item .height-200 {
            height: 200px;
        }

        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link:hover {
            border: none;
            color: #007bff;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            border: none;
            border-bottom: 3px solid #007bff;
            background-color: transparent;
        }
    </style>
@endsection

@section('content')

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="float-right">
                @if (config('themes.upload_enabled'))
                    <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#upload-theme-modal">
                        <i class="fas fa-cloud-upload-alt"></i> {{ __('core::translation.upload_theme') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.themes.index') }}">
                <i class="fa fa-laptop"></i> {{ __('core::translation.installed_themes') }}
            </a>
        </li>
        @if (config('themes.upload_enabled'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.themes.marketplace') }}">
                <i class="fa fa-store"></i> {{ __('core::translation.marketplace') }}
            </a>
        </li>
        @endif
    </ul>

    <div class="row" id="theme-list">
        <div class="col-md-4 p-2 theme-list-item">
            @component('core::admin.theme.components.theme-item', ['theme' => $currentTheme, 'active' => true])
            @endcomponent
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Upload Theme Modal -->
    <div class="modal fade" id="upload-theme-modal" tabindex="-1" role="dialog" aria-labelledby="upload-theme-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="upload-theme-modal-label">
                        <i class="fa fa-cloud-upload-alt"></i> {{ __('core::translation.upload_theme') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="{{ __('core::translation.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="upload-container p-2">
                        <form action="{{ route('upload.temp') }}" role="form" id="uploadThemeForm" name="uploadThemeForm"
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
                        <h4>{{ __('core::translation.installing_theme') }}</h4>
                        <p class="text-muted">{{ __('core::translation.please_wait') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        Dropzone.autoDiscover = false;

        $(function() {
            // Initialize Dropzone for theme upload
            let dropzoneInstance = new Dropzone("#uploadThemeForm", {
                paramName: "file",
                uploadMultiple: false,
                parallelUploads: 1,
                timeout: 0,
                // maxFiles: 1,
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
                    this.on('sending', function(file, xhr, formData) {
                        //console.log('Sending file:', file.name);
                    });

                    this.on('uploadprogress', function(file, progress, bytesSent) {
                        //console.log('Upload progress:', progress + '%');
                    });

                    this.on('success', function(file, res) {
                        let response = JSON.parse(file.xhr.response);

                        if (response.status && response.path) {
                            // Hide upload form and show loading
                            $('.upload-container').hide();
                            $('.loading-container').show();

                            // Call install API
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('admin.themes.install-from-zip') }}',
                                data: {
                                    path: response.path,
                                    _token: '{{ csrf_token() }}'
                                },
                                dataType: 'json',
                                success: function(installResponse) {
                                    if (installResponse && installResponse.status) {
                                        show_message({
                                            success: true,
                                            message: installResponse
                                                .message ||
                                                '{{ __('core::translation.theme_installed_successfully') }}'
                                        });

                                        $('#upload-theme-modal').modal('hide');
                                        // Reload page to show new theme
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 1500);
                                    } else {
                                        /*show_notify({
                                            success: false,
                                            message: installResponse
                                                .message ||
                                                '{{ __('core::translation.theme_installation_failed') }}'
                                        });*/

                                        // Mark file as error in Dropzone
                                        dropzoneInstance.emit('error', file,
                                            installResponse.message ||
                                            '{{ __('core::translation.theme_installation_failed') }}'
                                        );
                                    }
                                },
                                error: function(xhr) {
                                    // Reset modal state on error
                                    $('.loading-container').hide();
                                    $('.upload-container').show();

                                    // show_notify(xhr.responseJSON);
                                    // Mark file as error in Dropzone
                                    dropzoneInstance.emit('error', file,
                                        xhr.responseJSON.message ||
                                        '{{ __('core::translation.theme_installation_failed') }}'
                                    );
                                }
                            });
                        } else {
                            show_notify({
                                success: false,
                                message: response.message ||
                                    '{{ __('Response does not have status and path') }}',
                            });
                        }
                    });

                    this.on('error', function(file, errorMessage, xhr) {
                        console.error('Upload error:', errorMessage);
                        console.error('XHR status:', xhr ? xhr.status : 'N/A');
                        show_message(errorMessage.message || errorMessage, 'error');
                    });

                    this.on('complete', function(file) {
                        console.log('Upload complete for file:', file.name);
                    });
                }
            });

            // Reset modal state when modal is closed
            $('#upload-theme-modal').on('hidden.bs.modal', function() {
                $('.loading-container').hide();
                $('.upload-container').show();
            });

            // Theme list logic
            let pageSize = 12;
            let offset = 0;
            let total = 0;
            let page = 1;

            function loadData() {
                let jqxhr = $.ajax({
                    type: this.method,
                    url: '{{ route('admin.themes.get-data') }}',
                    dataType: 'json',
                    cache: false,
                    async: false,
                    data: {
                        page: page,
                        limit: pageSize,
                    }
                });

                let response = jqxhr.responseJSON;
                total = response.total;

                if (response.html) {
                    $('#theme-list').append(response.html);
                }
            }

            loadData();

            $('#theme-list').on('click', '.active-theme', function() {
                let btn = $(this);
                let icon = btn.find('i').attr('class');
                let theme = btn.data('theme');

                btn.find('i').attr('class', 'fa fa-spinner fa-spin');
                btn.prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.themes.activate') }}",
                    dataType: 'json',
                    data: {
                        theme: theme
                    }
                }).done(function(response) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);

                    if (response.status === false) {
                        show_message(response.data.message);
                        return false;
                    }

                    window.location.reload();
                    return false;
                }).fail(function(response) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);
                    show_message(response);
                    return false;
                });
            });

            $(window).scroll(function() {
                if ($(window).scrollTop() === $(document).height() - $(window).height()) {
                    if (offset + pageSize < total) {
                        page = page + 1;
                        loadData();
                    }
                }
            });
        });
    </script>
@endsection
