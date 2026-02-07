function initSelect2(parent = 'body') {
    $(parent + ' .select2').each(function () {
        $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') || '100%',
            dropdownAutoWidth: !$(this).data('width'),
        });
    });
}

function initDatepicker(parent = 'body') {
    $(parent + ' .jw-datepicker').each(function () {
        const options = {
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        };

        if ($(this).data('min-date')) {
            options.minDate = $(this).data('min-date');
        }

        if ($(this).data('max-date')) {
            options.maxDate = $(this).data('max-date');
        }

        if ($(this).data('date-format')) {
            options.dateFormat = $(this).data('date-format');
        }

        $(this).datepicker(options);
    });
}

function initEditor(parnet = 'body') {
    tinymce.init({
        selector: parnet + ' .jw-editor',
        convert_urls: true,
        document_base_url: juzaweb.documentBaseUrl,
        language: juzaweb.locale !== 'en' ? juzaweb.locale : undefined,
        language_url: juzaweb.locale !== 'en' ? '/plugins/tinymce/langs/' + juzaweb.locale + '.js' : undefined,
        urlconverter_callback: function (url, node, on_save, name) {
            return url.replace(juzaweb.documentBaseUrl, '');
        },
        height: 400,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table directionality",
            "emoticons template paste textpattern"
        ],
        menu: {
            file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
            edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
            view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
            insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
            format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
            tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
            table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
        },
        toolbar: [
            {
                name: 'new', items: ['newdocument']
            },
            {
                name: 'history', items: ['undo', 'redo']
            },
            {
                name: 'styles', items: ['styleselect']
            },
            {
                name: 'formatting', items: ['bold', 'italic']
            },
            {
                name: 'alignment', items: ['alignleft', 'aligncenter', 'alignright', 'alignjustify']
            },
            {
                name: 'indentation', items: ['outdent', 'indent']
            },
            {
                name: 'media', items: ['link', 'image', 'media']
            },
            {
                name: 'view', items: ['code', 'preview', 'fullscreen']
            }
        ],
        file_picker_callback: function (callback, value, meta) {
            // Store callback for later use
            window.tinyMceCallback = callback;

            // Determine type
            let type = meta.filetype === 'image' ? 'image' : 'file';

            // Get modal instance
            let modal = $('#jw-media-modal');
            if (modal.length === 0) {
                console.error('Media modal not found');
                return;
            }

            // Set up modal for TinyMCE mode
            modal.data('tinymce-mode', true);
            modal.data('tinymce-type', type);

            // Trigger modal open via a temporary element
            let tempButton = $('<button class="tinymce-media-picker" data-type="' + type + '" style="display:none;"></button>');
            $('body').append(tempButton);
            tempButton.click();
            tempButton.remove();
        }
    });
}

/**
 * Quick Add Category functionality
 * Provides reusable category quick-add functionality for any form
 */

