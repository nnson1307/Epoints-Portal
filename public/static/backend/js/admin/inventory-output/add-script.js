$('#warehouse').select2();
$('.rdo').click(function () {
    $('.rdo').attr('class', 'btn btn-default rdo');
    $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
});
$('.errs').css('color', 'red');
$('#created-at').datepicker({format: 'dd/mm/yyyy'});
$('#created-at').datepicker('setDate', 'today');
// $('#warehouse').select2();
$('#list-product').attr('disabled', true);
$('#product-code').attr('disabled', true);
$('#list-product').select2({
// }).on("select2:select", function (e) {
//     let id = e.params.data.id;
//     let stt = 1 + $('#table-product > tbody > tr').length;
//     let sum = 0;
//     let flag = true;
//     if(id!=''){
//         $.ajax({
//             url: laroute.route('admin.inventory-output.get-product-child-by-id'),
//             method: "POST",
//             dataType: "JSON",
//             data: {id: id, warehouse: $('#warehouse').val()},
//             success: function (data) {
//                 $.each($('#table-product tbody tr'), function () {
//                     let codeHidden = $(this).find("td input[name='hiddencode[]']");
//                     let codeExists = codeHidden.val();
//                     var code = data['product']['product_code'];
//                     if (codeExists == code) {
//                         flag = false;
//                         let valueNumberProduct = codeHidden.parents('tr').find('input[name="number-product"]').val();
//                         let numbers = parseInt(valueNumberProduct) + 1;
//                         codeHidden.parents('tr').find('input[name="number-product"]').val(numbers);
//
//                         //Tính tổng tiền.
//                         let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val();
//                         let totalMoney2 = (cost.replace(new RegExp('\\,', 'g'), '')) * (numbers);
//                         codeHidden.parents('tr').find('.total-money-product').text(formatNumber2(Number(totalMoney2).toFixed(decimal_number)))
//
//
//                         var inventory = codeHidden.parents('tr').find(".product-inventory").val();
//                         var quantity = codeHidden.parents('tr').find('.number-product').val();
//                         if (InventoryOutput.checkInventory2(quantity, inventory) == false) {
//                             // if (parseInt(quantity) > 0) {
//                                 codeHidden.parents('tr').find('.err-quantity').text('Vượt quá số lượng');
//                             // } else {
//                             //     codeHidden.parents('tr').find('.err-quantity').text('');
//                             // }
//
//                         } else {
//                             codeHidden.parents('tr').find('.err-quantity').text('');
//                         }
//                         totalMoney();
//                     }
//                 });
//                 if (flag == true) {
//                     var productInventoryQuantt = 0;
//                     if (typeof data['productInventory'] === "undefined") {
//                         productInventoryQuantt = 0;
//                     } else {
//                         productInventoryQuantt = data['productInventory'];
//                     }
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
//                     tpl = tpl.replace(/{productInventory}/g, productInventoryQuantt);
//                     tpl = tpl.replace(/{code}/g, data['product']['product_code']);
//                     tpl = tpl.replace(/{number}/g, 0);
//                     tpl = tpl.replace(/{cost}/g, Number(data['product']['cost']).toFixed(decimal_number));
//                     tpl = tpl.replace(/{price}/g, Number(data['product']['price']).toFixed(decimal_number));
//                     tpl = tpl.replace(/{total}/g, 0);
//                     tpl = tpl.replace(/{option}/g, option);
//                     $('#table-product > tbody').append(tpl);
//
//                     $('.number-product').ForceNumericOnly();
//                     totalMoney();
//                     $('.unit').select2();
//                     $('.number-product').ForceNumericOnly();
//                 }
//             }
//         });
//     }
//     if (stt == 1) {
//         $('#total-product').val(0);
//         $('#total-product-text').text(0 + ' ' + 'sản phẩm');
//         $('.error-product').text('');
//     }

});

