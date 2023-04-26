var number = 0;

var view = {
    _init: function () {
        $(document).ready(function () {
            
            if ($('#show_category').val() == 1) {
                //Show modal chọn loại HĐ
                $('#modal-category').modal('show');

                $('#category_choose').select2({
                    placeholder: edit.translateJson['Chọn loại hợp đồng']
                });
            } else {
                //Load view sau khi chọn loại HĐ
                view.loadViewChooseCategory($('#category_id_load').val(), $('#type').val(), $('#deal_code').val());
            }
       
        });
    },
    _initEdit: function () {
        $('.select').select2();

        $('.date_picker').datepicker({
            language: 'vi',
            orientation: "bottom left",
            todayHighlight: !0
        });

        new AutoNumeric.multiple('.input_float', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });

        $('.input_int').ForceNumericOnly();

        $("#tag").select2({
            // placeholder: edit.translateJson['Chọn tag'],
            tags: true,
            // tokenSeparators: [",", " "],
            createTag: function (tag) {
                return {
                    id: tag.term,
                    text: tag.term,
                    isNew: true
                };
            }
        }).on("select2:select", function (e) {
            if (e.params.data.isNew) {
                // store the new tag:
                $.ajax({
                    type: "POST",
                    url: laroute.route('contract.contract.insert-tag'),
                    data: {
                        tag_name: e.params.data.text
                    },
                    success: function (res) {
                        // append the new option element end replace id
                        $('#tag').find('[value="' + e.params.data.text + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                    }
                });
            }
        });

        $("#payment_method_id").select2({
            tags: true,
            createTag: function (tag) {
                return {
                    id: tag.term,
                    text: tag.term,
                    isNew: true
                };
            }
        }).on("select2:select", function (e) {
            if (e.params.data.isNew) {
                // store the new tag:
                $.ajax({
                    type: "POST",
                    url: laroute.route('contract.contract.insert-payment-method'),
                    data: {
                        payment_method_name: e.params.data.text
                    },
                    success: function (res) {
                        // append the new option element end replace id
                        $('#payment_method_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.payment_method_id + '">' + e.params.data.text + '</option>');
                    }
                });
            }
        });

        $("#payment_unit_id").select2({
            tags: true,
            createTag: function (tag) {
                return {
                    id: tag.term,
                    text: tag.term,
                    isNew: true
                };
            }
        }).on("select2:select", function (e) {
            if (e.params.data.isNew) {
                // store the new tag:
                $.ajax({
                    type: "POST",
                    url: laroute.route('contract.contract.insert-payment-unit'),
                    data: {
                        name: e.params.data.text
                    },
                    success: function (res) {
                        // append the new option element end replace id
                        $('#payment_unit_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.payment_unit_id + '">' + e.params.data.text + '</option>');
                    }
                });
            }
        });

        $('#status_code').select2({
            placeholder: edit.translateJson['Chọn trạng thái']
        });

        $('#status_code_created_ticket').select2({
            placeholder: edit.translateJson['Chọn trạng thái']
        });

        //Load ds dự kiến thu
        $('#autotable-expected-receipt').PioTable({
            baseUrl: laroute.route('contract.contract.list-expected-revenue')
        });

        $('.btn-search-expected-receipt').trigger('click');

        //Load ds đợt thu
        $('#autotable-receipt').PioTable({
            baseUrl: laroute.route('contract.contract.list-receipt')
        });

        $('.btn-search-receipt').trigger('click');

        //Load ds dự kiến chi
        $('#autotable-expected-spend').PioTable({
            baseUrl: laroute.route('contract.contract.list-expected-revenue')
        });

        $('.btn-search-expected-spend').trigger('click');

        //Load ds đợt chi
        $('#autotable-spend').PioTable({
            baseUrl: laroute.route('contract.contract.list-spend')
        });

        $('.btn-search-spend').trigger('click');

        //Load ds đính kèm
    
        $('#autotable-file').PioTable({
            baseUrl: laroute.route('contract.contract.list-file')
        });
       
        //Load ds đính kèm
        $('#autotable-annex').PioTable({
            baseUrl: laroute.route('contract.contract.annex.list')
        });

        $('.btn-search-file').trigger('click');

        //Load ds hàng hoá
        contractGoods.list();
        //Lấy trạng thái đơn hàng gần nhất
        view.getStatusOrder();
    
    },
    chooseCategory: function () {
        var form = $('#form-category');

        form.validate({
            rules: {
                category_choose: {
                    required: true
                },
            },
            messages: {
                category_choose: {
                    required: edit.translateJson['Hãy chọn loại hợp đồng']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        //Load view sau khi chọn loại HĐ
        view.loadViewChooseCategory($('#category_choose').val(), $('#type').val(), $('#deal_code').val());

        $('#modal-category').modal('hide');
    
    },
    changeCategory: function (obj) {
        //Load view sau khi chọn loại HĐ
        view.loadViewChooseCategory($(obj).val());
    },
    loadViewChooseCategory: function (categoryId, type = '', dealCode = '', customerId = 0) {
        
        $.ajax({
            url: laroute.route('contract.contract.choose-category'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: categoryId,
                type: type,
                deal_code: dealCode,
                order_code_load: $('#order_code_load').val()
            },
            success: function (res) {
                $('#group-info').html(res.html);
                $('.select').select2();

                if (type == 'from_deal' && res.dataCustomer != null) {
                    $('#partner_object_type').val(res.dataCustomer.customer_type);
                    // $('#partner_object_type').trigger('change');
                    $('#partner_object_type').attr('disabled', true);
                    $('#partner_object_id').append('<option value="' + res.dataCustomer.customer_id + '" selected>' + res.dataCustomer.full_name + '_' + res.dataCustomer.phone1 + '</option>');
                    $('#partner_object_id').attr('disabled', true);
                    view.choosePartner(null, res.dataCustomer.customer_id);
                } else {
                    view.choosePartnerType(null, '');
                }


                $('.date_picker').datepicker({
                    language: 'vi',
                    orientation: "bottom left",
                    todayHighlight: !0
                });

                new AutoNumeric.multiple('.input_float', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });

                $('.input_int').ForceNumericOnly();

                $("#tag").select2({
                    // placeholder: edit.translateJson['Chọn tag'],
                    tags: true,
                    // tokenSeparators: [",", " "],
                    createTag: function (tag) {
                        return {
                            id: tag.term,
                            text: tag.term,
                            isNew: true
                        };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.isNew) {
                        // store the new tag:
                        $.ajax({
                            type: "POST",
                            url: laroute.route('contract.contract.insert-tag'),
                            data: {
                                tag_name: e.params.data.text
                            },
                            success: function (res) {
                                // append the new option element end replace id
                                $('#tag').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                            }
                        });
                    }
                });

                $("#payment_method_id").select2({
                    tags: true,
                    createTag: function (tag) {
                        return {
                            id: tag.term,
                            text: tag.term,
                            isNew: true
                        };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.isNew) {
                        // store the new tag:
                        $.ajax({
                            type: "POST",
                            url: laroute.route('contract.contract.insert-payment-method'),
                            data: {
                                payment_method_name: e.params.data.text
                            },
                            success: function (res) {
                                // append the new option element end replace id
                                $('#payment_method_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.payment_method_id + '">' + e.params.data.text + '</option>');
                            }
                        });
                    }
                });

                $("#payment_unit_id").select2({
                    tags: true,
                    createTag: function (tag) {
                        return {
                            id: tag.term,
                            text: tag.term,
                            isNew: true
                        };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.isNew) {
                        // store the new tag:
                        $.ajax({
                            type: "POST",
                            url: laroute.route('contract.contract.insert-payment-unit'),
                            data: {
                                name: e.params.data.text
                            },
                            success: function (res) {
                                // append the new option element end replace id
                                $('#payment_unit_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.payment_unit_id + '">' + e.params.data.text + '</option>');
                            }
                        });
                    }
                });

                $('#status_code').select2({
                    placeholder: edit.translateJson['Chọn trạng thái']
                });

                $('#status_code_created_ticket').select2({
                    placeholder: edit.translateJson['Chọn trạng thái']
                });

                if (res.infoOrder != null) {
                    $('#partner_object_type').trigger('change');
                }
            }
        });
    
    },
    choosePartnerType: function (obj, customerId = '') {
        var id = obj != null ? $(obj).val() : $('#partner_object_type').val();

        $.ajax({
            url: laroute.route('contract.contract.change-partner-type'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                partner_object_type: id
            },
            success: function (res) {
                $('#partner_object_id').empty().prop('disabled', false);

                $('#partner_object_id').append('<option></option>');

                $.map(res.option, function (val) {
                    var info = '';

                    if (val.phone != null) {
                        info = val.name + '_' + val.phone;
                    } else {
                        info = val.name;
                    }

                    $('#partner_object_id').append('<option value="' + val.id + '">' + info + '</option>');
                });

                $('#partner_object_id').select2({
                    placeholder: res.placeholder
                });

                $('#partner_object_name').val('');
                $('#address').val('');
                $('#phone').val('');
                $('#email').val('');
                $('#representative').val('');
                $('#hotline').val('');
                $('#staff_title').val('');

                if (customerId != 0) {
                    $('#partner_object_id').val(customerId);
                    $('#partner_object_id').select2();
                    $('#partner_object_id').trigger('change');
                }
            }
        });
    },
    choosePartner: function (obj, customerId = '') {
        var id = obj != null ? $(obj).val() : customerId;
        $.ajax({
            url: laroute.route('contract.contract.change-partner'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                partner_object_type: $('#partner_object_type').val(),
                partner_object_id: id
            },
            success: function (res) {
                $('#partner_object_name').val(res.name);
                $('#address').val(res.address);
                $('#phone').val(res.phone);
                $('#email').val(res.email);
                $('#representative').val(res.representative);
                $('#hotline').val(res.hotline);
                $('#staff_title').val(res.staffTitle);
            }
        });
    },
    changeValueGoods: function (obj) {
        var is_value_goods = 0;
        if ($(obj).is(':checked')) {
            is_value_goods = 1;
        }

        $.ajax({
            url: laroute.route('contract.contract.change-value-goods'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                is_value_goods: is_value_goods,
                category_id: $('#category_id').val()
            },
            success: function (res) {
                $('.div-input-payment').empty();
                $('.div-input-payment').html(res.html);

                new AutoNumeric.multiple('.input_float', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });

                $('.input_int').ForceNumericOnly();

                $("#payment_method_id").select2({
                    tags: true,
                    createTag: function (newTag) {
                        return {
                            id: 'new:' + newTag.term,
                            text: newTag.term,
                            isNew: true
                        };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.isNew) {
                        // store the new tag:
                        $.ajax({
                            type: "POST",
                            url: laroute.route('contract.contract.insert-payment-method'),
                            data: {
                                payment_method_name: e.params.data.text
                            },
                            success: function (res) {
                                // append the new option element end replace id
                                $('#payment_method_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.payment_method_id + '">' + e.params.data.text + '</option>');
                            }
                        });
                    }
                });

                $("#payment_unit_id").select2({
                    tags: true,
                    createTag: function (newTag) {
                        return {
                            id: 'new:' + newTag.term,
                            text: newTag.term,
                            isNew: true
                        };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.isNew) {
                        // store the new tag:
                        $.ajax({
                            type: "POST",
                            url: laroute.route('contract.contract.insert-payment-unit'),
                            data: {
                                name: e.params.data.text
                            },
                            success: function (res) {
                                // append the new option element end replace id
                                $('#payment_method_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.payment_unit_id + '">' + e.params.data.text + '</option>');
                            }
                        });
                    }
                });
            }
        });
    },
    getStatusOrder: function () {
        
        $.ajax({
            url: laroute.route('contract.contract.get-status-order'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val()
            },
            success: function (res) {
                if (res.infoOrder != null) {
                    $('.div_search_order').css('display', 'none');

                    $('#order-goods').empty();
                    $('#order-goods').append('<input type="hidden" class="order_code_append" value="' + res.infoOrder.order_code + '">');

                    if (res.infoOrder.process_status != 'new') {
                        $('.div_add_goods').css('display', 'none');
                        $('#btn-save-good').css('display', 'none');
                    }

                    $('#contract_source').val(res.infoOrder.source);
                } else {
                    $('.div_search_order').css('display', 'block');
                    $('.div_add_goods').css('display', 'block');
                    $('#btn-save-good').css('display', 'block');

                    $('#contract_source').val('contract');
                }
            }
        });
   
    },
    changePrice: function () {
        var totalAmount = 0;
        var tax = 0;
        var discount = 0;

        if ($('#total_amount').val() != '') {
            totalAmount = $('#total_amount').val().replace(new RegExp('\\,', 'g'), '');
        }

        if ($('#discount').val() != '') {
            discount = $('#discount').val().replace(new RegExp('\\,', 'g'), '');
        }

        if ($('#tax').val() != '') {
            tax = $('#tax').val().replace(new RegExp('\\,', 'g'), '');
        }

        //Tính tổng tiền sau khi giảm giá
        var totalAmountAfterDiscount = parseInt(totalAmount) - parseInt(discount);

        var isValidateAfter = $('#total_amount_after_discount').attr("isValidate");
        var nameAfter = $('#total_amount_after_discount').attr("name");
        var keyNameAfter = $('#total_amount_after_discount').attr("keyName");
        var keyTypeAfter = $('#total_amount_after_discount').attr("keyType");

        $('#total_amount_after_discount').remove();

        $('.total_amount_after_discount').append('<input type="text" class="form-control m-input input_float" id="total_amount_after_discount" name="' + nameAfter + '" ' +
            'isValidate="' + isValidateAfter + '" keyName="' + keyNameAfter + '" keyType="' + keyTypeAfter + '" value="' + totalAmountAfterDiscount + '">');

        //Tính giá trị hợp đồng
        var amount = parseInt(totalAmountAfterDiscount) + parseInt(tax) ;

        var isValidate = $('#last_total_amount').attr("isValidate");
        var name = $('#last_total_amount').attr("name");
        var keyName = $('#last_total_amount').attr("keyName");
        var keyType = $('#last_total_amount').attr("keyType");

        $('#last_total_amount').remove();

        $('.last_total_amount').append('<input type="text" class="form-control m-input input_float" id="last_total_amount" name="' + name + '" ' +
            'isValidate="' + isValidate + '" keyName="' + keyName + '" keyType="' + keyType + '" value="' + amount + '">');

        new AutoNumeric.multiple('#last_total_amount, #total_amount_after_discount', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });
    },

    //Chọn VAT
    chooseVAT: function () {
        //Lấy option VAT
        var VAT = $("#vat_id option:selected").text();
        //Lấy tiền sau khi giảm giá
        var totalAmountAfterDiscount = 0;

        if ($('#total_amount_after_discount').val() != '') {
            totalAmountAfterDiscount = $('#total_amount_after_discount').val().replace(new RegExp('\\,', 'g'), '');
        }

        //Tính số tiền VAT
        var amountVAT = parseInt(totalAmountAfterDiscount * (VAT / 100));

        var isValidateVAT = $('#tax').attr("isValidate");
        var nameVAT = $('#tax').attr("name");
        var keyNameVAT = $('#tax').attr("keyName");
        var keyTypeVAT = $('#tax').attr("keyType");

        $('#tax').remove();

        $('.tax').append('<input type="text" class="form-control m-input input_float" id="tax" onchange="view.changePrice()" name="' + nameVAT + '" ' +
            'isValidate="' + isValidateVAT + '" keyName="' + keyNameVAT + '" keyType="' + keyTypeVAT + '" value="' + amountVAT + '">');

        new AutoNumeric.multiple('#tax', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });

        view.changePrice();
    },
};

var create = {
    save: function () {
        
        var form = $('#form-info');

        var rules = [];
        var messages = [];

        var dataGeneral = {};
        var dataPartner = {};
        var dataPayment = {};

        //Quét input cụm thông tin chung
        $.each($('#group-general'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            case 'tab_general_contract_name':
                                rules[$(this).attr("name")] = {
                                    required: true,
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống'],
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataGeneral[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataGeneral[$(this).attr("id")] = $(this).val();
                    }
                }
            });
        });

        //Quét input cụm đối tác
        $.each($('#group-partner'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            case 'tab_partner_address':
                                rules[$(this).attr("name")] = {
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            case 'tab_partner_email':
                                rules[$(this).attr("name")] = {
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataPartner[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataPartner[$(this).attr("id")] = $(this).val();
                    }
                }
            });
        });

        //Quét input cụm thanh toán
        $.each($('#group-payment'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataPayment[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataPayment[$(this).attr("id")] = $(this).val();
                    }

                }
            });
        });

        form.validate({
            rules: rules,
            messages: messages,
        });

        if (!form.valid()) {
            return false;
        }

        var is_renew = 0;
        if ($('#is_renew').is(':checked')) {
            is_renew = 1;
        }

        var is_created_ticket = 0;
        if ($('#is_created_ticket').is(':checked')) {
            is_created_ticket = 1;
        }

        var is_value_goods = 0;
        if ($('#is_value_goods').is(':checked')) {
            is_value_goods = 1;
        }

        $.ajax({
            url: laroute.route('contract.contract.store'),
            method: "POST",
            dataType: 'JSON',
            data: {
                deal_code: $('#deal_code').val(),
                dataGeneral: dataGeneral,
                dataPartner: dataPartner,
                dataPayment: dataPayment,
                status_code: $('#status_code').val(),
                is_renew: is_renew,
                number_day_renew: $('#number_day_renew').val(),
                is_created_ticket: is_created_ticket,
                status_code_created_ticket: $('#status_code_created_ticket').val(),
                contract_name: dataGeneral['contract_name'],
                contract_no: dataGeneral['contract_no'],
                category_type: $('#category_type').val(),
                is_value_goods: is_value_goods,
                order_code_load: $('#order_code_load').val(),
                contract_source: $('#contract_source').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            if (res.is_create_ticket == 1) {
                                if ($('#status_code').val() == $('#status_code_created_ticket').val() && is_created_ticket == 1) {
                                    swal({
                                        title: edit.translateJson["Thông báo"],
                                        text: edit.translateJson["Hợp đồng đang ở trạng thái cho phép tạo ticket, bạn có muốn tạo ticket cho hợp đồng này?"],
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: edit.translateJson["Có"],
                                        cancelButtonText: edit.translateJson["Không"]
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.open('/ticket/add?contract=' + res.contract_id, "_blank");
                                            window.location.href = res.url;
                                        }
                                        else {
                                            window.location.href = res.url;
                                        }
                                    });
                                }
                                else {
                                    window.location.href = res.url;
                                }
                            }
                            else {
                                window.location.href = res.url;
                            }
                        }
                        if (result.value == true) {
                            if (res.is_create_ticket == 1) {
                                if ($('#status_code').val() == $('#status_code_created_ticket').val() && is_created_ticket == 1) {
                                    swal({
                                        title: edit.translateJson["Thông báo"],
                                        text: edit.translateJson["Hợp đồng đang ở trạng thái cho phép tạo ticket, bạn có muốn tạo ticket cho hợp đồng này?"],
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: edit.translateJson["Có"],
                                        cancelButtonText: edit.translateJson["Không"]
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.open('/ticket/add?contract=' + res.contract_id, "_self");
                                            window.location.href = res.url;
                                        } else {
                                            window.location.href = res.url;
                                        }
                                    });
                                }
                                else {
                                    window.location.href = res.url;
                                }
                            } else {
                                window.location.href = res.url;
                            }
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
                swal(edit.translateJson['Thêm hợp đồng thất bại'], mess_error, "error");
            }
        });
    
    }
};

