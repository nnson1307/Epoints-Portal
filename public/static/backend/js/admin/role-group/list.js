var roleGroup = {
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
            url: laroute.route('admin.role-group.change-status'),
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
        var name = $('#name');
        var errorName = $('.error-name');
        $.getJSON(laroute.route('translate'), function (json) {
            if (name.val() == '') {
                errorName.text(json['Vui lòng nhập tên nhóm quyền']);
            } else {
                errorName.text('');
                $.ajax({
                    url: laroute.route('admin.role-group.submitadd'),
                    method: "POST",
                    data: {
                        name: name.val(),
                    },
                    success: function (data) {
                        if (data.status == 1) {
                            if (parameter == 0) {
                                $('#modalAdd').modal('hide');
                            }
                            swal(
                                json['Thêm nhóm quyền thành công'],
                                '',
                                'success'
                            );
                            name.val('');
                            errorName.val('');
                            $('#autotable').PioTable('refresh');
                        } else {
                            errorName.text(json['Nhóm quyền đã tồn tại']);
                        }
                    }
                });
            }
        });
    },
    edit: function (id) {
        $('.error-e_name').text('');
        $.ajax({
            url: laroute.route('admin.role-group.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('#modalEdit').modal('show');
                $('#e_name').val(data.name);
                $('#idid').val(id);

                if (data.is_actived == 1) {
                    $('#is_actived').prop('checked', true);
                } else {
                    $('#is_actived').prop('checked', false);
                }
            }
        });
    },
    submitEdit: function () {
        var name = $('#e_name');
        var errorName=$('.error-e_name');
        var id = $('#idid');
        var is_actived = 0;
        $.getJSON(laroute.route('translate'), function (json) {
            if ($('#is_actived').is(':checked')) {
                is_actived = 1;
            }
            if (name.val() == '') {
                errorName.text(json['Vui lòng nhập tên nhóm quyền']);
            } else {
                errorName.text('');
                $.ajax({
                    url: laroute.route('admin.role-group.submit-edit'),
                    method: "POST",
                    data: {
                        name: name.val(),
                        is_actived:is_actived,
                        id: id.val(),
                    },
                    success: function (data) {
                        if (data.status == 0) {
                            errorName.text(json['Nhóm quyền đã tồn tại']);
                        }
                        if (data.status == 1) {
                            $('#autotable').PioTable('refresh');
                            swal(
                                json['Cập nhật nhóm quyền thành công'],
                                '',
                                'success'
                            );
                            $('#modalEdit').modal('hide');
                            errorName.text('');
                        }
                    }
                });
            }
        });
    }


};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.role-group.list')
});

$('select[name="is_actived"]').select2();