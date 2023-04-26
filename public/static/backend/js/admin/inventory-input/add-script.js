$('.rdo').click(function () {
    $('.rdo').attr('class', 'btn btn-default rdo');
    $(this).attr('class', 'btn ss--button-cms-piospa active rdo');
});
$('.m_selectpicker').select2();
$('#product-code').attr('disabled', true);
$('.errs').css('color', 'red');
$('#created-at').datepicker({format: 'dd/mm/yyyy'});
$('#created-at').datepicker('setDate', 'today');
// $('#warehouse').select2();
// $('#supplier').select2();

// $('#list-product2').select2({
//     // minimumInputLength: 1,
//     // placeholder: 'Nhập tên hoặc mã sản phẩm',
//     // ajax: {
//     //     url: laroute.route('admin.inventory-input.search-product-child'),
//     //     dataType: 'JSON',
//     //     delay: 250,
//     //     type: 'POST',
//     //     data: function (params) {
//     //         var query = {
//     //             search: params.term,
//     //             page: params.page || 1
//     //         };
//     //         return query;
//     //     }
//     // },
//     // minimumInputLength: 1
// }).on("select2:select", function (e) {
//     let id = e.params.data.id;
//     let stt = 1 + $('#table-product > tbody > tr').length;
//     let sum = 0;
//     let flag = true;
//     if (id != '') {
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
//                         codeHidden.parents('tr').find('input[name="totalMoneyProduct"]').val(formatNumber(cost * numbers));
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
//                     tpl = tpl.replace(/{price}/g, Number(data['product']['price']).toFixed(decimal_number));
//                     tpl = tpl.replace(/{cost}/g, formatNumber(data['product']['cost']));
//                     tpl = tpl.replace(/{number}/g, 1);
//                     tpl = tpl.replace(/{option}/g, option);
//                     tpl = tpl.replace(/{total}/g, Number(data['product']['cost']).toFixed(decimal_number));
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
//     }
//     if (stt == 1) {
//         $('#total-product').val(1);
//         $('.total-product').text(1 + " " + "sản phẩm");
//         $('.error-product').text('');
//     } else {
//         sumNumberProduct(1);
//     }
//     // totalMoney();
// });

