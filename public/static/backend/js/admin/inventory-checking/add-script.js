$('#list-product').attr('disabled', true);
$('#product-code').attr('disabled', true);

$('.errs').css('color', 'red');
$('#warehouse').change(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        let errWarehouse = $('.error-warehouse');
        if ($('#warehouse').val() == "") {
            errWarehouse.text(json['Vui lòng chọn kho']);
            flag = false;
            $('#list-product').attr('disabled', true);
            $('#product-code').attr('disabled', true);
        } else {
            errWarehouse.text('');
            $('#list-product').attr('disabled', false);
            $('#product-code').attr('disabled', false);
        }
        $('tbody').empty();
        $('#list-product').empty();
        $('#list-product').append('<option value="">'+json["Chọn sản phẩm"]+'</option>');
        if ($('#warehouse').val() != '') {
            $.ajax({
                url: laroute.route('admin.inventory-checking.get-productss'),
                method: "POST",
                dataType: "JSON",
                data: {
                    warehouse: $('#warehouse').val()
                },
                success: function (data) {
                    $('#list-product').empty();
                    $('#list-product').append('<option value="">'+json["Chọn sản phẩm"]+'</option>');
                    $.each(data, function (key, value) {
                        $('#list-product').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    });
});
// search and select product.
$('#list-product').select2({

}).on("select2:select", function (e) {
    $.getJSON(laroute.route('translate'), function (json) {
        let id = e.params.data.id;
        let stt = 1 + $('#table-product > tbody > tr').length;
        let flag = true;
        if (id!=''){
            $.ajax({
                url: laroute.route('admin.inventory-checking.get-product-child-by-id'),
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    warehouse: $('#warehouse').val()
                },
                success: function (data) {
                    if (typeof  data['productInventoryNull'] !== 'undefined') {
                        $.each($('#table-product tbody tr'), function () {
                            let codeHidden = $(this).find("td input[name='hiddencode[]']");
                            let codeExists = codeHidden.val();
                            var code = data['productInventoryNull']['product']['product_code'];
                            if (codeExists == code) {
                                flag = false;
                            }
                        });
                        if (flag == true) {
                            var option = "";
                            $.each(data['productInventoryNull']['unit'], function (index, element) {
                                if (data['productInventoryNull']['unitExists']['unit_id'] == index) {
                                    option += '<option selected value="' + index + '">' + element + '</option>';
                                } else {
                                    option += '<option value="' + index + '">' + element + '</option>';
                                }
                            });
                            let $_tpl = $('#product-childs').html();
                            let tpl = $_tpl;
                            tpl = tpl.replace(/{stt}/g, stt);
                            tpl = tpl.replace(/{name}/g, data['productInventoryNull']['product']['product_child_name']);
                            tpl = tpl.replace(/{code}/g, data['productInventoryNull']['product']['product_code']);
                            tpl = tpl.replace(/{cost}/g, data['productInventoryNull']['product']['cost']);
                            tpl = tpl.replace(/{option}/g, option);
                            tpl = tpl.replace(/{quantityOld}/g, 0);
                            tpl = tpl.replace(/{quantityNew}/g, 0);
                            tpl = tpl.replace(/{quantityDifference}/g, 0);
                            tpl = tpl.replace(/{number}/g, 0);
                            $('#table-product > tbody').append(tpl).parents('tr').find('.unit').empty();
                            $('.unit').select2();
                            $('.quantityNew').ForceNumericOnly();
                        }
                    }
                    if (typeof  data['productInventory'] !== 'undefined') {
                        $.each($('#table-product tbody tr'), function () {
                            let codeHidden = $(this).find("td input[name='hiddencode[]']");
                            let codeExists = codeHidden.val();
                            var code = data['productInventory']['product']['code'];
                            if (codeExists == code) {
                                flag = false;
                            }
                        });
                        if (flag == true) {
                            var option = "";
                            $.each(data['productInventory']['unit'], function (index, element) {
                                if (data['productInventory']['unitExists']['unit_id'] == index) {
                                    option += '<option selected value="' + index + '">' + element + '</option>';
                                } else {
                                    option += '<option value="' + index + '">' + element + '</option>';
                                }
                            });
                            let $_tpl = $('#product-childs').html();
                            let tpl = $_tpl;
                            tpl = tpl.replace(/{stt}/g, stt);
                            tpl = tpl.replace(/{name}/g, data['productInventory']['product']['name']);
                            tpl = tpl.replace(/{code}/g, data['productInventory']['product']['code']);
                            tpl = tpl.replace(/{cost}/g, data['productInventory']['product']['cost']);
                            tpl = tpl.replace(/{option}/g, option);
                            tpl = tpl.replace(/{quantityOld}/g, data['productInventory']['product']['quantitys']);
                            tpl = tpl.replace(/{quantityNew}/g, data['productInventory']['product']['quantitys']);
                            tpl = tpl.replace(/{quantityDifference}/g, 0);
                            $('#table-product > tbody').append(tpl).parents('tr').find('.unit').empty();
                            $('.unit').select2({
                                placeholder: json['Chọn đơn vị tính']
                            });
                            $('.quantityNew').ForceNumericOnly();
                        }
                    }
                }
            });
        }
        $('.error-product').text('');
    });
});

function changeQuantityNew(o) {
    $.getJSON(laroute.route('translate'), function (json) {
        let values = parseInt($(o).val());
        if (values > 0) {
            $(o).val(values);

        } else {
            $(o).val(0);
        }
        let thisVal = parseInt($(o).val());
        let valSystems = $(o).parents('tr').find('input[name="quantityOld[]"]').val();
        let quantityDifference = $(o).parents('tr').find('input[name="quantityDifference[]"]');
        let quantityDifference2 = $(o).parents('tr').find('.quantityDifference');
        quantityDifference.val(valSystems - thisVal);
        quantityDifference2.text(valSystems - thisVal);
        let typeResolve = $(o).parents('tr').find('.typeResolve');
        if (quantityDifference.val() < 0) {
            typeResolve.find('b').attr('class', 'm--font-success').text(json['Nhập kho']);
        } else if (quantityDifference.val() > 0) {
            typeResolve.find('b').attr('class', 'm--font-danger').text(json['Xuất kho']);
        } else {
            typeResolve.find('b').text('');
        }
        if ($(o).val() == "" || thisVal < 0) {
            $(o).val(0);
            quantityDifference.val(valSystems);
            quantityDifference2.text(valSystems);
        }
    })
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

function deleteProductInList(o) {
    $(o).closest('tr').remove();
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
                o.focus();
                $('.error-code-product').text(json['Sản phẩm đã tồn tại']);
            }
        });
        if (flag == true) {
            let stt = 1 + $('#table-product > tbody > tr').length;
            let sum = 0;
            $.ajax({
                url: laroute.route('admin.inventory-checking.get-product-child-by-code'),
                method: "POST",
                dataType: "JSON",
                data: {
                    code: codeInput,
                    warehouse: $('#warehouse').val()
                },
                success: function (data) {

                    if (data['null'] == 1) {
                        $('.error-code-product').text(json['Mã sản phẩm không hợp lệ']);
                    } else {
                        if (typeof data['productInventory'] !== 'undefined') {
                            $('.error-code-product').text('');
                            var option = "";
                            $.each(data['productInventory']['unit'], function (index, element) {
                                if (data['productInventory']['unitExists']['unit_id'] == index) {
                                    option += '<option selected value="' + index + '">' + element + '</option>';
                                } else {
                                    option += '<option value="' + index + '">' + element + '</option>';
                                }
                            });
                            let $_tpl = $('#product-childs').html();
                            let tpl = $_tpl;
                            tpl = tpl.replace(/{stt}/g, stt);
                            tpl = tpl.replace(/{name}/g, data['productInventory']['product']['name']);
                            tpl = tpl.replace(/{code}/g, data['productInventory']['product']['code']);
                            tpl = tpl.replace(/{cost}/g, data['productInventory']['product']['cost']);
                            tpl = tpl.replace(/{option}/g, option);
                            tpl = tpl.replace(/{quantityOld}/g, data['productInventory']['product']['quantitys']);
                            tpl = tpl.replace(/{quantityNew}/g, data['productInventory']['product']['quantitys']);
                            tpl = tpl.replace(/{quantityDifference}/g, 0);
                            $('#table-product > tbody').append(tpl);
                            o.val('');
                            o.focus();
                            $('.unit').select2();
                            $('.quantityNew').ForceNumericOnly();
                        }
                        if (typeof data['productInventoryNull'] !== 'undefined') {

                            $('.error-code-product').text('');
                            var option = "";
                            $.each(data['productInventoryNull']['unit'], function (index, element) {
                                if (data['productInventoryNull']['unitExists']['unit_id'] == index) {
                                    option += '<option selected value="' + index + '">' + element + '</option>';
                                } else {
                                    option += '<option value="' + index + '">' + element + '</option>';
                                }
                            });
                            let $_tpl = $('#product-childs').html();
                            let tpl = $_tpl;
                            tpl = tpl.replace(/{stt}/g, stt);
                            tpl = tpl.replace(/{name}/g, data['productInventoryNull']['product']['product_child_name']);
                            tpl = tpl.replace(/{code}/g, data['productInventoryNull']['product']['product_code']);
                            tpl = tpl.replace(/{cost}/g, data['productInventoryNull']['product']['cost']);
                            tpl = tpl.replace(/{option}/g, option);
                            tpl = tpl.replace(/{quantityOld}/g, 0);
                            tpl = tpl.replace(/{quantityNew}/g, 0);
                            tpl = tpl.replace(/{quantityDifference}/g, 0);
                            tpl = tpl.replace(/{number}/g, 1);
                            $('#table-product > tbody').append(tpl);
                            o.val('');
                            o.focus();
                            $('.unit').select2();
                            $('.quantityNew').ForceNumericOnly();
                        }

                    }
                }
            });
            if (stt > 0) {
                $('.error-product').text('');
            }
        }
    })
});

