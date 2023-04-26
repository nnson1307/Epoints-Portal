var listCompany = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('team.company.list')
        });
    },
    remove:function (companyId) {
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
                        url: laroute.route('team.company.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            company_id: companyId
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
    },
    changeStatus(companyId, obj) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('team.company.change-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                company_id: companyId,
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
    }
};

var create = {
    save: function () {
        var form = $('#form-register');

        form.validate({
            rules: {
                company_name: {
                    required: true,
                    maxlength: 190
                }

            },
            messages: {
                company_name: {
                    required: 'Hãy nhập tên công ty',
                    maxlength: 'Tên công ty tối đa 190 kí tự'
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('team.company.store'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                company_name: $('#company_name').val(),
                description: $('#description').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.href = laroute.route('team.company');
                        }
                        if (result.value == true) {
                            window.location.href = laroute.route('team.company');
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
                swal('Thêm thất bại', mess_error, "error");
            }
        });
    }
};

var edit = {
    save: function (companyId) {
        var form = $('#form-edit');

        form.validate({
            rules: {
                company_name: {
                    required: true,
                    maxlength: 190
                }

            },
            messages: {
                company_name: {
                    required: 'Hãy nhập tên công ty',
                    maxlength: 'Tên công ty tối đa 190 kí tự'
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        var is_actived = 0;
        if ($('#is_actived').is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('team.company.update'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                company_id: companyId,
                company_name: $('#company_name').val(),
                is_actived: is_actived,
                description: $('#description').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.href = laroute.route('team.company');
                        }
                        if (result.value == true) {
                            window.location.href = laroute.route('team.company');
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
                swal('Thêm thất bại', mess_error, "error");
            }
        });
    }
};