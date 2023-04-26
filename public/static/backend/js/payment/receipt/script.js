var listReceipt = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('receipt.list')
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
                autoUpdateInput: true,
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
            });

            $("#created_at").val("");
        });
    },
    // Xoá phiếu thu
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
                        url: laroute.route('receipt.delete'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            receiptId: id
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
    // in bill
    printBill: function (id) {
        $("input[name=print_receipt_id]").val(id);
        $('#form-print-bill').submit();
    },
    searchList: function () {
        let search = $("input[name='search']").val();
        let status = $("select[name='receipts$status']").val();
        let created_at = $("input[name='created_at']").val();

        $('#search_export').val(search);
        $('#status_export').val(status);
        $('#created_at_export').val(created_at);
    }
}

var create = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#receipt_type_code').select2({
                placeholder: json['Chọn loại phiếu thu']
            });

            $('#object_accounting_id').select2({
                placeholder: json['Chọn đối tượng']
            });

            $('#object_accounting_type_code').select2({
                placeholder: json['Chọn loại đối tượng thu chi']
            });

            $('#payment_method').select2({
                placeholder: json['Chọn loại hình thức thanh toán']
            }).on('select2:select', function (event) {
                let methodCode = event.params.data.id;

                if (methodCode == 'VNPAY') {
                    $('.div_payment_online').css('display', 'block');
                } else {
                    $('.div_payment_online').css('display', 'none');
                }
            });
        });
        // Nếu loại đối tượng thu chi là SHIPPER, OTHER thì cho input nhập tên
        // Còn lại thì load option theo loại đối tượng
        new AutoNumeric.multiple('#money', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
    },

    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');
            form.validate({
                rules: {
                    receipt_type_code: { required: true },
                    money: {required: true },
                    object_accounting_type_code: {required: true },
                    payment_method: {required: true }
                },
                messages: {
                    receipt_type_code: { required: json['Hãy chọn loại phiếu thu'] },
                    money: {required: json['Hãy nhập số tiền'] },
                    object_accounting_type_code: {required: json['Hãy chọn thông tin người trả tiền'] },
                    payment_method: {required: json['Hãy chọn hình thức thanh toán'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            let flag = true;
            // check thông tin người trả tiền
            let objAccountingType = $('#object_accounting_type_code').val();
            if (objAccountingType == 'OAT_SHIPPER' || objAccountingType == 'OAT_OTHER') {
                // check tên
                if ($('#object_accounting_name').val() == "") {
                    $('.error-obj-acc-name').text(json['Hãy nhập tên']);
                    flag = false;
                } else {
                    $('.error-obj-acc-name').text('');
                }
            } else {
                // check id
                if ($('#object_accounting_id').val() == "") {
                    $('.error-obj-acc-id').text(json['Hãy chọn người trả tiền']);
                    flag = false;
                } else {
                    $('.error-obj-acc-id').text('');
                }
            }
            if (flag) {
                $.ajax({
                    url: laroute.route('receipt.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        receiptTypeCode: $('#receipt_type_code').val(),
                        money: $('#money').val(),
                        objectAccountingTypeCode: $('#object_accounting_type_code').val(),
                        note: $('#note').val(),
                        objectAccountingId: $('#object_accounting_id').val(),
                        objectAccountingName: $('#object_accounting_name').val(),
                        paymentMethodId: $('#payment_method').val(),
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    if (res.url != "") {
                                        window.open(res.url, '_blank');
                                    }
                                    window.location.href = laroute.route('receipt');
                                }
                                if (result.value == true) {
                                    if (res.url != "") {
                                        window.open(res.url, '_blank');
                                    }
                                    window.location.href = laroute.route('receipt');
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
            }
        });
    },

    printAndSave: function (id) {

    }
}

var edit = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#receipt_type_code').select2({
                placeholder: 'Chọn loại phiếu thu'
            });
            $('#object_accounting_id').select2({
                placeholder: 'Chọn đối tượng'
            });
            $('#object_accounting_type_code').select2({
                placeholder: 'Chọn loại đối tượng thu chi'
            });
            $('#payment_method').select2({
                placeholder: json['Chọn loại hình thức thanh toán']
            }).on('select2:select', function (event) {
                let methodCode = event.params.data.id;

                if (methodCode == 'VNPAY') {
                    $('.div_payment_online').css('display', 'block');
                } else {
                    $('.div_payment_online').css('display', 'none');
                }
            });
            // Nếu loại đối tượng thu chi là SHIPPER, OTHER thì cho input nhập tên
            // Còn lại thì load option theo loại đối tượng
            let objAccountingType = $('#object_accounting_type_code').val();
            let objAccountingId = $('#obj_acc_hidden').val();
            $.ajax({
                url: laroute.route('receipt.load-option-obj-accounting'),
                dataType: 'JSON',
                data: {
                    objAccountingType: objAccountingType
                },
                method: 'POST',
                success: function (res) {
                    $('#object_accounting_id').empty();
                    $.map(res, function (item) {
                        if (item.accounting_id == objAccountingId) {
                            $('#object_accounting_id').append('<option value="' + item.accounting_id + '" selected>' + item.accounting_name + '</option>');
                        } else {
                            $('#object_accounting_id').append('<option value="' + item.accounting_id + '">' + item.accounting_name + '</option>');

                        }
                    });
                }
            });

            new AutoNumeric.multiple('#money', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });
        });
    },
    save: function (id, isPaid, isGenCode = 0) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');
            form.validate({
                rules: {
                    receipt_type_code: { required: true },
                    money: {required: true },
                    object_accounting_type_code: {required: true },
                    payment_method: {required: true }
                },
                messages: {
                    receipt_type_code: { required: json['Hãy chọn loại phiếu thu'] },
                    money: {required: json['Hãy nhập số tiền'] },
                    object_accounting_type_code: {required: json['Hãy chọn thông tin người trả tiền'] },
                    payment_method: {required: json['Hãy chọn hình thức thanh toán'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            let flag = true;
            let status = 'unpaid';
            // check thông tin người trả tiền
            let objAccountingType = $('#object_accounting_type_code').val();
            if (objAccountingType == 'OAT_SHIPPER' || objAccountingType == 'OAT_OTHER') {
                // check tên
                if ($('#object_accounting_name').val() == "") {
                    $('.error-obj-acc-name').text(json['Hãy nhập tên']);
                    flag = false;
                } else {
                    $('.error-obj-acc-name').text('');
                }
            } else {
                // check id
                if ($('#object_accounting_id').val() == "") {
                    $('.error-obj-acc-id').text(json['Hãy chọn người trả tiền']);
                    flag = false;
                } else {
                    $('.error-obj-acc-id').text('');
                }
            }
            if (isPaid) {
                status = 'paid'
            }
            if (flag) {
                $.ajax({
                    url: laroute.route('receipt.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        receiptId: id,
                        receiptTypeCode: $('#receipt_type_code').val(),
                        money: $('#money').val(),
                        objectAccountingTypeCode: $('#object_accounting_type_code').val(),
                        note: $('#note').val(),
                        objectAccountingId: $('#object_accounting_id').val(),
                        objectAccountingName: $('#object_accounting_name').val(),
                        paymentMethodId: $('#payment_method').val(),
                        status: status,
                        gen_qr_code: isGenCode
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    if (res.url != "") {
                                        window.open(res.url, '_blank');
                                    } else {
                                        window.location.href = laroute.route('receipt');
                                    }
                                }
                                if (result.value == true) {
                                    if (res.url != "") {
                                        window.open(res.url, '_blank');
                                    } else {
                                        window.location.href = laroute.route('receipt');
                                    }
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
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
};

var view = {
    changeType: function (obj) {
        if ($(obj).val() == 'OAT_SHIPPER' || $(obj).val() == 'OAT_OTHER') {
            $('.div_add_name').css('display', 'block');
            $('.div_add_id').css('display', 'none');
            $('#object_accounting_id').empty();
        } else {
            $('.div_add_name').css('display', 'none');
            $('.div_add_id').css('display', 'block');
            // Load option theo type
            let objAccountingType = $(obj).val();
            $.ajax({
                url: laroute.route('receipt.load-option-obj-accounting'),
                dataType: 'JSON',
                data: {
                    objAccountingType: objAccountingType
                },
                method: 'POST',
                success: function (res) {
                    $('#object_accounting_id').empty();
                    $.map(res, function (item) {
                        $('#object_accounting_id').append('<option value="' + item.accounting_id + '">' + item.accounting_name + '</option>');
                    });
                }
            });
        }
    },
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var printBill = {
    printBill: function () {
        $.ajax({
            url: laroute.route('receipt.save-log-print-bill'),
            method: "POST",
            data: {
                id: $('#receipt_id').val()
            },
            async:false,
            success: function (res) {
                if (res.error == false) {
                    $('.error-print-bill').empty();
                    $("#PrintArea").print();
                    window.onafterprint = function(e){
                        $(window).off('mousemove', window.onafterprint);
                        location.reload();
                    };
                    setTimeout(function(){
                        $(window).one('mousemove', window.onafterprint);
                    }, 100);
                } else {
                    $('.error-print-bill').text(res.message);
                }
            }
        });
    },
    back:function () {
        window.top.close();
    }
};