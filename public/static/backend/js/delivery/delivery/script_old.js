var edit = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#delivery_status').select2({
                placeholder: json['Chọn trạng thái']
            });
        });
    },
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    contact_name: {
                        required: true,
                        maxlength: 250
                    },
                    contact_phone: {
                        required: true,
                        integer: true,
                        maxlength: 10
                    },
                    contact_address: {
                        required: true,
                        maxlength: 250
                    },
                    total_transport_estimate: {
                        required: true,
                        integer: true,
                        min: 1
                    },
                },
                messages: {
                    contact_name: {
                        required: json['Hãy nhập người nhận'],
                        maxlength: json['Người nhận tối đa 250 kí tự']
                    },
                    contact_phone: {
                        required: json['Hãy nhập số điện thoại người nhận'],
                        integer: json['Số điện thoại người nhận không hợp lệ'],
                        maxlength: json['Số điện thoại người nhận tối đa 10 kí tự']
                    },
                    contact_address: {
                        required: json['Hãy nhập địa chỉ người nhận'],
                        maxlength: json['Địa chỉ người nhận tối đa 250 kí tự']
                    },
                    total_transport_estimate: {
                        required: json['Hãy nhập số lần giao dự kiến'],
                        integer: json['Số lần giao dự kiến không hợp lệ'],
                        min: json['Số lần giao dự kiến tối thiểu 1']
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }

            var is_actived = 0;
            if ($("#is_actived").is(':checked')) {
                is_actived = 1;
            }

            $.ajax({
                url: laroute.route('delivery.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    delivery_id: id,
                    contact_name: $('#contact_name').val(),
                    contact_phone: $('#contact_phone').val(),
                    contact_address: $('#contact_address').val(),
                    total_transport_estimate: $('#total_transport_estimate').val(),
                    // delivery_status: $('#delivery_status').val(),
                    is_actived: is_actived
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('delivery');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('delivery');
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                },
                error: function (res) {
                    console.log(res);
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                }
            });
        });
    }
};

