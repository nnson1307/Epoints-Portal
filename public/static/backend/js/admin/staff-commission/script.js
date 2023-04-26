var is_load = 0
var staffCommission = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('admin.staff-commission.list')
        });

        $.getJSON(laroute.route('translate'), function (json) {
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
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
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
            });
        });
    },

    create: function (staffId) {
        $.ajax({
            url: laroute.route('admin.staff-commission.create'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_available: staffId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-create').modal('show');
                $('#staff_id').select2({
                    placeholder: 'Chọn nhân viên'
                });
                new AutoNumeric.multiple('#staff_money, #staff_money', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 0,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });
    },

    edit: function (id) {
        $.ajax({
            url: laroute.route('admin.staff-commission.edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-edit').modal('show');
                $('#staff_id').select2({
                    placeholder: 'Chọn nhân viên'
                });
                new AutoNumeric.multiple('#staff_money, #staff_money', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 0,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
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
                        url: laroute.route('admin.staff-commission.delete'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                if (is_load == 0) {
                                    $('#autotable').PioTable('refresh');
                                } else {
                                    window.location.reload();
                                }
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    }
}

var create = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');
            form.validate({
                rules: {
                    staff_id: { required: true },
                    staff_money: {required: true }
                },
                messages: {
                    staff_id: { required: json['Hãy chọn nhân viên'] },
                    staff_money: { required: json['Hãy nhập tiền hoa hồng nhân viên'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('admin.staff-commission.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    staff_id: $('#staff_id').val(),
                    staff_money: $('#staff_money').val(),
                    note: $('#note').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                if (is_load == 0) {
                                    $('#autotable').PioTable('refresh');
                                } else {
                                    window.location.reload();
                                }
                            }
                            if (result.value == true) {
                                if (is_load == 0) {
                                    $('#autotable').PioTable('refresh');
                                } else {
                                    window.location.reload();
                                }
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                    $('#modal-create').modal('hide');
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
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');
            form.validate({
                rules: {
                    staff_id: { required: true },
                    staff_money: {required: true }
                },
                messages: {
                    staff_id: { required: json['Hãy chọn nhân viên'] },
                    staff_money: { required: json['Hãy nhập tiền hoa hồng nhân viên'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('admin.staff-commission.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    staff_id: $('#staff_id').val(),
                    staff_money: $('#staff_money').val(),
                    note: $('#note').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                if (is_load == 0) {
                                    $('#autotable').PioTable('refresh');
                                } else {
                                    window.location.reload();
                                }
                            }
                            if (result.value == true) {
                                if (is_load == 0) {
                                    $('#autotable').PioTable('refresh');
                                } else {
                                    window.location.reload();
                                }
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                    $('#modal-edit').modal('hide');
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
}