// scroll customer
$('#scroll-customer').scroll(function() {
    if ($('#scroll-customer').scrollTop() + 520 >= $('#conversation-list').height()) {
        var customer = $('#list-customer').html();
        var channel_id = $("#selectChannel").val();
        var search= $('#search_message').val();
        var type_reading= $('#type_reading').val();
        $.ajax({
            url: laroute.route('message.add-customer'),
            method: 'POST',
            async: false,
            data: { customer, channel_id, search, type_reading},
            success: function(res) {
                $('#list-customer').html(parseInt(customer) + res.length);
                res.forEach(function(element) {
                    var tpl = $('#customer-add').html();
                    tpl = tpl.replace(/{register_object_id}/g, element['customer_register_id']);
                    tpl = tpl.replace(/{channel_id}/g, element['channel_id']);
                    tpl = tpl.replace(/{avatar}/g, element['avatar']);
                    tpl = tpl.replace(/{full_name_con}/g, element['full_name']);
                    tpl = tpl.replace(/{full_name}/g, element['full_name']);
                    tpl = tpl.replace(/{last_message}/g, element['last_message']);
                    tpl = tpl.replace(/{last_time}/g, element['last_time']);
                    if (element['is_read'] > 0) {
                        tpl = tpl.replace(/{bg}/g, "bg-secondary");
                        tpl = tpl.replace(/{is_read}/g, element['is_read']);
                    } else {
                        tpl = tpl.replace(/{is_read}/g, '');
                        tpl = tpl.replace(/{bg}/g, '');
                    }
                    tpl = tpl.replace(/{id}/g, element['customer_register_id'] + '_' + element['channel_id']);
                    tpl = tpl.replace(/{customer_id}/g, element['customer_id']);
                    tpl = tpl.replace(/{channel_name}/g, element['channel_name']);
                    if(element['is_read'] > 0){
                        tpl = tpl.replace(/{icon_message}/g, '<i class="fas fa-circle" style="color:blue"></i>');
                        tpl = tpl.replace(/{text-color}/g, 'blue');
                    }
                    else if(element['last_message_send'] == element['last_message']){
                        tpl = tpl.replace(/{icon_message}/g, '');
                        tpl = tpl.replace(/{text-color}/g, '');
                    }
                    else{
                        tpl = tpl.replace(/{icon_message}/g, '<i class="fas fa-check-circle"></i>');
                        tpl = tpl.replace(/{text-color}/g, '');
                    }
                    $('#conversation-list').append(tpl);

                });

            },
        });
    }
});
var stt = 0;
var numberPhone = 0;
var numberEmail = 0;
var numberFanpage = 0;
var numberContact = 0;

