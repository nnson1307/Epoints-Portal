var staffTitle = {
    remove: function (obj, id) {
        // hightlight row
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
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.staff-title.remove', {id: id}), function () {
                        swal(
                            json['Xóa thành công.'],
                            '',
                            'success'
                        );
                        // window.location.reload();
                        $('#autotable').PioTable('refresh');
                    });
                }
            });
        });

    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.staff-title.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
    add: function (parameter) {
        var staffTitleName = $('#modalAdd #staff_title_name');
        var staffTitleDescription = $('#modalAdd #staff_title_description');
        var errorStaffTitleName = $('#modalAdd .error-staff_title_name');
        $.getJSON(laroute.route('translate'), function (json) {
            if (staffTitleName.val() == '') {
                errorStaffTitleName.text(json['Vui lòng nhập tên chức vụ']);
            } else {
                errorStaffTitleName.text('');
                $.ajax({
                    url: laroute.route('admin.staff-title.submitadd'),
                    method: "POST",
                    data: {
                        staffTitleName: staffTitleName.val(),
                        staffTitleDescription: staffTitleDescription.val(),
                    },
                    success: function (data) {
                        if (data.status == 1) {
                            if (parameter == 0) {
                                $('#modalAdd').modal('hide');
                            }
                            swal(
                                json['Thêm chức vụ thành công'],
                                '',
                                'success'
                            );
                            staffTitleName.val('');
                            staffTitleDescription.val('');
                            $('#autotable').PioTable('refresh');
                        } else {
                            errorStaffTitleName.text(json['Chức vụ đã tồn tại']);
                        }
                    }
                });
            }
        });
    },
    edit: function (id) {
        $('#modalEdit .error-staff_title_name').text('');
        $.ajax({
            url: laroute.route('admin.staff-title.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('#modalEdit').modal('show');
                $('#modalEdit #staff_title_name').val(data.staff_title_name);
                $('#modalEdit #staff_title_description').val(data.staff_title_description);
                $('#modalEdit #staff_title_id').val(data.id);

                if (data.is_active == 1) {
                    $('#modalEdit #is_active').prop('checked', true);
                } else {
                    $('#modalEdit #is_active').prop('checked', false);
                }
            }
        });
    },
    submitEdit: function () {

        var staffTitleName = $('#modalEdit #staff_title_name');
        var staffTitleDescription = $('#modalEdit #staff_title_description');
        var errorStaffTitleName = $('#modalEdit .error-staff_title_name');
        var id = $('#modalEdit #staff_title_id');
        var is_actived = 0;
        $.getJSON(laroute.route('translate'), function (json) {
        if ($('#modalEdit #is_active').is(':checked')) {
            is_actived = 1;
        }
        if (staffTitleName == '') {
            errorStaffTitleName.text(json['Vui lòng nhập tên chức vụ']);
        } else {
            errorStaffTitleName.text('');
            $.ajax({
                url: laroute.route('admin.staff-title.submitedit'),
                method: "POST",
                data: {
                    staffTitleName: staffTitleName.val(),
                    staffTitleDescription: staffTitleDescription.val(),
                    is_actived:is_actived,
                    id:id.val(),
                    parameter: 0
                },
                success: function (data) {
                    if (data.status == 0) {
                        errorStaffTitleName.text(json['Chức vụ đã tồn tại']);
                    }
                    if (data.status == 1) {
                        $('#autotable').PioTable('refresh');
                        swal(
                            json['Cập nhật chức vụ thành công'],
                            '',
                            'success'
                        );
                        $('#modalEdit').modal('hide');
                        errorStaffTitleName.text('');
                    } else if (data.status == 2) {
                        swal({
                            title: json['Chức vụ đã tồn tại'],
                            text: json["Bạn có muốn kích hoạt lại không?"],
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: json['Có'],
                            cancelButtonText: json['Không'],
                        }).then(function (willDelete) {
                            if (willDelete.value == true) {
                                $.ajax({
                                    url: laroute.route('admin.staff-title.submitedit'),
                                    data: {
                                        staffTitleName: staffTitleName.val(),
                                        staffTitleDescription: staffTitleDescription.val(),
                                        is_actived:is_actived,
                                        id:id.val(),
                                        parameter: 1
                                    },
                                    method: "POST",
                                    dataType: 'JSON',
                                    success: function (data) {
                                        if (data.status = 3) {
                                            swal(
                                                json['Kích hoạt chức vụ thành công'],
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
    });
    }


};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.staff-title.list')
});

$('select[name="is_active"]').select2();