$('.btn-save').click(function () {
    let flag = true;
    let stt = $('#table-product > tbody > tr').length;
    $.getJSON(laroute.route('translate'), function (json) {
    if ($('#warehouse').val() == "") {
        $('.error-warehouse').text(json['Vui lòng chọn kho']);
        flag = false;
    }
    if ($('#note').val() == "") {
        $('.error-note').text(json['Vui lòng nhập lý do kiểm kho']);
        flag = false;
    } else if (stt < 1) {
        $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        flag = false;
    }
    if (flag == true) {
        let arrayProducts = [];
        $.each($('#table-product tbody tr'), function () {
            let productCode = $(this).find("td input[name='hiddencode[]']").val();
            let cost = $(this).find("td input[name='hiddencost[]']").val();
            let unit = $(this).find("td .unit").val();
            let quantityOld = $(this).find("td input[name='quantityOld[]']").val();
            let quantityNew = $(this).find("td input[name='quantityNew[]']").val();
            let quantityDifference = $(this).find("td input[name='quantityDifference[]']").val();
            arrayProducts.push(productCode, cost, unit, quantityOld, quantityNew, quantityDifference);
        });
        $.ajax({
            url: laroute.route('admin.inventory-checking.submit-add'),
            method: "POST",
            data: {
                warehouseId: $('#warehouse').val(),
                checkingCode: $('#checking-code').val(),
                note: $('#note').val(),
                arrayProducts: arrayProducts,
                created_at: $('#created-at').val(),
                status: 'success'
            },
            success: function (data) {
                if (data.status == true) {
                    swal(json["Thêm phiếu kiểm kho thành công"], "", "success");
                    window.location = laroute.route('admin.product-inventory');
                }
            }
        })
    }
});
});
$('#btn-save-draft').click(function () {
    let flag = true;
    let stt = $('#table-product > tbody > tr').length;
    $.getJSON(laroute.route('translate'), function (json) {
    if ($('#warehouse').val() == "") {
        $('.error-warehouse').text(json['Vui lòng chọn kho']);
        flag = false;
    } else if (stt < 1) {
        $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        flag = false;
    }
    if (flag == true) {
        let arrayProducts = [];
        $.each($('#table-product tbody tr'), function () {
            let productCode = $(this).find("td input[name='hiddencode[]']").val();
            let cost = $(this).find("td input[name='hiddencost[]']").val();
            let unit = $(this).find("td .unit").val();
            let quantityOld = $(this).find("td input[name='quantityOld[]']").val();
            let quantityNew = $(this).find("td input[name='quantityNew[]']").val();
            let quantityDifference = $(this).find("td input[name='quantityDifference[]']").val();
            arrayProducts.push(productCode, cost, unit, quantityOld, quantityNew, quantityDifference);
        });
        $.ajax({
            url: laroute.route('admin.inventory-checking.submit-add'),
            method: "POST",
            data: {
                warehouseId: $('#warehouse').val(),
                checkingCode: $('#checking-code').val(),
                note: $('#note').val(),
                arrayProducts: arrayProducts,
                created_at: $('#created-at').val(),
                status: 'draft'
            },
            success: function (data) {
                if (data.status == true) {
                    swal(json["Lưu nháp phiếu kiểm kho thành công"], "", "success");
                    location.reload();
                }
            }
        })
    }
});
});
$('#note').change(function () {
    if ($('#note').val() != '') {
        $('.error-note').text('');
    }
    ;
});