var dealMessage = {
    addObject: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            stt++;
            var tpl = $('#tpl-object').html();
            tpl = tpl.replace(/{stt}/g, stt);
            $('.append-object').append(tpl);
            $('.object_type').select2({
                placeholder: json['Chọn loại']
            });

            $('.object_code').select2({
                placeholder: json['Chọn đối tượng']
            });

            $(".object_quantity").TouchSpin({
                initval: 1,
                min: 1,
                buttondown_class: "btn btn-default down btn-ct",
                buttonup_class: "btn btn-default up btn-ct"

            });

            // Tính lại giá khi thay đổi số lượng
            $('.object_quantity, .object_discount').change(function () {
                $(this).closest('tr').find('.object_amount').empty();
                var type = $(this).closest('tr').find('.object_type').val();
                var id_type = 0;
                if (type === "product") {
                    id_type = 1;
                } else if (type === "service") {
                    id_type = 2;
                } else if (type === "service_card") {
                    id_type = 3;
                }
                var price = $(this).closest('tr').find('input[name="object_price"]').val().replace(new RegExp('\\,', 'g'), '');
                var discount = $(this).closest('tr').find('input[name="object_discount"]').val();
                var loc = discount.replace(new RegExp('\\,', 'g'), '');
                var quantity = $(this).closest('tr').find('input[name="object_quantity"]').val();

                var amount = ((price * quantity) - loc);

                $(this).closest('tr').find('.object_amount').val(formatNumber(amount.toFixed(decimal_number)));


                $('#amount').empty();
                $('#amount-remove').html('');
                $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
                var sum = 0;
                $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                    sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                });
                $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
                new AutoNumeric.multiple('#amount', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

            });

            new AutoNumeric.multiple('#object_discount_' + stt + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                eventIsCancelable: true,
                minimumValue: 0
            });
        });
    },

    removeObject: function (obj) {
        $(obj).closest('.add-object').remove();
        $('#amount').empty();
        $('#amount-remove').html('');
        $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
            sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
        });
        $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
        new AutoNumeric.multiple('#amount', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });

    },

    changeObjectType: function (obj) {
        $.getJSON(laroute.route('translate'), function (json) {
            var object = $(obj).val();
            // product, service, service_card
            $(obj).closest('tr').find('.object_code').prop('disabled', false);
            $(obj).closest('tr').find('.object_code').val('').trigger('change');

            $(obj).closest('tr').find('.object_code').select2({
                width: '100%',
                placeholder: json['Chọn đối tượng'],
                ajax: {
                    url: laroute.route('customer-lead.customer-deal.load-object'),
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            type: $(obj).val()
                        };
                    },
                    dataType: 'json',
                    method: 'POST',
                    processResults: function (data) {
                        data.page = data.page || 1;
                        return {
                            results: data.items.map(function (item) {
                                if ($(obj).val() == 'product') {
                                    return {
                                        id: item.product_code,
                                        text: item.product_child_name,
                                        code: item.product_code
                                    };
                                } else if ($(obj).val() == 'service') {
                                    return {
                                        id: item.service_code,
                                        text: item.service_name,
                                        code: item.service_code
                                    };
                                } else if ($(obj).val() == 'service_card') {
                                    return {
                                        id: item.code,
                                        text: item.card_name,
                                        code: item.code
                                    };
                                }
                            }),
                            pagination: {
                                more: data.pagination
                            }
                        };
                    },
                }
            });
        });
    },

    changeObject: function (obj) {
        console.log("changeObject");
        var object_type = $(obj).closest('tr').find('.object_type').val();
        var object_code = $(obj).val();

        //get price of object
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.get-price-object'),
            dataType: 'JSON',
            data: {
                object_type: object_type,
                object_code: object_code,
            },
            method: 'POST',
            success: function (result) {
                if (Object.keys(result).length === 0) {
                    $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(0).toFixed(decimal_number)));
                    $(obj).closest('tr').find($('.object_amount')).val(formatNumber(Number(0).toFixed(decimal_number)));
                } else {
                    if (object_type == 'product') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        // Reset số lượng về 1, Tính lại tiền * số lượng
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.product_child_id);
                    } else if (object_type == 'service') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price_standard).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price_standard).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.service_id);
                    } else if (object_type == 'service_card') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_quantity').val(1);
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_id').val(result.service_card_id);
                    }
                }

                // Tính lại tổng tiền
                $('#amount').empty();
                $('#amount-remove').html('');
                $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
                var sum = 0;
                $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                    sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                });
                $('#amount').val(formatNumber(sum.toFixed(decimal_number)));
                new AutoNumeric.multiple('#amount', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });


    },

    saveDeal: function(){

        $.getJSON(laroute.route('translate'), function (json) {
            if ($('#pipeline_code').val() == '') {
                swal(json['Hãy chọn pipeline'], '', "error");
                return;
            }
            if ($('#end_date_expected').val() == '') {
                swal(json['Hãy chọn ngày kết thúc dự kiến'], '', "error");
                return;
            }
            // check object
            $.each($('#table_add > tbody').find('.add-object'), function () {

                var object_type = $(this).find($('.object_type')).val();
                var object_code = $(this).find($('.object_code')).val();

                if (object_type == "") {
                    swal(json['Vui lòng chọn loại'], '', "error");
                    $(this).find($('.error_object_type')).text(json['Vui lòng chọn loại sản phẩm']);
                    return;
                } else {
                    $(this).find($('.error_object_type')).text('');
                }
                if (object_code == "") {
                    swal(json['Vui lòng chọn sản phẩm'], '', "error");
                    $(this).find($('.error_object')).text(json['Vui lòng chọn sản phẩm']);
                    return;
                } else {
                    $(this).find($('.error_object')).text('');
                }
            });

            // Lấy danh sách object (nếu có)
            var arrObject = [];
            $.each($('#table_add > tbody').find('.add-object'), function () {

                var object_type = $(this).find($('.object_type')).val();
                var object_name = $(this).find($('.object_code')).text();
                var object_code = $(this).find($('.object_code')).val();
                var object_id = $(this).find($('.object_id')).val();
                var price = $(this).find($('.object_price')).val();
                var quantity = $(this).find($('.object_quantity')).val();
                var discount = $(this).find($('.object_discount')).val();
                var amount = $(this).find($('.object_amount')).val();

                arrObject.push({
                    object_type: object_type,
                    object_name: object_name,
                    object_code: object_code,
                    object_id: object_id,
                    price: price,
                    quantity: quantity,
                    discount: discount,
                    amount: amount
                });
            });

            $.ajax({
                url: laroute.route('message.create-deal'),
                method: 'post',
                data: {
                    customer_id: message.customer_id,
                    end_date_expected: $('#end_date_expected').val(),
                    pipeline_code: $('#pipeline_code').val(),
                    journey_code: $('#journey_code').val(),
                    amount: $('#amount').val(),
                    arrObject: arrObject

                },
                success: function (res) {
                    if (res.error) {
                        swal.fire(res.message, "", "error");
                    } else {
                        $('#kt_modal_card').modal('hide');
                        swal.fire(res.message, "", "success");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(mess_error, '', "error");
                }
            });
        });
    },

    cancelSaveDeal: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json["Thông báo"],
                text: json["Những thay đổi vừa rồi sẽ không được lưu lại, bạn có muốn tiếp tục?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json["Có"],
                cancelButtonText: json["Không"],
            }).then(function (result) {
                if (result.value) {
                    $('#modal-message-create-deal').modal('hide');
                }
            });
        });
    }
};

