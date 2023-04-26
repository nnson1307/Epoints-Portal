$('.rdo').click(function () {
    $('.rdo').attr('class', 'btn btn-default rdo');
    $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
});
quantityProduct();
$('#warehouse-output').select2();
$('#warehouse-input').select2();

function quantityProduct() {
    var sum = 0;
    $.each($('.outputQuantity'), function () {
        sum += parseInt($(this).val());
    });
    $('#total-product').val(sum);
}

// $('.outputQuantity').TouchSpin({
//     min: 0,
//     max: 100000,
// });

function deleteProductInList(o) {
    $(o).closest('tr').remove();
    let table = $('#table-product > tbody tr').length;
    let a = 1;
    $.each($('.stt'), function () {
        $(this).text(a++);
    });
    quantityProduct();
}

$('#day-input').datepicker({format: 'dd/mm/yyyy'});
$('#day-input').datepicker('setDate', 'today');
$('#day-output').datepicker({format: 'dd/mm/yyyy'});
$('#day-output').datepicker('setDate', 'today');
// $('#list-product').select2({
//     // placeholder: 'Nhập tên hoặc mã sản phẩm',
//     // ajax: {
//     //     url: laroute.route('admin.inventory-transfer.search-product-child'),
//     //     dataType: 'JSON',
//     //     delay: 250,
//     //     type: 'POST',
//     //     data: function (params) {
//     //         if (params.term != null) {
//     //             var query = {
//     //                 search: params.term,
//     //                 page: params.page || 1,
//     //                 warehouseOutput: $('#warehouse-output').val()
//     //             };
//     //             return query;
//     //         }
//     //     }
//     // },
//     //minimumInputLength: 1
// }).on("select2:select", function (e) {
//     let id = e.params.data.id;
//     let stt = 1 + $('#table-product > tbody > tr').length;
//     let flag = true;
//     if (id != '') {
//         $.ajax({
//             url: laroute.route('admin.inventory-transfer.get-product-child-by-id'),
//             method: "POST",
//             dataType: "JSON",
//             data: {
//                 id: id,
//                 warehouseOutput: $('#warehouse-output').val()
//             },
//             success: function (data) {
//                 console.log(data)
//                 $.each($('#table-product tbody tr'), function () {
//                     let codeHidden = $(this).find("td input[name='hiddencode[]']");
//                     let codeExists = codeHidden.val();
//                     var code = data['product']['product_code'];
//                     if (codeExists == code) {
//                         flag = false;
//                         let valueNumberProduct = codeHidden.parents('tr').find('.outputQuantity').val();
//                         let numbers = parseInt(valueNumberProduct) + 1;
//                         codeHidden.parents('tr').find('.outputQuantity').val(numbers);
//                         quantityProduct();
//
//                         var inventory = codeHidden.parents('tr').find(".productInventory").val();
//                         var quantity = codeHidden.parents('tr').find('.outputQuantity').val();
//                         if (InventoryTransfer.checkInventory2(quantity, inventory) == false) {
//                             codeHidden.parents('tr').find('.error-output-quantity').text('Vượt quá số lượng');
//                         } else {
//                             codeHidden.parents('tr').find('.error-output-quantity').text('');
//                         }
//                     }
//                 });
//                 if (flag == true) {
//                     var option = "";
//                     $.each(data['units'], function (index, element) {
//                         if (data['unitExists']['unit_id'] == index) {
//                             option += '<option selected value="' + index + '">' + element + '</option>';
//                         } else {
//                             option += '<option value="' + index + '">' + element + '</option>';
//                         }
//                     });
//                     let $_tpl = $('#product-childs').html();
//                     let tpl = $_tpl;
//                     tpl = tpl.replace(/{stt}/g, stt);
//                     tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
//                     tpl = tpl.replace(/{code}/g, data['product']['product_code']);
//                     tpl = tpl.replace(/{option}/g, option);
//                     tpl = tpl.replace(/{productInventory}/g, data['productInventory']);
//                     tpl = tpl.replace(/{outputQuantity}/g, 0);
//                     $('#table-product > tbody').append(tpl).parents('tr').find('.unit').empty();
//                     // $(".outputQuantity").TouchSpin({
//                     //     min: 0,
//                     //     max: 100000,
//                     // });
//                     $('.unit').select2();
//                     quantityProduct();
//                     $('.outputQuantity').ForceNumericOnly();
//                 }
//             }
//         });
//     }
// });

