
var create = {
    save: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');

            form.validate({
                rules: {
                    tag_name: {
                        required: true,
                        maxlength: 190
                    },
                },
                messages: {
                    tag_name: {
                        required: json['Hãy nhập tên tag'],
                        maxlength: json['Tên tag tối đa 190 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('admin.product-tag.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    tag_name: $('#tag_name').val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('admin.product-tag');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('admin.product-tag');
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
    save : function (tag_id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');
            form.validate({
                rules: {
                    tag_name: {
                        required: true,
                        maxlength: 190
                    },
                },
                messages: {
                    tag_name: {
                        required: json['Hãy nhập tên tag'],
                        maxlength: json['Tên tag tối đa 190 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('admin.product-tag.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    product_tag_id: tag_id,
                    tag_name: $('#tag_name').val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('admin.product-tag');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('admin.product-tag');
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
            baseUrl: laroute.route('admin.product-tag.list')
        });
    },

    remove: function (tag_id) {
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
                        url: laroute.route('admin.product-tag.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            product_tag_id: tag_id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location = laroute.route('admin.product-tag');
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