var viewLead = {
    addPhone: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            //check các trường dữ liệu rỗng thì báo lỗi
            $.each($('.phone_append').find(".div_phone_attach"), function () {
                var phone = $(this).find($('.phone_attach')).val();
                var number = $(this).find($('.number_phone')).val();

                if (phone == '') {
                    $('.error_phone_attach_' + number + '').text(json['Hãy nhập số điện thoại']);
                    continute = false;
                } else {
                    $('.error_phone_attach_' + number + '').text('');
                }
            });

            if (continute == true) {
                numberPhone++;
                //append tr table
                var tpl = $('#tpl-phone').html();
                tpl = tpl.replace(/{number}/g, numberPhone);
                $('.phone_append').append(tpl);

                $('.phone').ForceNumericOnly();
            }
        });
    },
    removePhone: function (obj) {
        $(obj).closest('.div_phone_attach').remove();
    },
    addEmail: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            //check các trường dữ liệu rỗng thì báo lỗi
            $.each($('.email_append').find(".div_email_attach"), function () {
                var email = $(this).find($('.email_attach')).val();
                var number = $(this).find($('.number_email')).val();

                if (email == '') {
                    $('.error_email_attach_' + number + '').text(json['Hãy nhập email']);
                    continute = false;
                } else {
                    $('.error_email_attach_' + number + '').text('');
                }
            });

            if (continute == true) {
                numberEmail++;
                //append tr table
                var tpl = $('#tpl-email').html();
                tpl = tpl.replace(/{number}/g, numberEmail);
                $('.email_append').append(tpl);
            }
        });
    },
    removeEmail: function (obj) {
        $(obj).closest('.div_email_attach').remove();
    },
    addFanpage: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            //check các trường dữ liệu rỗng thì báo lỗi
            $.each($('.fanpage_append').find(".div_fanpage_attach"), function () {
                var fanpage = $(this).find($('.fanpage_attach')).val();
                var number = $(this).find($('.number_fanpage')).val();

                if (fanpage == '') {
                    $('.error_fanpage_attach_' + number + '').text(json['Hãy nhập fanpage']);
                    continute = false;
                } else {
                    $('.error_fanpage_attach_' + number + '').text('');
                }
            });

            if (continute == true) {
                numberFanpage++;
                //append tr table
                var tpl = $('#tpl-fanpage').html();
                tpl = tpl.replace(/{number}/g, numberFanpage);
                $('.fanpage_append').append(tpl);
            }
        });
    },
    removeFanpage: function (obj) {
        $(obj).closest('.div_fanpage_attach').remove();
    },
    changeType: function (obj) {
        $.getJSON(laroute.route('translate'), function (json) {
            if ($(obj).val() == 'personal') {
                $('.append_type').empty();

                $('.append_contact').empty();
                $('.div_add_contact').css('display', 'none');

                $('#table-contact > tbody').empty();

                $('.div_business_clue').css('display', 'block');

                $('#business_clue').select2({
                    placeholder: json['Chọn đầu mối doanh nghiệp']
                });
            } else {
                var tpl = $('#tpl-type').html();
                $('.append_type').append(tpl);

                $('.div_add_contact').css('display', 'block');

                $('.div_business_clue').css('display', 'none');
            }
        });
    },
    addContact: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            //check các trường dữ liệu rỗng thì báo lỗi
            $.each($('#table-contact').find(".tr_contact"), function () {
                var fullName = $(this).find($('.full_name_contact')).val();
                var phoneContact = $(this).find($('.phone_contact')).val();
                var emailContact = $(this).find($('.email_contact')).val();
                var addressContact = $(this).find($('.address_contact')).val();
                var number = $(this).find($('.number_contact')).val();

                if (fullName == '') {
                    $('.error_full_name_contact_' + number + '').text(json['Hãy nhập họ và tên']);
                    continute = false;
                } else {
                    $('.error_full_name_contact_' + number + '').text('');
                }

                if (phoneContact == '') {
                    $('.error_phone_contact_' + number + '').text(json['Hãy nhập số điện thoại']);
                    continute = false;
                } else {
                    $('.error_phone_contact_' + number + '').text('');
                }

                if (addressContact == '') {
                    $('.error_address_contact_' + number + '').text(json['Hãy nhập địa chỉ']);
                    continute = false;
                } else {
                    $('.error_address_contact_' + number + '').text('');
                }

                if (emailContact == '') {
                    $('.error_email_contact_' + number + '').text(json['Hãy nhập email']);
                    continute = false;
                } else {
                    $('.error_email_contact_' + number + '').text('');

                    if (isValidEmailAddress(emailContact) == false) {
                        $('.error_email_contact_' + number + '').text(json['Email không hợp lệ']);
                        continute = false;
                    } else {
                        $('.error_email_contact_' + number + '').text('');
                    }
                }
            });

            if (continute == true) {
                numberContact++;
                //append tr table
                var tpl = $('#tpl-contact').html();
                tpl = tpl.replace(/{number}/g, numberContact);
                $('#table-contact > tbody').append(tpl);

                $('.phone').ForceNumericOnly();
            }
        });
    },
    removeContact: function (obj) {
        $(obj).closest('.tr_contact').remove();
    },
    changeProvince: function (obj) {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $(obj).val()
            },
            method: 'POST',
            success: function (res) {
                $('.district').empty();

                $.map(res.optionDistrict, function (a) {
                    $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
            }
        });
    },
    changeBoolean: function (obj) {
        if ($(obj).is(":checked")) {
            $(obj).val(1);
        } else {
            $(obj).val(0);
        }
    },
    saveOrUpdate: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    full_name: {
                        required: true,
                        maxlength: 250
                    },
                    phone: {
                        required: true,
                        integer: true,
                        maxlength: 10
                    },
                    address: {
                        maxlength: 250
                    },
                    pipeline_code: {
                        required: true
                    },
                    journey_code: {
                        required: true
                    },
                    customer_type: {
                        required: true
                    },
                    tax_code: {
                        required: true,
                        maxlength: 50
                    },
                    representative: {
                        required: true,
                        maxlength: 250
                    },
                    customer_source: {
                        required: true
                    },
                    hotline: {
                        required: true
                    },
                },
                messages: {
                    full_name: {
                        required: json['Hãy nhập họ và tên'],
                        maxlength: json['Họ và tên tối đa 250 kí tự']
                    },
                    phone: {
                        required: json['Hãy nhập số điện thoại'],
                        integer: json['Số điện thoại không hợp lệ'],
                        maxlength: json['Số điện thoại tối đa 10 kí tự']
                    },
                    address: {
                        maxlength: json['Địa chỉ tối đa 250 kí tự']
                    },
                    pipeline_code: {
                        required: json['Hãy chọn pipeline']
                    },
                    journey_code: {
                        required: json['Hãy chọn hành trình khách hàng']
                    },
                    customer_type: {
                        required: json['Hãy chọn loại khách hàng']
                    },
                    tax_code: {
                        required: json['Hãy nhập mã số thuế'],
                        maxlength: json['Mã số thuế tối đa 50 kí tự']
                    },
                    representative: {
                        required: json['Hãy nhập người đại diện'],
                        maxlength: json['Người đại diện tối đa 250 kí tự']
                    },
                    customer_source: {
                        required: json['Hãy chọn nguồn khách hàng']
                    },
                    hotline: {
                        required: json['Hãy nhập hotline']
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }

            var continute = true;

            var arrPhoneAttack = [];
            var arrEmailAttack = [];
            var arrFanpageAttack = [];
            var arrContact = [];

            $.each($('.phone_append').find(".div_phone_attach"), function () {
                var phone = $(this).find($('.phone_attach')).val();
                var number = $(this).find($('.number_phone')).val();

                if (phone == '') {
                    $('.error_phone_attach_' + number + '').text(json['Hãy nhập số điện thoại']);
                    continute = false;
                } else {
                    $('.error_phone_attach_' + number + '').text('');
                }

                arrPhoneAttack.push({
                    phone: phone
                });
            });

            $.each($('.email_append').find(".div_email_attach"), function () {
                var email = $(this).find($('.email_attach')).val();
                var number = $(this).find($('.number_email')).val();

                if (email == '') {
                    $('.error_email_attach_' + number + '').text(json['Hãy nhập email']);
                    continute = false;
                } else {
                    $('.error_email_attach_' + number + '').text('');
                }

                arrEmailAttack.push({
                    email: email
                });
            });

            $.each($('.fanpage_append').find(".div_fanpage_attach"), function () {
                var fanpage = $(this).find($('.fanpage_attach')).val();
                var number = $(this).find($('.number_fanpage')).val();

                if (fanpage == '') {
                    $('.error_fanpage_attach_' + number + '').text(json['Hãy nhập fanpage']);
                    continute = false;
                } else {
                    $('.error_fanpage_attach_' + number + '').text('');
                }

                arrFanpageAttack.push({
                    fanpage: fanpage
                });
            });

            $.each($('#table-contact').find(".tr_contact"), function () {
                var fullName = $(this).find($('.full_name_contact')).val();
                var phoneContact = $(this).find($('.phone_contact')).val();
                var emailContact = $(this).find($('.email_contact')).val();
                var addressContact = $(this).find($('.address_contact')).val();
                var number = $(this).find($('.number_contact')).val();

                if (fullName == '') {
                    $('.error_full_name_contact_' + number + '').text(json['Hãy nhập họ và tên']);
                    continute = false;
                } else {
                    $('.error_full_name_contact_' + number + '').text('');
                }

                if (phoneContact == '') {
                    $('.error_phone_contact_' + number + '').text(json['Hãy nhập số điện thoại']);
                    continute = false;
                } else {
                    $('.error_phone_contact_' + number + '').text('');
                }

                if (addressContact == '') {
                    $('.error_address_contact_' + number + '').text(json['Hãy nhập địa chỉ']);
                    continute = false;
                } else {
                    $('.error_address_contact_' + number + '').text('');
                }

                if (emailContact == '') {
                    $('.error_email_contact_' + number + '').text(json['Hãy nhập email']);
                    continute = false;
                } else {
                    $('.error_email_contact_' + number + '').text('');

                    if (isValidEmailAddress(emailContact) == false) {
                        $('.error_email_contact_' + number + '').text(json['Email không hợp lệ']);
                        continute = false;
                    } else {
                        $('.error_email_contact_' + number + '').text('');
                    }
                }

                arrContact.push({
                    full_name: fullName,
                    phone: phoneContact,
                    email: emailContact,
                    address: addressContact
                });
            });
            if (!isValidEmailAddress($('#email').val())) {
                $('.error_email').text(json["Email không hợp lệ"]);
                var continute = false;
            }
            if (continute == true) {
                var dataBody = {
                    customer_id: message.customer_id,
                    full_name: $('#full_name').val(),
                    phone: $('#phone').val(),
                    gender: $('input[name="gender"]:checked').val(),
                    address: $('#address').val(),
                    avatar: $('#avatar').val(),
                    email: $('#email').val(),
                    tag_id: $('#tag_id').val(),
                    pipeline_code: $('#pipeline_code').val(),
                    journey_code: $('#journey_code').val(),
                    customer_type: $('#customer_type_create').val(),
                    hotline: $('#hotline').val(),
                    fanpage: $('#fanpage').val(),
                    zalo: $('#zalo').val(),
                    arrPhoneAttack: arrPhoneAttack,
                    arrEmailAttack: arrEmailAttack,
                    arrFanpageAttack: arrFanpageAttack,
                    arrContact: arrContact,
                    tax_code: $('#tax_code').val(),
                    representative: $('#representative').val(),
                    customer_source: $('#customer_source').val(),
                    business_clue: $('#business_clue').val(),
                    sale_id: $('#sale_id').val(),
                    province_id: $('#province_id').val(),
                    district_id: $('#district_id').val()
                };
                $.ajax({
                    url: laroute.route('message.check-exist-lead'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: dataBody,
                    success: function (res) {
                        if (res.error == true) {
                            swal({
                                title: json["Thông báo"],
                                text: json["Số điện thoại của khách hàng đã tồn tại dạng khách hàng tiềm năng. Bạn có muốn cập nhật thông tin khách hàng?"],
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: json["Có"],
                                cancelButtonText: json["Không"],
                            }).then(function (result) {
                                if (result.value) {
                                    $.ajax({
                                        url: laroute.route('message.create-or-update-lead'),
                                        method: 'POST',
                                        dataType: 'JSON',
                                        data: dataBody,
                                        success: function (res) {
                                            if (res.error == false) {
                                                swal(res.message, '', "success");
                                            } else {
                                                swal(res.message, '', "error");
                                            }
                                        },
                                        error: function (res) {
                                            var mess_error = '';
                                            $.map(res.responseJSON.errors, function (a) {
                                                mess_error = mess_error.concat(a + '<br/>');
                                            });
                                            swal(json['Thêm mới thất bại'], mess_error, "error");
                                        }
                                    });
                                }
                            });
                        } else {
                            $.ajax({
                                url: laroute.route('message.create-or-update-lead'),
                                method: 'POST',
                                dataType: 'JSON',
                                data: dataBody,
                                success: function (res) {
                                    if (res.error == false) {
                                        swal(res.message, '', "success");
                                    } else {
                                        swal(res.message, '', "error");
                                    }
                                },
                                error: function (res) {
                                    var mess_error = '';
                                    $.map(res.responseJSON.errors, function (a) {
                                        mess_error = mess_error.concat(a + '<br/>');
                                    });
                                    swal(json['Thêm mới thất bại'], mess_error, "error");
                                }
                            });
                        }
                    }
                });
            }
        });
    },

    cancelSaveLead: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json["Thông báo"],
                text: json["Những thay đổi vừa rồi sẽ không được lưu lại, bạn có muốn tiếp tục?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json["Có"],
                cancelButtonText: json["Không"],
            }).then(function (result) {
                if (result.value) {
                    $('#modal-message-create-lead').modal('hide');
                }
            });
        });
    }
};
//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
function cancelAddImage() {
    $('.dropzone-append-image').remove();
}

function cancelAddFile() {
    $('.dropzone-append-file').remove();
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

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}