function changeOutputQuantity(o) {
    let values = parseInt($(o).val());
    if (values > 0) {
        $(o).val(values);

    } else {
        $(o).val(1);
    }
    $('.errs').css('color', 'red');
    if (parseInt($(o).val()) <= 0) {
        $(o).parents('td').find('.error-output-quantity').text('Nhập lại số lượng');
    } else {
        let productInventory = $(o).parents('tr').find('.productInventory').val();

        if (parseInt($(o).val()) > parseInt(productInventory)) {
            $(o).parents('td').find('.error-output-quantity').text('Vượt quá số lượng');
            $(o).parents('td').find('.span-err').text('');
            check = false;
        } else {
            $(o).parents('td').find('.error-output-quantity').text('');
            $(o).parents('td').find('.span-err').text('');
            check = true;
        }
        quantityProduct();
    }
    InventoryTransfer.checkInventory(o);
}

$('#warehouse-output').change(function () {
    $('tbody').empty();
    $('#list-product').empty();
    $('#list-product').append('<option value="">Chọn sản phẩm</option>');
    $('#total-product').val(0);
    $('.error-warehouse-out').css('color', 'red');
    if ($('#warehouse-output').val() == "") {
        $('.error-warehouse-out').text('Vui lòng chọn kho xuất');
        $('#list-product').attr('disabled', true);
        $('#product-code').attr('disabled', true);
    } else {
        $('.error-warehouse-out').text('');
    }
    if ($('#warehouse-output').val() != "") {
        $.ajax({
            url: laroute.route('admin.inventory-transfer.get-warehouse-not-id'),
            method: "POST",
            data: {id: $(this).val()},
            dataType: "JSON",
            success: function (data) {
                $('#warehouse-input').empty();
                $('#warehouse-input').append('<option value="">Chọn kho nhập</option>');
                $.each(data, function (key, value) {
                    $('#warehouse-input').append('<option value="' + key + '">' + value + '</option>')
                })
            }
        });

        $.ajax({
            url: laroute.route('admin.inventory-transfer.get-product-by-warehouse'),
            method: "POST",
            data: {warehouse_id: $('#warehouse-output').val()},
            dataType: "JSON",
            success: function (data) {
                $('#list-product').empty();
                $('#list-product').append('<option value="">Chọn sản phẩm</option>');
                if (data != '') {
                    $.each(data, function (key, value) {
                        $('#list-product').append('<option value="' + key + '">' + value + '</option>')
                    });
                }
            }
        });

        $('#warehouse-input').attr('disabled', false);
        $('#list-product').attr('disabled', false);
        $('#product-code').attr('disabled', false);


    }
    if ($('#warehouse-input').val() == "") {
        $('.error-warehouse-input').css('color', 'red');
        $('.error-warehouse-input').text('Vui lòng chọn kho nhập');
    } else {
        $('.error-warehouse-input').text('');
    }
});
$('#product-code').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});

function compareDateTime(date1, date2) {
    return (date1 > date2);
}

function getFormattedDate(date) {
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return month + '/' + day + '/' + year;
}

function checkInput() {
    let flag = true;
    let errWarehouseOut = $('.error-warehouse-out');
    let errWarehouseIn = $('.error-warehouse-input');
    let errDayIn = $('.error-day-input');
    let errDayOut = $('.error-day-output');
    var now = new Date();
    if ($('#warehouse-output').val() == "") {
        errWarehouseOut.text('Vui lòng chọn kho xuất');
        flag = false;
    } else {
        errWarehouseOut.text('');
    }
    if ($('#warehouse-input').val() == "") {
        $('.error-warehouse-input').css('color', 'red');
        errWarehouseIn.text('Vui lòng chọn kho nhập');
        flag = false;
    } else {
        errWarehouseIn.text('');
    }
    if ($('#day-input').val() == "") {
        errDayIn.text('Vui lòng chọn ngày nhập');
        flag = false;
    } else {
        errDayIn.text('');
    }
    if ($('#day-output').val() == "") {
        errDayOut.text('Vui lòng chọn ngày xuất');
        flag = false;
    } else {
        errDayOut.text('');
    }
    return flag;
}

