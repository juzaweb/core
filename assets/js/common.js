function initSelect2(parent = 'body') {
    $(parent + ' .select2').each(function () {
        $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') || '100%',
            dropdownAutoWidth: !$(this).data('width'),
        });
    });
}

function initEditor(parnet = 'body') {
    tinymce.init({
        selector: parnet + ' .jw-editor',
        convert_urls: true,
        document_base_url: juzaweb.documentBaseUrl,
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
            let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
            let cmsURL = '/media/public/browser?editor=' + meta.fieldname;

            if (meta.filetype === 'image') {
                cmsURL = cmsURL + "&type=image";
            } else {
                cmsURL = cmsURL + "&type=file";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url: cmsURL,
                title: 'File Manager',
                width: x * 0.8,
                height: y * 0.8,
                resizable: "yes",
                close_previous: "no",
                onMessage: (api, message) => {
                    callback(message.content);
                }
            });
        }
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

$(function () {
    $(document).on('click', '.jw-checkbox .form-check-input', function () {
        $(this).closest('.jw-checkbox')
            .find('.jw-checkbox-input')
            .val($(this).is(':checked') ? '1' : '0');
    });

    initSelect2('body');

    initEditor('body');
});
