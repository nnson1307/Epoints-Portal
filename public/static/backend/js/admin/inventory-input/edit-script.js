$('.errs').css('color', 'red');
$('#created-at').datepicker({dateFormat: "yy-mm-dd"});
$('.m_selectpicker').select2();

// $('#list-product').select2({
// }).on("select2:select", function (e) {
//     let id = e.params.data.id;
//     let stt = 1 + $('#table-product > tbody > tr').length;
//     let sum = 0;
//     let flag = true;
//     if (id != "") {
//         $.ajax({
//             url: laroute.route('admin.inventory-input.get-product-child-by-id'),
//             method: "POST",
//             dataType: "JSON",
//             data: {id: id},
//             success: function (data) {
//                 if (stt == 1) {
//                     $('#total-money').val(formatNumber(data['product']['cost']));
//                     $('.total-money').text(formatNumber(data['product']['cost']));
//                 }
//                 $.each($('#table-product tbody tr'), function () {
//                     let codeHidden = $(this).find("td input[name='hiddencode[]']");
//                     let codeExists = codeHidden.val();
//                     var code = data['product']['product_code'];
//                     if (codeExists == code) {
//                         flag = false;
//                         let valueNumberProduct = codeHidden.parents('tr').find('input[name="number-product"]').val();
//                         let numbers = parseInt(valueNumberProduct) + 1;
//                         codeHidden.parents('tr').find('input[name="number-product"]').val(numbers);
//                         let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val().replace(new RegExp('\\,', 'g'), '');
//                         codeHidden.parents('tr').find('.total-money-product').val(formatNumber(cost * numbers));
//                         codeHidden.parents('tr').find('.total-money-product2').text(formatNumber(cost * numbers));
//                         totalMoney();
//                     }
//                 });
//                 if (flag == true) {
//                     var option = "";
//                     option += '<option value="' + data['unitExists']['unit_id'] + '">' + '' + data['unitExists']['name'] + '</option>';
//                     $.each(data['unit'], function (index, element) {
//                         option += '<option value="' + index + '">' + element + '</option>';
//                     });
//                     let $_tpl = $('#product-childs').html();
//                     let tpl = $_tpl;
//                     tpl = tpl.replace(/{stt}/g, stt);
//                     tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
//                     tpl = tpl.replace(/{code}/g, data['product']['product_code']);
//                     tpl = tpl.replace(/{price}/g, formatNumber(data['product']['price']));
//                     tpl = tpl.replace(/{cost}/g, formatNumber(data['product']['cost']));
//                     tpl = tpl.replace(/{number}/g, 1);
//                     tpl = tpl.replace(/{option}/g, option);
//                     tpl = tpl.replace(/{total}/g, formatNumber(data['product']['cost']));
//                     $('#table-product > tbody').append(tpl);
//
//                     new AutoNumeric.multiple('#id-child-' + data['product']['product_code'] + '', {
//                         currencySymbol : '',
//                         decimalCharacter : '.',
//                         digitGroupSeparator : ',',
//                         decimalPlaces: decimal_number,
//                         eventIsCancelable: true
//                     });
//
//                     totalMoney();
//                     $('.unit').select2();
//                     $('.number-product').ForceNumericOnly();
//                 }
//             }
//         });
//         if (stt == 1) {
//             $('#total-product').val(1);
//             $('.total-product').text(1 + " " + "sản phẩm");
//             $('.error-product').text('');
//         } else {
//             sumNumberProduct(1);
//         }
//     }
// });

