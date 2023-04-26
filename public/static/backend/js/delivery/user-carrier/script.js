var listCarrier = {
    changeStatus: function (userCarrierId, obj) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('user-carrier.change-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                user_carrier_id: userCarrierId,
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    remove: function (userCarrierId) {

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
                        url: laroute.route('user-carrier.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            user_carrier_id: userCarrierId
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
    }
};

var create = {
    _init: function () {
        $('#phone').ForceNumericOnly();
    },
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    full_name: {
                        required: true,
                        maxlength: 250
                    },
                    phone: {
                        required: true,
                        integer: true,
                        maxlength: 10
                    },
                    address: {
                        maxlength: 250
                    },
                    user_name: {
                        required: true,
                        minlength: 5,
                        maxlength: 30
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    password_confirm: {
                        // minlength : 5,
                        equalTo: "#password"
                    }
                },
                messages: {
                    full_name: {
                        required: json['Hãy nhập họ và tên'],
                        maxlength: json['Họ và tên tối đa 250 kí tự']
                    },
                    phone: {
                        required: json['Hãy nhập số điện thoại'],
                        integer: json['Số điện thoại không hợp lệ'],
                        maxlength: json['Số điện thoại tối đa 10 kí tự']
                    },
                    address: {
                        maxlength: json['Địa chỉ tối đa 250 kí tự']
                    },
                    user_name: {
                        required: json['Hãy nhập tên tài khoản'],
                        minlength: json['Tên tài khoản tối thiểu 5 kí tự'],
                        maxlength: json['Tên tài khoản tối đa 30 kí tự']
                    },
                    password: {
                        required: json['Hãy nhập mật khẩu'],
                        minlength: json['Mật khẩu tối thiểu 5 kí tự']
                    },
                    password_confirm: {
                        // minlength : 5,
                        equalTo: json['Xác nhận mật khẩu không đúng']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('user-carrier.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    full_name: $('#full_name').val(),
                    phone: $('#phone').val(),
                    gender: $('input[name="gender"]:checked').val(),
                    address: $('#address').val(),
                    avatar: $('#avatar').val(),
                    user_name: $('#user_name').val(),
                    password: $('#password').val(),
                    password_confirm: $('#password_confirm').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('user-carrier');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('user-carrier');
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
};

var edit = {
    _init: function () {
        $('#phone').ForceNumericOnly();
    },
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    full_name: {
                        required: true,
                        maxlength: 250
                    },
                    phone: {
                        required: true,
                        integer: true,
                        maxlength: 10
                    },
                    address: {
                        maxlength: 250
                    },
                    user_name: {
                        required: true,
                        minlength: 5,
                        maxlength: 30
                    },
                    password_new: {
                        minlength: 5
                    },
                    password_confirm: {
                        equalTo: "#password_new"
                    }
                },
                messages: {
                    full_name: {
                        required: json['Hãy nhập họ và tên'],
                        maxlength: json['Họ và tên tối đa 250 kí tự']
                    },
                    phone: {
                        required: json['Hãy nhập số điện thoại'],
                        integer: json['Số điện thoại không hợp lệ'],
                        maxlength: json['Số điện thoại tối đa 10 kí tự']
                    },
                    address: {
                        maxlength: json['Địa chỉ tối đa 250 kí tự']
                    },
                    user_name: {
                        required: json['Hãy nhập tên tài khoản'],
                        minlength: json['Tên tài khoản tối thiểu 5 kí tự'],
                        maxlength: json['Tên tài khoản tối đa 30 kí tự']
                    },
                    password_new: {
                        minlength: json['Mật khẩu tối thiểu 5 kí tự']
                    },
                    password_confirm: {
                        equalTo: json['Xác nhận mật khẩu không đúng']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var is_actived = 0;
            if ($("#is_actived").is(':checked')) {
                is_actived = 1;
            }

            $.ajax({
                url: laroute.route('user-carrier.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    full_name: $('#full_name').val(),
                    phone: $('#phone').val(),
                    gender: $('input[name="gender"]:checked').val(),
                    address: $('#address').val(),
                    avatar: $('#avatar').val(),
                    avatar_old: $('#avatar_old').val(),
                    user_name: $('#user_name').val(),
                    password_new: $('#password_new').val(),
                    password_confirm: $('#password_confirm').val(),
                    is_actived: is_actived,
                    user_carrier_id: id
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('user-carrier');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('user-carrier');
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
};

$('#autotable').PioTable({
    baseUrl: laroute.route('user-carrier.list')
});

function uploadAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#image');
        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_user-carrier.');
        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function () {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());

        if (Math.round(fsize / 1024) <= 10240) {
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == 0) {
                        $('#avatar').val(res.file);
                    }
                }
            });
        } else {
            swal("Hình ảnh vượt quá dung lượng cho phép", "", "error");
        }
    }
}

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