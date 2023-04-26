var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var listTemplate = {
    _init: function () {
        $(document).ready(function () {
            $('#autotable').PioTable({
                baseUrl: laroute.route('staff-salary.template.list')
            });
        });
    },
    changeStatus: function (obj, templateId) {
        var is_actived = 0;

        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('staff-salary.template.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_salary_template_id: templateId,
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
    remove: function (templateId) {
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
                    url: laroute.route('staff-salary.template.destroy'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        staff_salary_template_id: templateId
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
};