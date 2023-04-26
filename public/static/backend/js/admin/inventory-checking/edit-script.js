var check = true;

$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#warehouse').change(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                let errWarehouse = $('.error-warehouse');
                if ($('#warehouse').val() == "") {
                    errWarehouse.css('color', 'red').text(json['Vui lòng chọn kho']);
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
                $('#list-product').append('<option value="">'+json['Chọn sản phẩm']+'</option>');


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
                            $('#list-product').append('<option value="">'+json['Chọn sản phẩm']+'</option>');
                            $.each(data, function (key, value) {
                                $('#list-product').append('<option value="' + key + '">' + value + '</option>')
                            });
                        }
                    });
                }
            })
        });

        // search and select product.
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
                            $.each($('#table-product tbody tr.blockProductMain'), function () {
                                let codeHidden = $(this).find("td input.productCode");
                                let codeExists = codeHidden.val();
                                var code = data['productInventoryNull']['product']['product_code'];
                                if (codeExists == code) {
                                    flag = false;
                                }
                            });
                            if (flag == true) {
                                var option = "";
                                $.each(data['productInventoryNull']['units'], function (index, element) {
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
                                // $('.unit').select2();
                                // $('.quantityNew').ForceNumericOnly();
                                saveProduct();
                            }
                        }
                        if (typeof  data['productInventory'] !== 'undefined') {
                            $.each($('#table-product tbody tr.blockProductMain'), function () {
                                let codeHidden = $(this).find("td input.productCode");
                                let codeExists = codeHidden.val();
                                var code = data['productInventory']['product']['code'];
                                if (codeExists == code) {
                                    flag = false;
                                }
                            });
                            if (flag == true) {
                                var option = "";
                                $.each(data['productInventory']['units'], function (index, element) {
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
                                tpl = tpl.replace(/{number}/g, data['productInventory']['product']['quantitys']);
                                $('#table-product > tbody').append(tpl).parents('tr').find('.unit').empty();
                                // $('.unit').select2({
                                //     placeholder: 'Chọn đơn vị tính'
                                // });
                                // $('.quantityNew').ForceNumericOnly();
                                saveProduct();
                            }
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

            let id = e.params.data.id;
            let serial = $('#list-product-serial option:selected').attr('data-serial');
            let productCode = $('#list-product-serial option:selected').attr('data-product-code');

            let stt = 1 + $('#table-product > tbody > tr').length;
            let flag = true;

            if (id!=''){
                $.ajax({
                    url: laroute.route('admin.inventory-checking.get-product-child-by-id'),
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        warehouse: $('#warehouse').val(),
                        inventory_checking_id : $('#idHidden').val(),
                        productCode : productCode
                    },
                    success: function (data) {
                        console.log(data['productInventoryNull']);
                        if (typeof  data['productInventoryNull'] !== 'undefined') {
                            $.each($('#table-product tbody tr.blockProductMain'), function () {
                                let codeHidden = $(this).find("td input.productCode");
                                let codeExists = codeHidden.val();
                                var code = data['productInventoryNull']['product']['product_code'];
                                // if (codeExists == code) {
                                //     flag = false;
                                // }
                            });
                            if (flag == true) {
                                var option = "";
                                $.each(data['productInventoryNull']['units'], function (index, element) {
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
                                // $('.unit').select2();
                                // $('.quantityNew').ForceNumericOnly();

                            }
                        }
                        if (typeof  data['productInventory'] !== 'undefined') {
                            $.each($('#table-product tbody tr.blockProductMain'), function () {
                                let codeHidden = $(this).find("td input.productCode");
                                let codeExists = codeHidden.val();
                                var code = data['productInventory']['product']['code'];

                            });

                            if (flag == true) {
                                var option = "";
                                $.each(data['productInventory']['units'], function (index, element) {
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
                                tpl = tpl.replace(/{quantityNew}/g, data['productInventory']['totalSerial']);
                                tpl = tpl.replace(/{quantityDifference}/g, data['productInventory']['product']['quantitys'] - data['productInventory']['totalSerial']);
                                tpl = tpl.replace(/{number}/g, data['productInventory']['product']['quantitys']);
                                $('#table-product > tbody').append(tpl).parents('tr').find('.unit').empty();
                            }
                        }

                        saveProduct(serial,productCode);
                    }
                });
            }
        });
    });
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

function changeQuantityNew(o) {
    $.getJSON(laroute.route('translate'), function (json) {
        let values = parseInt($(o).val());
        if (values > 0) {
            $(o).val(values);

        } else {
            $(o).val(0);
        }
        // $(o).val(values);
        let thisVal = parseInt($(o).val());
        let valSystems = $(o).parents('tr').find('input.quantityOld').val();
        let quantityDifference = $(o).parents('tr').find('input.quantityDifference');
        let quantityDifference2 = $(o).parents('tr').find('span.quantityDifference');
        quantityDifference.val(valSystems - thisVal);
        quantityDifference2.text(valSystems - thisVal);
        let typeResolve = $(o).parents('tr').find('td');
        if (parseInt(quantityDifference.val()) == 0) {
            typeResolve.find('b').text('');
        }
        if (quantityDifference.val() < 0) {
            typeResolve.find('b').attr('class', 'm--font-success').text(json['Nhập kho']);
        } else if (quantityDifference.val() > 0) {
            typeResolve.find('b').attr('class', 'm--font-danger').text(json['Xuất kho']);
        }
        if ($(o).val() == "" || thisVal < 0) {
            $(o).val(0);
            quantityDifference.val(valSystems);
            quantityDifference2.text(valSystems - thisVal);
        }

        saveProduct();
    })
}

function deleteProductInList(o,inventory_checking_detail_id,productCode) {
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
                $.ajax({
                    url:laroute.route('admin.inventory-checking.remove-product-inline'),
                    method:"POST",
                    data:{
                        inventory_checking_detail_id : inventory_checking_detail_id,
                        inventory_checking_id : $('#idHidden').val(),
                        productCode : productCode
                    },
                    success:function (data) {
                        if(data.error == false){
                            swal(data.message,'', "success");
                            InventoryChecking.getListProductInput();
                        } else {
                            swal(data.message,'', "error");
                        }
                    }
                });
            }
        });
    });
}

