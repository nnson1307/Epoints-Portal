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
                    $.post(laroute.route('ticket.role.remove', { id: id }), function() {
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
        $.post(laroute.route('ticket.role.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function() {
        let role_group_id = $('#role_group_id');
        let description = $('#description');
        let errorDescription = $('.error-description');
        let error_role_group_id = $('.error-role_group_id');
        let status = $('#ticket_status_role_table [name="ticket_status_role[]"]:checked').map(function() {
            return this.value;
        }).get();
        $(".err").css("color", "red");
        // $.getJSON(laroute.route('translate'), function(json) {
        if (role_group_id.val() == "") {
            error_role_group_id.text(lang['Vui lòng chọn tên role']);
        } else {
            error_role_group_id.text('');
        }
        if (description.val().length > 255) {
            $('.error-description').text(lang['Mô tả không được quá 255 ký tự']);
        } else {
            $('.error-description').text('');
        }
        if (role_group_id.val() != "" && description.val().length <= 255) {
            $.ajax({
                url: laroute.route('ticket.role.add'),
                data: {
                    role_group_id: role_group_id.val(),
                    description: description.val(),
                    status: status
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data.status == 1) {
                        error_role_group_id.text('');
                        swal(
                            lang['Thêm role thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        clear();
                    } else {
                        swal(
                            lang['Thêm role không thành công'],
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
        let role_group_id = $('#role_group_id');
        let is_approve_refund = 0;
        if ($('#modalAdd [name="is_approve_refund"]').is(":checked")) {
            is_approve_refund = 1;
        }
        let description = $('#description');
        let errorDescription = $('.error-description');
        let error_role_group_id = $('.error-role_group_id');
        let status = $('#ticket_status_role_table [name="ticket_status_role[]"]:checked').map(function() {
            return this.value;
        }).get();
        let action = $('#modalAdd [name="ticket_action_role"]').val();
        $(".err").css("color", "red");
        // $.getJSON(laroute.route('translate'), function(json) {
        if (role_group_id.val() == "") {
            error_role_group_id.text(lang['Vui lòng chọn tên role']);
        } else {
            error_role_group_id.text('');
        }
        if (description.val().length > 255) {
            errorDescription.text(lang['Mô tả không được quá 255 ký tự']);
        } else {
            errorDescription.text('');
        }
        if (description.val().length <= 255 && role_group_id.val() != "") {
            $.ajax({
                url: laroute.route('ticket.role.add'),
                data: {
                    role_group_id: role_group_id.val(),
                    description: description.val(),
                    status: status,
                    action: action,
                    is_approve_refund: is_approve_refund
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 1) {
                        error_role_group_id.text('');
                        swal(
                            lang['Thêm role thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        $('#modalAdd').modal('hide');
                        location.reload();
                    } else {
                        swal(
                            lang['Thêm role không thành công'],
                            '',
                            'warning'
                        );
                    }
                }
            });
        }
    },
    edit: function(id) {
        Shift.clear();
        $('.option-tmp').remove();
        $('.error-description-edit').text('');
        $('.error-role_group_id-edit').text('');
        $.ajax({
            url: laroute.route('ticket.role.edit'),
            data: {
                roleId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalEdit').modal('show');
                $('#role-id-hidden').val(data.ticket_role_id);
                $('#role_group_id-edit').append(data.option);
                $('#role_group_id-edit').val(data.role_group_id).trigger('change');
                $('#description-edit').val(data.description);
                if (data.is_approve_refund) {
                    $('#modalEdit [name="is_approve_refund"]').prop('checked', true);
                }
                $.each(data.role_status, function() {
                    $('#ticket_status_role_table-edit input[value="' + this.ticket_status_id + '"]').prop('checked', true);
                });
                if (data.role_action) {
                    $('#modalEdit [name="ticket_action_role"]').val(data.role_action.ticket_action_value).change();
                }

            }
        })
    },
    submitEdit: function() {
        let id = $('#role-id-hidden');
        let is_approve_refund = 0;
        if ($('#modalEdit [name="is_approve_refund"]').is(":checked")) {
            is_approve_refund = 1;
        }
        let error_role_group_id = $('.error-role_group_id-edit');
        let role_group_id = $('#role_group_id-edit');
        let description = $('#description-edit');
        let status = $('#ticket_status_role_table-edit [name="ticket_status_role[]"]:checked').map(function() {
            return this.value;
        }).get();
        let action = $('#modalEdit [name="ticket_action_role"]').val();
        let errorDescription = $('.error-description-edit');
        $(".err").css("color", "red");
        errorDescription.text('');
        if (role_group_id.val() == "") {
            error_role_group_id.text(lang['Vui lòng chọn tên role']);
        } else {
            error_role_group_id.text('');
        }
        // $.getJSON(laroute.route('translate'), function(json) {
        if (description.val().length > 255) {
            errorDescription.text(lang['Mô tả không được quá 255 ký tự']);
        } else {
            errorDescription.text('');
        }
        if (description.val().length <= 255 && role_group_id.val() != "") {
            $.ajax({
                url: laroute.route('ticket.role.submit-edit'),
                data: {
                    id: id.val(),
                    role_group_id: role_group_id.val(),
                    description: description.val(),
                    status: status,
                    action: action,
                    is_approve_refund: is_approve_refund,
                    parameter: 0
                },
                method: "POST",
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 1) {
                        $('.error-role_group_id').text('');
                        $('#modalEdit').modal('hide');
                        swal(
                            lang['Cập nhật role thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        location.reload();
                    }
                }
            });
        }
        // });
    },
    view: function(id) {
        $('.option-tmp').remove();
        $.ajax({
            url: laroute.route('ticket.role.edit'),
            data: {
                roleId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                // $('#role-id-hidden').val(data['ticket_role_id']);
                $('#modalView #role_group_id-view').append(data.option);
                $('#modalView [name="role_group_id"]').val(data.role_group_id).trigger('change');
                $('#modalView [name="description"]').val(data.description);
                if (data.is_approve_refund) {
                    $('#modalView [name="is_approve_refund"]').prop('checked', true);
                }
                $.each(data.role_status, function() {
                    $('#ticket_status_role_table-view input[value="' + this.ticket_status_id + '"]').prop('checked', true);
                });
                if (data.role_action) {
                    $('#modalView [name="ticket_action_role"]').val(data.role_action.ticket_action_value).change();
                }

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
    $('#role_group_id').val('').trigger('change');
    $('#description').val('');
    $('[name="ticket_status_role[]"]').prop('checked', false);
    $('[name="ticket_action_role[]"]').prop('checked', false);
    $('.error-role_group_id').text('');
    $('.error-description').text('');
    $('[name="is_approve_refund"]').prop('checked', false);
}

$('#autotable').PioTable({
    baseUrl: laroute.route('ticket.role.list')
});

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