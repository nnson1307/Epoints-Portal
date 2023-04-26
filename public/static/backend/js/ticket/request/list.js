var jsonLang = JSON.parse(localStorage.getItem('tranlate'))

var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
var Shift = {
    remove: function(obj, id) {

        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function(json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function() {
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function(result) {
                if (result.value) {
                    $.post(laroute.route('ticket.request.remove', { id: id }), function() {
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
    changeStatus: function(obj, id, action) {
        $.post(laroute.route('ticket.request.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function() {
        let request_name = $('#modalAdd [name="request_name"]');
        let description = $('#modalAdd [name="description"]');
        let hour = $('#modalAdd [name="hour"]');
        let day = $('#modalAdd [name="day"]');
        let level = $('#modalAdd [name="level"]');
        let ticket_issue_group_id = $('#modalAdd [name="ticket_issue_group_id"]');
        let hour_val = hour.val();
        let day_val = day.val();
        if (hour_val == '') {
            hour_val = 0;
        } else {
            hour_val = parseFloat(hour_val);
        }
        if (day_val == '') {
            day_val = 0;
        } else {
            day_val = parseFloat(day_val);
        }
        let process_time = hour_val + day_val * 24;
        let check = 1;

        if (validateFormCustom() == true) {
            $.ajax({
                url: laroute.route('ticket.request.add'),
                data: {
                    ticket_issue_group_id: ticket_issue_group_id.val(),
                    level: level.val(),
                    request_name: request_name.val(),
                    description: description.val(),
                    process_time: process_time,
                    isActived: check,
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 1) {
                        swal(
                            jsonLang['Thêm yêu cầu thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        clear();
                    } else {
                        swal(
                            jsonLang['Thêm yêu cầu thất bại'],
                            '',
                            'warning'
                        );
                    }
                }
            });
        }
        // });
    },
    addClose: function() {
        let request_name = $('#modalAdd [name="request_name"]');
        let description = $('#modalAdd [name="description"]');
        let hour = $('#modalAdd [name="hour"]');
        let day = $('#modalAdd [name="day"]');
        let level = $('#modalAdd [name="level"]');
        let ticket_issue_group_id = $('#modalAdd [name="ticket_issue_group_id"]');
        let hour_val = hour.val();
        let day_val = day.val();
        if (hour_val == '') {
            hour_val = 0;
        } else {
            hour_val = parseFloat(hour_val);
        }
        if (day_val == '') {
            day_val = 0;
        } else {
            day_val = parseFloat(day_val);
        }
        let process_time = hour_val + day_val * 24;
        let check = 1;
        if (validateFormCustom() == true) {
            $.ajax({
                url: laroute.route('ticket.request.add'),
                data: {
                    ticket_issue_group_id: ticket_issue_group_id.val(),
                    level: level.val(),
                    request_name: request_name.val(),
                    description: description.val(),
                    process_time: process_time,
                    isActived: check,
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 1) {
                        swal(
                            jsonLang['Thêm yêu cầu thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        $('#modalAdd').modal('hide');
                        clear();
                    } else {
                        $('#modalEdit').modal('hide');
                        swal(
                            jsonLang['Thêm yêu cầu thất bại'],
                            '',
                            'warning'
                        );
                        clear();
                    }
                }
            });
        }
        // });
    },
    edit: function(id) {
        $('.error-description-edit').text('');
        $('.error-request_name-edit').text('');
        $.ajax({
            url: laroute.route('ticket.request.edit'),
            data: {
                requestId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalEdit').modal('show');
                $('#request-id-hidden').val(data['ticket_issue_id']);
                $('#modalEdit [name="ticket_issue_group_id"]').val(data['ticket_issue_group_id']).change();
                $('#modalEdit [name="level"]').val(data['level']).change();
                $('#modalEdit [name="request_name"]').val(data['request_name']);
                $('#modalEdit [name="day"]').val(Math.floor(data['process_time'] / 24));
                $('#modalEdit [name="hour"]').val(data['process_time'] % 24);
                $('#modalEdit [name="request_name"]').val(data['request_name']);
                $('#modalEdit [name="description"]').val(data['description']);
                if (data['is_active'] == 1) {
                    $('#is-actived-edit').prop('checked', true);
                } else {
                    $('#is-actived-edit').prop('checked', false);
                }

            }
        })
    },
    submitEdit: function() {
        let id = $('#modalEdit [name="request-id-hidden"]');
        let request_name = $('#modalEdit [name="request_name"]');
        let description = $('#modalEdit [name="description"]');
        let hour = $('#modalEdit [name="hour"]');
        let day = $('#modalEdit [name="day"]');
        let level = $('#modalEdit [name="level"]');
        // let ticket_issue_group_id = $('#modalEdit [name="ticket_issue_group_id"]');
        let hour_val = hour.val();
        let day_val = day.val();
        if (hour_val == '') {
            hour_val = 0;
        } else {
            hour_val = parseFloat(hour_val);
        }
        if (day_val == '') {
            day_val = 0;
        } else {
            day_val = parseFloat(day_val);
        }
        let process_time = hour_val + day_val * 24;
        if (validateFormCustom('#modalEdit') == true) {
            $.ajax({
                url: laroute.route('ticket.request.submit-edit'),
                data: {
                    id: id.val(),
                    // ticket_issue_group_id: ticket_issue_group_id.val(),
                    level: level.val(),
                    request_name: request_name.val(),
                    description: description.val(),
                    process_time: process_time,
                    parameter: 0
                },
                method: "POST",
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 0) {
                        $('#modalEdit').modal('hide');
                        swal(
                            jsonLang['Cập nhật yêu cầu không thành công'],
                            '',
                            'warning'
                        );
                        $('#autotable').PioTable('refresh');
                        clear();
                    }
                    if (data.status == 1) {
                        $('.error-request_name').text('');
                        $('#modalEdit').modal('hide');
                        swal(
                            jsonLang['Cập nhật yêu cầu thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        clear();
                    }
                }
            });
        }
        // });
    },
    view: function(id) {
        $.ajax({
            url: laroute.route('ticket.request.edit'),
            data: {
                requestId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                $('#request-id-hidden').val(data['ticket_issue_group_id']);
                $('#request_name-view').val(data['request_name']);
                $('#description-view').val(data['description']);
                $('#modalView [name="day"]').val(Math.floor(data['process_time'] / 24));
                $('#modalView [name="hour"]').val(data['process_time'] % 24);
            }
        });
    },
    clear: function() {
        clear();
    },
    refresh: function() {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");
    },
    search: function() {
        $(".btn-search").trigger("click");
    }
};

function clear() {
    $('[name="request_name"]').val('');
    $('[name="description"]').val('');
    $('[name="day"]').val('');
    $('[name="hour"]').val('');
    $("[name='level']").val("").change();
    $('[name="ticket_issue_group_id"]').prop('selectedIndex', 0);
    $('#is-actived').val(1);
    $('.error-request_name').text('');
    $('.error-description').text('');
    $('.error-description').text('');
    $('.error-process_time').text('');
    $('.error-ticket_issue_group_id').text('');
    $('.error-level').text('');
}

$('#autotable').PioTable({
    baseUrl: laroute.route('ticket.request.list')
});

$('.m_selectpicker').selectpicker();
$('select[name="is_active"]').select2();

var arrRange = {};
arrRange[lang['Hôm nay']] = [moment(), moment()],
    arrRange[lang['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
    arrRange[lang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
    arrRange[lang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
    arrRange[lang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
    arrRange[lang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
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
        "applyLabel": lang["Đồng ý"],
        "cancelLabel": lang["Thoát"],
        "customRangeLabel": lang["Tùy chọn ngày"],
        daysOfWeek: [
            lang["CN"],
            lang["T2"],
            lang["T3"],
            lang["T4"],
            lang["T5"],
            lang["T6"],
            lang["T7"]
        ],
        "monthNames": [
            lang["Tháng 1 năm"],
            lang["Tháng 2 năm"],
            lang["Tháng 3 năm"],
            lang["Tháng 4 năm"],
            lang["Tháng 5 năm"],
            lang["Tháng 6 năm"],
            lang["Tháng 7 năm"],
            lang["Tháng 8 năm"],
            lang["Tháng 9 năm"],
            lang["Tháng 10 năm"],
            lang["Tháng 11 năm"],
            lang["Tháng 12 năm"]
        ],
        "firstDay": 1
    },
    ranges: arrRange
}).on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
});

function validateFormCustom(is_edit = '#modalAdd') {
    let number_field_required = 4;
    let request_name = $(is_edit + ' [name="request_name"]');
    let description = $(is_edit + ' [name="description"]');
    let hour = $(is_edit + ' [name="hour"]');
    let day = $(is_edit + ' [name="day"]');
    let level = $(is_edit + ' [name="level"]');
    let ticket_issue_group_id = $(is_edit + ' [name="ticket_issue_group_id"]');
    let hour_val = hour.val();
    let day_val = day.val();
    if (hour_val == '') {
        hour_val = 0;
    } else {
        hour_val = parseFloat(hour_val);
    }
    if (day_val == '') {
        day_val = 0;
    } else {
        day_val = parseFloat(day_val);
    }
    let process_time = hour_val + day_val * 24;
    let error_ticket_issue_group_id = $(is_edit + ' .error-ticket_issue_group_id');
    let error_level = $('.error-level');
    let error_description = $('.error-description');
    let error_process_time = $('.error-process_time');
    let error_request_name = $('.error-request_name');
    $(".err").css("color", "red");
    let pass_error = 0;
    if (ticket_issue_group_id.val() == "") {
        error_ticket_issue_group_id.text(jsonLang['Vui lòng chọn loại yêu cầu']);
        pass_error--;
    } else {
        error_ticket_issue_group_id.text('');
        pass_error++;
    }
    if (level.val() == "") {
        error_level.text(jsonLang['Vui lòng chọn cấp độ sự cố']);
        pass_error--;

        return false;
    } else {
        error_level.text('');
        pass_error++;
    }
    if (request_name.val() == "") {
        error_request_name.text(jsonLang['Vui lòng nhập tên yêu cầu']);
        pass_error--;

        return false;
    } else if (request_name.val().length > 255) {
        error_request_name.text(jsonLang['Tên yêu cầu không được quá 255 ký tự']);
        pass_error--;

        return false;
    } else {
        error_request_name.text('');
        pass_error++;
    }
    if (process_time == 0) {
        error_process_time.text(jsonLang['Vui lòng nhập thời gian xử lý']);
        pass_error--;

        return false;
    } else if (day_val < 0 || hour_val < 0) {
        error_process_time.text(jsonLang['Thời gian xử lý không được âm']);
        pass_error--;

        return false;
    } else if (day_val > 1000000) {
        error_process_time.text(jsonLang['Số ngày quá lớn']);
        pass_error--;

        return false;
    } else if (hour_val > 25) {
        error_process_time.text(jsonLang['Số giờ quá lớn']);
        pass_error--;

        return false;
    } else {

        var x = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for (let z = 0; z < 10; z++) {
            x += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        var d = new Date()
        var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
        var Shift = {
            remove: function(obj, id) {

                $(obj).closest('tr').addClass('m-table__row--danger');
                $.getJSON(laroute.route('translate'), function(json) {
                    swal({
                        title: json['Thông báo'],
                        text: json["Bạn có muốn xóa không?"],
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: json['Xóa'],
                        cancelButtonText: json['Hủy'],
                        onClose: function() {
                            $(obj).closest('tr').removeClass('m-table__row--danger');
                        }
                    }).then(function(result) {
                        if (result.value) {
                            $.post(laroute.route('ticket.request.remove', { id: id }), function() {
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
            changeStatus: function(obj, id, action) {
                $.post(laroute.route('ticket.request.change-status'), { id: id, action: action }, function(data) {
                    $('#autotable').PioTable('refresh');
                }, 'JSON');
            },
            add: function() {
                let request_name = $('#modalAdd [name="request_name"]');
                let description = $('#modalAdd [name="description"]');
                let hour = $('#modalAdd [name="hour"]');
                let day = $('#modalAdd [name="day"]');
                let level = $('#modalAdd [name="level"]');
                let ticket_issue_group_id = $('#modalAdd [name="ticket_issue_group_id"]');
                let hour_val = hour.val();
                let day_val = day.val();
                if (hour_val == '') {
                    hour_val = 0;
                } else {
                    hour_val = parseFloat(hour_val);
                }
                if (day_val == '') {
                    day_val = 0;
                } else {
                    day_val = parseFloat(day_val);
                }
                let process_time = hour_val + day_val * 24;
                let check = 1;

                console.log(validateFormCustom());

                if (validateFormCustom() == true) {
                    $.ajax({
                        url: laroute.route('ticket.request.add'),
                        data: {
                            ticket_issue_group_id: ticket_issue_group_id.val(),
                            level: level.val(),
                            request_name: request_name.val(),
                            description: description.val(),
                            process_time: process_time,
                            isActived: check,
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status == 1) {
                                swal(
                                    jsonLang['Thêm yêu cầu thành công'],
                                    '',
                                    'success'
                                );
                                $('#autotable').PioTable('refresh');
                                clear();
                            } else {
                                swal(
                                    jsonLang['Thêm yêu cầu thất bại'],
                                    '',
                                    'warning'
                                );
                            }
                        }
                    });
                }
                // });
            },
            addClose: function() {
                let request_name = $('#modalAdd [name="request_name"]');
                let description = $('#modalAdd [name="description"]');
                let hour = $('#modalAdd [name="hour"]');
                let day = $('#modalAdd [name="day"]');
                let level = $('#modalAdd [name="level"]');
                let ticket_issue_group_id = $('#modalAdd [name="ticket_issue_group_id"]');
                let hour_val = hour.val();
                let day_val = day.val();
                if (hour_val == '') {
                    hour_val = 0;
                } else {
                    hour_val = parseFloat(hour_val);
                }
                if (day_val == '') {
                    day_val = 0;
                } else {
                    day_val = parseFloat(day_val);
                }
                let process_time = hour_val + day_val * 24;
                let check = 1;
                if (validateFormCustom() == true) {
                    $.ajax({
                        url: laroute.route('ticket.request.add'),
                        data: {
                            ticket_issue_group_id: ticket_issue_group_id.val(),
                            level: level.val(),
                            request_name: request_name.val(),
                            description: description.val(),
                            process_time: process_time,
                            isActived: check,
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status == 1) {
                                swal(
                                    jsonLang['Thêm yêu cầu thành công'],
                                    '',
                                    'success'
                                );
                                $('#autotable').PioTable('refresh');
                                $('#modalAdd').modal('hide');
                                clear();
                            } else {
                                $('#modalEdit').modal('hide');
                                swal(
                                    jsonLang['Thêm yêu cầu thất bại'],
                                    '',
                                    'warning'
                                );
                                clear();
                            }
                        }
                    });
                }
                // });
            },
            edit: function(id) {
                $('.error-description-edit').text('');
                $('.error-request_name-edit').text('');
                $.ajax({
                    url: laroute.route('ticket.request.edit'),
                    data: {
                        requestId: id
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function(data) {
                        $('#modalEdit').modal('show');
                        $('#request-id-hidden').val(data['ticket_issue_id']);
                        $('#modalEdit [name="ticket_issue_group_id"]').val(data['ticket_issue_group_id']).change();
                        $('#modalEdit [name="level"]').val(data['level']).change();
                        $('#modalEdit [name="request_name"]').val(data['request_name']);
                        $('#modalEdit [name="day"]').val(Math.floor(data['process_time'] / 24));
                        $('#modalEdit [name="hour"]').val(data['process_time'] % 24);
                        $('#modalEdit [name="request_name"]').val(data['request_name']);
                        $('#modalEdit [name="description"]').val(data['description']);
                        if (data['is_active'] == 1) {
                            $('#is-actived-edit').prop('checked', true);
                        } else {
                            $('#is-actived-edit').prop('checked', false);
                        }

                    }
                })
            },
            submitEdit: function() {
                let id = $('#modalEdit [name="request-id-hidden"]');
                let request_name = $('#modalEdit [name="request_name"]');
                let description = $('#modalEdit [name="description"]');
                let hour = $('#modalEdit [name="hour"]');
                let day = $('#modalEdit [name="day"]');
                let level = $('#modalEdit [name="level"]');
                // let ticket_issue_group_id = $('#modalEdit [name="ticket_issue_group_id"]');
                let hour_val = hour.val();
                let day_val = day.val();
                if (hour_val == '') {
                    hour_val = 0;
                } else {
                    hour_val = parseFloat(hour_val);
                }
                if (day_val == '') {
                    day_val = 0;
                } else {
                    day_val = parseFloat(day_val);
                }
                let process_time = hour_val + day_val * 24;
                if (validateFormCustom('#modalEdit') == true) {
                    $.ajax({
                        url: laroute.route('ticket.request.submit-edit'),
                        data: {
                            id: id.val(),
                            // ticket_issue_group_id: ticket_issue_group_id.val(),
                            level: level.val(),
                            request_name: request_name.val(),
                            description: description.val(),
                            process_time: process_time,
                            parameter: 0
                        },
                        method: "POST",
                        dataType: 'JSON',
                        success: function(data) {
                            if (data.status == 0) {
                                $('#modalEdit').modal('hide');
                                swal(
                                    jsonLang['Cập nhật yêu cầu không thành công'],
                                    '',
                                    'warning'
                                );
                                $('#autotable').PioTable('refresh');
                                clear();
                            }
                            if (data.status == 1) {
                                $('.error-request_name').text('');
                                $('#modalEdit').modal('hide');
                                swal(
                                    jsonLang['Cập nhật yêu cầu thành công'],
                                    '',
                                    'success'
                                );
                                $('#autotable').PioTable('refresh');
                                clear();
                            }
                        }
                    });
                }
                // });
            },
            view: function(id) {
                $.ajax({
                    url: laroute.route('ticket.request.edit'),
                    data: {
                        requestId: id
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function(data) {
                        $('#modalView').modal('show');
                        $('#request-id-hidden').val(data['ticket_issue_group_id']);
                        $('#request_name-view').val(data['request_name']);
                        $('#description-view').val(data['description']);
                        $('#modalView [name="day"]').val(Math.floor(data['process_time'] / 24));
                        $('#modalView [name="hour"]').val(data['process_time'] % 24);
                    }
                });
            },
            clear: function() {
                clear();
            },
            refresh: function() {
                $('input[name="search_keyword"]').val('');
                $('.m_selectpicker').val('');
                $('.m_selectpicker').selectpicker('refresh');
                $(".btn-search").trigger("click");
            },
            search: function() {
                $(".btn-search").trigger("click");
            }
        };

        function clear() {
            $('[name="request_name"]').val('');
            $('[name="description"]').val('');
            $('[name="day"]').val('');
            $('[name="hour"]').val('');
            $("[name='level']").val("").change();
            $('[name="ticket_issue_group_id"]').prop('selectedIndex', 0);
            $('#is-actived').val(1);
            $('.error-request_name').text('');
            $('.error-description').text('');
            $('.error-description').text('');
            $('.error-process_time').text('');
            $('.error-ticket_issue_group_id').text('');
            $('.error-level').text('');
        }

        $('#autotable').PioTable({
            baseUrl: laroute.route('ticket.request.list')
        });

        $('.m_selectpicker').selectpicker();
        $('select[name="is_active"]').select2();

        var arrRange = {};
        arrRange[lang['Hôm nay']] = [moment(), moment()],
            arrRange[lang['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
            arrRange[lang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
            arrRange[lang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
            arrRange[lang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
            arrRange[lang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
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
                "applyLabel": lang["Đồng ý"],
                "cancelLabel": lang["Thoát"],
                "customRangeLabel": lang["Tùy chọn ngày"],
                daysOfWeek: [
                    lang["CN"],
                    lang["T2"],
                    lang["T3"],
                    lang["T4"],
                    lang["T5"],
                    lang["T6"],
                    lang["T7"]
                ],
                "monthNames": [
                    lang["Tháng 1 năm"],
                    lang["Tháng 2 năm"],
                    lang["Tháng 3 năm"],
                    lang["Tháng 4 năm"],
                    lang["Tháng 5 năm"],
                    lang["Tháng 6 năm"],
                    lang["Tháng 7 năm"],
                    lang["Tháng 8 năm"],
                    lang["Tháng 9 năm"],
                    lang["Tháng 10 năm"],
                    lang["Tháng 11 năm"],
                    lang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
        });

        function validateFormCustom(is_edit = '#modalAdd') {
            let number_field_required = 4;
            let request_name = $(is_edit + ' [name="request_name"]');
            let description = $(is_edit + ' [name="description"]');
            let hour = $(is_edit + ' [name="hour"]');
            let day = $(is_edit + ' [name="day"]');
            let level = $(is_edit + ' [name="level"]');
            let ticket_issue_group_id = $(is_edit + ' [name="ticket_issue_group_id"]');
            let hour_val = hour.val();
            let day_val = day.val();
            if (hour_val == '') {
                hour_val = 0;
            } else {
                hour_val = parseFloat(hour_val);
            }
            if (day_val == '') {
                day_val = 0;
            } else {
                day_val = parseFloat(day_val);
            }
            let process_time = hour_val + day_val * 24;
            let error_ticket_issue_group_id = $(is_edit + ' .error-ticket_issue_group_id');
            let error_level = $('.error-level');
            let error_description = $('.error-description');
            let error_process_time = $('.error-process_time');
            let error_request_name = $('.error-request_name');
            $(".err").css("color", "red");
            let pass_error = 0;
            if (ticket_issue_group_id.val() == "") {
                error_ticket_issue_group_id.text(jsonLang['Vui lòng chọn loại yêu cầu']);
                pass_error--;
            } else {
                error_ticket_issue_group_id.text('');
                pass_error++;
            }
            if (level.val() == "") {
                error_level.text(jsonLang['Vui lòng chọn cấp độ sự cố']);
                pass_error--;

                return false;
            } else {
                error_level.text('');
                pass_error++;
            }
            if (request_name.val() == "") {
                error_request_name.text(jsonLang['Vui lòng nhập tên yêu cầu']);
                pass_error--;

                return false;
            } else if (request_name.val().length > 255) {
                error_request_name.text(jsonLang['Tên yêu cầu không được quá 255 ký tự']);
                pass_error--;

                return false;
            } else {
                error_request_name.text('');
                pass_error++;
            }
            if (process_time == 0) {
                error_process_time.text(jsonLang['Vui lòng nhập thời gian xử lý']);
                pass_error--;

                return false;
            } else if (day_val < 0 || hour_val < 0) {
                error_process_time.text(jsonLang['Thời gian xử lý không được âm']);
                pass_error--;

                return false;
            } else if (day_val > 1000000) {
                error_process_time.text(jsonLang['Số ngày quá lớn']);
                pass_error--;

                return false;
            } else if (hour_val > 25) {
                error_process_time.text(jsonLang['Số giờ quá lớn']);
                pass_error--;

                return false;
            } else {
                error_process_time.text('');
                pass_error++;
            }
            //
            if (day_val != 0 && hour_val != 0) {
                if (!Number.isInteger(day_val) && !Number.isInteger(hour_val)) {
                    error_process_time.text(jsonLang['Ngày, giờ phải là số nguyên']);
                    pass_error--;

                    return false;
                }
            } else if (day_val != 0 && hour_val == 0) {
                if (!Number.isInteger(day_val)) {
                    error_process_time.text(jsonLang['Ngày phải là số nguyên']);
                    pass_error--;

                    return false;
                }
            }
                // else if (hour_val != 0 && day_val == 0) {
                //     console.log(hour_val,day_val)
                //     error_process_time.text('Giờ là số thập phân có 1 chữ số');
                //     pass_error--;
            // }
            else {
                error_process_time.text('');
                pass_error++;
            }
            //
            if (description.val().length > 255) {
                error_description.text(jsonLang['Mô tả không quá 255 ký tự']);
                pass_error--;

                return false;
            } else {
                error_description.text('');
            }

            if (pass_error >= number_field_required) {
                return true;
            }

            return false;
        }
        error_process_time.text('');
        pass_error++;
    }
    //
    if (day_val != 0 && hour_val != 0) {
        if (!Number.isInteger(day_val) && !Number.isInteger(hour_val)) {
            error_process_time.text(jsonLang['Ngày, giờ phải là số nguyên']);
            pass_error--;

            return false;
        }
    } else if (day_val != 0 && hour_val == 0) {
        if (!Number.isInteger(day_val)) {
            error_process_time.text(jsonLang['Ngày phải là số nguyên']);
            pass_error--;

            return false;
        }
    }
        // else if (hour_val != 0 && day_val == 0) {
        //     console.log(hour_val,day_val)
        //     error_process_time.text('Giờ là số thập phân có 1 chữ số');
        //     pass_error--;
    // }
    else {
        error_process_time.text('');
        pass_error++;
    }
    //
    if (description.val().length > 255) {
        error_description.text(jsonLang['Mô tả không quá 255 ký tự']);
        pass_error--;

        return false;
    } else {
        error_description.text('');
    }

    if (pass_error >= number_field_required) {
        return true;
    }

    return false;
}