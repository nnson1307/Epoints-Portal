var list = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('extension.list')
        });
    },
    showPopAccount() {
        $.ajax({
            url: laroute.route('extension.modal-account'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#config-account').modal('show');
            }
        });
    },
    submitSetting(id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-config');

            form.validate({
                rules: {
                    user_name: {
                        required: true,
                        maxlength: 191
                    },
                    password: {
                        required: true,
                    }
                },
                messages: {
                    user_name: {
                        required: json['Hãy nhập tên tài khoản'],
                        maxlength: json['Tên tài khoản tối đa 191 kí tự']
                    },
                    password: {
                        required: json['Hãy nhập mật khẩu']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            // var isActived = 0;
            //
            // if ($('#is_actived').is(':checked')) {
            //     isActived = 1;
            // }

            $.ajax({
                url: laroute.route('extension.submit-setting'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    user_name: $('#user_name').val(),
                    password: $('#password').val(),
                    // is_actived: isActived
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#config-account').modal('hide');

                                window.location.reload();
                            }
                            if (result.value == true) {
                                $('#config-account').modal('hide');

                                window.location.reload();
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
                    swal(json['Cấu hình tài khoản thất bại'], mess_error, "error");
                }
            });
        });
    },
    showModalAssign(extensionId) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('extension.modal-assign'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    extension_id: extensionId
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#assign-staff').modal('show');

                    $('#staff_id').select2({
                        placeholder: json['Chọn nhân viên']
                    });
                }
            });
        });
    },
    submitAssign: function (extensionId) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-assign');

            form.validate({
                rules: {
                    staff_id: {
                        required: true,
                    },
                },
                messages: {
                    staff_id: {
                        required: json['Hãy chọn nhân viên']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('extension.submit-assign'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    extension_id: extensionId,
                    staff_id: $('#staff_id').val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#assign-staff').modal('hide');
                                $('#autotable').PioTable('refresh');
                            }
                            if (result.value == true) {
                                $('#assign-staff').modal('hide');
                                $('#autotable').PioTable('refresh');
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
                    swal(json['Phân bổ nhân viên thất bại'], mess_error, "error");
                }
            });
        });
    },
    syncExtension: function () {
        $.ajax({
            url: laroute.route('extension.sync-extension'),
            method: 'POST',
            dataType: 'JSON',
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
            }
        });
    }
};

var index = {
    updateStatusExtension: function (id, is_actived) {
        $.ajax({
            url: laroute.route('extension.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                extension_id: id,
                status: is_actived
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
    },
}