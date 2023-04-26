var forgetPass = {
    init: function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    },
    forget: function () {
        var form = $('#form_forget_password');
        $.getJSON(laroute.route('user.validation'), function (json) {
            form.validate({
                rules: {
                    email_forget: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    email_forget: {
                        required: json.login.enter_email,
                        email: json.login.error_format_email
                    }
                }
            });
            if (form.valid()) {
                $.ajax({
                    url: laroute.route('login.submitForgetPassword'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        email: $('#email_forget').val(),
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                        } else {
                            swal.fire(res.message, "", "error");
                        }
                    }
                });
            } else {
                $('label.error').addClass('text-danger');
            }
        });
    },
    submitNewPassword: function (token) {
        $.getJSON(laroute.route('user.validation'), function (json) {
            $.ajax({
                url: laroute.route('login.submitNewPassword'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    token: token,
                    password: $('#password').val(),
                    re_password: $('#re_password').val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        swal.fire(res.message, "", "success");
                        window.location.href = laroute.route('login');
                    } else {
                        swal.fire(res.message, "", "error");
                    }
                },
                error: function (res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire('', mess_error, "error");
                    }
                }
            });
        });
    }
};
forgetPass.init();