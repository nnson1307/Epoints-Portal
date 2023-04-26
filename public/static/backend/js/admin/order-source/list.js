var OrderSource = {
    remove: function (obj, id) {

        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: 'Thông báo',
            text: "Bạn có muốn xóa không?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            onClose: function () {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.order-source.remove', {id: id}), function () {
                    swal(
                        'Xóa thành công.',
                        '',
                        'success'
                    );
                    $('#autotable').PioTable('refresh');
                });
            }
        });
    },
    changeStatus: function (obj, id, action) {
        $.post(laroute.route('admin.order-source.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    clearAdd: function () {
        $('.error-order-source-name').text('');
        $('input[name="order_source_name"]').val('');
    },
    add: function () {
        $(".error-order-source-name").css("color", "red");
        let orderSourceName = $('input[name="order_source_name"]').val();
        let check = 0;
        if ($('#is_actived').is(':checked')) {
            check = 1;
        }
        if (orderSourceName != "") {
            $.ajax({
                url: laroute.route('admin.order-source.add'),
                data: {
                    orderSourceName: orderSourceName,
                    isActived: check,
                },
                method: "POST",
                success: function (data) {
                    if (data.status == 1) {
                        swal(
                            'Thêm nguồn đơn hàng thành công',
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        $('input[name="order_source_name"]').val('');
                        $('.error-order-source-name').text('');
                    } else {
                        $('.error-order-source-name').text('Nguồn đơn hàng đã tồn tại');
                    }

                }
            });
        } else {
            $('.error-order-source-name').text('Vui lòng nhập tên nguồn đơn hàng');
        }
    },
    addClose: function () {
        $(".error-order-source-name").css("color", "red");
        let orderSourceName = $('input[name="order_source_name"]').val();
        let check = 0;
        if ($('#is_actived').is(':checked')) {
            check = 1;
        }
        if (orderSourceName != "") {
            $.ajax({
                url: laroute.route('admin.order-source.add'),
                data: {
                    orderSourceName: orderSourceName,
                    isActived: check,
                },
                method: "POST",
                success: function (data) {
                    if (data.status == 1) {
                        swal(
                            'Thêm nguồn đơn hàng thành công',
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        $('.error-order-source-name').text('');
                        $('input[name="order_source_name"]').val('');
                        $('#modalAdd').modal('hide');
                    } else {
                        $('.error-order-source-name').text('Nguồn đơn hàng đã tồn tại');
                    }

                }
            });
        } else {
            $('.error-order-source-name').text('Vui lòng nhập tên nguồn đơn hàng');
        }
    },
    edit: function (id) {
        $('.error-order-source-name').text('');
        $.ajax({
            url: laroute.route('admin.order-source.edit'),
            data: {
                orderSourceId: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('#modalEditOrderSource').modal('show');
                if (data['is_actived'] == 1) {
                    $('#is-actived-edit').prop('checked', true);
                } else {
                    $('#is-actived-edit').prop('checked', false);
                }
                $('#oderSourceIdHidden').val(data['id']);
                $('#orderSourceName').val(data['order_source_name']);
            }
        })
    },
    submitEdit: function () {
        $(".error-order-source-name").css("color", "red");
        let id = $('#oderSourceIdHidden').val();
        let orderSourceName = $('#orderSourceName').val();
        let check = 0;
        if ($('#is-actived-edit').is(':checked')) {
            check = 1;
        }
        if (orderSourceName != "") {
            $.ajax({
                    url: laroute.route('admin.order-source.submit-edit'),
                    data: {
                        id: id,
                        isActive: check,
                        orderSourceName: orderSourceName,
                        parameter: 0
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status == 0) {
                            $('.error-order-source-name').text('Nguồn đơn hàng đã tồn tại');
                        }

                        if (data.status == 1) {
                            swal(
                                'Cập nhật nguồn đơn hàng thành công',
                                '',
                                'success'
                            );
                            $('#modalEditOrderSource').modal('hide');
                            $('#autotable').PioTable('refresh');
                            $('.error-order-source-name').text('');
                        } else if (data.status == 2) {
                            swal({
                                title: 'Nguồn đơn hàng đã tồn tại',
                                text: "Bạn có muốn kích hoạt lại không?",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Có',
                                cancelButtonText: 'Không',
                            }).then(function (willDelete) {
                                if (willDelete.value == true) {
                                    $.ajax({
                                        url: laroute.route('admin.order-source.submit-edit'),
                                        data: {
                                            id: id,
                                            isActive: check,
                                            orderSourceName: orderSourceName,
                                            parameter: 1
                                        },
                                        method: "POST",
                                        dataType: 'JSON',
                                        success: function (data) {
                                            if (data.status = 3) {
                                                swal(
                                                    'Kích hoạt nguồn đơn hàng thành công',
                                                    '',
                                                    'success'
                                                );
                                                $('#autotable').PioTable('refresh');
                                                $('#modalEditOrderSource').modal('hide');
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
                }
            );
        } else {
            $('.error-order-source-name').text('Vui lòng nhập tên nguồn đơn hàng');
        }
    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $('select[name="is_actived"]').val('').trigger('change');
        $(".btn-search").trigger("click");
    },
    search: function () {
        $(".btn-search").trigger("click");
    }
};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.order-source.list')
});
$('select[name="is_actived"]').select2();