function maskNumberPriceProductChild() {
    // $('input[name="cost-product-child"]').maskNumber({integer: true});
    // new AutoNumeric.multiple('input[name="cost-product-child"]' ,{
    //     currencySymbol : '',
    //     decimalCharacter : '.',
    //     digitGroupSeparator : ',',
    //     decimalPlaces: decimal_number
    // });
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

function clickNumberProduct(o) {

    $(o).val(parseInt($(o).val()));
    if ($(o).val() == '' || $(o).val() == 0) {
        $(o).val(1);
    }
    let cost = $(o).parents('tr').find('input[name="cost-product-child"]').val();
    let total = cost.replace(new RegExp('\\,', 'g'), '') * $(o).val();
    $(o).parents('tr').find('.total-money-product').val(Number(total).toFixed(decimal_number));
    $(o).parents('tr').find('.total-money-product2').text(Number(total).toFixed(decimal_number));

    sumNumberProduct(0);
    totalMoney();
}

function changeCost(o) {
    let cost = $(o).val().replace(new RegExp('\\,', 'g'), '');
    let number = $(o).parents('tr').find('.number-product').val();
    $(o).parents('tr').find('.total-money-product').val(Number(number * cost).toFixed(decimal_number));
    $(o).parents('tr').find('.total-money-product2').text(Number(number * cost).toFixed(decimal_number));
    totalMoney()
}

function deleteProductInList(o) {
    $(o).closest('tr').remove();
    let table = $('#table-product > tbody tr').length;
    let a = 1;
    $.each($('.stt'), function () {
        $(this).text(a++);
    });
    sumNumberProduct(0);
    totalMoney();
}

//sum quantity product
function sumNumberProduct(sum) {
    $('.number-product').each(function () {
        sum += Number($(this).val());
    });
    $('#total-product').val(sum);
    $('.total-product').text(sum + " " + "sản phẩm");
}

// total money all product.
function totalMoney() {
    let total = 0;
    let arrSum = [];
    $.each($('.total-money-product2'), function () {
        let valAttr = $(this).text().replace(new RegExp('\\,', 'g'), '');
        total += Number(valAttr);
    });
    $('#total-money').val(Number(total).toFixed(decimal_number));
    $('.total-money').text(Number(total).toFixed(decimal_number));

}
function checkInput() {
    let flag = true;
    let errWarehouse = $('.error-warehouse');
    let errSupplier = $('.error-supplier');
    $.getJSON(laroute.route('translate'), function (json) {
        if ($('#warehouse').val() == "") {
            errWarehouse.text(json['Vui lòng chọn nhà kho']);
            flag = false;
        } else {
            errWarehouse.text('');
        }
        if ($('#supplier').val() == "") {
            errSupplier.text(json['Vui lòng chọn nhà cung cấp']);
            flag = false;
        } else {
            errSupplier.text('');
        }
    });
    return flag;
}

var d = new Date();
$('.btn-save').click(function () {
    var now = new Date();
    let flag = true;
    $.getJSON(laroute.route('translate'), function (json) {
        if (checkInput() == true && flag == true) {
            var statusss = $('.active').find('input[name="options"]').val();
            let stt = $('#table-product > tbody > tr').length;
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
                    url: laroute.route('admin.inventory-input.submit-add'),
                    method: "POST",
                    data: {
                        warehouse_id: $('#warehouse').val(),
                        supplier_id: $('#supplier').val(),
                        pi_code: $('#code-inventory').val(),
                        status: statusss,
                        note: $('#note').val(),
                        arrayProducts: arrayProducts,
                        created_at: $('#created-at').val(),
                        type: $('#type').val()
                    },
                    success: function () {
                        swal(json["Thêm phiếu nhập thành công"], "", "success");
                        window.location = laroute.route('admin.product-inventory');
                    }
                });
            }
        }
    });
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
                codeHidden.parents('tr').find('input[name="totalMoneyProduct[]"]').val(formatNumber(numbers * parseInt(cost.replace(new RegExp('\\,', 'g'), ''))));
                codeHidden.parents('tr').find('.total-money-product2').text(formatNumber(numbers * parseInt(cost.replace(new RegExp('\\,', 'g'), ''))));
                totalMoney();
                o.val('');
                o.focus();
                $('.error-code-product').text('');
            }
        });
        if (flag == true) {
            let stt = 1 + $('#table-product > tbody > tr').length;
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
                        $('.error-code-product').text('');
                        var option = "";
                        option += '<option value="' + data['unitExists']['unit_id'] + '">' + '' + data['unitExists']['name'] + '</option>';
                        $.each(data['unit'], function (index, element) {
                            option += '<option value="' + index + '">' + element + '</option>';
                        });

                        let $_tpl = $('#product-childs').html();
                        let tpl = $_tpl;
                        tpl = tpl.replace(/{stt}/g, stt);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{price}/g, formatNumber(data['product']['price']));
                        tpl = tpl.replace(/{cost}/g, formatNumber(data['product']['cost']));
                        tpl = tpl.replace(/{number}/g, 1);
                        tpl = tpl.replace(/{total}/g, formatNumber(data['product']['cost']));
                        tpl = tpl.replace(/{option}/g, option);
                        $('#table-product > tbody').append(tpl);
                        let money = parseInt($('input[name="totalMoneyProduct[]"]').val().replace(new RegExp('\\,', 'g'), ''));

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
$('#btn-save-draft').click(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        if ($('#table-product > tbody > tr').length < 1) {
            $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        }
        if (checkInput() == true) {
            let stt = $('#table-product > tbody > tr').length;
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
                    url: laroute.route('admin.inventory-input.submit-add'),
                    method: "POST",
                    data: {
                        warehouse_id: $('#warehouse').val(),
                        supplier_id: $('#supplier').val(),
                        pi_code: $('#code-inventory').val(),
                        status: 'draft',
                        note: $('#note').val(),
                        arrayProducts: arrayProducts,
                        created_at: $('#created-at').val(),
                        type: $('#type').val()
                    },
                    success: function () {
                        swal(json["Lưu nháp phiếu nhập thành công"], "", "success");
                        location.reload();
                    }
                })
            }
        }
    })
});
$('#list-product').attr('disabled', true);
$('#warehouse').change(function () {
    // $('tbody').empty();
    // $('#list-product').empty();
    // $('#total-product').val(0);
    if ($('#warehouse').val() != "") {
        $('#list-product').attr('disabled', false);
        $('#product-code').attr('disabled', false);
    } else {
        $('#list-product').attr('disabled', true);
        $('#product-code').attr('disabled', true);
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

$('#btn-save-add-new').click(function () {
    var now = new Date();
    let flag = true;
    $.getJSON(laroute.route('translate'), function (json) {
    if (checkInput() == true && flag == true) {
        var statusss = $('.active').find('input[name="options"]').val();
        let stt = $('#table-product > tbody > tr').length;
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
                url: laroute.route('admin.inventory-input.submit-add'),
                method: "POST",
                data: {
                    warehouse_id: $('#warehouse').val(),
                    supplier_id: $('#supplier').val(),
                    pi_code: $('#code-inventory').val(),
                    status: statusss,
                    note: $('#note').val(),
                    arrayProducts: arrayProducts,
                    created_at: $('#created-at').val(),
                    type: $('#type').val()
                },
                success: function () {
                    swal(json["Thêm phiếu nhập thành công"], "", "success");
                    location.reload();
                }
            })
        }
    }
});
});

