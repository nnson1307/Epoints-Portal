var stt = 0;
$("#time-send").timepicker({
    minuteStep: 15,
    defaultTime: "08:00:00",
    showMeridian: !1,
    snapToStep: !0,
});
$('#day-send').datepicker({
    format: "dd/mm/yyyy",
    startDate: '0d',
    language: 'en',
}).datepicker("setDate", new Date());

new AutoNumeric.multiple('#cost' ,{
    currencySymbol : '',
    decimalCharacter : '.',
    digitGroupSeparator : ',',
    decimalPlaces: decimal_number,
    minimumValue: 0
});
;
var AddCampaign = {
    countCharacter: function (o) {
        let flag = true;
        let lengths = $(o).val().length;
        $.getJSON(laroute.route('translate'), function (json) {
            $('.count-character').text(lengths);
            if (lengths > 480) {
                $('.error-count-character').text(json['Vượt quá 480 ký tự.']);
                flag = false;
            } else {
                $('.error-count-character').text('');
            }
            $(o).val(AddCampaign.changeAlias(o));
        });
        return flag;
    },
    valueParameter: function (o) {
        if (o == "customer-name") {
            AddCampaign.insertAtCaret("{CUSTOMER_NAME}");
        }
        if (o == "customer-birthday") {
            AddCampaign.insertAtCaret("{CUSTOMER_BIRTHDAY}");
        }
        if (o == "customer-gender") {
            AddCampaign.insertAtCaret("{CUSTOMER_GENDER}");
        }
        if (o == "full-name") {
            AddCampaign.insertAtCaret("{CUSTOMER_FULL_NAME}");
        }

        $('.count-character').text($('#message-content').val().length);
        AddCampaign.countCharacter('#message-content');
    },
    searchCustomer: function () {
        $.ajax({
            url: laroute.route('admin.sms.search-customer'),
            method: "POST",
            data: {
                keyword: $('#keyword').val(),
                birthday: $('#birthday').val(),
                gender: $('#gender').val(),
                branch: $('#branch').val(),
            },
            success: function (data) {
                $('.customer_list').empty();
                $.map(data.result, function (a) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        var tpl = $('#customer-list-tpl').html();
                        tpl = tpl.replace(/{name}/g, a.full_name);
                        tpl = tpl.replace(/{customer_id}/g, a.customer_id);
                        tpl = tpl.replace(/{birthday}/g, a.birthday);
                        tpl = tpl.replace(/{phone}/g, a.phone);
                        tpl = tpl.replace(/{gender1}/g, a.gender);
                        if (a.gender == 'male') {
                            tpl = tpl.replace(/{gender}/g, json['Nam']);
                        }
                        else if (a.gender == 'female') {
                            tpl = tpl.replace(/{gender}/g, json['Nữ']);
                        }
                        else {
                            tpl = tpl.replace(/{gender}/g, json['Khác']);
                        }
                        tpl = tpl.replace(/{branchName}/g, a.branch_name);
                        $('.customer_list').append(tpl);
                    });
                });
                sttAction();
            }
        })
    },
    chooseCustomer: function () {
        var array = [];
        $.each($('.customer_list tr .check:checked').parentsUntil("tbody"), function () {
            var $tds = $(this).find("td input");
            $.each($tds, function () {
                array.push($(this).val());
            });
        });
        $.getJSON(laroute.route('translate'), function (json) {
            if (array == "") {
                $('.error-add-customer').text(json['Vui lòng thêm khách hàng.']);
            } else {
                $('.table-list-customer').empty();
                var result = chunkArray(array, 6);
                $.map(result, function (value) {
                    var tpl = $('#add-customer-list').html();
                    tpl = tpl.replace(/{customer_id}/g, value[0]);
                    tpl = tpl.replace(/{name}/g, value[1]);
                    tpl = tpl.replace(/{phone}/g, value[2]);
                    tpl = tpl.replace(/{birthday}/g, value[3]);
                    tpl = tpl.replace(/{gender1}/g, value[4]);
                    if (value[4] == 'male') {
                        tpl = tpl.replace(/{gender}/g, json['Nam']);
                    }
                    else if (value[4] == 'female') {
                        tpl = tpl.replace(/{gender}/g, json['Nữ']);
                    }
                    else {
                        tpl = tpl.replace(/{gender}/g, json['Khác']);
                    }
                    tpl = tpl.replace(/{daysend}/g, json['Chưa gửi']);
                    tpl = tpl.replace(/{status}/g, json["Đang cài đặt"]);
                    $('.table-list-customer').append(tpl);
                });
                sttAction();
                $('#m_modal_4').modal('hide');
            }
        });
    },
    removeCustomer: function (o) {
        $(o).closest('tr').remove();
        sttAction();
    },
    changeAlias: function (alias) {
        var str = $(alias).val();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
        str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
        str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
        str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
        str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
        str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
        str = str.replace(/Đ/g, "D");

        return str;
    },
    testTime: function (time1, time2) {
        let flag = true;
        var timeA = new Date();
        timeA.setHours(time1.split(":")[0], time1.split(":")[1]);
        var timeB = new Date();
        timeB.setHours(time2.split(":")[0], time2.split(":")[1]);
        if (timeA >= timeB) {
            flag = false;
        }
        return flag;
    },
    insertAtCaret: function (text) {
        var txtarea = document.getElementById('message-content');
        var scrollPos = txtarea.scrollTop;
        var caretPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0, caretPos);
        var back = (txtarea.value).substring(txtarea.selectionEnd, txtarea.value.length);
        txtarea.value = front + text + back;
        caretPos = caretPos + text.length;
        txtarea.selectionStart = caretPos;
        txtarea.selectionEnd = caretPos;
        txtarea.focus();
        txtarea.scrollTop = scrollPos;
    },
    testInput: function () {
        let flag = true;
        let name = $('#name').val();
        let cost = $('#cost').val();
        let content = $('#message-content').val();
        let daySent = $('#day-send').val();
        let timeSend = $('#time-send').val();
        let countCharacter = AddCampaign.countCharacter('#message-content');
        let errorName = $('.error-name');
        let errorCost = $('.error-cost');
        let errorContent = $('.error-count-character');
        let errorDateTime = $('.error-datetime');
        let branch = $('#branchOption');
        let errorBranch = $('.error-branch');
        //Lấy thời gian hiện tại.
        var currentdate = new Date();
        let a = '';
        if (currentdate.getMonth() < 9) {
            a = '0'
        }
        var datetimeNow = +currentdate.getDate() + "-"
            + a + (currentdate.getMonth() + 1) + "-"
            + currentdate.getFullYear();
        var timeNow = currentdate.getHours() + ":"
            + currentdate.getMinutes();

        $.getJSON(laroute.route('translate'), function (json) {
            if (name == '') {
                flag = false;
                errorName.text(json['Vui lòng nhập tên chiến dịch']);
            } else {
                errorName.text('');
            }
            if (cost == '') {
                flag = false;
                errorCost.text(json['Hãy nhập chi phí cho chiến dịch']);
            } else {
                errorCost.text('');
            }
            if (content == '') {
                flag = false;
                errorContent.text(json['Vui lòng nhập nội dung']);
            } else {
                errorContent.text('');
            }

            if ($('#is_now').is(':checked')) {

            } else {
                // let kq = AddCampaign.validateTwoDates(daySent.replace(new RegExp('\\/', 'g'), '-'), datetimeNow)
                // console.log(kq);
                // console.log(AddCampaign.process(daySent));
                // console.log(AddCampaign.process(datetimeNow.replace(new RegExp('\\-', 'g'), '/')));
                let kq = (AddCampaign.validateTwoDates(AddCampaign.process(daySent), AddCampaign.process(datetimeNow.replace(new RegExp('\\-', 'g'), '/'))));
                if (kq == false) {
                    if (AddCampaign.testTime(timeNow, timeSend) == false) {
                        errorDateTime.text(json['Thời gian gửi phải lớn hơn hoặc bằng thời gian hiện tại.']);
                        flag = false;
                    } else {
                        errorDateTime.text('');
                    }
                } else {
                    errorDateTime.text('');
                }
            }
            if (branch.val() == '') {
                flag = false;
                errorBranch.text(json['Vui lòng chọn chi nhánh']);
            } else {
                errorBranch.text('');
            }
        });
        return flag;
    },
    process: function (date) {
        var parts = date.split("/");
        return new Date(parts[2], parts[1] - 1, parts[0]);
    },
    saveInfo: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            if (AddCampaign.testInput() == true) {
                let isNow = 0;
                if ($('#is_now').is(':checked')) {
                    isNow = 1;
                }
                var is_deal_created = 0;
                if ($('#is_deal_created').is(":checked")) {
                    is_deal_created = 1;
                }
                if (is_deal_created == 1) {
                    if ($('#pipeline_code').val() == '') {
                        swal(json['Hãy chọn pipeline'], '', "error");
                        return;
                    }
                    if ($('#end_date_expected').val() == '') {
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
                    url: laroute.route('admin.campaign.submit-add'),
                    method: 'POST',
                    data: {
                        name: $('#name').val(),
                        cost: $('#cost').val(),
                        contents: $('#message-content').val(),
                        dateTime: $('#day-send').val() + " " + $('#time-send').val(),
                        isNow: isNow,
                        branch: $('#branchOption').val(),
                        is_deal_created:is_deal_created,
                        end_date_expected: $('#end_date_expected').val(),
                        pipeline_code: $('#pipeline_code').val(),
                        journey_code: $('#journey_code').val(),
                        amount: $('#amount').val(),
                        arrObject: arrObject
                    },
                    success: function (data) {
                        $.getJSON(laroute.route('translate'), function (json) {
                            if (data.error == 'slug') {
                                $('.error-name').text(json['Chiến dịch đã tồn tại.'])
                            }
                            if (data.error == 0) {
                                $('.error-name').text('');
                                swal(
                                    json['Thêm chiến dịch thành công'],
                                    '',
                                    'success'
                                );
                                setTimeout(function () {
                                    window.location = laroute.route('admin.campaign.edit', {id: data.id});
                                }, 1000);
                                // window.location.href = "{{URL::to('sms-campaign-edit/"+data.id+"')}}"
                            }
                        });
                    }
                });
            }
        });
    },
    validateTwoDates: function (time1, time2) {
        return (time1 > time2);
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
    popupCreateDeal: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            let load = $('#load-modal-create').val();
            if(load == 1){
                $('#modal-create').modal('show');
            }
            else{
                $.ajax({
                    url: laroute.route('admin.campaign.sms-popup-created-deal'),
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

var dealSms = {
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
$('#check-all').click(function () {
    if ($('#check-all').is(":checked")) {
        $('.check').prop("checked", true);
    } else {
        $('.check').prop("checked", false);

    }
});

function chunkArray(myArray, chunk_size) {
    var index = 0;
    var arrayLength = myArray.length;
    var tempArray = [];

    for (index = 0; index < arrayLength; index += chunk_size) {
        myChunk = myArray.slice(index, index + chunk_size);
        // Do something if you want with the group
        tempArray.push(myChunk);
    }

    return tempArray;
}

function sttAction() {
    let stt = 1;
    $.each($('.stt'), function () {
        $(this).text(stt++);
    });
}

$('#gender').select2();
$('#branch').select2();
$('#branchOption').select2();
$('#birthday').datepicker({
    format: 'dd/mm/yyyy',
    language: 'vi',
});

$('#is_now').click(function () {
    if ($('#is_now').is(':checked')) {
        $('#day-send').prop('disabled', true);
        $('#time-send').prop('disabled', true);
    } else {
        $('#day-send').prop('disabled', false);
        $('#time-send').prop('disabled', false);
    }
});