function initQuickAddCategory(container = 'body') {
    const INDENT_PX = 20;
    const CHILD_PREFIX = '-- ';

    $(container).find('.quick-add-category-container').each(function () {
        const $container = $(this);
        const $form = $container.find('.quick-add-category-form');
        const $toggle = $container.find('.quick-add-category-toggle');
        const $saveBtn = $container.find('.quick-add-category-save');
        const $cancelBtn = $container.find('.quick-add-category-cancel');
        const $nameInput = $container.find('.quick-add-category-name');
        const $parentSelect = $container.find('.quick-add-category-parent');
        const $checkboxList = $container.find('.categories-checkbox-list');

        // Get configuration from data attributes
        const storeUrl = $container.data('store-url');
        const locale = $container.data('locale') || 'en';

        // Toggle form visibility
        $toggle.on('click', function () {
            $form.slideDown();
            $(this).hide();
            $nameInput.focus();
        });

        // Cancel button
        $cancelBtn.on('click', function () {
            $form.slideUp();
            $toggle.show();
            $nameInput.val('');
            $parentSelect.val('');
        });

        // Save button
        $saveBtn.on('click', function () {
            const name = $nameInput.val().trim();
            const parentId = $parentSelect.val();
            const inputName = $(this).data('input-name') || 'categories[]';

            if (!name) {
                alert($nameInput.attr('placeholder') || 'Please enter a category name');
                return;
            }

            const btn = $(this);
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> ' + (btn.data('saving-text') || 'Saving...'));

            const data = {
                locale: locale,
                parent_id: parentId || null,
                name: name,
            };

            $.ajax({
                url: storeUrl,
                method: 'POST',
                data: data,
                success: function (response) {
                    if (response.success && response.data) {
                        const newCategoryId = response.data.id;
                        const newCategoryName = response.data.name;

                        // Determine indentation level based on parent
                        let marginLeft = 0;
                        if (parentId) {
                            const parentCheckbox = $checkboxList
                                .find(`input[name="${inputName}"][value="${CSS.escape(parentId)}"]`)
                                .closest('.form-check');
                            const parentMargin = parseInt(parentCheckbox.css('margin-left')) || 0;
                            marginLeft = parentMargin + INDENT_PX;
                        }

                        // Create the new checkbox element using DOM manipulation for security
                        const newCheckboxDiv = $('<div>', {
                            'class': 'form-check',
                            'style': `margin-left: ${marginLeft}px;`
                        });

                        const newCheckbox = $('<input>', {
                            'class': 'form-check-input',
                            'type': 'checkbox',
                            'name': inputName,
                            'value': newCategoryId,
                            'id': `cat-${newCategoryId}`,
                            'checked': true
                        });

                        const newLabel = $('<label>', {
                            'class': 'form-check-label',
                            'for': `cat-${newCategoryId}`
                        }).text(newCategoryName);

                        newCheckboxDiv.append(newCheckbox).append(newLabel);

                        // Insert the new checkbox at the appropriate location
                        if (parentId) {
                            const parentCheckbox = $checkboxList.find(`input[name="${inputName}"][value="${CSS.escape(parentId)}"]`).closest('.form-check');
                            let insertAfter = parentCheckbox;

                            // Find last child of this parent using more efficient DOM traversal
                            const parentMarginLeft = parseInt(parentCheckbox.css('margin-left')) || 0;
                            const allNext = parentCheckbox.nextAll('.form-check');

                            allNext.each(function () {
                                const nextMargin = parseInt($(this).css('margin-left')) || 0;
                                if (nextMargin <= parentMarginLeft) {
                                    return false; // break
                                }
                                insertAfter = $(this);
                            });

                            insertAfter.after(newCheckboxDiv);
                        } else {
                            // Add at the end of the list if no parent
                            $checkboxList.append(newCheckboxDiv);
                        }

                        // Add new category to parent select dropdown using DOM manipulation
                        const newOption = $('<option>', {
                            'value': newCategoryId
                        }).text(parentId ? CHILD_PREFIX + newCategoryName : newCategoryName);

                        if (parentId && parentId !== '') {
                            // Find parent option safely
                            const parentOption = $parentSelect.find('option').filter(function () {
                                return $(this).val() === String(parentId);
                            });

                            // Find where to insert: after parent's last child using more efficient DOM traversal
                            let insertAfter = parentOption;
                            const allNext = parentOption.nextAll('option');

                            allNext.each(function () {
                                if ($(this).text().startsWith(CHILD_PREFIX)) {
                                    insertAfter = $(this);
                                } else {
                                    return false; // break
                                }
                            });

                            insertAfter.after(newOption);
                        } else {
                            $parentSelect.append(newOption);
                        }

                        // Reset form and hide
                        $nameInput.val('');
                        $parentSelect.val('');
                        // $form.slideUp();
                        // $toggle.show();
                        btn.prop('disabled', false).html(originalHtml);

                        // Show success message if provided
                        if (response.message) {
                            show_notify(response);
                        }
                    }
                },
                error: function (xhr) {
                    let message = 'An error occurred while creating the category';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        // Allow Enter key to save
        $nameInput.on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                $saveBtn.click();
            }
        });
    });
}

function sendDataTablesActionRequest(endpoint, ids, action) {
    $.ajax({
        type: "POST",
        url: endpoint,
        dataType: 'json',
        data: {
            'ids': ids,
            'action': action,
        },
        beforeSend: function () {
            toggle_global_loading(true);
        },
        success: function (response) {
            if (response.window_redirect) {
                show_notify(response);
                window.location = response.window_redirect;
                return false;
            }

            if (response.redirect) {
                show_notify(response);
                setTimeout(function () {
                    window.location = response.redirect;
                }, 1000);
                return false;
            }

            toggle_global_loading(false);
            $('#select-all').prop('checked', false);
            $('.jw-datatable-bulk-actions .dropdown-toggle').prop('disabled', true);
            $('#jw-datatable').DataTable().draw();
        },
        error: function (response) {
            toggle_global_loading(false);
            show_notify(response);
        },
        complete: function () {
            toggle_global_loading(false);
        }
    });
}

let slugEditedManually = ($('#slug').length > 0) && ($('#slug').val().trim() !== '');

$(function () {
    $(document).on('click', '.jw-checkbox .custom-control-input', function () {
        $(this).closest('.jw-checkbox')
            .find('.jw-checkbox-input')
            .val($(this).is(':checked') ? '1' : '0');
    });

    $(document).on('change', '.select-language', function () {
        const locale = $(this).val();
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('locale', locale);
        window.location.href = currentUrl.toString();
    });

    $(document).on('input', '.slug-target', function () {
        let value = $(this).val().trim();

        if (value === '') {
            slugEditedManually = false; // Nếu user xóa slug → bật auto lại
        } else {
            slugEditedManually = true; // Chỉ lock khi thực sự thay đổi ký tự
        }
    });

    $(document).on('click', '.edit-slug', function () {
        let isDisabled = $(this).closest('.input-group')
            .find('.slug-target')
            .prop('disabled');
        $(this).closest('.input-group')
            .find('.slug-target')
            .prop('disabled', !isDisabled)
            .focus();
    });

    $('.slug-source').on('keyup', function () {
        if (slugEditedManually) {
            return; // Do not overwrite manually edited slug
        }

        let text = $(this).val();

        // Convert text to slug
        let slug = text
            .toString()
            .normalize('NFD')                 // remove accents
            .replace(/[\u0300-\u036f]/g, '') // remove diacritics (Vietnamese accents)
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\- ]/g, '')    // remove special chars
            .replace(/\s+/g, '-')            // replace spaces with -
            .replace(/\-+/g, '-');           // collapse multiple -

        $('#slug').val(slug);
    });

    $(document).on('click', '.edit-security', function () {
        let el = $(this).closest('.input-group').find('.form-control');
        let isDisabled = el.prop('disabled');
        el.prop('disabled', !isDisabled).focus();
    });

    $(document).on('click', '.add-repeater-item', function () {
        let template = $(this).closest('.repeater-component').find('script[type="text/html"]').html();
        let marker = uuidv4();

        template = template.replace(/__marker__/g, marker);
        $(this).closest('.repeater-component').find('.repeaters').append(template);
    });

    $(document).on('click', '.remove-repeater-item', function () {
        $(this).closest('.repeater-item').remove();
    });

    initSelect2('body');

    initDatepicker('body');

    initEditor('body');

    initQuickAddCategory('body');

    // Handle bulk translate action
    $(document).on('click', '.jw-datatable-bulk-action[data-action="translate"]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let endpoint = $(this).data('endpoint');
        let ids = $(".jw-datatable-checkbox:checked").map(function () {
            return $(this).val()
        }).get();

        if (ids.length === 0) {
            alert(juzaweb.lang.please_select_at_least_one_row || 'Please select at least one row');
            return false;
        }

        // Store data for the modal
        $('#translate-modal').data('ids', ids);
        $('#translate-modal').data('endpoint', endpoint);
        $('#translate-modal').modal('show');
        return false;
    });

    $(document).on('click', '#translate-submit', function () {
        let modal = $('#translate-modal');
        let model = $(this).data('model');
        let sourceLocale = $('#translate-source-locale').val();
        let targetLocale = $('#translate-target-locale').val();
        let ids = modal.data('ids');

        $.ajax({
            type: "POST",
            url: juzaweb.adminUrl + '/translations/translate-model',
            dataType: 'json',
            data: {
                'ids': ids,
                'source': sourceLocale,
                'locale': targetLocale,
                'model': model,
            },
            beforeSend: function () {
                toggle_global_loading(true);
            },
            success: function (response) {
                toggle_global_loading(false);

                if (response.history_ids && response.history_ids.length > 0) {
                    // Close translate modal
                    modal.modal('hide');

                    // Show progress modal
                    let progressModal = $('#translate-progress-modal');
                    progressModal.modal('show');

                    // Reset progress
                    $('#translate-progress-bar')
                        .css('width', '0%')
                        .attr('aria-valuenow', 0)
                        .text('0%')
                        .removeClass('bg-success bg-danger')
                        .addClass('progress-bar-animated progress-bar-striped bg-primary');
                    $('#translate-progress-status').text(juzaweb.lang.translation_processing || 'Processing...');
                    $('#translate-progress-detail').text('');

                    // Start polling
                    let historyIds = response.history_ids;
                    let pollInterval = setInterval(function () {
                        $.ajax({
                            url: juzaweb.adminUrl + '/translations/translate-status',
                            type: 'POST',
                            data: {
                                history_ids: historyIds
                            },
                            success: function (statusData) {
                                let completedCount = statusData.success + statusData.failed;
                                let progress = Math.round((completedCount / statusData.total) * 100);
                                $('#translate-progress-bar')
                                    .css('width', progress + '%')
                                    .attr('aria-valuenow', progress)
                                    .text(progress + '%');

                                let detail = juzaweb.lang.translating_items || 'Translating :current of :total items...';
                                detail = detail.replace(':current', completedCount).replace(':total', statusData.total);
                                $('#translate-progress-detail').text(detail);

                                if (statusData.completed) {
                                    clearInterval(pollInterval);

                                    if (statusData.failed > 0) {
                                        $('#translate-progress-status').text(juzaweb.lang.translation_failed || 'Translation failed');
                                        $('#translate-progress-bar').removeClass('progress-bar-animated progress-bar-striped bg-primary').addClass('bg-danger');
                                    } else {
                                        $('#translate-progress-status').text(juzaweb.lang.translation_completed || 'Translation completed');
                                        $('#translate-progress-bar').removeClass('progress-bar-animated progress-bar-striped bg-primary').addClass('bg-success');
                                    }

                                    setTimeout(function () {
                                        progressModal.modal('hide');
                                        show_notify(response);
                                        $('#select-all').prop('checked', false);
                                        $('.jw-datatable-bulk-actions .dropdown-toggle').prop('disabled', true);
                                        $('#jw-datatable').DataTable().draw();
                                    }, 2000);
                                }
                            },
                            error: function () {
                                clearInterval(pollInterval);
                                progressModal.modal('hide');
                                show_notify({ status: 'error', message: 'Error checking translation status' });
                            }
                        });
                    }, 2000); // Poll every 2 seconds
                } else {
                    // Fallback if no history IDs
                    show_notify(response);
                    modal.modal('hide');
                    $('#select-all').prop('checked', false);
                    $('.jw-datatable-bulk-actions .dropdown-toggle').prop('disabled', true);
                    $('#jw-datatable').DataTable().draw();
                }
            },
            error: function (response) {
                toggle_global_loading(false);
                show_notify(response);
            },
            complete: function () {
                toggle_global_loading(false);
            }
        });
    });

    $(document).on('click', '.translate-model', function (e) {
        e.preventDefault();
        var $this = $(this);
        var id = $this.data('id');
        var model = $this.data('model');
        var locale = $this.data('locale');
        var source = $this.data('source');

        if (id && model && locale) {
            $.ajax({
                url: juzaweb.adminUrl + '/translations/translate-model',
                type: 'POST',
                data: {
                    ids: id,
                    model: model,
                    locale: locale,
                    source: source,
                },
                beforeSend: function () {
                    toggle_global_loading(true);
                },
                success: function (response) {
                    toggle_global_loading(false);

                    if (response.history_ids && response.history_ids.length > 0) {
                        // Show progress modal
                        let progressModal = $('#translate-progress-modal');
                        progressModal.modal('show');

                        // Reset progress
                        $('#translate-progress-bar')
                            .css('width', '0%')
                            .attr('aria-valuenow', 0)
                            .text('0%')
                            .removeClass('bg-success bg-danger')
                            .addClass('progress-bar-animated progress-bar-striped bg-primary');
                        $('#translate-progress-status').text(juzaweb.lang.translation_processing || 'Processing...');
                        $('#translate-progress-detail').text('');

                        // Start polling
                        let historyIds = response.history_ids;
                        let pollInterval = setInterval(function () {
                            $.ajax({
                                url: juzaweb.adminUrl + '/translations/translate-status',
                                type: 'POST',
                                data: {
                                    history_ids: historyIds
                                },
                                success: function (statusData) {
                                    let completedCount = statusData.success + statusData.failed;
                                    let progress = Math.round((completedCount / statusData.total) * 100);
                                    $('#translate-progress-bar')
                                        .css('width', progress + '%')
                                        .attr('aria-valuenow', progress)
                                        .text(progress + '%');

                                    let detail = juzaweb.lang.translating_items || 'Translating :current of :total items...';
                                    detail = detail.replace(':current', completedCount).replace(':total', statusData.total);
                                    $('#translate-progress-detail').text(detail);

                                    if (statusData.completed) {
                                        clearInterval(pollInterval);

                                        if (statusData.failed > 0) {
                                            $('#translate-progress-status').text(juzaweb.lang.translation_failed || 'Translation failed');
                                            $('#translate-progress-bar').removeClass('progress-bar-animated progress-bar-striped bg-primary').addClass('bg-danger');
                                        } else {
                                            $('#translate-progress-status').text(juzaweb.lang.translation_completed || 'Translation completed');
                                            $('#translate-progress-bar').removeClass('progress-bar-animated progress-bar-striped bg-primary').addClass('bg-success');
                                        }

                                        setTimeout(function () {
                                            progressModal.modal('hide');
                                            show_notify(response);
                                            window.location.reload();
                                        }, 2000);
                                    }
                                },
                                error: function () {
                                    clearInterval(pollInterval);
                                    progressModal.modal('hide');
                                    show_notify({ status: 'error', message: 'Error checking translation status' });
                                }
                            });
                        }, 2000); // Poll every 2 seconds
                    } else {
                        show_notify(response);
                    }
                },
                error: function (xhr) {
                    toggle_global_loading(false);
                    show_notify(xhr);
                }
            });
        }
    });

    initCurrencyInput('body');

    $(document).on('close.bs.alert', '.jw-message', function () {
        $.ajax({
            url: juzaweb.adminUrl + '/remove-message',
            type: 'POST',
            data: {},
            success: function (response) {
                //
            }
        });
    });
});