function deleteProductInList(o) {
    $(o).closest('tr').remove();
    let table = $('#table-product > tbody tr').length;
    let a = 1;
    $.each($('.stt'), function () {
        $(this).text(a++);
    });
    sumNumberProduct(o, 0);
    totalMoneyEverProduct(o);
    totalMoney();
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
}

function formatNumber2(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

var check = true;

function sumNumberProduct(o, sum) {
    let values = parseInt($(o).val());
    if (values > 0) {
        $(o).val(values);

    } else {
        $(o).val(0);
    }
    $('.number-product').each(function () {
        sum += Number($(this).val());
    });
    $('#total-product').val(sum);
    $('#total-product-text').text(sum + ' ' + 'sản phẩm');
    InventoryOutput.checkInventory(o);
    totalMoneyEverProduct(o);
    totalMoney();
}

function totalMoneyEverProduct(o) {
    let cost = $(o).parents('tr').find("input[name='cost-product-child']").val().replace(new RegExp('\\,', 'g'), '');
    let sl = $(o).parents('tr').find(".number-product").val().replace(new RegExp('\\,', 'g'), '');
    $(o).parents('tr').find(".total-money-product").text(Number(cost * sl).toFixed(decimal_number));
}

function checkInput() {
    let flag = true;
    let errWarehouse = $('.error-warehouse');
    $.getJSON(laroute.route('translate'), function (json) {
        if ($('#warehouse').val() == "") {
            errWarehouse.text(json['Vui lòng chọn nhà kho']);
            flag = false;
            $('#list-product').attr('disabled', true);
            $('#product-code').attr('disabled', true);
        } else {
            errWarehouse.text('');
            $('#list-product').attr('disabled', false);
            $('#product-code').attr('disabled', false);
        }
    });
    return flag;
}

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
                let valueNumberProduct = codeHidden.parents('tr').find('input[name="number-product"]').val();
                let numbers = parseInt(valueNumberProduct) + 1;
                let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val();
                codeHidden.parents('tr').find('input[name="number-product"]').val(numbers);
                o.val('');
                o.focus();
                // checkInventory(codeHidden.parents('tr').find('input[name="number-product"]'));
                let totalMoney = (cost.replace(new RegExp('\\,', 'g'), '')) * (numbers);
                codeHidden.parents('tr').find('.total-money-product').text(formatNumber2(Number(totalMoney).toFixed(decimal_number)))

                var inventory = codeHidden.parents('tr').find(".product-inventory").val();
                var quantity = codeHidden.parents('tr').find('.number-product').val();
                if (InventoryOutput.checkInventory2(quantity, inventory) == false) {
                    codeHidden.parents('tr').find('.err-quantity').text(json['Vượt quá số lượng']);
                } else {
                    codeHidden.parents('tr').find('.err-quantity').text('');
                }
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
                        $('.error-code-product').text(json['Mã sản phẩm không hợp lệ']);
                    } else {
                        var productInventoryQuantt = 0;
                        if (typeof data['product']['quantity'] === "undefined") {
                            productInventoryQuantt = 0;
                        } else {
                            productInventoryQuantt = data['product']['quantity'];
                        }
                        $('.error-code-product').text('');

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
                        tpl = tpl.replace(/{productInventory}/g, data['product_inventory']);
                        tpl = tpl.replace(/{number}/g, 0);
                        tpl = tpl.replace(/{cost}/g, formatNumber2(data['product']['cost'])).replace(new RegExp('\\,', 'g'), ',');
                        tpl = tpl.replace(/{price}/g, formatNumber2(data['product']['price'])).replace(new RegExp('\\,', 'g'), ',');
                        tpl = tpl.replace(/{total}/g, 0);
                        tpl = tpl.replace(/{option}/g, option);

                        $('#table-product > tbody').append(tpl);

                        $('.unit').select2();
                        o.val('');
                        o.focus();

                        totalMoney();
                        $('.number-product').ForceNumericOnly();
                    }
                }
            });
            if (stt > 0) {
                $('.error-product').text('');
            }
        }
        totalMoney();
    })
});
$('.btn-save').click(function () {

    if (checkInput() == true && check == true && checkErr() == true) {
        let stt = $('#table-product > tbody > tr').length;
        var statusss = $('.active').find('input[name="options"]').val();
        $.getJSON(laroute.route('translate'), function (json) {
        if (stt < 1) {
            $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        } else {
            var arrayProducts = [];
            $.each($('#table-product tbody tr'), function () {
                var code = $(this).find("td input[name='hiddencode[]']").val();
                var unit = $(this).find("td .unit").val();
                var quantity = $(this).find("td input[name='number-product']").val();
                arrayProducts.push(code, unit, quantity);
            });
            $.ajax({
                url: laroute.route('admin.inventory-output.submit-add'),
                method: "POST",
                data: {
                    warehouse_id: $('#warehouse').val(),
                    pi_code: $('#code-inventory').val(),
                    status: statusss,
                    note: $('#note').val(),
                    arrayProducts: arrayProducts,
                    created_at: $('#created-at').val(),
                    total: $('#total-product').val(),
                    type: $('#type').val()
                },
                success: function () {
                    swal(json["Thêm phiếu xuất thành công"], "", "success");
                    window.location = laroute.route('admin.product-inventory');
                }
            })
        }
    });
    }
});
$('#btn-save-draft').click(function () {
    if (checkInput() == true && check == true && checkErr() == true) {
        let stt = $('#table-product > tbody > tr').length;
        var statusss = $('.active').find('input[name="options"]').val();
        $.getJSON(laroute.route('translate'), function (json) {
            if (stt < 1) {
                $('.error-product').text(json['Vui lòng thêm sản phẩm']);
            } else {
                var arrayProducts = [];
                $.each($('#table-product tbody tr'), function () {
                    var code = $(this).find("td input[name='hiddencode[]']").val();
                    var unit = $(this).find("td .unit").val();
                    var quantity = $(this).find("td input[name='number-product']").val();
                    arrayProducts.push(code, unit, quantity);
                });
                $.ajax({
                    url: laroute.route('admin.inventory-output.submit-add'),
                    method: "POST",
                    data: {
                        warehouse_id: $('#warehouse').val(),
                        pi_code: $('#code-inventory').val(),
                        status: 'draft',
                        note: $('#note').val(),
                        arrayProducts: arrayProducts,
                        created_at: $('#created-at').val(),
                        total: $('#total-product').val(),
                        type: $('#type').val()
                    },
                    success: function () {
                        swal(json["Lưu nháp phiếu nhập thành công"], "", "success");
                        location.reload();
                    }
                })
            }
        });
    }
});
$('#warehouse').change(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#total-product-text').text('0');
        $('.total-money').text('0');
        $('tbody').empty();
        $('#list-product').empty();
        $('#list-product').append('<option value="">Chọn sản phẩm</option>');
        $('#total-product').val(0);
        $('#total-product-text').val(0 + ' ' + json['sản phẩm']);
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
                        $('#list-product').append('<option value="">Chọn sản phẩm</option>');
                        $.each(data, function (key, value) {
                            $('#list-product').append('<option value="' + key + '">' + value + '</option>');
                        })
                    }

                }
            })
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

