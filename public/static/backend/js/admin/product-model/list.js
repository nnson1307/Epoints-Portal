function clearModalAdd() {
    $('#product-model-name').val('');
    $('#product-model-note').val('');
    $('.error-product-model-name').text('');
}

var productModel = {

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
                    $.post(laroute.route('admin.product-model.remove', {id: id}), function () {
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
    clearAdd: function () {
        clearModalAdd();
    },
    add: function () {
        $(".error-product-model-name").css("color", "red");
        let productModelName = $('#modalAdd #product-model-name');
        let productModelNote = $('#modalAdd #product-model-note');
        $.getJSON(laroute.route('translate'), function (json) {
            if (productModelName.val() != "") {
                $.ajax({
                    url: laroute.route('admin.product-model.add'),
                    data: {
                        productModelName: productModelName.val(),
                        productModelNote: productModelNote.val(),
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status == 1) {
                            swal(
                                json['Thêm nhãn hiệu sản phẩm thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clearModalAdd();
                        }
                        if (data.status == 0) {
                            $('.error-product-model-name').text(json['Nhãn sản phẩm đã tồn tại']);
                        }
                    }
                });

            } else {
                $('.error-product-model-name').text(json['Vui lòng nhập tên nhãn hiệu sản phẩm']);
            }
        });
    },
    addClose: function () {
        $("#modalAdd .error-product-model-name").css("color", "red");
        let productModelName = $('#modalAdd #product-model-name');
        let productModelNote = $('#modalAdd #product-model-note');
        $.getJSON(laroute.route('translate'), function (json) {
            if (productModelName.val() != "") {
                $.ajax({
                    url: laroute.route('admin.product-model.add'),
                    data: {
                        productModelName: productModelName.val(),
                        productModelNote: productModelNote.val(),
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status == 1) {
                            swal(
                                json['Thêm nhãn hiệu sản phẩm thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clearModalAdd();
                            $('#modalAdd').modal('hide');
                        }
                        if (data.status == 0) {
                            $('.error-product-model-name').text(json['Nhãn sản phẩm đã tồn tại']);
                        }
                    }
                });
            } else {
                $('#modalAdd .error-product-model-name').text(json['Vui lòng nhập tên nhãn hiệu sản phẩm']);
            }
        });
    },
    edit: function (id) {
        let productModelName = $('#modalEdit #product-model-name');
        let productModelNote = $('#modalEdit #product-model-note');
        let idHide = $('#modalEdit #id');
        let errorName = $('#modalEdit .error-product-model-name');
        $.ajax({
            url: laroute.route('admin.product-model.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('#modalEdit').modal('show');
                productModelName.val(data.product_model_name);
                productModelNote.val(data.product_model_note);
                idHide.val(data.product_model_id);
                errorName.text('');
            }
        })
    },
    submitEdit: function () {
        let productModelName = $('#modalEdit #product-model-name');
        let productModelNote = $('#modalEdit #product-model-note');
        let idHide = $('#modalEdit #id');
        let errorName = $('#modalEdit .error-product-model-name');
        $.getJSON(laroute.route('translate'), function (json) {
        if (productModelName.val() != "") {
            $.ajax({
                    url: laroute.route('admin.product-model.submit-edit'),
                    data: {
                        id: idHide.val(),
                        productModelName: productModelName.val(),
                        productModelNote: productModelNote.val(),
                        parameter: 0
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status == 0) {
                            errorName.css('color','red')
                            errorName.text(json['Nhãn sản phẩm đã tồn tại']);
                        }
                        if (data.status == 1) {
                            swal(
                                json['Cập nhật nhãn hiệu sản phẩm thành công'],
                                '',
                                'success'
                            );
                            $('#modalEdit').modal('hide');
                            $('#autotable').PioTable('refresh');
                        } else if (data.status == 2) {
                            swal({
                                title: json['Nhãn sản phẩm đã tồn tại'],
                                text: json["Bạn có muốn kích hoạt lại không?"],
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: json['Có'],
                                cancelButtonText: json['Không'],
                            }).then(function (willDelete) {
                                if (willDelete.value == true) {
                                    $.ajax({
                                        url: laroute.route('admin.product-model.submit-edit'),
                                        data: {
                                            id: idHide.val(),
                                            productModelName: productModelName.val(),
                                            productModelNote: productModelNote.val(),
                                            parameter: 1
                                        },
                                        method: "POST",
                                        dataType: 'JSON',
                                        success: function (data) {
                                            if (data.status = 3) {
                                                swal(
                                                    json['Kích hoạt nhãn sản phẩm thành công'],
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
                }
            );
        } else {
            errorName.css("color", "red");
            errorName.text(json['Vui lòng nhập tên nhãn hiệu sản phẩm']);
        }
    });
    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $(".btn-search").trigger("click");
    },
    search: function () {
        $(".btn-search").trigger("click");
    }
};

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.product-model.list')
});