var edit = {
    translateJson : JSON.parse(localStorage.getItem('tranlate')),
    saveInfo: function (contractId) {
        
        var form = $('#form-info');

        var rules = [];
        var messages = [];

        var dataGeneral = {};
        var dataPartner = {};
        var dataPayment = {};

        //Quét input cụm thông tin chung
        $.each($('#group-general'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            case 'tab_general_contract_name':
                                rules[$(this).attr("name")] = {
                                    required: true,
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống'],
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataGeneral[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    }
                    else {
                        dataGeneral[$(this).attr("id")] = $(this).val();
                    }
                }
            });
        });

        //Quét input cụm đối tác
        $.each($('#group-partner'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            case 'tab_partner_address':
                                rules[$(this).attr("name")] = {
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            case 'tab_partner_email':
                                rules[$(this).attr("name")] = {
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataPartner[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataPartner[$(this).attr("id")] = $(this).val();
                    }
                }
            });
        });

        //Quét input cụm thanh toán
        $.each($('#group-payment'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataPayment[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataPayment[$(this).attr("id")] = $(this).val();
                    }

                }
            });
        });

        form.validate({
            rules: rules,
            messages: messages,
        });

        if (!form.valid()) {
            return false;
        }

        var is_renew = 0;
        if ($('#is_renew').is(':checked')) {
            is_renew = 1;
        }

        var is_created_ticket = 0;
        if ($('#is_created_ticket').is(':checked')) {
            is_created_ticket = 1;
        }

        var is_value_goods = 0;
        if ($('#is_value_goods').is(':checked')) {
            is_value_goods = 1;
        }

        $.ajax({
            url: laroute.route('contract.contract.update-info'),
            method: "POST",
            dataType: 'JSON',
            data: {
                dataGeneral: dataGeneral,
                dataPartner: dataPartner,
                dataPayment: dataPayment,
                status_code: $('#status_code').val(),
                is_renew: is_renew,
                number_day_renew: $('#number_day_renew').val(),
                is_created_ticket: is_created_ticket,
                status_code_created_ticket: $('#status_code_created_ticket').val(),
                contract_name: dataGeneral['contract_name'],
                contract_no: dataGeneral['contract_no'],
                contract_id: contractId,
                category_type: $('#category_type').val(),
                is_value_goods: is_value_goods
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            if (res.is_create_ticket == 1) {
                                if ($('#status_code').val() == $('#status_code_created_ticket').val() && is_created_ticket == 1) {
                                    swal({
                                        title: edit.translateJson["Thông báo"],
                                        text: edit.translateJson["Hợp đồng đang ở trạng thái cho phép tạo ticket, bạn có muốn tạo ticket cho hợp đồng này?"],
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: edit.translateJson["Có"],
                                        cancelButtonText: edit.translateJson["Không"]
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.open('/ticket/add?contract=' + res.contract_id, "_blank");
                                            window.location.reload();
                                        }
                                        else {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    window.location.reload();
                                }
                            }
                            else {
                                window.location.reload();
                            }
                        }
                        if (result.value == true) {
                            if (res.is_create_ticket == 1) {
                                if ($('#status_code').val() == $('#status_code_created_ticket').val() && is_created_ticket == 1) {
                                    swal({
                                        title: edit.translateJson["Thông báo"],
                                        text: edit.translateJson["Hợp đồng đang ở trạng thái cho phép tạo ticket, bạn có muốn tạo ticket cho hợp đồng này?"],
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: edit.translateJson["Có"],
                                        cancelButtonText: edit.translateJson["Không"]
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.open('/ticket/add?contract=' + res.contract_id, "_blank");
                                            window.location.reload();
                                        }
                                        else {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    window.location.reload();
                                }
                            }
                            else {
                                window.location.reload();
                            }
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
                swal(edit.translateJson['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
            }
        });
    
    }
};

var addQuickly = {
    showPopupAddQuicklyCustomer: function () {
       
        $.ajax({
            url: laroute.route('contract.contract.popup-add-customer-quickly'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#add-customer-quickly').modal('show');
                $('.select').select2();
                $('#pop_province_id').change(function () {
                    $.ajax({
                        url: laroute.route('admin.customer.load-district'),
                        dataType: 'JSON',
                        data: {
                            id_province: $('#pop_province_id').val(),
                        },
                        method: 'POST',
                        success: function (res) {
                            $('.district').empty();
                            $.map(res.optionDistrict, function (a) {
                                $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                            });
                        }

                    });
                });
            }
        });
    },
    createCustomerQuickly: function () {
        
        var form = $('#form-add-customer');
        form.validate({
            rules: {
                pop_customer_group_id: {
                    required: true
                },
                pop_full_name: {
                    required: true
                },
                pop_phone1: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 11
                },
                pop_address: {
                    required: true
                },
                pop_province_id: {
                    required: true
                },
                pop_district_id: {
                    required: true
                },
                pop_tax_code: {
                    minlength: 11,
                    maxlength: 13
                },
                pop_representative: {
                    maxlength: 191
                },
                pop_hotline: {
                    minlength: 10,
                    maxlength: 15
                },
            },
            messages: {
                pop_customer_group_id: {
                    required: edit.translateJson["Hãy chọn nhóm khách hàng"]
                },
                pop_full_name: {
                    required: edit.translateJson["Hãy nhập tên khách hàng"]
                },
                pop_phone1: {
                    required: edit.translateJson["Hãy nhập số điện thoại"],
                    number: edit.translateJson["Số điện thoại không hợp lệ"],
                    minlength: edit.translateJson["Tối thiểu 10 số"],
                    maxlength: edit.translateJson["Tối đa 11 số"]
                },
                pop_address: {
                    required: edit.translateJson["Hãy nhập địa chỉ"]
                },
                pop_province_id: {
                    required: edit.translateJson["Hãy chọn tỉnh/thành phố"]
                },
                pop_district_id: {
                    required: edit.translateJson["Hãy chọn quận/huyện"]
                },
                pop_tax_code: {
                    minlength: edit.translateJson["Mã số thuế tối thiểu 11 ký tự"],
                    maxlength: edit.translateJson["Mã số thuế tối đa 13 ký tự"]
                },
                pop_representative: {
                    maxlength: edit.translateJson["Người đại diện tối đa 191 ký tự"]
                },
                pop_hotline: {
                    minlength: edit.translateJson["Hotline tối thiểu 10 ký tự"],
                    maxlength: edit.translateJson["Hotline tối đa 15 ký tự"]
                },

            },
        });
        if (!form.valid()) {
            return false;
        }
        var gender = $('input[name="pop_gender"]:checked').val();
        var customer_group_id = $('#pop_customer_group_id').val();
        var full_name = $('#pop_full_name').val();
        var phone1 = $('#pop_phone1').val();
        var province_id = $('#pop_province_id').val();
        var district_id = $('#pop_district_id').val();
        var address = $('#pop_address').val();

        // update 08/11/2021 type customer personal or business
        var customer_type = $('#pop_customer_type').val();
        var tax_code = $('#pop_tax_code').val();
        var representative = $('#pop_representative').val();
        var hotline = $('#pop_hotline').val();
        if (customer_type == 'personal') {
            tax_code = '';
            representative = '';
            hotline = '';
        }
        $.ajax({
            url: laroute.route('contract.contract.submit-add-customer-quickly'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                full_name: full_name,
                gender: gender,
                phone1: phone1,
                province_id: province_id,
                district_id: district_id,
                address: address,
                customer_type: customer_type,
                tax_code: tax_code,
                representative: representative,
                hotline: hotline,
                customer_group_id: customer_group_id,
            },
            success: function (res) {
                if (res.error == 1) {
                    if (res.error_phone1 == 1) {
                        $('.error_phone1').text(edit.translateJson["Số điện thoại đã tồn tại"]);
                    } else {
                        $('.error_phone1').text('');
                    }
                }
                else {
                    $('#partner_object_type').val(customer_type);
                    $('#partner_object_type').trigger('change');
                    setTimeout(e => {
                        $('#partner_object_id').val(res.data.id);
                        $('#partner_object_id').trigger('change');
                    }, 2000);
                    $('#add-customer-quickly').modal('hide');
                }
            }
        });
    
    },
    createSupplierQuickly: function () {
        
        var form = $('#form-add-supplier');
        form.validate({
            rules: {
                pop_supplier_name: {
                    required: true,
                    maxlength: 191
                },
                pop_contact_name: {
                    required: true,
                    maxlength: 191
                },
                pop_contact_phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 15
                },
                pop_address: {
                    required: true
                },
            },
            messages: {
                pop_supplier_name: {
                    required: edit.translateJson["Hãy nhập tên nhà cung cấp"],
                    maxlength: edit.translateJson["Tên nhà cung cấp tối đa 191 ký tự"]
                },
                pop_contact_name: {
                    required: edit.translateJson["Hãy nhập tên người đại diện"],
                    maxlength: edit.translateJson["Người đại diện tối đa 191 ký tự"]
                },
                pop_contact_phone: {
                    required: edit.translateJson["Hãy nhập hotline"],
                    minlength: edit.translateJson["Hotline tối thiểu 10 ký tự"],
                    maxlength: edit.translateJson["Hotline tối đa 15 ký tự"]
                },
                pop_address: {
                    required: edit.translateJson["Hãy nhập địa chỉ"]
                },
            },
        });
        if (!form.valid()) {
            return false;
        }
        var supplier_name = $('#pop_supplier_name').val();
        var contact_name = $('#pop_contact_name').val();
        var contact_phone = $('#pop_contact_phone').val();
        var address = $('#pop_address').val();
        $.ajax({
            url: laroute.route('contract.contract.submit-add-supplier-quickly'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                supplier_name: supplier_name,
                contact_name: contact_name,
                contact_phone: contact_phone,
                address: address,
            },
            success: function (res) {
                if (res.error == 1) {
                    $('.error_name').text(res.message);
                }
                else {
                    $('.error_name').text('');
                    $('#partner_object_type').val('supplier');
                    $('#partner_object_type').trigger('change');
                    setTimeout(e => {
                        $('#partner_object_id').val(res.data.id);
                        $('#partner_object_id').trigger('change');
                    }, 2000);
                    $('#add-supplier-quickly').modal('hide');
                }
            }
        });
    
    }
};

var expectedRevenue = {
    showModalCreate: function (type) {
        
        $.ajax({
            url: laroute.route('contract.contract.modal-create-revenue'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
                type: type
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-create-revenue').modal('show');

                $('#contract_category_remind_id').select2({
                    placeholder: edit.translateJson['Hãy chọn nội dung nhắc nhở']
                });

                $('#send_type').select2({
                    placeholder: edit.translateJson['Hãy chọn thời gian dự kiến thu']
                });

                $('.input_int').ForceNumericOnly();

                new AutoNumeric.multiple('.input_float', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            }
        });
    
    },
    changeType: function (obj) {
        $('.div_send_type').empty();

        if ($(obj).val() == "after") {
            //Sau ngày ký HĐ
            var tpl = $('#tpl-after').html();
            $('.div_send_type').append(tpl);

            $('.input_int').ForceNumericOnly();
        } else if ($(obj).val() == "hard") {
            //Cố định
            var tpl = $('#tpl-hard').html();
            $('.div_send_type').append(tpl);

            $('.input_int').ForceNumericOnly();
        } else if ($(obj).val() == "custom") {
            //Tuỳ chọn ngày
            var tpl = $('#tpl-custom').html();
            $('.div_send_type').append(tpl);
        }
    },
    addDate: function () {
        var tpl = $('#tpl-add-date').html();
        $('.div_add_date').append(tpl);

        $('.date_picker').datepicker({
            language: 'vi',
            orientation: "bottom left",
            todayHighlight: !0
        });
    },
    removeDate: function (obj) {
        $(obj).closest('.input-group').remove();
    },
    uploadFile: function (input) {
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            var file_data = $('#upload_file_revenue').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_contract_revenue.');
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == 0) {
                        $('#contract_revenue_files').append(`
                            <div class="col-lg-12">
                                <a href="${res.file}" value="${res.file}" name="contract_revenue_files[]" class="ss--text-black" download="${file_data.name}">${file_data.name}</a>
                                <a href="javascript:void(0)" onclick="expectedRevenue.removeFile(this)"><i class="la la-trash"></i></a>
                                <br>
                            </div>
                        `);
                    }

                }
            });

        }
    
    },
    removeFile: function (obj) {
        $(obj).parent('div').remove();
    },
    create: function (type) {
        
        var form = $('#form-register');

        form.validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 190
                },
                contract_category_remind_id: {
                    required: true
                },
                send_type: {
                    required: true
                },
                amount: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: edit.translateJson['Tiêu đề không được trống'],
                    maxlength: edit.translateJson['Tiêu đề tối đa 190 kí tự']
                },
                contract_category_remind_id: {
                    required: edit.translateJson['Hãy chọn nội dung nhắc nhở']
                },
                send_type: {
                    required: edit.translateJson['Hãy chọn thời gian dự kiến thu']
                },
                amount: {
                    required: edit.translateJson['Hãy nhập giá trị thanh toán']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        var arrDateCustom = [];
        //Lấy ngày custom
        $.each($('.div_add_date').find("input"), function () {
            arrDateCustom.push($(this).val());
        });

        //Lấy file upload
        var contract_revenue_files = [];
        var contract_revenue_name_files = [];
        var nFile = $('[name="contract_revenue_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_revenue_files.push($('[name="contract_revenue_files[]"]')[i].href);
                contract_revenue_name_files.push($('[name="contract_revenue_files[]"]')[i].text);
            }
        }

        $.ajax({
            url: laroute.route('contract.contract.store-revenue'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                type: type,
                title: $('#title').val(),
                contract_category_remind_id: $('#contract_category_remind_id').val(),
                send_type: $('#send_type').val(),
                send_value: $('#send_value').val(),
                send_value_child: $('#send_value_child').val(),
                arrDateCustom: arrDateCustom,
                contract_revenue_files: contract_revenue_files,
                contract_revenue_name_files: contract_revenue_name_files,
                note: $('#note_revenue').val(),
                amount: $('#amount').val().replace(new RegExp('\\,', 'g'), '')
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-create-revenue').modal('hide');

                            if (res.type == 'receipt') {
                                $('#autotable-expected-receipt').PioTable('refresh');
                            } else {
                                $('#autotable-expected-spend').PioTable('refresh');
                            }
                        }
                        if (result.value == true) {
                            $('#modal-create-revenue').modal('hide');

                            if (res.type == 'receipt') {
                                $('#autotable-expected-receipt').PioTable('refresh');
                            } else {
                                $('#autotable-expected-spend').PioTable('refresh');
                            }
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
                swal(edit.translateJson['Thêm thất bại'], mess_error, "error");
            }
        });
    },
    showModalEdit: function (type, revenueId) {
        
        $.ajax({
            url: laroute.route('contract.contract.modal-edit-revenue'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
                type: type,
                contract_expected_revenue_id: revenueId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-edit-revenue').modal('show');

                $('#contract_category_remind_id').select2({
                    placeholder: edit.translateJson['Hãy chọn nội dung nhắc nhở']
                });

                $('#send_type').select2({
                    placeholder: edit.translateJson['Hãy chọn thời gian dự kiến thu']
                });

                $('.input_int').ForceNumericOnly();

                new AutoNumeric.multiple('.input_float', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            }
        });
    
    },
    edit: function (type, revenueId) {
        
        var form = $('#form-edit');

        form.validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 190
                },
                contract_category_remind_id: {
                    required: true
                },
                send_type: {
                    required: true
                },
                amount: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: edit.translateJson['Tiêu đề không được trống'],
                    maxlength: edit.translateJson['Tiêu đề tối đa 190 kí tự']
                },
                contract_category_remind_id: {
                    required: edit.translateJson['Hãy chọn nội dung nhắc nhở']
                },
                send_type: {
                    required: edit.translateJson['Hãy chọn thời gian dự kiến thu']
                },
                amount: {
                    required: edit.translateJson['Hãy nhập giá trị thanh toán']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        var arrDateCustom = [];
        //Lấy ngày custom
        $.each($('.div_add_date').find("input"), function () {
            arrDateCustom.push($(this).val());
        });

        //Lấy file upload
        var contract_revenue_files = [];
        var contract_revenue_name_files = [];
        var nFile = $('[name="contract_revenue_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_revenue_files.push($('[name="contract_revenue_files[]"]')[i].href);
                contract_revenue_name_files.push($('[name="contract_revenue_files[]"]')[i].text);
            }
        }

        $.ajax({
            url: laroute.route('contract.contract.update-revenue'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_expected_revenue_id: revenueId,
                contract_id: $('#contract_id_hidden').val(),
                type: type,
                title: $('#title').val(),
                contract_category_remind_id: $('#contract_category_remind_id').val(),
                send_type: $('#send_type').val(),
                send_value: $('#send_value').val(),
                send_value_child: $('#send_value_child').val(),
                arrDateCustom: arrDateCustom,
                contract_revenue_files: contract_revenue_files,
                contract_revenue_name_files: contract_revenue_name_files,
                note: $('#note_revenue').val(),
                amount: $('#amount').val().replace(new RegExp('\\,', 'g'), '')
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-edit-revenue').modal('hide');

                            if (res.type == 'receipt') {
                                $('#autotable-expected-receipt').PioTable('refresh');
                            } else {
                                $('#autotable-expected-spend').PioTable('refresh');
                            }
                        }
                        if (result.value == true) {
                            $('#modal-edit-revenue').modal('hide');

                            if (res.type == 'receipt') {
                                $('#autotable-expected-receipt').PioTable('refresh');
                            } else {
                                $('#autotable-expected-spend').PioTable('refresh');
                            }
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
                swal(edit.translateJson['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });

    
    },
    remove: function (type, revenueId) {
        
        swal({
            title: edit.translateJson['Thông báo'],
            text: edit.translateJson["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: edit.translateJson['Xóa'],
            cancelButtonText: edit.translateJson['Hủy'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('contract.contract.destroy-revenue'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        contract_expected_revenue_id: revenueId,
                        type: type,
                        contract_id: $('#contract_id_hidden').val(),
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");

                            if (res.type == 'receipt') {
                                $('#autotable-expected-receipt').PioTable('refresh');
                            } else {
                                $('#autotable-expected-spend').PioTable('refresh');
                            }
                        } else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                });
            }
        });
    }
};

var contractReceipt = {
    showModalCreate: function () {
        
        $.ajax({
            url: laroute.route('contract.contract.modal-create-receipt'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
                contract_id: $('#contract_id_hidden').val(),
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-create-receipt').modal('show');

                $('.date_picker').datepicker({
                    language: 'vi',
                    orientation: "bottom left",
                    todayHighlight: !0
                });

                $('#collection_by').select2({
                    placeholder: edit.translateJson['Chọn người thu']
                });

                $('#payment_method_receipt_id').select2({
                    placeholder: edit.translateJson['Chọn phương thức thanh toán']
                }).on('select2:select', function (event) {
                    // Lấy id và tên của phương thức thanh toán
                    let methodId = event.params.data.id;
                    let methodName = event.params.data.text;
                    let tpl = $('#payment_method_tpl').html();
                    tpl = tpl.replace(/{label}/g, methodName);
                    tpl = tpl.replace(/{id}/g, methodId);
                    tpl = tpl.replace(/{id}/g, methodId);

                    $('.div_append_payment_method').append(tpl);

                    new AutoNumeric.multiple('#payment_method_' + methodId, {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                }).on('select2:unselect', function (event) {
                    let methodId = event.params.data.id;
                    $('.div_payment_method_' + methodId).remove();
                });

                new AutoNumeric.multiple('.input_float', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            }
        });
    },
    create: function () {
        
        var form = $('#form-register');

        form.validate({
            rules: {
                content_receipt: {
                    required: true,
                },
                collection_date: {
                    required: true
                },
                collection_by: {
                    required: true
                },
                amount_remain: {
                    required: true
                },
                payment_method_receipt_id: {
                    required: true
                },
                invoice_no: {
                    maxlength: 190
                }
            },
            messages: {
                content_receipt: {
                    required: edit.translateJson['Nội dung không được trống'],
                },
                collection_date: {
                    required: edit.translateJson['Hãy chọn ngày thu'],
                },
                collection_by: {
                    required: edit.translateJson['Hãy chọn người thu'],
                },
                amount_remain: {
                    required: edit.translateJson['Giá trị thanh toán không được trống']
                },
                payment_method_receipt_id: {
                    required: edit.translateJson['Hãy chọn phương thức thanh toán']
                },
                invoice_no: {
                    maxlength: edit.translateJson['Số hoá đơn tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        //Lấy file upload
        var contract_revenue_files = [];
        var contract_revenue_name_files = [];
        var nFile = $('[name="contract_revenue_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_revenue_files.push($('[name="contract_revenue_files[]"]')[i].href);
                contract_revenue_name_files.push($('[name="contract_revenue_files[]"]')[i].text);
            }
        }

        let arrayMethod = {};
        $.each($('.div_append_payment_method').find('.method'), function () {
            let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
            let getId = $(this).find("input[name='payment_method']").attr('id');
            let methodCode = getId.slice(15);
            arrayMethod[methodCode] = moneyEachMethod;
        });


        $.ajax({
            url: laroute.route('contract.contract.store-receipt'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                content: $('#content_receipt').val(),
                collection_date: $('#collection_date').val(),
                collection_by: $('#collection_by').val(),
                prepayment: $('#prepayment').val().replace(new RegExp('\\,', 'g'), ''),
                amount_remain: $('#amount_remain').val().replace(new RegExp('\\,', 'g'), ''),
                invoice_date: $('#invoice_date').val(),
                invoice_no: $('#invoice_no').val(),
                payment_method_id: $('#payment_method_receipt_id').val(),
                note: $('#note_receipt').val(),
                contract_receipt_files: contract_revenue_files,
                contract_receipt_name_files: contract_revenue_name_files,
                arrayMethod: arrayMethod
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        contractGoods.list();
                        view.getStatusOrder();

                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-create-receipt').modal('hide');

                            $('#autotable-receipt').PioTable('refresh');
                            view.getStatusOrder();
                        }
                        if (result.value == true) {
                            $('#modal-create-receipt').modal('hide');

                            $('#autotable-receipt').PioTable('refresh');
                            view.getStatusOrder();
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
                swal(edit.translateJson['Thêm thất bại'], mess_error, "error");
            }
        });
    },
    showModalEdit: function (contractReceiptId) {
        
        $.ajax({
            url: laroute.route('contract.contract.modal-edit-receipt'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
                contract_id: $('#contract_id_hidden').val(),
                contract_receipt_id: contractReceiptId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-edit-receipt').modal('show');

                $('.date_picker').datepicker({
                    language: 'vi',
                    orientation: "bottom left",
                    todayHighlight: !0
                });

                $('#collection_by').select2({
                    placeholder: edit.translateJson['Chọn người thu']
                });

                $('#payment_method_receipt_id').select2({
                    placeholder: edit.translateJson['Chọn phương thức thanh toán']
                });

                new AutoNumeric.multiple('.input_float', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            }
        });
    },
    edit: function (contractReceiptId) {
       
        var form = $('#form-edit');

        form.validate({
            rules: {
                invoice_no: {
                    maxlength: 190
                }
            },
            messages: {
                invoice_no: {
                    maxlength: edit.translateJson['Số hoá đơn tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        //Lấy file upload
        var contract_revenue_files = [];
        var contract_revenue_name_files = [];
        var nFile = $('[name="contract_revenue_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_revenue_files.push($('[name="contract_revenue_files[]"]')[i].href);
                contract_revenue_name_files.push($('[name="contract_revenue_files[]"]')[i].text);
            }
        }

        $.ajax({
            url: laroute.route('contract.contract.update-receipt'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_receipt_id: contractReceiptId,
                contract_id: $('#contract_id_hidden').val(),
                invoice_date: $('#invoice_date').val(),
                invoice_no: $('#invoice_no').val(),
                note: $('#note_receipt').val(),
                contract_receipt_files: contract_revenue_files,
                contract_receipt_name_files: contract_revenue_name_files,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-edit-receipt').modal('hide');

                            $('#autotable-receipt').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-edit-receipt').modal('hide');

                            $('#autotable-receipt').PioTable('refresh');
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
                swal(edit.translateJson['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    
    },
    showModalRemove: function (contractReceiptId) {
        $.ajax({
            url: laroute.route('contract.contract.modal-remove-receipt'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_receipt_id: contractReceiptId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-remove-receipt').modal('show');
            }
        });
    },
    remove: function (contractReceiptId) {
        
        var form = $('#form-remove');

        form.validate({
            rules: {
                reason: {
                    required: true,
                    maxlength: 190
                }
            },
            messages: {
                reason: {
                    required: edit.translateJson['Lý do xoá không được trống'],
                    maxlength: edit.translateJson['Lý do xoá tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('contract.contract.destroy-receipt'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                contract_receipt_id: contractReceiptId,
                reason: $('#reason').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-remove-receipt').modal('hide');

                            $('#autotable-receipt').PioTable('refresh');
                            view.getStatusOrder();
                        }
                        if (result.value == true) {
                            $('#modal-remove-receipt').modal('hide');

                            $('#autotable-receipt').PioTable('refresh');
                            view.getStatusOrder();
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    
    }
};

var contractSpend = {
    showModalCreate: function () {
        
        $.ajax({
            url: laroute.route('contract.contract.modal-create-spend'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
                contract_id: $('#contract_id_hidden').val(),
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-create-spend').modal('show');

                $('.date_picker').datepicker({
                    language: 'vi',
                    orientation: "bottom left",
                    todayHighlight: !0
                });

                $('#spend_by').select2({
                    placeholder: edit.translateJson['Chọn người chi']
                });

                $('#payment_method_spend_id').select2({
                    placeholder: edit.translateJson['Chọn phương thức thanh toán']
                });

                new AutoNumeric.multiple('.input_float', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            }
        });
    
    },
    create: function () {
        
        var form = $('#form-register');

        form.validate({
            rules: {
                content_spend: {
                    required: true,
                },
                spend_date: {
                    required: true
                },
                spend_by: {
                    required: true
                },
                amount_spend: {
                    required: true
                },
                payment_method_spend_id: {
                    required: true
                },
                invoice_no: {
                    maxlength: 190
                }
            },
            messages: {
                content_spend: {
                    required: edit.translateJson['Nội dung không được trống'],
                },
                spend_date: {
                    required: edit.translateJson['Hãy chọn ngày chi'],
                },
                spend_by: {
                    required: edit.translateJson['Hãy chọn người chi'],
                },
                amount_spend: {
                    required: edit.translateJson['Giá trị thanh toán không được trống']
                },
                payment_method_receipt_id: {
                    required: edit.translateJson['Hãy chọn phương thức thanh toán']
                },
                invoice_no: {
                    maxlength: edit.translateJson['Số hoá đơn tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        //Lấy file upload
        var contract_revenue_files = [];
        var contract_revenue_name_files = [];
        var nFile = $('[name="contract_revenue_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_revenue_files.push($('[name="contract_revenue_files[]"]')[i].href);
                contract_revenue_name_files.push($('[name="contract_revenue_files[]"]')[i].text);
            }
        }

        $.ajax({
            url: laroute.route('contract.contract.store-spend'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                content: $('#content_spend').val(),
                spend_date: $('#spend_date').val(),
                spend_by: $('#spend_by').val(),
                prepayment: $('#prepayment').val().replace(new RegExp('\\,', 'g'), ''),
                amount_remain: $('#amount_remain').val().replace(new RegExp('\\,', 'g'), ''),
                amount_spend: $('#amount_spend').val().replace(new RegExp('\\,', 'g'), ''),
                invoice_date: $('#invoice_date').val(),
                invoice_no: $('#invoice_no').val(),
                payment_method_id: $('#payment_method_spend_id').val(),
                note: $('#note_spend').val(),
                contract_spend_files: contract_revenue_files,
                contract_spend_name_files: contract_revenue_name_files,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-create-spend').modal('hide');

                            $('#autotable-spend').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-create-spend').modal('hide');

                            $('#autotable-spend').PioTable('refresh');
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
                swal(edit.translateJson['Thêm thất bại'], mess_error, "error");
            }
        });
    
    },
    showModalEdit: function (spendId) {
        
        $.ajax({
            url: laroute.route('contract.contract.modal-edit-spend'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
                contract_id: $('#contract_id_hidden').val(),
                contract_spend_id: spendId,
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-edit-spend').modal('show');

                $('.date_picker').datepicker({
                    language: 'vi',
                    orientation: "bottom left",
                    todayHighlight: !0
                });

                $('#spend_by').select2({
                    placeholder: edit.translateJson['Chọn người chi']
                });

                $('#payment_method_spend_id').select2({
                    placeholder: edit.translateJson['Chọn phương thức thanh toán']
                });

                new AutoNumeric.multiple('.input_float', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            }
        });
    
    },
    edit: function (spendId) {
        
        var form = $('#form-edit');

        form.validate({
            rules: {
                content_spend: {
                    required: true,
                },
                spend_date: {
                    required: true
                },
                spend_by: {
                    required: true
                },
                amount_spend: {
                    required: true
                },
                payment_method_spend_id: {
                    required: true
                },
                invoice_no: {
                    maxlength: 190
                }
            },
            messages: {
                content_spend: {
                    required: edit.translateJson['Nội dung không được trống'],
                },
                spend_date: {
                    required: edit.translateJson['Hãy chọn ngày chi'],
                },
                spend_by: {
                    required: edit.translateJson['Hãy chọn người chi'],
                },
                amount_spend: {
                    required: edit.translateJson['Giá trị thanh toán không được trống']
                },
                payment_method_receipt_id: {
                    required: edit.translateJson['Hãy chọn phương thức thanh toán']
                },
                invoice_no: {
                    maxlength: edit.translateJson['Số hoá đơn tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        //Lấy file upload
        var contract_revenue_files = [];
        var contract_revenue_name_files = [];
        var nFile = $('[name="contract_revenue_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_revenue_files.push($('[name="contract_revenue_files[]"]')[i].href);
                contract_revenue_name_files.push($('[name="contract_revenue_files[]"]')[i].text);
            }
        }

        $.ajax({
            url: laroute.route('contract.contract.update-spend'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_spend_id: spendId,
                contract_id: $('#contract_id_hidden').val(),
                content: $('#content_spend').val(),
                spend_date: $('#spend_date').val(),
                spend_by: $('#spend_by').val(),
                prepayment: $('#prepayment').val().replace(new RegExp('\\,', 'g'), ''),
                amount_remain: $('#amount_remain').val().replace(new RegExp('\\,', 'g'), ''),
                amount_spend: $('#amount_spend').val().replace(new RegExp('\\,', 'g'), ''),
                invoice_date: $('#invoice_date').val(),
                invoice_no: $('#invoice_no').val(),
                payment_method_id: $('#payment_method_spend_id').val(),
                note: $('#note_spend').val(),
                contract_spend_files: contract_revenue_files,
                contract_spend_name_files: contract_revenue_name_files,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-edit-spend').modal('hide');

                            $('#autotable-spend').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-edit-spend').modal('hide');

                            $('#autotable-spend').PioTable('refresh');
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
                swal(edit.translateJson['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    
    },
    showModalRemove: function (spendId) {
        $.ajax({
            url: laroute.route('contract.contract.modal-remove-spend'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_spend_id: spendId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-remove-spend').modal('show');
            }
        });
    },
    remove: function (spendId) {
        
        var form = $('#form-remove');

        form.validate({
            rules: {
                reason: {
                    required: true,
                    maxlength: 190
                }
            },
            messages: {
                reason: {
                    required: edit.translateJson['Lý do xoá không được trống'],
                    maxlength: edit.translateJson['Lý do xoá tối đa 190 kí tự']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('contract.contract.destroy-spend'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                contract_spend_id: spendId,
                reason: $('#reason').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-remove-spend').modal('hide');

                            $('#autotable-spend').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-remove-spend').modal('hide');

                            $('#autotable-spend').PioTable('refresh');
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    
    }
};

var contractFile = {
    showModalCreate: function () {
        
        $.ajax({
            url: laroute.route('contract.contract.modal-create-file'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
                contract_id: $('#contract_id_hidden').val(),
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-create-file').modal('show');
            }
        });
   
    },
    create: function () {
        
        var form = $('#form-register');

        form.validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 190
                }
            },
            messages: {
                name: {
                    required: edit.translateJson['Tên hồ sơ không được trống'],
                    maxlength: edit.translateJson['Tên hồ sơ tối đa 190 kí tự']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        //Lấy file upload
        var contract_revenue_files = [];
        var contract_revenue_name_files = [];
        var nFile = $('[name="contract_revenue_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_revenue_files.push($('[name="contract_revenue_files[]"]')[i].href);
                contract_revenue_name_files.push($('[name="contract_revenue_files[]"]')[i].text);
            }
        }

        $.ajax({
            url: laroute.route('contract.contract.store-file'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                name: $('#name').val(),
                note: $('#note_file').val(),
                contract_files: contract_revenue_files,
                contract_name_files: contract_revenue_name_files,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-create-file').modal('hide');

                            $('#autotable-file').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-create-file').modal('hide');

                            $('#autotable-file').PioTable('refresh');
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
                swal(edit.translateJson['Thêm thất bại'], mess_error, "error");
            }
        });
    
    },
    showModalEdit: function (fileId) {
        
        $.ajax({
            url: laroute.route('contract.contract.modal-edit-file'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_category_id: $('#contract_category_id').val(),
                contract_id: $('#contract_id_hidden').val(),
                contract_file_id: fileId,
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                //Show modal chọn loại HĐ
                $('#modal-edit-file').modal('show');
            }
        });
    
    },
    edit: function (fileId) {
        
        var form = $('#form-edit');

        form.validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 190
                }
            },
            messages: {
                name: {
                    required: edit.translateJson['Tên hồ sơ không được trống'],
                    maxlength: edit.translateJson['Tên hồ sơ tối đa 190 kí tự']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        //Lấy file upload
        var contract_revenue_files = [];
        var contract_revenue_name_files = [];
        var nFile = $('[name="contract_revenue_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_revenue_files.push($('[name="contract_revenue_files[]"]')[i].href);
                contract_revenue_name_files.push($('[name="contract_revenue_files[]"]')[i].text);
            }
        }

        $.ajax({
            url: laroute.route('contract.contract.update-file'),
            method: "POST",
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                contract_file_id: fileId,
                name: $('#name').val(),
                note: $('#note_file').val(),
                contract_files: contract_revenue_files,
                contract_name_files: contract_revenue_name_files,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-edit-file').modal('hide');

                            $('#autotable-file').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-edit-file').modal('hide');

                            $('#autotable-file').PioTable('refresh');
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
                swal(edit.translateJson['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    
    },
    remove: function (obj, fileId) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');

        
        swal({
            title: edit.translateJson['Thông báo'],
            text: edit.translateJson["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: edit.translateJson['Xóa'],
            cancelButtonText: edit.translateJson['Hủy'],
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("contract.contract.destroy-file"),
                    method: "POST",
                    data: {
                        contract_id: $('#contract_id_hidden').val(),
                        contract_file_id: fileId
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                            $('#autotable-file').PioTable('refresh');
                        } else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                })
            }
        });
    
    }
};

var contractGoods = {
    list: function () {
        
        $.ajax({
            url: laroute.route('contract.contract.list-goods'),
            method: "POST",
            dataType: "JSON",
            data: {
                contract_id: $('#contract_id_hidden').val(),
            },
            success: function (res) {
                $('#list-goods').html(res.html);
                number = res.countGoods;

                $('.object_type').select2({
                    placeholder: edit.translateJson['Chọn loại hàng hoá']
                });

                $('.unit_id').select2({
                    placeholder: edit.translateJson['Chọn đơn vị tính']
                });

                $('.input_int').ForceNumericOnly();
            }
        });
    
    },
    listGoodOfAnnex: function () {
        
        $.ajax({
            url: laroute.route('contract.contract.list-goods-contract-annex'),
            method: "POST",
            dataType: "JSON",
            data: {
                contract_id: $('#contract_id_hidden').val(),
                dataAnnexLocal: $('#dataAnnexLocal').val(),
            },
            success: function (res) {
                $('#list-goods').html(res.html);
                number = res.countGoods;

                $('.object_type').select2({
                    placeholder: edit.translateJson['Chọn loại hàng hoá']
                });

                $('.unit_id').select2({
                    placeholder: edit.translateJson['Chọn đơn vị tính']
                });

                $('.input_int').ForceNumericOnly();
            }
        });
   
    },
    addGoods: function () {
        
        var check = true;

        //validate object
        $.each($('#table-goods > tbody').find('.tr-goods'), function () {
            var objectType = $(this).find($('.object_type')).val();
            var objectId = $(this).find($('.object_id')).val();
            var quantity = $(this).find($('.quantity')).val();
            var tax = $(this).find($('.tax')).val();
            var number = $(this).find($('.number')).val();

            if (objectType == "") {
                $(this).find($(".error_object_type_" + number + "")).text(edit.translateJson['Hãy chọn loại hàng hoá']);
                check = false;
            } else {
                $(this).find($(".error_object_type_" + number + "")).text('');
            }

            if (objectId == "") {
                $(this).find($(".error_object_id_" + number + "")).text(edit.translateJson['Hãy chọn hàng hoá']);
                check = false;
            } else {
                $(this).find($(".error_object_id_" + number + "")).text('');
            }

            if (quantity == "" || quantity == 0 || isInt(quantity) == false) {
                $(this).find($(".error_quantity_" + number + "")).text(edit.translateJson['Số lượng không hợp lệ']);
                check = false;
            } else {
                $(this).find($(".error_quantity_" + number + "")).text('');
            }

            if (isInt(tax) == false) {
                $(this).find($(".error_tax_" + number + "")).text(edit.translateJson['VAT không hợp lệ']);
                check = false;
            } else {
                $(this).find($(".error_tax_" + number + "")).text('');
            }
        });

        if (check == true) {
            number++;

            var tpl = $('#tpl-goods').html();
            tpl = tpl.replace(/{number}/g, number);
            $('#table-goods > tbody').append(tpl);

            $('.object_type').select2({
                placeholder: edit.translateJson['Chọn loại hàng hoá']
            });

            $('.object_id').select2({
                placeholder: edit.translateJson['Chọn hàng hoá']
            });

            $('.unit_id').select2({
                placeholder: edit.translateJson['Chọn đơn vị tính']
            });

            new AutoNumeric.multiple('#discount_' + number + '', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: decimal_number,
                minimumValue: 0
            });

            $('.input_int').ForceNumericOnly();

            if ($('#contract_category_type').val() == 'sell') {
                $('#discount_' + number + '').closest('.tr-goods').find($('.tax')).prop('disabled', true);
            }
        }
    
    },
    changeObjectType: function (obj) {
        $(obj).closest('.tr-goods-same').find('.object_id').prop('disabled', false);
        $(obj).closest('.tr-goods-same').find('.object_id').val('').trigger('change');

        
        $(obj).closest('.tr-goods-same').find('.object_id').select2({
            width: '100%',
            placeholder: edit.translateJson["Chọn hàng hoá"],
            ajax: {
                url: laroute.route('promotion.list-option'),
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
                                    id: item.product_child_id,
                                    text: item.product_child_name,
                                    code: item.product_code
                                };
                            } else if ($(obj).val() == 'service') {
                                return {
                                    id: item.service_id,
                                    text: item.service_name,
                                    code: item.service_code
                                };
                            } else if ($(obj).val() == 'service_card') {
                                return {
                                    id: item.service_card_id,
                                    text: item.card_name,
                                    code: item.code
                                };
                            } else if ($(obj).val() == 'product_gift') {
                                return {
                                    id: item.product_child_id,
                                    text: item.product_child_name,
                                    code: item.product_code
                                };
                            } else if ($(obj).val() == 'service_gift') {
                                return {
                                    id: item.service_id,
                                    text: item.service_name,
                                    code: item.service_code
                                };
                            } else if ($(obj).val() == 'service_card_gift') {
                                return {
                                    id: item.service_card_id,
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
    
    },
    changeObject: function (obj, isEdit = 0) {
        $.ajax({
            url: laroute.route('contract.contract.change-object'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                contract_category_type: $('#contract_category_type').val(),
                object_type: $(obj).closest('.tr-goods-same').find('.object_type').val(),
                object_id: $(obj).val()
            },
            success: function (res) {
                if (isEdit == 0) {
                    $(obj).closest('.tr-goods').find('.object_code').val(res.objectCode);
                    $(obj).closest('.tr-goods').find('.object_name').val(res.objectName);
                    $(obj).closest('.tr-goods').find('.price').val(formatNumber(Number(res.price).toFixed(decimal_number)));
                    $(obj).closest('.tr-goods').find('.unit_id').val(res.unitId).trigger('change');
                    $(obj).closest('.tr-goods').find('.quantity').val(1);
                    $(obj).closest('.tr-goods').find('.tax').val(0);
                    $(obj).closest('.tr-goods').find('.amount').val(formatNumber(Number(res.price).toFixed(decimal_number)));
                    if (res.isAppliedKpi == 0) {
                        $(obj).closest('.tr-goods').find('.is_applied_kpi').prop('checked', false);
                    } else {
                        $(obj).closest('.tr-goods').find('.is_applied_kpi').prop('checked', true);
                    }

                    var number = $(obj).closest('.tr-goods').find('.number').val();

                    $('.td_discount_' + number + '').empty();
                    $('.td_discount_' + number + '').append('<input class="form-control discount input_float" id="discount_' + number + '" value="0" onchange="contractGoods.changePrice(this)">');

                    new AutoNumeric.multiple('#discount_' + number + '', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        minimumValue: 0
                    });

                    //Lấy quà tặng khi thay đổi hàng hoá
                    
                        //Lấy total quantity sp, dv, thẻ dv
                        var arrParam = [];

                        if ($('#contract_category_type').val() == 'sell') {
                            $.each($('#table-goods > tbody').find('.tr-goods'), function () {
                                var objectType = $(this).find($('.object_type')).val();
                                var objectCode = $(this).find($('.object_code')).val();
                                var quantity = $(this).find($('.quantity')).val();
                                //Sản phẩm bán hay quà tặng
                                var typeObject = $(this).find($('.type_object')).val();

                                arrParam.push({
                                    objectType: objectType,
                                    objectCode: objectCode,
                                    quantity: quantity
                                });

                                if (typeObject == 'gift') {
                                    $(this).remove();
                                }
                            });

                            //Check promotion gift
                            $.ajax({
                                url: laroute.route('admin.order.check-gift'),
                                method: 'POST',
                                dataType: 'JSON',
                                data: {
                                    customer_id: $('#partner_object_id').val(),
                                    arrParam: arrParam
                                },
                                async: false,
                                success: function (res) {
                                    if (res.gift > 0) {
                                        $.map(res.arr_gift, function (a) {
                                            number++;

                                            var tpl = $('#tpl-goods-order').html();
                                            tpl = tpl.replace(/{number}/g, number);
                                            $('#table-goods > tbody').append(tpl);

                                            $('.object_type').select2({
                                                placeholder: edit.translateJson['Chọn loại hàng hoá']
                                            });

                                            $('.unit_id').select2({
                                                placeholder: edit.translateJson['Chọn đơn vị tính']
                                            });

                                            $('#discount_' + number + '').closest('.tr-goods').find($('.object_type')).val(a.gift_object_type + '_gift').trigger('change');
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.object_code')).val(a.gift_object_code);
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.object_name')).val(a.gift_object_name + ' (' + edit.translateJson['quà tặng'] + ')');
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.object_id')).append('<option value="' + a.gift_object_id + '" selected>' + a.gift_object_name + '</option>');
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.price')).val(formatNumber(Number(0).toFixed(decimal_number)));
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.quantity')).val(a.quantity_gift);
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.discount')).val(formatNumber(Number(0).toFixed(decimal_number)));
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.amount')).val(formatNumber(Number(0).toFixed(decimal_number)));
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.order_code')).val($('.order_code_append').val());
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.type_object')).val('gift');

                                            $('#discount_' + number + '').closest('.tr-goods').find($('.object_type')).prop('disabled', true);
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.object_id')).prop('disabled', true);
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.quantity')).prop('disabled', true);
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.tax')).prop('disabled', true);
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.discount')).prop('disabled', true);
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.td_remove')).empty();
                                            $('#discount_' + number + '').closest('.tr-goods').find($('.type_object')).val('gift');


                                            new AutoNumeric.multiple('#discount_' + number + '', {
                                                currencySymbol: '',
                                                decimalCharacter: '.',
                                                digitGroupSeparator: ',',
                                                decimalPlaces: decimal_number,
                                                minimumValue: 0
                                            });

                                            $('.input_int').ForceNumericOnly();
                                        });
                                    }
                                }
                            });
                        }
                    
                }
            }
        });
    },
    changePrice: function (obj) {
        var price = $(obj).closest('.tr-goods').find('.price').val().replace(new RegExp('\\,', 'g'), '');
        var quantity = $(obj).closest('.tr-goods').find('.quantity').val();
        var tax = $(obj).closest('.tr-goods').find('.tax').val();
        var discount = $(obj).closest('.tr-goods').find('.discount').val().replace(new RegExp('\\,', 'g'), '');

        //Tính giá tổng tiền
        var amount = (price * quantity) - discount + ((tax * price) / 100 * quantity);
        $(obj).closest('.tr-goods').find('.amount').val(formatNumber(Number(amount).toFixed(decimal_number)));
    },
    changeQuantity: function (obj) {
        contractGoods.changePrice(obj);

        //Lấy quà tặng khi thay đổi hàng hoá
        
            //Lấy total quantity sp, dv, thẻ dv
            var arrParam = [];

            if ($('#contract_category_type').val() == 'sell') {
                $.each($('#table-goods > tbody').find('.tr-goods'), function () {
                    var objectType = $(this).find($('.object_type')).val();
                    var objectCode = $(this).find($('.object_code')).val();
                    var quantity = $(this).find($('.quantity')).val();
                    //Sản phẩm bán hay quà tặng
                    var typeObject = $(this).find($('.type_object')).val();

                    arrParam.push({
                        objectType: objectType,
                        objectCode: objectCode,
                        quantity: quantity
                    });

                    if (typeObject == 'gift') {
                        $(this).remove();
                    }
                });

                //Check promotion gift
                $.ajax({
                    url: laroute.route('admin.order.check-gift'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_id: $('#partner_object_id').val(),
                        arrParam: arrParam
                    },
                    async: false,
                    success: function (res) {
                        if (res.gift > 0) {
                            $.map(res.arr_gift, function (a) {
                                number++;

                                var tpl = $('#tpl-goods-order').html();
                                tpl = tpl.replace(/{number}/g, number);
                                $('#table-goods > tbody').append(tpl);

                                $('.object_type').select2({
                                    placeholder: edit.translateJson['Chọn loại hàng hoá']
                                });

                                $('.unit_id').select2({
                                    placeholder: edit.translateJson['Chọn đơn vị tính']
                                });

                                $('#discount_' + number + '').closest('.tr-goods').find($('.object_type')).val(a.gift_object_type + '_gift').trigger('change');
                                $('#discount_' + number + '').closest('.tr-goods').find($('.object_code')).val(a.gift_object_code);
                                $('#discount_' + number + '').closest('.tr-goods').find($('.object_name')).val(a.gift_object_name + ' (' + edit.translateJson['quà tặng'] + ')');
                                $('#discount_' + number + '').closest('.tr-goods').find($('.object_id')).append('<option value="' + a.gift_object_id + '" selected>' + a.gift_object_name + '</option>');
                                $('#discount_' + number + '').closest('.tr-goods').find($('.price')).val(formatNumber(Number(0).toFixed(decimal_number)));
                                $('#discount_' + number + '').closest('.tr-goods').find($('.quantity')).val(a.quantity_gift);
                                $('#discount_' + number + '').closest('.tr-goods').find($('.discount')).val(formatNumber(Number(0).toFixed(decimal_number)));
                                $('#discount_' + number + '').closest('.tr-goods').find($('.amount')).val(formatNumber(Number(0).toFixed(decimal_number)));
                                $('#discount_' + number + '').closest('.tr-goods').find($('.order_code')).val($('.order_code_append').val());
                                $('#discount_' + number + '').closest('.tr-goods').find($('.type_object')).val('gift');

                                $('#discount_' + number + '').closest('.tr-goods').find($('.object_type')).prop('disabled', true);
                                $('#discount_' + number + '').closest('.tr-goods').find($('.object_id')).prop('disabled', true);
                                $('#discount_' + number + '').closest('.tr-goods').find($('.quantity')).prop('disabled', true);
                                $('#discount_' + number + '').closest('.tr-goods').find($('.tax')).prop('disabled', true);
                                $('#discount_' + number + '').closest('.tr-goods').find($('.discount')).prop('disabled', true);
                                $('#discount_' + number + '').closest('.tr-goods').find($('.td_remove')).empty();
                                $('#discount_' + number + '').closest('.tr-goods').find($('.type_object')).val('gift');


                                new AutoNumeric.multiple('#discount_' + number + '', {
                                    currencySymbol: '',
                                    decimalCharacter: '.',
                                    digitGroupSeparator: ',',
                                    decimalPlaces: decimal_number,
                                    minimumValue: 0
                                });

                                $('.input_int').ForceNumericOnly();
                            });
                        }
                    }
                });
            }
       
    },
    removeObject: function (obj) {
        $(obj).closest('.tr-goods').remove();

        contractGoods.changeQuantity(obj);
    },
    save: function () {
        
        var check = true;

        var arrData = [];

        //validate object
        $.each($('#table-goods > tbody').find('.tr-goods'), function () {
            var objectType = $(this).find($('.object_type')).val();
            var objectName = $(this).find($('.object_name')).val();
            var objectCode = $(this).find($('.object_code')).val();
            var objectId = $(this).find($('.object_id')).val();
            var unitId = $(this).find($('.object_id')).val();
            var quantity = $(this).find($('.quantity')).val();
            var price = $(this).find($('.price')).val();
            var tax = $(this).find($('.tax')).val();
            var discount = $(this).find($('.discount')).val();
            var amount = $(this).find($('.amount')).val();
            var orderCode = $(this).find($('.order_code')).val();
            var note = $(this).find($('.note')).val();
            var staffId = $(this).find($('.staff_id')).val();
            var number = $(this).find($('.number')).val();
            var isAppliedKpi = $(this).find($('.is_applied_kpi')).is(':checked') ? 1 : 0;

            if (objectType == "") {
                $(this).find($(".error_object_type_" + number + "")).text(edit.translateJson['Hãy chọn loại hàng hoá']);
                check = false;
            } else {
                $(this).find($(".error_object_type_" + number + "")).text('');
            }

            if (objectId == "") {
                $(this).find($(".error_object_id_" + number + "")).text(edit.translateJson['Hãy chọn hàng hoá']);
                check = false;
            } else {
                $(this).find($(".error_object_id_" + number + "")).text('');
            }

            if (quantity == "" || quantity == 0 || isInt(quantity) == false) {
                $(this).find($(".error_quantity_" + number + "")).text(edit.translateJson['Số lượng không hợp lệ']);
                check = false;
            } else {
                $(this).find($(".error_quantity_" + number + "")).text('');
            }

            if (isInt(tax) == false) {
                $(this).find($(".error_tax_" + number + "")).text(edit.translateJson['VAT không hợp lệ']);
                check = false;
            } else {
                $(this).find($(".error_tax_" + number + "")).text('');
            }

            arrData.push({
                object_type: objectType,
                object_name: objectName,
                object_code: objectCode,
                object_id: objectId,
                unit_id: unitId,
                quantity: quantity,
                price: price.replace(new RegExp('\\,', 'g'), ''),
                tax: tax,
                discount: discount.replace(new RegExp('\\,', 'g'), ''),
                amount: amount.replace(new RegExp('\\,', 'g'), ''),
                order_code: orderCode,
                staff_id: staffId,
                note: note,
                is_applied_kpi: isAppliedKpi,
            });
        });

        let checkKpi = $($('#table-goods > tbody').find('.is_applied_kpi').is(':checked')).length;
        let checkNotKpi = $($('#table-goods > tbody').find('.is_applied_kpi').not(':checked')).length;
        if (checkKpi && checkNotKpi) {
            swal(edit.translateJson["Không thể thêm hàng hoá vừa tính kpi và không tính kpi"], "", "error")
            check = false;
        }
        else if (checkKpi == 0 && checkNotKpi > 1) {
            swal(edit.translateJson["Chỉ được thêm 1 hàng hoá không tính kpi"], "", "error")
            check = false;
        }
        if (check == true) {
            $.ajax({
                url: laroute.route('contract.contract.store-goods'),
                method: "POST",
                dataType: 'JSON',
                data: {
                    contract_id: $('#contract_id_hidden').val(),
                    arrData: arrData,
                    order_code: $('.order_code_append').val(),
                    contract_source: $('#contract_source').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        if (res.infoOrder != null && res.infoOrder.process_status != 'new') {
                            $('.div_search_order').css('display', 'none');
                        }

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
                },
            });
        }
    
    },
    saveAnnexGood: function (e) {
        
        var check = true;
        var arrData = [];
        //validate object
        $.each($('#table-goods > tbody').find('.tr-goods'), function () {
            var objectType = $(this).find($('.object_type')).val();
            var objectName = $(this).find($('.object_name')).val();
            var objectCode = $(this).find($('.object_code')).val();
            var objectId = $(this).find($('.object_id')).val();
            var unitId = $(this).find($('.object_id')).val();
            var quantity = $(this).find($('.quantity')).val();
            var price = $(this).find($('.price')).val();
            var tax = $(this).find($('.tax')).val();
            var discount = $(this).find($('.discount')).val();
            var amount = $(this).find($('.amount')).val();
            var orderCode = $(this).find($('.order_code')).val();
            var note = $(this).find($('.note')).val();
            var staffId = $(this).find($('.staff_id')).val();
            var number = $(this).find($('.number')).val();
            var isAppliedKpi = $(this).find($('.is_applied_kpi')).val();

            if (objectType == "") {
                $(this).find($(".error_object_type_" + number + "")).text(edit.translateJson['Hãy chọn loại hàng hoá']);
                check = false;
            } else {
                $(this).find($(".error_object_type_" + number + "")).text('');
            }

            if (objectId == "") {
                $(this).find($(".error_object_id_" + number + "")).text(edit.translateJson['Hãy chọn hàng hoá']);
                check = false;
            } else {
                $(this).find($(".error_object_id_" + number + "")).text('');
            }

            if (quantity == "" || quantity == 0 || isInt(quantity) == false) {
                $(this).find($(".error_quantity_" + number + "")).text(edit.translateJson['Số lượng không hợp lệ']);
                check = false;
            } else {
                $(this).find($(".error_quantity_" + number + "")).text('');
            }

            if (isInt(tax) == false) {
                $(this).find($(".error_tax_" + number + "")).text(edit.translateJson['VAT không hợp lệ']);
                check = false;
            } else {
                $(this).find($(".error_tax_" + number + "")).text('');
            }

            let checkKpi = $($('#table-goods > tbody').find('.is_applied_kpi').is(':checked')).length;
            let checkNotKpi = $($('#table-goods > tbody').find('.is_applied_kpi').not(':checked')).length;
            if (checkKpi && checkNotKpi) {
                swal(edit.translateJson["Không thể thêm hàng hoá vừa tính kpi và không tính kpi"], "", "error")
                check = false;
            }
            else if (checkKpi == 0 && checkNotKpi > 1) {
                swal(edit.translateJson["Chỉ được thêm 1 hàng hoá không tính kpi"], "", "error")
                check = false;
            }
            arrData.push({
                object_type: objectType,
                object_name: objectName,
                object_code: objectCode,
                object_id: objectId,
                unit_id: unitId,
                quantity: quantity,
                price: price.replace(new RegExp('\\,', 'g'), ''),
                tax: tax,
                discount: discount.replace(new RegExp('\\,', 'g'), ''),
                amount: amount.replace(new RegExp('\\,', 'g'), ''),
                order_code: orderCode,
                staff_id: staffId,
                note: note,
                is_applied_kpi: isAppliedKpi
            });
        });

        if (check == true) {
            $.ajax({
                url: laroute.route('contract.contract.store-annex-goods'),
                method: "POST",
                dataType: 'JSON',
                data: {
                    contract_id: $('#contract_id_hidden').val(),
                    arrData: arrData,
                    order_code: $('.order_code_append').val(),
                    dataAnnexLocal: $('#dataAnnexLocal').val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            }
                            if (result.value == true) {
                            }
                            var dataAnnexLocal = JSON.parse($('#dataAnnexLocal').val());
                            if (dataAnnexLocal['is_active'] == 1) {
                                $(e).remove();
                                $('.btn-add-good-remove').remove();
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                },
            });
        }
    
    },
    //Click chỉnh sửa hàng hoá
    clickEdit: function (obj) {
        var number = $(obj).closest('.tr-goods').find('.number').val();
        var objectName = $(obj).closest('.tr-goods').find($('.object_name')).val();
        var objectId = $(obj).closest('.tr-goods').find($('.object_id')).val();
        $(obj).closest('.tr-goods').find('.click_edit').val(1);
        //Bỏ disable các input ra
        $(obj).closest('.tr-goods').find('.object_type').prop('disabled', false);
        $(obj).closest('.tr-goods').find('.object_id').prop('disabled', false);
        $(obj).closest('.tr-goods').find('.unit_id').prop('disabled', false);
        $(obj).closest('.tr-goods').find('.quantity').prop('disabled', false);
        $(obj).closest('.tr-goods').find('.tax').prop('disabled', false);
        $(obj).closest('.tr-goods').find('.discount').prop('disabled', false);
        $(obj).closest('.tr-goods').find('.note').prop('disabled', false);
        //Bấm vào thì load option object_id
        $(obj).closest('.tr-goods').find('.object_type').trigger('change');
        $(obj).closest('.tr-goods').find('.object_id').append('<option value="' + objectId + '" selected>' + objectName + '</option>');

        if ($('#contract_category_type').val() == 'sell') {
            $(obj).closest('.tr-goods').find($('.tax')).prop('disabled', true);
        }

        //Xoá nút hiện tại
        $(obj).remove();
        //Append nút save vào
        var tpl = $('#tpl-save-goods').html();
        $('.td_action_' + number + '').prepend(tpl);

        new AutoNumeric.multiple('#discount_' + number + '', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });
    },
    clickSave: function (obj) {
        
        var number = $(obj).closest('.tr-goods').find('.number').val();
        var objectType = $(obj).closest('.tr-goods').find($('.object_type')).val();
        var objectId = $(obj).closest('.tr-goods').find($('.object_id')).val();
        var quantity = $(obj).closest('.tr-goods').find($('.quantity')).val();
        var tax = $(obj).closest('.tr-goods').find($('.tax')).val();

        var check = true;

        //Validate
        if (objectType == "") {
            $(obj).closest('.tr-goods').find($(".error_object_type_" + number + "")).text(edit.translateJson['Hãy chọn loại hàng hoá']);
            check = false;
        } else {
            $(obj).closest('.tr-goods').find($(".error_object_type_" + number + "")).text('');
        }

        if (objectId == "") {
            $(obj).closest('.tr-goods').find($(".error_object_id_" + number + "")).text(edit.translateJson['Hãy chọn hàng hoá']);
            check = false;
        } else {
            $(obj).closest('.tr-goods').find($(".error_object_id_" + number + "")).text('');
        }

        if (quantity == "" || quantity == 0 || isInt(quantity) == false) {
            $(obj).closest('.tr-goods').find($(".error_quantity_" + number + "")).text(edit.translateJson['Số lượng không hợp lệ']);
            check = false;
        } else {
            $(obj).closest('.tr-goods').find($(".error_quantity_" + number + "")).text('');
        }

        if (isInt(tax) == false) {
            $(obj).closest('.tr-goods').find($(".error_tax_" + number + "")).text(edit.translateJson['VAT không hợp lệ']);
            check = false;
        } else {
            $(obj).closest('.tr-goods').find($(".error_tax_" + number + "")).text('');
        }

        if (check == true) {
            $(obj).closest('.tr-goods').find('.click_edit').val(0);
            //Disable các input lại
            $(obj).closest('.tr-goods').find('.object_type').prop('disabled', true);
            $(obj).closest('.tr-goods').find('.object_id').prop('disabled', true);
            $(obj).closest('.tr-goods').find('.unit_id').prop('disabled', true);
            $(obj).closest('.tr-goods').find('.quantity').prop('disabled', true);
            $(obj).closest('.tr-goods').find('.tax').prop('disabled', true);
            $(obj).closest('.tr-goods').find('.discount').prop('disabled', true);
            $(obj).closest('.tr-goods').find('.note').prop('disabled', true);
            //Xoá nút hiện tại
            $(obj).remove();
            //Append nút save vào
            var tpl = $('#tpl-edit-goods').html();
            $('.td_action_' + number + '').prepend(tpl);
        }
    
    },
    removeGoods: function (obj) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');
    },
    searchOrder: function () {
        
        $.ajax({
            url: laroute.route('contract.contract.search-order'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_id: $('#contract_id_hidden').val(),
                search_order: $('#search_order').val()
            },
            success: function (res) {
                if (res.error == false) {
                    let countKpi = 0;
                    let countNotKpi = 0;
                    $.map(res.data, function (val) {
                        val.is_applied_kpi == 0 ? countNotKpi++ : countKpi++;
                    });
                    if (countKpi > 0 && countNotKpi > 0) {
                        swal.fire(edit.translateJson["Đơn hàng bao gồm sản phẩm tính kpi và không tính kpi, không thể thêm vào hợp đồng"], "", "error");
                        return false;
                    }
                    if (countKpi == 0 && countNotKpi > 1) {
                        swal(edit.translateJson["Đơn hàng bao gồm 2 sản phẩm không tính kpi, không thể thêm vào hợp đồng"], "", "error")
                        return false;
                    }
                    swal.fire(res.message, "", "success");

                    $('#table-goods > tbody').empty();

                    $.map(res.data, function (val) {
                        number++;

                        var tpl = $('#tpl-goods-order').html();
                        tpl = tpl.replace(/{number}/g, number);
                        if (val.is_applied_kpi == 1) {
                            tpl = tpl.replace(/{checked}/g, "checked");
                        } else {
                            tpl = tpl.replace(/{checked}/g, "");
                        }
                        $('#table-goods > tbody').append(tpl);

                        $('.object_type').select2({
                            placeholder: edit.translateJson['Chọn loại hàng hoá']
                        });

                        $('.unit_id').select2({
                            placeholder: edit.translateJson['Chọn đơn vị tính']
                        });
                        $('#discount_' + number + '').closest('.tr-goods').find($('.object_type')).val(val.object_type).trigger('change');
                        $('#discount_' + number + '').closest('.tr-goods').find($('.object_code')).val(val.object_code);
                        $('#discount_' + number + '').closest('.tr-goods').find($('.object_name')).val(val.object_name);
                        $('#discount_' + number + '').closest('.tr-goods').find($('.object_id')).append('<option value="' + val.object_id + '" selected>' + val.object_name + '</option>');
                        $('#discount_' + number + '').closest('.tr-goods').find($('.price')).val(formatNumber(Number(val.price).toFixed(decimal_number)));
                        $('#discount_' + number + '').closest('.tr-goods').find($('.quantity')).val(val.quantity);
                        $('#discount_' + number + '').closest('.tr-goods').find($('.discount')).val(formatNumber(Number(val.discount).toFixed(decimal_number)));
                        $('#discount_' + number + '').closest('.tr-goods').find($('.amount')).val(formatNumber(Number(val.amount).toFixed(decimal_number)));
                        $('#discount_' + number + '').closest('.tr-goods').find($('.order_code')).val(res.infoOrder.order_code);
                        $('#discount_' + number + '').closest('.tr-goods').find($('.staff_id')).val(val.staff_id);
                        $('#discount_' + number + '').closest('.tr-goods').find($('.type_object')).val('not_gift');

                        if (val.object_type == 'product_gift'
                            || val.object_type == 'service_gift'
                            || val.object_type == 'service_card_gift'
                            || res.infoOrder.process_status != 'new') {
                            $('#discount_' + number + '').closest('.tr-goods').find($('.object_type')).prop('disabled', true);
                            $('#discount_' + number + '').closest('.tr-goods').find($('.object_id')).prop('disabled', true);
                            $('#discount_' + number + '').closest('.tr-goods').find($('.quantity')).prop('disabled', true);
                            $('#discount_' + number + '').closest('.tr-goods').find($('.tax')).prop('disabled', true);
                            $('#discount_' + number + '').closest('.tr-goods').find($('.discount')).prop('disabled', true);
                            $('#discount_' + number + '').closest('.tr-goods').find($('.td_remove')).empty();
                            $('#discount_' + number + '').closest('.tr-goods').find($('.type_object')).val('gift');
                        }

                        $('#discount_' + number + '').closest('.tr-goods').find($('.tax')).prop('disabled', true);

                        new AutoNumeric.multiple('#discount_' + number + '', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            minimumValue: 0
                        });

                        $('.input_int').ForceNumericOnly();
                    });

                    if (res.infoOrder.process_status != 'new') {
                        $('.div_add_goods').css('display', 'none');
                    } else {
                        $('.div_add_goods').css('display', 'block');
                    }

                    $('#order-goods').empty();
                    $('#order-goods').append('<input type="hidden" class="order_code_append" value="' + res.infoOrder.order_code + '">');
                    $('#contract_source').val('order');
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    
    }
};

var contractAnnex = {
    _initEditContractAnnex: function () {
        
        $('.select').select2();

        $('.date_picker').datepicker({
            language: 'vi',
            orientation: "bottom left",
            todayHighlight: !0
        });

        new AutoNumeric.multiple('.input_float', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });

        $('.input_int').ForceNumericOnly();

        $("#tag").select2({
            // placeholder: edit.translateJson['Chọn tag'],
            tags: true,
            // tokenSeparators: [",", " "],
            createTag: function (newTag) {
                return {
                    id: 'new:' + newTag.term,
                    text: newTag.term,
                    isNew: true
                };
            }
        }).on("select2:select", function (e) {
            if (e.params.data.isNew) {
                // store the new tag:
                $.ajax({
                    type: "POST",
                    url: laroute.route('contract.contract.insert-tag'),
                    data: {
                        tag_name: e.params.data.text
                    },
                    success: function (res) {
                        // append the new option element end replace id
                        $('#tab_general_tag').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                    }
                });
            }
        });

        $("#payment_method_id").select2({
            tags: true,
            createTag: function (newTag) {
                return {
                    id: 'new:' + newTag.term,
                    text: newTag.term,
                    isNew: true
                };
            }
        }).on("select2:select", function (e) {
            if (e.params.data.isNew) {
                // store the new tag:
                $.ajax({
                    type: "POST",
                    url: laroute.route('contract.contract.insert-payment-method'),
                    data: {
                        payment_method_name: e.params.data.text
                    },
                    success: function (res) {
                        // append the new option element end replace id
                        $('#payment_method_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.payment_method_id + '">' + e.params.data.text + '</option>');
                    }
                });
            }
        });

        $("#payment_unit_id").select2({
            tags: true,
            createTag: function (newTag) {
                return {
                    id: 'new:' + newTag.term,
                    text: newTag.term,
                    isNew: true
                };
            }
        }).on("select2:select", function (e) {
            if (e.params.data.isNew) {
                // store the new tag:
                $.ajax({
                    type: "POST",
                    url: laroute.route('contract.contract.insert-payment-unit'),
                    data: {
                        name: e.params.data.text
                    },
                    success: function (res) {
                        // append the new option element end replace id
                        $('#payment_method_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.payment_unit_id + '">' + e.params.data.text + '</option>');
                    }
                });
            }
        });

        $('#status_code').select2({
            placeholder: edit.translateJson['Chọn trạng thái']
        });

        $('#status_code_created_ticket').select2({
            placeholder: edit.translateJson['Chọn trạng thái']
        });

        //Load ds dự kiến thu
        $('#autotable-expected-receipt').PioTable({
            baseUrl: laroute.route('contract.contract.list-expected-revenue')
        });

        $('.btn-search-expected-receipt').trigger('click');

        //Load ds đợt thu
        $('#autotable-receipt').PioTable({
            baseUrl: laroute.route('contract.contract.list-receipt')
        });

        $('.btn-search-receipt').trigger('click');

        //Load ds dự kiến chi
        $('#autotable-expected-spend').PioTable({
            baseUrl: laroute.route('contract.contract.list-expected-revenue')
        });

        $('.btn-search-expected-spend').trigger('click');

        //Load ds đợt chi
        $('#autotable-spend').PioTable({
            baseUrl: laroute.route('contract.contract.list-spend')
        });

        $('.btn-search-spend').trigger('click');

        //Load ds đính kèm
        $('#autotable-file').PioTable({
            baseUrl: laroute.route('contract.contract.list-file')
        });

        $('.btn-search-file').trigger('click');

        //Load ds hàng hoá
        contractGoods.listGoodOfAnnex();
        //Lấy trạng thái đơn hàng gần nhất
        view.getStatusOrder();
    
    },
    popupAddContractAnnex: function () {
        
        $.ajax({
            url: laroute.route('contract.contract.get-popup-annex'),
            method: "POST",
            dataType: "JSON",
            data: {
                contract_id: $('#contract_id_hidden').val(),
            },
            success: function (res) {
                $('#my-annex-modal').html(res.html);
                $('#add-annex').modal('show');
                var arrRange = {};
                arrRange[edit.translateJson["Hôm nay"]] = [moment(), moment()];
                arrRange[edit.translateJson["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
                arrRange[edit.translateJson["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
                arrRange[edit.translateJson["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
                arrRange[edit.translateJson["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
                arrRange[edit.translateJson["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
                $("#annex_effective_date,#annex_expired_date,#annex_sign_date").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy",
                    startDate: "dateToday"
                });
            }
        });
    
    },
    popupEditContractAnnex: function (id) {
        
        $.ajax({
            url: laroute.route('contract.contract.get-popup-annex'),
            method: "POST",
            dataType: "JSON",
            data: {
                contract_id: $('#contract_id_hidden').val(),
                contract_annex_id: id
            },
            success: function (res) {
                $('#my-annex-modal').html(res.html);
                $('#edit-annex').modal('show');
                var arrRange = {};
                arrRange[edit.translateJson["Hôm nay"]] = [moment(), moment()];
                arrRange[edit.translateJson["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
                arrRange[edit.translateJson["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
                arrRange[edit.translateJson["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
                arrRange[edit.translateJson["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
                arrRange[edit.translateJson["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
                $("#annex_effective_date,#annex_expired_date,#annex_sign_date").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy",
                    startDate: "dateToday"
                });
            }
        });
    
    },
    uploadFileCc: function (input) {
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            var file_data = $('#upload_file_cc').prop('files')[0];
            if (file_data.size > 41943040) {
                swal(edit.translateJson['Tối đa 5MB'], "", "error");
            }
            var actFile = [".pdf", ".doc", ".docx", ".pdf", ".csv", ".xls", ".xlsx"];
            var ext = file_data.name.substring(file_data.name.lastIndexOf("."), file_data.name.length);
            if (jQuery.inArray(ext, actFile) == -1) {
                swal(edit.translateJson['Vui lòng chọn file đúng định dạng'], "", "error");
            }
            else {
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('link', '_contract_annex.');
                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        if (res.error == 0) {
                            $('#contract_annex_list_files').append(`
                                <div class="col-lg-12">
                                    <a href="${res.file}" value="${res.file}" name="contract_annex_list_files[]" class="ss--text-black" download="${file_data.name}">${file_data.name}</a>
                                    <a href="javascript:void(0)" onclick="$(this).parent('div').remove()"><i class="la la-trash"></i></a>
                                    <br>
                                </div>
                            `);
                        }
                    }
                });
            }

        }
    
    },
    changeSubmitAnnex: function () {
        var adjustment_type = $('[name="annex_adjustment_type"]:checked').val();
        switch (adjustment_type) {
            case 'update_contract':
            case 'renew_contract':
                $('.annex_continue').prop('hidden', false);
                $('.annex_save').attr('hidden', true);
                break;
            case 'update_info':
                $('.annex_save').prop('hidden', false);
                $('.annex_continue').attr('hidden', true);
                break;

        }
    },
    actionAnnexSaveOrContinue: function (id) {
        
        var form = $('#form-add-annex');
        form.validate({
            rules: {
                annex_contract_annex_code: {
                    required: true,
                    maxlength: 191
                },
                annex_effective_date: {
                    required: true,
                },
                annex_sign_date: {
                    required: true,
                },
                annex_expired_date: {
                    required: true,
                },
                annex_content: {
                    required: true,
                },
            },
            messages: {
                annex_contract_annex_code: {
                    required: edit.translateJson['Mã phụ lục không được trống'],
                    maxlength: edit.translateJson['Tối đa 191 kí tự']
                },
                annex_effective_date: {
                    required: edit.translateJson['Ngày có hiệu lực không được trống'],
                },
                annex_sign_date: {
                    required: edit.translateJson['Ngày ký không được trống'],
                },
                annex_expired_date: {
                    required: edit.translateJson['Ngày hết hiệu lực không được trống'],
                },
                annex_content: {
                    required: edit.translateJson['Nội dung không được trống'],
                },
            },
        });
        if (!form.valid()) {
            return false;
        }
        var contract_annex_list_files = [];
        var contract_annex_list_name_files = [];
        var nFile = $('[name="contract_annex_list_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_annex_list_files.push($('[name="contract_annex_list_files[]"]')[i].href);
                contract_annex_list_name_files.push($('[name="contract_annex_list_files[]"]')[i].text);
            }
        }
        if (id == 0) {
            $.ajax({
                url: laroute.route('contract.contract.save-annex'),
                method: "POST",
                dataType: "JSON",
                data: {
                    contract_id: $('#annex_contract_id').val(),
                    contract_annex_code: $('#annex_contract_annex_code').val(),
                    effective_date: $('#annex_effective_date').val(),
                    sign_date: $('#annex_sign_date').val(),
                    expired_date: $('#annex_expired_date').val(),
                    adjustment_type: $('[name="annex_adjustment_type"]:checked').val(),
                    content: $('#annex_content').val(),
                    is_active: $('#is_active').is(":checked") ? 1 : 0,
                    contract_annex_list_files: contract_annex_list_files,
                    contract_annex_list_name_files: contract_annex_list_name_files,
                },
                success: function (res) {
                    if (!res.error) {
                        swal(edit.translateJson["Thêm phụ lục hợp đồng thành công"], "", "success");
                        // $('#autotable-annex').PioTable('refresh');
                        $('.btn-search-annex').trigger('click');
                        $('#add-annex').modal('hide');
                    } else {
                        swal(res.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(edit.translateJson['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                }
            });
        }
        else {
            var dataAnnexLocal = {
                contract_id: $('#annex_contract_id').val(),
                contract_annex_code: $('#annex_contract_annex_code').val(),
                effective_date: $('#annex_effective_date').val(),
                sign_date: $('#annex_sign_date').val(),
                expired_date: $('#annex_expired_date').val(),
                adjustment_type: $('[name="annex_adjustment_type"]:checked').val(),
                content: $('#annex_content').val(),
                is_active: $('#is_active').is(":checked") ? 1 : 0,
                contract_annex_list_files: contract_annex_list_files,
                contract_annex_list_name_files: contract_annex_list_name_files,
            }
            $.ajax({
                url: laroute.route('contract.contract.continue-annex'),
                method: "POST",
                dataType: "JSON",
                data: {
                    contract_id: $('#annex_contract_id').val(),
                    contract_annex_code: $('#annex_contract_annex_code').val(),
                    effective_date: $('#annex_effective_date').val(),
                    sign_date: $('#annex_sign_date').val(),
                    expired_date: $('#annex_expired_date').val(),
                    adjustment_type: $('[name="annex_adjustment_type"]:checked').val(),
                    content: $('#annex_content').val(),
                    is_active: $('#is_active').is(":checked") ? 1 : 0,
                    contract_annex_list_files: contract_annex_list_files,
                    contract_annex_list_name_files: contract_annex_list_name_files,
                    dataAnnexLocal: JSON.stringify(dataAnnexLocal)
                },
                success: function (res) {
                    if (!res.error) {
                        console.log(res);
                        window.open(laroute.route('contract.contract.view-edit-contract-annex', {
                            'finalData': JSON.stringify(res.finalData)
                        }), '_self');
                    } else {
                        swal(res.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(edit.translateJson['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                }
            });
        }
    
    },
    actionUpdateAnnexSaveOrContinue: function (id) {
        
        var form = $('#form-edit-annex');
        form.validate({
            rules: {
                annex_contract_annex_code: {
                    required: true,
                    maxlength: 191
                },
                annex_effective_date: {
                    required: true,
                },
                annex_sign_date: {
                    required: true,
                },
                annex_expired_date: {
                    required: true,
                },
                annex_content: {
                    required: true,
                },
            },
            messages: {
                annex_contract_annex_code: {
                    required: edit.translateJson['Mã phụ lục không được trống'],
                    maxlength: edit.translateJson['Tối đa 191 kí tự']
                },
                annex_effective_date: {
                    required: edit.translateJson['Ngày có hiệu lực không được trống'],
                },
                annex_sign_date: {
                    required: edit.translateJson['Ngày ký không được trống'],
                },
                annex_expired_date: {
                    required: edit.translateJson['Ngày hết hiệu lực không được trống'],
                },
                annex_content: {
                    required: edit.translateJson['Nội dung không được trống'],
                },
            },
        });
        if (!form.valid()) {
            return false;
        }
        var contract_annex_list_files = [];
        var contract_annex_list_name_files = [];
        var nFile = $('[name="contract_annex_list_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                contract_annex_list_files.push($('[name="contract_annex_list_files[]"]')[i].href);
                contract_annex_list_name_files.push($('[name="contract_annex_list_files[]"]')[i].text);
            }
        }
        if (id == 0) {
            $.ajax({
                url: laroute.route('contract.contract.update-annex'),
                method: "POST",
                dataType: "JSON",
                data: {
                    contract_annex_id: $('#annex_contract_annex_id').val(),
                    contract_id: $('#annex_contract_id').val(),
                    contract_annex_code: $('#annex_contract_annex_code').val(),
                    effective_date: $('#annex_effective_date').val(),
                    sign_date: $('#annex_sign_date').val(),
                    expired_date: $('#annex_expired_date').val(),
                    adjustment_type: $('[name="annex_adjustment_type"]:checked').val(),
                    content: $('#annex_content').val(),
                    is_active: $('#is_active').is(":checked") ? 1 : 0,
                    contract_annex_list_files: contract_annex_list_files,
                    contract_annex_list_name_files: contract_annex_list_name_files,
                },
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, "", "success");
                        // $('#autotable-annex').PioTable('refresh');

                        $('.btn-search-annex').trigger('click');
                        $('#edit-annex').modal('hide');
                    } else {
                        swal(res.message, "", "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(edit.translateJson['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                }
            });
        }
        else {
            var dataAnnexLocal = {
                contract_id: $('#annex_contract_id').val(),
                contract_annex_id: $('#annex_contract_annex_id').val(),
                contract_annex_code: $('#annex_contract_annex_code').val(),
                effective_date: $('#annex_effective_date').val(),
                sign_date: $('#annex_sign_date').val(),
                expired_date: $('#annex_expired_date').val(),
                adjustment_type: $('[name="annex_adjustment_type"]:checked').val(),
                content: $('#annex_content').val(),
                is_active: $('#is_active').is(":checked") ? 1 : 0,
                contract_annex_list_files: contract_annex_list_files,
                contract_annex_list_name_files: contract_annex_list_name_files,
            };
            $.ajax({
                url: laroute.route('contract.contract.continue-update-annex'),
                method: "POST",
                dataType: "JSON",
                data: {
                    contract_annex_id: $('#annex_contract_annex_id').val(),
                    contract_id: $('#annex_contract_id').val(),
                    contract_annex_code: $('#annex_contract_annex_code').val(),
                    effective_date: $('#annex_effective_date').val(),
                    sign_date: $('#annex_sign_date').val(),
                    expired_date: $('#annex_expired_date').val(),
                    adjustment_type: $('[name="annex_adjustment_type"]:checked').val(),
                    content: $('#annex_content').val(),
                    is_active: $('#is_active').is(":checked") ? 1 : 0,
                    contract_annex_list_files: contract_annex_list_files,
                    contract_annex_list_name_files: contract_annex_list_name_files,
                    dataAnnexLocal: JSON.stringify(dataAnnexLocal)
                },
                success: function (res) {
                    console.log(res);
                    window.open(laroute.route('contract.contract.view-edit-contract-annex', {
                        'finalData': JSON.stringify(res.finalData)
                    }), '_self');
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(edit.translateJson['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                }
            });
        }
    
    },
    saveInfoContractAnnex: function (e, contractId) {
        
        var form = $('#form-info');

        var rules = [];
        var messages = [];

        var dataGeneral = {};
        var dataPartner = {};
        var dataPayment = {};
        //Quét input cụm thông tin chung
        $.each($('#group-general'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            case 'tab_general_contract_name':
                                rules[$(this).attr("name")] = {
                                    required: true,
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống'],
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataGeneral[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    }
                    else {
                        dataGeneral[$(this).attr("id")] = $(this).val();
                    }
                }
            });
        });

        //Quét input cụm đối tác
        $.each($('#group-partner'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            case 'tab_partner_address':
                                rules[$(this).attr("name")] = {
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            case 'tab_partner_email':
                                rules[$(this).attr("name")] = {
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataPartner[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataPartner[$(this).attr("id")] = $(this).val();
                    }
                }
            });
        });

        //Quét input cụm thanh toán
        $.each($('#group-payment'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataPayment[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataPayment[$(this).attr("id")] = $(this).val();
                    }

                }
            });
        });
        //Quét input cụm thông tin chung
        $.each($('#group-general'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            case 'tab_general_contract_name':
                                rules[$(this).attr("name")] = {
                                    required: true,
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống'],
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataGeneral[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    }
                    else {
                        dataGeneral[$(this).attr("id")] = $(this).val();
                    }
                }
            });
        });

        //Quét input cụm đối tác
        $.each($('#group-partner'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            case 'tab_partner_address':
                                rules[$(this).attr("name")] = {
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            case 'tab_partner_email':
                                rules[$(this).attr("name")] = {
                                    maxlength: 190
                                };

                                messages[$(this).attr("name")] = {
                                    maxlength: $(this).attr("keyName") + ' ' + edit.translateJson['tối đa 190 ký tự']
                                };
                                break;
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataPartner[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataPartner[$(this).attr("id")] = $(this).val();
                    }
                }
            });
        });

        //Quét input cụm thanh toán
        $.each($('#group-payment'), function () {
            var $tds = $(this).find("input,select,textarea");

            $.each($tds, function () {
                if ($(this).attr("name") != null) {
                    if ($(this).attr("isValidate") == 1) {
                        //Lấy thông tin validate
                        switch ($(this).attr("name")) {
                            default:
                                rules[$(this).attr("name")] = {
                                    required: true
                                };

                                messages[$(this).attr("name")] = {
                                    required: $(this).attr("keyName") + ' ' + edit.translateJson['không được trống']
                                };
                        }
                    }
                    //Lấy dữ liệu của các trường
                    if ($(this).attr("keyType") == "float" || $(this).attr("keyType") == "int") {
                        dataPayment[$(this).attr("id")] = $(this).val().replace(new RegExp('\\,', 'g'), '');
                    } else if ($(this).attr("keyType") == "date") {
                        if ($(this).val() != '') {
                            dataGeneral[$(this).attr("id")] = moment(moment($(this).val(), 'DD/MM/YYYY')).format('YYYY-MM-DD');
                        }
                    } else {
                        dataPayment[$(this).attr("id")] = $(this).val();
                    }

                }
            });
        });

        form.validate({
            rules: rules,
            messages: messages,
        });

        if (!form.valid()) {
            return false;
        }

        var is_renew = 0;
        if ($('#is_renew').is(':checked')) {
            is_renew = 1;
        }

        var is_created_ticket = 0;
        if ($('#is_created_ticket').is(':checked')) {
            is_created_ticket = 1;
        }

        var is_value_goods = 0;
        if ($('#is_value_goods').is(':checked')) {
            is_value_goods = 1;
        }

        $.ajax({
            url: laroute.route('contract.contract.submit-edit-contract-annex'),
            method: "POST",
            dataType: 'JSON',
            data: {
                dataGeneral: dataGeneral,
                dataPartner: dataPartner,
                dataPayment: dataPayment,
                dataAnnexLocal: $('#dataAnnexLocal').val(),
                status_code: $('#status_code').val(),
                is_renew: is_renew,
                number_day_renew: $('#number_day_renew').val(),
                is_created_ticket: is_created_ticket,
                status_code_created_ticket: $('#status_code_created_ticket').val(),
                contract_name: dataGeneral['contract_name'],
                contract_id: contractId,
                category_type: $('#category_type').val(),
                is_value_goods: is_value_goods
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            // window.location.reload();
                        }
                        if (result.value == true) {
                            // window.location.reload();
                        }
                        var dataAnnexLocal = JSON.parse($('#dataAnnexLocal').val());
                        if (dataAnnexLocal['is_active'] == 1) {
                            $(e).remove();
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
                swal(edit.translateJson['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
            }
        });
   
    },
    remove: function (e, id) {
        
        $.ajax({
            url: laroute.route('contract.contract.delete-annex'),
            method: "POST",
            dataType: "JSON",
            data: {
                contract_id: id
            },
            success: function (res) {
                if (!res.error) {
                    swal(res.message, "", "success");
                    window.location.reload();
                } else {
                    swal(res.message, "", "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(edit.translateJson['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
            }
        });
    
    }
};

var detail = {
    showModalStatus: function (contractId) {
        $.ajax({
            url: laroute.route('contract.contract.show-modal-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_id: contractId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-update-status').modal('show');

                $('#status_update_code').select2();
            }
        });
    },
    updateStatus: function (contractId) {
        $.ajax({
            url: laroute.route('contract.contract.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                contract_id: contractId,
                status_code: $('#status_update_code').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-update-status').modal('hide');

                            window.location.reload();
                        }
                        if (result.value == true) {
                            $('#modal-update-status').modal('hide');

                            window.location.reload();
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    }
};

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

function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function isInt(value) {
    if (isNaN(value)) {
        return false;
    }
    var x = parseFloat(value);
    return (x | 0) === x;
}