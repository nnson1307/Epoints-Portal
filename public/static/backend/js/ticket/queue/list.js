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
                    $.post(laroute.route('ticket.queue.remove', { id: id }), function() {
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
        $.post(laroute.route('ticket.queue.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function() {
        let queue_name = $('#queue_name');
        let email = $('#email');
        let description = $('#description');
        let check = 0;
        let errorEmail = $('.error-email');
        let errorDescription = $('.error-description');
        let errorQueue_name = $('.error-queue_name');
        let department_id = $('#department_id');
        let errorDepartment_id = $('.error-department_id');
        $(".err").css("color", "red");
        // $.getJSON(laroute.route('translate'), function(json) {
        if (department_id.val() == "") {
            errorDepartment_id.text(lang['Vui lòng chọn phòng ban']);
        } else {
            errorDepartment_id.text('');
        }
        if (queue_name.val() == "") {
            errorQueue_name.text(lang['Vui lòng nhập tên queue']);
        } else if (queue_name.val().length >= 255) {
            errorQueue_name.text(lang['Tên queue không quá 255 ký tự']);
        } else {
            errorQueue_name.text('');
        }
        if (email.val() == "") {
            errorEmail.text(lang['Vui lòng nhập email']);
        } else if (email.val().length >= 255) {
            errorEmail.text(lang['Email không quá 255 ký tự']);
        } else {
            errorEmail.text('');
        }
        if (description.val().trim().length >= 255) {
            errorDescription.text(lang['Mô tả không quá 255 ký tự']);
        } else {
            errorDescription.text('');
        }
        if ($('#is_actived').is(':checked')) {
            check = 1;
        }
        if (queue_name.val() != "" && email.val() != "" && queue_name.val().length < 255 && email.val().length < 255 && description.val().trim().length < 255 && department_id.val() != "" && department_id.val() != "") {
            if (validateEmail(email.val()) == false) {
                $('.error-email').text(lang['Vui lòng nhập lại email']);
            } else {
                $.ajax({
                    url: laroute.route('ticket.queue.add'),
                    data: {
                        queue_name: queue_name.val(),
                        email: email.val(),
                        description: description.val(),
                        isActived: check,
                        department_id: department_id.val(),
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == -2) {
                            errorQueue_name.text(lang['Tên queue đã tồn tại']);
                        }
                        if (data.status == 1) {
                            errorQueue_name.text('');
                            swal(
                                lang['Thêm queue thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                        }
                        if (data.status == 0) {
                            errorEmail.text(lang['Email đã tồn tại']);
                        }
                    }
                });
            }
        }
        // });
    },
    addClose: function() {
        let queue_name = $('#queue_name');
        let email = $('#email');
        let description = $('#description');
        let check = 0;
        let errorEmail = $('.error-email');
        let errorDescription = $('.error-description');
        let errorQueue_name = $('.error-queue_name');
        let department_id = $('#department_id');
        let errorDepartment_id = $('.error-department_id');
        $(".err").css("color", "red");
        // $.getJSON(laroute.route('translate'), function(json) {
        if (department_id.val() == "") {
            errorDepartment_id.text(lang['Vui lòng chọn phòng ban']);
        } else {
            errorDepartment_id.text('');
        }
        if (queue_name.val() == "") {
            errorQueue_name.text(lang['Vui lòng nhập tên queue']);
        } else if (queue_name.val().length >= 255) {
            errorQueue_name.text(lang['Tên queue không quá 255 ký tự']);
        } else {
            errorQueue_name.text('');
        }
        if (email.val() == "") {
            errorEmail.text(lang['Vui lòng nhập email']);
        } else if (email.val().length >= 255) {
            errorEmail.text(lang['Email không quá 255 ký tự']);
        } else {
            errorEmail.text('');
        }
        if (description.val().trim().length >= 255) {
            errorDescription.text(lang['Mô tả không quá 255 ký tự']);
        } else {
            errorDescription.text('');
        }
        if ($('#is_actived').is(':checked')) {
            check = 1;
        }
        if (queue_name.val() != "" && email.val() != "" && queue_name.val().length < 255 && email.val().length < 255 && description.val().trim().length < 255 && department_id.val() != "") {
            if (validateEmail(email.val()) == false) {
                $('.error-email').text(lang['Vui lòng nhập email']);
            } else {
                $.ajax({
                    url: laroute.route('ticket.queue.add'),
                    data: {
                        queue_name: queue_name.val(),
                        email: email.val(),
                        description: description.val(),
                        department_id: department_id.val(),
                        isActived: check,
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == -2) {
                            errorQueue_name.text(lang['Tên queue đã tồn tại']);
                        }
                        if (data.status == 1) {
                            errorQueue_name.text('');
                            swal(
                                lang['Thêm queue thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                            $('#modalAdd').modal('hide');
                        }
                        if (data.status == 0) {
                            errorEmail.text(lang['Email đã tồn tại']);
                        }
                    }
                });
            }
        }
        // });
    },
    edit: function(id) {
        $('.error-email-edit').text('');
        $('.error-description-edit').text('');
        $('.error-queue_name-edit').text('');
        $.ajax({
            url: laroute.route('ticket.queue.edit'),
            data: {
                queueId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                console.log(data.ticket_queue_id);
                $('#modalEdit').modal('show');
                $('#queue-id-hidden').val(data['ticket_queue_id']);
                $('#queue_name-edit').val(data['queue_name']);
                $('#email-edit').val(data['email']);
                $('#department_id-edit').val(data['department_id']).change();
                $('#description-edit').val(data['description']);
                if (data['is_actived'] == 1) {
                    $('#is-actived-edit').prop('checked', true);
                } else {
                    $('#is-actived-edit').prop('checked', false);
                }

            }
        })
    },
    submitEdit: function() {
        let id = $('#queue-id-hidden');
        console.log(id);
        let queue_name = $('#queue_name-edit');
        let email = $('#email-edit');
        let description = $('#description-edit');
        let isActive = $('#is-actived-edit');
        let check = 0;
        let errorEmail = $('.error-email-edit');
        let errorDescription = $('.error-description-edit');
        let errorQueue_name = $('.error-queue_name-edit');
        let errorDepartment_id = $('.error-department_id');
        $(".err").css("color", "red");
        errorQueue_name.text('');
        errorEmail.text('');
        errorDescription.text('');
        let department_id = $('#department_id-edit');
        // $.getJSON(laroute.route('translate'), function(json) {
        if (department_id.val() == "") {
            errorDepartment_id.text(lang['Vui lòng chọn phòng ban']);
        } else {
            errorDepartment_id.text('');
        }
        if (isActive.is(':checked')) {
            check = 1;
        }
        if (queue_name.val() == "") {
            errorQueue_name.text(lang['Vui lòng nhập tên queue']);
        } else if (queue_name.val().length >= 255) {
            errorQueue_name.text(lang['Tên queue không quá 255 ký tự']);
        } else {
            errorQueue_name.text('');
        }
        if (email.val() == "") {
            errorEmail.text(lang['Vui lòng nhập email']);
        } else if (email.val().length >= 255) {
            errorEmail.text(lang['Email không quá 255 ký tự']);
        } else {
            errorEmail.text('');
        }
        if (description.val().trim().length >= 255) {
            errorDescription.text(lang['Mô tả không quá 255 ký tự']);
        } else {
            errorDescription.text('');
        }
        if (queue_name.val() != "" && email.val() != "" && queue_name.val().length < 255 && email.val().length < 255 && description.val().trim().length < 255 && department_id.val() != "") {
            if (validateEmail(email.val()) == false) {
                errorEmail.text(lang['Vui lòng nhập lại email']);
            } else {
                $.ajax({
                    url: laroute.route('ticket.queue.submit-edit'),
                    data: {
                        id: id.val(),
                        email: email.val(),
                        queue_name: queue_name.val(),
                        description: description.val(),
                        department_id: department_id.val(),
                        isActived: check,
                        parameter: 0
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status == 0) {
                            errorEmail.text(lang['Email đã tồn tại']);
                        }
                        if (data.status == 2) {
                            errorQueue_name.text(lang['Tên queue đã tồn tại']);
                        }
                        if (data.status == 1) {
                            $('.error-queue_name').text('');
                            $('#modalEdit').modal('hide');
                            swal(
                                lang['Cập nhật queue thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                        }
                    }
                });
            }
        }
        // });
    },
    view: function(id) {
        $.ajax({
            url: laroute.route('ticket.queue.edit'),
            data: {
                queueId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                // $('#queue-id-hidden').val(data['ticket_queue_id']);
                $('#queue_name-view').val(data['queue_name']);
                $('#department_id-view').val(data['department_id']).change();
                $('#email-view').val(data['email']);
                $('#description-view').val(data['description']);

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
    $('#queue_name').val('');
    $('#email').val('');
    $('#description').val('');
    $('#is-actived').val(1);
    $('.error-queue_name').text('');
    $('.error-email').text('');
    $('.error-description').text('');
}

$('#autotable').PioTable({
    baseUrl: laroute.route('ticket.queue.list')
});

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
$('.m_selectpicker').selectpicker();
$('select[name="is_actived"]').select2();

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