$(function () {
    const bodyElement = $('body');
    let currentPage = 1;
    let hasMorePages = true;
    let isLoading = false;
    let currentFolderId = null;
    let currentType = null;

    // Create Modal HTML if not exists
    if ($('#jw-media-modal').length === 0) {
        let uploadUrl = '/admin/file-manager/public/upload';
        if (typeof juzaweb !== 'undefined' && juzaweb.websiteId) {
            uploadUrl = '/admin/' + juzaweb.websiteId + '/file-manager/public/upload';
        }

        let modalHtml = `
            <div class="modal fade" id="jw-media-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl" style="max-width: 90%; height: 90%; margin: 30px auto;">
                    <div class="modal-content" style="height: 100%;">
                        <div class="modal-header" style="display: block;">
                            <div class="d-flex justify-content-between align-items-center">
                                <ul class="nav nav-tabs mb-0" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="library-tab" data-toggle="tab" href="#library" role="tab">
                                            <i class="fa fa-images"></i> Media Library
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="upload-tab" data-toggle="tab" href="#upload" role="tab">
                                            <i class="fa fa-cloud-upload-alt"></i> Upload Files
                                        </a>
                                    </li>
                                </ul>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="modal-body p-0" style="overflow-y: auto;">
                            <div class="tab-content h-100">
                                <div class="tab-pane fade show active h-100" id="library" role="tabpanel">
                                    <div class="media-list-container p-3 h-100" style="overflow-y: auto;">
                                        <ul class="media-list row list-unstyled"></ul>
                                        <div class="loading-indicator text-center p-3" style="display: none;">
                                            <i class="fa fa-spinner fa-spin fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade h-100" id="upload" role="tabpanel">
                                    <div class="upload-container p-4">
                                        <form action="${uploadUrl}" id="dropzoneForm" class="dropzone">
                                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                            <input type="hidden" name="working_dir" id="upload_working_dir" value="">
                                            <div class="dz-message text-center p-5">
                                                <i class="fa fa-cloud-upload-alt fa-4x mb-3 text-muted"></i>
                                                <h4>Drop files here or click to upload</h4>
                                                <p class="text-muted">You can upload multiple files at once</p>
                                            </div>
                                        </form>
                                        <div class="upload-info mt-3 alert alert-info" style="display: none;">
                                            <i class="fa fa-info-circle"></i> <span class="upload-message"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        bodyElement.append(modalHtml);
    }

    const modal = $('#jw-media-modal');
    const mediaList = modal.find('.media-list');
    const loadingIndicator = modal.find('.loading-indicator');
    const modalBody = modal.find('.modal-body');

    let currentTarget = null;

    function loadMedia(page, folderId = null, type = null, append = true) {
        if (isLoading) return;
        isLoading = true;
        loadingIndicator.show();

        let prefix = '/admin/media/load-more';
        if (typeof juzaweb !== 'undefined' && juzaweb.websiteId) {
            prefix = '/admin/' + juzaweb.websiteId + '/media/load-more';
        }

        if (folderId) {
            prefix = '/admin/' + juzaweb.websiteId + '/media/folder/' + folderId + '/load-more';
        }

        let ajaxData = { page: page };
        if (type) {
            ajaxData.type = type;
        }

        $.ajax({
            url: prefix,
            type: 'GET',
            data: ajaxData,
            success: function (response) {
                if (append) {
                    mediaList.append(response.html);
                } else {
                    mediaList.html(response.html);
                }

                currentPage = response.current_page;
                hasMorePages = response.has_more;
                isLoading = false;
                loadingIndicator.hide();

                // Initialize lazyload if available
                if (typeof lazySizes !== 'undefined' && lazySizes.autoSizer) {
                    lazySizes.autoSizer.checkElems();
                }
                // if (typeof lazyload !== 'undefined') {
                //     lazyload();
                // }
            },
            error: function () {
                isLoading = false;
                loadingIndicator.hide();
            }
        });
    }

    bodyElement.on('click', '.form-image-modal', function () {
        currentTarget = $(this);

        // Reset state
        currentPage = 1;
        hasMorePages = true;
        currentFolderId = null;
        currentType = $(this).data('type') || null;
        mediaList.empty();

        loadMedia(currentPage, currentFolderId, currentType);
        modal.modal('show');
    });

    // Handler for upload-url field media picker
    bodyElement.on('click', '.upload-url-media-picker', function () {
        let inputId = $(this).data('input');
        let inputField = $('#' + inputId);

        if (inputField.length === 0) return;

        // Store reference to input field
        currentTarget = inputField;

        // Reset state
        currentPage = 1;
        hasMorePages = true;
        currentFolderId = null;
        currentType = $(this).data('type') || null;
        mediaList.empty();

        loadMedia(currentPage, currentFolderId, currentType);
        modal.modal('show');
    });

    // Handler for TinyMCE file picker
    bodyElement.on('click', '.tinymce-media-picker', function () {
        // Reset state
        currentPage = 1;
        hasMorePages = true;
        currentFolderId = null;
        currentType = $(this).data('type') || null;
        mediaList.empty();

        // Set TinyMCE mode flag
        modal.data('tinymce-mode', true);

        loadMedia(currentPage, currentFolderId, currentType);
        modal.modal('show');
    });

    // Handler for images field (multiple selection)
    let selectedImages = [];
    let imagesContainer = null;

    bodyElement.on('click', '.add-image-images-modal', function () {
        imagesContainer = $(this).closest('.form-images');
        selectedImages = [];

        // Reset state
        currentPage = 1;
        hasMorePages = true;
        currentFolderId = null;
        currentType = $(this).data('type') || 'image';
        mediaList.empty();

        // Set modal to multiple selection mode
        modal.data('multiple-mode', true);
        modal.find('#library .media-list-container').prepend('<div class="text-right mb-2"><button type="button" class="btn btn-success btn-confirm-selection" style="display:none;"><i class="fa fa-check"></i> Add Selected</button></div>');

        loadMedia(currentPage, currentFolderId, currentType);
        modal.modal('show');
    });

    // Toggle image selection in multiple mode
    modal.on('click', '.media-file-item', function (e) {
        if (!modal.data('multiple-mode')) {
            return; // Let default handler work for single selection
        }

        e.preventDefault();
        e.stopPropagation();

        let item = $(this);
        let infoText = item.find('.item-info').val();
        let file = JSON.parse(infoText);

        // Toggle selection
        if (item.hasClass('selected')) {
            item.removeClass('selected');
            item.find('.selection-checkmark').remove();
            selectedImages = selectedImages.filter(f => f.path !== file.path);
        } else {
            item.addClass('selected');
            item.find('.attachment-preview').append('<div class="selection-checkmark"><i class="fa fa-check-circle"></i></div>');
            selectedImages.push(file);
        }

        // Show/hide confirm button
        if (selectedImages.length > 0) {
            modal.find('.btn-confirm-selection').show();
        } else {
            modal.find('.btn-confirm-selection').hide();
        }
    });

    // Confirm multiple selection
    modal.on('click', '.btn-confirm-selection', function () {
        if (!imagesContainer || selectedImages.length === 0) return;

        let inputName = imagesContainer.find('.input-name').val();
        let temp = document.getElementById('form-images-template').innerHTML;
        let str = "";

        $.each(selectedImages, function (index, file) {
            str += replace_template(temp, {
                name: inputName,
                url: file.url,
                path: file.path,
            });
        });

        imagesContainer.find('.images-list .image-item:last').before(str);
        modal.modal('hide');
    });

    // Reset multiple mode when modal closes
    modal.on('hidden.bs.modal', function () {
        modal.data('multiple-mode', false);
        modal.data('tinymce-mode', false);
        modal.find('.btn-confirm-selection').remove();
        modal.find('.media-file-item').removeClass('selected');
        modal.find('.selection-checkmark').remove();
        selectedImages = [];
        imagesContainer = null;
    });

    // Infinite scroll
    modalBody.on('scroll', function () {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 100) {
            if (hasMorePages && !isLoading) {
                loadMedia(currentPage + 1, currentFolderId, currentType);
            }
        }
    });

    // Handle item selection (files) - single selection mode
    modal.on('click', '.media-file-item', function (e) {
        // Skip if in multiple mode - let multiple handler work
        if (modal.data('multiple-mode')) {
            return;
        }

        e.preventDefault();

        let infoText = $(this).find('.item-info').val();
        let file = JSON.parse(infoText);

        // Check if in TinyMCE mode
        if (modal.data('tinymce-mode')) {
            if (window.tinyMceCallback) {
                window.tinyMceCallback(file.url);
                window.tinyMceCallback = null;
            }
            modal.modal('hide');
            return;
        }

        if (!currentTarget) return;

        // Check if currentTarget is an input field (upload-url) or form-image-modal
        if (currentTarget.is('input')) {
            // For upload-url field: just set the URL
            currentTarget.val(file.path);
        } else {
            // For form-image-modal: set image preview
            let targetInput = currentTarget.find('.input-path');
            let targetPreview = currentTarget.find('.dropify-render');
            let targetName = currentTarget.find('.dropify-filename-inner');

            targetInput.val(file.path);
            targetPreview.html('<img src="' + file.url + '" alt="">');
            targetName.html(file.name);

            currentTarget.addClass('previewing');
            currentTarget.find('.image-hidden').show();
        }

        modal.modal('hide');
    });

    // Handle folder navigation
    modal.on('click', '.media-item a:not(.media-file-item)', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        if (id) {
            currentFolderId = id;
            currentPage = 1;
            hasMorePages = true;
            loadMedia(currentPage, currentFolderId, currentType, false);
        }
    });

    bodyElement.on('click', '.form-image-modal .image-clear', function (e) {
        e.stopPropagation();
        let item = $(this).closest('.form-image-modal');
        let targetInput = item.find('.input-path');
        let targetPreview = item.find('.dropify-render');
        let targetName = item.find('.dropify-filename-inner');
        targetInput.val("");
        targetPreview.html('');
        targetName.html("");
        item.removeClass('previewing');
        item.find('.image-hidden').hide();
    });

    // Dropzone instance
    let dropzoneInstance = null;

    // Initialize Dropzone when Upload tab is clicked
    modal.on('shown.bs.tab', '#upload-tab', function (e) {
        if (dropzoneInstance) return; // Already initialized

        Dropzone.autoDiscover = false;

        let acceptedFiles = null;
        if (currentType) {
            // Get mime types from config based on type
            let mimeTypes = {
                'image': 'image/*',
                'video': 'video/*',
                'audio': 'audio/*',
                'document': '.pdf,.doc,.docx,.txt',
                'file': '*'
            };
            acceptedFiles = mimeTypes[currentType] || null;
        }

        dropzoneInstance = new Dropzone("#dropzoneForm", {
            paramName: "upload",
            uploadMultiple: false,
            parallelUploads: 5,
            timeout: 0,
            acceptedFiles: acceptedFiles,
            maxFilesize: 15, // MB
            chunking: true,
            forceChunking: true,
            chunkSize: 1048576, // 1MB chunks
            headers: {
                'Authorization': "Bearer " + $('meta[name="csrf-token"]').attr('content')
            },
            init: function () {
                this.on('success', function (file, response) {
                    // Show success message
                    modal.find('.upload-info').show().removeClass('alert-danger').addClass('alert-success');
                    modal.find('.upload-message').text('File uploaded successfully!');

                    // Remove file from dropzone
                    this.removeFile(file);

                    // Reload media list
                    currentPage = 1;
                    hasMorePages = true;
                    mediaList.empty();
                    loadMedia(currentPage, currentFolderId, currentType, false);

                    // Switch back to Library tab after short delay
                    setTimeout(function () {
                        $('#library-tab').tab('show');
                        modal.find('.upload-info').hide();
                    }, 1500);
                });

                this.on('error', function (file, errorMessage) {
                    modal.find('.upload-info').show().removeClass('alert-success').addClass('alert-danger');
                    modal.find('.upload-message').text('Upload failed: ' + (errorMessage.message || errorMessage));
                    this.removeFile(file);
                });

                this.on('sending', function (file, xhr, formData) {
                    formData.append('working_dir', currentFolderId || '');
                });
            }
        });
    });

    // Update working_dir when opening modal
    bodyElement.on('show.bs.modal', '#jw-media-modal', function () {
        $('#upload_working_dir').val(currentFolderId || '');
    });
});
