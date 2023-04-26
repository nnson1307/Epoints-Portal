var arrayLogDelete = new Array();

var EditCampaign = {
    countCharacter: function (o) {
        let flag = true;
        let lengths = $(o).val().length;
        $('.count-character').text(lengths);
        $.getJSON(laroute.route('translate'), function (json) {
            if (lengths > 480) {
                $('.error-count-character').text(json['Vượt quá 480 ký tự.']);
                flag = false;
            } else {
                $('.error-count-character').text('');
            }
        });
        $(o).val(EditCampaign.changeAlias(o));
        return flag;
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
    valueParameter: function (o) {
        if (o == "customer-name") {
            EditCampaign.insertAtCaret("{CUSTOMER_NAME}");
        }
        if (o == "customer-birthday") {
            EditCampaign.insertAtCaret("{CUSTOMER_BIRTHDAY}");
        }
        if (o == "customer-gender") {
            EditCampaign.insertAtCaret("{CUSTOMER_GENDER}");
        }
        if (o == "full-name") {
            EditCampaign.insertAtCaret("{CUSTOMER_FULL_NAME}");
        }

        $('.count-character').text($('#message-content').val().length);
        EditCampaign.countCharacter('#message-content');
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
    validateTwoDates: function (time1, time2) {
        return (time1 > time2);
    },
    testInput: function () {
        let flag = true;
        $.getJSON(laroute.route('translate'), function (json) {

            let name = $('#name').val();
            let cost_edit = $('#cost_edit').val();
            let content = $('#message-content').val();
            let daySent = $('#day-send').val();
            let timeSend = $('#time-send').val();
            let countCharacter = EditCampaign.countCharacter('#message-content');
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
            if (name == '') {
                flag = false;
                errorName.text(json['Vui lòng nhập tên chiến dịch']);
            } else {
                errorName.text('');
            }
            if (cost_edit == '') {
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
                // let kq = EditCampaign.validateTwoDates(daySent.replace(new RegExp('\\/', 'g'), '-'), datetimeNow)
                let kq = (EditCampaign.validateTwoDates(EditCampaign.process(daySent), EditCampaign.process(datetimeNow.replace(new RegExp('\\-', 'g'), '/'))));

                if (kq == false) {
                    if (EditCampaign.testTime(timeNow, timeSend) == false) {
                        errorDateTime.text(json['Thời gian gửi phải lớn hơn hoặc bằng thời gian hiện tại.']);
                        flag = false;
                    } else {
                        errorDateTime.text('');
                    }
                } else {
                    errorDateTime.text('');
                }
            }
            if ($('#is_now').is(':checked')) {

            } else {
                if (daySent == '' || timeSend == '') {
                    errorDateTime.text(json['Thời gian gửi phải lớn hơn hoặc bằng thời gian hiện tại.']);
                    flag = false;
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
    saveChange: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            let flag = true;
            let name = $('#name').val();
            let cost_edit = $('#cost_edit').val();
            let content = $('#message-content').val();
            let daySent = $('#day-send').val();
            let timeSend = $('#time-send').val();
            let countCharacter = EditCampaign.countCharacter('#message-content');
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
            if (name == '') {
                flag = false;
                errorName.text(json['Vui lòng nhập tên chiến dịch']);
            } else {
                errorName.text('');
            }
            if (cost_edit == '') {
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
                // let kq = EditCampaign.validateTwoDates(daySent.replace(new RegExp('\\/', 'g'), '-'), datetimeNow)
                let kq = (EditCampaign.validateTwoDates(EditCampaign.process(daySent), EditCampaign.process(datetimeNow.replace(new RegExp('\\-', 'g'), '/'))));

                if (kq == false) {
                    if (EditCampaign.testTime(timeNow, timeSend) == false) {
                        errorDateTime.text(json['Thời gian gửi phải lớn hơn hoặc bằng thời gian hiện tại.']);
                        flag = false;
                    } else {
                        errorDateTime.text('');
                    }
                } else {
                    errorDateTime.text('');
                }
            }
            if ($('#is_now').is(':checked')) {

            } else {
                if (daySent == '' || timeSend == '') {
                    errorDateTime.text(json['Thời gian gửi phải lớn hơn hoặc bằng thời gian hiện tại.']);
                    flag = false;
                }
            }
            if (branch.val() == '') {
                flag = false;
                errorBranch.text(json['Vui lòng chọn chi nhánh']);
            } else {
                errorBranch.text('');
            }
            if (flag) {
                let isNow = 0;
                if ($('#is_now').is(':checked')) {
                    isNow = 1;
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
                    url: laroute.route('admin.campaign.submit-edit'),
                    method: 'POST',
                    data: {
                        name: $('#name').val(),
                        contents: $('#message-content').val(),
                        // dateTime: $('#day-send').val() + " " + $('#time-send').val(),
                        dateSend: $('#day-send').val(),
                        timeSend: $('#time-send').val(),
                        isNow: isNow,
                        id: $('#id').val(),
                        cost:$('#cost_edit').val(),
                        is_deal_created:is_deal_created,
                        end_date_expected: $('#end_date_expected').val(),
                        pipeline_code: $('#pipeline_code').val(),
                        journey_code: $('#journey_code').val(),
                        amount: $('#amount').val(),
                        arrObject: arrObject
                    },
                    success: function (data) {
                        if (data.error == 'slug') {
                            $('.error-name').text(json['Chiến dịch đã tồn tại'])
                        }
                        if (data.error == 0) {
                            $('.error-name').text('');
                            swal(
                                json['Cập nhật chiến dịch thành công'],
                                '',
                                'success'
                            );
                        }
                    }
                });
            }
        });
    },
    closeModalDeal: function () {
        $('#load-modal-edit').val(0);
        $('#modal-edit').modal('hide');
    },
    saveModalDeal: function(){
        $('#load-modal-edit').val(1);
        $('#modal-edit').modal('hide');
    },
    changeCreateDeal: function(){
        $('#switch_deal_created').val(1); // check có tắt chỗ tạo deal
        if($('#is_deal_created').is(":checked")){
            $('#popup_create_deal').removeAttr('hidden');
        }
        else{
            $('#popup_create_deal').attr('hidden', true);
            $('#load-modal-edit').val(0);
            $('#load-modal-create').val(0);
        }
    },
    popupEditDeal: function(campaign_id){
        $.getJSON(laroute.route('translate'), function (json) {
            if($('#switch_deal_created').val() == '1'){ // có bật/tắt => mở popup mới hoàn toàn
                $('#my-modal-edit').html('');
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
            }
            else{
                $('#my-modal-create').html('');
                let load = $('#load-modal-edit').val();
                if(load == 1){
                    $('#modal-edit').modal('show');
                }
                else{
                    $.ajax({
                        url: laroute.route('admin.campaign.sms-popup-edit-deal'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            'campaign_id' : campaign_id
                        },
                        success: function (res) {
                            $('#my-modal-edit').html(res.html);
                            $('#modal-edit').modal('show');

                            $('#pipeline_code').select2({
                                placeholder: json['Chọn pipeline']
                            });

                            $('#journey_code').select2({
                                placeholder: json['Chọn hành trình']
                            });


                            $(".object_quantity").TouchSpin({
                                initval: 1,
                                min: 1,
                                buttondown_class: "btn btn-default down btn-ct",
                                buttonup_class: "btn btn-default up btn-ct"

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
            }
        });
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
                // $.getJSON(laroute.route('translate'), function (json) {
                    $.map(data.result, function (a){
                        if (a.gender == null) {
                            a.gender = 'other';
                        }
                        if (a.phone == null) {
                            a.phone = '';
                        }
                        if (a.birthday == null) {
                            a.birthday = '';
                        }
                        if (a.branch_name == null) {
                            a.branch_name = '';
                        }
                        var tpl = $('#customer-list-tpl').html();
                        // tpl = tpl.replace(/{name}/g, a.full_name.substr(0,50));
                        tpl = tpl.replace(/{name}/g, a.full_name);
                        tpl = tpl.replace(/{name_title}/g, a.full_name);
                        tpl = tpl.replace(/{customer_id}/g, a.customer_id);
                        tpl = tpl.replace(/{birthday}/g, a.birthday);
                        tpl = tpl.replace(/{phone}/g, a.phone);
                        // tpl = tpl.replace(/{gender}/g, a.gender);
                        tpl = tpl.replace(/{gender}/g, a.gender_name);
                        tpl = tpl.replace(/{branchName}/g, a.branch_name);
                        if(a.is_checked == 1) {
                            tpl = tpl.replace(/{is_checked}/g, 'checked');
                        } else {
                            tpl = tpl.replace(/{is_checked}/g, '');
                        }
                        $('.customer_list').append(tpl);
                    });
                // });
                let stt = 1;
                $.each($('.stt2'), function () {
                    $(this).text(stt++);
                });
            }
        })
    },
    searchCustomerGroup: function () {
        $.ajax({
            url: laroute.route('admin.sms.search-customer-group'),
            method: "POST",
            data: {
                filter_type_group: $('#filter_type_group option:selected').val(),
                customer_group_filter: $('#customer_group_filter option:selected').val(),
            },
            success: function (data) {
                $('.customer_group_list').empty();
                $.map(data.result, function (a){
                    if (a.gender == null) {
                        a.gender = 'other';
                    }
                    if (a.phone == null) {
                        a.phone = '';
                    }
                    if (a.birthday == null) {
                        a.birthday = '';
                    }
                    if (a.branch_name == null) {
                        a.branch_name = '';
                    }
                    var tpl = $('#customer-group-list-tpl').html();
                    tpl = tpl.replace(/{name}/g, a.full_name);
                    tpl = tpl.replace(/{name_title}/g, a.full_name);
                    tpl = tpl.replace(/{customer_id}/g, a.customer_id);
                    tpl = tpl.replace(/{birthday}/g, a.birthday);
                    tpl = tpl.replace(/{phone}/g, a.phone);
                    tpl = tpl.replace(/{gender}/g, a.gender_name);
                    tpl = tpl.replace(/{branchName}/g, a.branch_name);
                    if(a.is_checked == 1) {
                        tpl = tpl.replace(/{is_checked}/g, 'checked');
                    } else {
                        tpl = tpl.replace(/{is_checked}/g, '');
                    }
                    $('.customer_group_list').append(tpl);
                });
                // });
                let stt = 1;
                $.each($('.stt2'), function () {
                    $(this).text(stt++);
                });
            }
        })
    },
    searchCusGroupFilter: function(){

        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('admin.sms.search-customer-group-filter'),
                method: "POST",
                data: {
                    filter_type_group: $('#filter_type_group option:selected').val(),
                },
                success: function (data) {
                    var stringHtml= '';
                    if(data.length == 0){
                        stringHtml += `<option value="">${json['Chọn nhóm khách hàng']}</option>`;
                    }
                    else{
                        data.forEach(e=>{
                            stringHtml += `<option value="${e.id}">${e.name}</option>`;
                        });
                    }
                    $('#customer_group_filter').html('');
                    $('#customer_group_filter').append(stringHtml);
                }
            })
        });
    },
    sttAction: function () {
        let stt = 1;
        $.each($('.stt'), function () {
            $(this).text(stt++);
        });
    },

    chooseCustomer: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var append = [];
            $.each($('.customer_list tr input[name="check"]:checked').parentsUntil("tbody"), function () {
                var $tds = $(this).find("input[name='customer_id']");
                $.each($tds, function () {
                    append.push($(this).val());
                });
            });
            if (append != '') {
                $.ajax({
                    url: laroute.route('admin.campaign.append-table'),
                    dataTye: 'JSON',
                    method: 'POST',
                    data: {
                        list: append,
                        campaign_id: $('#id').val(),
                    },
                    success: function (res) {
                        $.map(res.data_list, function (a) {
                            if(a.phone != null && !$('.table_list_body tr td').text().toString().includes(a.phone)){
                                // var stts = $('.customer_list tr').length;
                                var stts = $('.table_list_body tr').length;
                                var tpl = $('#customer-list-append').html();
                                tpl = tpl.replace(/{stt}/g, stts + 1);
                                tpl = tpl.replace(/{name}/g, a.customer_name);
                                if (a.email != null) {
                                    tpl = tpl.replace(/{email}/g, a.email);
                                } else {
                                    tpl = tpl.replace(/{email}/g, '');
                                }
                                tpl = tpl.replace(/{content}/g, a.content);
                                tpl = tpl.replace(/{phone}/g, a.phone);
                                tpl = tpl.replace(/{type_customer}/g, 'customer');
                                tpl = tpl.replace(/{object_id}/g, a.customer_id);
                                $('.table_list_body').append(tpl);
                            }
                        });
                        $('#add-customer').modal('hide');
                    }
                });
            } else {
                $('.error_append').text(json['Vui lòng chọn khách hàng']);
            }
        });

    },

    chooseCustomerGroup: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var append = [];
            $.each($('.customer_group_list tr input[name="check-group"]:checked').parentsUntil("tbody"), function () {
                var $tds = $(this).find("input[name='customer_id']");
                $.each($tds, function () {
                    append.push($(this).val());
                });
            });
            if (append != '') {
                $.ajax({
                    url: laroute.route('admin.campaign.append-table'),
                    dataTye: 'JSON',
                    method: 'POST',
                    data: {
                        list: append,
                        campaign_id: $('#id').val(),
                    },
                    success: function (res) {
                        $.map(res.data_list, function (a) {
                            if(a.phone != null && !$('.table_list_body tr td').text().toString().includes(a.phone)){
                                var stts = $('.table_list_body tr').length;
                                var tpl = $('#customer-list-append').html();
                                tpl = tpl.replace(/{stt}/g, stts + 1);
                                tpl = tpl.replace(/{name}/g, a.customer_name);
                                if (a.email != null) {
                                    tpl = tpl.replace(/{email}/g, a.email);
                                } else {
                                    tpl = tpl.replace(/{email}/g, '');
                                }
                                tpl = tpl.replace(/{content}/g, a.content);
                                tpl = tpl.replace(/{phone}/g, a.phone);
                                tpl = tpl.replace(/{type_customer}/g, 'customer');
                                tpl = tpl.replace(/{object_id}/g, a.customer_id);
                                $('.table_list_body').append(tpl);
                            }
                        });
                        $('#add-customer-group').modal('hide');
                    }
                });
            } else {
                $('.error_append').text(json['Vui lòng chọn khách hàng']);
            }
        });

    },
    chooseCustomerLead: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var append = [];
            $.each($('.customer_lead_list tr input[name="check-lead"]:checked').parentsUntil("tbody"), function () {
                var $tds = $(this).find("input[name='customer_lead_id']");
                $.each($tds, function () {
                    append.push($(this).val());
                });
            });
            if (append != '') {
                $.ajax({
                    url: laroute.route('admin.campaign.append-table'),
                    dataTye: 'JSON',
                    method: 'POST',
                    data: {
                        list: append,
                        campaign_id: $('#id').val(),
                    },
                    success: function (res) {
                        $.map(res.data_list, function (a) {
                            if(a.phone != null && !$('.table_list_body tr td').text().toString().includes(a.phone)){
                                var stts = $('.table_list_body tr').length;
                                var tpl = $('#customer-list-append').html();
                                tpl = tpl.replace(/{stt}/g, stts + 1);
                                tpl = tpl.replace(/{name}/g, a.customer_name);
                                if (a.email != null) {
                                    tpl = tpl.replace(/{email}/g, a.email);
                                } else {
                                    tpl = tpl.replace(/{email}/g, '');
                                }
                                tpl = tpl.replace(/{content}/g, a.content);
                                tpl = tpl.replace(/{phone}/g, a.phone);
                                tpl = tpl.replace(/{type_customer}/g, 'lead');
                                tpl = tpl.replace(/{object_id}/g, a.customer_lead_id);
                                $('.table_list_body').append(tpl);
                            }
                        });
                        $('#add-customer-lead').modal('hide');
                    }
                });
            } else {
                $('.error_append').text(json['Vui lòng chọn khách hàng']);
            }
        });

    },
    // chooseCustomer: function () {
    //     var array = [];
    //     $.each($('.customer_list tr .check:checked').parentsUntil("tbody"), function () {
    //         var $tds = $(this).find("td input");
    //         $.each($tds, function () {
    //             array.push($(this).val());
    //         });
    //     });
    //     $.getJSON(laroute.route('translate'), function (json) {
    //         if (array == "") {
    //             $('.error-add-customer').text(json['Vui lòng thêm khách hàng.']);
    //         } else {
    //
    //             var result = chunkArray(array, 6);
    //             let messageContent = $('#message-content').val();
    //
    //             $.map(result, function (value) {
    //                 let gioitinh = json['Anh/Chi'];
    //                 if (value[4] == 'Nữ' || value[4] == 'female') {
    //                     gioitinh = json['Chi'];
    //                 } else if (value[4] == json['Nam'] || value[4] == 'male') {
    //                     gioitinh = json['Anh'];
    //                 }
    //
    //                 let content = messageContent.replace(/{CUSTOMER_NAME}/g, " " + value[1].slice(value[1].lastIndexOf(' ') + 1, 20) + " ").replace(/{CUSTOMER_FULL_NAME}/g, " " + value[1] + " ").replace(/{CUSTOMER_BIRTHDAY}/g, " " + value[3] + " ").replace(/{CUSTOMER_GENDER}/g, " " + gioitinh + " ");
    //                 var tpl = $('#customer-list-append').html();
    //                 tpl = tpl.replace(/{customer_id}/g, value[0]);
    //                 tpl = tpl.replace(/{name}/g, value[1]);
    //                 tpl = tpl.replace(/{phone}/g, value[2]);
    //                 tpl = tpl.replace(/{content}/g, EditCampaign.xoa_dau(content));
    //                 $('.table-list-customer').append(tpl);
    //             });
    //             EditCampaign.sttAction();
    //             $('#add-customer').modal('hide');
    //         }
    //     });
    // },
    xoa_dau: function (str) {
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
    removeCustomer: function (o) {
        $(o).closest('tr').remove();
        EditCampaign.sttAction();
    },
    saveLog: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var customer = $('.table-list-customer tr').length;
            let errorCustomerss = $('.error-customer-');
            let flag = true;
            if (customer < 1) {
                flag = false;
                errorCustomerss.text(json['Vui lòng chọn khách hàng'])
            } else {
                errorCustomerss.text('');
            }
            if (flag == true) {
                var array = [];
                $.each($('.table-list-customer tr .aaaa').parentsUntil("tbody"), function () {
                    var $tds = $(this).find("td input");
                    $.each($tds, function () {
                        array.push($(this).val());
                    });
                });

                $.ajax({
                    url: laroute.route('admin.campaign.sms-campaign-save-log'),
                    method: 'POST',
                    data: {
                        array: array,
                        id: $('#id').val(),
                        arrayLogDelete: arrayLogDelete
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.error == 0) {
                            swal(
                                json['Chỉnh sửa thành công'],
                                '',
                                'success'
                            );
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    }
                });
            }
        });
    },
    removeCustomerLog: function (th, id = null) {
        $(th).closest('tr').remove();
        arrayLogDelete.push(id);
        EditCampaign.sttAction();
        $.ajax({
            url: laroute.route('admin.campaign.remove-log'),
            method: 'POST',
            data: {id: $(th).attr('data-value')},
            success: function (data) {
                // if (data.error == 0) {
                //     EditCampaign.sttAction();
                //     $(th).closest('tr').remove();
                // }
            }
        })
    },
    showNameFile: function () {
        var fileNamess = $('input[type=file]').val();
        $('.file-name-excels').val(fileNamess);
    },
    chooseFile: function () {
        var file_data = $('#file_excel').prop('files')[0];
        var fileNamess = $('input[type=file]').val();
        if (fileNamess != '') {
            $('.error-file-name-excels').text('');
            var form_data = new FormData();
            form_data.append('file', file_data);

            $.ajax({
                url: laroute.route("admin.campaign.import-file-excel"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    let messageContent = $('#message-content').val();

                    $.map(data, function (value) {

                        let content = messageContent.replace(/{CUSTOMER_NAME}/g, " " + value['customer_name'].slice(value['customer_name'].lastIndexOf(' ') + 1, 20) + " ").replace(/{CUSTOMER_FULL_NAME}/g, " " + value['customer_name'] + " ").replace(/{CUSTOMER_BIRTHDAY}/g, " " + value['birthday'] + " ").replace(/{CUSTOMER_GENDER}/g, " " + value['gender'] + " ");
                        var tpl = $('#customer-list-append').html();
                        tpl = tpl.replace(/{customer_id}/g, '');
                        tpl = tpl.replace(/{name}/g, value['customer_name']);
                        tpl = tpl.replace(/{phone}/g, value['phone']);
                        tpl = tpl.replace(/{content}/g, EditCampaign.xoa_dau(content));
                        $('.table-list-customer').append(tpl);
                    });
                    EditCampaign.sttAction();
                    $('#modalChooseFile').modal('hide');
                }
            });
        } else {
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error-file-name-excels').text(json['Chưa có tệp']);
            });
        }
    },
    removeAllInput: function (thi) {
        $(thi).val('');
    },
    emptyListCustomer: function () {
        $('.customer_list').empty();
        $('#check-all').prop("checked", false);
    },
    emptyListCustomerGroup: function () {
        $('.customer_group_list').empty();
        $('#check-all-customer-group').prop("checked", false);
    },
    emptyListCustomerLead: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.customer_lead_list_body').empty();
            $('#lead_type_customer').select2();
            $('#lead_customer_source').select2();
            $('#lead_sale_status').select2();
            $('#lead_pipeline_code').select2();
            $('#lead_pipeline_code').change(function () {
                $.ajax({
                    url: laroute.route('customer-lead.load-option-journey'),
                    dataType: 'JSON',
                    data: {
                        pipeline_code: $('#lead_pipeline_code').val(),
                    },
                    method: 'POST',
                    success: function (res) {
                        $('#lead_journey_code').empty();
                        $('#lead_journey_code').append('<option value=""></option>');
                        $.map(res.optionJourney, function (a) {
                            $('#lead_journey_code').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                        });
                    }
                });
            });
            $('#lead_journey_code').select2({
                placeholder: json['Chọn hành trình'],
            });

            $('#add-customer-lead').modal('show');
            EditCampaign.search_lead();
        });
    },
    search_lead: function () {
        $('.customer_lead_list_body').empty();
        $.ajax({
            url: laroute.route('admin.campaign.search-customer-lead'),
            method: "POST",
            data: {
                search: $('#lead_search').val(),
                type_customer: $('#lead_type_customer').val(),
                customer_source: $('#lead_customer_source').val(),
                sale_status: $('#lead_sale_status').val(),
                pipeline_code: $('#lead_pipeline_code').val(),
                journey_code: $('#lead_journey_code').val(),
            },
            success: function (res) {
                $('.customer_lead_list_body').empty();
                $.map(res.arr_data, function (a) {
                    // $.getJSON(laroute.route('translate'), function (json) {
                    var stts = $('.customer_lead_list_body tr').length;
                    var tpl = $('#customer-lead-list-tpl').html();
                    tpl = tpl.replace(/{stt}/g, stts + 1);
                    tpl = tpl.replace(/{name}/g, a.full_name);
                    tpl = tpl.replace(/{customer_lead_id}/g, a.customer_lead_id);
                    tpl = tpl.replace(/{sale_name}/g, a.sale_name);
                    tpl = tpl.replace(/{phone}/g, a.phone);
                    tpl = tpl.replace(/{customer_type}/g, a.customer_type);
                    tpl = tpl.replace(/{object_id}/g, a.customer_lead_id);
                    tpl = tpl.replace(/{customer_source_name}/g, a.customer_source_name);
                    tpl = tpl.replace(/{pipeline_name}/g, a.pipeline_name);
                    tpl = tpl.replace(/{journey_name}/g, a.journey_name);

                    if(a.is_checked == 1) {
                        tpl = tpl.replace(/{is_checked}/g, 'checked');
                    } else {
                        tpl = tpl.replace(/{is_checked}/g, '');
                    }
                    $('.customer_lead_list_body').append(tpl);
                    // });
                });
            }
        });
    },
    checkCustomer:function (customer_id,check = 0,status = 'add') {
        $.ajax({
            url: laroute.route("admin.campaign.check-customer"),
            method: "POST",
            dataType: 'JSON',
            data: {
                customer_id : customer_id,
                check : check,
                status : status
            },
            success: function (res) {

            }
        });
    },
    checkCustomerLead:function (customer_lead_id,check = 0,status = 'add') {
        $.ajax({
            url: laroute.route("admin.campaign.check-customer-lead"),
            method: "POST",
            dataType: 'JSON',
            data: {
                customer_lead_id : customer_lead_id,
                check : check,
                status : status
            },
            success: function (res) {

            }
        });
    }
};


