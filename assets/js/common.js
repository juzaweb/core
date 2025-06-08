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
            if (response.data.window_redirect) {
                show_notify(response);
                window.location = response.data.window_redirect;
                return false;
            }

            if (response.data.redirect) {
                show_notify(response);
                setTimeout(function () {
                    window.location = response.data.redirect;
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

let searchTimeout;

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
        if (jqxhr.status === 401) {
            window.location = "/admin-cp/login";
        }

        if (jqxhr.status === 419) {
            window.location = location.toString();
        }
    });

    $(document).on('click', '.jw-checkbox .form-check-input', function () {
        $(this).closest('.jw-checkbox')
            .find('.jw-checkbox-input')
            .val($(this).is(':checked') ? '1' : '0');
    });

    $(document).on('click', '#select-all', function () {
        const rows = $('#jw-datatable').DataTable().rows({ 'search': 'applied' }).nodes();
        const checked = this.checked;

        $('input[type="checkbox"]', rows).prop('checked', checked);
        $('.jw-datatable-bulk-actions .dropdown-toggle').prop('disabled', !checked);
    });

    $(document).on('change', '.jw-datatable-checkbox', function () {
        const checked = $('.jw-datatable-checkbox:checked').length > 0;

        $('.jw-datatable-bulk-actions .dropdown-toggle').prop('disabled', !checked);
    });

    $(document).on('input', '.jw-datatable_filter input[type="search"]', function () {
        clearTimeout(searchTimeout);

        const value = this.value;

        searchTimeout = setTimeout(function () {
            const dt = $('#jw-datatable').DataTable();
            dt.search(value).draw();
        }, 300);
    });

    $(document).on('click', '#jw-datatable .datatables-row-action[data-type="action"]', function () {
        let ids = [$(this).data('id')];
        let action = $(this).data('action');
        let endpoint = $(this).data('endpoint');

        if (action == 'delete') {
            confirm_message(juzaweb.lang.remove_question, function (result) {
                if (result) {
                    sendDataTablesActionRequest(endpoint, ids, action);
                }
            })
        } else {
            sendDataTablesActionRequest(endpoint, ids, action);
        }

        return false;
    });

    $(document).on('click', '.jw-datatable-bulk-action', function () {
        let action = $(this).data('action');
        let endpoint = $(this).data('endpoint');
        let ids = $(".jw-datatable-checkbox:checked").map(function(){return $(this).val()}).get();

        if (action == 'delete') {
            confirm_message(
                juzaweb.lang.remove_question,
                function (result) {
                    if (result) {
                        sendDataTablesActionRequest(endpoint, ids, action);
                    }
                }
            );
        } else {
            sendDataTablesActionRequest(endpoint, ids, action);
        }
    });

    $(document).on('change', '.jw-datatable_filters input, .jw-datatable_filters select', function () {
        const paramKey = $(this).attr('name');
        const paramValue = $(this).val();

        // Parse current URL
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        // Set or update the parameter
        params.set(paramKey, paramValue);

        // Construct the new URL
        url.search = params.toString();
        let newurl = url.toString();

        // Push new state to browser history
        window.history.pushState({ path: newurl }, '', newurl);

        // Update the datatable source and reload
        $('#jw-datatable').DataTable()
            .ajax
            .url(newurl);

        $('#jw-datatable').DataTable().ajax.reload();
    });

    initSelect2('body');

    initEditor('body');
});
