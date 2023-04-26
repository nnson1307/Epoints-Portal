var remindUse = {
    _init: function () {
        $('#sent_date').datepicker({
            startDate: '0d',
            language: 'vi',
            orientation: "bottom left", todayHighlight: !0,
        });

        $('#sent_time').timepicker({
            minuteStep: 1,
            defaultTime: "",
            showMeridian: !1,
            snapToStep: !0,
        });
    },
    save: function (id) {
        var is_finish = 0;
        if ($('#is_finish').is(':checked')) {
            is_finish = 1;
        }

        $.ajax({
            url: laroute.route('customer-remind-use.update'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_remind_use_id: id,
                sent_date: $('#sent_date').val(),
                sent_time: $('#sent_time').val(),
                note: $('#note').val(),
                is_finish: is_finish,
                is_sent_notify: $('#is_sent_notify').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.href = laroute.route('customer-remind-use');
                        }
                        if (result.value == true) {
                            window.location.href = laroute.route('customer-remind-use');
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    modalCare: function (id) {
        $.ajax({
            url: laroute.route('customer-remind-use.modal-care'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_remind_use_id: id,
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-care').modal('show');
            }
        });
    },
    submitCare: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-care');

            form.validate({
                rules: {
                    type_name: {
                        required: true,
                        maxlength: 190
                    },
                    content: {
                        required: true,
                    },
                },
                messages: {
                    type_name: {
                        required: json['Hãy nhập loại chăm sóc'],
                        maxlength: json["Loại chăm sóc tối đa 190 kí tự"]
                    },
                    content: {
                        required: json['Hãy nhập nội dung chăm sóc']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('customer-remind-use.submit-care'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    type_name: $('#type_name').val(),
                    content: $('#content').val(),
                    customer_remind_use_id: id
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.reload();
                            }
                            if (result.value == true) {
                                window.location.reload();
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                }
            });
        });
    }
};