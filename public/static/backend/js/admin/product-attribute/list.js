function clearModalAdd() {
    $('#modalAdd .error-product-attribute-group-id').text('');
    $('#modalAdd .error-product-attribute-label').text('');
    $('#modalAdd .error-product-attribute-code').text('');
    $('#modalAdd #product_attribute_label').val('');
    var x = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for (let z = 0; z < 10; z++) {
        x += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    let d = new Date()
    let code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
    $('.product_attribute_code').val(code);
}

var productAttribute = {
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
                    $.post(laroute.route('admin.product-attribute.remove', {id: id}), function () {
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
        $.post(laroute.route('admin.product-attribute.change-status'), {id: id, action: action}, function (data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function () {
        let productAttributeGroup_id = $('#modalAdd #product_attribute_group_id');
        let productAttributeLabel = $('#modalAdd #product_attribute_label');
        let productAttributeCode = $('#modalAdd #product_attribute_code');
        let attributeTypeLabel = $('#modalAdd #attribute-type-label');
        let isActived = $('#modalAdd #is_actived');
        let errLabel = $("#modalAdd .error-product-attribute-label");
        let errCode = $('#modalAdd .error-product-attribute-code');
        let errTypeLabel = $('#modalAdd .error-attribute-type-label');
        let active = 0;
        if (isActived.is(':checked')) {
            active = 1;
        }
        $.getJSON(laroute.route('translate'), function (json) {
            if (productAttributeGroup_id.val() == "") {
                $('.error-product-attribute-group-id').css("color", "red");
                $('.error-product-attribute-group-id').text(json['Vui lòng chọn nhóm thuộc tính']);
            }
            if (productAttributeLabel.val() == "") {
                errLabel.css("color", "red");
                errLabel.text(json['Vui lòng nhập nhãn thuộc tính sản phẩm']);
            } else {
                errLabel.text('');
            }
            if (productAttributeCode.val() == "") {
                errCode.css("color", "red");
                errCode.text(json['Vui lòng nhập mã thuộc tính sản phẩm']);
            } else {
                errCode.text('');
            }
            if (attributeTypeLabel.val() == "") {
                errTypeLabel.css("color", "red");
                errTypeLabel.text(json['Vui lòng loại nhãn thuộc tính']);
            } else {
                errTypeLabel.text('');
            }
        });
        if (productAttributeCode.val() != "" && productAttributeLabel.val() != "") {
            $.ajax({
                url: laroute.route('admin.product-attribute.add'),
                data: {
                    productAttributeGroup_id: productAttributeGroup_id.val(),
                    productAttributeLabel: productAttributeLabel.val(),
                    productAttributeCode: productAttributeCode.val(),
                    isActived: active,
                    attributeTypeLabel: attributeTypeLabel.val()
                },
                method: "POST",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.label == 1) {
                            swal(
                                json['Thêm thuộc tính sản phẩm thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            $('.error-product-attribute-label').text('');
                            clearModalAdd();
                        } else {
                            $('.error-product-attribute-label').text(json['Thuộc tính sản phẩm đã tồn tại']);
                        }
                    });
                }
            });
        }
    },
    addClose: function () {
        let productAttributeGroup_id = $('#modalAdd #product_attribute_group_id');
        let productAttributeLabel = $('#modalAdd #product_attribute_label');
        let productAttributeCode = $('#modalAdd #product_attribute_code');
        let attributeTypeLabel = $('#modalAdd #attribute-type-label');
        let isActived = $('#modalAdd #is_actived');
        let errLabel = $("#modalAdd .error-product-attribute-label");
        let errCode = $('#modalAdd .error-product-attribute-code');
        let errTypeLabel = $('#modalAdd .error-attribute-type-label');
        let active = 0;
        if (isActived.is(':checked')) {
            active = 1;
        }
        $.getJSON(laroute.route('translate'), function (json) {
        if (productAttributeGroup_id.val() == "") {
            $('.error-product-attribute-group-id').css("color", "red");
            $('.error-product-attribute-group-id').text(json['Vui lòng chọn nhóm thuộc tính']);
        }
        if (productAttributeLabel.val() == "") {
            errLabel.css("color", "red");
            errLabel.text(json['Vui lòng nhập nhãn thuộc tính sản phẩm']);
        } else {
            errLabel.text('');
        }
        if (productAttributeCode.val() == "") {
            errCode.css("color", "red");
            errCode.text(json['Vui lòng nhập mã thuộc tính sản phẩm']);
        } else {
            errCode.text('');
        }
        if (attributeTypeLabel.val() == "") {
            errTypeLabel.css("color", "red");
            errTypeLabel.text(json['Vui lòng loại nhãn thuộc tính']);
        } else {
            errTypeLabel.text('');
        }
    });
        if (productAttributeGroup_id.val() != '' && productAttributeLabel.val() != "") {
            $.ajax({
                url: laroute.route('admin.product-attribute.add'),
                data: {
                    productAttributeGroup_id: productAttributeGroup_id.val(),
                    productAttributeLabel: productAttributeLabel.val(),
                    productAttributeCode: productAttributeCode.val(),
                    isActived: active,
                    attributeTypeLabel: attributeTypeLabel.val()
                },
                method: "POST",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                    if (data.label == 1) {
                        swal(
                            json['Thêm thuộc tính sản phẩm thành công'],
                            '',
                            'success'
                        );
                        $('#modalAdd').modal('hide');
                        $('#autotable').PioTable('refresh');
                        clearModalAdd();
                        $('.error-product-attribute-label').text('');
                    } else {
                        $('.error-product-attribute-label').text(json['Thuộc tính sản phẩm đã tồn tại']);
                    }
                });
                }
            });
        }
    },
    edit: function (id) {
        $('#modalEdit .error-product-attribute-label').text('');
        $('#modalEdit .error-product-attribute-code').text('');
        let idHide = $('#modalEdit #idHide');
        let productAttributeGroup_id = $('#modalEdit #product_attribute_group_id');
        let productAttributeLabel = $('#modalEdit #product_attribute_label');
        let typeLabel = $('#modalEdit #attribute-type-label');
        $.ajax({
            url: laroute.route('admin.product-attribute.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                productAttributeGroup_id.empty();
                $('#modalEdit').modal('show');
                idHide.val(data['id']);
                // productAttributeGroup_id.val(data['product_attribute_group_id']);
                $.each(data['productAttributeGroup'], function (key, value) {
                    if (data['product_attribute_group_id'] == key) {
                        productAttributeGroup_id.append('<option value="' + key + '" selected>' + value + '</option>');
                    } else {
                        productAttributeGroup_id.append('<option value="' + key + '">' + value + '</option>');
                    }
                });
                typeLabel.find('option').remove();
                typeLabel.append('<option value="' + data['type'] + '" selected>' + data['type'] + '</option>');
                switch (data['type']) {
                    case 'int':
                        typeLabel.append('<option value="text">text</option>');
                        typeLabel.append('<option value="date">date</option>');
                        typeLabel.append('<option value="boolean">boolean</option>');
                        break;
                    case 'date':
                        typeLabel.append('<option value="text">text</option>');
                        typeLabel.append('<option value="int">int</option>');
                        typeLabel.append('<option value="boolean">boolean</option>');
                        break;
                    case 'boolean':
                        typeLabel.append('<option value="text">text</option>');
                        typeLabel.append('<option value="date">date</option>');
                        typeLabel.append('<option value="int">int</option>');
                        break;
                    default:
                        typeLabel.append('<option value="int">int</option>');
                        typeLabel.append('<option value="date">date</option>');
                        typeLabel.append('<option value="boolean">boolean</option>');
                        break;
                }

                productAttributeGroup_id.select2();
                productAttributeLabel.val(data['product_attribute_label']);
                if (data['is_actived'] == 1) {
                    $('.is_actived').prop('checked', true);
                } else {
                    $('.is_actived').prop('checked', false);
                }
            }
        })
    },
    submitEdit: function () {
        let idHide = $('#modalEdit #idHide');
        let productAttributeGroup_id = $('#modalEdit #product_attribute_group_id');
        let productAttributeLabel = $('#modalEdit #product_attribute_label');
        let productAttributeCode = $('#modalEdit #product_attribute_code');
        let typeLabel = $('#modalEdit #attribute-type-label');
        let check = 0;
        if ($('.is_actived').is(':checked')) {
            check = 1;
        }
        let errLabel = $("#modalEdit .error-product-attribute-label");
        let errCode = $('#modalEdit .error-product-attribute-code');
         errLabel.css("color", "red");

        $("#modalEdit .error-product-attribute-code").css("color", "red");
        if (productAttributeLabel.val() == "") {
            $.getJSON(laroute.route('translate'), function (json) {
            errLabel.text(json['Vui lòng nhập thuộc tính sản phẩm'])
            });
        } else {
            errLabel.text('');
            $.ajax({
                url: laroute.route('admin.product-attribute.submit-edit'),
                data: {
                    id: idHide.val(),
                    productAttributeGroup_id: productAttributeGroup_id.val(),
                    productAttributeLabel: productAttributeLabel.val(),
                    isActived: check,
                    attributeTypeLabel : typeLabel.val(),
                    parameter: 0
                },
                method: "POST",
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.status == 0) {
                            errLabel.text(json['Thuộc tính sản phẩm đã tồn tại'])
                        }
                        if (data.status == 1) {
                            swal(
                                json['Cập nhật thuộc tính sản phẩm thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clearModalAdd();
                            $('#modalEdit').modal('hide');
                        } else if (data.status == 2) {
                            swal({
                                title: json['Thuộc tính sản phẩm đã tồn tại'],
                                text: json["Bạn có muốn kích hoạt lại không?"],
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: json['Có'],
                                cancelButtonText: json['Không'],
                            }).then(function (willDelete) {
                                if (willDelete.value == true) {
                                    $.ajax({
                                        url: laroute.route('admin.product-attribute.submit-edit'),
                                        data: {
                                            id: idHide.val(),
                                            productAttributeGroup_id: productAttributeGroup_id.val(),
                                            productAttributeLabel: productAttributeLabel.val(),
                                            productAttributeCode: productAttributeCode.val(),
                                            isActived: check,
                                            parameter: 1
                                        },
                                        method: "POST",
                                        dataType: 'JSON',
                                        success: function (data) {
                                            if (data.status = 3) {
                                                swal(
                                                    'Kích hoạt Thuộc tính sản phẩm thành công',
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
            });
        }
    },
    clearModalAdd: function () {
        $("#modalAdd .error-product-attribute-label").css("color", "red");
        $('#modalAdd .error-product-attribute-code').css("color", "red");
        clearModalAdd();
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
    baseUrl: laroute.route('admin.product-attribute.list')
});
$('.m_selectpicker').select2();

$('#modalEdit #product_attribute_group_id').select2();
$('#modalAdd .select2-22').select2();
$('select[name="product_attributes$is_actived"]').select2();
$('select[name="product_attribute_groups$product_attribute_group_id"]').select2();

$('#modalAdd #attribute-type-label').change(function() {
    let type = $('#modalAdd #attribute-type-label').val();
    $('#modalAdd #product_attribute_label').val('');
    switch (type) {
        case "text":

            break;
        case "int":
            // new AutoNumeric.multiple('#product_attribute_label', {
            //     currencySymbol: '',
            //     decimalCharacter: '.',
            //     digitGroupSeparator: ',',
            //     decimalPlaces: decimal_number,
            //     eventIsCancelable: true,
            //     minimumValue: 0
            // });
            break;
        case "date":
            // $("#product_attribute_label").datepicker({
            //     todayHighlight: !0,
            //     autoclose: !0,
            //     format: "dd/mm/yyyy"
            // });
            break;
        case "boolean":
            break;
        default:
            break;
    }
});

