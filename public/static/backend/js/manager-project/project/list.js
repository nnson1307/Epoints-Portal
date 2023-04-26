var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
var Project = {
    remove: function (obj, id) {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $(obj).closest('tr').addClass('m-table__row--danger');
        swal({
            title: jsonLang['Thông báo'],
            text: jsonLang["Tất cả các công việc liên quan đến dự án này sẽ bị xoá theo. Bạn xác nhận muốn xoá ?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang['Xóa'],
            cancelButtonText: jsonLang['Hủy'],
            onClose: function () {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('manager-project.project.remove', { id: id }), function (res) {
                    if (!res.error) {
                        swal(
                            res.message,
                            '',
                            'success'
                        );
                        Project.search();
                    } else {
                        swal(
                            res.message,
                            '',
                            'warning'
                        );
                    }

                });
            }
        });
    },
    changeStatus: function (obj, id, action) {
        $.post(laroute.route('manager-project.project.change-status'), { id: id, action: action }, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    addClose: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            let manage_project_name = $('#modalAdd [name="manage_project_name"]');
            let error_manage_project_name = $('#modalAdd .error_manage_project_name');
            $(".err").css("color", "red");
            if (manage_project_name.val() == "") {
                error_manage_project_name.text(json['Vui lòng nhập tên dự án']);
            } else if (manage_project_name.val().length >= 255) {
                error_manage_project_name.text(json['Tên dự án không quá 255 ký tự']);
            } else {
                error_manage_project_name.text('');
            }

            if (manage_project_name.val() != "" && manage_project_name.val().length < 255) {
                $.ajax({
                    url: laroute.route('manager-project.project.add'),
                    data: {
                        manage_project_name: manage_project_name.val(),
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status == 0) {
                            error_manage_project_name.text('');
                            swal(
                                json['Thêm dự án thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                            $('#modalAdd').modal('hide');
                        } else if (data.status == 1) {
                            error_manage_project_name.text(json['Tên dự án đã tồn tại']);
                        } else {
                            swal(
                                json['Thêm dự án thất bại'],
                                '',
                                'warning'
                            );
                        }
                    }
                });
            }
        });
    },
    edit: function (id) {
        $('.error_manage_project_name').text('');
        $.ajax({
            url: laroute.route('manager-project.project.edit'),
            data: {
                manage_project_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('#modalEdit').modal('show');
                $('#modalEdit [name="manage_project_id_hidden"]').val(data.manage_project_id);
                $('#modalEdit [name="manage_project_name"]').val(data.manage_project_name);
            }
        });
    },
    submitEdit: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            let id = $('#modalEdit [name="manage_project_id_hidden"]');
            let manage_project_name = $('#modalEdit [name="manage_project_name"]');
            let error_manage_project_name = $('#modalEdit .error_manage_project_name');
            $(".err").css("color", "red");
            error_manage_project_name.text('');
            if (manage_project_name.val() == "") {
                error_manage_project_name.text(json['Vui lòng nhập tên dự án']);
            } else if (manage_project_name.val().length >= 255) {
                error_manage_project_name.text('Tên dự án không quá 255 ký tự');
            } else {
                error_manage_project_name.text('');
            }
            if (manage_project_name.val() != "" && manage_project_name.val().length < 255) {
                $.ajax({
                    url: laroute.route('manager-project.project.submit-edit'),
                    data: {
                        manage_project_id: id.val(),
                        manage_project_name: manage_project_name.val(),
                        parameter: 0
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status == 0) {
                            error_manage_project_name.text('Tên dự án đã tồn tại');
                        }
                        if (data.status == 1) {
                            $('#modalEdit .error_manage_project_name').text('');
                            $('#modalEdit').modal('hide');
                            swal(
                                json['Cập nhật dự án thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                        } else if (data.status == 2) {
                            swal(
                                json['Cập nhật dự án thất bại'],
                                '',
                                'warning'
                            );
                        }
                    }
                });
            }
        });
    },
    view: function (id) {
        $.ajax({
            url: laroute.route('manager-project.project.edit'),
            data: {
                manage_project_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('#modalView').modal('show');
                $('#modalView [name="manage_project_name"]').val(data['manage_project_name']);
            }
        });
    },
    clear: function () {
        clear();
    },
    refresh: function () {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $(':input').val('');
        $('.m_selectpicker').val('');
        $(".select2-active").val("");
        $(".select2-active").select2();
        $('.m_selectpicker').selectpicker('refresh');
        $('.daterange-picker').val('');
        $('.daterange-picker').selectpicker('refresh');
        $("#customer_id").select2({
            placeholder: jsonLang['Khách hàng'],
            allowClear: true
        });
        $("#tags").select2({
            placeholder: jsonLang['Tags'],
            allowClear: true
        });
        $("#manage_project_status_id").select2({
            placeholder: jsonLang['Chọn trạng thái'],
            allowClear: true
        });
        $("#manager_id").select2({
            placeholder: jsonLang['Chọn người quản trị'],
            allowClear: true
        });
        $("#permission").select2({
            placeholder: jsonLang['Chọn quyền truy cập'],
            allowClear: true
        });
        $("#department_id").select2({
            placeholder: jsonLang['Chọn phòng ban trực thuộc'],
            allowClear: true
        });
        $("#create_by").select2({
            placeholder: jsonLang['Chọn người tạo dự án'],
            allowClear: true
        });
        $("#update_by").select2({
            placeholder: jsonLang['Chọn người cập nhật'],
            allowClear: true
        });

        $("#customer_type").select2({
            placeholder: jsonLang['Loại khách hàng'],
            allowClear: true
        });

        Project.search();
    },
    search: function (page = 1) {
        let manage_project_status_id = $("#manage_project_status_id").val();
        let manager_id = $("#manager_id").val();
        let date_between = $("#date_between").val();
        let progress = $("#progress").val();
        let permission = $("#permission").val();
        let department_id = $("#department_id").val();
        let date_complete = $("#date_complete").val();
        let create_by = $("#create_by").val();
        let created_at = $("#created_at").val();
        let updated_at = $("#updated_at").val();
        let update_by = $("#update_by").val();
        let customer_type = $("#customer_type").val();
        let customer_id = $("#customer_id").val();
        let tags = $("#tags").val();
        let manage_project_name = $("#manage_project_name").val();

        data = {
            manage_project_status_id,
            manager_id,
            date_between,
            progress,
            permission,
            department_id,
            date_complete,
            create_by,
            created_at,
            updated_at,
            update_by,
            customer_type,
            customer_id,
            manage_project_name,
            tags,
            page
        }

        $.ajax({
            url: laroute.route('manager-project.project.list'),
            data: data,
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    $(".table-content").html(res.view);
                }
            },
            error: function (res) {

            }
        });


    },
    getCustomerDynamic: (o) => {
        let typeCustomer = $(o).val();
        var option = '';
        let listCustomer = $("#customer_id");
        listCustomer.empty();
        $.ajax({
            url: laroute.route('manager-project.project.type'),
            data: {
                type: typeCustomer
            },
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    $.each(res.data, function (key, value) {
                        option += `<option  value="${value.customer_id}">${value.full_name}</option>`;
                    })
                    listCustomer.append(option);
                }
            }
        });
    },
    configList: () => {
        $("#modal-config").modal('show');
        Project.configSearchColum();
        Project.configShowColum();
    },
    configSearchColum: () => {
        Project.disableCheckBoxSearch();
    },
    disableCheckBoxSearch: () => {
        let listChecked = $("input[name='search']:checked").length;
        if (listChecked >= 10) {
            $("input[name='search']:not(':checked')").prop("disabled", true);
        } else {
            $("input[name='search']:not(':checked')").prop("disabled", false);
        }
    },
    configShowColum: () => {
        Project.disableCheckBoxShowColumn();
    },
    disableCheckBoxShowColumn: () => {
        let listChecked = $("input[name='column']:checked").length;
        if (listChecked >= 7) {
            $("input[name='column']:not(':checked')").prop("disabled", true);
        } else {
            $("input[name='column']:not(':checked')").prop("disabled", false);
        }
    },
    saveConfig: () => {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        let dataSearch = [];
        let dataColumn = [];
        let listSearchChecked = $("input[name='search']:checked");
        let listColumChecked = $("input[name='column']:checked");
        if (listSearchChecked.length < 2 || listColumChecked.length < 5) {
            swal.fire(jsonLang['Cấu hình danh sách thất bại'], jsonLang['Chọn ít nhất 2 cấu hình tìm kiếm và 6 cấu hình hiển thị'], "error");
            return;
        }
        listSearchChecked.each(function () {
            let val = $(this).val();
            dataSearch.push(val);
        })
        listColumChecked.each(function () {
            let val = $(this).val();
            dataColumn.push(val);
        })
        data = {
            dataSearch,
            dataColumn
        }
        $.ajax({
            url: laroute.route('manager-project.project.config.list.project'),
            data: data,
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    swal.fire(jsonLang['Cấu hình danh sách dự án thành công'], "", "success").then(function () {
                        window.location.href = laroute.route('manager-project.project');
                    });
                } else {
                    var mess_error = '';
                    $.map(res.array_error, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Cấu hình danh sách dự án thất bại'], mess_error, "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Cấu hình danh sách dự án thất bại'], mess_error, "error");
                }
            }
        });
    }
};



$("#date_start").datepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    // format: "dd/mm/yyyy hh:ii",
    format: "dd/mm/yyyy",
    // startDate : new Date()
    // locale: 'vi'
});



$("#date_start").datepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy",
    startDate: '+0d',
    minDate: new Date()
});
$("#date_end").datepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    // format: "dd/mm/yyyy hh:ii",
    format: "dd/mm/yyyy",
    // startDate : new Date()
    // locale: 'vi'
});



$("#date_end").datepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy",
    // startDate : new Date()
    // locale: 'vi'
});
function clear() {
    $('[name="manage_project_name"]').val('');
    $('.error_manage_project_name').text('');
    $('.error_manage_project_name').text('');
}

$('#autotable').PioTable({
    baseUrl: laroute.route('manager-project.project.list')
});

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
$('.m_selectpicker').datepicker({
    todayHighlight: true,
    autoclose: true,
    format: 'dd/mm/yyyy',
});
$('select[name="is_active"]').select2();

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

AutoNumeric.multiple('#progress', {
    currencySymbol: '',
    decimalCharacter: '.',
    digitGroupSeparator: ',',
    decimalPlaces: 0,
    minimumValue: 0,
    maximumValue: 100,
});