$('.btn-save').click(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $.ajax({
            url: laroute.route('admin.inventory-checking.submit-edit-check'),
            method: "POST",
            data: {
                id: $('#idHidden').val()
            },
            success: function (data) {
                if (data.error == false) {
                    submitProductSuccess();
                } else {
                    swal({
                        title: data.message,
                        text: data.message_confirm,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: json['Đồng ý'],
                        cancelButtonText: json['Đóng'],

                    }).then(function(result) {
                        if (result.value) {
                            submitProductSuccess();
                        }
                    });
                }
            }
        })
    })
});

function submitProductSuccess(){
    $.getJSON(laroute.route('translate'), function (json) {
        var now = new Date();
        let flag = true;
        let stt = $('#table-product > tbody > tr').length;
        if ($('#warehouse').val() == "") {
            $('.error-warehouse').text(json['Vui lòng chọn kho']);
            flag = false;
        } else if ($('#note').val() == "") {
            $('.error-note').css('color', 'red').text(json['Vui lòng nhập lý do kiểm kho']);
            flag = false;
        } else if (stt < 1) {
            $('.error-product').css('color', 'red').text(json['Vui lòng thêm sản phẩm']);
            flag = false;
        }
        if (flag == true) {
            let arrayProducts = [];
            $.each($('#table-product tbody tr.blockProductMain'), function () {
                let productCode = $(this).find("td input.productCode").val();
                let unit = $(this).find("td .unit").val();
                let quantityOld = $(this).find("td input.quantityOld").val();
                let quantityNew = $(this).find("td input.quantityNew").val();
                let note = $(this).find("td input.note").val();
                let inventory_management = $(this).find("td input.inventory_management").val();


                if (inventory_management == 'serial'){
                    let total_import = $(this).find("td input.total_import").val();
                    let total_export = $(this).find("td input.total_export").val();
                    console.log(total_import,total_export);
                    arrayProducts.push(productCode, unit, quantityOld, quantityNew, total_import+'_'+total_export, note,inventory_management);
                } else {
                    let quantityDifference = $(this).find("td input.quantityDifference").val();
                    arrayProducts.push(productCode, unit, quantityOld, quantityNew, quantityDifference+'_'+quantityDifference, note,inventory_management);
                }
            });
            $.ajax({
                url: laroute.route('admin.inventory-checking.submit-edit'),
                method: "POST",
                data: {
                    warehouseId: $('#warehouse').val(),
                    checkingCode: $('#checking-code').val(),
                    reason: $('#note').val(),
                    arrayProducts: arrayProducts,
                    created_at: $('#created-at').val(),
                    status: 'success',
                    id: $('#idHidden').val()
                },
                success: function (data) {
                    if (data.status == true) {
                        swal(json["Cập nhật phiếu kiểm kho thành công"], "", "success").then(function () {
                            location.reload();
                        });
                    }
                }
            })

        }
    })
}