//sum quantity product
function sumNumberProduct(sum) {
    $.getJSON(laroute.route('translate'), function (json) {
        var totalProduct = 0;
        $('.number-product').each(function () {
            sum += Number($(this).val());
            totalProduct++;
        });
        $('#total-quantity').val(sum);
        $('#total-product').val(totalProduct);
        $('.total-quantity').text(sum);
        $('.total-product').text(totalProduct  + " " + json["sản phẩm"]);
    });
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

// total money all product.
function totalMoney() {
    let total = 0;
    let arrSum = [];
    $.each($('.total-money-product2'), function () {
        let valAttr = $(this).text().replace(new RegExp('\\,', 'g'), '');
        total += Number(valAttr);
    });
    // for (let i = 0; i < arrSum.length; i++) {
    //     total += parseInt(arrSum[i]);
    // }
    $('#total-money').val(Number(total).toFixed(decimal_number));
    $('.total-money').text(formatNumber(total));
}

function maskNumberPriceProductChild() {
    // $('input[name="cost-product-child"]').maskNumber({integer: true});
}

// $(".number-product").TouchSpin({});

function clickNumberProduct(o) {
    $(o).val(parseInt($(o).val()));
    if ($(o).val() == '' || $(o).val() == 0) {
        $(o).val(1);
    }
    // alert($(o).val());
    let cost = $(o).parents('tr').find('input[name="cost-product-child"]').val();
    let total = cost.replace(new RegExp('\\,', 'g'), '') * $(o).val();
    $(o).parents('tr').find('.total-money-product').val(Number(total).toFixed(decimal_number));
    // $(o).parents('tr').find('.total-money-product2').text(Number(total).toFixed(decimal_number));
    $(o).parents('tr').find('.total-money-product2').text(formatNumber(total));
    sumNumberProduct(0);
    // totalMoney();
    saveProduct();
}

function changeCost(o) {
    let cost = $(o).val().replace(new RegExp('\\,', 'g'), '');
    let number = $(o).parents('tr').find('.number-product').val();
    $(o).parents('tr').find('.total-money-product').val(Number(number * cost).toFixed(decimal_number));
    $(o).parents('tr').find('.total-money-product2').text(Number(number * cost).toFixed(decimal_number));
    totalMoney();
    saveProduct();
}

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
                let valueNumberProduct = codeHidden.parents('tr').find('input[name="number-product"]').val();
                let numbers = parseInt(valueNumberProduct) + 1;
                let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val().replace(new RegExp('\\,', 'g'), '');
                codeHidden.parents('tr').find('input[name="number-product"]').val(numbers);
                codeHidden.parents('tr').find('input[name="totalMoneyProduct[]"]').val(formatNumber(numbers * parseInt(cost.replace(new RegExp('\\,', 'g'), ''))));
                codeHidden.parents('tr').find('.total-money-product2').text(Number(cost * numbers).toFixed(decimal_number));
                totalMoney();
                o.val('');
                o.focus();
            }
        });
        if (flag == true) {
            let stt = 1 + $('#table-product > tbody > tr.blockProductMain').length;
            let sum = 0;
            $.ajax({
                url: laroute.route('admin.inventory-input.get-product-child-by-code'),
                method: "POST",
                dataType: "JSON",
                data: {code: codeInput},
                success: function (data) {
                    if (data == "") {
                        $('.error-code-product').text(json['Mã sản phẩm không hợp lệ']);
                    } else {
                        var option = "";
                        option += '<option value="' + data['unitExists']['unit_id'] + '">' + '' + data['unitExists']['name'] + '</option>';
                        $.each(data['unit'], function (index, element) {
                            option += '<option value="' + index + '">' + element + '</option>';
                        });

                        $('.error-code-product').text('');
                        if (data['inventoryInputDetail']['inventory_management'] == 'serial') {
                            let $_tpl = $('#product-childs-serial').html();
                        } else {
                            let $_tpl = $('#product-childs').html();
                        }

                        let tpl = $_tpl;

                        tpl = tpl.replace(/{stt}/g, stt);
                        tpl = tpl.replace(/{inventory_input_detail_id}/g, data['inventoryInputDetail']['inventory_input_detail_id']);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{price}/g, formatNumber(data['product']['price']));
                        tpl = tpl.replace(/{cost}/g, formatNumber(data['product']['cost']));
                        tpl = tpl.replace(/{number}/g, 1);
                        tpl = tpl.replace(/{option}/g, option);
                        tpl = tpl.replace(/{total}/g, formatNumber(data['product']['cost']));
                        $('#table-product > tbody').append(tpl);

                        let money = parseInt($('input[name="totalMoneyProduct[]"]').val().replace(new RegExp('\\,', 'g'), ''));
                        // $(".number-product").TouchSpin({});
                        $('#total-money').val(formatNumber(money + parseInt(data['product']['cost'])));
                        $('.total-money').text(formatNumber(money + parseInt(data['product']['cost'])));
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
            if (stt > 0) {
                $('.error-product').text('');
            }
        }
        sumNumberProduct(0);
    })
});
$('#product-code').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});

