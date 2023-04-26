var listPaymentType = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('payment-type.list')
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
            });
        });
    },
    delete: function (id) {
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
                        url: laroute.route('payment-type.delete'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            paymentTypeId: id
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
    updateStatus: function (id, isActive) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn thay đổi trạng thái không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Có'],
                cancelButtonText: json['Không'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('payment-type.update-status'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            paymentTypeId: id,
                            isActive: isActive
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
                } else {
                    $('#autotable').PioTable('refresh');
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
                    name_vi: {required: true, maxlength: 250},
                    name_en: {required: true, maxlength: 250}
                },
                messages: {
                    name_vi: {
                        required: json['Tên loại phiếu chi Tiếng Việt là bắt buộc'],
                        maxlength: json['Tên loại phiếu chi Tiếng Việt không được quá 250 ký tự']
                    },
                    name_en: {
                        required: json['Tên loại phiếu chi Tiếng Anh là bắt buộc'],
                        maxlength: json['Tên loại phiếu chi Tiếng Anh không được quá 250 ký tự']
                    }
                },
            });
            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('payment-type.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    name_vi: $('#name_vi').val(),
                    name_en: $('#name_en').val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('payment-type');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('payment-type');
                            }
                        });
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
}

var edit = {
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');
            form.validate({
                rules: {
                    name_vi: {required: true, maxlength: 250},
                    name_en: {required: true, maxlength: 250}
                },
                messages: {
                    name_vi: {
                        required: json['Tên loại phiếu chi Tiếng Việt là bắt buộc'],
                        maxlength: json['Tên loại phiếu chi Tiếng Việt không được quá 250 ký tự']
                    },
                    name_en: {
                        required: json['Tên loại phiếu chi Tiếng Anh là bắt buộc'],
                        maxlength: json['Tên loại phiếu chi Tiếng Anh không được quá 250 ký tự']
                    }
                },
            });
            if (!form.valid()) {
                return false;
            }
            // check default
            let isActive = $('#is_active').is(":checked");
            if(isActive == true) {
                isActive = 1;
            } else {
                isActive = 0;
            }
            $.ajax({
                url: laroute.route('payment-type.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    payment_type_id: id,
                    name_vi: $('#name_vi').val(),
                    name_en: $('#name_en').val(),
                    is_active: isActive
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('payment-type');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('payment-type');
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Cập nhật thất bại'], mess_error, "error");
                }
            });
        });
    }
}