$('#btn-save-draft').click(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        var now = new Date();
        let flag = true;
        let stt = $('#table-product > tbody > tr').length;

        if ($('#warehouse').val() == "") {
            $('.error-warehouse').text(json['Vui lòng chọn kho']);
            flag = false;
        } else if ($('#note').val() == "") {
            $('.error-note').css('color', 'red').text(json['Vui lòng nhập lý do kiểm kho']);
            flag = false;
        } else if (stt < 1) {
            $('.error-product').css('color', 'red').text(json['Vui lòng thêm sản phẩm']);
            flag = false;
        }
        if (flag == true) {
            let arrayProducts = [];
            $.each($('#table-product tbody tr.blockProductMain'), function () {
                let productCode = $(this).find("td input.productCode").val();
                let unit = $(this).find("td .unit").val();
                let quantityOld = $(this).find("td input.quantityOld").val();
                let quantityNew = $(this).find("td input.quantityNew").val();
                let quantityDifference = $(this).find("td input.quantityDifference").val();
                let note = $(this).find("td input.note").val();
                arrayProducts.push(productCode, unit, quantityOld, quantityNew, quantityDifference, note);
            });
            $.ajax({
                url: laroute.route('admin.inventory-checking.submit-edit'),
                method: "POST",
                data: {
                    warehouseId: $('#warehouse').val(),
                    checkingCode: $('#checking-code').val(),
                    reason: $('#note').val(),
                    arrayProducts: arrayProducts,
                    created_at: $('#created-at').val(),
                    status: 'draft',
                    id: $('#idHidden').val()
                },
                success: function (data) {
                    if (data.status == true) {
                        swal(json["Cập nhật phiếu kiểm kho thành công"], "", "success");
                        location.reload();
                    }
                }
            })

        }
    })
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
            let codeHidden = $(this).find("td input.productCode");
            var code = codeHidden.val();
            if (codeInput == code) {
                flag = false;
                o.focus();
                $('.error-code-product').css('color', 'red').text('Sản phẩm đã tồn tại');
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
                            $('#table-product > tbody').append(tpl);
                            o.val('');
                            o.focus();
                            // $('.unit').select2();
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
$('.m_selectpicker').select2();
// $('.unit').select2();

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

function saveProduct(serial = null,product_code = null, type = ''){
    $.getJSON(laroute.route('translate'), function (json) {
        var now = new Date();
        let flag = true;
        let stt = $('#table-product > tbody > tr').length;
        if ($('#warehouse').val() == "") {
            // $('.error-warehouse').text('Vui lòng chọn kho');
            // flag = false;
        } else if ($('#note').val() == "") {
            // $('.error-note').css('color', 'red').text('Vui lòng nhập lý do kiểm kho');
            // flag = false;
        } else if (stt < 1) {
            $('.error-product').css('color', 'red').text(json['Vui lòng thêm sản phẩm']);
            flag = false;
        }
        if (flag == true) {
            let arrayProducts = [];
            $.each($('#table-product tbody tr.blockProductMain'), function () {
                let productCode = $(this).find("td input.productCode").val();
                let unit = $(this).find("td .unit").val();
                let quantityOld = $(this).find("td input.quantityOld").val();
                let quantityNew = $(this).find("td input.quantityNew").val();
                let quantityDifference = $(this).find("td input.quantityDifference").val();
                let note = $(this).find("td input.note").val();

                if (type == 'insert_serial' && product_code == productCode){
                    quantityNew = parseInt(quantityNew) + 1;
                    quantityDifference = parseInt(quantityOld) - 1;
                }

                if (type == 'delete_serial' && product_code == productCode){
                    quantityNew = parseInt(quantityNew) - 1;
                    quantityDifference = parseInt(quantityOld) + 1;
                }

                arrayProducts.push(productCode, unit, quantityOld, quantityNew, quantityDifference, note);
            });
            $.ajax({
                url: laroute.route('admin.inventory-checking.submit-edit-product'),
                method: "POST",
                data: {
                    warehouseId: $('#warehouse').val(),
                    checkingCode: $('#checking-code').val(),
                    reason: $('#note').val(),
                    arrayProducts: arrayProducts,
                    status: 'draft',
                    id: $('#idHidden').val(),
                    serial: serial,
                    product_code: product_code
                },
                success: function () {
                    // swal(json["Cập nhật phiếu nhập thành công"], "", "success");
                    // location.reload();
                    InventoryChecking.getListProductInput();
                }
            })

        }
    })
}

var InventoryChecking = {
    getListProductInput : function(){
        $.ajax({
            url:laroute.route('admin.inventory-checking.get-list-product-input'),
            method:"POST",
            data: {
                inventory_checking_id : $('#idHidden').val()
            },
            success:function (data) {
                if(data.error == false){
                    $('.block-list-product-main').empty();
                    $('.block-list-product-main').append(data.view);
                    $('.inventory_checking_status').select2({
                        tags: true
                    });
                }
            }
        });
    },

    addSerialProduct : function(event,product_code,inventory_checking_detail_id){
        var serial = $('#input_product_'+inventory_checking_detail_id).val().replace(/\s/g, '').length;
        if(event.keyCode == 13 ){
            $.ajax({
                url:laroute.route('admin.inventory-checking.add-serial-product'),
                method:"POST",
                data: {
                    warehouseId: $('#warehouse').val(),
                    product_code : product_code,
                    inventory_checking_detail_id : inventory_checking_detail_id,
                    serial : $('#input_product_'+inventory_checking_detail_id).val(),
                    inventory_checking_status_id : $('#select_product_'+inventory_checking_detail_id).val()
                },
                success:function (data) {
                    $('#input_product_'+inventory_checking_detail_id).val('');
                    if(data.error == false){
                        // InventoryChecking.calculatorQuantity(inventory_checking_detail_id,'add');
                        saveProduct($('#input_product_'+inventory_checking_detail_id).val(),product_code,'insert_serial');
                    } else {
                        swal(data.message,'', "error");
                    }
                },
                error: function(res){
                    $('#input_product_'+inventory_checking_detail_id).val('');
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(mess_error,'', "error");
                }
            });

            // saveProduct($('#input_product_'+inventory_checking_detail_id).val(),product_code,'input_serial');
        }
    },

    showPopupListSerial:function(inventory_checking_detail_id){
        $.ajax({
            url:laroute.route('admin.inventory-checking.show-popup-list-serial'),
            method:"POST",
            data:{
                inventory_checking_detail_id : inventory_checking_detail_id
            },
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-list-serial').modal('show');
                    InventoryChecking.getListSerial();
                }
            }
        });
    },
    getListSerial : function(){
        $.ajax({
            url:laroute.route('admin.inventory-checking.get-list-serial'),
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
        InventoryChecking.getListSerial();
    },

    removeSearchSerial : function(){
        $('#serial').val('');
        $('#checking_status').val('').trigger('change');
        $('#type_resolve').val('').trigger('change');
        InventoryChecking.changePageSerial(1);
    },

    removeSerial : function(detailSerialId,inventory_checking_detail_id,productCode,serial, type = null){
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
                        url:laroute.route('admin.inventory-checking.remove-serial'),
                        method:"POST",
                        data:{
                            inventory_checking_detail_serial_id : detailSerialId,
                            inventory_checking_detail_id : inventory_checking_detail_id,
                            productCode : productCode,
                            inventory_checking_id : $('#idHidden').val(),
                            serial : serial
                        },
                        success:function (data) {
                            if(data.error == false){
                                swal(data.message,'', "success");
                                // InventoryInput.getListSerial();
                                InventoryChecking.calculatorQuantity(inventory_checking_detail_id,'minus');
                                if (type == 'list') {
                                    InventoryChecking.getListSerial();
                                }
                                saveProduct();
                            } else {
                                swal(data.message,'', "error");
                            }
                        }
                    });
                }
            });
        });
    },

    calculatorQuantity : function(inventory_checking_detail_id,type){
        var quantityOld = $('.quantityOld_'+inventory_checking_detail_id).val();
        var quantityNew = $('.quantityNew_'+inventory_checking_detail_id).val();
        if(type == 'add'){
            $('.quantityNew_'+inventory_checking_detail_id).val(parseInt(quantityNew) + 1 );
            $('.quantityDifference_'+inventory_checking_detail_id).val(parseInt(quantityOld) - ( parseInt(quantityNew) + 1 ));
        } else {
            $('.quantityNew_'+inventory_checking_detail_id).val(parseInt(quantityNew) - 1 );
            $('.quantityDifference_'+inventory_checking_detail_id).val(parseInt(quantityOld) - ( parseInt(quantityNew) - 1 ));
        }
    },

    showPopup : function () {
        $.ajax({
            url:laroute.route('admin.inventory-checking.show-popup-add-product'),
            method:"POST",
            data:{},
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-add-checking-product').modal('show');
                }
            }
        });
    },

    fileName: function () {
        var fileNamess = $('input[type=file]').val();
        $('#show').val(fileNamess);
    },

    addInventory: function(){
        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('inventory_checking_id', $('#idHidden').val());
        form_data.append('warehouse_id', $('#warehouse').val());
        form_data.append('status_detail', $('#status_detail').val());
        $.ajax({
            url: laroute.route("admin.inventory-checking.submit-add-product"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {

                // mApp.unblock(".modal-body");
                if (res.error == false) {
                    if (res.countError != 0){
                        $('#form-data-error').empty();
                        var n = 0;
                        $.map(res.dataError, function (val) {
                            var tpl = $('#tpl-data-error').html();
                            tpl = tpl.replace(/{keyNumber}/g, n);
                            tpl = tpl.replace(/{product_code}/g, val.product_code);
                            tpl = tpl.replace(/{serial}/g, val.serial);
                            tpl = tpl.replace(/{quantity_old}/g, val.quantity_old);
                            tpl = tpl.replace(/{quantity_new}/g, val.quantity_new);
                            tpl = tpl.replace(/{status_name}/g, val.status_name);
                            tpl = tpl.replace(/{error_message}/g, val.error_message);
                            n = n + 1;
                            $('#form-data-error').append(tpl);
                        });
                        $("#form-data-error").submit();
                    }
                    $('#popup-add-checking-product').modal('hide');
                    swal(res.message, "", "success").then(function(){
                        InventoryChecking.getListProductInput();

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

    showPopupSerialProduct : function(inventory_checking_detail_id,product_code,type){
        $.ajax({
            url:laroute.route('admin.inventory-checking.show-popup-serial-product'),
            method:"POST",
            data:{
                warehouse_id : $('#warehouse').val(),
                inventory_checking_detail_id : inventory_checking_detail_id,
                product_code : product_code,
                type_list : type,
                type : 'edit'
            },
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-list-serial-product').modal('show');
                }
            }
        });
    },

    listSerialProduct : function(){
        $.ajax({
            url:laroute.route('admin.inventory-checking.get-list-serial-product'),
            method:"POST",
            data: $('#form-list-serial-product').serialize(),
            success:function (data) {
                if(data.error == false){
                    $('.block-list-serial-product').empty();
                    $('.block-list-serial-product').append(data.view);
                }
            }
        });
    },

    changePageSerialProduct : function(page){
        $('#page_serial').val(page);
        InventoryChecking.listSerialProduct();
    },

    removeSearchSerialProduct : function(){
        $('#serial').val('');
        $('#checking_status').val('').trigger('change');
        $('#type_resolve').val('').trigger('change');
        InventoryChecking.changePageSerialProduct(1);
    },

    removeSerialProduct : function(detailSerialId,inventory_checking_detail_id,productCode,serial, type = null){
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
                        url:laroute.route('admin.inventory-checking.remove-serial'),
                        method:"POST",
                        data:{
                            inventory_checking_detail_serial_id : detailSerialId,
                            inventory_checking_detail_id : inventory_checking_detail_id,
                            productCode : productCode,
                            inventory_checking_id : $('#idHidden').val(),
                            serial : serial
                        },
                        success:function (data) {
                            if(data.error == false){
                                swal(data.message,'', "success");
                                // InventoryInput.getListSerial();
                                InventoryChecking.calculatorQuantity(inventory_checking_detail_id,'minus');
                                if (type == 'list') {
                                    InventoryChecking.listSerialProduct();
                                }
                                saveProduct();
                            } else {
                                swal(data.message,'', "error");
                            }
                        }
                    });
                }
            });
        });
    },
}