$('.check_all_lead').click(function () {
    if ($('.check_all_lead').is(":checked")) {
        $('input[name="check-lead"]').prop("checked", true);
    } else {
        $('input[name="check-lead"]').prop("checked", false);
    }
    var append = [];
    $.each($('.customer_lead_list tr input[name="check-lead"]:checked').parentsUntil("tbody"), function () {
        var $tds = $(this).find("input[name='customer_lead_id']");
        $.each($tds, function () {
            append.push(parseInt($(this).val()));
        });
    });
    if(append.length != 0) {
        EditCampaign.checkCustomerLead(append,1,'add');
    } else {
        var append = [];
        $.each($('.customer_lead_list tr input[name="check-lead"]').parentsUntil("tbody"), function () {
            var $tds = $(this).find("input[name='customer_lead_id']");
            $.each($tds, function () {
                append.push(parseInt($(this).val()));
            });
        });
        EditCampaign.checkCustomerLead(append,1,'delete');
    }
});
$('#check-all-customer-group').click(function () {
    if ($('#check-all-customer-group').is(":checked")) {
        $('input[name="check-group"]').prop("checked", true);
    } else {
        $('input[name="check-group"]').prop("checked", false);
    }
    var append = [];
    $.each($('.customer_group_list tr input[name="check-group"]:checked').parentsUntil("tbody"), function () {
        var $tds = $(this).find("input[name='customer_id']");
        $.each($tds, function () {
            append.push(parseInt($(this).val()));
        });
    });
    if(append.length != 0) {
        EditCampaign.checkCustomer(append,1,'add');
    } else {
        var append = [];
        $.each($('.customer_group_list tr input[name="check-group"]').parentsUntil("tbody"), function () {
            var $tds = $(this).find("input[name='customer_id']");
            $.each($tds, function () {
                append.push(parseInt($(this).val()));
            });
        });
        EditCampaign.checkCustomer(append,1,'delete');
    }
});

