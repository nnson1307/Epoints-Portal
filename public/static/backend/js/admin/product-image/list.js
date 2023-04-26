var productImage = {
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
                $.post(laroute.route('admin.product-image.remove', {id: id}), function () {
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
    add: function (close) {
        $('#close').val(close);
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#formAdd');

            form.validate({
                rules: {
                    product_id: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    type: {
                        required: true
                    }
                },
                messages: {
                    product_id: json["Vui lòng chọn sản phẩm"],
                    name: json["Vui lòng nhập tên hình"],
                    type: json["Vui lòng chọn loại hình"],
                },
            });
            if (!form.valid()) {
                return false;
            }
            let name = $('#name');
            $.ajax({
                url: laroute.route('admin.product-image.add'),
                method: "POST",
                data: {
                    productId: $('#product_id').val(),
                    name: name.val(),
                    type: $('#type').val(),
                    close: $('#close').val()
                },
                dataType: "JSON",
                success: function (data) {
                    
                        $('#autotable').PioTable('refresh');
                        $('#product_id option:first').prop('selected', true);
                        $('#type option:first').prop('selected', true);
                        name.val('');
                        swal(json["Thêm sản phẩm thành công"], "", "success");
                        if (data.close != 0) {
                            $('#modalAdd').modal('hide');
                        }
                
                }
            });
        });
    },
    edit: function (id) {
        $.ajax({
            url: laroute.route('admin.product-image.edit'),
            method: "POST",
            data: {
                id: id
            },
            dataType: "JSON",
            success: function (data) {
                $('#modalEdit #product_id').val(data.product_id);
                $('#modalEdit #name').val(data.name);
                $('#modalEdit #type').val(data.type);
                $('#modalEdit #idHidden').val(data.id);
                $('#modalEdit').modal('show');
            }
        });
    },
    submitEdit: function () {
        // $('#formEdit').validate({
        //     rules: {
        //         product_id: {
        //             required: true
        //         },
        //         name: {
        //             required: true
        //         },
        //         type: {
        //             required: true
        //         }
        //     },
        //     messages: {
        //         product_id: "Vui lòng chọn sản phẩm",
        //         name: "Vui lòng nhập tên hình",
        //         type: "Vui lòng chọn loại hình",
        //     },
        //     submitHandler: function () {
        //         $.ajax({
        //             url: laroute.route('admin.product-image.submit-edit'),
        //             method: "POST",
        //             data: {
        //                 productId: $('#modalEdit #product_id').val(),
        //                 name: $('#modalEdit #name').val(),
        //                 type: $('#modalEdit #type').val(),
        //                 id: $('#modalEdit #idHidden').val(),
        //             },
        //             success: function () {
        //                 swal("Cập nhật sản phẩm thành công", "", "success");
        //                 $('#modalEdit').modal('hide');
        //                 $('#autotable').PioTable('refresh');
        //             }
        //         })
        //     }
        // });

        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#formEdit');

            form.validate({
                rules: {
                    product_id: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    type: {
                        required: true
                    }
                },
                messages: {
                    product_id: json["Vui lòng chọn sản phẩm"],
                    name: json["Vui lòng nhập tên hình"],
                    type: json["Vui lòng chọn loại hình"],
                },
            });
            if (!form.valid()) {
                return false;
            }
            let name = $('#name');
            $.ajax({
                url: laroute.route('admin.product-image.submit-edit'),
                method: "POST",
                data: {
                    productId: $('#modalEdit #product_id').val(),
                    name: $('#modalEdit #name').val(),
                    type: $('#modalEdit #type').val(),
                    id: $('#modalEdit #idHidden').val(),
                },
                success: function () {
                    swal(json["Cập nhật sản phẩm thành công"], "", "success");
                    $('#modalEdit').modal('hide');
                    $('#autotable').PioTable('refresh');
                }
            });
        });
    }
}
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.product-image.list')
});