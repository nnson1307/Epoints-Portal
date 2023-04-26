var stt = 0;


var listShift = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.select').select2();
            $("input[name='search']").change(function () {
                $('#search_filter').val($(this).val());
            });

            $("select[name='journey_code']").change(function () {
                $('#journey_code_filter').val($(this).val());
            });

            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

            $("#created_at").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",

                // maxDate: moment().endOf("day"),
                // startDate: moment().startOf("day"),
                // endDate: moment().add(1, 'days'),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    // "applyLabel": "Đồng ý",
                    // "cancelLabel": "Thoát",
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            }, function (start, end, label) {
                $('#created_at_filter').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            });

            $('#autotable').PioTable({
                baseUrl: laroute.route('shift.list')
            });
        });
    },
    changeCreate: function (obj) {
        alert('ok');
    },
    remove: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('shift.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            shift_id: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                $('#autotable').PioTable('refresh');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    changeStatus: function (obj, shiftId) {
        var is_actived = 0;

        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('shift.shift.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                shift_id: shiftId,
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    $('#autotable').PioTable('refresh');
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    }
};

var view = {
    calculateMinWork: function () {
        $.ajax({
            url: laroute.route('shift.shift.calculate-min-work'),
            method: 'POST',
            dataType: 'JSON',
            global: false,
            data: {
                start_work_time: $('#start_work_time').val(),
                end_work_time: $('#end_work_time').val(),
                start_lunch_break: $('#start_lunch_break').val(),
                end_lunch_break: $('#end_lunch_break').val()
            },
            success: function (res) {
                $('.input_min_time_work').empty();

                var tpl = $('#input-min-time-work-tpl').html();
                tpl = tpl.replace(/{hour}/g, formatNumber(res.hourWork.toFixed(decimal_number)));
                $('.input_min_time_work').append(tpl);

                new AutoNumeric.multiple('#min_time_work', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });
    }  
};

var create = {
    popupCreate: function (load) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('shift.create'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    load: load
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-create').modal('show');

                    $('.timepicker').timepicker({
                        minuteStep: 1,
                        defaultTime: "",
                        showMeridian: !1,
                        snapToStep: !0,
                    });

                    new AutoNumeric.multiple('#min_time_work, #timekeeping_coefficient', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 2,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                    $('#branch_id').select2({
                        width: "100%",
                        placeholder: json['Chọn chi nhánh']
                    });
                }
            });
        });
    },
    save: function (load) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    shift_name: {
                        required: true,
                        maxlength: 50
                    },

                    start_work_time: {
                        required: true,
                    },
                    end_work_time: {
                        required: true
                    },
                    branch_id: {
                        required: true
                    },
                    min_time_work: {
                        required: true,
                        min: 1
                    },
                    timekeeping_coefficient: {
                        required: true
                    }
                },
                messages: {
                    shift_name: {
                        required: json['Hãy nhập tên ca'],
                        maxlength: json['Tên ca tối đa 50 kí tự']
                    },
                    start_day_shift: {
                        required: json['Hãy nhập thời gian bắt đầu làm việc'],
                    },
                    end_day_shift: {
                        required: json['Hãy chọn ngày kết thúc']
                    },
                    branch_id: {
                        required: json['Hãy chọn chi nhánh']
                    },
                    min_time_work: {
                        required: json['Hãy nhập số giờ làm'],
                        min: json['Số giờ làm tối thiểu 1']
                    },
                    timekeeping_coefficient: {
                        required: json['Hãy nhập hệ số công'],
                        min: json['Hệ số công tối thiểu 1']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var isMonday = 0;
            var isTuesday = 0;
            var isWednesday = 0;
            var isThursday = 0;
            var isFriday = 0;
            var isSaturday = 0;
            var isSunday = 0;

            if ($('.is_monday').is(':checked')) {
                isMonday = 1;
            }

            if ($('.is_tuesday').is(':checked')) {
                isTuesday = 1;
            }

            if ($('.is_wednesday').is(':checked')) {
                isWednesday = 1;
            }

            if ($('.is_thursday').is(':checked')) {
                isThursday = 1;
            }

            if ($('.is_friday').is(':checked')) {
                isFriday = 1;
            }

            if ($('.is_saturday').is(':checked')) {
                isSaturday = 1;
            }

            if ($('.is_sunday').is(':checked')) {
                isSunday = 1;
            }

            $.ajax({
                url: laroute.route('shift.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    shift_name: $('[name="shift_name"]').val(),
                    shift_type: $('#shift_type').val(),
                    start_work_time: $('[name="start_work_time"]').val(),
                    end_work_time: $('[name="end_work_time"]').val(),
                    start_lunch_break: $('[name="start_lunch_break"]').val(),
                    end_lunch_break: $('[name="end_lunch_break"]').val(),
                    note: $('[name="note"]').val(),
                    min_time_work: $('[name="min_time_work"]').val().replace(new RegExp('\\,', 'g'), ''),
                    is_monday: isMonday,
                    is_tuesday: isTuesday,
                    is_wednesday: isWednesday,
                    is_thursday: isThursday,
                    is_friday: isFriday,
                    is_saturday: isSaturday,
                    is_sunday: isSunday,
                    branch_id: $('#branch_id').val(),
                    timekeeping_coefficient: $('#timekeeping_coefficient').val().replace(new RegExp('\\,', 'g'), '')
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#modal-create').modal('hide');
                            }
                            if (result.value == true) {
                                $('#modal-create').modal('hide');
                            }
                        });
                        $('#autotable').PioTable('refresh');
                    } else {
                        swal(res.message, '', "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm mới thất bại'], mess_error, "error");
                }
            });
        });
    }
};

