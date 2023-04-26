var stt = 0;

var view = {
    _init: function () {
        $(document).ready(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $("#start_date, #end_date").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    // pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    // minDate: new Date(),
                    // locale: 'vi'
                });

                new AutoNumeric.multiple('#quota, #promotion_type_discount_value_same', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

                $('#promotion_type_discount_value_percent').ForceNumericOnly();

                // $("#sortable").sortable();
                // $("#sortable").disableSelection();

                $("#start_time, #end_time").timepicker({
                    minuteStep: 15,
                    defaultTime: "",
                    showMeridian: !1,
                    snapToStep: !0,
                });

                $('#branch_apply').select2().on('select2:select', function (event) {
                    if (event.params.data.id == 'all') {
                        $('#branch_apply').val('all').trigger('change');
                    } else {
                        var arrayChoose = [];

                        $.map($('#branch_apply').val(), function (val) {
                            if (val != 'all') {
                                arrayChoose.push(val);
                            }
                        });
                        $('#branch_apply').val(arrayChoose).trigger('change');
                    }
                }).on('select2:unselect', function (event) {
                    if ($('#branch_apply').val() == '') {
                        $('#branch_apply').val('all').trigger('change');
                    }
                });

                // $('#promotion_type_discount_value_same').css('display', 'none');

                $('#order_source').select2();

                $('#promotion_apply_to').select2({
                    placeholder: json['Chọn đối tượng áp dụng']
                });

                $('#member_level_id').select2({
                    placeholder: json['Chọn hạng thành viên']
                });

                $('#customer_group_id').select2({
                    placeholder: json['Chọn nhóm khách hàng']
                });

                $('#customer_id').select2({
                    placeholder: json['Chọn khách hàng']
                });

                $("#description_detail").summernote({
                    height: 208,
                    width: 1000,
                    placeholder: json['Nhập nội dung'],
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']]
                    ]
                }).summernote("disable");
            });
            if ($('#is_display').is(':checked')) {
                $('.is_feature').css('display', 'flex');
                $('.div_feature').css('display', 'block');
            } else {
                $('.is_feature').css('display', 'none');
                $('.div_feature').css('display', 'none');
            }
        });
    }
};

function onDay(day) {
    switch (day) {
        case 'Monday':
            $('#is_monday').prop("checked", true);
            $('#is_other_monday').prop('disabled', false);
            break;
        case 'Tuesday':
            $('#is_tuesday').prop("checked", true);
            $('#is_other_tuesday').prop('disabled', false);
            break;
        case 'Wednesday':
            $('#is_wednesday').prop("checked", true);
            $('#is_other_wednesday').prop('disabled', false);
            break;
        case 'Thursday':
            $('#is_thursday').prop("checked", true);
            $('#is_other_thursday').prop('disabled', false);
            break;
        case 'Friday':
            $('#is_friday').prop("checked", true);
            $('#is_other_friday').prop('disabled', false);
            break;
        case 'Saturday':
            $('#is_saturday').prop("checked", true);
            $('#is_other_saturday').prop('disabled', false);
            break;
        case 'Sunday':
            $('#is_sunday').prop("checked", true);
            $('#is_other_sunday').prop('disabled', false);
            break;
    }
}