function checkInventory(o) {
    $.getJSON(laroute.route('translate'), function (json) {
        var code = $(o).parents('tr').find("input[name='hiddencode[]']").val();
        $.ajax({
            url: laroute.route('admin.inventory-output.check-quantity-product-inventory'),
            method: "POST",
            data: {
                warehouse: $('#warehouse').val(),
                code: code,
                quantity: $(o).val()
            },
            dataType: "JSON",
            success: function (data) {
                if (data.status == 0) {
                    $('.err-quantity').css('color', 'red');
                    $(o).parents('td').find(".err-quantity").text(json['Vượt quá số lượng']);
                    check = false;
                } else {
                    $(o).parents('td').find(".err-quantity").text('');
                    check = true;
                }

            }
        });
    });
}

$('#btn-save-add-new').click(function () {
    if (checkInput() == true && check == true && checkErr() == true) {
        let stt = $('#table-product > tbody > tr').length;
        var statusss = $('.active').find('input[name="options"]').val();
        $.getJSON(laroute.route('translate'), function (json) {
        if (stt < 1) {
            $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        } else {
            var arrayProducts = [];
            $.each($('#table-product tbody tr'), function () {
                var code = $(this).find("td input[name='hiddencode[]']").val();
                var unit = $(this).find("td .unit").val();
                var quantity = $(this).find("td input[name='number-product']").val();
                arrayProducts.push(code, unit, quantity);
            });
            $.ajax({
                url: laroute.route('admin.inventory-output.submit-add'),
                method: "POST",
                data: {
                    warehouse_id: $('#warehouse').val(),
                    pi_code: $('#code-inventory').val(),
                    status: statusss,
                    note: $('#note').val(),
                    arrayProducts: arrayProducts,
                    created_at: $('#created-at').val(),
                    total: $('#total-product').val(),
                    type: $('#type').val()
                },
                success: function () {
                    swal(json["Thêm phiếu xuất thành công"], "", "success");
                    location.reload();
                }
            })
        }
    });
    }
});

