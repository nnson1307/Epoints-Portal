var customerInfoType = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('customer-info-type.list')
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
            url: laroute.route('customer-info-type.add'),
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
            url: laroute.route('customer-info-type.edit'),
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
                        url: laroute.route('customer-info-type.delete'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            customer_info_type_id: id
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

    updateStatus: function (id, status) {
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
                        url: laroute.route('customer-info-type.update-status'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            customer_info_type_id: id,
                            status: status
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                            $('#autotable').PioTable('refresh');
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
                    customer_info_type_name_vi: { required: true },
                    customer_info_type_name_en: {required: true }
                },
                messages: {
                    customer_info_type_name_vi: { required: json['Hãy nhập tên loại thông tin kèm theo tiếng Việt'] },
                    customer_info_type_name_en: { required: json['Hãy nhập tên loại thông tin kèm theo tiếng Anh'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('customer-info-type.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_info_type_name_vi: $('#customer_info_type_name_vi').val(),
                    customer_info_type_name_en: $('#customer_info_type_name_en').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#autotable').PioTable('refresh');
                            }
                            if (result.value == true) {
                                $('#autotable').PioTable('refresh');
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
                    customer_info_type_name_vi: { required: true },
                    customer_info_type_name_en: { required: true }
                },
                messages: {
                    customer_info_type_name_vi: { required: json['Hãy nhập tên loại thông tin kèm theo tiếng Việt'] },
                    customer_info_type_name_en: { required: json['Hãy nhập tên loại thông tin kèm theo tiếng Anh'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('customer-info-type.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_info_type_id: id,
                    customer_info_type_name_vi: $('#customer_info_type_name_vi').val(),
                    customer_info_type_name_en: $('#customer_info_type_name_en').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#autotable').PioTable('refresh');
                            }
                            if (result.value == true) {
                                $('#autotable').PioTable('refresh');
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