var check = true;
$('#product-code').bind("enterKey", function (e) {
    if (checkInput() == true) {
        let o = $(this);
        let flag = true;
        let codeInput = $(this).val().trim();
        $.each($('#table-product tbody tr'), function () {
            let codeHidden = $(this).find("td input[name='hiddencode[]']");
            var code = codeHidden.val();

            if (codeInput == code) {
                flag = false;
                let valueNumberProduct = codeHidden.parents('tr').find('.outputQuantity').val();
                let numbers = parseInt(valueNumberProduct) + 1;
                codeHidden.parents('tr').find('.outputQuantity').val(numbers);
                quantityProduct();
                o.focus();
                $('.error-code-product').css('color', 'red');
                // $('.error-code-product').text('Sản phẩm đã tồn tại.');
                let productInventory = codeHidden.parents('tr').find('.productInventory');

                var inventory = codeHidden.parents('tr').find(".productInventory").val();
                var quantity = codeHidden.parents('tr').find('.outputQuantity').val();
                if (InventoryTransfer.checkInventory2(quantity, inventory) == false) {
                    codeHidden.parents('tr').find('.error-output-quantity').text('Vượt quá số lượng');
                } else {
                    codeHidden.parents('tr').find('.error-output-quantity').text('');
                }
                $('.error-code-product').text('');
            }
        });
        if (flag == true) {
            let stt = 1 + $('#table-product > tbody > tr').length;
            let sum = 0;
            $.ajax({
                url: laroute.route('admin.inventory-transfer.get-product-child-by-code'),
                method: "POST",
                dataType: "JSON",
                data: {
                    code: codeInput,
                    warehouseOutput: $('#warehouse-output').val()
                },
                success: function (data) {
                    if (data == "") {
                        $('.error-code-product').css('color', 'red');
                        $('.error-code-product').text('Mã sản phẩm không hợp lệ');
                    } else {
                        var option = "";
                        option += '<option value="' + data['unitExists']['unit_id'] + '">' + '' + data['unitExists']['name'] + '</option>';
                        $.each(data['unit'], function (index, element) {
                            option += '<option value="' + index + '">' + element + '</option>';
                        });
                        $('.error-code-product').text('');
                        let $_tpl = $('#product-childs').html();
                        let tpl = $_tpl;
                        tpl = tpl.replace(/{stt}/g, stt);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{productInventory}/g, data['product_inventory']);
                        tpl = tpl.replace(/{outputQuantity}/g, 0);
                        tpl = tpl.replace(/{option}/g, option);
                        $('#table-product > tbody').append(tpl);

                        o.val('');
                        o.focus();
                        $('.unit').select2();
                        $('.outputQuantity').ForceNumericOnly();
                    }
                }
            });
        }
    }
});

function checkErr() {
    let f = true;
    $.each($('.span-err'), function () {
        if ($(this).text() != '') {
            f = false;
        }
    });
    return f;
}

$('.btn-save').click(function () {
    if (checkInput() == true && checkErr() == true && check == true) {
        let stt = $('#table-product > tbody > tr').length;
        var statusss = $('.active').find('input[name="options"]').val();
        if (stt < 1) {
            $('.error-product').css('color', 'red').text('Vui lòng thêm sản phẩm');
        } else {
            var arrayProducts = [];
            $.each($('#table-product tbody tr'), function () {
                var code = $(this).find("td input[name='hiddencode[]']").val();
                var unit = $(this).find("td .unit").val();
                var quantity = $(this).find("td input.outputQuantity").val();
                arrayProducts.push(code, unit, quantity);
            });
            $.ajax({
                url: laroute.route('admin.inventory-transfer.submit-edit'),
                method: "POST",
                data: {
                    warehouse_to: $('#warehouse-input').val(),
                    warehouse_from: $('#warehouse-output').val(),
                    transfer_code: $('#code-inventory').val(),
                    transfer_at: $('#day-output').val(),
                    status: statusss,
                    note: $('#note').val(),
                    arrayProducts: arrayProducts,
                    approved_at: $('#day-input').val(),
                    id: $('#idHidden').val()
                },
                success: function () {
                    swal("Cập nhật phiếu chuyển kho thành công", "", "success");
                    location.reload();
                }
            })
        }
    }
});
$('#warehouse-input').change(function () {
    if ($('#warehouse-input').val() != '') {
        $('.error-warehouse-input').text('');
    }
});
$('#btn-save-draft').click(function () {
    if (checkInput() == true && checkErr() == true && check == true) {
        let stt = $('#table-product > tbody > tr').length;
        var statusss = $('.active').find('input[name="options"]').val();
        if (stt < 1) {
            $('.error-product').css('color', 'red').text('Vui lòng thêm sản phẩm');
        } else {
            var arrayProducts = [];
            $.each($('#table-product tbody tr'), function () {
                var code = $(this).find("td input[name='hiddencode[]']").val();
                var unit = $(this).find("td .unit").val();
                var quantity = $(this).find("td input.outputQuantity").val();
                arrayProducts.push(code, unit, quantity);
            });
            $.ajax({
                url: laroute.route('admin.inventory-transfer.submit-edit'),
                method: "POST",
                data: {
                    warehouse_to: $('#warehouse-input').val(),
                    warehouse_from: $('#warehouse-output').val(),
                    transfer_code: $('#code-inventory').val(),
                    transfer_at: $('#day-output').val(),
                    status: 'draft',
                    note: $('#note').val(),
                    arrayProducts: arrayProducts,
                    approved_at: $('#day-input').val(),
                    id: $('#idHidden').val()
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.status == true) {
                        swal("Lưu nháp phiếu chuyển kho thành công", "", "success");
                        location.reload();
                    } else {
                        swal({
                            title: 'Cập nhật phiếu chuyển kho thất bại',
                            text: "",
                            type: 'warning',
                            confirmButtonText: 'Ok',
                        })
                    }
                }
            })
        }
    }
});

