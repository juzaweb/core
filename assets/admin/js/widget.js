$(function () {
    $('.jw-widget-builder').nestable({
        noDragClass: 'dd-nodrag',
        maxDepth: 1,
    });

    $('#widget-container').on('click', '.dropdown-action', function () {
        let $widget = $(this).closest('.widget-block');
        let $blocks = $widget.find('.sidebar-blocks');
        let $icon = $(this).find('.toggle-icon'); // nếu có icon

        if ($blocks.is(':visible')) {
            $blocks.slideUp(250);
            $widget.removeClass('open');
            $icon.removeClass('rotated');
        } else {
            $blocks.slideDown(250);
            $widget.addClass('open');
            $icon.addClass('rotated');
        }
    });

    $('#widget-container').on('submit', '.form-add-widget', function (e) {
        e.preventDefault();
        let form = new FormData($(this)[0]);
        let btn = $(this).find('button[type=submit]');
        let icon = btn.find('i').attr('class');
        let widgetKey = form.get('widget');
        let widgetLabel = form.get('widget_label');
        let items = $(this).find('input[name="sidebars[]"]:checked').map(function () {
            return {key: $(this).val()};
        }).get();

        let formTemplate = document.getElementById(`sidebar-widget-form-template`).innerHTML;
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);

        $.each(items, function (key, item) {
            let itemKey = uuidv4();
            let form = replace_template(
                document.getElementById(`widget-${widgetKey}-form`).innerHTML,
                {
                    key: itemKey,
                }
            );
            let htm = replace_template(formTemplate, {
                key: itemKey,
                widget_key: widgetKey,
                widget_label: widgetLabel,
                form: form,
            });

            $('#sidebar-' + item.key + ' .jw-widget-builder .dd-empty').remove();
            $('#sidebar-' + item.key + ' .dd-list').append(htm);
        });

        $.each(items, function (key, item) {
            initSelect2('#dd-item-' + item.key);
        });

        btn.find('i').attr('class', icon);
        btn.prop("disabled", false);

        return false;
    });

    $('#widget-container').on('click', '.widget-sidebar-item', function () {
            let item = $(this);
            let isChecked = item.find('input').is(':checked');
            let form = item.closest('.form-add-widget');
            let btn = form.find('button[type=submit]');

            if (isChecked) {
                item.find('span').html('');
                item.find('input').prop('checked', false);
            } else {
                item.find('span').html(`<i class="fa fa-check"></i>`);
                item.find('input').prop('checked', true);
            }

            if (form.find('.widget-sidebar-item input:checked').length > 0) {
                btn.prop('disabled', false);
            } else {
                btn.prop('disabled', true);
            }
        }
    );

    $('#widget-container').on('click', '.show-edit-form', function () {
        let item = $(this);
        let form = item.closest('.sidebar-item').find('.card-body');
        if (form.is(':hidden')) {
            form.show();
        } else {
            form.hide();
        }
    });

    $('#widget-container').on('click', '.show-item-form', function () {
        let editForm = $(this).closest('.dd-item').find('.form-item-edit');
        if (editForm.is(':hidden')) {
            editForm.show();
        } else {
            editForm.hide();
        }
    });

    $('#widget-container').on('click', '.delete-item-form', function () {
        $(this).closest('.dd-item').remove();
    });
});
