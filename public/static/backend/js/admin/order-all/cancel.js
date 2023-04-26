var cancel = {
    modal_cancel: function (id) {
        $.ajax({
            url: laroute.route('admin.order.cancel'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                order_id: id
            },
            success: function (res) {
                $('#my-modal').html(res.view).find($('#modal_cancel').modal({
                    backdrop: 'static',
                    keyboard: false
                }));
            }
        });
    },
    submit_cancel: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-cancel');
            form.validate({
                rules: {
                    order_description: {
                        required: true,
                        maxlength: 255
                    },
                },
                messages: {
                    order_description: {
                        required: json['Hãy nhập ghi chú'],
                        maxlength: json['Tối đa 255 kí tự']
                    }
                }
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('admin.order.submit-cancel'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    order_id: id,
                    order_description: $('#order_description').val()
                },
                success: function (res) {
                    if (res.error == true) {
                        swal(res.message, '', "error");
                    } else {
                        swal.fire(json['Hủy thành công'], "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.reload();
                            }
                            if (result.value == true) {
                                window.location.reload();
                            }
                        });
                    }
                },
                error: function (res) {
                    swal(json['Hủy thất bại'], '', "error");
                }
            });
        });
    }
};
