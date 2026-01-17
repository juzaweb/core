$(function () {
    const mediaContainer = $('#media-container');
    let isLoading = false;
    let currentPage = window.mediaInfiniteScroll ? window.mediaInfiniteScroll.currentPage : 1;
    let hasMorePages = window.mediaInfiniteScroll ? window.mediaInfiniteScroll.hasMorePages : true;
    let contextMenuItem = null;

    mediaContainer.on('click', '.show-form-upload', function () {
        let form = $('.media-upload-form');

        if (form.is(':hidden')) {
            form.show('slow');
        } else {
            form.hide('slow');
        }
    });

    // Context menu functionality
    mediaContainer.on('contextmenu', '.media-item', function (e) {
        e.preventDefault();
        
        contextMenuItem = $(this);
        const contextMenu = $('#context-menu');
        
        // Position the context menu
        contextMenu.css({
            display: 'block',
            left: e.pageX + 'px',
            top: e.pageY + 'px'
        });
        
        return false;
    });

    // Hide context menu when clicking elsewhere
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#context-menu').length) {
            $('#context-menu').hide();
        }
    });

    // Handle context menu actions
    $('#context-menu').on('click', '.context-menu-item', function () {
        const action = $(this).data('action');
        $('#context-menu').hide();
        
        if (!contextMenuItem) return;
        
        if (action === 'view-detail') {
            const mediaLink = contextMenuItem.find('.media-item-info');
            if (mediaLink.hasClass('media-file-item')) {
                showPreviewModal(mediaLink);
            }
        } else if (action === 'delete') {
            const mediaLink = contextMenuItem.find('.media-item-info');
            const info = JSON.parse(mediaLink.find('.item-info').val());
            deleteMediaItem(info.id, info.is_file, info.name);
        }
        
        contextMenuItem = null;
    });

    function showPreviewModal(mediaLink) {
        let temp = document.getElementById('media-detail-template').innerHTML;
        let info = JSON.parse(mediaLink.find('.item-info').val());

        info.name = htmlspecialchars(info.name);
        temp = replace_template(temp, info);

        const modal = $('#media-preview-modal');
        modal.find('.modal-body').html(temp);
        modal.modal('show');
    }

    mediaContainer.on('click', '.media-file-item', function () {
        showPreviewModal($(this));
    });

    function deleteMediaItem(id, is_file, name) {
        confirm_message(
            juzaweb.lang.remove_question.replace(':name', (is_file == 1 ? ' file '+ name : ' folder '+ name)),
            function (value) {
                if (!value) {
                    return false;
                }

                toggle_global_loading(true);
                $.ajax({
                    url: juzaweb.adminUrl + '/media/'+ id,
                    type: 'DELETE',
                    data: {
                        is_file: is_file
                    },
                    success: function (response) {
                        toggle_global_loading(false);
                        show_notify(response);

                        setTimeout(
                            function () {
                                window.location.reload();
                            },
                            500
                        );
                    },
                    error: function (response) {
                        toggle_global_loading(false);
                        show_notify(response);
                    }
                });
            }
        );
    }

    $('#media-preview-modal').on('click', '.delete-file', function () {
        let id = $(this).data('id');
        let is_file = $(this).data('is_file');
        let name = $(this).data('name');
        deleteMediaItem(id, is_file, name);
    });

    // Infinite scroll functionality
    function loadMoreMedia() {
        if (isLoading || !hasMorePages) {
            return;
        }

        isLoading = true;
        $('.loading-indicator').show();

        const nextPage = currentPage + 1;
        const searchParams = new URLSearchParams(window.location.search);
        searchParams.set('page', nextPage);

        const folderId = $('#media-container').data('folder-id');
        let loadMoreUrl = juzaweb.adminUrl + '/media/load-more';
        
        if (folderId) {
            loadMoreUrl = juzaweb.adminUrl + '/media/folder/' + folderId + '/load-more';
        }

        $.ajax({
            url: loadMoreUrl + '?' + searchParams.toString(),
            type: 'GET',
            success: function (response) {
                if (response.html && response.html.trim() !== '') {
                    const mediaList = $('.media-list');
                    mediaList.append(response.html);

                    // Initialize lazyload for new images
                    if (typeof lazySizes !== 'undefined' && lazySizes.autoSizer) {
                        // Trigger lazySizes to check for new lazyload elements
                        lazySizes.autoSizer.checkElems();
                    }

                    currentPage = response.current_page;
                    hasMorePages = response.has_more;
                }

                isLoading = false;
                $('.loading-indicator').hide();
            },
            error: function (response) {
                console.error('Error loading more media:', response);
                isLoading = false;
                $('.loading-indicator').hide();
            }
        });
    }

    // Scroll event listener for infinite scroll
    $('.list-media').on('scroll', function () {
        const scrollTop = $(this).scrollTop();
        const scrollHeight = $(this)[0].scrollHeight;
        const clientHeight = $(this).height();

        // Load more when user scrolls to 80% of the container
        if (scrollTop + clientHeight >= scrollHeight * 0.8) {
            loadMoreMedia();
        }
    });
});