function offDay(day) {
    switch (day) {
        case 'Monday':
            $('#is_monday').prop("checked", false);
            $('#is_other_monday').prop('disabled', true);
            $('#is_other_monday').prop("checked", false);
            $('#is_other_monday_start_time').prop("disabled", true);
            $('#is_other_monday_end_time').prop("disabled", true);
            break;
        case 'Tuesday':
            $('#is_tuesday').prop("checked", false);
            $('#is_other_tuesday').prop('disabled', true);
            $('#is_other_tuesday').prop("checked", false);
            $('#is_other_tuesday_start_time').prop("disabled", true);
            $('#is_other_tuesday_end_time').prop("disabled", true);
            break;
        case 'Wednesday':
            $('#is_wednesday').prop("checked", false);
            $('#is_other_wednesday').prop('disabled', true);
            $('#is_other_wednesday').prop("checked", false);
            $('#is_other_wednesday_start_time').prop("disabled", true);
            $('#is_other_wednesday_end_time').prop("disabled", true);
            break;
        case 'Thursday':
            $('#is_thursday').prop("checked", false);
            $('#is_other_thursday').prop('disabled', true);
            $('#is_other_thursday').prop("checked", false);
            $('#is_other_thursday_start_time').prop("disabled", true);
            $('#is_other_thursday_end_time').prop("disabled", true);
            break;
        case 'Friday':
            $('#is_friday').prop("checked", false);
            $('#is_other_friday').prop('disabled', true);
            $('#is_other_friday').prop("checked", false);
            $('#is_other_friday_start_time').prop("disabled", true);
            $('#is_other_friday_end_time').prop("disabled", true);
            break;
        case 'Saturday':
            $('#is_saturday').prop("checked", false);
            $('#is_other_saturday').prop('disabled', true);
            $('#is_other_saturday').prop("checked", false);
            $('#is_other_saturday_start_time').prop("disabled", true);
            $('#is_other_saturday_end_time').prop("disabled", true);
            break;
        case 'Sunday':
            $('#is_sunday').prop("checked", false);
            $('#is_other_sunday').prop('disabled', true);
            $('#is_other_sunday').prop("checked", false);
            $('#is_other_sunday_start_time').prop("disabled", true);
            $('#is_other_sunday_end_time').prop("disabled", true);
            break;
    }
}

function onOther(day) {
    switch (day) {
        case 'Monday':
            $('#is_other_monday').prop("checked", true);
            $('#is_other_monday_start_time').prop("disabled", false);
            $('#is_other_monday_end_time').prop("disabled", false);
            break;
        case 'Tuesday':
            $('#is_other_tuesday').prop("checked", true);
            $('#is_other_tuesday_start_time').prop("disabled", false);
            $('#is_other_tuesday_end_time').prop("disabled", false);
            break;
        case 'Wednesday':
            $('#is_other_wednesday').prop("checked", true);
            $('#is_other_wednesday_start_time').prop("disabled", false);
            $('#is_other_wednesday_end_time').prop("disabled", false);
            break;
        case 'Thursday':
            $('#is_other_thursday').prop("checked", true);
            $('#is_other_thursday_start_time').prop("disabled", false);
            $('#is_other_thursday_end_time').prop("disabled", false);
            break;
        case 'Friday':
            $('#is_other_friday').prop("checked", true);
            $('#is_other_friday_start_time').prop("disabled", false);
            $('#is_other_friday_end_time').prop("disabled", false);
            break;
        case 'Saturday':
            $('#is_other_saturday').prop("checked", true);
            $('#is_other_saturday_start_time').prop("disabled", false);
            $('#is_other_saturday_end_time').prop("disabled", false);
            break;
        case 'Sunday':
            $('#is_other_sunday').prop("checked", true);
            $('#is_other_sunday_start_time').prop("disabled", false);
            $('#is_other_sunday_end_time').prop("disabled", false);
            break;
    }
}

function offOther(day) {
    switch (day) {
        case 'Monday':
            $('#is_other_monday').prop("checked", false);
            $('#is_other_monday_start_time').prop("disabled", true);
            $('#is_other_monday_end_time').prop("disabled", true);
            break;
        case 'Tuesday':
            $('#is_other_tuesday').prop("checked", false);
            $('#is_other_tuesday_start_time').prop("disabled", true);
            $('#is_other_tuesday_end_time').prop("disabled", true);
            break;
        case 'Wednesday':
            $('#is_other_wednesday').prop("checked", false);
            $('#is_other_wednesday_start_time').prop("disabled", true);
            $('#is_other_wednesday_end_time').prop("disabled", true);
            break;
        case 'Thursday':
            $('#is_other_thursday').prop("checked", false);
            $('#is_other_thursday_start_time').prop("disabled", true);
            $('#is_other_thursday_end_time').prop("disabled", true);
            break;
        case 'Friday':
            $('#is_other_friday').prop("checked", false);
            $('#is_other_friday_start_time').prop("disabled", true);
            $('#is_other_friday_end_time').prop("disabled", true);
            break;
        case 'Saturday':
            $('#is_other_saturday').prop("checked", false);
            $('#is_other_saturday_start_time').prop("disabled", true);
            $('#is_other_saturday_end_time').prop("disabled", true);
            break;
        case 'Sunday':
            $('#is_other_sunday').prop("checked", false);
            $('#is_other_sunday_start_time').prop("disabled", true);
            $('#is_other_sunday_end_time').prop("disabled", true);
            break;
    }
}

jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };

