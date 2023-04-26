$('.m_selectpicker').select2();
$('.rdo').click(function () {
    $('.rdo').attr('class', 'btn btn-default rdo');
    $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
});

$('.errs').css('color', 'red');
var check = true;
// $('#warehouse').select2();
$('#created-at').datepicker({dateFormat: "yy-mm-dd"});
quantityProduct();

function changeOutputQuantity(o) {
    $.getJSON(laroute.route('translate'), function (json) {
        let values = parseInt($(o).val());
        if (values > 0) {
            $(o).val(values);

        } else {
            $(o).val(0);
        }
        $('.errs').css('color', 'red');
        let productInventory = $(o).parents('tr').find('.product-inventory').val();
        if (values > parseInt(productInventory)) {
            $(o).parents('td').find('.error-output-quantity').text(json['Vượt quá số lượng']);
            check = false;
        } else {
            $(o).parents('td').find('.error-output-quantity').empty();
            check = true;
            saveProduct();
        }
        // quantityProduct();
        // InventoryOutput.totalMoneyEverProduct(o);
        // InventoryOutput.totalMoneyAllProduct();
    });
}

function deleteProductInList(o,idDetail = null) {
    $.getJSON(laroute.route('translate'), function (json) {
        // $(o).closest('tr').remove();
        // let table = $('#table-product > tbody tr').length;
        // let a = 1;
        // $.each($('.stt'), function () {
        //     $(this).text(a++);
        // });
        //
        // InventoryOutput.totalMoneyEverProduct(o);
        // InventoryOutput.totalMoneyAllProduct();
        // quantityProduct();

        swal({
            title: json['Xoá sản phẩm'],
            text: json["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: json['Xóa'],
            cancelButtonText: json['Hủy'],

        }).then(function(result) {
            if (result.value) {
                if(idDetail != null){
                    $.ajax({
                        url: laroute.route('admin.inventory-output.delete-product'),
                        method: "POST",
                        data: {
                            inventory_output_detail_id: idDetail,
                        },
                        success: function (res) {
                            if (res.error == false){
                                $(o).closest('tr').remove();
                                saveProduct();
                                swal(res.message, "", "success");
                            } else {
                                swal(res.message, "", "error");
                            }
                        }
                    })
                } else {
                    $(o).closest('tr').remove();
                    let table = $('#table-product > tbody tr').length;
                    let a = 1;
                    $.each($('.stt'), function () {
                        $(this).text(a++);
                    });
                    sumNumberProduct(0);
                    totalMoney();
                }
            }
        });
    });
}

function quantityProduct() {
    var sum = 0;
    $.each($('.outputQuantity'), function () {
        sum += parseInt($(this).val());
    });
    $('#total-product').val(sum);
    $('#total-product-text').text(sum);
}

// $('#list-product').select2({
// }).on("select2:select", function (e) {
//     // $(this).empty();
//     let id = e.params.data.id;
//     let stt = 1 + $('#table-product > tbody > tr').length;
//
//     let flag = true;
//
//     if (id != '') {
//         $.ajax({
//             url: laroute.route('admin.inventory-output.get-product-child-by-id'),
//             method: "POST",
//             dataType: "JSON",
//             data: {
//                 id: id,
//                 warehouse: $('#warehouse').val()
//             },
//             success: function (data) {
//                 $.each($('#table-product tbody tr'), function () {
//                     let codeHidden = $(this).find("td input[name='hiddencode[]']");
//                     let codeExists = codeHidden.val();
//                     var code = data['product']['product_code'];
//                     if (codeExists == code) {
//                         flag = false;
//                         let valueNumberProduct = codeHidden.parents('tr').find('.outputQuantity').val();
//                         let numbers = parseInt(valueNumberProduct) + 1;
//                         codeHidden.parents('tr').find('.outputQuantity').val(numbers);
//                         let productInventory = codeHidden.parents('tr').find('.product-inventory');
//
//                         let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val();
//                         let totalMoney = cost.replace(new RegExp('\\,', 'g'), '') * parseInt(numbers);
//                         codeHidden.parents('tr').find('.total-money-product').text(Number(totalMoney).toFixed(decimal_number))
//
//                         if (parseInt(codeHidden.parents('tr').find('.outputQuantity').val()) > parseInt(productInventory.val())) {
//                             $('.errs').css('color', 'red');
//                             productInventory.parents('tr').find('.error-output-quantity').text('Vượt quá số lượng');
//                             check = false;
//                         } else {
//                             productInventory.parents('td').find('.error-output-quantity').text('');
//                             check = true;
//                         }
//                         quantityProduct();
//                         InventoryOutput.totalMoneyAllProduct();
//
//                     }
//                 });
//                 if (flag == true) {
//                     var option = "";
//                     option += ('<option value="' + data['unitExists']['unit_id'] + '">' +
//                         '' + data['unitExists']['name'] + '</option>');
//                     $.each(data['unit'], function (index, element) {
//                         option += ('<option value="' + index + '">' + element + '</option>');
//                     });
//
//                     let $_tpl = $('#product-childs').html();
//                     let tpl = $_tpl;
//                     tpl = tpl.replace(/{stt}/g, stt);
//                     tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
//                     tpl = tpl.replace(/{code}/g, data['product']['product_code']);
//                     tpl = tpl.replace(/{productInventory}/g, data['productInventory']);
//                     tpl = tpl.replace(/{outputQuantity}/g, 0);
//                     tpl = tpl.replace(/{cost}/g, formatNumber2(data['product']['cost']));
//                     tpl = tpl.replace(/{price}/g, formatNumber2(data['product']['price']));
//                     // tpl = tpl.replace(/{totalMoney}/g, formatNumber2(data['product']['cost']));
//                     tpl = tpl.replace(/{totalMoney}/g, 0);
//                     tpl = tpl.replace(/{option}/g, option);
//                     $('#table-product > tbody').append(tpl);
//
//                     quantityProduct();
//                     $('.unit').select2();
//                     InventoryOutput.totalMoneyAllProduct();
//                 }
//             }
//         });
//     }
// });
$('#product-code').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});
$('#product-code').bind("enterKey", function (e) {
    $.getJSON(laroute.route('translate'), function (json) {
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
                let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val();
                codeHidden.parents('tr').find('.outputQuantity').val(numbers);
                let productInventory = codeHidden.parents('tr').find('.product-inventory');

                if (parseInt(codeHidden.parents('tr').find('.outputQuantity').val()) > parseInt(productInventory.val())) {
                    $('.errs').css('color', 'red');
                    productInventory.parents('tr').find('.error-output-quantity').text(json['Vượt quá số lượng']);
                    check = false;
                } else {
                    productInventory.parents('td').find('.error-output-quantity').text('');
                    check = true;
                }
                let totalMoney = (cost.replace(new RegExp('\\,', 'g'), '')) * parseInt(numbers);
                codeHidden.parents('tr').find('.total-money-product').text(formatNumber2(totalMoney))
                quantityProduct();
                o.val('');
                o.focus();
                $('.error-code-product').text('');
                InventoryOutput.totalMoneyAllProduct();
                InventoryOutput.totalProduct();
            }
        });
        if (flag == true) {
            let stt = 1 + $('#table-product > tbody > tr').length;
            let sum = 0;
            $.ajax({
                url: laroute.route('admin.inventory-output.get-product-child-by-code'),
                method: "POST",
                dataType: "JSON",
                data: {
                    code: codeInput,
                    warehouse: $('#warehouse').val()
                },
                success: function (data) {
                    if (data == "") {
                        $('.error-code-product').css('color', 'red');
                        $('.error-code-product').text(json['Mã sản phẩm không hợp lệ']);
                    } else {
                        var option = "";
                        option += ('<option value="' + data['unitExists']['unit_id'] + '">' +
                            '' + data['unitExists']['name'] + '</option>');
                        $.each(data['unit'], function (index, element) {
                            option += ('<option value="' + index + '">' + element + '</option>');
                        });

                        let $_tpl = $('#product-childs').html();
                        let tpl = $_tpl;
                        tpl = tpl.replace(/{stt}/g, stt);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{number}/g, 0);
                        tpl = tpl.replace(/{outputQuantity}/g, 0);
                        // tpl = tpl.replace(/{cost}/g, formatNumber2(data['product']['cost'])).replace(new RegExp('\\,', 'g'), ',');
                        // tpl = tpl.replace(/{price}/g, formatNumber2(data['product']['price'])).replace(new RegExp('\\,', 'g'), ',');
                        // tpl = tpl.replace(/{total}/g, formatNumber2(data['product']['cost'])).replace(new RegExp('\\,', 'g'), ',');
                        tpl = tpl.replace(/{cost}/g, formatNumber2(data['product']['cost']));
                        tpl = tpl.replace(/{number}/g, 1);
                        tpl = tpl.replace(/{option}/g, option);
                        tpl = tpl.replace(/{total}/g, formatNumber2(data['product']['cost']));
                        tpl = tpl.replace(/{totalMoney}/g, 0);
                        tpl = tpl.replace(/{productInventory}/g, data['product_inventory']);
                        tpl = tpl.replace(/{option}/g, option);

                        $('#table-product > tbody').append(tpl);

                        // o.val('');
                        // o.focus();
                        // $('.error-code-product').text('');
                        // $('.unit').select2();
                        // InventoryOutput.totalMoneyAllProduct();
                        // InventoryOutput.totalProduct();
                        let money = parseInt($('input[name="totalMoneyProduct[]"]').val().replace(new RegExp('\\,', 'g'), ''));
                        $('#total-money').val(formatNumber2(money + parseInt(data['product']['cost'])));
                        $('.total-money').text(formatNumber2(money + parseInt(data['product']['cost'])));
                        if (stt == 1) {
                            $('#total-product').val(1);
                            $('.total-product').text(1 + " " + json["sản phẩm"]);
                        } else {
                            sumNumberProduct(0);
                        }
                        totalMoney();
                        o.val('');
                        o.focus();
                        $('.unit').select2();
                        $('.number-product').ForceNumericOnly();
                    }
                }
            });
        }
    })
});

function checkInput() {
    let flag = true;
    let errWarehouse = $('.error-warehouse');
    if ($('#warehouse').val() == "") {
        errWarehouse.text('Vui lòng chọn nhà kho');
        flag = false;
        $('#list-product').attr('disabled', true);
        $('#product-code').attr('disabled', true);
    } else {
        errWarehouse.text('');
        $('#list-product').attr('disabled', false);
        $('#product-code').attr('disabled', false);
    }
    // InventoryOutput.removeAllProduct();
    return flag;
}

$('#warehouse').change(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#total-product-text').text('0');
        $('.total-money').text('0');
        $('tbody').empty();
        $('#list-product').empty();
        $('#total-product').val(0);
        $('#list-product').append('<option value="">'+json['Chọn sản phẩm']+'</option>');
        if ($('#warehouse').val() != '') {
            $.ajax({
                url: laroute.route('admin.inventory-output.get-product-child-by-warehouse'),
                method: "POST",
                data: {
                    warehouse_id: $('#warehouse').val()
                },
                success: function (data) {
                    if (data != '') {
                        $('#list-product').empty();
                        $('#list-product').append('<option value="">'+json['Chọn sản phẩm']+'</option>');
                        $.each(data, function (key, value) {
                            $('#list-product').append('<option value="' + key + '">' + value + '</option>');
                        })
                    }

                }
            })
        }
    })
});
$('.btn-save').click(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        if (checkInput() == true && check == true) {
            let stt = $('#table-product > tbody > tr').length;
            var statusss = $('.active').find('input[name="options"]').val();
            if (stt < 1) {
                $('.error-product').text(json['Vui lòng thêm sản phẩm']);
            } else {
                var arrayProducts = [];
                // $.each($('#table-product tbody tr'), function () {
                //     var code = $(this).find("td input[name='hiddencode[]']").val();
                //     var unit = $(this).find("td .unit").val();
                //     var outputQuantity = $(this).find("td input.outputQuantity").val();
                //     arrayProducts.push(code, unit, outputQuantity);
                // });
                $.ajax({
                    url: laroute.route('admin.inventory-output.submit-edit'),
                    method: "POST",
                    data: {
                        id: $('#idHidden').val(),
                        warehouse_id: $('#warehouse').val(),
                        po_code: $('#code-inventory').val(),
                        status: statusss,
                        note: $('#note').val(),
                        arrayProducts: arrayProducts,
                        created_at: $('#created-at').val(),
                        type: $('#type').val()
                    },
                    success: function (res) {
                        // swal("Cập nhật phiếu xuất thành công", "", "success");
                        // location.reload();
                        if (res.status == true) {
                            swal(json["Cập nhật phiếu xuất thành công"], "", "success").then(function () {
                                window.location.href = laroute.route('admin.product-inventory');
                            });
                        } else {
                            swal(res.message, "", "error");
                        }
                    }
                })
            }
        }
    })
});
$('#btn-save-draft').click(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        if (checkInput() == true && check == true) {
            let stt = $('#table-product > tbody > tr').length;
            if (stt < 1) {
                $('.error-product').text(json['Vui lòng thêm sản phẩm']);
            } else {
                var arrayProducts = [];
                $.each($('#table-product tbody tr'), function () {
                    var code = $(this).find("td input[name='hiddencode[]']").val();
                    var unit = $(this).find("td .unit").val();
                    var outputQuantity = $(this).find("td input.outputQuantity").val();
                    arrayProducts.push(code, unit, outputQuantity);
                });
                $.ajax({
                    url: laroute.route('admin.inventory-output.submit-edit'),
                    method: "POST",
                    data: {
                        id: $('#idHidden').val(),
                        warehouse_id: $('#warehouse').val(),
                        po_code: $('#code-inventory').val(),
                        status: 'draft',
                        note: $('#note').val(),
                        arrayProducts: arrayProducts,
                        created_at: $('#created-at').val(),
                        type: $('#type').val()
                    },
                    success: function () {
                        swal(json["Cập nhật phiếu xuất thành công"], "", "success");
                        location.reload();
                    }
                })
            }
        }
    })
});

function onKeyDownInput(o) {
    $(o).on('keydown', function (e) {
        -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110])
        || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true))
        && (!0 === e.ctrlKey || !0 === e.metaKey)
        || 35 <= e.keyCode && 40 >= e.keyCode
        || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode)
        && e.preventDefault()
    });
}

var InventoryOutput = {
    cong: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.number-product');
        $(o).parents('td').find('.number-product').val(parseInt(inputNumberProduct.val()) + 1);
        // InventoryOutput.totalProduct();
        // InventoryOutput.totalMoneyEverProduct(inputNumberProduct);
        // InventoryOutput.totalMoneyAllProduct();
        // changeOutputQuantity(inputNumberProduct);
        saveProduct();
    },
    tru: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.number-product');
        if (inputNumberProduct.val() > 0) {
            $(o).parents('td').find('.number-product').val(parseInt(inputNumberProduct.val()) - 1);
            if (inputNumberProduct.val() == 0) {
                inputNumberProduct.val(1);
            }
            // InventoryOutput.totalProduct();
            // InventoryOutput.totalMoneyEverProduct(inputNumberProduct);
            // InventoryOutput.totalMoneyAllProduct();
            // changeOutputQuantity(inputNumberProduct);
        }
        if (inputNumberProduct.val()==1){
            inputNumberProduct.val(0);
            // InventoryOutput.totalProduct();
            // InventoryOutput.totalMoneyEverProduct(inputNumberProduct);
            // InventoryOutput.totalMoneyAllProduct();
            // changeOutputQuantity(inputNumberProduct);
        }
        saveProduct();
    },
    totalProduct: function () {
        let totalQuantity = 0;
        $.each($('.outputQuantity'), function () {
            let quantity = $(this).val();
            totalQuantity += parseInt(quantity);
        });
        $('#total-product-text').text(totalQuantity);
    },
    totalMoneyEverProduct: function (o) {
        let cost = $(o).parents('tr').find("input[name='cost-product-child']").val().replace(new RegExp('\\,', 'g'), '');
        let sl = $(o).parents('tr').find(".number-product").val().replace(new RegExp('\\,', 'g'), '');
        $(o).parents('tr').find(".total-money-product").text(Number(cost * sl).toFixed(decimal_number));
    },
    totalMoneyAllProduct: function () {
        let total = 0;
        let arrSum = [];
        $.each($('.total-money-product'), function () {
            let valAttr = $(this).text().replace(new RegExp('\\,', 'g'), '');
            total += Number(valAttr);
        });
        $('.total-money').text(formatNumber2(total));
    },
    formatQuantity: function (num) {
        // var txt = "#div-name-1234-characteristic:561613213213";
        var numb = num.match(/\d/g);
        numb = numb.join("");
        return numb;
    },

    showPopup : function () {
        $.ajax({
            url:laroute.route('admin.inventory-output.show-popup-add-product'),
            method:"POST",
            data:{
            },
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-add-inventory-product').modal('show');
                }
            }
        });
    },

    fileName: function () {
        var fileNamess = $('input[type=file]').val();
        $('#show').val(fileNamess);
    },

    addInventory: function(){
        // mApp.block(".modal-body", {
        //     overlayColor: "#000000",
        //     type: "loader",
        //     state: "success",
        //     message: "Xin vui lòng chờ..."
        // });

        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('inventory_output_id', $('#idHidden').val());
        $.ajax({
            url: laroute.route("admin.inventory-output.submit-add-product"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {

                if (res.error == false) {
                    if (res.countError != 0){
                        $('#form-data-error').empty();
                        var n = 0;
                        $.map(res.dataError, function (val) {
                            var tpl = $('#tpl-data-error').html();
                            tpl = tpl.replace(/{keyNumber}/g, n);
                            tpl = tpl.replace(/{product_code}/g, val.product_code);
                            tpl = tpl.replace(/{quantity}/g, val.quantity);
                            tpl = tpl.replace(/{price}/g, val.price);
                            tpl = tpl.replace(/{barcode}/g, val.barcode);
                            tpl = tpl.replace(/{serial}/g, val.serial);
                            tpl = tpl.replace(/{error_message}/g, val.error_message);
                            n = n + 1;
                            $('#form-data-error').append(tpl);
                        });
                        $("#form-data-error").submit();
                    }
                    $('#popup-add-inventory-product').modal('hide');
                    swal(res.message, "", "success").then(function(){
                        // setTimeout(function(){
                        //     location.reload();
                        // }, 3000);
                        InventoryOutput.getListProductInput();

                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function(res){
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(mess_error,'', "error");
            }
        });
    },

    getListProductInput : function(){
        $.ajax({
            url:laroute.route('admin.inventory-output.get-list-product-input'),
            method:"POST",
            data: {
                inventory_output_id : $('#idHidden').val()
            },
            success:function (data) {
                if(data.error == false){
                    $('.block-list-product-main').empty();
                    $('.block-list-product-main').append(data.view);
                    // $('select').select2();
                    quantityProduct();
                }
            }
        });
    },

    addSerialProduct : function(event,product_code,inventory_output_detail_id){
        var serial = $('#input_product_'+inventory_output_detail_id).val().replace(/\s/g, '').length;
        if(event.keyCode == 13 ){
            $.ajax({
                url:laroute.route('admin.inventory-output.add-serial-product'),
                method:"POST",
                data: {
                    product_code : product_code,
                    inventory_output_detail_id : inventory_output_detail_id,
                    serial : $('#input_product_'+inventory_output_detail_id).val(),
                    warehouse_id: $('#warehouse').val(),
                },
                success:function (data) {
                    $('#input_product_'+inventory_output_detail_id).val('');
                    if(data.error == false){
                        $('.sum_'+inventory_output_detail_id).trigger('click');
                        InventoryOutput.getListSerialDetail(inventory_output_detail_id);
                        $('#input_product_'+inventory_output_detail_id).focus();
                    } else {
                        swal(data.message,'', "error");
                    }
                },
                error: function(res){
                    $('#input_product_'+inventory_output_detail_id).val('');
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(mess_error,'', "error");
                }
            });
        }
    },

    getListSerialDetail: function(inventory_output_detail_id){
        $.ajax({
            url:laroute.route('admin.inventory-output.get-list-serial-detail'),
            method:"POST",
            data: {
                inventory_output_detail_id : inventory_output_detail_id,
            },
            success:function (data) {
                if(data.error == false){
                    $('.block_tr_'+inventory_output_detail_id).empty();
                    $('.block_tr_'+inventory_output_detail_id).append(data.view);
                    $('#input_product_'+inventory_output_detail_id).focus();
                } else {
                    swal(data.message,'', "error");
                }
            },
            error: function(res){
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(mess_error,'', "error");
            }
        });
    },

    removeSerial : function(detailSerialId,inventory_output_detail_id){
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Xoá số serial'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],

            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url:laroute.route('admin.inventory-output.remove-serial'),
                        method:"POST",
                        data:{
                            inventory_output_detail_serial_id : detailSerialId
                        },
                        success:function (data) {
                            if(data.error == false){
                                InventoryOut.getListSerial();
                                $('.minus_'+inventory_output_detail_id).trigger('click');
                                InventoryOutput.getListSerialDetail(inventory_output_detail_id);

                                swal(data.message,'', "success");
                            } else {
                                swal(data.message,'', "error");
                            }
                        }
                    });
                }
            });
        });
    },

    removeAllProduct : function(){
        $.ajax({
            url:laroute.route('admin.inventory-output.remove-all-product'),
            method:"POST",
            data:{
                inventory_output_id : $('#idHidden').val()
            },
            success:function (data) {
                if(data.error == false){
                    InventoryOutput.getListProductInput();
                }
            }
        });
    }

};

var InventoryOut = {
    showPopupListSerial:function(inventory_output_detail_id){
        $.ajax({
            url:laroute.route('admin.inventory-output.show-popup-list-serial'),
            method:"POST",
            data:{
                inventory_output_detail_id : inventory_output_detail_id
            },
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-list-serial').modal('show');
                    InventoryOut.getListSerial();
                }
            }
        });
    },

    getListSerial : function(){
        $.ajax({
            url:laroute.route('admin.inventory-output.get-list-serial'),
            method:"POST",
            data: $('#form-list-serial').serialize()+'&type=edit',
            success:function (data) {
                if(data.error == false){
                    $('.block-list-serial').empty();
                    $('.block-list-serial').append(data.view);
                }
            }
        });
    },

    changePageSerial : function(page){
        $('#page_serial').val(page);
        InventoryOut.getListSerial();
    },
    removeSearchSerial : function(){
        $('#serial').val('');
        InventoryOut.changePageSerial(1);
    },
}

function totalMoneyEverProduct(o) {
    let cost = $(o).parents('tr').find("input[name='cost-product-child']").val().replace(new RegExp('\\,', 'g'), '');
    let sl = $(o).parents('tr').find(".number-product").val().replace(new RegExp('\\,', 'g'), '');
    $(o).parents('tr').find(".total-money-product").text(Number(cost * sl).toFixed(decimal_number));

}