$('.unit').select2();

var InventoryTransfer = {
    cong: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.outputQuantity');
        $(o).parents('td').find('.outputQuantity').val(parseInt(inputNumberProduct.val()) + 1);
        changeOutputQuantity(inputNumberProduct);
        InventoryTransfer.checkInventory(inputNumberProduct);
    },
    tru: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.outputQuantity');
        if (inputNumberProduct.val() > 0) {
            $(o).parents('td').find('.outputQuantity').val(parseInt(inputNumberProduct.val()) - 1);
            changeOutputQuantity(inputNumberProduct);
        }
        InventoryTransfer.checkInventory(inputNumberProduct);
    },
    checkInventory: function (o) {
        var inventory = $(o).parents('tr').find(".productInventory").val();
        var quantity = $(o).parents('td').find('.outputQuantity').val();
        var flag = true;
        if (parseInt(quantity) > parseInt(inventory)) {
            flag = false;
        } else {
            flag = true;
        }
        if (flag == false) {
            check = false;
            $(o).parents('tr').find(".error-output-quantity").text('Vượt quá số lượng');
        } else {
            check = true;
            $(o).parents('tr').find(".error-output-quantity").text('');
        }
    },
    checkInventory2: function (quantity, inventory) {
        // var inventory = $(o).parents('tr').find(".product-inventory").val();
        // var quantity = $(o).parents('td').find('.number-product').val();
        var flag = true;
        if (parseInt(quantity) > parseInt(inventory)) {
            flag = false;
            check = false;
        } else {
            flag = true;
            check = true;
        }
        return flag;
    }
}
jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };
$('.outputQuantity').ForceNumericOnly();
$.getJSON(laroute.route('translate'), function (json) {
    $('#list-product').select2({
        width: '100%',
        placeholder: json["Chọn sản phẩm"],
        ajax: {
            url:laroute.route('admin.inventory-input.getProductChildOptionPage'),
            data: function (params) {
                return {
                    keyword: params.term,
                    page: params.page || 1,
                };
            },
            method: "POST",
            dataType: 'json',
            processResults: function (data) {
                data.page = data.current_page || 1;
                return {
                    results: data.data.map(function (item) {
                        return {
                            id: item.product_child_id,
                            text: item.product_child_name
                        };
                    }),
                    pagination: {
                        more: data.current_page + 1
                    }
                };
            },
        }
    }).on("select2:select", function (e) {
        let id = e.params.data.id;
        let stt = 1 + $('#table-product > tbody > tr').length;
        let flag = true;
        if (id != '') {
            $.ajax({
                url: laroute.route('admin.inventory-transfer.get-product-child-by-id'),
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    warehouseOutput: $('#warehouse-output').val()
                },
                success: function (data) {
                    console.log(data)
                    $.each($('#table-product tbody tr'), function () {
                        let codeHidden = $(this).find("td input[name='hiddencode[]']");
                        let codeExists = codeHidden.val();
                        var code = data['product']['product_code'];
                        if (codeExists == code) {
                            flag = false;
                            let valueNumberProduct = codeHidden.parents('tr').find('.outputQuantity').val();
                            let numbers = parseInt(valueNumberProduct) + 1;
                            codeHidden.parents('tr').find('.outputQuantity').val(numbers);
                            quantityProduct();

                            var inventory = codeHidden.parents('tr').find(".productInventory").val();
                            var quantity = codeHidden.parents('tr').find('.outputQuantity').val();
                            if (InventoryTransfer.checkInventory2(quantity, inventory) == false) {
                                codeHidden.parents('tr').find('.error-output-quantity').text('Vượt quá số lượng');
                            } else {
                                codeHidden.parents('tr').find('.error-output-quantity').text('');
                            }
                        }
                    });
                    if (flag == true) {
                        var option = "";
                        $.each(data['units'], function (index, element) {
                            if (data['unitExists']['unit_id'] == index) {
                                option += '<option selected value="' + index + '">' + element + '</option>';
                            } else {
                                option += '<option value="' + index + '">' + element + '</option>';
                            }
                        });
                        let $_tpl = $('#product-childs').html();
                        let tpl = $_tpl;
                        tpl = tpl.replace(/{stt}/g, stt);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{option}/g, option);
                        tpl = tpl.replace(/{productInventory}/g, data['productInventory']);
                        tpl = tpl.replace(/{outputQuantity}/g, 0);
                        $('#table-product > tbody').append(tpl).parents('tr').find('.unit').empty();
                        // $(".outputQuantity").TouchSpin({
                        //     min: 0,
                        //     max: 100000,
                        // });
                        $('.unit').select2();
                        quantityProduct();
                        $('.outputQuantity').ForceNumericOnly();
                    }
                }
            });
        }
    });
});