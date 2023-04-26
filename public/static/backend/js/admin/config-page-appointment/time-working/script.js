$(document).ready(function () {
    $('.start_time').select2({
        placeholder: 'Chọn giờ'
    });
    $('.end_time').select2({
        placeholder: 'Chọn giờ'
    });
});
var time_working = {
    change_status: function (obj, id) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('admin.config-page-appointment.change-status-time'),
            method: "POST",
            data: {
                id: id,
                is_actived:is_actived
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable-time-working').PioTable('refresh');
        });
    },
    submit_edit: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var check = true;
            $.each($('#table-time tr input[name="id_time"]').parentsUntil("tbody"), function () {
                var $start_time = $(this).closest("tr").find('.start_time').val();
                var $end_time = $(this).closest("tr").find('.end_time').val();

                if ($start_time == "" || $end_time == "") {
                    $(this).closest("tr").find('.error_time').text(json['Hãy chọn giờ làm việc']);
                    check = false;
                    return false;
                } else {
                    // check = true;
                    $(this).closest("tr").find('.error_time').text('');
                    var start = new Date();
                    var end = new Date();
                    start.setHours($start_time.split(":")[0], $start_time.split(":")[1], 0);
                    end.setHours($end_time.split(":")[0], $end_time.split(":")[1], 0);
                    if (start < end) {
                        $(this).closest("tr").find('.error_time').text('');
                        check = true;
                    } else {
                        $(this).closest("tr").find('.error_time').text(json['Giờ làm việc không hợp lệ']);
                        check = false;
                        return false;
                    }
                }

            });
            if (check == true) {
                var id = new Array();
                $('.id_time').each(function () {
                    id.push($(this).val());
                });
                var start_time = new Array();
                $('.start_time').each(function () {
                    start_time.push($(this).val());
                });
                var end_time = new Array();
                $('.end_time').each(function () {
                    end_time.push($(this).val());
                });

                $.ajax({
                    url: laroute.route('admin.config-page-appointment.submit-edit-time'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id,
                        start_time: start_time,
                        end_time: end_time
                    },
                    success: function (res) {
                        if (res.success == 1) {
                            swal(json["Cập nhật thời gian làm việc thành công"], "", "success");
                            $('#autotable-time-working').PioTable('refresh');
                        }
                    }
                });
            }
        });
    }
};
$('#autotable-time-working').PioTable({
    baseUrl: laroute.route('admin.config-page-appointment.list-time')
});