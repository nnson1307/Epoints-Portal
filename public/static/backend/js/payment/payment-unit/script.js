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
var paymentUnit = {
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
                        url: laroute.route("payment-unit.delete"),
                        method: "POST",
                        data: {
                            payment_unit_id: id
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
                    name: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: json['Hãy nhập tên đơn vị thanh toán']
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                method: 'POST',
                url: laroute.route('payment-unit.store'),
                data: {
                    name: $('[name="name"]').val(),
                    is_actived: 1
                },
                dataType: "JSON",
                success: function (response) {
                    if (!response.error) {
                        swal.fire(
                            response.message,
                            '',
                            'success'
                        ).then(function(e){
                            location.href = "/payment/payment-unit";
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
            form.validate({
                rules: {
                    name: {
                        required: true
                    },
                },
                messages: {
                    payment_method_code: {
                        required: json['Hãy nhập tên đơn vị thanh toán']
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }
            var statusEdit = 0;
            if($('#is_actived').is(':checked'))
            {
                statusEdit=1;
            }
            $.ajax({
                method: 'POST',
                url: laroute.route('payment-unit.update'),
                data: {
                    payment_unit_id: $('[name="payment_unit_id"]').val(),
                    name: $('[name="name"]').val(),
                    is_actived: statusEdit,
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
    baseUrl: laroute.route('payment-unit.list')
});