function deleteProductInList(o,idDetail = null) {
    $.getJSON(laroute.route('translate'), function (json) {
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
                        url: laroute.route('admin.inventory-input.delete-product'),
                        method: "POST",
                        data: {
                            inventory_input_detail_id: idDetail,
                        },
                        success: function (res) {
                            if (res.error == false){
                                swal(res.message, "", "success").then(function(){
                                    $(o).closest('tr').remove();
                                    $('.block_tr_'+idDetail).remove();
                                    let table = $('#table-product > tbody tr').length;
                                    let a = 1;
                                    $.each($('.stt'), function () {
                                        $(this).text(a++);
                                    });
                                    sumNumberProduct(0);
                                    totalMoney();
                                });
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

function checkInput() {

        var flag = true;
        var errWarehouse = $('.error-warehouse');
        var errSupplier = $('.error-supplier');
        if ($('#warehouse').val() == "") {
            $.getJSON(laroute.route('translate'), function (json) {
                errWarehouse.text(json["Vui lòng chọn nhà kho"]);
            });
            flag = false;
        } else {
            errWarehouse.text('');
        }
        if ($('#supplier').val() == "") {
            // errSupplier.text('Vui lòng chọn nhà cung cấp');
            // flag = false;
        } else {
            errSupplier.text('');
        }
        return flag;
}

$('.btn-save').click(function () {
    console.log(checkInput());
    if (checkInput() == true) {
        var statusss = $('.active').find('input[name="options"]').val();
        let stt = $('#table-product > tbody > tr').length;
        $.getJSON(laroute.route('translate'), function (json) {
        if (stt < 1) {
            $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        } else {
            var arrayProducts = [];
            // $.each($('#table-product tbody tr'), function () {
            //     var code = $(this).find("td input[name='hiddencode[]']").val();
            //     var unit = $(this).find("td .unit").val();
            //     var quantity = $(this).find("td input[name='number-product']").val();
            //     var currentPrice = $(this).find("td input[name='cost-product-child']").val();
            //     var quantityRecived = $(this).find("td input[name='number-product']").val();
            //     var total = $(this).find("td input[name='totalMoneyProduct[]']").val();
            //     arrayProducts.push(code, unit, quantity, currentPrice, quantityRecived, total);
            // });
            $.ajax({
                url: laroute.route('admin.inventory-input.submit-edit'),
                method: "POST",
                data: {
                    warehouse_id: $('#warehouse').val(),
                    supplier_id: $('#supplier').val(),
                    pi_code: $('#code-inventory').val(),
                    status: statusss,
                    note: $('#note').val(),
                    // arrayProducts: arrayProducts,
                    created_at: $('#created-at').val(),
                    type: $('#type').val(),
                    id: $('#idInventoryInput').val()
                },
                success: function (res) {
                    if (res.status == true){
                        swal(json["Cập nhật phiếu nhập thành công"], "", "success").then(function (){
                            window.location.href = laroute.route('admin.product-inventory');
                        });
                    } else {
                        swal(res.message, "", "error");
                    }

                }
            })
        }
    });
    }
});
$('.rdo').click(function () {
    $('.rdo').attr('class', 'btn btn-default rdo');
    $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
});
$('#btn-save-draft').click(function () {
    if (checkInput() == true) {
        let stt = $('#table-product > tbody > tr').length;
        $.getJSON(laroute.route('translate'), function (json) {
        if (stt < 1) {
            $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        } else {
            var arrayProducts = [];
            $.each($('#table-product tbody tr'), function () {
                var code = $(this).find("td input[name='hiddencode[]']").val();
                var unit = $(this).find("td .unit").val();
                var quantity = $(this).find("td input[name='number-product']").val();
                var currentPrice = $(this).find("td input[name='cost-product-child']").val();
                var quantityRecived = $(this).find("td input[name='number-product']").val();
                var total = $(this).find("td input[name='totalMoneyProduct[]']").val();
                arrayProducts.push(code, unit, quantity, currentPrice, quantityRecived, total);
            });
            $.ajax({
                url: laroute.route('admin.inventory-input.submit-edit'),
                method: "POST",
                data: {
                    warehouse_id: $('#warehouse').val(),
                    supplier_id: $('#supplier').val(),
                    pi_code: $('#code-inventory').val(),
                    status: "draft",
                    note: $('#note').val(),
                    arrayProducts: arrayProducts,
                    created_at: $('#created-at').val(),
                    type: $('#type').val(),
                    id: $('#idInventoryInput').val()
                },
                success: function () {
                    swal(json["Cập nhật phiếu nhập thành công"], "", "success");
                    location.reload();
                }
            })
        }
    });
    }
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

// $('.unit').select2();
var InventoryInput = {

    totalQuantity : function(){
        totalQuantity = 0;
        $.each($('#table-product tbody tr'), function () {
            totalQuantity += parseInt($(this).find("td input[name='number-product']").val());
        });

        $('.total-quantity').text(totalQuantity);
    },

    cong: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.number-product');
        $(o).parents('td').find('.number-product').val(parseInt(inputNumberProduct.val()) + 1);
        clickNumberProduct(inputNumberProduct);
        saveProduct();
        // InventoryInput.totalQuantity();
    },
    tru: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.number-product');
        if (inputNumberProduct.val() > 0) {
            $(o).parents('td').find('.number-product').val(parseInt(inputNumberProduct.val()) - 1);
            clickNumberProduct(inputNumberProduct);
            saveProduct();
            // InventoryInput.totalQuantity();
        }
    },

    showPopup : function () {
        $.ajax({
            url:laroute.route('admin.inventory-input.show-popup-add-product'),
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
        form_data.append('inventory_input_id', $('#idInventoryInput').val());
        $.ajax({
            url: laroute.route("admin.inventory-input.submit-add-product"),
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
                        InventoryInput.getListProductInput();

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

    removeSerial : function(detailSerialId,inventory_input_detail_id){
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
                        url:laroute.route('admin.inventory-input.remove-serial'),
                        method:"POST",
                        data:{
                            inventory_input_detail_serial_id : detailSerialId
                        },
                        success:function (data) {
                            if(data.error == false){
                                InventoryInput.getListSerial();
                                // InventoryInput.getListProductInput();
                                $('.minus_'+inventory_input_detail_id).trigger('click');
                                InventoryInput.getListSerialDetail(inventory_input_detail_id);

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

    showPopupListSerial:function(inventory_input_detail_id){
        $.ajax({
            url:laroute.route('admin.inventory-input.show-popup-list-serial'),
            method:"POST",
            data:{
                inventory_input_detail_id : inventory_input_detail_id
            },
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-list-serial').modal('show');
                    InventoryInput.getListSerial();
                }
            }
        });
    },

    getListSerial : function(){
        $.ajax({
            url:laroute.route('admin.inventory-input.get-list-serial'),
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
        InventoryInput.getListSerial();
    },
    removeSearchSerial : function(){
        $('#serial').val('');
        InventoryInput.changePageSerial(1);
    },

    getListProductInput : function(){
        $.ajax({
            url:laroute.route('admin.inventory-input.get-list-product-input'),
            method:"POST",
            data: {
                inventory_input_id : $('#idInventoryInput').val()
            },
            success:function (data) {
                if(data.error == false){
                    $('.block-list-product-main').empty();
                    $('.block-list-product-main').append(data.view);
                    // $('select').select2();
                }
            }
        });
    },

    addSerialProduct : function(event,product_code,inventory_input_detail_id){
        var serial = $('#input_product_'+inventory_input_detail_id).val().replace(/\s/g, '').length;
        if(event.keyCode == 13 ){
            $.ajax({
                url:laroute.route('admin.inventory-input.add-serial-product'),
                method:"POST",
                data: {
                    product_code : product_code,
                    inventory_input_detail_id : inventory_input_detail_id,
                    serial : $('#input_product_'+inventory_input_detail_id).val()
                },
                success:function (data) {
                    $('#input_product_'+inventory_input_detail_id).val('');
                    if(data.error == false){
                        // $('.block-list-product-main').empty();
                        // $('.block-list-product-main').append(data.view);
                        $('.add_'+inventory_input_detail_id).trigger('click');
                        InventoryInput.getListSerialDetail(inventory_input_detail_id);
                    } else {
                        swal(data.message,'', "error");
                    }
                },
                error: function(res){
                    $('#input_product_'+inventory_input_detail_id).val('');
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(mess_error,'', "error");
                }
            });
        }
    },

    getListSerialDetail: function(inventory_input_detail_id){
        $.ajax({
            url:laroute.route('admin.inventory-input.get-list-serial-detail'),
            method:"POST",
            data: {
                inventory_input_detail_id : inventory_input_detail_id,
            },
            success:function (data) {
                if(data.error == false){
                    $('.block_tr_'+inventory_input_detail_id).empty();
                    $('.block_tr_'+inventory_input_detail_id).append(data.view);
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
$('.number-product').ForceNumericOnly();

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
        let id = e.params.data.id;
        let stt = 1 + $('#table-product > tbody > tr.blockProductMain').length;
        let sum = 0;
        let flag = true;
        if (id != "") {
            $.ajax({
                url: laroute.route('admin.inventory-input.get-product-child-by-id'),
                method: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    if (stt == 1) {
                        $('#total-money').val(formatNumber(data['product']['cost']));
                        $('.total-money').text(formatNumber(data['product']['cost']));
                    }
                    $.each($('#table-product tbody tr'), function () {
                        let codeHidden = $(this).find("td input[name='hiddencode[]']");
                        let codeExists = codeHidden.val();
                        var code = data['product']['product_code'];
                        if (codeExists == code) {
                            flag = false;
                            let valueNumberProduct = codeHidden.parents('tr').find('input[name="number-product"]').val();
                            let numbers = parseInt(valueNumberProduct) + 1;
                            codeHidden.parents('tr').find('input[name="number-product"]').val(numbers);
                            let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val().replace(new RegExp('\\,', 'g'), '');
                            codeHidden.parents('tr').find('.total-money-product').val(formatNumber(cost * numbers));
                            codeHidden.parents('tr').find('.total-money-product2').text(formatNumber(cost * numbers));
                            totalMoney();
                        }
                    });
                    if (flag == true) {
                        var option = "";
                        console.log(data);
                        option += '<option value="' + data['unitExists']['unit_id'] + '">' + '' + data['unitExists']['name'] + '</option>';
                        $.each(data['unit'], function (index, element) {
                            option += '<option value="' + index + '">' + element + '</option>';
                        });

                        let $_tpl = $('#product-childs').html();
                        if(data['product']['inventory_management'] == 'serial'){
                            $_tpl = $('#product-childs-serial').html();
                        }

                        let tpl = $_tpl;
                        tpl = tpl.replace(/{stt}/g, stt);
                        // tpl = tpl.replace(/{inventory_input_detail_id}/g, data['inventoryInputDetail']['inventory_input_detail_id']);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{price}/g, formatNumber(data['product']['price']));
                        tpl = tpl.replace(/{cost}/g, formatNumber(data['product']['cost']));
                        tpl = tpl.replace(/{number}/g, 1);
                        tpl = tpl.replace(/{option}/g, option);
                        tpl = tpl.replace(/{total}/g, formatNumber(data['product']['cost']));
                        $('#table-product > tbody').append(tpl);

                        new AutoNumeric.multiple('#id-child-' + data['product']['product_code'] + '', {
                            currencySymbol : '',
                            decimalCharacter : '.',
                            digitGroupSeparator : ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });

                        // totalMoney();
                        // $('.unit').select2();
                        $('.number-product').ForceNumericOnly();

                        saveProduct();
                    }
                }
            });
            if (stt == 1) {
                $('#total-product').val(1);
                $('.total-product').text(1 + " " + json["sản phẩm"]);
                $('.error-product').text('');
            } else {
                sumNumberProduct(1);
            }
        }
    });
});

function saveProduct(){
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
                var quantityRecived = $(this).find("td input[name='number-product']").val();
                var total = $(this).find("td input[name='totalMoneyProduct[]']").val();
                arrayProducts.push(code, unit, quantity, currentPrice, quantityRecived, total);
            });
            $.ajax({
                url: laroute.route('admin.inventory-input.submit-edit-product'),
                method: "POST",
                data: {
                    arrayProducts: arrayProducts,
                    id: $('#idInventoryInput').val()
                },
                success: function () {
                    // swal(json["Cập nhật phiếu nhập thành công"], "", "success");
                    // location.reload();
                    InventoryInput.getListProductInput();
                }
            })
        }
    });
}
