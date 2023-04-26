var listCategory = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('customer-lead.pipeline-category.list')
        });
    },
    changeStatus:function (categoryId, obj) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('customer-lead.pipeline-category.change-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                pipeline_category_id: categoryId,
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
    remove:function (categoryId) {
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
                        url: laroute.route('customer-lead.pipeline-category.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            pipeline_category_id: categoryId
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
    _init:function () {

    },
    save:function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    pipeline_category_name: {
                        required: true,
                        maxlength: 250
                    },
                },
                messages: {
                    pipeline_category_name: {
                        required: json['Hãy nhập tên danh mục pipeline'],
                        maxlength: json['Tên danh mục pipeline tối đa 250 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('customer-lead.pipeline-category.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    pipeline_category_name: $('#pipeline_category_name').val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('customer-lead.pipeline-category');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('customer-lead.pipeline-category');
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
    save:function (categoryId) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    pipeline_category_name: {
                        required: true,
                        maxlength: 250
                    },
                },
                messages: {
                    pipeline_category_name: {
                        required: json['Hãy nhập tên danh mục pipeline'],
                        maxlength: json['Tên danh mục pipeline tối đa 250 kí tự']
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
                url: laroute.route('customer-lead.pipeline-category.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    pipeline_category_name: $('#pipeline_category_name').val(),
                    is_actived: is_actived,
                    pipeline_category_id: categoryId
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('customer-lead.pipeline-category');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('customer-lead.pipeline-category');
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