$(document).ready(function () {
    $('#day-send').val($('#hidden-daySent').val());
    $('#add-customer').on('shown.bs.modal', function (e) {
        EditCampaign.searchCustomer();
    });
    $('#add-customer').on('hidden.bs.modal', function (e) {
        $.ajax({
            url: laroute.route('admin.campaign.delete-session'),
            dataTye: 'JSON',
            method: 'POST',
            success: function (res) {
            }
        });
    })
    $('#add-customer-group').on('shown.bs.modal', function (e) {
        EditCampaign.searchCustomerGroup();
    });
    $('#add-customer-group').on('hidden.bs.modal', function (e) {
        $.ajax({
            url: laroute.route('admin.campaign.delete-session'),
            dataTye: 'JSON',
            method: 'POST',
            success: function (res) {
            }
        });
    });
    $('#add-customer-lead').on('hidden.bs.modal', function (e) {
        $.ajax({
            url: laroute.route('admin.campaign.delete-session'),
            dataTye: 'JSON',
            method: 'POST',
            success: function (res) {
            }
        });
    });

    new AutoNumeric.multiple('#cost_edit' ,{
        currencySymbol : '',
        decimalCharacter : '.',
        digitGroupSeparator : ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });
    $('#check_all').click(function () {
        if ($('#check_all').is(":checked")) {
            $('input[name="check"]').prop("checked", true);
        } else {
            $('input[name="check"]').prop("checked", false);
        }
        var append = [];
        $.each($('.customer_list tr input[name="check"]:checked').parentsUntil("tbody"), function () {
            var $tds = $(this).find("input[name='customer_id']");
            $.each($tds, function () {
                append.push(parseInt($(this).val()));
            });
        });
        if(append.length != 0) {
            EditCampaign.checkCustomer(append,1,'add');
        } else {
            var append = [];
            $.each($('.customer_list tr input[name="check"]').parentsUntil("tbody"), function () {
                var $tds = $(this).find("input[name='customer_id']");
                $.each($tds, function () {
                    append.push(parseInt($(this).val()));
                });
            });
            EditCampaign.checkCustomer(append,1,'delete');
        }
    });
})


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

