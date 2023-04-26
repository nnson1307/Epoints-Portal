var customerSource = {

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
                $.post(laroute.route('customer-source.remove', {id: id}), function () {
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
        $.post(laroute.route('customer-source.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function () {
        let customer_source_name = $('input[name="customer_source_name"]');
        let is_inactive = $('#is_actived');
        let isActive = 0;
        if (is_inactive.is(':checked')) {
            isActive = 1;
        }
        let type = "in";
        if ($('#type-out').is(":checked")) {
            type = "out";
        }
        $(".error-customer-source-name").css("color", "red");
        if (customer_source_name.val() != "") {
            $.ajax({
                url: laroute.route('customer-source.add'),
                data: {
                    customer_source_name: customer_source_name.val(),
                    customer_source_type: type,
                    is_inactive: isActive
                },
                method: "POST",
                dataType: "JSON",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.success == 1) {
                            swal(
                                json['Thêm nguồn khách hàng thành công'],
                                '',
                                'success'
                            );
                            customer_source_name.val('');
                            is_inactive.val('1');
                            $('.error-customer-source-name').text('');
                            $('#autotable').PioTable('refresh');
                        }
                        if (data.success == 0) {
                            $('.error-customer-source-name').text(json['Nguồn khách hàng đã tồn tại']);
                        }
                    });
                }
            });

        } else {
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error-customer-source-name').text(json['Vui lòng nhập tên nguồn khách hàng']);
            });
        }
    },
    addClose: function () {
        let customer_source_name = $('input[name="customer_source_name"]');
        let is_inactive = $('#is_actived');
        let isActive = 0;
        if (is_inactive.is(':checked')) {
            isActive = 1;
        }
        let type = "in";
        if ($('#type-out').is(":checked")) {
            type = "out";
        }
        $(".error-customer-source-name").css("color", "red");
        if (customer_source_name.val() != "") {
            $.ajax({
                url: laroute.route('customer-source.add'),
                data: {
                    customer_source_name: customer_source_name.val(),
                    customer_source_type: type,
                    is_inactive: isActive
                },
                method: "POST",
                dataType: "JSON",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.success == 1) {
                            swal(
                                json['Thêm nguồn khách hàng thành công'],
                                '',
                                'success'
                            );
                            $('#modalAdd').modal('hide');
                            customer_source_name.val('');
                            is_inactive.val('1');
                            $('.error-customer-source-name').text('');
                            $('#autotable').PioTable('refresh');
                        }
                        if (data.success == 0) {
                            
                            $('.error-customer-source-name').text(json['Nguồn khách hàng đã tồn tại']);
                        }
                    });
                }
            });

        } else {
            $('.error-customer-source-name').text('Vui lòng nhập tên nguồn khách hàng');
        }
    },
    edit: function (id) {
        $('.error-group-name').text('');
        $.ajax({
            url: laroute.route('customer-source.edit'),
            data: {
                customerSourceId: id
            },
            method: "GET",
            dataType: 'JSON',
            success: function (data) {
                $('#modalEdit').modal('show');
                $('input[name="customer_source_id_edit"]').val(data['customer_source_id']);
                $('input[name="customer_source_name_edit"]').val(data['customer_source_name']);

                if (data['customer_source_type'] == "in") {
                    $('.type-in').prop('checked', true);
                } else {
                    $('.type-out').prop('checked', true);
                }
                if (data['is_actived'] == 1) {
                    $('#is_actived_edit').prop('checked', true);
                }
                if (data['is_actived'] == 0) {
                    $('#is_actived_edit').prop('checked', false);
                }
            }
        })
    },
    submitEdit: function () {
        let id = $('input[name="customer_source_id_edit"]').val();
        let customer_source_name = $('input[name="customer_source_name_edit"]').val();
        let customer_source_type = $('select[name="customer_source_type_edit"]').val();
        let isActive = 0;
        if ($('#is_actived_edit').is(':checked')) {
            isActive = 1;
        }
        $(".error-group-name").css("color", "red");
        if (customer_source_name != "") {
            $.ajax({
                    url: laroute.route('customer-source.edit-submit'),
                    data: {
                        id: id,
                        customer_source_name: customer_source_name,
                        customer_source_type: customer_source_type,
                        is_actived: isActive,
                        parameter: 0
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function (data) {
                        $.getJSON(laroute.route('translate'), function (json) {
                        if (data.status == 0) {
                            $('.error-group-name').text(json['Nguồn khách hàng đã tồn tại']);
                        }
                        if (data.status == 1) {
                            swal(
                                json['Cập nhật nguồn khách hàng thành công'],
                                '',
                                'success'
                            );
                            $('#modalEdit').modal('hide');
                            $('#autotable').PioTable('refresh');
                        } else if (data.status == 2) {
                            swal({
                                title: json['Nguồn khách hàng đã tồn tại'],
                                text: json["Bạn có muốn kích hoạt lại không?"],
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: json['Có'],
                                cancelButtonText: json['Không'],
                            }).then(function (willDelete) {
                                if (willDelete.value == true) {
                                    $.ajax({
                                        url: laroute.route('customer-source.edit-submit'),
                                        data: {
                                            id: id,
                                            customer_source_name: customer_source_name,
                                            customer_source_type: customer_source_type,
                                            is_actived: isActive,
                                            parameter: 1
                                        },
                                        method: "POST",
                                        dataType: 'JSON',
                                        success: function (data) {
                                            if (data.status = 3) {
                                                swal(
                                                    json['Kích hoạt nguồn khách hàng thành công'],
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
                    });

                    }
                }
            );
        } else {
            $.getJSON(laroute.route('translate'), function (json) {
            $('.error-group-name').text(json['Vui lòng nhập tên nguồn khách hàng']);
            });
        }
    },
    clearAdd: function () {
        $('.error-customer-source-name').text('');
        $('#modalAdd #customer_source_type').val('in');
        $('#modalAdd #is_actived').val('1');
        $('#modalAdd #customer_source_name').val('');

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
    baseUrl: laroute.route('customer-source.list')
});
$('select[name="is_actived"]').select2();