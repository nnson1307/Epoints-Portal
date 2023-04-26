function clearModalAdd() {
    $('#modalAdd #category-name').val('');
    $('#modalAdd #description').val('');
    $('#modalAdd .error-category-name').text('');
    $('.is_actived').prop('checked', true);

    $('#icon_image').val('');
    $('#blah').attr('src', 'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947');
}

var productCategory = {
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
                    $.post(laroute.route('admin.product-category.remove', {id: id}), function () {
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
        $.post(laroute.route('admin.product-category.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    clearModalAdd: function () {
        clearModalAdd();
    },
    add: function () {
        let categoryName = $('#modalAdd #category-name');
        let categoryCode = $('#modalAdd #category-code');
        let description = $('#modalAdd #description');
        let errorCategoryName = $('#modalAdd .error-category-name');
        let check = 0;
        if ($('.is_actived').is(':checked')) {
            check = 1;
        }
        errorCategoryName.css('color', 'red');
        if (categoryName.val() != "") {
            $.ajax({
                url: laroute.route('admin.product-category.add'),
                data: {
                    categoryName: categoryName.val(),
                    description: description.val(),
                    categoryCode: categoryCode.val(),
                    isActived: check,
                    icon_image: $('#icon_image').val()
                },
                method: "POST",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.status == 1) {
                            swal(
                                json['Thêm danh mục thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clearModalAdd();
                        } else {
                            swal(
                                json['Thông báo'],
                                data.message,
                                'error'
                            );
                        }
                    });
                }
            });
        } else {
            $.getJSON(laroute.route('translate'), function (json) {
                errorCategoryName.text(json['Vui lòng nhập tên danh mục']);
            });
        }
    },
    addClose: function () {
        let categoryName = $('#modalAdd #category-name');
        let categoryCode = $('#modalAdd #category-code');
        let description = $('#modalAdd #description');
        let isActived = $('#modalAdd #is_actived');
        let errorCategoryName = $('#modalAdd .error-category-name');
        let check = 0;
        if ($('.is_actived').is(':checked')) {
            check = 1;
        }
        errorCategoryName.css('color', 'red');
        if (categoryName.val() != "") {
            $.ajax({
                url: laroute.route('admin.product-category.add'),
                data: {
                    categoryName: categoryName.val(),
                    description: description.val(),
                    categoryCode: categoryCode.val(),
                    isActived: check,
                    icon_image: $('#icon_image').val()
                },
                method: "POST",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.status == 1) {
                            swal(
                                json['Thêm danh mục thành công'],
                                '',
                                'success'
                            );
                            $('#modalAdd').modal('hide');
                            $('#autotable').PioTable('refresh');
                            clearModalAdd();
                        } else {
                            swal(
                                json['Thông báo'],
                                data.message,
                                'error'
                            );
                        }
                    });
                }
            });
        } else {
            $.getJSON(laroute.route('translate'), function (json) {
                errorCategoryName.text(json['Vui lòng nhập tên danh mục']);
            });
        }
    },
    edit: function (id) {
        $.ajax({
            url: laroute.route('admin.product-category.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (res) {
                $('#show-modal').html(res.html);
                $('#modalEdit').modal('show');
            }
        })
    },
    submitEdit: function () {
        let categoryName = $('#modalEdit #category-name');
        let categoryCode = $('#modalEdit #category-code');
        let description = $('#modalEdit #description');
        let isActived = $('#modalEdit .is_actived');
        let check = 0;
        if (isActived.is(':checked')) {
            check = 1;
        }
        let idHidden = $('#modalEdit #idHidden');
        let errorCategoryName = $('#modalEdit .error-category-name');
        errorCategoryName.css("color", "red");
        if (categoryName.val() != "") {
            $.ajax({
                    url: laroute.route('admin.product-category.submit-edit'),
                    data: {
                        id: idHidden.val(),
                        categoryName: categoryName.val(),
                        categoryCode: categoryCode.val(),
                        description: description.val(),
                        isActived: check,
                        parameter: 0,
                        icon_image: $('#icon_image').val()
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        $.getJSON(laroute.route('translate'), function (json) {
                            if (data.status == 0) {
                                swal(
                                    json['Thông báo'],
                                    data.message,
                                    'error'
                                );
                            }
                            if (data.status == 1) {
                                swal(
                                    json['Cập nhật danh mục thành công'],
                                    '',
                                    'success'
                                );
                                $('#modalEdit').modal('hide');
                                $('#autotable').PioTable('refresh');
                                errorCategoryName.text('');
                            } else if (data.status == 2) {
                                swal({
                                    title: json['Danh mục sản phẩm đã tồn tại'],
                                    text: json["Bạn có muốn kích hoạt lại không?"],
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: json['Có'],
                                    cancelButtonText: json['Không'],
                                }).then(function (willDelete) {
                                    if (willDelete.value == true) {
                                        $.ajax({
                                            url: laroute.route('admin.product-category.submit-edit'),
                                            data: {
                                                id: idHidden.val(),
                                                categoryName: categoryName.val(),
                                                description: description.val(),
                                                isActived: check,
                                                parameter: 1
                                            },
                                            method: "POST",
                                            dataType: 'JSON',
                                            success: function (data) {
                                                if (data.status = 3) {
                                                    swal(
                                                        json['Kích hoạt danh mục sản phẩm thành công'],
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
                errorCategoryName.text(json['Vui lòng nhập tên danh mục']);
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
    },
    showModalAdd: function () {
        $.ajax({
            url: laroute.route('admin.product-category.show-modal-add'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#show-modal').html(res.html);
                $('#modalAdd').modal('show');
            }
        });
    }
};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.product-category.list')
});
$('select[name="is_actived"]').select2();

function uploadAvatar2(input) {
    $.getJSON(laroute.route('translate'), function (json) {
        var arr = ['.jpg', '.png', '.jpeg', '.JPG', '.PNG', '.JPEG'];
        var check = 0;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var file_data = $('#getFile').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_product-category.');
            var fsize = input.files[0].size;
            var fileInput = input,
                file = fileInput.files && fileInput.files[0];
            var img = new Image();
            $.map(arr, function (item) {
                if (file_data.name.indexOf(item) != -1) {
                    check = 1;
                }
            })
            if (check == 1) {
                if (Math.round(fsize / 1024) <= 10240) {
                    reader.onload = function (e) {
                        $('#blah')
                            .attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                    $.ajax({
                        url: laroute.route("admin.upload-image"),
                        method: "POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (res) {
                            $('#icon_image').val(res.file);
                        },
                        error: function (res) {
                            swal.fire(json["Hình ảnh không đúng định dạng"], "", "error");
                        }
                    });
                } else {
                    swal.fire(json["Hình ảnh vượt quá dung lượng cho phép"], "", "error");
                }
            } else {
                swal.fire(json["Hình ảnh không đúng định dạng"], "", "error");
            }
        }
    });
}