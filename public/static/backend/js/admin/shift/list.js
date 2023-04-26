var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date();
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
var Shift = {
        remove: function (obj, id) {

            $(obj).closest('tr').addClass('m-table__row--danger');
            $.getJSON(laroute.route('translate'), function (json) {
                swal({
                    title: json['Thông báo'],
                    text: json["Bạn có muốn xóa không?"],
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: json['Xóa'],
                    cancelButtonText: json['Hủy'],
                    onClose: function () {
                        $(obj).closest('tr').removeClass('m-table__row--danger');
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.post(laroute.route('admin.shift.remove', {id: id}), function () {
                            swal(
                                json['Xóa thành công.'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                        });
                    }
                });
            });
        },
        changeStatus: function (obj, id, action) {
            $.post(laroute.route('admin.shift.change-status'), {id: id, action: action}, function (data) {
                $('#autotable').PioTable('refresh');
            }, 'JSON');
        },
        add: function () {
            let shiftCode = $('#shift-code');
            let startTime = $('#start-time');
            let endTime = $('#end-time');
            let check = 0;
            let errorStartTime = $('.error-start-time');
            let errorEndTime = $('.error-end-time');
            let errorCode = $('.error-shift-code');
            $(".err").css("color", "red");
            $.getJSON(laroute.route('translate'), function (json) {
            if (shiftCode.val() == "") {
                errorCode.text(json['Vui lòng nhập mã ca']);
            } else {
                errorCode.text('');
            }
            if (startTime.val() == "") {
                errorStartTime.text(json['Vui lòng nhập thời gian bắt đầu']);
            } else {
                errorStartTime.text('');
            }
            if (endTime.val() == "") {
                errorEndTime.text(json['Vui lòng nhập thời gian kết thúc']);
            } else {
                errorEndTime.text('');
            }
            if ($('#is_actived').is(':checked')) {
                check = 1;
            }
            if (shiftCode.val() != "" && startTime.val() != "" && endTime.val() != "") {
                if (testTime(startTime.val(), endTime.val()) == false) {
                    $('.error-end-time').text('Vui lòng nhập lại thời gian');
                } else {
                    $.ajax({
                        url: laroute.route('admin.shift.add'),
                        data: {
                            shiftCode: shiftCode.val(),
                            startTime: startTime.val(),
                            endTime: endTime.val(),
                            isActived: check,
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            if (data.status == 1) {
                                errorCode.text('');
                                swal(
                                    json['Thêm ca thành công'],
                                    '',
                                    'success'
                                );
                                $('#autotable').PioTable('refresh');
                                clear();
                            } else {
                                errorEndTime.text(json['Ca đã tồn tại']);
                                alert('sdfsdf')
                            }
                        }
                    });
                }
            }
        });
        },
        addClose: function () {
            let shiftCode = $('#shift-code');
            let startTime = $('#start-time');
            let endTime = $('#end-time');
            let check = 0;
            let errorStartTime = $('.error-start-time');
            let errorEndTime = $('.error-end-time');
            let errorCode = $('.error-shift-code');
            $(".err").css("color", "red");
            $.getJSON(laroute.route('translate'), function (json) {
                if (shiftCode.val() == "") {
                    errorCode.text(json['Vui lòng nhập mã ca']);
                } else {
                    errorCode.text('');
                }
                if (startTime.val() == "") {
                    errorStartTime.text(json['Vui lòng nhập thời gian bắt đầu']);
                } else {
                    errorStartTime.text('');
                }
                if (endTime.val() == "") {
                    errorEndTime.text(json['Vui lòng nhập thời gian kết thúc']);
                } else {
                    errorEndTime.text('');
                }
                if ($('#is_actived').is(':checked')) {
                    check = 1;
                }
                if (shiftCode.val() != "" && startTime.val() != "" && endTime.val() != "") {
                    if (testTime(startTime.val(), endTime.val()) == false) {
                        $('.error-end-time').text(json['Vui lòng nhập lại thời gian']);
                    } else {
                        $.ajax({
                            url: laroute.route('admin.shift.add'),
                            data: {
                                shiftCode: shiftCode.val(),
                                startTime: startTime.val(),
                                endTime: endTime.val(),
                                isActived: check,
                            },
                            method: "POST",
                            dataType: "JSON",
                            success: function (data) {
                                if (data.status == 1) {
                                    errorCode.text('');
                                    swal(
                                        json['Thêm ca thành công'],
                                        '',
                                        'success'
                                    );
                                    $('#autotable').PioTable('refresh');
                                    clear();
                                    $('#modalAdd').modal('hide');
                                } else {
                                    errorEndTime.text(json['Ca đã tồn tại']);
                                }
                            }
                        });
                    }
                }
            });
        },
        edit: function (id) {
            $('.error-start-time-edit').text('');
            $('.error-end-time-edit').text('');
            $('.error-shift-code-edit').text('');
            $.ajax({
                url: laroute.route('admin.shift.edit'),
                data: {
                    shiftId: id
                },
                method: "POST",
                dataType: 'JSON',
                success: function (data) {
                    $('#modalEdit').modal('show');
                    $('#shift-id-hidden').val(data['shift_id']);
                    $('#start-time-edit').val(data['start_time']);
                    $('#end-time-edit').val(data['end_time']);
                    if (data['is_actived'] == 1) {
                        $('#is-actived-edit').prop('checked', true);
                    } else {
                        $('#is-actived-edit').prop('checked', false);
                    }

                }
            })
        },
        submitEdit: function () {
            let id = $('#shift-id-hidden');
            let shiftCode = $('#shift-code-edit');
            let startTime = $('#start-time-edit');
            let endTimes = $('#end-time-edit');
            let isActive = $('#is-actived-edit');
            let check = 0;
            let errorStartTime = $('.error-start-time-edit');
            let errorEndTime = $('.error-end-time-edit');
            let errorCode = $('.error-shift-code-edit');
            $(".err").css("color", "red");
            errorCode.text('');
            errorStartTime.text('');
            errorEndTime.text('');
            $.getJSON(laroute.route('translate'), function (json) {
                if (isActive.is(':checked')) {
                    check = 1;
                }
                if (shiftCode.val() == "") {
                    errorCode.text(json['Vui lòng nhập mã ca']);
                } else {
                    errorCode.text('');
                }
                if (startTime.val() == "") {
                    errorStartTime.text(json['Vui lòng nhập thời gian bắt đầu']);
                } else {
                    errorStartTime.text('');
                }
                if (endTimes.val() == "") {
                    errorEndTime.text(json['Vui lòng nhập thời gian kết thúc']);
                } else {
                    errorEndTime.text('');
                }
                if (shiftCode.val() != "" && startTime.val() != "" && endTimes.val() != "") {
                    if (testTime(startTime.val(), endTimes.val()) == false) {
                        errorEndTime.text(json['Vui lòng nhập lại thời gian']);
                    } else {
                        $.ajax({
                            url: laroute.route('admin.shift.submit-edit'),
                            data: {
                                id: id.val(),
                                startTime: startTime.val(),
                                endTimes: endTimes.val(),
                                isActived: check,
                                parameter: 0
                            },
                            method: "POST",
                            dataType: 'JSON',
                            success: function (data) {
                                if (data.status == 0) {
                                    errorEndTime.text(json['Ca làm việc đã tồn tại']);
                                }
                                if (data.status == 1) {
                                    $('.error-shift-code').text('');
                                    $('#modalEdit').modal('hide');
                                    swal(
                                        json['Cập nhật ca thành công'],
                                        '',
                                        'success'
                                    );
                                    $('#autotable').PioTable('refresh');
                                    clear();
                                } else if (data.status == 2) {
                                    swal({
                                        title: json['Ca làm việc đã tồn tại'],
                                        text: json["Bạn có muốn kích hoạt lại không?"],
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: json['Có'],
                                        cancelButtonText: json['Không'],
                                    }).then(function (willDelete) {
                                        if (willDelete.value == true) {
                                            $.ajax({
                                                url: laroute.route('admin.shift.submit-edit'),
                                                data: {
                                                    id: id.val(),
                                                    startTime: startTime.val(),
                                                    endTimes: endTimes.val(),
                                                    isActived: check,
                                                    parameter: 1
                                                },
                                                method: "POST",
                                                dataType: 'JSON',
                                                success: function (data) {
                                                    if (data.status = 3) {
                                                        swal(
                                                            json['Kích hoạt ca làm việc thành công'],
                                                            '',
                                                            'success'
                                                        );
                                                        $('#autotable').PioTable('refresh');
                                                        $('#modalEdit').modal('hide');
                                                    }
                                                }
                                            });
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            });
        },
        clear: function () {
            clear();
        },
        refresh: function () {
            $('input[name="search_keyword"]').val('');
            $('.m_selectpicker').val('');
            $('.m_selectpicker').selectpicker('refresh');
            $(".btn-search").trigger("click");
        },
        search: function () {
            $(".btn-search").trigger("click");
        }
    }
;

function clear() {
    $('#shift-code').val(code);
    $('#start-time').val('');
    $('#end-time').val('');
    $('#is-actived').val(1);
    $('.error-shift-code').text('');
    $('.error-start-time').text('');
    $('.error-end-time').text('');
}

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.shift.list')
});
$(document).ready(function () {
    // $('#start-time').timepicker({
    //     timeFormat: 'HH:mm',
    // });
    $("#start-time").timepicker({
        minuteStep: 15,
        defaultTime: "12:00:00",
        showMeridian: !1,
        snapToStep: !0,
    });
    $('#end-time').timepicker({
        minuteStep: 15,
        defaultTime: "12:00:00",
        showMeridian: !1,
        snapToStep: !0,
    });
    $('#start-time-edit').timepicker({
        minuteStep: 15,
        showMeridian: !1,
        snapToStep: !0,
    });
    $('#end-time-edit').timepicker({
        minuteStep: 15,
        showMeridian: !1,
        snapToStep: !0,
    });
});

function testTime(time1, time2) {
    let flag = true;
    var timeA = new Date();
    timeA.setHours(time1.split(":")[0], time1.split(":")[1]);
    var timeB = new Date();
    timeB.setHours(time2.split(":")[0], time2.split(":")[1]);
    if (timeA >= timeB) {
        flag = false;
    }
    return flag;
}

$('.m_selectpicker').selectpicker();
$('select[name="is_actived"]').select2();