function formatNumber2(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

InventoryOutput.totalProduct();
InventoryOutput.totalMoneyAllProduct();

// $('.outputQuantity').maskNumber({integer: true});

// $('.unit').select2();

// Numeric only control handler
jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
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
                console.log(data);
                data.page = data.current_page || 1;
                return {
                    results: data.data.map(function (item) {
                        return {
                            id: item.product_child_id,
                            text: item.product_code+' - '+item.product_child_name
                        };
                    }),
                    pagination: {
                        more: data.current_page + 1
                    }
                };
            },
        }
    }).on("select2:select", function (e) {
        // $(this).empty();
        let id = e.params.data.id;
        let stt = 1 + $('#table-product > tbody > tr.blockProductMain').length;
        let sum = 0;
        let flag = true;

        if (id != '') {
            $.ajax({
                url: laroute.route('admin.inventory-output.get-product-child-by-id'),
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    warehouse: $('#warehouse').val()
                },
                success: function (data) {
                    $.each($('#table-product tbody tr'), function () {
                        let codeHidden = $(this).find("td input[name='hiddencode[]']");
                        let codeExists = codeHidden.val();
                        var code = data['product']['product_code'];
                        if (codeExists == code) {
                            flag = false;
                            let valueNumberProduct = codeHidden.parents('tr').find('.outputQuantity').val();
                            let numbers = parseInt(valueNumberProduct) + 1;
                            codeHidden.parents('tr').find('.outputQuantity').val(numbers);
                            let productInventory = codeHidden.parents('tr').find('.product-inventory');

                            let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val();
                            let totalMoney = cost.replace(new RegExp('\\,', 'g'), '') * parseInt(numbers);
                            codeHidden.parents('tr').find('.total-money-product').text(Number(totalMoney).toFixed(decimal_number))

                            if (parseInt(codeHidden.parents('tr').find('.outputQuantity').val()) > parseInt(productInventory.val())) {
                                $('.errs').css('color', 'red');
                                productInventory.parents('tr').find('.error-output-quantity').text(json['Vượt quá số lượng']);
                                check = false;
                            } else {
                                productInventory.parents('td').find('.error-output-quantity').text('');
                                check = true;
                            }
                            quantityProduct();
                            InventoryOutput.totalMoneyAllProduct();

                        }
                    });
                    if (flag == true) {
                        var option = "";
                        option += ('<option value="' + data['unitExists']['unit_id'] + '">' +
                            '' + data['unitExists']['name'] + '</option>');
                        $.each(data['unit'], function (index, element) {
                            option += ('<option value="' + index + '">' + element + '</option>');
                        });

                        let $_tpl = $('#product-childs').html();
                        if(data['product']['inventory_management'] == 'serial'){
                            $_tpl = $('#product-childs-serial').html();
                        }
                        let tpl = $_tpl;
                        tpl = tpl.replace(/{stt}/g, stt);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{productInventory}/g, data['productInventory']);
                        tpl = tpl.replace(/{outputQuantity}/g, 0);
                        tpl = tpl.replace(/{cost}/g, formatNumber2(data['product']['cost']));
                        tpl = tpl.replace(/{price}/g, formatNumber2(data['product']['price']));
                        // tpl = tpl.replace(/{totalMoney}/g, formatNumber2(data['product']['cost']));
                        tpl = tpl.replace(/{totalMoney}/g, 0);
                        tpl = tpl.replace(/{option}/g, option);
                        $('#table-product > tbody').append(tpl);

                        new AutoNumeric.multiple('#id-child-' + data['product']['product_code'] + '', {
                            currencySymbol : '',
                            decimalCharacter : '.',
                            digitGroupSeparator : ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });

                        quantityProduct();
                        // $('.unit').select2();
                        // InventoryOutput.totalMoneyAllProduct();

                        saveProduct();

                    }
                }
            });
        }
    });

    $('#list-product-serial').select2({
        width: '100%',
        placeholder: json["Chọn sản phẩm theo số serial"],
        ajax: {
            url:laroute.route('admin.inventory-output.getProductChildSerialOptionPage'),
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
                            id: item.product_child_id+'-'+item.serial,
                            // id: item.product_child_id,
                            product_child_id: item.product_child_id,
                            text: item.serial+' - '+item.product_child_name,
                            serial : item.serial,
                            product_code : item.product_code,
                        };
                    }),
                    pagination: {
                        more: data.current_page + 1
                    }
                };
            },
        }
    }).on("select2:select", function (e) {
        let id = e.params.data.product_child_id;
        let serial = e.params.data.serial;
        let product_code = e.params.data.product_code;

        let stt = 1 + $('#table-product > tbody > tr.blockProductMain').length;
        let sum = 0;
        let flag = true;

        if (id != '') {
            $.ajax({
                url: laroute.route('admin.inventory-output.get-product-child-by-id'),
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    warehouse: $('#warehouse').val()
                },
                success: function (data) {
                    $.each($('#table-product tbody tr'), function () {
                        let codeHidden = $(this).find("td input[name='hiddencode[]']");
                        let codeExists = codeHidden.val();
                        var code = data['product']['product_code'];
                        if (codeExists == code) {
                            flag = false;
                            let valueNumberProduct = codeHidden.parents('tr').find('.outputQuantity').val();
                            let numbers = parseInt(valueNumberProduct) + 1;
                            codeHidden.parents('tr').find('.outputQuantity').val(numbers);
                            let productInventory = codeHidden.parents('tr').find('.product-inventory');

                            let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val();
                            let totalMoney = cost.replace(new RegExp('\\,', 'g'), '') * parseInt(numbers);
                            codeHidden.parents('tr').find('.total-money-product').text(Number(totalMoney).toFixed(decimal_number))

                            if (parseInt(codeHidden.parents('tr').find('.outputQuantity').val()) > parseInt(productInventory.val())) {
                                $('.errs').css('color', 'red');
                                productInventory.parents('tr').find('.error-output-quantity').text(json['Vượt quá số lượng']);
                                check = false;
                            } else {
                                productInventory.parents('td').find('.error-output-quantity').text('');
                                check = true;
                            }
                            quantityProduct();
                            InventoryOutput.totalMoneyAllProduct();

                        }
                    });
                    if (flag == true) {
                        var option = "";
                        option += ('<option value="' + data['unitExists']['unit_id'] + '">' +
                            '' + data['unitExists']['name'] + '</option>');
                        $.each(data['unit'], function (index, element) {
                            option += ('<option value="' + index + '">' + element + '</option>');
                        });

                        let $_tpl = $('#product-childs').html();
                        if(data['product']['inventory_management'] == 'serial'){
                            $_tpl = $('#product-childs-serial').html();
                        }
                        let tpl = $_tpl;
                        tpl = tpl.replace(/{stt}/g, stt);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{productInventory}/g, data['productInventory']);
                        tpl = tpl.replace(/{outputQuantity}/g, 0);
                        tpl = tpl.replace(/{cost}/g, formatNumber2(data['product']['cost']));
                        tpl = tpl.replace(/{price}/g, formatNumber2(data['product']['price']));
                        // tpl = tpl.replace(/{totalMoney}/g, formatNumber2(data['product']['cost']));
                        tpl = tpl.replace(/{totalMoney}/g, 0);
                        tpl = tpl.replace(/{option}/g, option);
                        $('#table-product > tbody').append(tpl);

                        new AutoNumeric.multiple('#id-child-' + data['product']['product_code'] + '', {
                            currencySymbol : '',
                            decimalCharacter : '.',
                            digitGroupSeparator : ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });

                        quantityProduct();
                        $('.unit').select2();
                        // InventoryOutput.totalMoneyAllProduct();

                    }

                    saveProduct(serial,product_code);
                }
            });
        }
    });

});

