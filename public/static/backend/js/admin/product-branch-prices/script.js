// filter();
$(document).ready(function () {
    // Nút check toàn bộ
    $('#check_all_branch').click(function () {
        $('.check:checkbox').prop('checked', this.checked);

    });

    // Nhập giá khác cho toàn bộ table
    $('#price_standard').keyup(function () {
        var newPrice = $(this).val();

        if (newPrice == '') {
            $.ajax({
                url: laroute.route('admin.product-branch-price.list-branch-price'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id: $('#product_child_id').val()
                },
                success: function (data) {
                    jQuery.each(data, function (key, val) {
                        $('#' + val.product_branch_price_id).val(val.new_price);
                    });
                }
            });
        } else {
            $('#table_branch .branch_tb').each(function () {
                $(this).find('input:text').each(function () {
                    $(this).val(newPrice);
                });
            });
        }
    });

    // Nút submit chỉnh sửa giá
    $('#btn').click(function () {
        var listBranch = [];

        $('#table_branch .branch_tb').each(function () {
            var values = [];

            $(this).find('input:hidden').each(function () {
                values.push($(this).val());
            });
            $(this).find('input:text').each(function () {
                values.push($(this).val());
            });
            $(this).find('input:checkbox').each(function () {
                values.push($(this).prop("checked"));
            });

            listBranch.push(values);
        });
        $.ajax({
            url: laroute.route('admin.product-branch-price.submit-edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                listBranch: listBranch,
                product_child_id: $('#product_child_id').val()
            },
            success: function (data) {
                $.getJSON(laroute.route('translate'), function (json) {
                    // window.location = laroute.route('admin.product-branch-price');
                    swal(
                        json['Cập nhật giá sản phẩm thành công'],
                        '',
                        'success'
                    );
                });
                setTimeout(function () {
                    location.reload();
                }, 1500);
            }
        });

    });

    // Chọn danh sách sản phẩm
    $('#product_id').on('change', function () {
        var listProductId = $('#product_id').val();
        $('#branch_id').val(0).change();
        if (listProductId.length != 0) {
            $('#branch_id').removeAttr('disabled');

            $.ajax({
                url: laroute.route('admin.product-branch-price.list-product-child'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    listProductId: listProductId
                },
                success: function (data) {
                    var stt = 0;
                    $('#table_branch > tbody > .branch_tb').remove();
                    jQuery.each(data, function (key, val) {
                        stt++;
                        $('#table_branch > tbody').append('<tr class="branch_tb">' +
                            // '<td>' + val.product_child_id + '</td>' +
                            '<td>' + stt + '</td>' +
                            '<td>' + val.product_child_name + '<input type="hidden" name="id_product[]" value="' + val.product_child_id + '"></td>' +
                            '<td class="ss--text-center">' + (val.price) + '<input type="hidden" value="' + val.price + '"></td>' +
                            '<td class="ss--text-center ss--width-150"><input class="new form-control m-input price_branch_' + val.product_child_id + ' ss--btn-ct ss--text-center" name="new_price" value="' + (val.price) + '"></td>' +
                            '<td><label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success pull-right m--margin-bottom-20">' +
                            '<input class="check check_branch_' + val.product_child_id + '" ' + ((val.is_actived == 1) ? 'checked' : '') + ' name="check_branch[]" type="checkbox"><span></span></label>' +
                            '</td>' +
                            '</tr>');
                        new AutoNumeric.multiple('.price_branch_' + val.product_child_id + '', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            minimumValue: 0
                        });
                    });

                }
            });
        } else {
            $('#branch_id').attr('disabled', 'disabled');
            $('#branch_id').val(0).change();
            $('#table_branch > tbody > .branch_tb').remove();
            $('#table_branch > tbody').append('<tr class="branch_tb"><td align="center" colspan="8">Tạm thời chưa có dữ liệu.</td></tr>');
        }
        if (listProductId == 0) {
            $('#branch_id').attr('disabled', 'disabled');
        }
    });

    // Chọn danh sách chi nhánh
    $('#branch_id').change(function () {
        var branchId = $(this).val();
        var productId = $('#product_id').val();
        if (branchId != 0) {
            // loadPrice(branchId);
            $('#price').removeAttr('disabled');
            $.ajax({
                url: laroute.route('admin.product-branch-price.change-branch'),
                method: "POST",
                data: {
                    // productId: productId
                    branchId : branchId
                },
                success: function (res) {
                    $('#price').empty();
                    $('#price').append(res.view);
                    $('#price').select2();
                }
            });

        } else {
            $('#price').val(0).change();
            $('#price').attr('disabled', 'disabled');
        }
    });

    // Chọn danh sách chi nhánh sao chép
    $('#price').change(function () {
        var branchId = $(this).val();
        loadPrice(branchId);
        if (branchId != 0) {
            $.ajax({
                url: laroute.route('admin.product-branch-price.list-config'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    branchId: branchId
                },
                ///
                success: function (data) {
                    jQuery.each(data[1], function (key, val) {
                        $('.price_branch_' + val.product_id).val((val.new_price));
                    });
                }
            });
        } else {
            branchId = $('#branch_id').val();
            loadPrice(branchId);
        }
    });

    // Nút submit cấu hình giá
    $('#btnSubmitChange').click(function () {
        var branchId = $('#branch_id').val();
        var listProduct = [];
        $('#table_branch .branch_tb').each(function () {
            var values = [];

            $(this).find('input:hidden').each(function () {
                values.push($(this).val());
            });
            $(this).find('input:text').each(function () {
                values.push($(this).val().replace(new RegExp('\\,', 'g'), ''));
            });
            $(this).find('input:checkbox').each(function () {
                values.push($(this).prop("checked"));
            });

            listProduct.push(values);
        });

        if (branchId != 0) {
            $('.error-branch-config').text('');
            $.ajax({
                url: laroute.route('admin.product-branch-price.submit-config'),
                type: 'POST',
                dataType: 'json',
                data: {
                    branchId: branchId,
                    listProduct: listProduct
                },
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        // window.location = laroute.route('admin.product-branch-price');
                        swal(
                            json['Cấu hình giá sản phẩm thành công'],
                            '',
                            'success'
                        );
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            });
        } else {
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error-branch-config').text(json['Vui lòng chọn chi nhánh']);
            });
        }
        if ($('#product_id').val() == 0) {
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error-product-config').text(json['Vui lòng chọn sản phẩm']);
            });
        } else {
            $('.error-product-config').text('');
        }

    });

    new AutoNumeric.multiple('.new', {
        currencySymbol : '',
        decimalCharacter : '.',
        digitGroupSeparator : ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });
});
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.product-branch-price.list')
});

