var stt = 0;
$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $("#time_sent").timepicker({
            minuteStep: 1,
            showMeridian: !1,
            snapToStep: !0,
        });
        $('#day_sent').datepicker({
            format: 'dd/mm/yyyy',
            language: 'vi',
            startDate: '0d'
        });
        $('#is_now').click(function () {
            if ($('#is_now').prop('checked')) {
                $('#day_sent').attr("disabled", true);
                $('#time_sent').attr("disabled", true);
                $(this).val(1);
            } else {
                $('#day_sent').attr("disabled", false);
                $('#time_sent').attr("disabled", false);
                $(this).val(0);
            }
        });
        $('#content').summernote({
            height:200,
            placeholder: json['Nhập nội dung'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ],

        });
        $('.note-btn').attr('title', '');
        $('#branch_id').select2({
        placeholder: json['Hãy chọn chi nhánh']
        });

        new AutoNumeric.multiple('#cost' ,{
            currencySymbol : '',
            decimalCharacter : '.',
            digitGroupSeparator : ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });
    });
});
var add = {
    append_para: function (param) {
        var text = param;
        $('#content').summernote('pasteHTML', text);
    },
    submit_add: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-add');

            form.validate({
                rules: {
                    name: {
                        required: true
                    },
                    content: {
                        required: true
                    },
                    branch_id:{
                        required:true
                    },
                    cost:{
                        required:true
                    }
                },
                messages: {
                    name: {
                        required: json['Hãy nhập tên chiến dịch']
                    },
                    content: {
                        required: json['Hãy nhập nội dung tin nhắn']
                    },
                    branch_id: {
                        required:json['Hãy chọn chi nhánh']
                    },
                    cost: {
                        required:json['Hãy nhập chi phí cho chiến dịch']
                    }
                }
            });

            if (!form.valid()) {
                return false;
            }
            var is_deal_created = 0;
            if($('#is_deal_created').is(":checked")){
                is_deal_created = 1;
            }
            if(is_deal_created == 1){
                if($('#pipeline_code').val() == ''){
                    swal(json['Hãy chọn pipeline'], '', "error");
                    return;
                }
                if($('#end_date_expected').val() == ''){
                    swal(json['Hãy chọn ngày kết thúc dự kiến'], '', "error");
                    return;
                }
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
                url: laroute.route('admin.email.submitAdd'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    name: $('#name').val(),
                    content_campaign: $('#content').summernote('code'),
                    day_sent: $('#day_sent').val(),
                    time_sent: $('#time_sent').val(),
                    is_now: $('#is_now').val(),
                    branch_id:$('#branch_id').val(),
                    cost:$('#cost').val(),
                    is_deal_created:is_deal_created,
                    end_date_expected: $('#end_date_expected').val(),
                    pipeline_code: $('#pipeline_code').val(),
                    journey_code: $('#journey_code').val(),
                    amount: $('#amount').val(),
                    arrObject: arrObject
                }, success: function (res) {
                    if (res.error_slug == 1) {
                        $('.error_slug').text(json['Tên chiến dịch đã tồn tại']);
                    } else {
                        $('.error_slug').text('');
                    }
                    if (res.error_time == 1) {
                        $('.error_time').text(json['Thời gian gửi không hợp lệ']);
                    } else {
                        $('.error_time').text('');
                    }
                    if (res.success == 1) {
                        swal(json["Thêm chiến dịch thành công"], "", "success");
                        let routess = laroute.route('admin.email.edit');
                        window.location = routess.substring(0, 17) + '/' + res.id_add;
                    }
                }
            });
        });
    },

    closeModalDeal: function () {
        $('#load-modal-create').val(0);
        $('#modal-create').modal('hide');
    },
    saveModalDeal: function(){
        $('#load-modal-create').val(1);
        $('#modal-create').modal('hide');
    },
    changeCreateDeal: function(){
        if($('#is_deal_created').is(":checked")){
            $('#popup_create_deal').removeAttr('hidden');
        }
        else{
            $('#popup_create_deal').attr('hidden', true);
            $('#load-modal-create').val(0);
        }
    },

    popupCreateLead: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            let load = $('#load-modal-create').val();
            if(load == 1){
                $('#modal-create').modal('show');
            }
            else{
                $.ajax({
                    url: laroute.route('admin.email.email-popup-created-deal'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                    },
                    success: function (res) {
                        $('#my-modal-create').html(res.html);
                        $('#modal-create').modal('show');

                        $('#pipeline_code').select2({
                            placeholder: json['Chọn pipeline']
                        });

                        $('#journey_code').select2({
                            placeholder: json['Chọn hành trình']
                        });


                        $("#end_date_expected").datepicker({
                            todayHighlight: !0,
                            autoclose: !0,
                            format: "dd/mm/yyyy",
                            startDate: "dateToday"
                        });
                        $('#pipeline_code').change(function () {
                            $.ajax({
                                url: laroute.route('customer-lead.load-option-journey'),
                                dataType: 'JSON',
                                data: {
                                    pipeline_code: $('#pipeline_code').val(),
                                },
                                method: 'POST',
                                success: function (res) {
                                    $('.journey').empty();
                                    $.map(res.optionJourney, function (a) {
                                        $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                    });
                                }
                            });
                        });

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
            }
        });
    },
};
var dealEmail = {
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


    }
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