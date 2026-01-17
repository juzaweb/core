$(function () {
    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target);

        if (window.JSON) {
            $('#items-output').val(window.JSON.stringify(list.nestable('serialize')));
        } else {
            alert('JSON browser support required for this application.');
        }
    };

    $('#jw-menu-builder').nestable({
        noDragClass: 'dd-nodrag',

    }).on('change', updateOutput);

    updateOutput($('#jw-menu-builder'));

    $('#menu-container').on('submit', '.form-menu-block', function (event) {
        if (event.isDefaultPrevented()) {
            return false;
        }

        event.preventDefault();
        const form = $(this);
        const formData = new FormData(form[0]);
        const formKey = form.data('key');

        let tempData = {};
        let attributes = 'data-target="_self"';
        for (const [key, value] of formData.entries()) {
            tempData[key] = value;
            attributes += ` data-${key}="${htmlspecialchars(value)}"`;
        }

        let template = form.data('template');
        let menuItemTemplate = document.getElementById('template-menu-item').innerHTML;
        let templateHtml = document.getElementById('template-menu-item-' + template).innerHTML;

        if (template === 'custom') {
            templateHtml = replace_template(templateHtml, tempData);
            menuItemTemplate = replace_template(
                menuItemTemplate,
                {
                    slot: templateHtml,
                    label: htmlspecialchars(tempData.label),
                    attributes: attributes,
                }
            );

            $('#jw-menu-builder .dd-empty').remove();

            $('#jw-menu-builder .dd-list:first').append(menuItemTemplate);
        } else {
            let itemElements = form.find(`.select-all-${formKey}:checked`);
            $('#jw-menu-builder .dd-empty').remove();

            $.each(itemElements, function (index, item) {
                let itemData = {};
                let attributes = '';

                $.each($(item).data(), function (k, v) {
                    itemData[k] = v;
                    attributes += ` data-${k}="${htmlspecialchars(v)}"`;
                });

                let itemHtml = replace_template(templateHtml, itemData);
                let menuItemHtml = replace_template(
                    menuItemTemplate,
                    {
                        slot: itemHtml,
                        label: htmlspecialchars($(item).data('label')),
                        attributes: attributes,
                    }
                );

                $('#jw-menu-builder .dd-list:first').append(menuItemHtml);
            });
        }

        updateOutput($('#jw-menu-builder'));
        form.find('.reset-after-add').each(
            function () {
                if ($(this).is(':checkbox') || $(this).is(':radio')) {
                    $(this).prop('checked', false);
                } else {
                    $(this).val('');
                }
            }
        );

        return false;
    });

    $('#menu-container').on('click', '.btn-add-menu', function () {
        let eForm = $('.form-add-menu');
        if (eForm.is(':hidden')) {
            eForm.show('slow');
        } else {
            eForm.hide('slow');
        }
    });

    $('#menu-container').on('change', '.form-select-menu .load-menu', function () {
        let id = $(this).val();
        if (id) {
            window.location = juzaweb.adminUrl + "/menus/" + id;
        }
    });

    $('#menu-container').on('click', '.card-menu-items .card-header', function () {
        let cardBody = $(this).closest('.card-menu-items').find('.card-body');

        if (cardBody.is(':hidden')) {
            $('.card-menu-items').find('.card-body').slideUp('slow');
            $('.card-menu-items').find('.card-header').removeClass('bg-light');
            $(this).closest('.card-menu-items').find('.card-header').addClass('bg-light');
            cardBody.slideDown('slow');
        } else {
            cardBody.slideUp('slow');
            $(this).closest('.card-menu-items').find('.card-header').removeClass('bg-light');
        }
    });

    $('#menu-container').on('click', '.show-menu-edit', function (e) {
        let formEdit = $(this).closest('.dd-item').find('.form-item-edit').first();
        if (formEdit.is(':hidden')) {
            formEdit.slideDown('slow');
        } else {
            formEdit.slideUp('slow');
        }
    });

    $('#menu-container').on('click', '.delete-menu', function (e) {
        let id = $(this).data('id');
        let name = $(this).data('name');

        confirm_message(
            juzaweb.lang.remove_question.replace(':name', name),
            function (result) {
                if (result) {
                    $.ajax({
                        url: juzaweb.adminUrl + "/menus/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function (res) {
                            show_message(res);
                            window.location = juzaweb.adminUrl + "/menus";
                        },
                        error: function () {
                            show_notify({success: false, message: 'Error fetching data'});
                        }
                    });
                }
            }
        );
    });

    $('#menu-container').on('change', '.menu-data', function () {
        let name = $(this).data('name');
        let val = $(this).val();

        $(this).closest('li').data(name, val);
        updateOutput($('#jw-menu-builder'));

        if ($(this).hasClass('change-label')) {
            $(this).closest('li').find('.dd-handle span:first').text(val);
        }
    });

    $('#menu-container').on('click', '.delete-menu-item', function () {
        $(this).closest('li').remove();
        updateOutput($('#jw-menu-builder'));
    });

    $('#menu-container').on('click', '.close-menu-item', function () {
        let formEdit = $(this).closest('.dd-item').find('.form-item-edit').first();
        if (formEdit.is(':hidden')) {
            formEdit.slideDown('slow');
        } else {
            formEdit.slideUp('slow');
        }
    });

    $('#menu-container').on('change', '.select-all-checkbox', function () {
        let select = $(this).data('select');
        let checked = $(this).is(':checked');
        $(this).closest('.tab-pane').find('.' + select).prop('checked', checked);
    });

    $('#menu-container').on('keyup', '.menu-box-model-search', function () {
        let search = $(this).val();
        let key = $(this).data('key');
        let resultElement = $(this).closest('.tab-pane').find('.box-tab-search-result');
        let dataUrl = $(this).data('url');
        resultElement.html('');

        if (search.length <= 0) {
            return false;
        }

        $.ajax({
            url: dataUrl,
            type: 'GET',
            dataType: 'json',
            data: {
                q: search,
                per_page: 5
            },
            success: function (res) {
                let temps = '';
                $.each(res.results, function (index, item) {
                    temps += `<div class="form-check mt-1">
                        <label class="form-check-label">
                            <input
                                class="form-check-input reset-after-add select-all-${key}"
                                type="checkbox"
                                name="items[]"
                                value="${item.id}"
                                data-id=""
                                data-key="${key}"
                                data-menuable_id="${item.id}"
                                data-target="_self"
                                data-label="${htmlspecialchars(item.text)}"
                                data-edit_url="${item.edit_url}"
                                data-menuable_class_name="${item.menuable_class_name}"
                                data-menuable_class="${item.menuable_class}"
                            >
                            ${item.text}
                        </label>
                    </div>`;
                });

                resultElement.html(temps);
            },
            error: function () {
                show_notify({success: false, message: 'Error fetching data'});
            }
        });

        return false;
    });
});