// Load thông tin giá
function loadPrice(branchId) {
    $('#table_branch .branch_tb').each(function () {
        $(this).find('input:text').each(function () {
            $(this).val('0');
        });
    });

    $.ajax({
        url: laroute.route('admin.product-branch-price.list-config'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            branchId: branchId,
            product: $('#product_id').val()
        },
        success: function (data) {
            // $.getJSON(laroute.route('translate'), function (json) {
            //     $('#price option').remove();
            //     $('#price').append($('<option>', {
            //         value: 0,
            //         text: json['Chọn chi nhánh']
            //     }));
            // });
            // jQuery.each(data[0], function (index, val) {
            //     $('#price').append($('<option>', {
            //         value: index,
            //         text: val
            //     }));
            // });

            // jQuery.each(data[1], function (key, val) {
            //     $('.price_branch_' + val.product_id).val((val.new_price));
            //     $('.check_branch_' + val.product_id).prop('checked', val.is_actived);
            //
            //     // new AutoNumeric.multiple('.price_branch_' + val.product_id + '', val.new_price, {
            //     //     currencySymbol: '',
            //     //     decimalCharacter: '.',
            //     //     digitGroupSeparator: ',',
            //     //     decimalPlaces: decimal_number,
            //     // });
            // });
        }
    });
}

$('select[name="products$product_category_id"]').select2().on('select2:select', function () {
    filter();
});

// $("#tb-branch-price").tableHeadFixer({"head": false, "left": 3});

$('input[name="search_keyword"]').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});
$('input[name="search_keyword"]').bind("enterKey", function (e) {
    filter();
});

function filter() {
    // $.ajax({
    //     url: laroute.route('admin.product-branch-price.filter'),
    //     method: "POST",
    //     data: {
    //         keyword: $('input[name="search_keyword"]').val(),
    //         productCategory: $('select[name="products$product_category_id"]').val()
    //     },
    //     success: function (data) {
    //         $('#list-data').empty();
    //         $('#list-data').append(data);
    //     }
    // })
}

$(document).ready(function () {
    // $("#tb-branch-price").tableHeadFixer({"head": false, "left": 3});

});

function pageClickFilter(page) {
    $.ajax({
        url: laroute.route('admin.product-branch-price.paging-filter'),
        method: "POST",
        data: {
            keyword: $('input[name="search_keyword"]').val(),
            productCategory: $('select[name="products$product_category_id"]').val(),
            page: page
        },
        success: function (data) {
            $('#list-data').empty();
            $('#list-data').append(data);
        }
    })
}

function refresh() {
    $('input[name="search_keyword"]').val('');
    $('select[name="products$product_category_id"]').val('').trigger('change');
    // filter();
}

function formatNumber(num) {
    if (num != null || num != '') {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    } else {
        return num;
    }

}

// $(document).on('keyup', "input[name='new_price']", function () {
    // var n = parseInt($(this).val().replace(/\D/g, ''), 10);
    // if (typeof n == 'number' && Number.isInteger(n))
    //     $(this).val(n.toLocaleString());
    // else {
    //     $(this).val('');
    // }
    //do something else as per updated question
    // myFunc(); //call another function too
// });




