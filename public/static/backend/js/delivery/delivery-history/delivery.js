var delivery = {
    changeProvince: function(){
        $.ajax({
            url: laroute.route('admin.order.changeProvince'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                province_id : $('#province_id').val(),
            },
            success: function (res) {
                if (res.error == false){
                    $('.district_id').html(res.view);
                    $('.ward_id').html(res.view1);
                    $('.district_id').select2();
                    $('.ward_id').select2();
                } else {
                    swal(res.message, "", "error");
                }
                delivery.previewOrder();
            }
        });
    },

    changeDistrict: function(){
        $.ajax({
            url: laroute.route('admin.order.changeDistrict'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                district_id : $('#district_id').val(),
            },
            success: function (res) {
                if (res.error == false){
                    $('.ward_id').html(res.view);
                    $('.ward_id').select2();
                } else {
                    swal(res.message, "", "error");
                }
                delivery.previewOrder();
            }
        });
    },

    changeValue: function(delivery_id,objectId,type){
        var quantity = $('.tr_'+objectId+' .quantity').val();

        if(quantity == ''){
            quantity = 0;
        }
        quantity = parseFloat(quantity);

        if(type == 'minus'){
            quantity = quantity - 1;
            if(quantity < 0){
                quantity = 0;
            }
        } else {
            quantity = quantity + 1;
        }

        if (decimalQuantity == 0){
            quantity = parseInt(quantity);
        }

        $('.tr_'+objectId+' .quantity').val(quantity);
        createHistory.changeQuantity(delivery_id);
        delivery.priceCod();
        delivery.previewOrder();
    },

    changeAddress : function () {
        var attrAddress = $('.pick_up option:selected').attr('data-address');
        $('.pick_up_address').html(attrAddress);
        delivery.previewOrder();
    },

    changeReceiptProduct : function () {
        var receiptProduct = $('.selectReceiptProduct option:selected').val();
        if (receiptProduct == 'staff'){
            $('.block-receipt-product').hide();
            $('.block-receipt-staff').show();
            $('.staff_hide').hide();
        } else {
            $('.block-receipt-staff').hide();
            $('.block-receipt-product').show();
            $('.staff_hide').show();
        }

        delivery.previewOrder();
    },

    changePostOffice: function () {
        // if($('#is_post_office').is(':checked')){
        //     AutoNumeric.multiple('.length_input,.width_input,.height_input', {
        //         currencySymbol: '',
        //         decimalCharacter: '.',
        //         digitGroupSeparator: ',',
        //         decimalPlaces: 0,
        //         eventIsCancelable: true,
        //         minimumValue: 0,
        //         maximumValue: 100,
        //     });
        // } else {
        //     AutoNumeric.multiple('.length_input,.height_input', {
        //         currencySymbol: '',
        //         decimalCharacter: '.',
        //         digitGroupSeparator: ',',
        //         decimalPlaces: 0,
        //         eventIsCancelable: true,
        //         minimumValue: 0,
        //         maximumValue: 50,
        //     });
        //
        //     AutoNumeric.multiple('.width_input', {
        //         currencySymbol: '',
        //         decimalCharacter: '.',
        //         digitGroupSeparator: ',',
        //         decimalPlaces: 0,
        //         eventIsCancelable: true,
        //         minimumValue: 0,
        //         maximumValue: 30,
        //     });
        // }
    },

    previewOrder : function(){
        if($('.selectReceiptProduct').val() == 'delivery_unit'){
            var continute = true;
            var arrProduct = [];
            var quantityCheck = 0;

            $.each($('#table_product').find(".tr_product"), function () {
                var quantity = parseFloat($(this).find('.quantity').val());
                var quantityOld = parseFloat($(this).find('.quantity_old').val());
                var productId = $(this).find($('.product_id')).val();

                if (parseFloat(quantity) > parseFloat(quantityOld)) {
                    continute = false;
                }

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
                continute = false;
            }

            if (quantityCheck == 0) {
                continute = false;
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
                    url: laroute.route('delivery.preview-order'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
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
                    },
                    success: function (res) {
                        if (res.error == false) {
                            $('.block-receipt-product-select').html(res.view);
                            // $('.block-receipt-product-select').html(res.view);
                            $('.insurance').text(formatNumber(res.insurance));
                            $('#insurance_amount').val(res.insurance);
                            $('#time_ship').val(res.expected_delivery_time);
                            $('.expected_delivery_time').text(res.expected_delivery_time_input);
                            $('.total_fee').text(formatNumber(res.total_fee));
                            $('.total_fee_input').val(res.total_fee);
                            $('.fee_'+service_id+'_'+service_type_id).text(formatNumber(res.fee));
                            $('.input_fee_'+service_id+'_'+service_type_id).val(res.fee);
                        } else {
                            $('.block-receipt-product-select').html(res.view);
                        }
                    },
                    error: function (res) {
                    }
                });
            }
        }

    },

    // Tính lại tiền thu hộ
    priceCod: function (){
        var totalPrice = 0;
        $.each($('#table_product').find(".tr_product"), function () {
            var quantity = parseFloat($(this).find('.quantity').val());
            var price = $(this).find($('.price')).val().replace(new RegExp('\\,', 'g'), '');

            totalPrice = totalPrice + (quantity * price);
        });
        totalPrice = totalPrice + parseFloat($('#total_fee').val());

        var amount = $('#amount_hidden').val().replace(new RegExp('\\,', 'g'), '');
        if (totalPrice > amount){
            totalPrice = amount;
        }
        if(totalPrice < 0){
            totalPrice = 0;
        }
        $('.text-cod').text(formatNumber(totalPrice));
        $('#amount_cod').val(totalPrice);
    }
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
