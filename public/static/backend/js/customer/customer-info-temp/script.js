var view = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#birthday').datepicker({
                format: "dd/mm/yyyy",
                orientation: "bottom left", todayHighlight: !0,
            });

            $('#province_id').select2({
                placeholder: json["Chọn tỉnh/thành"],
            });

            $('#district_id').select2({
                placeholder: json["Chọn quận/huyện"]
            });
        });
    },
    changeProvince: function (obj) {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $(obj).val(),
            },
            method: 'POST',
            success: function (res) {
                $('#district_id').empty();
                $.map(res.optionDistrict, function (a) {
                    $('#district_id').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },
    confirm: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-confirm');

            form.validate({
                rules: {
                    full_name: {
                        required: true,
                        maxlength: 190
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    province_id: {
                        required: true
                    },
                    district_id: {
                        required: true
                    },
                    address: {
                        required: true,
                        maxlength: 190
                    },

                },
                messages: {
                    full_name: {
                        required: json["Hãy nhập tên khách hàng"],
                        maxlength: json["Tên khách hàng tối đa 190 kí tự"]
                    },
                    phone: {
                        required: json["Hãy nhập số điện thoại"],
                        number: json["Số điện thoại không hợp lệ"],
                        minlength: json["Số điện thoại không hợp lệ"],
                        maxlength: json["Số điện thoại không hợp lệ"]
                    },
                    province_id: {
                        required: json["Hãy chọn tỉnh thành"]
                    },
                    district_id: {
                        required: json["Hãy chọn quận huyện"]
                    },
                    address: {
                        required: json["Hãy nhập địa chỉ"],
                        maxlength: json["Địa chỉ tối đa 190 kí tự"]
                    },

                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('customer-info.submit-confirm'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_id: $('#customer_id').val(),
                    full_name: $('#full_name').val(),
                    phone: $('#phone').val(),
                    email: $('#email').val(),
                    birthday: $('#birthday').val(),
                    province_id: $('#province_id').val(),
                    district_id: $('#district_id').val(),
                    address: $('#address').val(),
                    gender: $('input[name="gender"]:checked').val(),
                    customer_info_temp_id: $('#customer_info_temp_id').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('customer-info-temp');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('customer-info-temp');
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
                    swal(json['Xác nhận thất bại'], mess_error, "error");
                }
            });
        });
    }
};