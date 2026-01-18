$(function () {
    $(document).on('click', '.show-form-block', function () {
        let form = $(this).closest('.dd-item').find('.form-block-edit');
        if (form.is(':hidden')) {
            form.show();
        } else {
            form.hide();
        }
    });

    $(document).on('click', '.remove-form-block', function () {
        $(this).closest('.dd-item').remove();
    });

    $(document).on('change', '#template', function () {
        let template = $(this).val();
        let currentUrl = window.location.href;
        let urlParams = new URLSearchParams(window.location.search);

        urlParams.set('template', template);
        window.location = currentUrl.split("?")[0] + '?' + urlParams.toString();
    });

    $(document).on('click', '.add-block-data', function () {
        let block = $(this).data('block');
        let contentKey = $(this).data('content_key');
        let item = $(this);
        let template = document.getElementById('block-'+ block + '-template').innerHTML;
        let marker = (new Date()).getTime() + '-' + uuidv4();

        template = replace_template(template, {
            'marker': marker,
            'content_key': contentKey,
        });

        item.closest('.page-block-content').find('.dd-empty').remove();
        item.closest('.page-block-content').find('.dd-list').append(template);

        initSelect2('#page-block-' + marker);
    });
});
