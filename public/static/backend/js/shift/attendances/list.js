var attendances = {

    showModalCheckin: function (time_working_staff_id, staff_name ,branch_id, branch_name, working_end_time, type) {

        $.ajax({
            url: '/shift/attendances/show-check-in',
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id : time_working_staff_id,
                staff_name : staff_name,
                branch_id : branch_id,
                branch_name : branch_name,
                working_end_time : working_end_time,
                type : type
            },
            success: function (res) {
                if (res.html != null) {
                    $('#show-modal-checkin').html(res.html);
                    $('#modalChecking').modal('show');
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

    checkin: function () {
        var shift_id = $('input[name="shift"]:checked').val();
        if (shift_id == null) {
            Swal.fire(
                'Thông Báo',
                'Chưa chọn ca làm việc',
                'error'
            )
        } else {
            $.ajax({
                url: '/shift/attendances/check-in',
                data: {
                    branch_id: $("#checkin_branch_id").val(),
                    time_working_staff_id: $("#time_working_staff_id").val(),
                    shift_id: shift_id,
                    working_time: $("#working_time").val()
                },
                method: "POST",
                success: function (data) {
                    if (data.status == 1) {
                        Swal.fire(
                            'Thông Báo',
                            'Checkin thành công',
                            'success'
                        )
                        $('#modalChecking').modal('hide');
                        $('.modal-backdrop').remove();
                        $('#show-modal-checkin').html('');
                    }
                }
            });
        }

    },
    checkout: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: '/shift/attendances/check-out',
                data: {
                    branch_id: $("#checkin_branch_id").val(),
                    time_working_staff_id: $("#time_working_staff_id").val(),
                    shift_id: $("#shift_id").val(),
                    working_end_time: $("#working_end_time").val(),
                    working_day : $("#working_day").val(),
                    checkout_time : $('#checkout_time').val()
                },
                method: "POST",
                success: function (data) {
                    if (data.status == 1) {
                        Swal.fire(
                            json['Thông báo'],
                            json['Checkout thành công'],
                            'success'
                        )
                        $('#modalChecking').modal('hide');
                        $('.modal-backdrop').remove();
                        $('#show-modal-checkin').html('');
                        $(".frmFilter").submit();
                    }
                }
            });
        });
    },
    approve: function (time_working_staff_id, type) {
        $.getJSON(laroute.route('translate'), function (json) {
            var textAlert = json['Bạn có muốn duyệt đi trễ không?'];
        if(type == 2){
            textAlert = json['Bạn có muốn duyệt về sớm không?'];
        }

        swal({
            title: json['Duyệt checkin / checkout'],
            text: textAlert,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: json['Đồng ý'],
            cancelButtonText: json['Hủy']
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('attendances.approve'),
                    data: {
                        time_working_staff_id: time_working_staff_id,
                        type : type
                    },
                    method: "POST",
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire(
                                json['Thông Báo'],
                                json['Phê duyệt thành công'],
                                'success'
                            )
                            $(".frmFilter").submit();
                        }else {
                            Swal.fire(
                                json['Thông Báo'],
                                json['Phê duyệt thất bại'],
                                'error'
                            )
                        }
                    }
                });
            }
        });
        });
    },
    getList: function (isValid) {
        $("#isValid").val(isValid);
        $(".frmFilter").submit();
    },
}
$('#autotable').PioTable({
    baseUrl: laroute.route('attendances.list')
});

$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json['Hôm nay']] = [moment(), moment()],
        arrRange[json['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
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
        },
        ranges: arrRange
    }).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
    });
});