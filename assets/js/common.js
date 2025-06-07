function initSelect2(parent = 'body') {
    $(parent + ' .select2').each(function () {
        $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') || '100%',
            dropdownAutoWidth: !$(this).data('width'),
        });
    });
}

$(function () {
    initSelect2('body');
});
