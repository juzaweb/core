$('#show_tree').on('click', function (e) {
    toggleMobileTree();
});

$('#main').on('click', function (e) {
    if ($('#tree').hasClass('in')) {
        toggleMobileTree(false);
    }
});

$(document).on('click', '#add-folder', function () {
    dialog(lang['message-name'], '', createFolder);
});

$(document).on('click', '#upload', function () {
    $('#uploadModal').modal('show');
});

$(document).on('click', '[data-display]', function () {
    show_list = $(this).data('display');
    loadItems();
});

$(document).on('click', '[data-action]', function () {
    window[$(this).data('action')]($(this).data('multiple') ? getSelectedItems() : getOneSelectedElement());
});

$(document).on('submit', 'form#import-url', function () {
    let btn = $(this).find('button[type=submit]');
    let icon = btn.find('i').attr('class');
    const url = $(this).attr('action');
    btn.find('i').attr('class', 'fa fa-spinner fa-spin');
    btn.prop("disabled", true);

    performLfmRequest(
        url,
        {
            url: $('#import-url input[name=url]').val(),
            download: $('#import-url input[name=download]').is(':checked') ? 1 : 0,
        }, 
        'post', 
        'json'
    ).done(function (response) {
        if (response.success) {
            loadItems();
            $('#uploadModal').modal('hide');
            $('#import-url input[name=url]').val(null);
        } else {
            notify('<div class="text-danger">' + response.message + '</div>');
        }
        
        btn.find('i').attr('class', icon);
	    btn.prop("disabled", false);
        return false;
    });

    btn.find('i').attr('class', icon);
    btn.prop("disabled", false);
    return false;
});