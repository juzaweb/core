let searchTimeout;

$(function () {
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
});