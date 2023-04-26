var customerGroup = {
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
                $.post(laroute.route('customer-group.remove', {id: id}), function () {
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
        $.post(laroute.route('customer-group.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    edit: function (id) {
        $('#modalEdit .error-group-name').text('');
        $.ajax({
            url: laroute.route('customer-group.edit'),
            data: {
                idCustomerGroup: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('#modalEdit').modal('show');
                $('input[name="group_name_edit"]').val(data['group_name']);
                $('input[name="customer_group_id_edit"]').val(data['customer_group_id']);
                if (data['is_actived'] == 1) {
                    $('#is_actived_edit').prop('checked', true);
                } else {
                    $('#is_actived_edit').prop('checked', false);
                }
                $('#autotable').PioTable('refresh');
                $(this).closest('tr').removeClass('btn-modal-edit-s');
            }
        })
    },
    add: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            let group_name = $('#group-name-add');
            $(".error-group-name").css("color", "red");
            if (group_name.val() == "") {
                $('.error-group-name').text(json['Vui lòng nhập tên nhóm khách hàng']);
            } else {
                $.ajax({
                    url: laroute.route('customer-group.add'),
                    data: {group_name: group_name.val()},
                    method: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status == 1) {
                            swal(
                                json['Thêm nhóm khách hàng thành công'],
                                '',
                                'success'
                            );
                            $('.error-group-name').text('');
                            group_name.val('');
                            $('#autotable').PioTable('refresh');
                        } else {
                            $('.error-group-name').text(json['Nhóm khách hàng đã tồn tại']);
                        }
                    }
                });

            }
        });
    },
    addClose: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            let group_name = $('#group-name-add');
            $(".error-group-name").css("color", "red");
            if (group_name.val() == "") {
                $('.error-group-name').text(json['Vui lòng nhập tên nhóm khách hàng']);
            } else {
                $.ajax({
                    url: laroute.route('customer-group.add'),
                    data: {group_name: group_name.val()},
                    method: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status == 1) {
                            swal(
                                json['Thêm nhóm khách hàng thành công'],
                                '',
                                'success'
                            );
                            $('#modalAdd').modal('hide');
                            $('.error-group-name').text('');
                            group_name.val('');
                            $('#autotable').PioTable('refresh');
                        } else {
                            $('.error-group-name').text(json['Nhóm khách hàng đã tồn tại']);
                        }
                    }
                });
            }
        });
    },
    submitEdit: function () {
        $(".error-group-name").css("color", "red");
        var id = $('input[name="customer_group_id_edit"]').val();
        var group_name = $('input[name="group_name_edit"]');
        let is_actived = 0;
        if ($('#is_actived_edit').is(':checked')) {
            is_actived = 1;
        }
        if (group_name != "") {
            $.ajax({
                    url: laroute.route('customer-group.edit-submit'),
                    data: {
                        id: id,
                        group_name: group_name.val(),
                        parameter: 0,
                        is_actived:is_actived
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function (data) {
                            
                            if (data.status == 0) {
                                $.getJSON(laroute.route('translate'), function (json) {
                                $('.error-group-name').text(json['Nhóm khách hàng đã tồn tại']);
                                });
                            }
                            if (data.status == 1) {
                                $('#autotable').PioTable('refresh');
                                swal(
                                    'Cập nhật nhóm khách hàng thành công',
                                    '',
                                    'success'
                                );
                                $('#modalEdit').modal('hide');
                                $('.error-group-name').text('');
                                group_name.val('');
                            } else if (data.status == 2) {
                                $.getJSON(laroute.route('translate'), function (json) {
                                swal({
                                    
                                    title: json['Nhóm khách hàng đã tồn tại'],
                                    text: json["Bạn có muốn kích hoạt lại không?"],
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: json['Có'],
                                    cancelButtonText: json['Không'],
                                }).then(function (willDelete) {
                                    if (willDelete.value == true) {
                                        $.ajax({
                                            url: laroute.route('customer-group.edit-submit'),
                                            data: {
                                                id: id,
                                                group_name: group_name.val(),
                                                parameter: 1
                                            },
                                            method: "POST",
                                            dataType: 'JSON',
                                            success: function (data) {
                                                if (data.status = 3) {
                                                    swal(
                                                        json['Kích hoạt nhóm khách hàng thành công'],
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
                            });
                            }
                    }
                }
            );
        } else {
            $(".error-group-name").css("color", "red");
            $.getJSON(laroute.route('translate'), function (json) {
            $('.error-group-name').text(json['Vui lòng nhập tên nhóm khách hàng']);
            });
        }
    },
    clearAdd: function () {
        $('#modalAdd .error-group-name').text('');
        $('#modalAdd #group-name-add').val('');
    },
    // refresh: function () {
    //     $('input[name="search_keyword"]').val('');
    //     $('.m_selectpicker').val('');
    //     $('.m_selectpicker').selectpicker('refresh');
    //     $(".btn-search").trigger("click");
    // },
    search: function () {
        $(".btn-search").trigger("click");
    }
};
$('#autotable').PioTable({
    baseUrl: laroute.route('customer-group.list')
});
$('select[name="is_actived"]').select2();


