function initSelect2(parent = 'body') {
    $(parent + ' .select2-input').select2({
        width: $(this).data('width') || '100%',
        placeholder: $(this).data('placeholder') || null,
    });

    $(parent + ' .load-data').each(function () {
        const $el = $(this);

        $el.select2({
            allowClear: true,
            dropdownAutoWidth: !$el.data('width'),
            width: $el.data('width') || '100%',
            placeholder: function (params) {
                return {
                    id: null,
                    text: params.placeholder,
                };
            },
            ajax: {
                method: 'GET',
                url: $el.data('url'),
                dataType: 'json',
                data: function (params) {
                    let explodes = $el.data('explodes') ? $el.data('explodes') : null;
                    if (explodes) {
                        explodes = $("." + explodes)
                            .map(function () {
                                return $(this).val();
                            })
                            .get();
                    }

                    return {
                        q: $.trim(params.term),
                        page: params.page,
                        explodes: explodes,
                    };
                }
            },
        });
    });

    $(parent + ' .tags-input').each(function () {
        const $el = $(this);

        $el.select2({
            theme: 'bootstrap4',
            tags: true,
            tokenSeparators: [','],
            placeholder: $el.data('placeholder'),
            ajax: {
                url: $el.data('url'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page,
                    };
                },
            },
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') return null;
                return {
                    id: term,
                    text: term,
                    newTag: true,
                };
            }
        });
    });
}

$(function () {
    initSelect2('body');
});
