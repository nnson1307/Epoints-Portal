var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();

var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

$('[name="ticket_queue_id"]').select2({
    placeholder: jsonLang['Chọn queue']
});

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
                    $.post(laroute.route('ticket.queue_staff.remove', { id: id }), function() {
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
        $.post(laroute.route('ticket.queue_staff.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function() {
        $('#form-add').validate({
            rules: {
                staff: {
                    required: true,
                    valueNotEquals: ""
                },
                ticket_queue_id: {
                    required: true,
                    valueNotEquals: ""
                },
                ticket_role_queue_id: {
                    required: true,
                    valueNotEquals: ""
                },
            },
            messages: {
                staff: {
                    required: lang['Vui lòng chọn nhân viên xử lý'],
                },
                ticket_queue_id: {
                    required: lang['Vui lòng chọn queue'],
                },
                ticket_role_queue_id: {
                    required: lang['Vui lòng chọn vai trò trên queue'],
                },
            },
        });

        if (!$('#form-add').valid()) {
            return false;
        }
        $.ajax({
            url: laroute.route('ticket.queue_staff.add'),
            data: {
                staff_id: $('#form-add [name="staff"]').val(),
                ticket_queue_id: $('#form-add [name="ticket_queue_id"]').val(),
                ticket_role_queue_id: $('#form-add [name="ticket_role_queue_id"]').val(),
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status == 1) {
                    swal(
                        lang['Phân công nhân viên thành công'],
                        '',
                        'success'
                    );
                    $('#autotable').PioTable('refresh');
                    clear();
                } else {
                    swal(
                        lang['Phân công nhân viên không thành công'],
                        '',
                        'warning'
                    );
                    $('#autotable').PioTable('refresh');
                }
            }
        });
    },
    addClose: function() {
        $('#form-add').validate({
            rules: {
                staff: {
                    required: true,
                    valueNotEquals: ""
                },
                ticket_queue_id: {
                    required: true,
                    valueNotEquals: ""
                },
                ticket_role_queue_id: {
                    required: true,
                    valueNotEquals: ""
                },
            },
            messages: {
                staff: {
                    required: lang['Vui lòng chọn nhân viên xử lý'],
                },
                ticket_queue_id: {
                    required: lang['Vui lòng chọn queue'],
                },
                ticket_role_queue_id: {
                    required: lang['Vui lòng chọn vai trò trên queue'],
                },
            },
        });

        if (!$('#form-add').valid()) {
            return false;
        }
        $.ajax({
            url: laroute.route('ticket.queue_staff.add'),
            data: {
                staff_id: $('#form-add [name="staff"]').val(),
                ticket_queue_id: $('#form-add [name="ticket_queue_id"]').val(),
                ticket_role_queue_id: $('#form-add [name="ticket_role_queue_id"]').val(),
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status == 1) {
                    swal(
                        'Phân công nhân viên thành công',
                        '',
                        'success'
                    );
                    location.reload();
                    $('#autotable').PioTable('refresh');
                    $('#modalAdd').modal('hide');
                    clear();
                } else {
                    swal(
                        lang['Phân công nhân viên không thành công'],
                        '',
                        'warning'
                    );
                    location.reload();
                    $('#autotable').PioTable('refresh');
                }
            }
        });
    },
    edit: function(id) {
        $.ajax({
            url: laroute.route('ticket.queue_staff.edit'),
            data: {
                ticket_staff_queue_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalEdit').modal('show');
                console.log(data.staff_id, $('#form-edit [name="staff"]'))
                $('#ticket-staff-queue-id-hiden').val(data.ticket_staff_queue_id);
                $('#form-edit [name="staff"]').val(data.info.full_name).trigger('change');
                $('#form-edit [name="ticket_role_queue_id"]').val(data.ticket_role_queue_id).trigger('change');
                $('#form-edit [name="name"]').val(data.info.full_name);
                $('#form-edit [name="email"]').val(data.info.email);
                $('#form-edit [name="phone"]').val(data.info.phone);
                $('#form-edit [name="address"]').val(data.info.address);

                var arrQueueMap = [];

                if (data.queue_map.length > 0) {
                    $.each(data.queue_map, function (k,v) {
                        arrQueueMap.push(v.ticket_queue_id);
                    });
                }

                $('#form-edit [name="ticket_queue_id"]').val(arrQueueMap).trigger('change');
            }
        })
    },
    submitEdit: function() {
        $('#form-edit').validate({
            rules: {
                ticket_queue_id: {
                    required: true,
                    valueNotEquals: ""
                },
                ticket_role_queue_id: {
                    required: true,
                    valueNotEquals: ""
                },
            },
            messages: {
                staff: {
                    required: lang['Vui lòng chọn nhân viên xử lý'],
                },
                ticket_queue_id: {
                    required: lang['Vui lòng chọn queue'],
                },
                ticket_role_queue_id: {
                    required: lang['Vui lòng chọn quyền trên queue'],
                },
            },
        });

        if (!$('#form-edit').valid()) {
            return false;
        }
        $.ajax({
            url: laroute.route('ticket.queue_staff.submit-edit'),
            data: {
                ticket_staff_queue_id: $('#ticket-staff-queue-id-hiden').val(),
                ticket_queue_id: $('#form-edit [name="ticket_queue_id"]').val(),
                ticket_role_queue_id: $('#form-edit [name="ticket_role_queue_id"]').val(),
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                if (data.status == 1) {
                    $('.error-queue_name').text('');
                    $('#modalEdit').modal('hide');
                    swal(
                        lang['Cập nhật thành công'],
                        '',
                        'success'
                    );
                    $('#autotable').PioTable('refresh');
                    clear();
                } else {
                    swal(
                        lang['Phân công nhân viên không thành công'],
                        '',
                        'warning'
                    );
                    $('#autotable').PioTable('refresh');
                }
            }
        });
    },
    view: function(id) {
        $.ajax({
            url: laroute.route('ticket.queue_staff.edit'),
            data: {
                ticket_staff_queue_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                $('#form-view [name="staff"]').val(data.info.full_name).trigger('change');
                $('#form-view [name="role_queue_id"]').val(data.ticket_role_queue_id).trigger('change');
                $('#form-view [name="name"]').val(data.info.full_name);
                $('#form-view [name="email"]').val(data.info.email);
                $('#form-view [name="phone"]').val(data.info.phone);
                $('#form-view [name="address"]').val(data.info.address);

                var arrQueueMap = [];

                if (data.queue_map.length > 0) {
                    $.each(data.queue_map, function (k,v) {
                        arrQueueMap.push(v.ticket_queue_id);
                    });
                }

                $('#form-view [name="ticket_queue_id"]').val(arrQueueMap).trigger('change');
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
    $('[name="staff"]').val('').trigger('change');
    $('[name="ticket_queue_id"]').val('').trigger('change');
    $('[name="ticket_role_queue_id"]').val('').trigger('change');
    $('[name="name"]').val('');
    $('[name="email"]').val('');
    $('[name="phone"]').val('');
    $('[name="address"]').val('');
    $('[id$="-error"]').text('');
}

$('#autotable').PioTable({
    baseUrl: laroute.route('ticket.queue_staff.list')
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

$('#staff').change(function() {
    var id_staff = $(this).val();
    if (!id_staff) {
        return;
    }
    $.ajax({
        url: laroute.route('ticket.queue_staff.get-detail-staff'),
        data: {
            staff_id: id_staff
        },
        method: "POST",
        dataType: 'JSON',
        success: function(data) {
            $('[name=name]').val(data.full_name);
            $('[name=address]').val(data.address);
            $('[name=phone]').val(data.phone);
            $('[name=email]').val(data.email);
        }
    });
});

$.validator.addMethod("valueNotEquals", function(value, element, arg) {
    return arg !== value;
}, "Vui lòng chọn giá trị.");
$.validator.addMethod("checkEmpty", function(value, element, arg) {
    if (value[0] == '') {
        return false;
    }
    return true;
}, "Vui lòng chọn giá trị.");