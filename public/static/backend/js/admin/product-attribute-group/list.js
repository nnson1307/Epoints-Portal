function clearModalAdd() {
    $('#modalAdd .error-product-attribute-group-name').text('');
    $('#modalAdd #product_attribute_group_name').val('');
}

function clearModalEdit() {
    $('#modalEdit .error-product-attribute-group-name').text('');
    $('#modalEdit #product_attribute_group_name').val('');
}

var productAttributeGroup = {
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
                $.post(laroute.route('admin.product-attribute-group.remove', {id: id}), function () {
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
        $.post(laroute.route('admin.product-attribute-group.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    clearAdd: function () {
        clearModalAdd();
    },
    add: function () {
        let productAttrName = $('#product_attribute_group_name');
        let errName = $('#modalAdd .error-product-attribute-group-name');
        let check = 0;
        if ($('#is_actived').is(':checked')) {
            check = 1;
        }
        if (productAttrName.val() != "") {
            $.ajax({
                url: laroute.route('admin.product-attribute-group.add'),
                data: {
                    productAttrName: productAttrName.val(),
                    isActived: check,
                },
                method: "POST",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                    if (data.message == "") {
                        swal(
                            json['Thêm nhóm thuộc tính sản phẩm thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        clearModalAdd();
                    } else {
                        errName.css('color', 'red');
                        errName.text(data.message);
                    }
                });

                }
            });
        } else {
            $("#modalAdd .error-product-attribute-group-name").css("color", "red");
            $.getJSON(laroute.route('translate'), function (json) {
            $('#modalAdd .error-product-attribute-group-name').text(json['Vui lòng nhập tên nhóm thuộc tính']);
            });
        }
    },
    addClose: function () {
        let productAttrName = $('input[name="product_attribute_group_name"]');
        let errName = $('#modalAdd .error-product-attribute-group-name');
        let check = 0;
        if ($('#is_actived').is(':checked')) {
            check = 1;
        }
        if (productAttrName.val() != "") {
            $.ajax({
                url: laroute.route('admin.product-attribute-group.add'),
                data: {
                    productAttrName: productAttrName.val(),
                    isActived: check,
                },
                method: "POST",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                    if (data.message == "") {
                        swal(
                            json['Thêm nhóm thuộc tính sản phẩm thành công'],
                            '',
                            'success'
                        );
                        $('#modalAdd').modal('hide');
                        $('#autotable').PioTable('refresh');
                        clearModalAdd();
                    } else {
                        errName.css('color', 'red');
                        errName.text(data.message);
                    }
                    });
                }
            });
        } else {
            $("#modalAdd .error-product-attribute-group-name").css("color", "red");
            $.getJSON(laroute.route('translate'), function (json) {
            $('#modalAdd .error-product-attribute-group-name').text(json['Vui lòng nhập tên nhóm thuộc tính']);
            });
        }
    },
    edit: function (id) {
        $.ajax({
            url: laroute.route('admin.product-attribute-group.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                if (data['is_actived'] == 1) {
                    $('#modalEdit #is_actived11').prop('checked', true);
                } else {
                    $('#modalEdit #is_actived11').prop('checked', false);
                }
                $('#modalEdit #idHidden').val(data['id']);
                $('#modalEdit #productattname').val(data['product_attribute_group_name']);
                $('#modalEdit').modal('show');

            }
        })
    },
    submitEdit: function () {
        let id = $('#modalEdit #idHidden').val();
        let isActive = $('#modalEdit #is_actived11');
        let productAttrName = $('#modalEdit #productattname').val();
        let errName = $('#modalEdit .error-product-attribute-group-name');
        errName.css("color", "red");
        let acvived = 0;
        if (isActive.is(':checked')) {
            acvived = 1;
        }else {
            acvived=0;
        }
        if (productAttrName != "") {
            $.ajax({
                    url: laroute.route('admin.product-attribute-group.submit-edit'),
                    data: {
                        id: id,
                        isActive: acvived,
                        productAttrName: productAttrName,
                        parameter: 0
                    },
                    method: "POST",

                    success: function (data) {
                        $.getJSON(laroute.route('translate'), function (json) {
                        if (data.status == 0) {
                            errName.text(json['Nhóm thuộc tính sản phẩm đã tồn tại']);
                        }
                        if (data.status == 1) {
                            swal(
                                json['Cập nhật nhóm thuộc tính sản phẩm thành công'],
                                '',
                                'success'
                            );
                            $('#modalEdit').modal('hide');
                            $('#autotable').PioTable('refresh');
                            $('.error-product-attribute-group-name').text('');
                        } else if (data.status == 2) {
                            swal({
                                title: json['Nhóm thuộc tính sản phẩm đã tồn tại'],
                                text: json["Bạn có muốn kích hoạt lại không?"],
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: json['Có'],
                                cancelButtonText: json['Không'],
                            }).then(function (willDelete) {
                                if (willDelete.value == true) {
                                    $.ajax({
                                        url: laroute.route('admin.product-attribute-group.submit-edit'),
                                        data: {
                                            id: id,
                                            isActive: acvived,
                                            productAttrName: productAttrName,
                                            parameter: 1
                                        },
                                        method: "POST",
                                        dataType: 'JSON',
                                        success: function (data) {
                                            if (data.status = 3) {
                                                swal(
                                                    json['Kích hoạt nhóm nhóm thuộc tính sản phẩm thành công'],
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
            errName.text(json['Vui lòng nhập tên nhóm thuộc tính']);
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
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.product-attribute-group.list')
});
$('select[name="is_actived"]').select2();