var createHistory = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#transport_id').select2({
                placeholder: json['Chọn hình thức giao']
            });

            $('#delivery_staff').select2({
                placeholder: json['Chọn nhân viên']
            });

            $('#pick_up').select2({
                placeholder: json['Chọn nơi lấy hàng']
            });
        });

        new AutoNumeric.multiple('#amount' ,{
            currencySymbol : '',
            decimalCharacter : '.',
            digitGroupSeparator : ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });

        $("#time_ship").datetimepicker({
            todayHighlight: !0,
            autoclose: !0,
            pickerPosition: "bottom-left",
            format: "dd/mm/yyyy hh:ii",
            // minDate: new Date(),
            // locale: 'vi'
        });

        $('.quantity').ForceNumericOnly();
    },

    changeQuantity(deliveryId) {
        $.getJSON(laroute.route('translate'), function (json) {
            delivery.priceCod();
            var continute = true;
            var quantityAll = 0;
            var arrProduct = [];

            $.each($('#table_product').find(".tr_product"), function () {
                var quantity = parseInt($(this).find('.quantity').val());
                var quantityOld = parseInt($(this).find('.quantity_old').val());
                var productId = $(this).find($('.product_id')).val();

                if ($(this).find('.quantity').val() == '') {
                    $(this).find('.quantity').val(0);
                    quantity = 0;
                }

                if (parseInt(quantity) > parseInt(quantityOld)) {
                    $('.error_quantity_' + productId + '').text(json['Vượt quá số lượng']);
                    continute = false;
                } else {
                    $('.error_quantity_' + productId + '').text('');
                    quantityAll += quantity;
                }

                arrProduct.push({
                    object_id: productId,
                    quantity: quantity,
                });
            });

            if (continute == true) {
                $.ajax({
                    url: laroute.route('delivery.load-amount'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        delivery_id: deliveryId,
                        quantityAll: quantityAll,
                        arrProduct: arrProduct
                    },
                    success: function (res) {
                        $('#amount').val(formatNumber(res.amount.toFixed(decimal_number)));
                    }
                });
            }
        });
    },
    changeSKU: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var tmp = $('.sku_' + id).val();
            if (tmp != '' || tmp != null) {
                var check = /^[a-z|0-9]+$/i.test(tmp);
                if (tmp.length > 250) {
                    $('.error_sku_' + id + '').text(json['SKU vượt quá 250 ký tự']);
                    continute = false;
                } else {
                    $('.error_sku_' + id + '').text('');
                    if (tmp.length > 0) {
                        if (check == false) {
                            $('.error_sku_' + id + '').text(json['SKU bao gồm số và chữ không dấu']);
                            continute = false;
                        } else {
                            $('.error_sku_' + id + '').text('');
                        }
                    }
                }
            }
        });
    },
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    contact_name: {
                        required: true,
                        maxlength: 250
                    },
                    contact_phone: {
                        required: true,
                        integer: true,
                        maxlength: 10
                    },
                    contact_address: {
                        required: true,
                        maxlength: 250
                    },
                    product: {
                        required: true
                    },
                    amount: {
                        required: true
                    },
                    transport_code: {
                        maxlength: 250
                    },
                    // time_ship: {
                    //     required: true
                    // },
                    pick_up: {
                        required: true,
                        maxlength: 250
                    },
                    // delivery_staff: {
                    //     required: true
                    // }
                },
                messages: {
                    contact_name: {
                        required: json['Hãy nhập người nhận'],
                        maxlength: json['Người nhận tối đa 250 kí tự']
                    },
                    contact_phone: {
                        required: json['Hãy nhập số điện thoại người nhận'],
                        integer: json['Số điện thoại người nhận không hợp lệ'],
                        maxlength: json['Số điện thoại người nhận tối đa 10 kí tự']
                    },
                    contact_address: {
                        required: json['Hãy nhập địa chỉ người nhận'],
                        maxlength: json['Địa chỉ người nhận tối đa 250 kí tự']
                    },
                    product: {
                        required: json['Hãy chọn sản phẩm cần giao']
                    },
                    amount: {
                        required: json['Hãy nhập số tiền cần thu']
                    },
                    transport_code: {
                        maxlength: json['Mã đơn vị vận chuyển tối đa 250 kí tự']
                    },
                    // time_ship: {
                    //     required: json['Hãy chọn thời gian giao hàng dự kiến']
                    // },
                    pick_up: {
                        required: json['Hãy chọn nơi lấy hàng'],
                        // maxlength: json['Nơi lấy hàng tối đa 250 kí tự']
                    },
                    // delivery_staff: {
                    //     required: json['Hãy chọn nhân viên giao hàng'],
                    // }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var continute = true;
            var arrProduct = [];
            var quantityCheck = 0;

            $.each($('#table_product').find(".tr_product"), function () {
                var quantity = parseInt($(this).find('.quantity').val());
                var quantityOld = parseInt($(this).find('.quantity_old').val());
                var productId = $(this).find($('.product_id')).val();
                // var sku = $(this).find($('.sku')).val();

                if (parseInt(quantity) > parseInt(quantityOld)) {
                    $('.error_quantity_' + productId + '').text(json['Vượt quá số lượng']);
                    continute = false;
                } else {
                    $('.error_quantity_' + productId + '').text('');
                }

                // if (sku != null && sku != '') {
                //     if (sku.length > 255) {
                //         $('.error_sku_' + productId + '').text(json['SKU vượt quá 250 ký tự']);
                //         continute = false;
                //     } else {
                //         var check = /^[a-z|0-9]+$/i.test(sku);
                //         if (check == false) {
                //             $('.error_sku_' + productId + '').text(json['SKU bao gồm số và chữ không dấu']);
                //         } else {
                //             $('.error_sku_' + productId + '').text('');
                //             $.each($('#table_product').find(".tr_product"), function () {
                //                 var productIdTmp = $(this).find($('.product_id')).val();
                //                 var skuTmp = $(this).find($('.sku')).val();
                //                 if (productId != productIdTmp && sku == skuTmp) {
                //                     $('.error_sku_' + productId + '').text(json['SKU bị trùng']);
                //                     continute = false;
                //                 }
                //             })
                //         }
                //     }
                // } else {
                //     $('.error_sku_' + productId + '').text('');
                // }

                quantityCheck += quantity;

                arrProduct.push({
                    object_type: $(this).find($('.object_type')).val(),
                    object_id: productId,
                    object_code: $(this).find($('.object_code')).val(),
                    object_name: $(this).find($('.object_name')).val(),
                    quantity: quantity,
                    note: $(this).find($('.note')).val(),
                    // sku: $(this).find($('.sku')).val(),
                    price: $(this).find($('.price')).val().replace(new RegExp('\\,', 'g'), '')
                });
            });

            if (arrProduct.length == 0) {
                $('.error-table').text(json['Hãy chọn sản phẩm giao hàng']);
                continute = false;
            } else {
                $('.error-table').text('');
            }

            if (quantityCheck == 0) {
                $('.error-table').text(json['Hãy chọn sản phẩm giao hàng']);
                continute = false;
            } else {
                $('.error-table').text('');
            }

            if (continute == true) {

                var is_insurance = 0;

                if($('#is_insurance').is(':checked')){
                    is_insurance = 1;
                }

                var is_post_office = 0;

                if($('#is_post_office').is(':checked')){
                    is_post_office = 1;
                }

                var is_cod_amount = 0;

                if($('#is_cod_amount').is(':checked')){
                    is_cod_amount = 1;
                }

                var service_id = $('input[name="is_partner"]:checked').attr('data-service-id');
                var service_type_id = $('input[name="is_partner"]:checked').attr('data-service-type-id');

                $.ajax({
                    url: laroute.route('delivery.store-history'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        delivery_id: id,
                        transport_id: $('#transport_id').val(),
                        transport_code: $('#transport_code').val(),
                        delivery_staff: $('#delivery_staff').val(),
                        contact_phone: $('#contact_phone').val(),
                        contact_address: $('#contact_address').val(),
                        contact_name: $('#contact_name').val(),
                        amount: $('#amount').val().replace(new RegExp('\\,', 'g'), ''),
                        note: $('#note').val(),
                        arrProduct: arrProduct,
                        order_id: $('#order_id').val(),
                        time_ship: $('#time_ship').val(),
                        time_ship_staff: $('#time_ship_staff').val(),
                        pick_up: $('#pick_up').select2('data')[0].text,
                        warehouse_id: $('#pick_up').val(),
                        province_id : $('#province_id').val(),
                        district_id : $('#district_id').val(),
                        ward_id : $('.ward_id').val(),
                        weight : $('#weight').val(),
                        type_weight : $('#type_weight').val(),
                        length : $('.length_input').val(),
                        width : $('.width_input').val(),
                        height : $('.height_input').val(),
                        shipping_unit : $('.selectReceiptProduct').val(),
                        required_note : $('.required_note').val(),
                        is_post_office : is_post_office,
                        is_insurance : is_insurance,
                        is_cod_amount : is_cod_amount,
                        is_partner : $('input[name="is_partner"]:checked').val(),
                        service_id : service_id,
                        service_type_id : service_type_id,
                        fee : $('.input_fee_'+service_id+'_'+service_type_id).val(),
                        name_service : $('.input_name_'+service_id+'_'+service_type_id).val(),
                        total_fee : $('.total_fee_input').val(),
                        insurance_fee : $('#insurance_amount').val(),
                        amount_cod: $('#amount_cod').val().replace(new RegExp('\\,', 'g'), ''),
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('delivery');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('delivery');
                                }
                            });
                        } else {
                            swal(res.message, '', "error");
                        }
                    },
                    error: function (res) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Tạo phiếu giao hàng thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
};

