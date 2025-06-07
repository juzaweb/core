function initSelect2(parent = 'body')
{
    $(parent +' .select2').select2({
        width: $(this).data('width') || '100%',
        dropdownAutoWidth: !$(this).data('width'),
    });
}

$(function () {
    initSelect2();
});
