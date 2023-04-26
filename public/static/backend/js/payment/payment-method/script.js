$(document).ready(function () {
    $('.select2').select2();
    $(".date-picker").datepicker({
        todayHighlight: !0,
        autoclose: !0,
        format: "dd/mm/yyyy"
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

});
var paymentMethod = {
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function () {
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route("payment-method.delete"),
                        method: "POST",
                        data: {
                            payment_method_id: id
                        },
                        success: function (result){
                            swal(
                                json['Xóa thành công'],
                                '',
                                'success'
                            ).then(function(){
                                $('#autotable').PioTable('refresh');
                            });
                        }
                    })
                }
            });
        });
    },
    add: function (close) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#formCreate');
            form.validate({
                rules: {
                    payment_method_code: {
                        required: true
                    },
                    payment_method_name_vi: {
                        required: true
                    },
                    payment_method_name_en: {
                        required: true
                    },
                    note: {
                        maxlength: 190
                    }
                },
                messages: {
                    payment_method_code: {
                        required: json['Hãy nhập mã hình thức thanh toán']
                    },
                    payment_method_name_vi: {
                        required: json['Hãy nhập tên hình thức thanh toán (Tiếng Việt)']
                    },
                    payment_method_name_en: {
                        required: json['Hãy nhập tên hình thức thanh toán (Tiếng Anh)']
                    },
                    note: {
                        maxlength: json['Ghi chú tối đa 190 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                method: 'POST',
                url: laroute.route('payment-method.store'),
                data: {
                    payment_method_code: $('[name="payment_method_code"]').val(),
                    payment_method_name_vi: $('[name="payment_method_name_vi"]').val(),
                    payment_method_name_en: $('[name="payment_method_name_en"]').val(),
                    payment_method_type: $('[name="payment_method_type"] option:selected').val(),
                    note: $('#note').val()
                },
                dataType: "JSON",
                success: function (response) {
                    if (!response.error) {
                        swal.fire(
                            response.message,
                            '',
                            'success'
                        ).then(function(e){
                            location.href = "/payment/payment-method";
                        })
                    } else {
                        swal(response.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm mới thất bại'], mess_error, "error");
                }
            })
        });
    },
    save: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#formEdit');
            if($('[name="payment_method_code"]').val() == 'VNPAY'){
                var formVnPay = $('#formEdit');
                formVnPay.validate({
                    rules: {
                        payment_method_code: {
                            required: true
                        },
                        payment_method_name_vi: {
                            required: true
                        },
                        payment_method_name_en: {
                            required: true
                        },
                        note: {
                            maxlength: 190
                        },
                        url: {
                            required: true
                        },
                        terminal_id: {
                            required: true
                        },
                        secret_key: {
                            required: true
                        }
                    },
                    messages: {
                        payment_method_code: {
                            required: json['Hãy nhập mã hình thức thanh toán']
                        },
                        payment_method_name_vi: {
                            required: json['Hãy nhập tên hình thức thanh toán (Tiếng Việt)']
                        },
                        payment_method_name_en: {
                            required: json['Hãy nhập tên hình thức thanh toán (Tiếng Anh)']
                        },
                        note: {
                            maxlength: json['Ghi chú tối đa 190 kí tự']
                        },
                        url: {
                            required: json['Hãy nhập url khởi tạo giao dịch']
                        },
                        terminal_id: {
                            required: json['Hãy nhập terminal ID']
                        },
                        secret_key: {
                            required: json['Hãy nhập secret key']
                        }
                    },
                });
            }
            else if($('[name="payment_method_code"]').val() == 'MOMO'){
                form.validate({
                    rules: {
                        payment_method_code: {
                            required: true
                        },
                        payment_method_name_vi: {
                            required: true
                        },
                        payment_method_name_en: {
                            required: true
                        },
                        note: {
                            maxlength: 190
                        },
                        terminal_id: {
                            required: true
                        },
                        access_key: {
                            required: true
                        },
                        secret_key: {
                            required: true
                        }
                    },
                    messages: {
                        payment_method_code: {
                            required: json['Hãy nhập mã hình thức thanh toán']
                        },
                        payment_method_name_vi: {
                            required: json['Hãy nhập tên hình thức thanh toán (Tiếng Việt)']
                        },
                        payment_method_name_en: {
                            required: json['Hãy nhập tên hình thức thanh toán (Tiếng Anh)']
                        },
                        note: {
                            maxlength: json['Ghi chú tối đa 190 kí tự']
                        },
                        terminal_id: {
                            required: json['Hãy nhập partner code']
                        },
                        access_key: {
                            required: json['Hãy nhập access key']
                        },
                        secret_key: {
                            required: json['Hãy nhập secret key']
                        }
                    },
                });
            }
            else {
                form.validate({
                    rules: {
                        payment_method_code: {
                            required: true
                        },
                        payment_method_name_vi: {
                            required: true
                        },
                        payment_method_name_en: {
                            required: true
                        },
                        note: {
                            maxlength: 190
                        }
                    },
                    messages: {
                        payment_method_code: {
                            required: json['Hãy nhập mã hình thức thanh toán']
                        },
                        payment_method_name_vi: {
                            required: json['Hãy nhập tên hình thức thanh toán (Tiếng Việt)']
                        },
                        payment_method_name_en: {
                            required: json['Hãy nhập tên hình thức thanh toán (Tiếng Anh)']
                        },
                        note: {
                            maxlength: json['Ghi chú tối đa 190 kí tự']
                        }
                    },
                });
            }
            if (!form.valid()) {
                return false;
            }
            var statusEdit = 0;
            if($('#is_active').is(':checked'))
            {
                statusEdit=1;
            }
            $.ajax({
                method: 'POST',
                url: laroute.route('payment-method.update'),
                data: {
                    payment_method_id: parseInt($('[name="payment_method_id"]').val()),
                    payment_method_code: $('[name="payment_method_code"]').val(),
                    payment_method_name_vi: $('[name="payment_method_name_vi"]').val(),
                    payment_method_name_en: $('[name="payment_method_name_en"]').val(),
                    payment_method_type: $('[name="payment_method_type"] option:selected').val(),
                    is_active: statusEdit,
                    note: $('#note').val(),
                    url: $('[name="url"]').val(),
                    terminal_id: $('[name="terminal_id"]').val(),
                    access_key: $('[name="access_key"]').val(),
                    secret_key: $('[name="secret_key"]').val(),
                },
                dataType: "JSON",
                success: function (response) {
                    if (!response.error) {
                        swal.fire(
                            response.message,
                            '',
                            'success'
                        ).then(function(e){
                            location.reload(true);
                        })
                    } else {
                        swal(response.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                }
            })
        });
    },
    refresh: function () {
        $('input[name="search"]').val('');
        $(".btn-search").trigger("click");
    },

};
$('#autotable').PioTable({
    baseUrl: laroute.route('payment-method.list')
});