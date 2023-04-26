$(document).ready(function () {
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
    $('#birthday').datepicker({
        format: 'dd/mm/yyyy',
        language: 'vi',
    });
    $.getJSON(laroute.route('translate'), function (json) {
        $('#branch_id').select2({
            placeholder: json['Chi nhánh'],
            allowClear: true
        });
        $('#gender').select2({
        placeholder:json['Giới tính'],
        allowClear:true
        });
        $('.check_all').click(function () {
            if ($('.check_all').is(":checked")) {
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
                edit.checkCustomer(append,1,'add');
            } else {
                var append = [];
                $.each($('.customer_list tr input[name="check"]').parentsUntil("tbody"), function () {
                    var $tds = $(this).find("input[name='customer_id']");
                    $.each($tds, function () {
                        append.push(parseInt($(this).val()));
                    });
                });
                edit.checkCustomer(append,1,'delete');
            }
        });
        $('.check_all_group').click(function () {
            if ($('.check_all_group').is(":checked")) {
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
                edit.checkCustomer(append,1,'add');
            } else {
                var append = [];
                $.each($('.customer_group_list tr input[name="check-group"]').parentsUntil("tbody"), function () {
                    var $tds = $(this).find("input[name='customer_id']");
                    $.each($tds, function () {
                        append.push(parseInt($(this).val()));
                    });
                });
                edit.checkCustomer(append,1,'delete');
            }
        });
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
                edit.checkCustomerLead(append,1,'add');
            } else {
                var append = [];
                $.each($('.customer_lead_list tr input[name="check-lead"]').parentsUntil("tbody"), function () {
                    var $tds = $(this).find("input[name='customer_lead_id']");
                    $.each($tds, function () {
                        append.push(parseInt($(this).val()));
                    });
                });
                edit.checkCustomerLead(append,1,'delete');
            }
        });
        $('.content').summernote({
            height:200,
            placeholder: json['Nhập nội dung'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });
        $('.note-btn').attr('title', '');
        $('#content').summernote('code',$('#content_hidden').val());
        $('.content').summernote('disable');
        $('#branch_id_edit').select2({
            placeholder: json['Hãy chọn chi nhánh']
        });

        new AutoNumeric.multiple('#cost_edit' ,{
            currencySymbol : '',
            decimalCharacter : '.',
            digitGroupSeparator : ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });
    });
});
var edit = {
    append_para: function (param) {
        var text = param;
        $('#content').summernote('pasteHTML', text);
    },
    submit_edit: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    name_edit: {
                        required: true
                    },
                    content: {
                        required: true
                    },
                    branch_id_edit:{
                        required:true
                    },
                    cost_edit:{
                        required:true
                    }
                },
                messages: {
                    name_edit: {
                        required: json['Hãy nhập tên chiến dịch']
                    },
                    content: {
                        required: json['Hãy nhập nội dung tin nhắn']
                    },
                    branch_id_edit:{
                        required:json['Hãy chọn chi nhánh']
                    },
                    cost_edit:{
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
                url: laroute.route('admin.email.submit-edit'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    campaign_id: $('#campaign_id').val(),
                    name: $('#name_edit').val(),
                    content_campaign: $('#content').summernote('code'),
                    is_now: $('#is_now').val(),
                    day_sent: $('#day_sent').val(),
                    time_sent: $('#time_sent').val(),
                    branch_id_edit:$('#branch_id_edit').val(),
                    cost:$('#cost_edit').val(),
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
                    if(res.error_content==1)
                    {
                        $('.error_content_html').text(json['Nội dung không hợp lệ']);
                    }else{
                        $('.error_content_html').text('');
                    }
                    if (res.success == 1) {
                        swal(json["Chỉnh sửa chiến dịch thành công"], "", "success");
                        if (res.is_now == 1) {
                            $('#day_sent').val(res.day_sent);
                            $('#time_sent').val(res.time_sent);
                        }
                    }
                }
            })
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
    popupEditLead: function(email_campaign_id){
        $.getJSON(laroute.route('translate'), function (json) {
            if($('#switch_deal_created').val() == '1'){ // có bật/tắt => mở popup mới hoàn toàn
                $('#my-modal-edit').html('');
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
            else{
                $('#my-modal-create').html('');
                let load = $('#load-modal-edit').val();
                if(load == 1){
                    $('#modal-edit').modal('show');
                }
                else{
                    $.ajax({
                        url: laroute.route('admin.email.email-popup-edit-deal'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            'email_campaign_id' : email_campaign_id
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
    modal_customer: function () {
        $('#add-customer').modal('show');
        $('#name').val('');
        $('#birthday').val('');
        $('#gender').val('');
        $('#branch_id').val('');
        $('.customer_list_body').empty();
        $('.error_append').text('');
        $.ajax({
            url: laroute.route('admin.email.delete-session'),
            dataTye: 'JSON',
            method: 'POST',
            success: function (res) {
            }
        });
        edit.search();
    },
    modal_customer_group: function () {

        $.getJSON(laroute.route('translate'), function (json) {
            $('#add-customer-group').modal('show');
            $('#filter_type_group').val('');
            $('#customer_group_filter').val('');
            $('#filter_type_group').select2();
            $('#customer_group_filter').select2({
                placeholder: json['Chọn nhóm khách hàng']
            });
            $('.customer_group_list_body').empty();
            $.ajax({
                url: laroute.route('admin.email.delete-session'),
                dataTye: 'JSON',
                method: 'POST',
                success: function (res) {
                }
            });
            edit.search_group();
        });

    },
    modal_customer_lead: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#add-customer-lead').modal('show');
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
            $('.customer_lead_list_body').empty();

            edit.search_lead();
        });

    },
    search: function () {
        var arrId = [];
        $.each($('.customer_list tr input[name="check"]:checked').parentsUntil("tbody"), function () {
            var $tds = $(this).find("input[name='customer_id']");
            $.each($tds, function () {
                arrId.push($(this).val());
            });
        });
        $('.customer_list_body').empty();
        var data = $('#name').val();
        var birthday = $('#birthday').val();
        var gender = $('#gender').val();
        var branch = $('#branch_id').val();
        $.ajax({
            url: laroute.route('admin.email.search-customer'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                data: data,
                birthday: birthday,
                gender: gender,
                branch: branch,
                arrId : arrId
            },
            success: function (res) {
                $.map(res.arr_data, function (a) {
                    // $.getJSON(laroute.route('translate'), function (json) {
                        var stts = $('.customer_list_body tr').length;
                        var tpl = $('#customer-list-tpl').html();
                        tpl = tpl.replace(/{stt}/g, stts + 1);
                        // tpl = tpl.replace(/{name}/g, a.full_name);
                        // tpl = tpl.replace(/{name}/g, a.full_name.substr(0,50));
                        tpl = tpl.replace(/{name}/g, a.full_name);
                        tpl = tpl.replace(/{name_title}/g, a.full_name);
                        tpl = tpl.replace(/{customer_id}/g, a.customer_id);
                        if(a.birthday!=undefined)
                        {
                            tpl = tpl.replace(/{birthday}/g, a.birthday);
                        }else{
                            tpl = tpl.replace(/{birthday}/g, '');
                        }

                        // if (a.gender == 'male') {
                        //     tpl = tpl.replace(/{gender}/g, json['Nam']);
                        // }
                        // else if (a.gender == 'female') {
                        //     tpl = tpl.replace(/{gender}/g, json['Nữ']);
                        // }
                        // else {
                        //     tpl = tpl.replace(/{gender}/g, json['Khác']);
                        // }
                        tpl = tpl.replace(/{gender}/g, a.gender_name);
                        if (a.email != null) {
                            tpl = tpl.replace(/{email}/g, a.email);
                        } else {
                            tpl = tpl.replace(/{email}/g, '');
                        }
                        if(a.branch_name!=null)
                        {
                            tpl = tpl.replace(/{branch_name}/g, a.branch_name);
                        }else{
                            tpl = tpl.replace(/{branch_name}/g, '');
                        }

                        if(a.is_checked == 1) {
                            tpl = tpl.replace(/{is_checked}/g, 'checked');
                        } else {
                            tpl = tpl.replace(/{is_checked}/g, '');
                        }

                        $('.customer_list_body').append(tpl);
                    // });
                });
            }
        });
    },
    search_group: function () {
        $('.customer_group_list_body').empty();
        $.ajax({
            url: laroute.route('admin.email.search-customer-group'),
            method: "POST",
            data: {
                filter_type_group: $('#filter_type_group option:selected').val(),
                customer_group_filter: $('#customer_group_filter option:selected').val(),
            },
            success: function (res) {
                $('.customer_group_list_body').empty();
                $.map(res.arr_data, function (a) {
                    // $.getJSON(laroute.route('translate'), function (json) {
                    var stts = $('.customer_group_list_body tr').length;
                    var tpl = $('#customer-group-list-tpl').html();
                    tpl = tpl.replace(/{stt}/g, stts + 1);
                    // tpl = tpl.replace(/{name}/g, a.full_name);
                    // tpl = tpl.replace(/{name}/g, a.full_name.substr(0,50));
                    tpl = tpl.replace(/{name}/g, a.full_name);
                    tpl = tpl.replace(/{name_title}/g, a.full_name);
                    tpl = tpl.replace(/{customer_id}/g, a.customer_id);
                    if(a.birthday!=undefined)
                    {
                        tpl = tpl.replace(/{birthday}/g, a.birthday);
                    }else{
                        tpl = tpl.replace(/{birthday}/g, '');
                    }
                    tpl = tpl.replace(/{gender}/g, a.gender_name);
                    if (a.email != null) {
                        tpl = tpl.replace(/{email}/g, a.email);
                    } else {
                        tpl = tpl.replace(/{email}/g, '');
                    }
                    if(a.branch_name!=null)
                    {
                        tpl = tpl.replace(/{branch_name}/g, a.branch_name);
                    }else{
                        tpl = tpl.replace(/{branch_name}/g, '');
                    }

                    if(a.is_checked == 1) {
                        tpl = tpl.replace(/{is_checked}/g, 'checked');
                    } else {
                        tpl = tpl.replace(/{is_checked}/g, '');
                    }
                    $('.customer_group_list_body').append(tpl);
                    // });
                });
            }
        });
    },
    search_lead: function () {
        $('.customer_lead_list_body').empty();
        $.ajax({
            url: laroute.route('admin.email.search-customer-lead'),
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
                    if (a.email != null) {
                        tpl = tpl.replace(/{email}/g, a.email);
                    } else {
                        tpl = tpl.replace(/{email}/g, '');
                    }
                    tpl = tpl.replace(/{customer_type}/g, a.customer_type);
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
    click_append: function () {
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
                    url: laroute.route('admin.email.append-table'),
                    dataTye: 'JSON',
                    method: 'POST',
                    data: {
                        list: append,
                        campaign_id: $('#campaign_id').val()
                    },
                    success: function (res) {
                        $.map(res.data_list, function (a) {
                            var stts = $('.table_list_body tr').length;
                            var tpl = $('#list-send-tpl').html();
                            if($(`.table_list_body tr td:contains("${a.email}")`).html() == undefined) {
                                tpl = tpl.replace(/{stt}/g, stts + 1);
                                tpl = tpl.replace(/{name}/g, a.customer_name);
                                tpl = tpl.replace(/{type_customer}/g, 'customer');
                                tpl = tpl.replace(/{object_id}/g, a.customer_id);
                                if (a.email != null) {
                                    tpl = tpl.replace(/{email}/g, a.email);
                                } else {
                                    tpl = tpl.replace(/{email}/g, '');
                                }
                                tpl = tpl.replace(/{content}/g, a.content);
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

    click_append_group: function () {
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
                    url: laroute.route('admin.email.append-table'),
                    dataTye: 'JSON',
                    method: 'POST',
                    data: {
                        list: append,
                        campaign_id: $('#campaign_id').val()
                    },
                    success: function (res) {
                        $.map(res.data_list, function (a) {
                            var stts = $('.table_list_body tr').length;
                            var tpl = $('#list-send-tpl').html();
                            if($(`.table_list_body tr td:contains("${a.email}")`).html() == undefined){
                                tpl = tpl.replace(/{stt}/g, stts + 1);
                                tpl = tpl.replace(/{name}/g, a.customer_name);
                                tpl = tpl.replace(/{type_customer}/g, 'customer');
                                tpl = tpl.replace(/{object_id}/g, a.customer_id);
                                if (a.email != null) {
                                    tpl = tpl.replace(/{email}/g, a.email);
                                } else {
                                    tpl = tpl.replace(/{email}/g, '');
                                }
                                tpl = tpl.replace(/{content}/g, a.content);
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
    click_append_lead: function () {
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
                    url: laroute.route('admin.email.append-table'),
                    dataTye: 'JSON',
                    method: 'POST',
                    data: {
                        type: 'lead',
                        list: append,
                        campaign_id: $('#campaign_id').val()
                    },
                    success: function (res) {
                        $.map(res.data_list, function (a) {
                            var stts = $('.table_list_body tr').length;
                            var tpl = $('#list-send-tpl').html();
                            if($(`.table_list_body tr td:contains("${a.email}")`).html() == undefined){
                                tpl = tpl.replace(/{stt}/g, stts + 1);
                                tpl = tpl.replace(/{name}/g, a.customer_name);
                                tpl = tpl.replace(/{type_customer}/g, 'lead');
                                tpl = tpl.replace(/{object_id}/g, a.customer_lead_id);
                                if (a.email != null) {
                                    tpl = tpl.replace(/{email}/g, a.email);
                                } else {
                                    tpl = tpl.replace(/{email}/g, '');
                                }
                                tpl = tpl.replace(/{content}/g, a.content);
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
    seen_content_old:function(e){
        $('.content_cus').empty();
        var content=$(e).closest('.old').find('textarea[name="content"]').val();
        $('#content-customer').modal('show');
        $('.content_cus').append(content);
    },
    seen_content:function(e){
        $('.content_cus').empty();
        var content=$(e).closest('.send').find('textarea[name="content"]').val();
        $('#content-customer').modal('show');
        $('.content_cus').append(content);
    },
    remove_old: function (e) {
        $(e).closest('.old').remove();
        edit.sttAction();
        $.ajax({
            url: laroute.route('admin.email.remove-log'),
            method: 'POST',
            data: {email: $(e).attr('data-value')},
            success: function (data) {
            }
        })
    },
    remove: function (e) {
        $(e).closest('.send').remove();
        edit.sttAction();
        $.ajax({
            url: laroute.route('admin.email.remove-log'),
            method: 'POST',
            data: {email: $(e).attr('data-value')},
            success: function (data) {
            }
        })
    },
    sttAction: function () {
        let stt = 1;
        $.each($('.stt'), function () {
            $(this).text(stt++);
        });
    },
    save_log: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var check = [];
            $.each($('.table_list').find("tbody tr"), function () {
                var $tds = $(this).find("input");
                $.each($tds, function () {
                    check.push($(this).val());
                });
            });
            if (check == '') {
                $('.tb_log').text(json['Vui lòng thêm khách hàng']);
            } else {
                $('.tb_log').text('');
                var send = [];
                $.each($('.table_list').find(".send"), function () {
                    var $tds = $(this).find("input,textarea");
                    $.each($tds, function () {
                        send.push($(this).val());
                    });
                });

                var old = [];
                $.each($('.table_list').find(".old"), function () {
                    var $tds = $(this).find("input,textarea");
                    $.each($tds, function () {
                        old.push($(this).val());
                    });
                });
                $.ajax({
                    url: laroute.route('admin.email.save-log'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        list_send: send,
                        list_old: old,
                        campaign_id: $('#campaign_id').val(),
                    }, success: function (res) {
                        if (res.success == 1) {
                            swal(json["Chỉnh sửa thành công"], "", "success");
                            window.location = laroute.route('admin.email');
                        }
                    }
                })
            }
        });
    },
    send_mail: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var check = [];
            $.each($('.table_list').find("tbody tr"), function () {
                var $tds = $(this).find("input");
                $.each($tds, function () {
                    check.push($(this).val());
                });
            });
            if (check == '') {
                $('.tb_log').text(json['Vui lòng thêm khách hàng']);
            } else {
                mApp.block("#m_blockui_1_content", {
                    overlayColor: "#000000",
                    type: "loader",
                    state: "success",
                    message: json["Đang tải..."]
                });
                $('.tb_log').text('');
                var send = [];
                $.each($('.table_list').find(".send"), function () {
                    var $tds = $(this).find("input,textarea");
                    $.each($tds, function () {
                        send.push($(this).val());
                    });
                });

                var old = [];
                $.each($('.table_list').find(".old"), function () {
                    var $tds = $(this).find("input,textarea");
                    $.each($tds, function () {
                        old.push($(this).val());
                    });
                });
                $.ajax({
                    url: laroute.route('admin.email.send-mail'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        list_send: send,
                        list_old: old,
                        campaign_id: $('#campaign_id').val(),
                    }, success: function (res) {
                        mApp.unblock("#m_blockui_1_content");
                        if (res.success == 1) {
                            swal("Gửi mail thành công", "", "success");
                            window.location = laroute.route('admin.email');
                        }
                    }
                })
            }
        });
        // var old = [];
        // $.each($('.table_list').find(".old"), function () {
        //     var $tds = $(this).find("input,textarea");
        //     $.each($tds, function () {
        //         old.push($(this).val());
        //     });
        // });
        // $.ajax({
        //     url: laroute.route('admin.email.send-mail'),
        //     dataType: 'JSON',
        //     method: 'POST',
        //     data: {
        //         list_old: old,
        //         campaign_id:$('#campaign_id').val()
        //     }, success: function (res) {
        //
        //     }
        // });
    },
    modal_file:function () {
        $('#modal-excel').modal('show');
        $('#show').val('');
        $('input[type=file]').val('');
    },
    import:function () {
        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('campaign_id', $('#campaign_id').val());
        $.ajax({
            url: laroute.route("admin.email.import-excel"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                $.map(res.list_customer, function (a) {
                    var stts = $('.table_list_body tr').length;
                    var tpl = $('#list-send-tpl').html();
                    tpl = tpl.replace(/{stt}/g, stts + 1);
                    tpl = tpl.replace(/{name}/g, a.customer_name);
                    if (a.email != null) {
                        tpl = tpl.replace(/{email}/g, a.email);
                    } else {
                        tpl = tpl.replace(/{email}/g, '');
                    }
                    tpl = tpl.replace(/{content}/g, a.content);
                    $('.table_list_body').append(tpl);
                });
                $('#modal-excel').modal('hide');
            }
        });
    },
    showNameFile:function(){
        var fileNamess=$('input[type=file]').val();
        $('#show').val(fileNamess);
    },
    checkCustomer:function (customer_id,check = 0,status = 'add') {
        $.ajax({
            url: laroute.route("admin.email.check-customer"),
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
            url: laroute.route("admin.email.check-customer-lead"),
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
}

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


    }
};
$(document).ready(function () {
    $('#filter_type_group').select2();
    $('#customer_group_filter').select2();
    $('#add-customer').on('hidden.bs.modal', function (e) {
        $.ajax({
            url: laroute.route('admin.email.delete-session'),
            dataTye: 'JSON',
            method: 'POST',
            success: function (res) {
            }
        });
    })
    $('#add-customer-group').on('hidden.bs.modal', function (e) {
        $.ajax({
            url: laroute.route('admin.email.delete-session'),
            dataTye: 'JSON',
            method: 'POST',
            success: function (res) {
            }
        });
    })
})