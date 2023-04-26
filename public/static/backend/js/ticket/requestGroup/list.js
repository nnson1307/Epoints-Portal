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
                    $.post(laroute.route('ticket.group_request.remove', { id: id }), function() {
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
        $.post(laroute.route('ticket.group_request.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function() {
        let group_request_name = $('#group_request_name');
        let description = $('#description');
        let check = 0;
        let errorDescription = $('.error-description');
        let errorgroup_request_name = $('.error-group_request_name');
        $(".err").css("color", "red");
        // $.getJSON(laroute.route('translate'), function(json) {
        if (group_request_name.val() == "") {
            errorgroup_request_name.text('Vui lòng nhập tên loại yêu cầu');
        } else {
            errorgroup_request_name.text('');
        }
        if ($('#is_actived').is(':checked')) {
            check = 1;
        }
        if (group_request_name.val() != "") {
            $.ajax({
                url: laroute.route('ticket.group_request.add'),
                data: {
                    group_request_name: group_request_name.val(),
                    description: description.val(),
                    isActived: check,
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data.status == 1) {
                        errorgroup_request_name.text('');
                        swal(
                            lang['Thêm loại yêu cầu thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        clear();
                    } else {
                        errorEmail.text(lang['Email đã tồn tại']);
                    }
                }
            });
        }
        // });
    },
    addClose: function() {
        let group_request_name = $('#group_request_name');
        let description = $('#description');
        let check = 0;
        let errorDescription = $('.error-description');
        let errorgroup_request_name = $('.error-group_request_name');
        $(".err").css("color", "red");
        // $.getJSON(laroute.route('translate'), function(json) {
        if (group_request_name.val() == "") {
            errorgroup_request_name.text(lang['Vui lòng nhập tên loại yêu cầu']);
        } else {
            errorgroup_request_name.text('');
        }
        if ($('#is_actived').is(':checked')) {
            check = 1;
        }
        if (group_request_name.val() != "") {
            $.ajax({
                url: laroute.route('ticket.group_request.add'),
                data: {
                    group_request_name: group_request_name.val(),
                    description: description.val(),
                    isActived: check,
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 1) {
                        errorgroup_request_name.text('');
                        swal(
                            lang['Thêm loại yêu cầu thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        clear();
                        $('#modalAdd').modal('hide');
                    } else {
                        errorEmail.text(lang['Email đã tồn tại']);
                    }
                }
            });
        }
        // });
    },
    edit: function(id) {
        $('.error-description-edit').text('');
        $('.error-group_request_name-edit').text('');
        $.ajax({
            url: laroute.route('ticket.group_request.edit'),
            data: {
                group_requestId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalEdit').modal('show');
                $('#group_request-id-hidden').val(data['ticket_issue_group_id']);
                $('#group_request_name-edit').val(data['group_request_name']);
                $('#description-edit').val(data['description']);
                if (data['is_active'] == 1) {
                    $('#is-actived-edit').prop('checked', true);
                } else {
                    $('#is-actived-edit').prop('checked', false);
                }

            }
        })
    },
    submitEdit: function() {
        let id = $('#group_request-id-hidden');
        let group_request_name = $('#group_request_name-edit');
        let description = $('#description-edit');
        let isActive = $('#is-actived-edit');
        let check = 0;
        let errorDescription = $('.error-description-edit');
        let errorgroup_request_name = $('.error-group_request_name-edit');
        $(".err").css("color", "red");
        errorgroup_request_name.text('');
        errorDescription.text('');
        // $.getJSON(laroute.route('translate'), function(json) {
        if (isActive.is(':checked')) {
            check = 1;
        }
        if (group_request_name.val() == "") {
            errorgroup_request_name.text(lang['Vui lòng nhập tên loại yêu cầu']);
        } else {
            errorgroup_request_name.text('');
        }
        if (group_request_name.val() != "") {
            $.ajax({
                url: laroute.route('ticket.group_request.submit-edit'),
                data: {
                    id: id.val(),
                    description: description.val(),
                    isActived: check,
                    parameter: 0
                },
                method: "POST",
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 0) {
                        $('#modalEdit').modal('hide');
                        swal(
                            lang['Cập nhật loại yêu cầu không thành công'],
                            '',
                            'warning'
                        );
                        $('#autotable').PioTable('refresh');
                        clear();
                    }
                    if (data.status == 1) {
                        $('.error-group_request_name').text('');
                        $('#modalEdit').modal('hide');
                        swal(
                            lang['Cập nhật loại yêu cầu thành công'],
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
            url: laroute.route('ticket.group_request.edit'),
            data: {
                group_requestId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                $('#group_request-id-hidden').val(data['ticket_issue_group_id']);
                $('#group_request_name-view').val(data['group_request_name']);
                $('#description-view').val(data['description']);
                if (data['is_active'] == 1) {
                    $('#is-actived-view').prop('checked', true);
                } else {
                    $('#is-actived-view').prop('checked', false);
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
    $('#group_request_name').val('');
    $('#description').val('');
    $('#is-actived').val(1);
    $('.error-group_request_name').text('');
    $('.error-description').text('');
}

$('#autotable').PioTable({
    baseUrl: laroute.route('ticket.group_request.list')
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