var detail = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.status').select2({
                placeholder: json['Chọn trạng thái']
            });
        });
    },
    modalConfirmReceipt: function (historyId) {
        $.ajax({
            url: laroute.route('delivery.modal-confirm-receipt'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                delivery_history_id: historyId
            },
            success:function (res) {
                $('#my-modal').html(res.url);
                $('#modal-confirm').modal('show');

                $.each($('.payment_method').find('.method'), function () {
                    let getId = $(this).find("input[name='payment_method']").attr('id');
                    //Load js format tiền phương thức thanh toán
                    new AutoNumeric.multiple("#" + getId + "", {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                });

                new AutoNumeric.multiple('#total' ,{
                    currencySymbol : '',
                    decimalCharacter : '.',
                    digitGroupSeparator : ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });

            }
        });
    },
    confirmReceipt: function (historyId) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-payment');

            form.validate({
                rules: {
                    total: {
                        required: true
                    }
                },
                messages: {
                    total: {
                        required: json['Hãy nhập tổng tiền cần thanh toán']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            let arrayMethod = [];

            $.each($('.payment_method').find('.method'), function () {
                let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                let getId = $(this).find("input[name='payment_method']").attr('id');
                let methodCode = getId.slice(15);
                let transactionCode = $(this).find("input[name='payment_transaction_code']").val();
                let paymentDetailId = $(this).find("input[name='delivery_history_payment_detail_id']").val();

                arrayMethod.push({
                    'payment_method_code': methodCode,
                    'money' : moneyEachMethod,
                    'payment_transaction_code': transactionCode,
                    'delivery_history_payment_detail_id': paymentDetailId
                });
            });

            $.ajax({
                url: laroute.route('delivery.confirm-receipt'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    delivery_history_id: historyId,
                    total: $('#total').val(),
                    note: $('#note').val(),
                    delivery_payment_id: $('#delivery_payment_id').val(),
                    arrayMethod: arrayMethod
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.reload();
                            }
                            if (result.value == true) {
                                window.location.reload();
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                }
            });
        });

    },
    save: function (deliveryId) {
        var arrHistory = [];

        $.each($('#table_history').find(".tr_history"), function () {
            arrHistory.push({
                delivery_history_id: $(this).find($('.delivery_history_id')).val(),
                status: $(this).find($('.status')).val()
            });
        });

        $.ajax({
            url: laroute.route('delivery.save-detail'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                arrHistory: arrHistory,
                delivery_id: deliveryId
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.reload();
                        }
                        if (result.value == true) {
                            window.location.reload();
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    detailHistory: function (deliveryHistoryId) {
        $.ajax({
            url: laroute.route('delivery.detail-history'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deliver_history_id: deliveryHistoryId
            },
            success: function (res) {
                $('#my-modal').html(res.url);
                $('#modal-detail').modal('show');
            }
        });
    },
    editHistory: function (deliveryHistoryId) {
        $.ajax({
            url: laroute.route('delivery.edit-history'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deliver_history_id: deliveryHistoryId
            },
            success: function (res) {
                $('#my-modal').html(res.url);
                $('#modal-edit').modal('show');

                $.getJSON(laroute.route('translate'), function (json) {
                    $('#delivery_staff').select2({
                        placeholder: json['Chọn nhân viên']
                    });
                });

                $("#time_ship").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    // minDate: new Date(),
                    // locale: 'vi'
                });
            }
        });
    },
    submitEditHistory: function (deliveryHistoryId) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    time_ship: {
                        required: true
                    },
                    delivery_staff: {
                        required: true
                    }
                },
                messages: {
                    time_ship: {
                        required: json['Hãy chọn thời gian giao hàng dự kiến']
                    },
                    delivery_staff: {
                        required: json['Hãy chọn nhân viên giao hàng'],
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('delivery.update-history'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    delivery_history_id: deliveryHistoryId,
                    time_ship: $('#time_ship').val(),
                    delivery_staff: $('#delivery_staff').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.reload();
                            }
                            if (result.value == true) {
                                window.location.reload();
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                }
            });
        });
    },
    changeAmountReceipt: function (obj) {
        let total = 0
        $.each($('.payment_method').find('.method'), function () {
            let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
            total += Number(moneyEachMethod);
        });


        $('#total').val(formatNumber(total.toFixed(decimal_number)));
    },
};

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
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