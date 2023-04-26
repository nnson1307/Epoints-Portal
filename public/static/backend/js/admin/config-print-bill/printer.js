var stt = 0;
var IP_ADRESS_REX = /^(?:(?:^|\.)(?:2(?:5[0-5]|[0-4]\d)|1?\d?\d)){4}$/

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.config-print-bill.printers')
});


var create = {
    popupCreate: function (load) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('admin.config-print-bill.printers.create'),
                method: 'POST',
                dataType: 'JSON',
                // data: {
                //     load: load
                // },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-create').modal('show');

                    // $('.timepicker').timepicker({
                    //     minuteStep: 1,
                    //     defaultTime: "",
                    //     showMeridian: !1,
                    //     snapToStep: !0,
                    // });
                    //
                    // new AutoNumeric.multiple('#min_time_work', {
                    //     currencySymbol: '',
                    //     decimalCharacter: '.',
                    //     digitGroupSeparator: ',',
                    //     decimalPlaces: decimal_number,
                    //     eventIsCancelable: true,
                    //     minimumValue: 0
                    // });
                    //
                    // $('#branch_id').select2({
                    //     width: "100%",
                    //     placeholder: json['Chọn chi nhánh']
                    // });
                }
            });
        });
    },
    save: function (load) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    printer_name: {
                        required: true,
                        maxlength: 50
                    },
                    printer_ip: {
                        required: true,
                        maxlength: 50,
                        pattern: IP_ADRESS_REX
                    },
                    printer_port: {
                        required: true,
                        maxlength: 10
                    },
                },
                messages: {
                    printer_name: {
                        required: json['Hãy nhập tên máy in'],
                        maxlength: json['Tên máy in tối đa 50 kí tự']
                    },
                    printer_ip: {
                        required: json['Hãy nhập địa chỉ IP máy in'],
                        maxlength: json['Địa chỉ IP máy in tối đa 50 kí tự'],
                        pattern: json['Địa chỉ IP máy in không đúng định dạng']
                    },
                    printer_port: {
                        required: json['Hãy nhập cổng máy in'],
                        maxlength: json['Tên máy in tối đa 10 kí tự']
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('admin.config-print-bill.printers.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    printer_name: $('[name="printer_name"]').val(),
                    template: $('#template').val(),
                    printer_ip: $('[name="printer_ip"]').val(),
                    printer_port: $('[name="printer_port"]').val(),
                    template_width: $('[name="template_width"]').val(),
                    branch_id: $('#branch_id').val(),
                    is_actived: $('#is_actived').is(':checked') ? 1 : 0,
                    is_default: $('#is_default').is(':checked') ? 1 : 0
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
                        $('.frmFilter').submit();
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
                url: laroute.route('admin.config-print-bill.printers.edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    print_bill_device_id: id,
                    view: 'edit'
                },
                success: function (res) {
                    if(res.error == 0){
                        $('#my-modal').html(res.html);
                        $('#modal-edit').modal('show');
                    }else{
                        var mess_error = res.message;
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                }
            });
        });
    },
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    printer_name: {
                        required: true,
                        maxlength: 50
                    },
                    printer_ip: {
                        required: true,
                        maxlength: 50,
                        pattern: IP_ADRESS_REX
                    },
                    printer_port: {
                        required: true,
                        maxlength: 10
                    },
                },
                messages: {
                    printer_name: {
                        required: json['Hãy nhập tên máy in'],
                        maxlength: json['Tên máy in tối đa 50 kí tự']
                    },
                    printer_ip: {
                        required: json['Hãy nhập địa chỉ IP máy in'],
                        maxlength: json['Địa chỉ IP máy in tối đa 50 kí tự'],
                        pattern: json['Địa chỉ IP máy in không đúng định dạng']
                    },
                    printer_port: {
                        required: json['Hãy nhập cổng máy in'],
                        maxlength: json['Tên máy in tối đa 10 kí tự']
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('admin.config-print-bill.printers.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    print_bill_device_id: id,
                    printer_name: $('[name="printer_name"]').val(),
                    template: $('#template').val(),
                    printer_ip: $('[name="printer_ip"]').val(),
                    printer_port: $('[name="printer_port"]').val(),
                    template_width: $('[name="template_width"]').val(),
                    branch_id: $('#branch_id').val(),
                    is_actived: $('#is_actived').is(':checked') ? 1 : 0,
                    is_default: $('#is_default').is(':checked') ? 1 : 0
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
                        $('.frmFilter').submit();
                        // $('#autotable').PioTable({
                        //     baseUrl: laroute.route('admin.config-print-bill.printers')
                        // });
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

var listPrinters = {
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
                        url: laroute.route('admin.config-print-bill.printers.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            print_bill_device_id: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");

                                $('.frmFilter').submit();
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    changeStatus: function (obj, id) {
        var is_actived = 0;

        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('admin.config-print-bill.printers.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                print_bill_device_id: id,
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    $('.frmFilter').submit();
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    changePrinterDefault: function (obj, id) {
        var is_default = 0;

        if ($(obj).is(':checked')) {
            is_default = 1;
        }

        $.ajax({
            url: laroute.route('admin.config-print-bill.printers.default'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                print_bill_device_id: id,
                is_default: is_default
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    $('.frmFilter').submit();
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    }
};