var stt = 0;

var listTimekeepingConfig = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.select').select2();
            $("input[name='search']").change(function () {
                $('#search_filter').val($(this).val());
            });
        });
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
                        url: laroute.route('timekeeping-config.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            timekeeping_config_id: id
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
    changeStatus: function (obj, timekeepingConfigId) {
        var is_actived = 0;

        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('timekeeping-config.change-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                timekeeping_config_id: timekeepingConfigId,
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
    getCurrentIp: function () {
        $.ajax({
            url: laroute.route('timekeeping-config.get-current-ip'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#wifi_ip').val(res.ip);
            }
        });
    }  
};

var create = {
    popupCreate: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('timekeeping-config.create'),
                method: 'POST',
                dataType: 'JSON',
                data: {},
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-create').modal('show');

                    $('#branch_id').select2({
                        placeholder: json['Chọn chi nhánh']
                    });
                }
            });
        });
    },
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');
            form.validate({
                ignore: [],
                rules: {
                    wifi_name: {
                        required: true,
                        maxlength: 190
                    },
                    wifi_ip: {
                        required: true,
                        maxlength: 190
                    },
                    latitude: {
                        required: true,
                        maxlength: 50
                    },
                    longitude: {
                        required: true,
                        maxlength: 50
                    },
                    branch_id: {
                        required: true
                    },
                },
                messages: {
                    wifi_name: {
                        required: json['Hãy nhập tên wifi'],
                        maxlength: json['Tên wifi tối đa 190 kí tự']
                    },
                    wifi_ip: {
                        required: json['Hãy nhập địa chỉ ip'],
                        maxlength: json['Địa chỉ ip tối đa 190 kí tự']
                    },
                    latitude: {
                        required: json['Hãy nhập kinh độ'],
                        maxlength: json['Kinh độ tối đa 50 kí tự']
                    },
                    longitude: {
                        required: json['Hãy nhập vĩ độ'],
                        maxlength: json['Kinh độ tối đa 50 kí tự']
                    },
                    branch_id: {
                        required: json['Hãy chọn chi nhánh']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('timekeeping-config.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    wifi_name: $('[name="wifi_name"]').val(),
                    wifi_ip: $('[name="wifi_ip"]').val(),
                    timekeeping_type: $('[name="timekeeping_type"]:checked').val(),
                    latitude: $('[name="latitude"]').val(),
                    longitude: $('[name="longitude"]').val(),
                    allowable_radius: $('[name="allowable_radius"]').val(),
                    branch_id: $('#branch_id').val(),
                    note: $('[name="note"]').val(),
                    note_gps: $('[name="note_gps"]').val()
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
    popupEdit: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('timekeeping-config.edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    timekeeping_config_id: id
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-edit').modal('show');

                    $('#branch_id').select2({
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
                    wifi_name: {
                        required: true,
                        maxlength: 190
                    },
                    wifi_ip: {
                        required: true,
                        maxlength: 190
                    },
                    latitude: {
                        required: true,
                        maxlength: 50
                    },
                    longitude: {
                        required: true,
                        maxlength: 50
                    },
                    branch_id: {
                        required: true
                    },
                },
                messages: {
                    wifi_name: {
                        required: json['Hãy nhập tên wifi'],
                        maxlength: json['Tên wifi tối đa 190 kí tự']
                    },
                    wifi_ip: {
                        required: json['Hãy nhập địa chỉ ip'],
                        maxlength: json['Địa chỉ ip tối đa 190 kí tự']
                    },
                    latitude: {
                        required: json['Hãy nhập kinh độ'],
                        maxlength: json['Kinh độ tối đa 50 kí tự']
                    },
                    longitude: {
                        required: json['Hãy nhập vĩ độ'],
                        maxlength: json['Kinh độ tối đa 50 kí tự']
                    },
                    branch_id: {
                        required: json['Hãy chọn chi nhánh']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('timekeeping-config.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    timekeeping_config_id: id,
                    wifi_name: $('[name="wifi_name"]').val(),
                    wifi_ip: $('[name="wifi_ip"]').val(),
                    timekeeping_type: $('[name="timekeeping_type"]:checked').val(),
                    latitude: $('[name="latitude"]').val(),
                    longitude: $('[name="longitude"]').val(),
                    allowable_radius: $('[name="allowable_radius"]').val(),
                    branch_id: $('#branch_id').val(),
                    note: $('[name="note"]').val(),
                    note_gps: $('[name="note_gps"]').val()
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
    }

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