function initCurrencyInput(parent = 'body') {
    $(parent + ' .currency-input').each(function () {
        const $input = $(this);
        const decimals = parseInt($input.data('decimals')) || 2;
        const thousandSep = $input.data('thousand-separator') || ',';
        const decimalSep = $input.data('decimal-separator') || '.';

        // Format on blur
        $input.on('blur', function () {
            let value = $(this).val().replace(/[^\d.-]/g, '');

            if (value === '' || value === '-') {
                return;
            }

            let num = parseFloat(value);
            if (!isNaN(num)) {
                $(this).val(formatCurrency(num, decimals, decimalSep, thousandSep));
            }
        });

        // Remove formatting on focus
        $input.on('focus', function () {
            let value = $(this).val();
            if (value) {
                // Remove all separators to get raw number
                value = value.replace(new RegExp('\\' + thousandSep, 'g'), '');
                value = value.replace(new RegExp('\\' + decimalSep, 'g'), '.');
                $(this).val(value);
            }
        });

        // Format initial value
        if ($input.val()) {
            let value = $input.val().replace(/[^\d.-]/g, '');
            let num = parseFloat(value);
            if (!isNaN(num)) {
                $input.val(formatCurrency(num, decimals, decimalSep, thousandSep));
            }
        }
    });
}

function formatCurrency(num, decimals, decimalSep, thousandSep) {
    const fixed = num.toFixed(decimals);
    const parts = fixed.split('.');

    // Add thousand separators
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSep);

    return parts.join(decimalSep);
}