var InventoryInput = {
    cong: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.number-product');
        $(o).parents('td').find('.number-product').val(parseInt(inputNumberProduct.val()) + 1);
        clickNumberProduct(inputNumberProduct);
    },
    tru: function (o) {
        var inputNumberProduct = $(o).parents('td').find('.number-product');
        if (inputNumberProduct.val() > 0) {
            $(o).parents('td').find('.number-product').val(parseInt(inputNumberProduct.val()) - 1);
            clickNumberProduct(inputNumberProduct);
        }
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
        let sum = 0;
        let flag = true;
        if (id != '') {
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
                            codeHidden.parents('tr').find('input[name="totalMoneyProduct"]').val(formatNumber(cost * numbers));
                            codeHidden.parents('tr').find('.total-money-product2').text(formatNumber(cost * numbers));
                            totalMoney();
                        }
                    });
                    if (flag == true) {
                        var option = "";
                        option += '<option value="' + data['unitExists']['unit_id'] + '">' + '' + data['unitExists']['name'] + '</option>';
                        $.each(data['unit'], function (index, element) {
                            option += '<option value="' + index + '">' + element + '</option>';
                        });
                        let $_tpl = $('#product-childs').html();
                        let tpl = $_tpl;
                        tpl = tpl.replace(/{stt}/g, stt);
                        tpl = tpl.replace(/{name}/g, data['product']['product_child_name']);
                        tpl = tpl.replace(/{code}/g, data['product']['product_code']);
                        tpl = tpl.replace(/{price}/g, Number(data['product']['price']).toFixed(decimal_number));
                        tpl = tpl.replace(/{cost}/g, formatNumber(data['product']['cost']));
                        tpl = tpl.replace(/{number}/g, 1);
                        tpl = tpl.replace(/{option}/g, option);
                        tpl = tpl.replace(/{total}/g, Number(data['product']['cost']).toFixed(decimal_number));
                        $('#table-product > tbody').append(tpl);

                        new AutoNumeric.multiple('#id-child-' + data['product']['product_code'] + '', {
                            currencySymbol : '',
                            decimalCharacter : '.',
                            digitGroupSeparator : ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });

                        totalMoney();
                        $('.unit').select2();
                        $('.number-product').ForceNumericOnly();
                    }
                }
            });
        }
        if (stt == 1) {
            $('#total-product').val(1);
            $('.total-product').text(1 + " " + json["sản phẩm"]);
            $('.error-product').text('');
        } else {
            sumNumberProduct(1);
        }
        // totalMoney();
    });
});

