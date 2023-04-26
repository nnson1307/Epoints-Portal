var detail = {
    _init:function () {
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


        // $('#amount').mask('000,000,000', {reverse: true});

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
                success:function (res) {
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

};

var edit = {
    _init:function () {
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


        $('#amount').mask('000,000,000', {reverse: true});

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
    save:function (deliveryHistoryId) {
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
                    // time_ship: {
                    //     required: true
                    // },
                    // delivery_staff: {
                    //     required: true
                    // },
                    transport_code: {
                        maxlength: 250
                    },
                    pick_up: {
                        required: true,
                        maxlength: 250
                    }
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
                    time_ship: {
                        required: json['Hãy chọn thời gian giao hàng dự kiến']
                    },
                    delivery_staff: {
                        required: json['Hãy chọn nhân viên giao hàng'],
                    },
                    transport_code: {
                        maxlength: json['Mã đơn vị vận chuyển tối đa 250 kí tự']
                    },
                    pick_up: {
                        required: json['Hãy nhập nơi lấy hàng'],
                        // maxlength: json['Nơi lấy hàng tối đa 250 kí tự']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var continute = true;
            var arrProduct = [];
            var quantityCheck = 0;

            $.each($('#table_product').find(".tr_product"), function () {
                var quantity = parseFloat($(this).find('.quantity').val());
                var quantityOld = parseFloat($(this).find('.quantity_old').val());
                var productId = $(this).find($('.product_id')).val();
                // var sku = $(this).find($('.sku')).val();

                if (decimalQuantity == 0){
                    quantity = parseInt(quantity);
                    quantityOld = parseInt(quantityOld);
                    $(this).find('.quantity').val(quantity);
                }

                if (quantity > quantityOld) {
                    $('.error_quantity_' + productId + '').text(json['Vượt quá số lượng']);
                    continute = false;
                } else {
                    $('.error_quantity_' + productId + '').text('');
                }

                quantityCheck += quantity;

                arrProduct.push({
                    delivery_detail_id: $(this).find($('.delivery_detail_id')).val(),
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

                if ($('#is_insurance').is(':checked')) {
                    is_insurance = 1;
                }

                var is_post_office = 0;

                if ($('#is_post_office').is(':checked')) {
                    is_post_office = 1;
                }

                var is_cod_amount = 0;

                if ($('#is_cod_amount').is(':checked')) {
                    is_cod_amount = 1;
                }

                var service_id = $('input[name="is_partner"]:checked').attr('data-service-id');
                var service_type_id = $('input[name="is_partner"]:checked').attr('data-service-type-id');

                $.ajax({
                    url: laroute.route('delivery-history.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        delivery_history_id: deliveryHistoryId,
                        contact_name: $('#contact_name').val(),
                        contact_phone: $('#contact_phone').val(),
                        contact_address: $('#contact_address').val(),
                        time_ship: $('#time_ship').val(),
                        delivery_staff: $('#delivery_staff').val(),
                        transport_code: $('#transport_code').val(),
                        note: $('#note').val(),
                        transport_id: $('#transport_id').val(),

                        amount: $('#amount').val().replace(new RegExp('\\,', 'g'), ''),
                        arrProduct: arrProduct,
                        order_id: $('#order_id').val(),
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
                    success:function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    // window.location.reload();
                                    window.location.href = laroute.route('delivery');
                                }
                                if (result.value == true) {
                                    // window.location.reload();
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
            }
        });
    }
};

var createHistory = {
    changeQuantity(deliveryId) {
        $.getJSON(laroute.route('translate'), function (json) {
            delivery.priceCod();
            var continute = true;
            var quantityAll = 0;
            var arrProduct = [];

            $.each($('#table_product').find(".tr_product"), function () {
                var quantity = parseFloat($(this).find('.quantity').val());
                var quantityOld = parseFloat($(this).find('.quantity_old').val());
                var productId = $(this).find($('.product_id')).val();

                if (decimalQuantity == 0){
                    quantity = parseInt(quantity);
                    quantityOld = parseInt(quantityOld);
                    $(this).find('.quantity').val(quantity);
                }

                if ($(this).find('.quantity').val() == '') {
                    $(this).find('.quantity').val(0);
                    quantity = 0;
                }

                if (quantity > quantityOld) {
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
}

var listHistory = {
    print: function (ghn_order_code,partner,ghn_shop_id) {
        // Swal.fire({
        //     title: 'Chọn  kích thước in',
        //     buttonsStyling: false,
        //
        //     showCancelButton: false,
        //     showConfirmButton: false,
        //     html:
        //         '<button type="button" class="btn btn-primary color_button btn-search" onClick="listHistory.printSelect(`'+ghn_order_code+'`,`'+partner+'`,`'+ghn_shop_id+'`,`printA5`)">A5</button><button type="button" class="btn btn-primary color_button btn-search" onClick="listHistory.printSelect(`'+ghn_order_code+'`,`'+partner+'`,`'+ghn_shop_id+'`,`print80x80`)">80x80</button><button type="button" class="btn btn-primary color_button btn-search" onClick="listHistory.printSelect(`'+ghn_order_code+'`,`'+partner+'`,`'+ghn_shop_id+'`,`print52x70`)">52x70</button>',
        // })

        $.ajax({
            url: laroute.route('delivery-history.show-popup-print'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                ghn_order_code: ghn_order_code,
                partner: partner,
                ghn_shop_id: ghn_shop_id,
            },
            success: function (res) {
                if(res.error == false){
                    $('#my-modal-print').append(res.view);
                    $('#popup-print-size').modal('show');
                } else {
                    swal(res.message, "", "error");
                }
            }
        });
    },

    printSelect: function (ghn_order_code,partner,ghn_shop_id,print_text) {
        $.ajax({
            url: laroute.route('delivery-history.print'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                ghn_order_code: ghn_order_code,
                method: partner,
                shop_id: ghn_shop_id,
                print_text: print_text
            },
            success: function (res) {
                if (res.error == false){
                    window.open(res.url, '_blank');
                } else {
                    swal(res.message, "", "error");
                }
            }
        });
    }
}

$('#autotable').PioTable({
    baseUrl: laroute.route('delivery-history.list')
});