var edit = {
    popupEdit: function (id, load) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('shift.edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    shift_id: id,
                    load: load,
                    view: 'edit'
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-edit').modal('show');

                    $('.timepicker').timepicker({
                        minuteStep: 1,
                        defaultTime: "",
                        showMeridian: !1,
                        snapToStep: !0,
                    });

                    new AutoNumeric.multiple('#min_time_work, #timekeeping_coefficient', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 2,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                    $('#branch_id').select2({
                        width: "100%",
                        placeholder: json['Chọn chi nhánh']
                    });
                }
            });
        });
    },
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    shift_name: {
                        required: true,
                        maxlength: 50
                    },

                    start_work_time: {
                        required: true,
                    },
                    end_work_time: {
                        required: true
                    },
                    branch_id: {
                        required: true
                    },
                    min_time_work: {
                        required: true,
                        min: 1
                    },
                    timekeeping_coefficient: {
                        required: true
                    }
                },
                messages: {
                    shift_name: {
                        required: json['Hãy nhập tên ca'],
                        maxlength: json['Tên ca tối đa 50 kí tự']
                    },
                    start_day_shift: {
                        required: json['Hãy nhập thời gian bắt đầu làm việc'],
                    },
                    end_day_shift: {
                        required: json['Hãy chọn ngày kết thúc']
                    },
                    branch_id: {
                        required: json['Hãy chọn chi nhánh']
                    },
                    min_time_work: {
                        required: json['Hãy nhập số giờ làm'],
                        min: json['Số giờ làm tối thiểu 1']
                    },
                    timekeeping_coefficient: {
                        required: json['Hãy nhập hệ số công'],
                        min: json['Hệ số công tối thiểu 1']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var isMonday = 0;
            var isTuesday = 0;
            var isWednesday = 0;
            var isThursday = 0;
            var isFriday = 0;
            var isSaturday = 0;
            var isSunday = 0;

            if ($('.is_monday').is(':checked')) {
                isMonday = 1;
            }

            if ($('.is_tuesday').is(':checked')) {
                isTuesday = 1;
            }

            if ($('.is_wednesday').is(':checked')) {
                isWednesday = 1;
            }

            if ($('.is_thursday').is(':checked')) {
                isThursday = 1;
            }

            if ($('.is_friday').is(':checked')) {
                isFriday = 1;
            }

            if ($('.is_saturday').is(':checked')) {
                isSaturday = 1;
            }

            if ($('.is_sunday').is(':checked')) {
                isSunday = 1;
            }

            $.ajax({
                url: laroute.route('shift.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    shift_id: $('[name="shift_id"]').val(),
                    shift_name: $('[name="shift_name"]').val(),
                    shift_type: $('#shift_type').val(),
                    start_work_time: $('[name="start_work_time"]').val(),
                    end_work_time: $('[name="end_work_time"]').val(),
                    start_lunch_break: $('[name="start_lunch_break"]').val(),
                    end_lunch_break: $('[name="end_lunch_break"]').val(),
                    note: $('[name="note"]').val(),
                    min_time_work: $('[name="min_time_work"]').val().replace(new RegExp('\\,', 'g'), ''),
                    is_monday: isMonday,
                    is_tuesday: isTuesday,
                    is_wednesday: isWednesday,
                    is_thursday: isThursday,
                    is_friday: isFriday,
                    is_saturday: isSaturday,
                    is_sunday: isSunday,
                    branch_id: $('#branch_id').val(),
                    timekeeping_coefficient: $('#timekeeping_coefficient').val().replace(new RegExp('\\,', 'g'), '')
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#modal-edit').modal('hide');
                            }
                            if (result.value == true) {
                                $('#modal-edit').modal('hide');
                            }
                        });

                        $('#autotable').PioTable('refresh');
                    } else {
                        swal(res.message, '', "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                }
            });
        });
    },
};


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

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