function saveProduct(serial = null,product_code = null){
    let stt = $('#table-product > tbody > tr').length;
    $.getJSON(laroute.route('translate'), function (json) {
        if (stt < 1) {
            $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        } else {
            var arrayProducts = [];
            $.each($('#table-product tbody tr.blockProductMain'), function () {
                var code = $(this).find("td input[name='hiddencode[]']").val();
                var unit = $(this).find("td .unit").val();
                var quantity = $(this).find("td input[name='number-product']").val();
                var currentPrice = $(this).find("td input[name='cost-product-child']").val();
                var totalMoney = stringToNumber(quantity) * stringToNumber(currentPrice);
                var total = $(this).find("td input[name='totalMoneyProduct[]']").val();
                // arrayProducts.push(code, unit, quantity, currentPrice, totalMoney, total);
                arrayProducts.push(code, unit, quantity, currentPrice, totalMoney);
            });
            $.ajax({
                url: laroute.route('admin.inventory-output.submit-edit-product'),
                method: "POST",
                data: {
                    arrayProducts: arrayProducts,
                    id: $('#idHidden').val(),
                    serial : serial,
                    product_code: product_code
                },
                success: function () {
                    // swal(json["Cập nhật phiếu nhập thành công"], "", "success");
                    // location.reload();
                    InventoryOutput.getListProductInput();
                }
            })
        }
    });
}


function stringToNumber(value){
    let valAttr = value.replace(new RegExp('\\,', 'g'), '');
    console.log(valAttr);
    return Number(valAttr);
    // return value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}
