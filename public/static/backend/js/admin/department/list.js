function clearModalAdd() {
    $('#modalAdd #department_name').val('');
    $('#modalAdd #is_inactive').val('1');
    $('#modalAdd .department-name').text('');
}

var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var Department = {
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
        // $.getJSON(laroute.route('translate'), function (json) {
        swal({
            title: jsonLang['Thông báo'],
            text: jsonLang["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang['Xóa'],
            cancelButtonText: jsonLang['Hủy'],
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.department.remove', {id: id}), function () {
                    swal(
                        jsonLang['Xóa thành công.'],
                        '',
                        'success'
                    );

                    // window.location.reload();

                    $('#autotable').PioTable('refresh');
                });
            }
        });
        // });
    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.department.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
    clearAdd: function () {
        clearModalAdd();
    },
    showPopupAdd: function () {
        $.ajax({
            url: laroute.route('admin.department.show-pop-add'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modalAdd').modal('show');

                $('#branch_id').select2({
                    placeholder: jsonLang['Chọn thông tin cha']
                });

                $('#staff_title_id').select2({
                    placeholder: jsonLang['Chọn chức vụ']
                });

                $('#staff_id').select2({
                    placeholder: jsonLang['Chọn người quản lý']
                });
            }
        });
    },
    add: function () {
        $('#form-department').validate({
            rules: {
                department_name: {
                    required: true,
                    maxlength: 190
                },
                // staff_title_id: {
                //     required: true
                // },
                // branch_id: {
                //     required: true
                // },
                // staff_id: {
                //     required: true
                // }
            },
            messages: {
                department_name: {
                    required: jsonLang['Yêu cầu nhập tên phòng ban'],
                    maxlength: jsonLang['Tên phòng ban vượt quá 190 ký tự']
                },
                staff_title_id: {
                    required: jsonLang['Hãy chọn chức vụ']
                },
                branch_id: {
                    required: jsonLang['Hãy chọn thông tin nhánh cha']
                },
                staff_id: {
                    required: jsonLang['Hãy chọn người quản lý']
                }
            },
        });

        if (!$('#form-department').valid()) {
            return true;
        } else {
            $.ajax({
                url: laroute.route('admin.department.add'),
                method: 'POST',
                dataType: 'JSON',
                data: $('#form-department').serialize(),
                success: function (res) {
                    if (res.error == true) {
                        swal(jsonLang['Tạo phòng ban thất bại'], '', 'error');
                    } else {
                        swal(jsonLang['Tạo phòng ban thành công'], '', 'success').then(function () {
                            location.reload();
                        });
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(jsonLang['Tạo phòng ban thất bại'], mess_error, "error");
                }
            });
        }
    },
    edit: function (id) {
        $.ajax({
            url: laroute.route('admin.department.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modalEdit').modal('show');

                $('#branch_id').select2({
                    placeholder: jsonLang['Chọn thông tin cha']
                });

                $('#staff_title_id').select2({
                    placeholder: jsonLang['Chọn chức vụ']
                });

                $('#staff_id').select2({
                    placeholder: jsonLang['Chọn người quản lý']
                });

            }
        })
    },
    submitEdit: function (id) {
        $('#form-department').validate({
            rules: {
                department_name: {
                    required: true,
                    maxlength: 190
                },
                // staff_title_id: {
                //     required: true
                // },
                // branch_id: {
                //     required: true
                // },
                // staff_id: {
                //     required: true
                // }
            },
            messages: {
                department_name: {
                    required: jsonLang['Yêu cầu nhập tên phòng ban'],
                    maxlength: jsonLang['Tên phòng ban vượt quá 190 ký tự']
                },
                staff_title_id: {
                    required: jsonLang['Hãy chọn chức vụ']
                },
                branch_id: {
                    required: jsonLang['Hãy chọn thông tin nhánh cha']
                },
                staff_id: {
                    required: jsonLang['Hãy chọn người quản lý']
                }
            },
        });

        if (!$('#form-department').valid()) {
            return true;
        } else {
            var is_inactive = 0;
            if ($('#is_inactive').is(':checked')) {
                is_inactive = 1;
            }

            $.ajax({
                url: laroute.route('admin.department.submit-edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    department_id: id,
                    department_name: $('#departName-edit').val(),
                    // branch_id: $('#branch_id').val(),
                    // staff_title_id: $('#staff_title_id').val(),
                    // staff_id: $('#staff_id').val(),
                    is_inactive: is_inactive
                },
                success: function (res) {
                    if (res.error == true) {
                        swal(jsonLang['Chỉnh sửa phòng ban thất bại'], '', 'error');
                    } else {
                        swal(jsonLang['Chỉnh sửa phòng ban thành công'], '', 'success').then(function () {
                            location.reload();
                        });
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(jsonLang['Chỉnh sửa phòng ban thất bại'], mess_error, "error");
                }
            });
        }
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
};

var view = {
    changeTitle: function (obj) {
        $.ajax({
            url: laroute.route('team.team.change-title'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_title_id: $(obj).val()
            },
            success: function (res) {
                $('#staff_id').empty();

                $.map(res.optionStaff, function (v) {
                    $('#staff_id').append('<option value="' + v.staff_id + '">' + v.full_name + '</option>');
                })
            }
        });
    }
};

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.department.list')
});

$('.m_selectpicker').select2();