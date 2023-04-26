var processPayment;
processPayment = {
    jsonTranslate: null,
    editPaymentAction: function (type = 'order') {
        // update order, order detail

        const resultPromise = new Promise((res, rej) => {
            //Lưu thông tin cho đơn hàng mới

            var continute = true;
            var table_subbmit = [];
            var check_service_card = [];

            $.each($('#table_add').find('.tr_table'), function () {
                var orderDetailId = $(this).find("input[name='id_detail']").val()
                var objectId = $(this).find("input[name='id']").val()
                var objectName = $(this).find("input[name='name']").val()
                var objectType = $(this).find("input[name='object_type']").val()
                var objectCode = $(this).find("input[name='object_code']").val()
                var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '')
                var quantity = $(this).find("input[name='quantity']").val()
                var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '')
                var voucherCode = $(this).find("input[name='voucher_code']").val()
                var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '')
                var isChangePrice = $(this).find("input[name='is_change_price']").val()
                var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val()
                var numberRow = $(this).find("input[name='numberRow']").val()
                var note = $(this).find("input[name='note']").val()

                if (amount < 0) {
                    $('.error-table').text(order.jsonLang['Tổng tiền không hợp lệ'])
                    continute = false
                }

                var number = $(this).find("input[name='number_tr']").val();

                var staffId = $(this).closest('tbody').find('.staff_' + number + '').val();

                var arrayAttach = []

                //Lấy sản phẩm/dịch vụ kèm theo
                $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                    var orderDetailIdAttach = $(this).find("input[name='id_detail']").val()
                    var objectIdAttach = $(this).find("input[name='object_id']").val()
                    var objectNameAttach = $(this).find("input[name='object_name']").val()
                    var objectTypeAttach = $(this).find("input[name='object_type']").val()
                    var objectCodeAttach = $(this).find("input[name='object_code']").val()
                    var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '')
                    var quantityAttach = $(this).find("input[name='quantity']").val()

                    arrayAttach.push({
                        order_detail_id: orderDetailIdAttach,
                        object_id: objectIdAttach,
                        object_name: objectNameAttach,
                        object_type: objectTypeAttach,
                        object_code: objectCodeAttach,
                        price: priceAttach,
                        quantity: quantityAttach
                    })
                })

                var arrayData = {
                    order_detail_id: orderDetailId,
                    object_id: objectId,
                    object_name: objectName,
                    object_type: objectType,
                    object_code: objectCode,
                    price: price,
                    quantity: quantity,
                    discount: discount,
                    voucher_code: voucherCode,
                    amount: amount,
                    staff_id: staffId,
                    is_change_price: isChangePrice,
                    is_check_promotion: isCheckPromotion,
                    number_row: numberRow,
                    note: note,
                    array_attach: arrayAttach
                };

                table_subbmit.push(arrayData);

                if (objectType == 'service_card') {
                    check_service_card.push(arrayData);
                }
            });

            if (table_subbmit == '') {
                $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
                res({
                    'status': false
                });

            } else {
                $('.error-table').text('');
                var total_bill = $('input[name="total_bill"]').val();
                var discount_bill = $('input[name="discount_bill"]').val();
                var voucher_bill = $('#voucher_code_bill').val();
                var amount_bill = $('input[name="amount_bill_input"]').val();

                var delivery_active = 0;
                if ($('#delivery_active').is(':checked')) {
                    delivery_active = 1;
                }
                var receipt_info_check = 0;
                if ($('.receipt_info_check').is(':checked')) {
                    receipt_info_check = 1;
                }


                var receipt_info_check = 0;
                if ($('.receipt_info_check').is(':checked')) {
                    receipt_info_check = 1;
                }

                $.ajax({
                    url: laroute.route('admin.order.submit-edit'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        table_edit: table_subbmit,
                        total_bill: total_bill,
                        discount_bill: discount_bill,
                        voucher_bill: voucher_bill,
                        amount_bill: amount_bill,
                        order_id: $('#order_id').val(),
                        order_code: $('#order_code').val(),
                        refer_id: $('#refer_id').val(),
                        delivery_active: delivery_active,
                        customer_id: $('#customer_id').val(),
                        tranport_charge: $('#tranport_charge').val().replace(new RegExp('\\,', 'g'), ''),
                        custom_price: $('#custom_price').val(),
                        order_description: $('[name="order_description"]').val(),
                        type_time: $('#type_time_hidden').val(),
                        time_address: $('#time_address_hidden').val(),
                        customer_contact_id: $('#customer_contact_id_hidden').val(),
                        receipt_info_check: receipt_info_check,
                        // tranport_charge : $('#delivery_fee').val(),
                        delivery_type: $('#delivery_type').val(),
                        delivery_cost_id: $('#delivery_cost_id').val(),
                        discount_member: $('#member_level_discount').val().replace(new RegExp('\\,', 'g'), '')
                    },
                    success: function (response) {
                        if (response.error == true) {
                            res({
                                'status': false
                            });
                        } else {
                            res({
                                'status': true
                            });
                        }
                    }
                });
            }
        });
        Promise.all([resultPromise])
            .then(result => {
                let res = result[0];
                if (!res.status) {
                    return false;
                }

            })
            .catch(function (error) {
                return false;
            });

        // set up view
        $('#receipt_amount').val(formatNumber($('input[name="amount_bill_input"]').val()));
        $('#amount_all').val(formatNumber($('input[name="amount_bill_input"]').val()));
        $('#amount_rest').val(0);
        $('#amount_return').val(0);
        //span
        $('.cl_receipt_amount').text(formatNumber($('input[name="amount_bill_input"]').val()));
        $('.cl_amount_all').text(formatNumber($('input[name="amount_bill_input"]').val()));
        $('.cl_amount_rest').text(0);
        $('.cl_amount_return').text(0);
        //Xoá data method cũ
        $.each($('.payment_method').find('.method'), function () {
            $(this).remove();
        });
        //Load sẵn hình thức thanh toán = tiền mặt
        $('#receipt_type').val('CASH').trigger('change');
        $('.payment_method_CASH').remove();
        var tpl = $('#payment_method_tpl').html();
        tpl = tpl.replace(/{label}/g, 'Tiền mặt');
        tpl = tpl.replace(/{money}/g, '*');
        tpl = tpl.replace(/{id}/g, 'CASH');
        tpl = tpl.replace(/{style-display}/g, 'none');
        $('.payment_method').append(tpl);
        $('#payment_method_CASH').val(formatNumber($('input[name="amount_bill_input"]').val()));

        new AutoNumeric.multiple('#payment_method_CASH', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });

        $('.checkbox_active_card').empty();
        $('#amount_receipt_detail').val('');
        $('.error_amount_small').text('');
        $('.error_amount_large').text('');
        $('.error_amount_null').text('');
        $('.card_list_error').text('');
        $('.quantity_error').text('');
        $('.error_card_pired_date').text('');
        $('.count_using_card_error').text('');
        $('.type_error').text('');
        $('.error_count').text('');
        $('.error_account_money').text('');
        $('.money_owed_zero').text('');
        $('.money_large_moneybill').text('');
        // $("#receipt_type").val('').trigger('change');
        $('.card_null_sv').text('');
        $('#note').val('');
        $('.btn_receipt').empty();
        $('.btn_receipt').append('<button type="button" data-dismiss="modal" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn" id="receipt-close-btn"><span><i class="la la-arrow-left"></i><span>' + processPayment.jsonLang['HỦY'] + '</span></span></button>');
        $('.btn_receipt').append('<button type="button" class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10" id="receipt-btn-print-bill"><span>' + processPayment.jsonLang['THANH TOÁN & IN HÓA ĐƠN'] + '</span></button>');
        $('.btn_receipt').append('<button type="button" class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10" id="receipt-print-debt"><span>' + processPayment.jsonLang['THANH TOÁN & IN CÔNG NỢ'] + '</span></button>');
        $('.btn_receipt').append('<button type="button" class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10" id="receipt-btn"><span>' + processPayment.jsonLang['THANH TOÁN'] + '</span></button>');

        // create variable verify
        var continute = true;

        var table_subbmit = [];

        $.each($('#table_add').find(".tr_table"), function () {
            var orderDetailId = $(this).find("input[name='id_detail']").val();
            var objectId = $(this).find("input[name='id']").val();
            var objectName = $(this).find("input[name='name']").val();
            var objectType = $(this).find("input[name='object_type']").val();
            var objectCode = $(this).find("input[name='object_code']").val();
            var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '');
            var quantity = $(this).find("input[name='quantity']").val();
            var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '');
            var voucherCode = $(this).find("input[name='voucher_code']").val();
            var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '');
            var isChangePrice = $(this).find("input[name='is_change_price']").val();
            var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val();
            var numberRow = $(this).find("input[name='numberRow']").val();
            var note = $(this).find("input[name='note']").val();

            if (amount < 0) {
                $('.error-table').text(processPayment.jsonLang['Tổng tiền không hợp lệ']);
                continute = false;
            }

            var number = $(this).find("input[name='number_tr']").val();

            var staffId = $(this).closest('tbody').find('.staff_' + number + '').val();

            var arrayAttach = [];

            //Lấy sản phẩm/dịch vụ kèm theo
            $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                var objectIdAttach = $(this).find("input[name='object_id']").val();
                var objectNameAttach = $(this).find("input[name='object_name']").val();
                var objectTypeAttach = $(this).find("input[name='object_type']").val();
                var objectCodeAttach = $(this).find("input[name='object_code']").val();
                var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '');
                var quantityAttach = $(this).find("input[name='quantity']").val();

                arrayAttach.push({
                    object_id: objectIdAttach,
                    object_name: objectNameAttach,
                    object_type: objectTypeAttach,
                    object_code: objectCodeAttach,
                    price: priceAttach,
                    quantity: quantityAttach
                });
            });

            table_subbmit.push({
                order_detail_id: orderDetailId,
                object_id: objectId,
                object_name: objectName,
                object_type: objectType,
                object_code: objectCode,
                price: price,
                quantity: quantity,
                discount: discount,
                voucher_code: voucherCode,
                amount: amount,
                staff_id: staffId,
                is_change_price: isChangePrice,
                is_check_promotion: isCheckPromotion,
                number_row: numberRow,
                note: note,
                array_attach: arrayAttach
            });
        });

        var check_service_card = [];


        if (type == 'order') {
            if (table_subbmit == '') {
                $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
            } else {
                if ($('#customer_id').val() != 1) {
                    if ($('#money').val() <= 0) {
                        $("#receipt_type option[value='MEMBER_MONEY']").remove();
                    } else {
                        // Check exist option member money
                        if (!($("#receipt_type option[value='MEMBER_MONEY']").length > 0)) {
                            $('#receipt_type').append('<option value="MEMBER_MONEY" class="member_money_op">Tài khoản thành viên</option>');
                        }
                    }

                    if (check_service_card.length > 0) {
                        var tpl = $('#active-tpl').html();
                        $('.checkbox_active_card').append(tpl);
                        $("#check_active").change(function () {
                            if ($(this).is(":checked")) {
                                $(this).val(1);
                            } else if ($(this).not(":checked")) {
                                $(this).val(0);
                            }
                        });

                    } else {
                        $('.checkbox_active_card').empty();
                    }
                } else {
                    $('.member_card').remove();
                    $('.member_money').remove();
                    $('.checkbox_active_card').empty();
                    $("#receipt_type option[value='MEMBER_MONEY']").remove(); // Xoá option chọn tiền hội viên
                }
                // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                var listSVAndMemberCard = [];
                $.each($('#table_add').find(".tr_table"), function () {
                    let table_row = {};
                    let objectType = $(this).find($("input[name='object_type']")).val();
                    let objectId = $(this).find($("input[name='id']")).val();
                    let objectName = $(this).find($("input[name='name']")).val();
                    if (objectType == "service" || objectType == "member_card") {
                        table_row["object_id"] = objectId;
                        table_row["object_name"] = objectName;
                        table_row["object_type"] = objectType;
                        table_row["object_code"] = $(this).find($("input[name='object_code']")).val();
                        table_row["price"] = $(this).find($("input[name='price']")).val();
                        table_row["quantity"] = $(this).find($("input[name='quantity']")).val();
                        table_row["quantity_hidden"] = $(this).find($("input[name='quantity_hid']")).val();
                        table_row["discount"] = $(this).find($("input[name='discount']")).val();
                        table_row["voucher_code"] = $(this).find($("input[name='voucher_code']")).val();
                        table_row["amount"] = $(this).find($("input[name='amount']")).val();
                        table_row["staff_id"] = $(this).find($("select[name='staff_id']")).val();
                        listSVAndMemberCard.push(table_row);
                    }
                });
                if (listSVAndMemberCard.length > 0 && $('#customer_id').val() != 1) {
                    $('.add-quick-appointment').css("display", 'block');
                } else {
                    $('.add-quick-appointment').css("display", 'none');
                    $('#cb_add_appointment').prop('checked', false);
                    $('.append-appointment').empty();
                }
                $("#cb_add_appointment").change(function () {
                    if ($(this).is(":checked")) {
                        $('.append-appointment').empty();
                        // show ngày giờ, dịch vụ, thẻ liệu trình
                        let tpl = $('#quick_appointment_tpl').html();
                        $('.append-appointment').append(tpl);
                        let arrMemberCard = []; // số thẻ liệu trình đã chọn trong đơn hàng
                        listSVAndMemberCard.map(function (item) {
                            if (item.object_type == "member_card") {
                                arrMemberCard.push(parseInt(item.object_id));
                            }
                        });
                        // Load sẵn thẻ liệu trình của khách hàng
                        $.ajax({
                            url: laroute.route('admin.order.check-card-customer'),
                            dataType: 'JSON',
                            method: 'POST',
                            data: {
                                id: $('#customer_id').val()
                            },
                            success: function (res) {
                                $('.customer_svc').empty();
                                if (res.number_card > 0) {
                                    console.log(arrMemberCard);
                                    res.data.map(function (item) {
                                        console.log(arrMemberCard.includes(item.customer_service_card_id));
                                        if (arrMemberCard.includes(item.customer_service_card_id)) {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '" selected>' + item.card_name + '</option>');
                                        } else {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '">' + item.card_name + '</option>');
                                        }
                                    });
                                }
                            }
                        });
                        $('#date, #end_date').datepicker({
                            startDate: '0d',
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                        $('#time, #end_time').timepicker({
                            minuteStep: 1,
                            defaultTime: "",
                            showMeridian: !1,
                            snapToStep: !0,
                        });
                        $('.staff_id').select2({
                            placeholder: processPayment.jsonLang['Chọn nhân viên']
                        });
                        $('.room_id').select2({
                            placeholder: processPayment.jsonLang['Chọn phòng']
                        });
                        $('.service_id').select2({
                            placeholder: processPayment.jsonLang['Chọn dịch vụ']
                        });
                        $('.customer_svc').select2();

                    } else if ($(this).not(":checked")) {
                        // xoá
                        $('.append-appointment').empty();
                    }
                });
                // END UPDATE

                //Check voucher còn sử dụng được hay ko
                var voucher_using = [];

                $.each($('#table_add').find(".tr_table"), function () {
                    var voucher_code = $(this).find("input[name='voucher_code']").val();
                    var type = $(this).find("input[name='object_type']").val();
                    if (voucher_code != '') {
                        voucher_using.push({
                            code: voucher_code,
                            type: type
                        });
                    }
                });
                $.each($('#table_add').find(".table_add"), function () {
                    var voucher_code = $(this).find("input[name='voucher_code']").val();
                    var type = $(this).find("input[name='object_type']").val();
                    if (voucher_code != '') {
                        voucher_using.push({
                            code: voucher_code,
                            type: type
                        });
                    }
                });

                if (voucher_using.length > 0) {
                    $.ajax({
                        url: laroute.route('admin.order.check-voucher'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            voucher_bill: $('#voucher_code_bill').val(),
                            voucher_using: voucher_using
                        },
                        success: function (res) {
                            if (res.is_success == false) {
                                $('.error-table').text(processPayment.jsonLang['Voucher bạn đang sử dụng đã hết số lần sử dụng']);
                            } else {
                                $('.error-table').text('');
                                $('#modal-receipt').modal('show');
                            }
                        }
                    });
                } else {
                    $('#modal-receipt').modal('show');
                }

                $('#receipt-btn').click(function () {
                    var check = true;
                    var order_code = $('#order_code').val();
                    var order_id = $('#order_id').val();
                    var customer_id = $('#customer_id').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var amount_bill = $('input[name="amount_bill_input"]').val();
                    var amount_return = $('input[name="amount_return"]').val();
                    var amount_receipt = $('#receipt_amount').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;
                    } else {
                        arrAppointment['checked'] = 0;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }

                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order.submit-receipt-after'),
                            dataType: 'JSON',
                            data: {
                                order_code: order_code,
                                order_id: order_id,
                                customer_id: customer_id,
                                member_money: member_money,
                                table_edit: table_subbmit,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                voucher_bill: voucher_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                amount_receipt: amount_receipt,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                order_source_id: $('#order_source_id').val(),
                                custom_price: $('#custom_price').val(),
                                receipt_info_check: receipt_info_check,
                                arrAppointment: arrAppointment,
                                sessionSerial: $('#session').val(),
                                tranport_charge: $('#delivery_fee').val(),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            success: function (response) {
                                if (response.card_list_error == 1) {
                                    $('.card_list_error').text(processPayment.jsonLang['Mã thẻ '] + response.name + processPayment.jsonLang['Mã thẻ ']);
                                } else {
                                    $('.card_list_error').text('');
                                }
                                if (response.quantity_error == 1) {
                                    $('.quantity_error').text(processPayment.jsonLang['Số lượng '] + response.name + processPayment.jsonLang[' không hợp lệ']);
                                } else {
                                    $('.quantity_error').text('');
                                }
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.error_account_money == 1) {
                                    processPayment.jsonLang['Tiền trong tài khoản không đủ']
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.count_using_card_error == 1) {
                                    $('.count_using_card_error').text(processPayment.jsonLang['Thẻ '] + response.name_card + '(' + response.code + processPayment.jsonLang[') còn '] + response.using + processPayment.jsonLang[' lần sử dụng ']);
                                } else {
                                    $('.count_using_card_error').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        mApp.block(".load_ajax", {
                                            overlayColor: "#000000",
                                            type: "loader",
                                            state: "success",
                                            message: processPayment.jsonLang["Đang tải..."]
                                        });
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                mApp.unblock(".load");
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        // let flagLoyalty = loyalty(response.orderId);
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        window.location.reload();
                                        // }
                                    }
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        mApp.unblock("#load");
                    }
                });
                //Thanh toán và in hóa đơn
                $('#receipt-btn-print-bill').click(function () {
                    var check = true;
                    var order_code = $('#order_code').val();
                    var order_id = $('#order_id').val();
                    var customer_id = $('#customer_id').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var amount_bill = $('input[name="amount_bill_input"]').val();
                    var amount_return = $('input[name="amount_return"]').val();
                    var amount_receipt = $('#receipt_amount').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money').val();
                    var discount_member = $('#member_level_discount').val();
                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;
                    } else {
                        arrAppointment['checked'] = 0;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order.submit-receipt-after'),
                            dataType: 'JSON',
                            data: {
                                order_code: order_code,
                                order_id: order_id,
                                customer_id: customer_id,
                                member_money: member_money,
                                table_edit: table_subbmit,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                voucher_bill: voucher_bill,
                                amount_bill: amount_bill,
                                // amount_load: amount_load,
                                amount_return: amount_return,
                                amount_receipt: amount_receipt,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                receipt_info_check: receipt_info_check,
                                arrAppointment: arrAppointment,
                                sessionSerial: $('#session').val(),
                                tranport_charge: $('#delivery_fee').val(),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            success: function (response) {
                                if (response.card_list_error == 1) {
                                    $('.card_list_error').text(processPayment.jsonLang['Mã thẻ '] + response.name + processPayment.jsonLang['Mã thẻ ']);
                                } else {
                                    $('.card_list_error').text('');
                                }
                                if (response.quantity_error == 1) {
                                    $('.quantity_error').text(processPayment.jsonLang['Số lượng '] + response.name + processPayment.jsonLang[' không hợp lệ']);
                                } else {
                                    $('.quantity_error').text('');
                                }
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.count_using_card_error == 1) {
                                    $('.count_using_card_error').text(processPayment.jsonLang['Thẻ '] + response.name_card + '(' + response.code + processPayment.jsonLang[') còn '] + response.using + processPayment.jsonLang[' lần sử dụng ']);
                                } else {
                                    $('.count_using_card_error').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        mApp.block(".load_ajax", {
                                            overlayColor: "#000000",
                                            type: "loader",
                                            state: "success",
                                            message: processPayment.jsonLang["Đang tải..."]
                                        });
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                mApp.unblock(".load");
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        $('#orderiddd').val(response.orderId);
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        $('#form-order-ss').submit();
                                        // }
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");

                                        $('#orderiddd').val(response.orderId);

                                        $('#form-order-ss').submit();
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
                                        // }
                                    }
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        mApp.unblock("#load");
                    }

                    // $.ajax({
                    //     url: laroute.route('admin.order.print-bill'),
                    //     method: "POST",
                    //     data: {orderId: response.orderId},
                    //     success: function (data) {
                    //         $('#orderiddd').val(response.orderId);
                    //         $('#form-order-ss').submit();
                    //     }
                    //
                    // });
                    //

                });
                //Thanh toán và in công nợ
                $('#receipt-print-debt').click(function () {
                    var check = true;
                    var order_code = $('#order_code').val();
                    var order_id = $('#order_id').val();
                    var customer_id = $('#customer_id').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var amount_bill = $('input[name="amount_bill_input"]').val();
                    var amount_return = $('input[name="amount_return"]').val();
                    var amount_receipt = $('#receipt_amount').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money').val();
                    var discount_member = $('#member_level_discount').val();
                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;
                    } else {
                        arrAppointment['checked'] = 0;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order.submit-receipt-after'),
                            dataType: 'JSON',
                            data: {
                                order_code: order_code,
                                order_id: order_id,
                                customer_id: customer_id,
                                member_money: member_money,
                                table_edit: table_subbmit,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                voucher_bill: voucher_bill,
                                amount_bill: amount_bill,
                                // amount_load: amount_load,
                                amount_return: amount_return,
                                amount_receipt: amount_receipt,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                receipt_info_check: receipt_info_check,
                                arrAppointment: arrAppointment,
                                sessionSerial: $('#session').val(),
                                tranport_charge: $('#delivery_fee').val(),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            success: function (response) {
                                if (response.card_list_error == 1) {
                                    $('.card_list_error').text(processPayment.jsonLang['Mã thẻ '] + response.name + processPayment.jsonLang['Mã thẻ ']);
                                } else {
                                    $('.card_list_error').text('');
                                }
                                if (response.quantity_error == 1) {
                                    $('.quantity_error').text(processPayment.jsonLang['Số lượng '] + response.name + processPayment.jsonLang[' không hợp lệ']);
                                } else {
                                    $('.quantity_error').text('');
                                }
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.count_using_card_error == 1) {
                                    $('.count_using_card_error').text(processPayment.jsonLang['Thẻ '] + response.name_card + '(' + response.code + processPayment.jsonLang[') còn '] + response.using + processPayment.jsonLang[' lần sử dụng ']);
                                } else {
                                    $('.count_using_card_error').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        mApp.block(".load_ajax", {
                                            overlayColor: "#000000",
                                            type: "loader",
                                            state: "success",
                                            message: processPayment.jsonLang["Đang tải..."]
                                        });
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                mApp.unblock(".load");
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");

                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();

                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
                                        // }
                                    }
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        mApp.unblock("#load");
                    }

                    // $.ajax({
                    //     url: laroute.route('admin.order.print-bill'),
                    //     method: "POST",
                    //     data: {orderId: response.orderId},
                    //     success: function (data) {
                    //         $('#orderiddd').val(response.orderId);
                    //         $('#form-order-ss').submit();
                    //     }
                    //
                    // });
                    //

                });
            }
        } else if (type == 'order-online') {
            if (table_subbmit == '') {
                $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
            } else {
                if ($('#customer_id').val() != 1) {
                    if ($('#money').val() <= 0) {
                        $("#receipt_type option[value='MEMBER_MONEY']").remove();
                    } else {
                        // Check exist option member money
                        if (!($("#receipt_type option[value='MEMBER_MONEY']").length > 0)) {
                            $('#receipt_type').append('<option value="MEMBER_MONEY" class="member_money_op">Tài khoản thành viên</option>');
                        }
                    }
                    if (check_service_card.length > 0) {
                        var tpl = $('#active-tpl').html();
                        $('.checkbox_active_card').append(tpl);
                        $("#check_active").change(function () {
                            if ($(this).is(":checked")) {
                                $(this).val(1);
                            } else if ($(this).not(":checked")) {
                                $(this).val(0);
                            }
                        });

                    } else {
                        $('.checkbox_active_card').empty();
                    }
                } else {
                    $('.member_card').remove();
                    $('.member_money').remove();
                    $('.checkbox_active_card').empty();
                    $("#receipt_type option[value='MEMBER_MONEY']").remove(); // Xoá option chọn tiền hội viên
                }
                //Check voucher còn sử dụng được hay ko
                var voucher_using = [];

                $.each($('#table_add').find(".tr_table"), function () {
                    var voucher_code = $(this).find("input[name='voucher_code']").val();
                    var type = $(this).find("input[name='object_type']").val();
                    if (voucher_code != '') {
                        voucher_using.push({
                            code: voucher_code,
                            type: type
                        });
                    }
                });
                $.each($('#table_add').find(".table_add"), function () {
                    var voucher_code = $(this).find("input[name='voucher_code']").val();
                    var type = $(this).find("input[name='object_type']").val();
                    if (voucher_code != '') {
                        voucher_using.push({
                            code: voucher_code,
                            type: type
                        });
                    }
                });

                if (voucher_using.length > 0) {
                    $.ajax({
                        url: laroute.route('admin.order.check-voucher'),
                        method: 'POST',
                        async: false,
                        dataType: 'JSON',
                        data: {
                            voucher_bill: $('#voucher_code_bill').val(),
                            voucher_using: voucher_using
                        },
                        success: function (res) {
                            if (res.is_success == false) {
                                $('.error-table').text(processPayment.jsonLang['Voucher bạn đang sử dụng đã hết số lần sử dụng']);
                            } else {
                                $('.error-table').text('');
                                $('#modal-receipt').modal('show');
                            }
                        }
                    });
                } else {
                    $('#modal-receipt').modal('show');
                }

                $('#receipt-btn').click(function () {
                    mApp.block("#load", {
                        overlayColor: "#000000",
                        type: "loader",
                        state: "success",
                        message: processPayment.jsonLang["Đang tải..."]
                    });
                    var order_code = $('#order_code').val();
                    var order_id = $('#order_id').val();
                    var customer_id = $('#customer_id').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var amount_bill = $('input[name="amount_bill_input"]').val();
                    var amount_return = $('input[name="amount_return"]').val();
                    var amount_receipt = $('#receipt_amount').val();
                    var receipt_type = $('#receipt_type').val();
                    // var amount_receipt_cash = $('#amount_receipt_cash').val();
                    // var amount_receipt_atm = $('#amount_receipt_atm').val();
                    // var amount_receipt_visa = $('#amount_receipt_visa').val();
                    // var amount_receipt_money = $('#amount_receipt_money').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }

                    if (check = true) {
                        $.ajax({
                            url: laroute.route('admin.order-app.submit-receipt'),
                            dataType: 'JSON',
                            async: false,
                            data: {
                                order_code: order_code,
                                order_id: order_id,
                                customer_id: customer_id,
                                member_money: member_money,
                                table_edit: table_subbmit,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                voucher_bill: voucher_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                amount_receipt: amount_receipt,
                                receipt_type: receipt_type,
                                // amount_receipt_cash: amount_receipt_cash,
                                // amount_receipt_atm: amount_receipt_atm,
                                // amount_receipt_visa: amount_receipt_visa,
                                // amount_receipt_money: amount_receipt_money,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                order_source_id: $('#order_source_id').val(),
                                receipt_info_check: receipt_info_check,
                                // tranport_charge: $('#tranport_charge').val(),
                                custom_price: $('#custom_price').val(),
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            success: function (response) {
                                mApp.unblock("#load");
                                if (response.card_list_error == 1) {
                                    $('.card_list_error').text(processPayment.jsonLang['Mã thẻ '] + response.name + processPayment.jsonLang[' không tồn tại']);
                                } else {
                                    $('.card_list_error').text('');
                                }
                                if (response.quantity_error == 1) {
                                    $('.quantity_error').text(processPayment.jsonLang['Số lượng '] + response.name + processPayment.jsonLang[' không hợp lệ']);
                                } else {
                                    $('.quantity_error').text('');
                                }
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.count_using_card_error == 1) {
                                    $('.count_using_card_error').text(processPayment.jsonLang['Thẻ '] + response.name_card + '(' + response.code + processPayment.jsonLang[') còn '] + response.using + processPayment.jsonLang[' lần sử dụng ']);
                                } else {
                                    $('.count_using_card_error').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        mApp.block(".load_ajax", {
                                            overlayColor: "#000000",
                                            type: "loader",
                                            state: "success",
                                            message: "Đang tải..."
                                        });
                                        $.ajax({
                                            url: laroute.route('admin.order-app.render-card'),
                                            dataType: 'JSON',
                                            async: false,
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                mApp.unblock(".load");
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        // let flagLoyalty = loyalty(response.orderId);
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        window.location.reload();
                                        // }
                                    }
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    }
                });
                //Thanh toán và in hóa đơn
                $('#receipt-btn-print-bill').click(function () {
                    mApp.block("#load", {
                        overlayColor: "#000000",
                        type: "loader",
                        state: "success",
                        message: processPayment.jsonLang["Đang tải..."]
                    });
                    var order_code = $('#order_code').val();
                    var order_id = $('#order_id').val();
                    var customer_id = $('#customer_id').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var amount_bill = $('input[name="amount_bill_input"]').val();
                    var amount_return = $('input[name="amount_return"]').val();
                    var amount_receipt = $('#receipt_amount').val();
                    var receipt_type = $('#receipt_type').val();
                    // var amount_receipt_cash = $('#amount_receipt_cash').val();
                    // var amount_receipt_atm = $('#amount_receipt_atm').val();
                    // var amount_receipt_visa = $('#amount_receipt_visa').val();
                    // var amount_receipt_money = $('#amount_receipt_money').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money').val();
                    var discount_member = $('#member_level_discount').val();
                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }

                    if (check = true) {
                        $.ajax({
                            url: laroute.route('admin.order-app.submit-receipt'),
                            async: false,
                            dataType: 'JSON',
                            data: {
                                order_code: order_code,
                                order_id: order_id,
                                customer_id: customer_id,
                                member_money: member_money,
                                table_edit: table_subbmit,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                voucher_bill: voucher_bill,
                                amount_bill: amount_bill,
                                // amount_load: amount_load,
                                amount_return: amount_return,
                                amount_receipt: amount_receipt,
                                receipt_type: receipt_type,
                                // amount_receipt_cash: amount_receipt_cash,
                                // amount_receipt_atm: amount_receipt_atm,
                                // amount_receipt_visa: amount_receipt_visa,
                                // amount_receipt_money: amount_receipt_money,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                receipt_info_check: receipt_info_check,
                                // tranport_charge: $('#tranport_charge').val(),
                                custom_price: $('#custom_price').val(),
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            success: function (response) {
                                mApp.unblock("#load");
                                if (response.card_list_error == 1) {
                                    $('.card_list_error').text(processPayment.jsonLang['Mã thẻ '] + response.name + processPayment.jsonLang[' không tồn tại']);
                                } else {
                                    $('.card_list_error').text('');
                                }
                                if (response.quantity_error == 1) {
                                    $('.quantity_error').text(processPayment.jsonLang['Số lượng '] + response.name + processPayment.jsonLang[' không hợp lệ']);
                                } else {
                                    $('.quantity_error').text('');
                                }
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.count_using_card_error == 1) {
                                    $('.count_using_card_error').text(processPayment.jsonLang['Thẻ '] + response.name_card + '(' + response.code + processPayment.jsonLang[') còn '] + response.using + processPayment.jsonLang[' lần sử dụng ']);
                                } else {
                                    $('.count_using_card_error').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        mApp.block(".load_ajax", {
                                            overlayColor: "#000000",
                                            type: "loader",
                                            state: "success",
                                            message: processPayment.jsonLang["Đang tải..."]
                                        });
                                        $.ajax({
                                            url: laroute.route('admin.order-app.render-card'),
                                            dataType: 'JSON',
                                            async: false,
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                mApp.unblock(".load");
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        $('#orderiddd').val(response.orderId);
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        $('#form-order-ss').submit();
                                        // }
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");

                                        $('#orderiddd').val(response.orderId);

                                        $('#form-order-ss').submit();
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
                                        // }
                                    }
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    }


                    // $.ajax({
                    //     url: laroute.route('admin.order.print-bill'),
                    //     method: "POST",
                    //     data: {orderId: response.orderId},
                    //     success: function (data) {
                    //         $('#orderiddd').val(response.orderId);
                    //         $('#form-order-ss').submit();
                    //     }
                    //
                    // });
                    //

                });
                //Thanh toán và in công nợ
                $('#receipt-print-debt').click(function () {
                    mApp.block("#load", {
                        overlayColor: "#000000",
                        type: "loader",
                        state: "success",
                        message: processPayment.jsonLang["Đang tải..."]
                    });
                    var order_code = $('#order_code').val();
                    var order_id = $('#order_id').val();
                    var customer_id = $('#customer_id').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var amount_bill = $('input[name="amount_bill_input"]').val();
                    var amount_return = $('input[name="amount_return"]').val();
                    var amount_receipt = $('#receipt_amount').val();
                    var receipt_type = $('#receipt_type').val();
                    // var amount_receipt_cash = $('#amount_receipt_cash').val();
                    // var amount_receipt_atm = $('#amount_receipt_atm').val();
                    // var amount_receipt_visa = $('#amount_receipt_visa').val();
                    // var amount_receipt_money = $('#amount_receipt_money').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money').val();
                    var discount_member = $('#member_level_discount').val();
                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }

                    if (check = true) {
                        $.ajax({
                            url: laroute.route('admin.order-app.submit-receipt'),
                            async: false,
                            dataType: 'JSON',
                            data: {
                                order_code: order_code,
                                order_id: order_id,
                                customer_id: customer_id,
                                member_money: member_money,
                                table_edit: table_subbmit,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                voucher_bill: voucher_bill,
                                amount_bill: amount_bill,
                                // amount_load: amount_load,
                                amount_return: amount_return,
                                amount_receipt: amount_receipt,
                                receipt_type: receipt_type,
                                // amount_receipt_cash: amount_receipt_cash,
                                // amount_receipt_atm: amount_receipt_atm,
                                // amount_receipt_visa: amount_receipt_visa,
                                // amount_receipt_money: amount_receipt_money,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                receipt_info_check: receipt_info_check,
                                // tranport_charge: $('#tranport_charge').val(),
                                custom_price: $('#custom_price').val(),
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            success: function (response) {
                                mApp.unblock("#load");
                                if (response.card_list_error == 1) {
                                    $('.card_list_error').text(processPayment.jsonLang['Mã thẻ '] + response.name + processPayment.jsonLang[' không tồn tại']);
                                } else {
                                    $('.card_list_error').text('');
                                }
                                if (response.quantity_error == 1) {
                                    $('.quantity_error').text(processPayment.jsonLang['Số lượng '] + response.name + processPayment.jsonLang[' không hợp lệ']);
                                } else {
                                    $('.quantity_error').text('');
                                }
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.count_using_card_error == 1) {
                                    $('.count_using_card_error').text(processPayment.jsonLang['Thẻ '] + response.name_card + '(' + response.code + processPayment.jsonLang[') còn '] + response.using + processPayment.jsonLang[' lần sử dụng ']);
                                } else {
                                    $('.count_using_card_error').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        mApp.block(".load_ajax", {
                                            overlayColor: "#000000",
                                            type: "loader",
                                            state: "success",
                                            message: processPayment.jsonLang["Đang tải..."]
                                        });
                                        $.ajax({
                                            url: laroute.route('admin.order-app.render-card'),
                                            dataType: 'JSON',
                                            async: false,
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                mApp.unblock(".load");
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();
                                        // }
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");

                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();

                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
                                        // }
                                    }
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    }


                    // $.ajax({
                    //     url: laroute.route('admin.order.print-bill'),
                    //     method: "POST",
                    //     data: {orderId: response.orderId},
                    //     success: function (data) {
                    //         $('#orderiddd').val(response.orderId);
                    //         $('#form-order-ss').submit();
                    //     }
                    //
                    // });
                    //

                });
            }
            discountCustomerInput();
        }

    },

    processFunctionAddOrder: function (data) {
        window.close();
        const bc = new BroadcastChannel('addSuccessOrder');
        bc.postMessage(data);
    },

    createPaymentAction: function (type = 'order') {
        // setup view
        $('#receipt_amount').val(formatNumber($('input[name="amount_bill_input"]').val()));
        $('#amount_all').val(formatNumber($('input[name="amount_bill_input"]').val()));
        $('#amount_rest').val(0);
        $('#amount_return').val(0);
        //span receipt
        $('.cl_receipt_amount').text(formatNumber($('input[name="amount_bill_input"]').val()));
        $('.cl_amount_all').text(formatNumber($('input[name="amount_bill_input"]').val()));
        $('.cl_amount_rest').text(0);
        $('.cl_amount_return').text(0);
        //Xoá data cũ
        $.each($('.payment_method').find('.method'), function () {
            $(this).remove();
        });
        //Load sẵn hình thức thanh toán = tiền mặt
        $('#receipt_type').val('CASH').trigger('change');
        $('.payment_method_CASH').remove();
        var tpl = $('#payment_method_tpl').html();
        tpl = tpl.replace(/{label}/g, 'Tiền mặt');
        tpl = tpl.replace(/{money}/g, '*');
        tpl = tpl.replace(/{id}/g, 'CASH');
        tpl = tpl.replace(/{style-display}/g, 'none');
        $('.payment_method').append(tpl);
        $('#payment_method_CASH').val(formatNumber($('input[name="amount_bill_input"]').val()));

        new AutoNumeric.multiple('#payment_method_CASH', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });

        $('.error_amount_small').text('');
        $('.error_amount_large').text('');
        $('.error_amount_null').text('');
        $('.not_id_table').text('');
        $('.error_card_pired_date').text('');
        $('.type_error').text('');
        $('.error_count').text('');
        $('.card_null_sv').text('');
        $('.error_account_money').text('');
        $('.error_account_money_null').text('');
        $('.money_owed_zero').text('');
        $('.money_large_moneybill').text('');
        $('#note').val('');
        $('.checkbox_active_card').empty();
        $('.btn_receipt').empty();
        $('.btn_receipt').append('<button type="button" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn" id="receipt-close-btn"><span><i class="la la-arrow-left"></i><span>' + processPayment.jsonLang['HỦY'] + '</span></span></button>');
        $('.btn_receipt').append('<button type="button" class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10" id="receipt-print-btn"><span>' + processPayment.jsonLang['THANH TOÁN & IN HÓA ĐƠN'] + '</span></button>');
        $('.btn_receipt').append('<button type="button" class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10" id="receipt-print-debt"><span>' + processPayment.jsonLang['THANH TOÁN & IN CÔNG NỢ'] + '</span></button>');
        $('.btn_receipt').append('<button type="button" class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10" id="receipt-btn"><span>' + processPayment.jsonLang['THANH TOÁN'] + '</span></button>');

        // create variable vervify
        var continute = true;

        if (type == 'order') {
            const resultPromise = new Promise((res, rej) => {
                var customer_id = $('#customer_id').val();
                var table_subbmit = [];

                $.each($('#table_add').find('.tr_table'), function () {
                    var objectId = $(this).find("input[name='id']").val()
                    var objectName = $(this).find("input[name='name']").val()
                    var objectType = $(this).find("input[name='object_type']").val()
                    var objectCode = $(this).find("input[name='object_code']").val()
                    var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '')
                    var quantity = $(this).find("input[name='quantity']").val()
                    var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '')
                    var voucherCode = $(this).find("input[name='voucher_code']").val()
                    var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '')
                    var isChangePrice = $(this).find("input[name='is_change_price']").val()
                    var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val()
                    var numberRow = $(this).find("input[class='numberRow']").val()
                    var note = $(this).find("input[name='note']").val();

                    if (amount < 0) {
                        $('.error-table').text(order.jsonLang['Tổng tiền không hợp lệ'])
                        continute = false
                    }

                    var number = $(this).find("input[name='number_tr']").val();

                    var staffId = $(this).closest('tbody').find('.staff_' + number + '').val();

                    var arrayAttach = [];

                    //Lấy sản phẩm/dịch vụ kèm theo
                    $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                        var objectIdAttach = $(this).find("input[name='object_id']").val()
                        var objectNameAttach = $(this).find("input[name='object_name']").val()
                        var objectTypeAttach = $(this).find("input[name='object_type']").val()
                        var objectCodeAttach = $(this).find("input[name='object_code']").val()
                        var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '')
                        var quantityAttach = $(this).find("input[name='quantity']").val()

                        arrayAttach.push({
                            object_id: objectIdAttach,
                            object_name: objectNameAttach,
                            object_type: objectTypeAttach,
                            object_code: objectCodeAttach,
                            price: priceAttach,
                            quantity: quantityAttach
                        })
                    });

                    table_subbmit.push({
                        object_id: objectId,
                        object_name: objectName,
                        object_type: objectType,
                        object_code: objectCode,
                        price: price,
                        quantity: quantity,
                        discount: discount,
                        voucher_code: voucherCode,
                        amount: amount,
                        staff_id: staffId,
                        is_change_price: isChangePrice,
                        is_check_promotion: isCheckPromotion,
                        number_row: numberRow,
                        note: note,
                        array_attach: arrayAttach
                    });
                });

                var voucher_bill = $('#voucher_code_bill').val();
                var total_bill = $('input[name="total_bill"]').val();
                var discount_bill = $('input[name="discount_bill"]').val();
                var amount_bill = $('input[name="amount_bill_input"]').val();
                // var loc_total = total_bill.replace(/\D+/g, '');
                // var loc_discount = discount_bill.replace(/\D+/g, '');
                // var discountCauseBill = $('#discount_causes_bill').val();

                var receipt_info_check = 0;
                if ($('.receipt_info_check').is(':checked')) {
                    receipt_info_check = 1;
                }

                $.ajax({
                    url: laroute.route('admin.order.submit-add-or-update'),
                    data: {
                        order_id: $('#order_id').val(),
                        order_code: $('#order_code').val(),
                        customer_id: customer_id,
                        total_bill: total_bill,
                        discount_bill: discount_bill,
                        amount_bill: amount_bill,
                        table_add: table_subbmit,
                        voucher_bill: voucher_bill,
                        refer_id: $('#refer_id').val(),
                        custom_price: $('#custom_price').val(),
                        order_description: $('[name="order_description"]').val(),

                        type_time: $('#type_time_hidden').val(),
                        time_address: $('#time_address_hidden').val(),
                        customer_contact_id: $('#customer_contact_id_hidden').val(),
                        receipt_info_check: receipt_info_check,
                        tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                        delivery_type: $('#delivery_type').val(),
                        delivery_cost_id: $('#delivery_cost_id').val(),
                        discount_member: $('#member_level_discount').val().replace(new RegExp('\\,', 'g'), '')
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (response) {
                        if (response.table_error == 1) {
                            $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
                            res({
                                'status': false
                            });
                        }
                        if (response.error) {
                            res({
                                'status': true,
                                'order_id': response.order_id,
                                'order_code': response.order_code,
                            });
                        } else {
                            res({
                                'status': false
                            });
                        }
                    }
                });
            });
            Promise.all([resultPromise])
                .then(result => {
                    let res = result[0];
                    if (res.status) {
                        $('#order_id').val(res.order_id);
                        $('#order_code').val(res.order_code);
                    } else {
                        return false;
                    }

                })
                .catch(function (error) {
                    return false;
                });
            var table_subbmit = [];

            $.each($('#table_add').find(".tr_table"), function () {
                var objectId = $(this).find("input[name='id']").val();
                var objectName = $(this).find("input[name='name']").val();
                var objectType = $(this).find("input[name='object_type']").val();
                var objectCode = $(this).find("input[name='object_code']").val();
                var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '');
                var quantity = $(this).find("input[name='quantity']").val();
                var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '');
                var voucherCode = $(this).find("input[name='voucher_code']").val();
                var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '');
                var staffId = $(this).find("input[name='staff_id']").val();
                var isChangePrice = $(this).find("input[name='is_change_price']").val();
                var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val();
                var numberRow = $(this).find("input[name='numberRow']").val();
                var note = $(this).find("input[name='note']").val();

                if (amount < 0) {
                    $('.error-table').text(processPayment.jsonLang['Tổng tiền không hợp lệ']);
                    continute = false;
                }

                var number = $(this).find("input[name='number_tr']").val();

                var arrayAttach = [];

                //Lấy sản phẩm/dịch vụ kèm theo
                $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                    var objectIdAttach = $(this).find("input[name='object_id']").val();
                    var objectNameAttach = $(this).find("input[name='object_name']").val();
                    var objectTypeAttach = $(this).find("input[name='object_type']").val();
                    var objectCodeAttach = $(this).find("input[name='object_code']").val();
                    var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '');
                    var quantityAttach = $(this).find("input[name='quantity']").val();

                    arrayAttach.push({
                        object_id: objectIdAttach,
                        object_name: objectNameAttach,
                        object_type: objectTypeAttach,
                        object_code: objectCodeAttach,
                        price: priceAttach,
                        quantity: quantityAttach
                    });
                });

                table_subbmit.push({
                    object_id: objectId,
                    object_name: objectName,
                    object_type: objectType,
                    object_code: objectCode,
                    price: price,
                    quantity: quantity,
                    discount: discount,
                    voucher_code: voucherCode,
                    amount: amount,
                    staff_id: staffId,
                    is_change_price: isChangePrice,
                    is_check_promotion: isCheckPromotion,
                    number_row: numberRow,
                    note: note,
                    array_attach: arrayAttach
                });
            });

            var check_service_card = [];
            $.each($('#table_add').find(".tr_table"), function () {
                var $check_amount = $(this).find("input[name='object_type']");
                if ($check_amount.val() == 'service_card') {
                    $.each($check_amount, function () {
                        check_service_card.push($(this).val());
                    });
                }
            });

            if (table_subbmit == '') {
                $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
            } else {
                if ($('#customer_id').val() != 1) {
                    if ($('#money_customer').val() <= 0) {
                        $("#receipt_type option[value='MEMBER_MONEY']").remove();
                    } else {
                        // Check exist option member money
                        if (!($("#receipt_type option[value='MEMBER_MONEY']").length > 0)) {
                            $('#receipt_type').append('<option value="MEMBER_MONEY" class="member_money_op">Tài khoản thành viên</option>');
                        }
                    }

                    if (check_service_card.length > 0) {
                        var tpl = $('#active-tpl').html();
                        $('.checkbox_active_card').append(tpl);
                        $("#check_active").change(function () {
                            if ($(this).is(":checked")) {
                                $(this).val(1);
                            } else if ($(this).not(":checked")) {
                                $(this).val(0);
                            }
                        });

                    } else {
                        $('.checkbox_active_card').empty();
                    }
                } else {
                    $('.member_money').empty();
                    $('.checkbox_active_card').empty();
                    $("#receipt_type option[value='MEMBER_MONEY']").remove(); // Xoá option chọn tiền hội viên
                }
                // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                var listSVAndMemberCard = [];
                $.each($('#table_add').find(".tr_table"), function () {
                    let table_row = {};
                    let objectType = $(this).find($("input[name='object_type']")).val();
                    let objectId = $(this).find($("input[name='id']")).val();
                    let objectName = $(this).find($("input[name='name']")).val();
                    if (objectType == "service" || objectType == "member_card") {
                        table_row["object_id"] = objectId;
                        table_row["object_name"] = objectName;
                        table_row["object_type"] = objectType;
                        table_row["object_code"] = $(this).find($("input[name='object_code']")).val();
                        table_row["price"] = $(this).find($("input[name='price']")).val();
                        table_row["quantity"] = $(this).find($("input[name='quantity']")).val();
                        table_row["quantity_hidden"] = $(this).find($("input[name='quantity_hid']")).val();
                        table_row["discount"] = $(this).find($("input[name='discount']")).val();
                        table_row["voucher_code"] = $(this).find($("input[name='voucher_code']")).val();
                        table_row["amount"] = $(this).find($("input[name='amount']")).val();
                        table_row["staff_id"] = $(this).find($("select[name='staff_id']")).val();
                        listSVAndMemberCard.push(table_row);
                    }
                });
                if (listSVAndMemberCard.length > 0 && $('#customer_id').val() != 1) {
                    $('.add-quick-appointment').css("display", 'block');
                } else {
                    $('.add-quick-appointment').css("display", 'none');
                    $('#cb_add_appointment').prop('checked', false);
                    $('.append-appointment').empty();
                }
                $("#cb_add_appointment").change(function () {
                    if ($(this).is(":checked")) {
                        $('.append-appointment').empty();
                        // show ngày giờ, dịch vụ, thẻ liệu trình
                        let tpl = $('#quick_appointment_tpl').html();
                        $('.append-appointment').append(tpl);
                        let arrMemberCard = []; // số thẻ liệu trình đã chọn trong đơn hàng
                        listSVAndMemberCard.map(function (item) {
                            if (item.object_type == "member_card") {
                                arrMemberCard.push(parseInt(item.object_id));
                            }
                        });
                        // Load sẵn thẻ liệu trình của khách hàng
                        $.ajax({
                            url: laroute.route('admin.order.check-card-customer'),
                            dataType: 'JSON',
                            method: 'POST',
                            data: {
                                id: $('#customer_id').val()
                            },
                            success: function (res) {
                                $('.customer_svc').empty();
                                if (res.number_card > 0) {
                                    console.log(arrMemberCard);
                                    res.data.map(function (item) {
                                        console.log(arrMemberCard.includes(item.customer_service_card_id));
                                        if (arrMemberCard.includes(item.customer_service_card_id)) {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '" selected>' + item.card_name + '</option>');
                                        } else {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '">' + item.card_name + '</option>');
                                        }
                                    });
                                }
                            }
                        });
                        $('#date, #end_date').datepicker({
                            startDate: '0d',
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                        $('#time, #end_time').timepicker({
                            minuteStep: 1,
                            defaultTime: "",
                            showMeridian: !1,
                            snapToStep: !0,
                        });
                        $('.staff_id').select2({
                            placeholder: processPayment.jsonLang['Chọn nhân viên']
                        });
                        $('.room_id').select2({
                            placeholder: processPayment.jsonLang['Chọn phòng']
                        });
                        $('.service_id').select2({
                            placeholder: processPayment.jsonLang['Chọn dịch vụ']
                        });
                        $('.customer_svc').select2();

                    } else if ($(this).not(":checked")) {
                        // xoá
                        $('.append-appointment').empty();
                    }
                });
                // END UPDATE

                $('#modal-receipt').modal('show');
                //Submit thanh toán
                $('#receipt-btn').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;
                    } else {
                        arrAppointment['checked'] = 0;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }

                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order.submitAddReceipt'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                order_description: $('[name="order_description"]').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment,
                                sessionSerial: $('#session').val(),
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                receipt_info_check: receipt_info_check,
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            async: false,
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });

                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        // let flagLoyalty = loyalty(response.orderId);
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {

                                        if (typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup') {
                                            processPayment.processFunctionAddOrder(response.data);
                                        } else {
                                            window.location = laroute.route('admin.order');
                                        }

                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        mApp.unblock("#load");
                    }
                });
                //Submit thánh toán và in hóa đơn
                $('#receipt-print-btn').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();
                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;

                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order.submitAddReceipt'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment,
                                order_description: $('[name="order_description"]').val(),
                                sessionSerial: $('#session').val(),
                                receipt_info_check: receipt_info_check,
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        $('#orderiddd').val(response.orderId);

                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        $('#form-order-ss').submit();
                                        // }
                                    } else {
                                        $('#orderiddd').val(response.orderId);
                                        $('#form-order-ss').submit();

                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location = laroute.route('admin.order');
                                        }, 1000);
                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                    $('#orderiddd').val(response.orderId);
                                    $('#form-order-ss').submit();
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });

                //Submit thanh toán và in công nợ
                $('#receipt-print-debt').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();
                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;

                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order.submitAddReceipt'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment,
                                order_description: $('[name="order_description"]').val(),
                                sessionSerial: $('#session').val(),
                                receipt_info_check: receipt_info_check,
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();
                                        // }
                                    } else {
                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();

                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location = laroute.route('admin.order');
                                        }, 1000);
                                        // }
                                    }
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });

                $('#receipt-close-btn').click(function () {
                    $('#modal-receipt').modal('hide');
                });
            }
        } else if (type == 'order-online') {
            const resultPromise = new Promise((res, rej) => {
                var continute = true;
                var customer_id = $('#customer_id').val();
                var table_subbmit = [];

                $.each($('#table_add').find('.tr_table'), function () {
                    var objectId = $(this).find("input[name='id']").val()
                    var objectName = $(this).find("input[name='name']").val()
                    var objectType = $(this).find("input[name='object_type']").val()
                    var objectCode = $(this).find("input[name='object_code']").val()
                    var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '')
                    var quantity = $(this).find("input[name='quantity']").val()
                    var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '')
                    var voucherCode = $(this).find("input[name='voucher_code']").val()
                    var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '')
                    var isChangePrice = $(this).find("input[name='is_change_price']").val()
                    var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val()
                    var numberRow = $(this).find("input[class='numberRow']").val()
                    var note = $(this).find("input[name='note']").val();

                    if (amount < 0) {
                        $('.error-table').text(order.jsonLang['Tổng tiền không hợp lệ'])
                        continute = false
                    }

                    var number = $(this).find("input[name='number_tr']").val();

                    var staffId = $(this).closest('tbody').find('.staff_' + number + '').val();

                    var arrayAttach = [];

                    //Lấy sản phẩm/dịch vụ kèm theo
                    $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                        var objectIdAttach = $(this).find("input[name='object_id']").val()
                        var objectNameAttach = $(this).find("input[name='object_name']").val()
                        var objectTypeAttach = $(this).find("input[name='object_type']").val()
                        var objectCodeAttach = $(this).find("input[name='object_code']").val()
                        var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '')
                        var quantityAttach = $(this).find("input[name='quantity']").val()

                        arrayAttach.push({
                            object_id: objectIdAttach,
                            object_name: objectNameAttach,
                            object_type: objectTypeAttach,
                            object_code: objectCodeAttach,
                            price: priceAttach,
                            quantity: quantityAttach
                        })
                    });

                    table_subbmit.push({
                        object_id: objectId,
                        object_name: objectName,
                        object_type: objectType,
                        object_code: objectCode,
                        price: price,
                        quantity: quantity,
                        discount: discount,
                        voucher_code: voucherCode,
                        amount: amount,
                        staff_id: staffId,
                        is_change_price: isChangePrice,
                        is_check_promotion: isCheckPromotion,
                        number_row: numberRow,
                        note: note,
                        array_attach: arrayAttach
                    });
                });

                var voucher_bill = $('#voucher_code_bill').val();
                var total_bill = $('input[name="total_bill"]').val();
                var discount_bill = $('input[name="discount_bill"]').val();
                var amount_bill = $('input[name="amount_bill_input"]').val();
                // var loc_total = total_bill.replace(/\D+/g, '');
                // var loc_discount = discount_bill.replace(/\D+/g, '');
                var customer_contact_code = $('#customer_contact_code').val();

                if (customer_id == 1) {
                    continute = false;
                    swal(processPayment.jsonLang['Hãy chọn khách hàng'], "", "error");
                    res({
                        'status': false
                    });
                }

                if (continute == true) {
                    var delivery_active = 0;
                    if ($("#delivery_active").is(':checked')) {
                        delivery_active = 1;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }

                    $.ajax({
                        url: laroute.route('admin.order-app.store-or-update'),
                        data: {
                            order_id: $('#order_id').val(),
                            order_code: $('#order_code').val(),
                            customer_id: customer_id,
                            total_bill: total_bill,
                            discount_bill: discount_bill,
                            amount_bill: amount_bill,
                            table_add: table_subbmit,
                            voucher_bill: voucher_bill,
                            refer_id: $('#refer_id').val(),
                            delivery_active: delivery_active,
                            shipping_address: $('.contact-text').text(),
                            contact_name: $('#contact_name').val(),
                            contact_phone: $('#contact_phone').val(),
                            customer_contact_code: customer_contact_code,
                            custom_price: $('#custom_price').val(),
                            order_description: $('[name="order_description"]').val(),
                            type_time: $('#type_time_hidden').val(),
                            time_address: $('#time_address_hidden').val(),
                            customer_contact_id: $('#customer_contact_id_hidden').val(),
                            receipt_info_check: receipt_info_check,
                            tranport_charge : $('#delivery_fee').val(),
                            delivery_type: $('#delivery_type').val(),
                            delivery_cost_id: $('#delivery_cost_id').val(),
                            discount_member: $('#member_level_discount').val().replace(new RegExp('\\,', 'g'), '')
                        },
                        method: 'POST',
                        dataType: "JSON",
                        success: function (response) {
                            if (response.table_error == 1) {
                                $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
                                res({
                                    'status': false
                                });
                            }
                            if (response.error == true) {
                                res({
                                    'status': true,
                                    'order_id': response.order_id,
                                    'order_code': response.order_code,
                                });
                            }
                        }
                    })
                }
            });
            Promise.all([resultPromise])
                .then(result => {
                    let res = result[0];
                    if (res.status) {
                        $('#order_id').val(res.order_id);
                        $('#order_code').val(res.order_code);
                    } else {
                        return false;
                    }

                })
                .catch(function (error) {
                    console.log(error);
                    return false;
                });

            var table_subbmit = [];

            $.each($('#table_add').find(".tr_table"), function () {
                var objectId = $(this).find("input[name='id']").val();
                var objectName = $(this).find("input[name='name']").val();
                var objectType = $(this).find("input[name='object_type']").val();
                var objectCode = $(this).find("input[name='object_code']").val();
                var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '');
                var quantity = $(this).find("input[name='quantity']").val();
                var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '');
                var voucherCode = $(this).find("input[name='voucher_code']").val();
                var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '');
                var staffId = $(this).find("input[name='staff_id']").val();
                var isChangePrice = $(this).find("input[name='is_change_price']").val();
                var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val();
                var numberRow = $(this).find("input[name='numberRow']").val();
                var note = $(this).find("input[name='note']").val();

                if (amount < 0) {
                    $('.error-table').text(processPayment.jsonLang['Tổng tiền không hợp lệ']);
                    continute = false;
                }

                var number = $(this).find("input[name='number_tr']").val();

                var arrayAttach = [];

                //Lấy sản phẩm/dịch vụ kèm theo
                $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                    var objectIdAttach = $(this).find("input[name='object_id']").val();
                    var objectNameAttach = $(this).find("input[name='object_name']").val();
                    var objectTypeAttach = $(this).find("input[name='object_type']").val();
                    var objectCodeAttach = $(this).find("input[name='object_code']").val();
                    var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '');
                    var quantityAttach = $(this).find("input[name='quantity']").val();

                    arrayAttach.push({
                        object_id: objectIdAttach,
                        object_name: objectNameAttach,
                        object_type: objectTypeAttach,
                        object_code: objectCodeAttach,
                        price: priceAttach,
                        quantity: quantityAttach
                    });
                });

                table_subbmit.push({
                    object_id: objectId,
                    object_name: objectName,
                    object_type: objectType,
                    object_code: objectCode,
                    price: price,
                    quantity: quantity,
                    discount: discount,
                    voucher_code: voucherCode,
                    amount: amount,
                    staff_id: staffId,
                    is_change_price: isChangePrice,
                    is_check_promotion: isCheckPromotion,
                    number_row: numberRow,
                    note: note,
                    array_attach: arrayAttach
                });
            });

            var check_service_card = [];
            $.each($('#table_add').find(".tr_table"), function () {
                var $check_amount = $(this).find("input[name='object_type']");
                if ($check_amount.val() == 'service_card') {
                    $.each($check_amount, function () {
                        check_service_card.push($(this).val());
                    });
                }
            });

            if ($('#customer_id').val() == 1) {
                swal(processPayment.jsonLang['Hãy chọn khách hàng'], "", "error");
                return false;
            }

            if (table_subbmit == '') {
                $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
            } else {
                if ($('#customer_id').val() != 1) {
                    if ($('#money_customer').val() <= 0) {
                        $("#receipt_type option[value='MEMBER_MONEY']").remove();
                    } else {
                        // Check exist option member money
                        if (!($("#receipt_type option[value='MEMBER_MONEY']").length > 0)) {
                            $('#receipt_type').append('<option value="MEMBER_MONEY" class="member_money_op">Tài khoản thành viên</option>');
                        }
                    }

                    if (check_service_card.length > 0) {
                        var tpl = $('#active-tpl').html();
                        $('.checkbox_active_card').append(tpl);
                        $("#check_active").change(function () {
                            if ($(this).is(":checked")) {
                                $(this).val(1);
                            } else if ($(this).not(":checked")) {
                                $(this).val(0);
                            }
                        });

                    } else {
                        $('.checkbox_active_card').empty();
                    }
                } else {
                    $('.member_money').empty();
                    $('.checkbox_active_card').empty();
                    $("#receipt_type option[value='MEMBER_MONEY']").remove(); // Xoá option chọn tiền hội viên
                }
                // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                var listSVAndMemberCard = [];
                $.each($('#table_add').find(".tr_table"), function () {
                    let table_row = {};
                    let objectType = $(this).find($("input[name='object_type']")).val();
                    let objectId = $(this).find($("input[name='id']")).val();
                    let objectName = $(this).find($("input[name='name']")).val();
                    if (objectType == "service" || objectType == "member_card") {
                        table_row["object_id"] = objectId;
                        table_row["object_name"] = objectName;
                        table_row["object_type"] = objectType;
                        table_row["object_code"] = $(this).find($("input[name='object_code']")).val();
                        table_row["price"] = $(this).find($("input[name='price']")).val();
                        table_row["quantity"] = $(this).find($("input[name='quantity']")).val();
                        table_row["quantity_hidden"] = $(this).find($("input[name='quantity_hid']")).val();
                        table_row["discount"] = $(this).find($("input[name='discount']")).val();
                        table_row["voucher_code"] = $(this).find($("input[name='voucher_code']")).val();
                        table_row["amount"] = $(this).find($("input[name='amount']")).val();
                        table_row["staff_id"] = $(this).find($("select[name='staff_id']")).val();
                        listSVAndMemberCard.push(table_row);
                    }
                });
                if (listSVAndMemberCard.length > 0 && $('#customer_id').val() != 1) {
                    $('.add-quick-appointment').css("display", 'block');
                } else {
                    $('.add-quick-appointment').css("display", 'none');
                    $('#cb_add_appointment').prop('checked', false);
                    $('.append-appointment').empty();
                }
                $("#cb_add_appointment").change(function () {
                    if ($(this).is(":checked")) {
                        $('.append-appointment').empty();
                        // show ngày giờ, dịch vụ, thẻ liệu trình
                        let tpl = $('#quick_appointment_tpl').html();
                        $('.append-appointment').append(tpl);
                        let arrMemberCard = []; // số thẻ liệu trình đã chọn trong đơn hàng
                        listSVAndMemberCard.map(function (item) {
                            if (item.object_type == "member_card") {
                                arrMemberCard.push(parseInt(item.object_id));
                            }
                        });
                        // Load sẵn thẻ liệu trình của khách hàng
                        $.ajax({
                            url: laroute.route('admin.order.check-card-customer'),
                            dataType: 'JSON',
                            method: 'POST',
                            data: {
                                id: $('#customer_id').val()
                            },
                            success: function (res) {
                                $('.customer_svc').empty();
                                if (res.number_card > 0) {
                                    console.log(arrMemberCard);
                                    res.data.map(function (item) {
                                        console.log(arrMemberCard.includes(item.customer_service_card_id));
                                        if (arrMemberCard.includes(item.customer_service_card_id)) {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '" selected>' + item.card_name + '</option>');
                                        } else {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '">' + item.card_name + '</option>');
                                        }
                                    });
                                }
                            }
                        });
                        $('#date, #end_date').datepicker({
                            startDate: '0d',
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                        $('#time, #end_time').timepicker({
                            minuteStep: 1,
                            defaultTime: "",
                            showMeridian: !1,
                            snapToStep: !0,
                        });
                        $('.staff_id').select2({
                            placeholder: processPayment.jsonLang['Chọn nhân viên']
                        });
                        $('.room_id').select2({
                            placeholder: processPayment.jsonLang['Chọn phòng']
                        });
                        $('.service_id').select2({
                            placeholder: processPayment.jsonLang['Chọn dịch vụ']
                        });
                        $('.customer_svc').select2();

                    } else if ($(this).not(":checked")) {
                        // xoá
                        $('.append-appointment').empty();
                    }
                });
                // END UPDATE

                $('#modal-receipt').modal('show');
                //Submit thanh toán
                $('#receipt-btn').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();

                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();
                    var customer_contact_code = $('#customer_contact_code').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;
                    } else {
                        arrAppointment['checked'] = 0;
                    }

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order-app.store-receipt'),
                            data: {
                                order_code: $('#order_code').val(),
                                order_id: $('#order_id').val(),
                                customer_id: customer_id,
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                shipping_address: $('.contact-text').text(),
                                contact_name: $('#contact_name').val(),
                                contact_phone: $('#contact_phone').val(),
                                customer_contact_code: customer_contact_code,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment,
                                receipt_info_check: receipt_info_check,
                                order_description: $('[name="order_description"]').val(),
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            async: false,
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });

                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        // let flagLoyalty = loyalty(response.orderId);
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        window.location = laroute.route('admin.order-app');
                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        mApp.unblock("#load");
                    }
                });
                //Submit thánh toán và in hóa đơn
                $('#receipt-print-btn').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();
                    var customer_contact_code = $('#customer_contact_code').val();
                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;

                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order-app.store-receipt'),
                            data: {
                                customer_id: customer_id,
                                order_id: $('#order_id').val(),
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                shipping_address: $('.contact-text').text(),
                                contact_name: $('#contact_name').val(),
                                contact_phone: $('#contact_phone').val(),
                                customer_contact_code: customer_contact_code,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment,
                                order_description: $('[name="order_description"]').val(),
                                receipt_info_check: receipt_info_check,
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {

                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        $('#orderiddd').val(response.orderId);

                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        $('#form-order-ss').submit();
                                        // }
                                    } else {
                                        $('#orderiddd').val(response.orderId);
                                        $('#form-order-ss').submit();

                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location = laroute.route('admin.order-app');
                                        }, 1000);
                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                    $('#orderiddd').val(response.orderId);
                                    $('#form-order-ss').submit();
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    swal(response.message, "", "error");
                                }

                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });

                //Submit thánh toán và in công nợ
                $('#receipt-print-debt').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();
                    var customer_contact_code = $('#customer_contact_code').val();
                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;

                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.order-app.store-receipt'),
                            data: {
                                customer_id: customer_id,
                                order_id: $('#order_id').val(),
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                shipping_address: $('.contact-text').text(),
                                contact_name: $('#contact_name').val(),
                                contact_phone: $('#contact_phone').val(),
                                customer_contact_code: customer_contact_code,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment,
                                order_description: $('[name="order_description"]').val(),
                                receipt_info_check: receipt_info_check,
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {

                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });
                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();
                                        // }
                                    } else {
                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();

                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location = laroute.route('admin.order-app');
                                        }, 1000);
                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                    $('#orderiddd').val(response.orderId);
                                    $('#form-order-ss').submit();
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    swal(response.message, "", "error");
                                }

                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });

                // $('#amount_receipt_detail').mask('000,000,000', {reverse: true});
                $('#receipt-close-btn').click(function () {
                    $('#modal-receipt').modal('hide');
                });
            }
        } else if (type == 'deal') {
            const resultPromise = new Promise((res, rej) => {
                var flag = true;
                var table_submit = [];

                $.each($('#table_add').find(".tr_table"), function () {
                    var objectId = $(this).find("input[name='id']").val();
                    var objectName = $(this).find("input[name='name']").val();
                    var objectType = $(this).find("input[name='object_type']").val();
                    var objectCode = $(this).find("input[name='object_code']").val();
                    var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '');
                    var quantity = $(this).find("input[name='quantity']").val();
                    var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '');
                    var voucherCode = $(this).find("input[name='voucher_code']").val();
                    var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '');
                    var staffId = $(this).find("input[name='staff_id']").val();
                    var isChangePrice = $(this).find("input[name='is_change_price']").val();
                    var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val();
                    var numberRow = $(this).find("input[name='numberRow']").val();
                    var note = $(this).find("input[name='note']").val();

                    if (amount < 0) {
                        $('.error-table').text(processPayment.jsonLang['Tổng tiền không hợp lệ']);
                        flag = false;
                    }

                    var number = $(this).find("input[name='number_tr']").val();

                    var arrayAttach = [];

                    //Lấy sản phẩm/dịch vụ kèm theo
                    $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                        var objectIdAttach = $(this).find("input[name='object_id']").val();
                        var objectNameAttach = $(this).find("input[name='object_name']").val();
                        var objectTypeAttach = $(this).find("input[name='object_type']").val();
                        var objectCodeAttach = $(this).find("input[name='object_code']").val();
                        var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '');
                        var quantityAttach = $(this).find("input[name='quantity']").val();

                        arrayAttach.push({
                            object_id: objectIdAttach,
                            object_name: objectNameAttach,
                            object_type: objectTypeAttach,
                            object_code: objectCodeAttach,
                            price: priceAttach,
                            quantity: quantityAttach
                        });
                    });

                    table_submit.push({
                        object_id: objectId,
                        object_name: objectName,
                        object_type: objectType,
                        object_code: objectCode,
                        price: price,
                        quantity: quantity,
                        discount: discount,
                        voucher_code: voucherCode,
                        amount: amount,
                        staff_id: staffId,
                        is_change_price: isChangePrice,
                        is_check_promotion: isCheckPromotion,
                        number_row: numberRow,
                        note: note,
                        array_attach: arrayAttach
                    });
                });

                if (table_submit == '') {
                    $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
                    res({
                        'status': false
                    });
                } else {
                    $('.error-table').text('');
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('input[name="amount_bill_input"]').val();

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }

                    if (flag) {
                        $.ajax({
                            url: laroute.route('customer-lead.customer-deal.save-or-update-order'),
                            method: 'POST',
                            dataType: 'JSON',
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                voucher_bill: voucher_bill,
                                table_add: table_submit,
                                deal_code: $('#deal_code').val(),
                                deal_id: $('#deal_id').val(),
                                refer_id: $('#refer_id').val(),
                                custom_price: $('#custom_price').val(),
                                order_description: $('[name="order_description"]').val(),
                                sessionSerial: $('#session').val(),
                                type_time: $('#type_time_hidden').val(),
                                time_address: $('#time_address_hidden').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val(),
                                receipt_info_check: receipt_info_check,
                                tranport_charge: $('#delivery_fee').val(),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                discount_member: $('#member_level_discount').val().replace(new RegExp('\\,', 'g'), '')
                            },
                            success: function (response) {
                                if (response.error == true) {
                                    res({
                                        'status': false
                                    });
                                } else {
                                    res({
                                        'status': true,
                                        'order_id': response.order_id,
                                        'order_code': response.order_code,
                                    });
                                }
                            }
                        });
                    }
                }
            });
            Promise.all([resultPromise])
                .then(result => {
                    let res = result[0];
                    if (res.status) {
                        $('#order_id').val(res.order_id);
                        $('#order_code').val(res.order_code);
                    } else {
                        return false;
                    }

                })
                .catch(function (error) {
                    return false;
                });

            var flag = true;
            var table_submit = [];
            $.each($('#table_add').find(".tr_table"), function () {
                let table_row = {};
                table_row["object_id"] = $(this).find($("input[name='id']")).val();
                table_row["object_name"] = $(this).find($("input[name='name']")).val();
                table_row["object_type"] = $(this).find($("input[name='object_type']")).val();
                table_row["object_code"] = $(this).find($("input[name='object_code']")).val();
                table_row["price"] = $(this).find($("input[name='price']")).val();
                table_row["quantity"] = $(this).find($("input[name='quantity']")).val();
                table_row["quantity_hidden"] = $(this).find($("input[name='quantity_hid']")).val();
                table_row["discount"] = $(this).find($("input[name='discount']")).val();
                table_row["voucher_code"] = $(this).find($("input[name='voucher_code']")).val();
                table_row["amount"] = $(this).find($("input[name='amount']")).val();
                table_row["staff_id"] = $(this).find($("select[name='staff_id']")).val();
                table_row["number_ran"] = $(this).find($("input[name='number_ran']")).val();
                table_submit.push(table_row);
                // var $tds = $(this).find("input,select");
                var check_amount = $(this).find("input[name='amount']");
                if (check_amount.val() < 0) {
                    $('.error-table').text(processPayment.jsonLang['Tổng tiền không hợp lệ']);
                    flag = false;
                }
            });

            var check_service_card = [];
            $.each($('#table_add').find(".tr_table"), function () {
                var $check_amount = $(this).find("input[name='object_type']");
                if ($check_amount.val() == 'service_card') {
                    $.each($check_amount, function () {
                        check_service_card.push($(this).val());
                    });
                }
            });
            if (table_submit == '') {
                $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
            } else {
                if ($('#customer_id').val() != 1) {
                    if ($('#member_money').val() <= 0) {
                        $("#receipt_type option[value='MEMBER_MONEY']").remove();
                    } else {
                        // Check exist option member money
                        if (!($("#receipt_type option[value='MEMBER_MONEY']").length > 0)) {
                            $('#receipt_type').append('<option value="MEMBER_MONEY" class="member_money_op">Tài khoản thành viên</option>');
                        }
                    }

                    if (check_service_card.length > 0) {
                        var tpl = $('#active-tpl').html();
                        $('.checkbox_active_card').append(tpl);
                        $("#check_active").change(function () {
                            if ($(this).is(":checked")) {
                                $(this).val(1);
                            } else if ($(this).not(":checked")) {
                                $(this).val(0);
                            }
                        });

                    } else {
                        $('.checkbox_active_card').empty();
                    }
                } else {
                    $('.member_money').empty();
                    $('.checkbox_active_card').empty();
                    $("#receipt_type option[value='MEMBER_MONEY']").remove(); // Xoá option chọn tiền hội viên
                }
                // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                var listSVAndMemberCard = [];
                $.each($('#table_add').find(".tr_table"), function () {
                    let table_row = {};
                    let objectType = $(this).find($("input[name='object_type']")).val();
                    let objectId = $(this).find($("input[name='id']")).val();
                    let objectName = $(this).find($("input[name='name']")).val();
                    if (objectType == "service" || objectType == "member_card") {
                        table_row["object_id"] = objectId;
                        table_row["object_name"] = objectName;
                        table_row["object_type"] = objectType;
                        table_row["object_code"] = $(this).find($("input[name='object_code']")).val();
                        table_row["price"] = $(this).find($("input[name='price']")).val();
                        table_row["quantity"] = $(this).find($("input[name='quantity']")).val();
                        table_row["quantity_hidden"] = $(this).find($("input[name='quantity_hid']")).val();
                        table_row["discount"] = $(this).find($("input[name='discount']")).val();
                        table_row["voucher_code"] = $(this).find($("input[name='voucher_code']")).val();
                        table_row["amount"] = $(this).find($("input[name='amount']")).val();
                        table_row["staff_id"] = $(this).find($("select[name='staff_id']")).val();
                        listSVAndMemberCard.push(table_row);
                    }
                });
                if (listSVAndMemberCard.length > 0 && $('#customer_id').val() != 1) {
                    $('.add-quick-appointment').css("display", 'block');
                } else {
                    $('.add-quick-appointment').css("display", 'none');
                    $('#cb_add_appointment').prop('checked', false);
                    $('.append-appointment').empty();
                }
                $("#cb_add_appointment").change(function () {
                    if ($(this).is(":checked")) {
                        $('.append-appointment').empty();
                        // show ngày giờ, dịch vụ, thẻ liệu trình
                        let tpl = $('#quick_appointment_tpl').html();
                        $('.append-appointment').append(tpl);
                        let arrMemberCard = []; // số thẻ liệu trình đã chọn trong đơn hàng
                        listSVAndMemberCard.map(function (item) {
                            if (item.object_type == "member_card") {
                                arrMemberCard.push(parseInt(item.object_id));
                            }
                        });
                        // Load sẵn thẻ liệu trình của khách hàng
                        $.ajax({
                            url: laroute.route('admin.order.check-card-customer'),
                            dataType: 'JSON',
                            method: 'POST',
                            data: {
                                id: $('#customer_id').val()
                            },
                            success: function (res) {
                                $('.customer_svc').empty();
                                if (res.number_card > 0) {
                                    console.log(arrMemberCard);
                                    res.data.map(function (item) {
                                        console.log(arrMemberCard.includes(item.customer_service_card_id));
                                        if (arrMemberCard.includes(item.customer_service_card_id)) {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '" selected>' + item.card_name + '</option>');
                                        } else {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '">' + item.card_name + '</option>');
                                        }
                                    });
                                }
                            }
                        });
                        $('#date, #end_date').datepicker({
                            startDate: '0d',
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                        $('#time, #end_time').timepicker({
                            minuteStep: 1,
                            defaultTime: "",
                            showMeridian: !1,
                            snapToStep: !0,
                        });
                        $('.staff_id').select2({
                            placeholder: processPayment.jsonLang['Chọn nhân viên']
                        });
                        $('.room_id').select2({
                            placeholder: processPayment.jsonLang['Chọn phòng']
                        });
                        $('.service_id').select2({
                            placeholder: processPayment.jsonLang['Chọn dịch vụ']
                        });
                        $('.customer_svc').select2();

                    } else if ($(this).not(":checked")) {
                        // xoá
                        $('.append-appointment').empty();
                    }
                });
                // END UPDATE

                //Check voucher còn sử dụng được hay ko
                var voucher_using = [];

                $.each($('#table_add').find(".tr_table"), function () {
                    var voucher_code = $(this).find("input[name='voucher_code']").val();
                    var type = $(this).find("input[name='object_type']").val();
                    if (voucher_code != '') {
                        voucher_using.push({
                            code: voucher_code,
                            type: type
                        });
                    }
                });
                if (voucher_using.length > 0) {
                    $.ajax({
                        url: laroute.route('admin.order.check-voucher'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            voucher_bill: $('#voucher_code_bill').val(),
                            voucher_using: voucher_using
                        },
                        success: function (res) {
                            if (res.is_success == false) {
                                $('.error-table').text(processPayment.jsonLang['Voucher bạn đang sử dụng đã hết số lần sử dụng']);
                            } else {
                                $('.error-table').text('');
                                $('#modal-receipt').modal('show');
                            }
                        }
                    });
                } else {
                    $('#modal-receipt').modal('show');
                }

                $('#receipt-btn').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;
                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('customer-lead.customer-deal.submit-payment'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_submit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                customer_appointment_id: $('#customer_appointment_id').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                deal_id: $('#deal_id').val(),
                                deal_code: $('#deal_code').val(),
                                order_source_id: $('#order_source_id').val(),
                                member_money: $('#member_money').val(),
                                arrAppointment: arrAppointment,
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == false) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });

                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        window.location = laroute.route('admin.order');
                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });
                $('#receipt-print-btn').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;
                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('customer-lead.customer-deal.submit-payment'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_submit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                customer_appointment_id: $('#customer_appointment_id').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                deal_id: $('#deal_id').val(),
                                deal_code: $('#deal_code').val(),
                                order_source_id: $('#order_source_id').val(),
                                member_money: $('#member_money').val(),
                                arrAppointment: arrAppointment,
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == false) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });

                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        $('#order_id_to_print').val(response.order_id);
                                        $('#form-order-ss').submit();
                                    } else {
                                        $('#order_id_to_print').val(response.order_id);
                                        $('#form-order-ss').submit();

                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        setTimeout(function () {
                                            window.location = laroute.route('admin.order');
                                        }, 1000);
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });
                $('#receipt-print-debt').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;
                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('customer-lead.customer-deal.submit-payment'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_submit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                customer_appointment_id: $('#customer_appointment_id').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                deal_id: $('#deal_id').val(),
                                deal_code: $('#deal_code').val(),
                                order_source_id: $('#order_source_id').val(),
                                member_money: $('#member_money').val(),
                                arrAppointment: arrAppointment,
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val(),
                                customer_contact_id: $('#customer_contact_id_hidden').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == false) {
                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });

                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();
                                    } else {
                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();

                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        setTimeout(function () {
                                            window.location = laroute.route('admin.order');
                                        }, 1000);
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });

                $('#receipt-close-btn').click(function () {
                    $('#modal-receipt').modal('hide');
                });
            }
        } else if (type == 'appointment') {
            const resultPromise = new Promise((res, rej) => {
                var table_subbmit = [];

                $.each($('#table_add').find(".tr_table"), function () {
                    var objectId = $(this).find("input[name='id']").val();
                    var objectName = $(this).find("input[name='name']").val();
                    var objectType = $(this).find("input[name='object_type']").val();
                    var objectCode = $(this).find("input[name='object_code']").val();
                    var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '');
                    var quantity = $(this).find("input[name='quantity']").val();
                    var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '');
                    var voucherCode = $(this).find("input[name='voucher_code']").val();
                    var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '');
                    var staffId = $(this).find("input[name='staff_id']").val();
                    var isChangePrice = $(this).find("input[name='is_change_price']").val();
                    var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val();
                    var numberRow = $(this).find("input[name='numberRow']").val();
                    var note = $(this).find("input[name='note']").val();

                    if (amount < 0) {
                        $('.error-table').text(processPayment.jsonLang['Tổng tiền không hợp lệ']);
                        continute = false;
                    }

                    var number = $(this).find("input[name='number_tr']").val();

                    var arrayAttach = [];

                    //Lấy sản phẩm/dịch vụ kèm theo
                    $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                        var objectIdAttach = $(this).find("input[name='object_id']").val();
                        var objectNameAttach = $(this).find("input[name='object_name']").val();
                        var objectTypeAttach = $(this).find("input[name='object_type']").val();
                        var objectCodeAttach = $(this).find("input[name='object_code']").val();
                        var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '');
                        var quantityAttach = $(this).find("input[name='quantity']").val();

                        arrayAttach.push({
                            object_id: objectIdAttach,
                            object_name: objectNameAttach,
                            object_type: objectTypeAttach,
                            object_code: objectCodeAttach,
                            price: priceAttach,
                            quantity: quantityAttach
                        });
                    });

                    table_subbmit.push({
                        object_id: objectId,
                        object_name: objectName,
                        object_type: objectType,
                        object_code: objectCode,
                        price: price,
                        quantity: quantity,
                        discount: discount,
                        voucher_code: voucherCode,
                        amount: amount,
                        staff_id: staffId,
                        is_change_price: isChangePrice,
                        is_check_promotion: isCheckPromotion,
                        number_row: numberRow,
                        note: note,
                        array_attach: arrayAttach
                    });
                });

                if (table_subbmit == '') {
                    $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
                    res({
                        'status': false
                    });
                } else {
                    $('.error-table').text('');
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('input[name="amount_bill_input"]').val();

                    var receipt_info_check = 0;
                    if ($('.receipt_info_check').is(':checked')) {
                        receipt_info_check = 1;
                    }

                    $.ajax({
                        url: laroute.route('admin.order.save-or-update-order'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            order_id: $('#order_id').val(),
                            order_code: $('#order_code').val(),
                            customer_id: customer_id,
                            total_bill: total_bill,
                            discount_bill: discount_bill,
                            amount_bill: amount_bill,
                            voucher_bill: voucher_bill,
                            table_add: table_subbmit,
                            customer_appointment_id: $('#customer_appointment_id').val(),
                            refer_id: $('#refer_id').val(),
                            custom_price: $('#custom_price').val(),
                            order_description: $('[name="order_description"]').val(),
                            type_time: $('#type_time_hidden').val(),
                            time_address: $('#time_address_hidden').val(),
                            customer_contact_id: $('#customer_contact_id_hidden').val(),
                            receipt_info_check: receipt_info_check,
                            tranport_charge: $('#delivery_fee').val(),
                            delivery_type: $('#delivery_type').val(),
                            delivery_cost_id: $('#delivery_cost_id').val(),
                            discount_member: $('#member_level_discount').val().replace(new RegExp('\\,', 'g'), '')
                        },
                        success: function (response) {
                            if (response.error == true) {
                                res({
                                    'status': false
                                });
                            } else {
                                res({
                                    'status': true,
                                    'order_id': response.order_id,
                                    'order_code': response.order_code
                                });
                            }
                        }
                    });
                }
            });
            Promise.all([resultPromise])
                .then(result => {
                    let res = result[0];
                    if (res.status) {
                        $('#order_id').val(res.order_id);
                        $('#order_code').val(res.order_code);
                    } else {
                        return false;
                    }

                })
                .catch(function (error) {
                    console.log(error);
                    return false;
                });
            var table_subbmit = [];

            $.each($('#table_add').find(".tr_table"), function () {
                var objectId = $(this).find("input[name='id']").val();
                var objectName = $(this).find("input[name='name']").val();
                var objectType = $(this).find("input[name='object_type']").val();
                var objectCode = $(this).find("input[name='object_code']").val();
                var price = $(this).find("input[name='price']").val().replace(new RegExp('\\,', 'g'), '');
                var quantity = $(this).find("input[name='quantity']").val();
                var discount = $(this).find("input[name='discount']").val().replace(new RegExp('\\,', 'g'), '');
                var voucherCode = $(this).find("input[name='voucher_code']").val();
                var amount = $(this).find("input[name='amount']").val().replace(new RegExp('\\,', 'g'), '');
                var staffId = $(this).find("input[name='staff_id']").val();
                var isChangePrice = $(this).find("input[name='is_change_price']").val();
                var isCheckPromotion = $(this).find("input[name='is_check_promotion']").val();
                var numberRow = $(this).find("input[name='numberRow']").val();
                var note = $(this).find("input[name='note']").val();

                if (amount < 0) {
                    $('.error-table').text(processPayment.jsonLang['Tổng tiền không hợp lệ']);
                    continute = false;
                }

                var number = $(this).find("input[name='number_tr']").val();

                var arrayAttach = [];

                //Lấy sản phẩm/dịch vụ kèm theo
                $.each($('#table_add').find('.tr_child_' + number + ''), function () {
                    var objectIdAttach = $(this).find("input[name='object_id']").val();
                    var objectNameAttach = $(this).find("input[name='object_name']").val();
                    var objectTypeAttach = $(this).find("input[name='object_type']").val();
                    var objectCodeAttach = $(this).find("input[name='object_code']").val();
                    var priceAttach = $(this).find("input[name='price_attach']").val().replace(new RegExp('\\,', 'g'), '');
                    var quantityAttach = $(this).find("input[name='quantity']").val();

                    arrayAttach.push({
                        object_id: objectIdAttach,
                        object_name: objectNameAttach,
                        object_type: objectTypeAttach,
                        object_code: objectCodeAttach,
                        price: priceAttach,
                        quantity: quantityAttach
                    });
                });

                table_subbmit.push({
                    object_id: objectId,
                    object_name: objectName,
                    object_type: objectType,
                    object_code: objectCode,
                    price: price,
                    quantity: quantity,
                    discount: discount,
                    voucher_code: voucherCode,
                    amount: amount,
                    staff_id: staffId,
                    is_change_price: isChangePrice,
                    is_check_promotion: isCheckPromotion,
                    number_row: numberRow,
                    note: note,
                    array_attach: arrayAttach
                });
            });

            var check_service_card = [];
            $.each($('#table_add').find(".tr_table"), function () {
                var $check_amount = $(this).find("input[name='object_type']");
                if ($check_amount.val() == 'service_card') {
                    $.each($check_amount, function () {
                        check_service_card.push($(this).val());
                    });
                }
            });


            if (table_subbmit == '') {
                $('.error-table').text(processPayment.jsonLang['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
            } else {
                if ($('#customer_id').val() != 1) {
                    if ($('#money_customer').val() <= 0) {
                        $("#receipt_type option[value='MEMBER_MONEY']").remove();
                    } else {
                        // Check exist option member money
                        if (!($("#receipt_type option[value='MEMBER_MONEY']").length > 0)) {
                            $('#receipt_type').append('<option value="MEMBER_MONEY" class="member_money_op">Tài khoản thành viên</option>');
                        }
                    }

                    if (check_service_card.length > 0) {
                        var tpl = $('#active-tpl').html();
                        $('.checkbox_active_card').append(tpl);
                        $("#check_active").change(function () {
                            if ($(this).is(":checked")) {
                                $(this).val(1);
                            } else if ($(this).not(":checked")) {
                                $(this).val(0);
                            }
                        });

                    } else {
                        $('.checkbox_active_card').empty();
                    }
                } else {
                    $('.member_money').empty();
                    $('.checkbox_active_card').empty();
                    $("#receipt_type option[value='MEMBER_MONEY']").remove(); // Xoá option chọn tiền hội viên
                }
                // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                var listSVAndMemberCard = [];
                $.each($('#table_add').find(".tr_table"), function () {
                    let table_row = {};
                    let objectType = $(this).find($("input[name='object_type']")).val();
                    let objectId = $(this).find($("input[name='id']")).val();
                    let objectName = $(this).find($("input[name='name']")).val();
                    if (objectType == "service" || objectType == "member_card") {
                        table_row["object_id"] = objectId;
                        table_row["object_name"] = objectName;
                        table_row["object_type"] = objectType;
                        table_row["object_code"] = $(this).find($("input[name='object_code']")).val();
                        table_row["price"] = $(this).find($("input[name='price']")).val();
                        table_row["quantity"] = $(this).find($("input[name='quantity']")).val();
                        table_row["quantity_hidden"] = $(this).find($("input[name='quantity_hid']")).val();
                        table_row["discount"] = $(this).find($("input[name='discount']")).val();
                        table_row["voucher_code"] = $(this).find($("input[name='voucher_code']")).val();
                        table_row["amount"] = $(this).find($("input[name='amount']")).val();
                        table_row["staff_id"] = $(this).find($("select[name='staff_id']")).val();
                        listSVAndMemberCard.push(table_row);
                    }
                });
                if (listSVAndMemberCard.length > 0 && $('#customer_id').val() != 1) {
                    $('.add-quick-appointment').css("display", 'block');
                } else {
                    $('.add-quick-appointment').css("display", 'none');
                    $('#cb_add_appointment').prop('checked', false);
                    $('.append-appointment').empty();
                }
                $("#cb_add_appointment").change(function () {
                    if ($(this).is(":checked")) {
                        $('.append-appointment').empty();
                        // show ngày giờ, dịch vụ, thẻ liệu trình
                        let tpl = $('#quick_appointment_tpl').html();
                        $('.append-appointment').append(tpl);
                        let arrMemberCard = []; // số thẻ liệu trình đã chọn trong đơn hàng
                        listSVAndMemberCard.map(function (item) {
                            if (item.object_type == "member_card") {
                                arrMemberCard.push(parseInt(item.object_id));
                            }
                        });
                        // Load sẵn thẻ liệu trình của khách hàng
                        $.ajax({
                            url: laroute.route('admin.order.check-card-customer'),
                            dataType: 'JSON',
                            method: 'POST',
                            data: {
                                id: $('#customer_id').val()
                            },
                            success: function (res) {
                                $('.customer_svc').empty();
                                if (res.number_card > 0) {
                                    console.log(arrMemberCard);
                                    res.data.map(function (item) {
                                        console.log(arrMemberCard.includes(item.customer_service_card_id));
                                        if (arrMemberCard.includes(item.customer_service_card_id)) {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '" selected>' + item.card_name + '</option>');
                                        } else {
                                            $('.customer_svc').append('<option value="' + item.customer_service_card_id + '">' + item.card_name + '</option>');
                                        }
                                    });
                                }
                            }
                        });
                        $('#date, #end_date').datepicker({
                            startDate: '0d',
                            language: 'vi',
                            orientation: "bottom left", todayHighlight: !0,
                        }).on('changeDate', function (ev) {
                            $(this).datepicker('hide');
                        });
                        $('#time, #end_time').timepicker({
                            minuteStep: 1,
                            defaultTime: "",
                            showMeridian: !1,
                            snapToStep: !0,
                        });
                        $('.staff_id').select2({
                            placeholder: processPayment.jsonLang['Chọn nhân viên']
                        });
                        $('.room_id').select2({
                            placeholder: processPayment.jsonLang['Chọn phòng']
                        });
                        $('.service_id').select2({
                            placeholder: processPayment.jsonLang['Chọn dịch vụ']
                        });
                        $('.customer_svc').select2();

                    } else if ($(this).not(":checked")) {
                        // xoá
                        $('.append-appointment').empty();
                    }
                });
                // END UPDATE

                //Check voucher còn sử dụng được hay ko
                var voucher_using = [];

                $.each($('#table_add').find(".tr_table"), function () {
                    var voucher_code = $(this).find("input[name='voucher_code']").val();
                    var type = $(this).find("input[name='object_type']").val();
                    if (voucher_code != '') {
                        voucher_using.push({
                            code: voucher_code,
                            type: type
                        });
                    }
                });
                if (voucher_using.length > 0) {
                    $.ajax({
                        url: laroute.route('admin.order.check-voucher'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            voucher_bill: $('#voucher_code_bill').val(),
                            voucher_using: voucher_using
                        },
                        success: function (res) {
                            if (res.is_success == false) {
                                $('.error-table').text(processPayment.jsonLang['Voucher bạn đang sử dụng đã hết số lần sử dụng']);
                            } else {
                                $('.error-table').text('');
                                $('#modal-receipt').modal('show');
                            }
                        }
                    });
                } else {
                    $('#modal-receipt').modal('show');
                }

                $('#receipt-btn').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;

                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.customer_appointment.submitReceipt'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                customer_appointment_id: $('#customer_appointment_id').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment,
                                tranport_charge: $('#delivery_fee').val().replace(new RegExp('\\,', 'g'), ''),
                                delivery_type: $('#delivery_type').val(),
                                delivery_cost_id: $('#delivery_cost_id').val()
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {

                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });

                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        // }
                                    } else {
                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        window.location = laroute.route('admin.customer_appointment.list-day');
                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });
                $('#receipt-print-btn').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;

                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.customer_appointment.submitReceipt'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                customer_appointment_id: $('#customer_appointment_id').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {

                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });

                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        $('#orderiddd').val(response.orderId);

                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        $('#form-order-ss').submit();
                                        // }
                                    } else {
                                        $('#orderiddd').val(response.orderId);
                                        $('#form-order-ss').submit();

                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location = laroute.route('admin.customer_appointment.list-day');
                                        }, 1000);
                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });
                $('#receipt-print-debt').click(function () {
                    var check = true;
                    var customer_id = $('#customer_id').val();
                    var voucher_bill = $('#voucher_code_bill').val();
                    var total_bill = $('input[name="total_bill"]').val();
                    var discount_bill = $('input[name="discount_bill"]').val();
                    var amount_bill = $('#receipt_amount').val();
                    var amount_return = $('#amount_return').val();
                    var receipt_type = $('#receipt_type').val();
                    let arrayMethod = {};
                    $.each($('.payment_method').find('.method'), function () {
                        let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
                        let getId = $(this).find("input[name='payment_method']").attr('id');
                        let methodCode = getId.slice(15);
                        arrayMethod[methodCode] = moneyEachMethod;
                    });
                    var id_amount_card = $('#service_card_search').val();
                    var amount_card = $('#service_cash').val();
                    var card_code = $('#service_card_search').val();
                    var note = $('#note').val();
                    var member_money = $('#money_customer').val();
                    var discount_member = $('#member_level_discount').val();

                    if (receipt_type == '') {
                        $('.error_type').text(processPayment.jsonLang['Hãy chọn hình thức thanh toán']);
                        check = false;
                    } else {
                        $('.error_type').text('');
                        check = true;
                    }
                    // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
                    // validate
                    if ($("#cb_add_appointment").is(":checked")) {
                        if ($('#date').val() == "") {
                            $('.error_date_appointment').text(processPayment.jsonLang['Hãy chọn ngày hẹn']);
                            check = false;
                        } else {
                            $('.error_date_appointment').text('');
                            check = true;
                        }
                        if ($('#time').val() == "") {
                            $('.error_time_appointment').text(processPayment.jsonLang['Hãy chọn giờ hẹn']);
                            check = false;
                        } else {
                            $('.error_time_appointment').text('');
                            check = true;
                        }
                    }
                    // get data
                    let arrAppointment = {};
                    if ($("#cb_add_appointment").is(":checked")) {
                        arrAppointment['checked'] = 1;
                        arrAppointment['date'] = $('#date').val();
                        arrAppointment['time'] = $('#time').val();
                        arrAppointment['end_date'] = $('#end_date').val();
                        arrAppointment['end_time'] = $('#end_time').val();
                        // Dịch vụ + thẻ liệu trình
                        let table_quantity = [];
                        $.each($('#table_quantity').find(".tr_quantity"), function () {
                            var stt = $(this).find("input[name='customer_order']").val();
                            var sv = '';
                            if ($('#service_id_' + stt + '').val() != '') {
                                sv = $('#service_id_' + stt + '').val();
                            }
                            var arr = {
                                stt: stt,
                                sv: sv,
                                staff: $('#staff_id_' + stt + '').val(),
                                room: $('#room_id_' + stt + '').val(),
                                object_type: $(this).find("input[name='object_type']").val()
                            };
                            table_quantity.push(arr);
                        });
                        arrAppointment['table_quantity'] = table_quantity;

                    } else {
                        arrAppointment['checked'] = 0;
                    }
                    // END UPDATE
                    if (check == true) {
                        $.ajax({
                            url: laroute.route('admin.customer_appointment.submitReceipt'),
                            data: {
                                order_id: $('#order_id').val(),
                                order_code: $('#order_code').val(),
                                customer_id: customer_id,
                                member_money: member_money,
                                total_bill: total_bill,
                                discount_bill: discount_bill,
                                amount_bill: amount_bill,
                                amount_return: amount_return,
                                table_add: table_subbmit,
                                voucher_bill: voucher_bill,
                                receipt_type: receipt_type,
                                amount_all: $('#amount_all').val(),
                                array_method: arrayMethod,
                                id_amount_card: id_amount_card,
                                amount_card: amount_card,
                                card_code: card_code,
                                note: note,
                                check_active: $('#check_active').val(),
                                customer_appointment_id: $('#customer_appointment_id').val(),
                                refer_id: $('#refer_id').val(),
                                discount_member: discount_member,
                                custom_price: $('#custom_price').val(),
                                arrAppointment: arrAppointment
                            },
                            method: 'POST',
                            dataType: "JSON",
                            success: function (response) {
                                if (response.amount_null == 1) {
                                    $('.error_amount_null').text(response.message);
                                } else {
                                    $('.error_amount_null').text('');
                                }
                                if (response.amount_detail_large == 1) {
                                    $('.error_amount_large').text(response.message);
                                } else {
                                    $('.error_amount_large').text('');
                                }
                                if (response.amount_detail_small == 1) {
                                    $('.error_amount_small').text(response.message);
                                } else {
                                    $('.error_amount_small').text('');
                                }
                                if (response.not_id_table == 1) {
                                    $('.not_id_table').text(processPayment.jsonLang['Không có dịch vụ để sử dụng thẻ']);
                                } else {
                                    $('.not_id_table').text('');
                                }
                                if (response.error_account_money == 1) {
                                    $('.error_account_money').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money').text('');
                                }
                                if (response.error_account_money_null == 1) {
                                    $('.error_account_money_null').text(processPayment.jsonLang['Tiền trong tài khoản không đủ']);
                                } else {
                                    $('.error_account_money_null').text('');
                                }
                                if (response.money_owed_zero == 1) {
                                    $('.money_owed_zero').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_owed_zero').text('');
                                }
                                if (response.money_large_moneybill == 1) {
                                    $('.money_large_moneybill').text(processPayment.jsonLang['Tiền tài khoản không hợp lệ']);
                                } else {
                                    $('.money_large_moneybill').text('');
                                }
                                if (response.error == true) {

                                    $('#modal-receipt').modal('hide');
                                    if (response.print_card.length > 0) {
                                        $.ajax({
                                            url: laroute.route('admin.order.render-card'),
                                            dataType: 'JSON',
                                            method: 'POST',
                                            data: {
                                                list_card: response.print_card
                                            },
                                            success: function (res) {
                                                $('.list-card').empty();
                                                $('.list-card').append(res);
                                                $('#modal-print').modal('show');

                                                var list_code = [];
                                                $.each($('.list-card').find(".toimg"), function () {
                                                    var $tds = $(this).find("input[name='code']");
                                                    $.each($tds, function () {
                                                        list_code.push($(this).val());
                                                    });
                                                });
                                                for (let i = 1; i <= list_code.length; i++) {
                                                    html2canvas(document.querySelector("#check-selector-" + i + "")).then(canvas => {
                                                        $('.canvas').append(canvas);
                                                        var canvas = $(".canvas canvas");
                                                        var context = canvas.get(0).getContext("2d");
                                                        var dataURL = canvas.get(0).toDataURL();
                                                        var img = $("<img class='img-canvas' id=" + i + "></img>");
                                                        img.attr("src", dataURL);
                                                        canvas.replaceWith(img);
                                                    });
                                                }

                                            }
                                        });

                                        if (response.isSMS == 0) {
                                            $(".btn-send-sms").remove();
                                        }
                                        $('#modal-print').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();
                                        // }
                                    } else {
                                        $('#customer_id_bill_debt').val(customer_id);
                                        $('#form-customer-debt').submit();

                                        swal(processPayment.jsonLang["Thanh toán đơn hàng thành công"], "", "success");
                                        // let flagLoyalty = loyalty(response.orderId);
                                        // if (flagLoyalty == true) {
                                        setTimeout(function () {
                                            window.location = laroute.route('admin.customer_appointment.list-day');
                                        }, 1000);
                                        // }
                                    }
                                    $('.hiddenOrderIdss').val(response.orderId);
                                } else {
                                    swal(response.message, "", "error");
                                }
                            }
                        })
                    } else {
                        // mApp.unblock("#load");
                    }
                });

                $('#receipt-close-btn').click(function () {
                    $('#modal-receipt').modal('hide');
                });
            }
        }

    }
};

$(document).ready(function () {
    processPayment.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
    ;
});