$("#time-send").timepicker({
    minuteStep: 15,
    defaultTime: $('#hidden-timeSent').val(),
    showMeridian: !1,
    snapToStep: !0,
});
$('#day-send').datepicker({
    format: "dd/mm/yyyy",
    startDate: '0d',
    language: 'vi',
}).datepicker("setDate", $('#hidden-daySent').val());
$('#is_now').click(function () {
    if ($('#is_now').is(':checked')) {
        $('#day-send').prop('disabled', true);
        $('#time-send').prop('disabled', true);
        $('.error-datetime').text('');
    } else {
        $('#day-send').prop('disabled', false);
        $('#time-send').prop('disabled', false);
    }
});
EditCampaign.countCharacter('#message-content');

$('#filter_type_group').select2();
$('#customer_group_filter').select2();

$('#gender').select2();
$('#branch').select2();
$('#branchOption').select2();
$('#birthday').datepicker({
    format: 'dd/mm/yyyy',
    language: 'vi',
});
$('#check-all').click(function () {
    if ($('#check-all').is(":checked")) {
        $('.check').prop("checked", true);
    } else {
        $('.check').prop("checked", false);
    }
    var append = [];
    $.each($('.customer_list tr input[name="check"]:checked').parentsUntil("tbody"), function () {
        var $tds = $(this).find("input[name='customer_id']");
        $.each($tds, function () {
            append.push(parseInt($(this).val()));
        });
    });
    if(append.length != 0) {
        EditCampaign.checkCustomer(append,1,'add');
    } else {
        var append = [];
        $.each($('.customer_list tr input[name="check"]').parentsUntil("tbody"), function () {
            var $tds = $(this).find("input[name='customer_id']");
            $.each($tds, function () {
                append.push(parseInt($(this).val()));
            });
        });
        EditCampaign.checkCustomer(append,1,'delete');
    }
});
$('#check-all-customer_group').click(function () {
    if ($('#check-all-customer_group').is(":checked")) {
        $('.check-group').prop("checked", true);
    } else {
        $('.check-group').prop("checked", false);

    }
});
