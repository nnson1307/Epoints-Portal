// check form submit
$('#form-alert').submit(function() {
    var check_select = 0;
    var check_area = 0;
    $('#form-alert select').each(function() {
        if ($(this).val() == '') {
            if ($(this).closest('.form-group').find('.err').length == 0) {
                $('<span class="err error-' + $(this).attr('name') + '" data-name=' + $(this).attr('name') + '">' + lang["Vui lòng không để trống."] + '</span>').insertAfter(this);
                if (check_select == 0) {
                    check_select = 1;
                }
            }
        }
    });
    $('#form-alert textarea').each(function() {
        if (!$(this).val()) {
            console.log($(this).closest('.form-group').find('.err'))
            if ($(this).closest('.form-group').find('.err').length == 0) {
                $('<span class="err error-' + $(this).attr('name') + '" data-name=' + $(this).attr('name') + '">' + lang["Vui lòng không để trống."] + '</span>').insertAfter(this);
                if (check_area == 0) {
                    check_area = 1;
                }
            }
        }
    });
    if (check_select == 0 && check_area == 0) {
        return true;
    }
    $(".err").css("color", "red");
    return false;

});
// check validate
$('#form-alert select').change(function() {
    if ($(this).val() != '') {
        $(this).closest('.form-group').find('.err').remove();
    }
});
$('#form-alert textarea').change(function() {
    if ($(this).val()) {
        $(this).closest('.form-group').find('.err').remove();
    }
});
$('.m_selectpicker').selectpicker();
$('select[name="is_actived"]').select2();