var InventoryOutput = {
    cong: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.number-product');
        $(o).parents('td').find('.number-product').val(parseInt(inputNumberProduct.val()) + 1);
        clickNumberProduct(o);
        sumNumberProduct(inputNumberProduct, 0);
        totalMoneyEverProduct(o);
        totalMoney();
        // checkInventory(inputNumberProduct);
        InventoryOutput.checkInventory(inputNumberProduct);

    },
    tru: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.number-product');
        if (inputNumberProduct.val() > 0) {
            $(o).parents('td').find('.number-product').val(parseInt(inputNumberProduct.val()) - 1);

            clickNumberProduct(o);
            sumNumberProduct(inputNumberProduct, 0)
            totalMoneyEverProduct(o);
            totalMoney();
            InventoryOutput.checkInventory(inputNumberProduct);

        }
    },
    formatQuantity: function (num) {
        var numb = num.match(/\d/g);
        numb = numb.join("");
        return numb;
    },
    checkInventory: function (o) {
        var inventory = $(o).parents('tr').find(".product-inventory").val();
        var quantity = $(o).parents('td').find('.number-product').val();
        var flag = true;
        if (parseInt(quantity) > parseInt(inventory)) {
            flag = false;
        } else {
            flag = true;
        }
        if (flag == false) {
            check = false;
            // if (parseInt(quantity) > 0) {
                $(o).parents('tr').find(".err-quantity").text('Vượt quá số lượng');
            // } else {
            //     $(o).parents('tr').find(".err-quantity").text('');
            // }
        } else {
            check = true;
            $(o).parents('tr').find(".err-quantity").text('');
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

function totalMoney() {
    let total = 0;
    let arrSum = [];
    $.each($('.total-money-product'), function () {
        let valAttr = $(this).text().replace(new RegExp('\\,', 'g'), '');
        arrSum.push(valAttr);
    });
    
    for (let i = 0; i < arrSum.length; i++) {
        total += Number(arrSum[i]);
    }
    
    $('.total-money').text(Number(total).toFixed(decimal_number));
}

// function clickNumberProduct(o) {
//     let cost = $(o).parents('tr').find('input[name="cost-product-child"]').val();
//     let sl = $(o).parents('tr').find('input[name="number-product"]').val();
//     let total = cost.replace(new RegExp('\\,', 'g'), '') * sl;
//     $(o).parents('tr').find('.total-money-product').val(formatNumber(total));
//
//     $(o).parents('tr').find('#total-money-product').text(formatNumber(total));
//     $(o).parents('tr').find('.total-money-product').text(formatNumber2(total));
//     sumNumberProduct(0);
//     totalMoney();
// }
function clickNumberProduct(o) {
    let cost = $(o).parents('tr').find('input[name="cost-product-child"]').val();
    let total = cost.replace(new RegExp('\\,', 'g'), '') * $(o).val();
    $(o).parents('tr').find('.total-money-product').val(formatNumber(total));
    $(o).parents('tr').find('#total-money-product').text(formatNumber(total));
    sumNumberProduct(o, 0);
    totalMoney();
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

function checkErr() {
    let f = true;
    $.each($('.err-quantity'), function () {
        if ($(this).text() != '') {
            f = false;
        }
    });
    return f;
}

$.getJSON(laroute.route('translate'), function (json) {
    $('#list-product').select2({
        width: '100%',
        placeholder: json["Chọn sản phẩm"],
        ajax: {
            url:laroute.route('admin.inventory-output.getProductChildInventoryOutputOptionPage'),
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
                            code: item.product_code,
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
        let code = e.params.data.code;
        let stt = 1 + $('#table-product > tbody > tr').length;
        let sum = 0;
        let flag = true;
        if(id!=''){
            $.ajax({
                url: laroute.route('admin.inventory-output.get-product-child-by-id'),
                method: "POST",
                dataType: "JSON",
                data: {id: id, warehouse: $('#warehouse').val(),product_code:code},
                success: function (data) {
                    $.each($('#table-product tbody tr'), function () {
                        let codeHidden = $(this).find("td input[name='hiddencode[]']");
                        let codeExists = codeHidden.val();
                        var code = data['product']['product_code'];

                        if (codeExists == code) {
                            flag = false;
                            let valueNumberProduct = codeHidden.parents('tr').find('input[name="number-product"]').val();
                            let numbers = parseInt(valueNumberProduct) + 1;
                            codeHidden.parents('tr').find('input[name="number-product"]').val(numbers);

                            //Tính tổng tiền.
                            let cost = codeHidden.parents('tr').find('input[name="cost-product-child"]').val();
                            let totalMoney2 = (cost.replace(new RegExp('\\,', 'g'), '')) * (numbers);
                            codeHidden.parents('tr').find('.total-money-product').text(formatNumber2(Number(totalMoney2).toFixed(decimal_number)))


                            var inventory = codeHidden.parents('tr').find(".product-inventory").val();
                            var quantity = codeHidden.parents('tr').find('.number-product').val();
                            if (InventoryOutput.checkInventory2(quantity, inventory) == false) {
                                // if (parseInt(quantity) > 0) {
                                codeHidden.parents('tr').find('.err-quantity').text(json['Vượt quá số lượng']);
                                // } else {
                                //     codeHidden.parents('tr').find('.err-quantity').text('');
                                // }

                            } else {
                                codeHidden.parents('tr').find('.err-quantity').text('');
                            }
                            totalMoney();
                        }
                    });
                    if (flag == true) {
                        var productInventoryQuantt = 0;
                        if (typeof data['productInventory'] === "undefined") {
                            productInventoryQuantt = 0;
                        } else {
                            productInventoryQuantt = data['productInventory'];
                        }
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
                        tpl = tpl.replace(/{productInventory}/g, productInventoryQuantt);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{number}/g, 0);
                        tpl = tpl.replace(/{cost}/g, Number(data['product']['cost']).toFixed(decimal_number));
                        tpl = tpl.replace(/{price}/g, Number(data['product']['price']).toFixed(decimal_number));
                        tpl = tpl.replace(/{total}/g, 0);
                        tpl = tpl.replace(/{option}/g, option);
                        $('#table-product > tbody').append(tpl);

                        $('.number-product').ForceNumericOnly();
                        totalMoney();
                        $('.unit').select2();
                        $('.number-product').ForceNumericOnly();
                    }
                }
            });
        }
        if (stt == 1) {
            $('#total-product').val(0);
            $('#total-product-text').text(0 + ' ' + json['sản phẩm']);
            $('.error-product').text('');
        }
    });
});