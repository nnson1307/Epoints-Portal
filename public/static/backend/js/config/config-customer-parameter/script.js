var create = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    parameter_name: {
                        required: true,
                        maxlength: 190
                    },
                    content: {
                        required: true,
                        maxlength: 190
                    }
                },
                messages: {
                    parameter_name: {
                        required: json['Hãy nhập tên tham số'],
                        maxlength: json['Tên tham số tối đa 190 kí tự']
                    },
                    content: {
                        required: json['Hãy nhập nội dung'],
                        maxlength: json['Nội dung tối đa 190 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('config.customer-parameter.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    parameter_name: $('#parameter_name').val(),
                    content: $('#content').val(),

                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('config.customer-parameter');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('config.customer-parameter');
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
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    parameter_name: {
                        required: true,
                        maxlength: 190
                    },
                    content: {
                        required: true,
                        maxlength: 190
                    }
                },
                messages: {
                    parameter_name: {
                        required: json['Hãy nhập tên tham số'],
                        maxlength: json['Tên tham số tối đa 190 kí tự']
                    },
                    content: {
                        required: json['Hãy nhập nội dung'],
                        maxlength: json['Nội dung tối đa 190 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('config.customer-parameter.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    parameter_id: id,
                    parameter_name: $('#parameter_name').val(),
                    content: $('#content').val(),

                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('config.customer-parameter');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('config.customer-parameter');
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