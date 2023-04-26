var allowance = {

    showModalAdd: function () {
       
        $.ajax({
            url: laroute.route('staff-salary-allowance.modal-add'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.html != null) {
                    $('#modal-allowance-add').html(res.html);
                    $('#modalAllowanceAdd').modal('show');
                } else {
                    Swal.fire(
                        'Thông Báo',
                        'Không có lịch làm việc trong thời gian này',
                        'error'
                    )
                }

            }
        });
    },

    showModalEdit: function ($id) {
        $.ajax({
            url: laroute.route('staff-salary-allowance.modal-edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_holiday_id: $id
            },
            success: function (res) {
                if (res.html != null) {
                    $('#modal-allowance-edit').html(res.html);
                    $('#modalAllowanceEdit').modal('show');
                } else {
                    Swal.fire(
                        'Thông Báo',
                        'Không có lịch làm việc trong thời gian này',
                        'error'
                    )
                }

            }
        });
    },

    add: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = false;
            var title = $('#salary_allowance_name').val();
            if (title == '') {
                $('.error-salary-allowance-name').css("color", "red");
                $('.error-salary-allowance-name').text(json['Hãy nhập tên phụ cấp']);
                continute = false;
            } else {
                $('.error-salary-allowance-name').text('');
                continute = true;
            }
            if (!continute) {
                return;
            } else {
                $.ajax({
                    url: laroute.route('staff-salary-allowance.add'),
                    data: {
                        salary_allowance_name: $("#salary_allowance_name").val(),
                    },
                    method: "POST",
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire(
                                json['Thông báo'],
                                json['Thêm mới thành công'],
                                'success'
                            )
                            $('#modal-allowance-add').html('');
                            $('#modalAllowanceAdd').modal('hide');
                            $('.modal-backdrop').remove();
                            location.reload();
                        }
                    }
                });
            }

        });

    },

    edit: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = false;
            var title = $('#salary_allowance_name').val();
            if (title == '') {
                $('.error-salary-allowance-name').css("color", "red");
                $('.error-salary-allowance-name').text(json['Hãy nhập tên phụ cấp']);
                continute = false;
            } else {
                $('.error-salary-allowance-name').text('');
                continute = true;
            }
            if (!continute) {
                return;
            } else {
                $.ajax({
                    url: laroute.route('staff-salary-allowance.edit'),
                    data: {
                        salary_allowance_id: $("#salary_allowance_id").val(),
                        salary_allowance_name: $("#salary_allowance_name").val(),
                    },
                    method: "POST",
                    success: function (data) {
                        if (data.status == 1) {
                          
                            swal({
                                title:  json['Chỉnh sửa thành công'],
                                text: 'Redirecting...',
                                type: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                            })
                            .then(() => {
                                window.location.reload(); 
                            });
                            // $('#modalAllowanceEdit').modal('hide');
                            // $('.modal-backdrop').remove();
                            // $('#modal-allowance-edit').html('');
                            // location.reload();
                        }
                    }
                });
            }

        });
    },

    getList: function () {
        $(".frmFilter").submit();
    },
}
$('#autotable').PioTable({
    baseUrl: laroute.route('staff-salary-allowance.list')
});

$.getJSON(laroute.route('translate'), function (json) {
    $(".daterange-picker").daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        buttonClasses: "m-btn btn",
        applyClass: "btn-primary",
        cancelClass: "btn-danger",
        maxDate: moment().endOf("day"),
        startDate: moment().startOf("day"),
        endDate: moment().add(1, 'days'),
        locale: {
            format: 'DD/MM/YYYY',
            "applyLabel": json["Đồng ý"],
            "cancelLabel": json["Thoát"],
            "customRangeLabel": json["Tùy chọn ngày"],
            daysOfWeek: [
                json["CN"],
                json["T2"],
                json["T3"],
                json["T4"],
                json["T5"],
                json["T6"],
                json["T7"]
            ],
            "monthNames": [
                json["Tháng 1 năm"],
                json["Tháng 2 năm"],
                json["Tháng 3 năm"],
                json["Tháng 4 năm"],
                json["Tháng 5 năm"],
                json["Tháng 6 năm"],
                json["Tháng 7 năm"],
                json["Tháng 8 năm"],
                json["Tháng 9 năm"],
                json["Tháng 10 năm"],
                json["Tháng 11 năm"],
                json["Tháng 12 năm"]
            ],
            "firstDay": 1
        }
    }).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
    });
});
function parseDate(str) {
    var mdy = str.split('/');
    return new Date(mdy[2], mdy[1], mdy[0]);
}