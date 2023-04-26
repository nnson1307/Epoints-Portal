
var create = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');

            form.validate({
                rules: {
                    address: {
                        required: true,
                        maxlength: 250
                    },
                },
                messages: {
                    address: {
                        required: json['Hãy nhập địa chỉ lấy hàng'],
                        maxlength: json['Địa chỉ lấy hàng tối đa 250 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            // get data is active
            let is_actived = 0;
            if ($('#is_actived').is(":checked")) {
                is_actived = 1;
            } else {
                is_actived = 0;
            }
            $.ajax({
                url: laroute.route('pickup-address.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    address: $('#address').val(),
                    is_actived: is_actived
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('pickup-address');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('pickup-address');
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
    save: function (pickup_address_id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    address: {
                        required: true,
                        maxlength: 250
                    },
                },
                messages: {
                    address: {
                        required: json['Hãy nhập địa chỉ lấy hàng'],
                        maxlength: json['Địa chỉ lấy hàng tối đa 250 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            // get data is active
            let is_actived = 0;
            if ($('#is_actived').is(":checked")) {
                is_actived = 1;
            } else {
                is_actived = 0;
            }
            $.ajax({
                url: laroute.route('pickup-address.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    address: $('#address').val(),
                    is_actived: is_actived,
                    pickup_address_id: pickup_address_id
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('pickup-address');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('pickup-address');
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
        });
    }
}


var list = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('pickup-address.list')
        });
    },
    remove: function (pickup_address_id) {
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
                        url: laroute.route('pickup-address.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            pickup_address_id: pickup_address_id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location = laroute.route('pickup-address');
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