function getFormattedDate(date) {
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return month + '/' + day + '/' + year;
}

function compareDateTime(date1, date2) {
    return (date1 > date2);
}

$('#created-at').datepicker({format: 'dd/mm/yyyy'});
$('#created-at').datepicker('setDate', 'today');
$('.m_selectpicker').select2();

function deleteProductInList(o) {
    $(o).closest('tr').remove();
    let table = $('#table-product > tbody tr').length;
    let a = 1;
    $.each($('.stt'), function () {
        $(this).text(a++);
    });
}

$('#btn-save-add-new').click(function () {
    let flag = true;
    let stt = $('#table-product > tbody > tr').length;
    $.getJSON(laroute.route('translate'), function (json) {
    if ($('#warehouse').val() == "") {
        $('.error-warehouse').text(json['Vui lòng chọn kho']);
        flag = false;
    }
    if ($('#note').val() == "") {
        $('.error-note').text(json['Vui lòng nhập lý do kiểm kho']);
        flag = false;
    } else if (stt < 1) {
        $('.error-product').text(json['Vui lòng thêm sản phẩm']);
        flag = false;
    }
    if (flag == true) {
        let arrayProducts = [];
        $.each($('#table-product tbody tr'), function () {
            let productCode = $(this).find("td input[name='hiddencode[]']").val();
            let cost = $(this).find("td input[name='hiddencost[]']").val();
            let unit = $(this).find("td .unit").val();
            let quantityOld = $(this).find("td input[name='quantityOld[]']").val();
            let quantityNew = $(this).find("td input[name='quantityNew[]']").val();
            let quantityDifference = $(this).find("td input[name='quantityDifference[]']").val();
            arrayProducts.push(productCode, cost, unit, quantityOld, quantityNew, quantityDifference);
        });
        $.ajax({
            url: laroute.route('admin.inventory-checking.submit-add'),
            method: "POST",
            data: {
                warehouseId: $('#warehouse').val(),
                checkingCode: $('#checking-code').val(),
                note: $('#note').val(),
                arrayProducts: arrayProducts,
                created_at: $('#created-at').val(),
                status: 'success'
            },
            success: function (data) {
                if (data.status == true) {
                    swal(json["Thêm phiếu kiểm kho thành công"], "", "success");
                    location.reload();
                }
            }
        })

    }
});
});

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
$('.quantityNew').ForceNumericOnly();