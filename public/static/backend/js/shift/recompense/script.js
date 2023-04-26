var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var listRecompense = {
    _init: function () {
        $(document).ready(function () {
            $('#autotable').PioTable({
                baseUrl: laroute.route('shift.recompense.list')
            });
        });
    },
    changeStatus: function (obj, recompenseId) {
        var is_actived = 0;

        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('shift.recompense.change-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                recompense_id: recompenseId,
                is_actived: is_actived
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
    remove: function (recompenseId) {
        swal({
            title: jsonLang['Thông báo'],
            text: jsonLang["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang['Xóa'],
            cancelButtonText: jsonLang['Hủy'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('shift.recompense.destroy'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        recompense_id: recompenseId
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
    },
    showPopCreate: function () {
        $.ajax({
            url: laroute.route('shift.recompense.show-pop-create'),
            method: 'POST',
            dataType: 'JSON',
            data: {

            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-create').modal('show');
            }
        });
    },
    submitCreate: function () {
        var form = $('#form-create');

        form.validate({
            rules: {
                recompense_name: {
                    required: true,
                    maxlength: 190
                },
            },
            messages: {
                recompense_name: {
                    required: jsonLang['Hãy nhập tên nội dung'],
                    maxlength: jsonLang['Tên nội dung tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('shift.recompense.store'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                type: $('input[name=type]:checked').val(),
                recompense_name: $('#recompense_name').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-create').modal('hide');

                            $('#autotable').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-create').modal('hide');

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
                swal(jsonLang['Thêm thất bại'], mess_error, "error");
            }
        });
    },
    showPopEdit: function (recompenseId) {
        $.ajax({
            url: laroute.route('shift.recompense.show-pop-edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                recompense_id: recompenseId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-edit').modal('show');
            }
        });
    },
    submitEdit: function (recompenseId) {
        var form = $('#form-edit');

        form.validate({
            rules: {
                recompense_name: {
                    required: true,
                    maxlength: 190
                },
            },
            messages: {
                recompense_name: {
                    required: jsonLang['Hãy nhập tên nội dung'],
                    maxlength: jsonLang['Tên nội dung tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        var isActive = 0;

        if ($('#is_actived').is(':checked')) {
            isActive = 1;
        }

        $.ajax({
            url: laroute.route('shift.recompense.update'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                recompense_id: recompenseId,
                type: $('input[name=type]:checked').val(),
                recompense_name: $('#recompense_name').val(),
                is_actived: isActive
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-edit').modal('hide');

                            $('#autotable').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-edit').modal('hide');

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
                swal(jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    }
};