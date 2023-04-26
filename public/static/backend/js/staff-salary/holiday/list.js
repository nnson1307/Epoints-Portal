var holiday = {

    showModalAdd: function () {
        // alert(laroute.route('attendances.modal-checkin'));
        $.ajax({
            url: laroute.route('holiday.modal-add'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.html != null) {
                    $('#modal-holiday-add').html(res.html);
                    $('#modalHolidayAdd').modal('show');
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
            url: laroute.route('holiday.modal-edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_holiday_id: $id
            },
            success: function (res) {
                if (res.html != null) {
                    $('#modal-holiday-edit').html(res.html);
                    $('#modalHolidayEdit').modal('show');
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
            var continute = true;
            $('.error-staff-holiday-title').text('');
            $('.error-staff-holiday-start-date').text('');
            $('.error-staff-holiday-end-date').text('');
            var title = $('#staff_holiday_title').val();
            var startDate = $('#staff_holiday_start_date').val();
            var endDate = $('#staff_holiday_end_date').val();          
            if (title == '') {
                $('.error-staff-holiday-title').css("color", "red");
                $('.error-staff-holiday-title').text(json['Hãy nhập tên ngày nghĩ']);
                continute = false;
            } 
            if (startDate == '') {
                $('.error-staff-holiday-start-date').css("color", "red");
                $('.error-staff-holiday-start-date').text(json['Hãy chọn ngày bắt đầu']);
                continute = false;
            } 
            if (endDate == '') {
                $('.error-staff-holiday-end-date').css("color", "red");
                $('.error-staff-holiday-end-date').text(json['Hãy chọn ngày kết thúc']);
                continute = false;
            } 
            if (startDate != '' && endDate != '') {
                if (parseDate(startDate).getTime() > parseDate(endDate).getTime()) {
                    $('.error-staff-holiday-start-date').css("color", "red");
                    $('.error-staff-holiday-start-date').text(json['Ngày bắt đầu phải lớn hơn ngày kết thúc']);
                    continute = false;
                }
            }
            if (!continute) {
                return;
            } else {
                $.ajax({
                    url: laroute.route('holiday.add'),
                    data: {
                        staff_holiday_title: $("#staff_holiday_title").val(),
                        staff_holiday_start_date: $("#staff_holiday_start_date").val(),
                        staff_holiday_end_date: $("#staff_holiday_end_date").val(),
                        staff_holiday_number: 1
                    },
                    method: "POST",
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire(
                                json['Thông Báo'],
                                json['Thêm mới thành công'],
                                'success'
                            )
                            $('#modalHolidayAdd').modal('hide');
                            $('.modal-backdrop').remove();
                            $('#modal-holiday-add').html('');
                            holiday.getList();
                        }
                    }
                });
            }

        });

    },

    edit: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;
            $('.error-staff-holiday-title').text('');
            $('.error-staff-holiday-start-date').text('');
            $('.error-staff-holiday-end-date').text('');
            var title = $('#staff_holiday_title').val();
            var startDate = $('#staff_holiday_start_date').val();
            var endDate = $('#staff_holiday_end_date').val();
            if (title == '') {
                $('.error-staff-holiday-title').css("color", "red");
                $('.error-staff-holiday-title').text(json['Hãy nhập tên ngày nghĩ']);
                continute = false;
            } 
            if (startDate == '') {
                $('.error-staff-holiday-start-date').css("color", "red");
                $('.error-staff-holiday-start-date').text(json['Hãy chọn ngày bắt đầu']);
                continute = false;
            } 
            if (endDate == '') {
                $('.error-staff-holiday-end-date').css("color", "red");
                $('.error-staff-holiday-end-date').text(json['Hãy chọn ngày kết thúc']);
                continute = false;
            } 
            if (startDate != '' && endDate != '') {
                if (parseDate(startDate).getTime() > parseDate(endDate).getTime()) {
                    $('.error-staff-holiday-start-date').css("color", "red");
                    $('.error-staff-holiday-start-date').text(json['Ngày bắt đầu phải lớn hơn ngày kết thúc']);
                    continute = false;
                }
            }
            if (!continute) {
                return;
            } else {
                $.ajax({
                    url: laroute.route('holiday.edit'),
                    data: {
                        staff_holiday_id: $("#staff_holiday_id").val(),
                        staff_holiday_title: $("#staff_holiday_title").val(),
                        staff_holiday_start_date: $("#staff_holiday_start_date").val(),
                        staff_holiday_end_date: $("#staff_holiday_end_date").val()
                    },
                    method: "POST",
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire(
                                json['Thông báo'],
                                json['Chỉnh sửa thành công'],
                                'success'
                            )
                            $('#modalHolidayEdit').modal('hide');
                            $('.modal-backdrop').remove();
                            $('#modal-holiday-edit').html('');
                            holiday.getList();
                        }
                    }
                });
            }

        });
    },

    delete: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Xoá ngày lễ'],
                text: json["Bạn có muốn xoá không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Đồng ý'],
                cancelButtonText: json['Hủy']
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('holiday.delete'),
                        data: {
                            staff_holiday_id: id
                        },
                        method: "POST",
                        success: function (data) {
                            if (data.status == 1) {
                                Swal.fire(
                                    json['Thông báo'],
                                    json['Xoá ngày lễ thành công'],
                                    'success'
                                )
                                holiday.getList();
                            }
                        }
                    });
                }
            });
        });
    },

    getList: function () {
        $(".frmFilter").submit();
    },
}
$('#autotable').PioTable({
    baseUrl: laroute.route('holiday.list')
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