const avt = "https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947";
var layout = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {

        });
    },
    getModal: function (dataArray){
        let phone = '';
        if(dataArray['dataCustomer']['type'] == 'lead'){
            phone = dataArray['dataCustomer']['phone'];
        }
        else {
            phone = dataArray['dataCustomer']['phone1'];
        }
        if($(`#oncall-${phone}`).is(':visible')){
            $(`#oncall-${phone}`).trigger('click');
        }
        else if ($('#nhandt-modal-oncall').is(':visible')) {
            // nếu lần đẩy thông tin này là thông tin của popup đang show => không có action
            let isShowAlert = 1;

            if($('#parent_oncall_type').val() == dataArray['dataCustomer']['type']){
                if($('#parent_oncall_type').val() == 'lead'){
                    if($('#parent_oncall_id').val() == dataArray['dataCustomer']['customer_lead_id']){
                        isShowAlert = 0;
                    }
                } else {
                    if($('#parent_oncall_id').val() == dataArray['dataCustomer']['customer_id']){
                        isShowAlert = 0;
                    }
                }
            }
            if(isShowAlert == 1){
                if(!$(`#oncall-${phone}`).is(':visible')){
                    var tpl = $('#oncall-icon-popup-messenger-tpl').html();
                    tpl = tpl.replace(/{phone}/g, phone);
                    tpl = tpl.replace(/{history_id}/g, dataArray['history_id']);
                    if(dataArray['dataCustomer']['type'] == 'lead'){
                        tpl = tpl.replace(/{id}/g, dataArray['dataCustomer']['customer_lead_id']);
                    } else {
                        tpl = tpl.replace(/{id}/g, dataArray['dataCustomer']['customer_id']);
                    }
                    tpl = tpl.replace(/{type}/g, dataArray['dataCustomer']['type']);

                    if(dataArray['dataCustomer']['avatar'] != ''){
                        tpl = tpl.replace(/{avatar}/g, dataArray['dataCustomer']['avatar']);
                    } else {
                        tpl = tpl.replace(/{avatar}/g, avt);
                    }

                    // 10: because this contains in layout and popup (5x2)
                    if($('.oncall-append-li>ul>li').length == 10){
                        $('.oncall-append-li>ul>li').each(function( index ) {
                            $(this).last().remove();
                        });
                    }
                    $('.oncall-append-li>ul').prepend(tpl);

                }
                //
                $('#toast-container').remove();
                toastr.info(`Có khách ${phone} gọi đến`);
            }
        } else {
            layout.ajaxGetModal(dataArray, 1);
        }
    },
    getModalFromIcon: function(staffId, historyId, id, type, phone, brandCode){
        $(`#oncall-${phone}`).remove();
        let dataCustomer = {};
        let dataArray = {
            "dataExtension": {
                "staff_id": staffId
            },
            "history_id": historyId
        };
        if(type == 'lead'){
            dataCustomer = {
                "customer_lead_id": id,
                "type": type,
                "phone": phone,
                "brand_code": brandCode
            }
            dataArray['dataCustomer'] = dataCustomer;
        } else if(type == 'customer') {
            dataCustomer = {
                "customer_id": id,
                "type": type,
                "phone1": phone,
                "brand_code": brandCode
            }
            dataArray['dataCustomer'] = dataCustomer;
        }
        layout.ajaxGetModal(dataArray, 1);
    },
    minimizePopupOncall: function(staffId, historyId, id, type, phone, brandCode, avatar = ''){
        var tpl = $('#oncall-icon-popup-messenger-tpl').html();
        tpl = tpl.replace(/{phone}/g, phone);
        tpl = tpl.replace(/{history_id}/g, historyId);
        tpl = tpl.replace(/{id}/g, id);
        tpl = tpl.replace(/{type}/g, type);
        if(avatar != ''){
            tpl = tpl.replace(/{avatar}/g, avatar);
        } else {
            tpl = tpl.replace(/{avatar}/g, avt);
        }

        // 10: because this contains in layout and popup (5x2)
        if($('.oncall-append-li>ul>li').length == 10){
            $('.oncall-append-li>ul>li').each(function( index ) {
                $(this).last().remove();
            });
        }
        $('.oncall-append-li>ul').prepend(tpl);
        $('#nhandt-modal-oncall').modal('hide');
        $('#nhandt-my-modal-oncall').html('');

        $('.modal-backdrop').remove();
    },
    ajaxGetModal: function(dataArray, append = 0){
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('extension.get-modal-calling'),
                method: "POST",
                data: {
                    'dataArray': dataArray
                },
                success: function (res) {
                    $('#nhandt-my-modal-oncall').html(res.html);
                    $('.modal-select2').select2();

                    $('#oncall_province_id').change(function () {
                        $.ajax({
                            url: laroute.route('admin.customer.load-district'),
                            dataType: 'JSON',
                            data: {
                                id_province: $('#oncall_province_id').val(),
                            },
                            global: false,
                            method: 'POST',
                            success: function (res) {
                                $('.oncall_district').empty();
                                $.map(res.optionDistrict, function (a) {
                                    $('.oncall_district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                                });
                            }
                        });
                    });
                    $('#oncall_district_id').select2({
                        placeholder: json["Chọn quận/huyện"],
                        ajax: {
                            url: laroute.route('admin.customer.load-district'),
                            data: function (params) {
                                return {
                                    id_province: $('#oncall_province_id').val(),
                                    search: params.term,
                                    page: params.page || 1
                                };
                            },
                            global: false,
                            dataType: 'JSON',
                            method: 'POST',
                            processResults: function (res) {
                                res.page = res.page || 1;
                                return {
                                    results: res.optionDistrict.map(function (item) {
                                        return {
                                            id: item.id,
                                            text: item.name
                                        };
                                    }),
                                    pagination: {
                                        more: res.pagination
                                    }
                                };
                            },
                        }
                    });
                    $('#oncall_pipeline_code').change(function () {
                        $.ajax({
                            url: laroute.route('customer-lead.load-option-journey'),
                            dataType: 'JSON',
                            data: {
                                pipeline_code: $('#oncall_pipeline_code').val(),
                            },
                            global: false,
                            method: 'POST',
                            success: function (res) {
                                $('.oncall_journey').empty();
                                $.map(res.optionJourney, function (a) {
                                    $('.oncall_journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                });
                            }
                        });
                    });

                    layout.initStyleCare();
                    $('#nhandt-modal-oncall').modal('show');
                    if(append == 1){
                        let curListIconPopup = $('.oncall-append-li').html();
                        $('#nhandt-modal-oncall').append(`
                        <div class="oncall-modal">
                             <div class="oncall-append-li">
                             ${curListIconPopup}
                             </div>
                         </div>
                        `);
                    }
                }
            });
        });
    },
    initStyleCare: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            // $('#oncall-deal-autotable').PioTable({
            //     baseUrl: laroute.route('extension.get-list-deal-paging')
            // });
            $('.oncall_select2-active').select2();
            $(".oncall_date-timepicker").datetimepicker({
                todayHighlight: !0,
                autoclose: !0,
                pickerPosition: "bottom-left",
                format: "dd/mm/yyyy hh:ii",
                // format: "dd/mm/yyyy",
                startDate : new Date()
                // locale: 'vi'
            });


            $(".oncall_time-input").timepicker({
                todayHighlight: !0,
                autoclose: !0,
                pickerPosition: "bottom-left",
                // format: "dd/mm/yyyy hh:ii",
                format: "HH:ii",
                defaultTime: "",
                showMeridian: false,
                minuteStep: 5,
                snapToStep: !0,
                // startDate : new Date()
                // locale: 'vi'
            });

            $(".oncall_daterange-input").datepicker({
                todayHighlight: !0,
                autoclose: !0,
                pickerPosition: "bottom-left",
                // format: "dd/mm/yyyy hh:ii",
                format: "dd/mm/yyyy",
                // startDate : new Date()
                // locale: 'vi'
            });

            AutoNumeric.multiple('.oncall_input-mask,.oncall_input-mask-remind',{
                currencySymbol : '',
                decimalCharacter : '.',
                digitGroupSeparator : ',',
                decimalPlaces: 0,
                minimumValue: 0,
            });

            $('#oncall_care_type').select2({
                placeholder: json['Chọn loại chăm sóc']
            });

            $('.oncall_summernote').summernote({
                placeholder: '',
                height: 295,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname', 'fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        for(let i=0; i < files.length; i++) {
                            uploadImgCk(files[i]);
                        }
                    }
                },
            });
        });
    },
    saveInfo: function(type, id){
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#oncall_form_care_info');
            if(type == 'lead'){
                form.validate({
                    rules: {
                        oncall_full_name: {
                            required: true,
                            maxlength: 250
                        },
                        oncall_phone: {
                            required: true,
                            integer: true,
                            maxlength: 10
                        },
                        oncall_address: {
                            maxlength: 250
                        },
                        // oncall_customer_source: {
                        //     required: true
                        // },
                        // oncall_pipeline_code: {
                        //     required: true
                        // },
                        // oncall_journey_code: {
                        //     required: true
                        // },
                        // oncall_tax_code: {
                        //     minlength: 11,
                        //     maxlength: 13
                        // },
                        // oncall_representative: {
                        //     maxlength: 191
                        // },
                        // oncall_hotline: {
                        //     minlength: 10,
                        //     maxlength: 15
                        // },
                    },
                    messages: {
                        oncall_full_name: {
                            required: json['Hãy nhập họ và tên'],
                            maxlength: json['Họ và tên tối đa 250 kí tự']
                        },
                        oncall_phone: {
                            required: json['Hãy nhập số điện thoại'],
                            integer: json['Số điện thoại không hợp lệ'],
                            maxlength: json['Số điện thoại tối đa 10 kí tự']
                        },
                        oncall_address: {
                            maxlength: json['Địa chỉ tối đa 250 kí tự']
                        },
                        // oncall_pipeline_code: {
                        //     required: json['Hãy chọn pipeline']
                        // },
                        // oncall_journey_code: {
                        //     required: json['Hãy chọn hành trình khách hàng']
                        // },
                        // oncall_customer_source: {
                        //     required: json['Hãy chọn nguồn khách hàng']
                        // },
                        // oncall_tax_code: {
                        //     minlength: json["Mã số thuế tối thiểu 11 ký tự"],
                        //     maxlength: json["Mã số thuế tối đa 13 ký tự"]
                        // },
                        // oncall_representative: {
                        //     maxlength: json["Người đại diện tối đa 191 ký tự"]
                        // },
                        // oncall_hotline: {
                        //     minlength: json["Hotline tối thiểu 10 ký tự"],
                        //     maxlength: json["Hotline tối đa 15 ký tự"]
                        // },
                    },
                });
                if (!form.valid()) {
                    return false;
                }
                $.ajax({
                    url: laroute.route('customer-lead.update-from-oncall'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_lead_id: id,
                        customer_lead_code: $('#oncall_customer_lead_code').val(),
                        full_name: $('#oncall_full_name').val(),
                        gender: $('input[name="oncall_gender"]:checked').val(),
                        phone: $('#oncall_phone').val(),
                        province_id: $('#oncall_province_id').val(),
                        district_id: $('#oncall_district_id').val(),
                        address: $('#oncall_address').val(),
                        // email: $('#oncall_email').val(),
                        // customer_source: $('#oncall_customer_source').val(),
                        // customer_type: $('#oncall_customer_type').val(),
                        // hotline: $('#oncall_hotline').val(),
                        // tax_code: $('#oncall_tax_code').val(),
                        // representative: $('#oncall_representative').val(),
                        // pipeline_code: $('#oncall_pipeline_code').val(),
                        // journey_code: $('#oncall_journey_code').val(),
                        // fanpage: $('#fanpage').val(),
                        // zalo: $('#oncall_zalo').val(),
                    },
                    success: function (res) {
                        if (res.error == false) {
                            return true;
                            // swal(res.message, "", "success").then(function (result) {
                            // });
                        } else {
                            swal(res.message, '', "error");
                            return true;
                        }
                    },
                    error: function (res) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                });
            } else {
                form.validate({
                    rules: {
                        oncall_full_name: {
                            required: true
                        },
                        oncall_phone1: {
                            required: true,
                            number: true,
                            minlength: 10,
                            maxlength: 11
                        },
                        // oncall_address: {
                        //     required: true
                        // },
                        // oncall_province_id: {
                        //     required: true
                        // },
                        // oncall_district_id: {
                        //     required: true
                        // },
                        // oncall_tax_code: {
                        //     minlength: 11,
                        //     maxlength: 13
                        // },
                        // oncall_representative: {
                        //     maxlength: 191
                        // },
                        // oncall_hotline: {
                        //     minlength: 10,
                        //     maxlength: 15
                        // },
                        // oncall_customer_group_id: {
                        //     required: true
                        // },
                        // oncall_email: {
                        //     required: true,
                        //     email: true
                        // },
                    },
                    messages: {
                        oncall_full_name: {
                            required: json["Hãy nhập tên khách hàng"]
                        },
                        oncall_phone1: {
                            required: json["Hãy nhập số điện thoại"],
                            number: json["Số điện thoại không hợp lệ"],
                            minlength: json["Tối thiểu 10 số"],
                            maxlength: json["Tối đa 11 số"]
                        },
                        oncall_address: {
                            required: json["Hãy nhập địa chỉ"]
                        },
                        oncall_province_id: {
                            required: json["Hãy chọn tỉnh thành"]
                        },
                        oncall_district_id: {
                            required: json["Hãy chọn quận huyện"]
                        },
                        oncall_tax_code: {
                            minlength: json["Mã số thuế tối thiểu 11 ký tự"],
                            maxlength: json["Mã số thuế tối đa 13 ký tự"]
                        },
                        oncall_representative: {
                            maxlength: json["Người đại diện tối đa 191 ký tự"]
                        },
                        oncall_hotline: {
                            minlength: json["Hotline tối thiểu 10 ký tự"],
                            maxlength: json["Hotline tối đa 15 ký tự"]
                        },
                        oncall_customer_group_id: {
                            required: json["Hãy chọn nhóm khách hàng"]
                        },
                        oncall_email: {
                            required: json["Email là bắt buộc"],
                            email: json['Email không hợp lệ']
                        },
                    },
                });

                if (!form.valid()) {
                    return false;
                }
                var gender = $('input[name="oncall_gender"]:checked').val();
                var customer_group_id = $('#oncall_customer_group_id').val();
                var full_name = $('#oncall_full_name').val();
                var phone1 = $('#oncall_phone1').val();
                var province_id = $('#oncall_province_id').val();
                var district_id = $('#oncall_district_id').val();
                var address = $('#oncall_address').val();
                var email = $('#oncall_email').val();
                var day = $('#oncall_day').val();
                var month = $('#oncall_month').val();
                var year = $('#oncall_year').val();
                var customer_source_id = $('#oncall_customer_source').val();
                var customer_refer_id = $('#oncall_customer_refer_id').val();
                var facebook = $('#oncall_facebook').val();
                var note = $('#oncall_note').val();
                var customer_id = id;
                var is_actived = 0;
                if ($('#oncall_is_actived').is(':checked')) {
                    is_actived = 1;
                }
                var customer_type = $('#oncall_customer_type').val();
                var tax_code = $('#oncall_tax_code').val();
                var representative = $('#oncall_representative').val();
                var hotline = $('#oncall_hotline').val();
                if(customer_type == 'personal'){
                    tax_code = '';
                    representative = '';
                    hotline = '';
                }

                $.ajax({
                    url: laroute.route('admin.customer.update-from-oncall'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        gender: gender,
                        // customer_group_id: customer_group_id,
                        full_name: full_name,
                        phone1: phone1,
                        province_id: province_id,
                        district_id: district_id,
                        address: address,
                        // email: email,
                        day: day,
                        month: month,
                        year: year,
                        // customer_source_id: customer_source_id,
                        // customer_refer_id: customer_refer_id,
                        // facebook: facebook,
                        // is_actived: is_actived,
                        // note: note,
                        // postcode: $('#oncall_postcode').val(),
                        // customer_type: customer_type,
                        // tax_code: tax_code,
                        // representative: representative,
                        // hotline: hotline,
                        id: customer_id,
                    },
                    success: function(res) {
                        if (res.error == false) {
                            return true;
                            // swal(res.message, "", "success");
                        } else {
                            swal(res.message, "", "error");
                            return false;
                        }
                    }
                });
            }

        });
    },
    saveCare: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#oncall_form_care_info');
            var is_booking = 0;
            if ($('#oncall_is_booking').is(':checked')){
                is_booking = 1;
            }
            $.ajax({
                url: laroute.route('extension.submit-care-calling'),
                method: 'POST',
                dataType: 'JSON',
                data : $('#oncall_form_care_info').formSerialize()+'&is_booking='+is_booking,
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            // append list care
                            $('#oncall-list-care-timeline').html('');
                            $('#oncall-list-care-timeline').html(res.list_care_html);
                            // append list word
                            $('.oncall_list-table-work').html('');
                            $('.oncall_list-table-work').html(res.list_work_html);
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
                    swal(json['Thêm mới thất bại'], mess_error, "error");
                }
            });
        });
    },
    saveCareAndInfo: function (type, id){
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#oncall_form_care_info');
            if(type == 'lead'){
                form.validate({
                    rules: {
                        oncall_full_name: {
                            required: true,
                            maxlength: 250
                        },
                        oncall_phone: {
                            required: true,
                            integer: true,
                            maxlength: 10
                        },
                        oncall_address: {
                            maxlength: 250
                        }
                    },
                    messages: {
                        oncall_full_name: {
                            required: json['Hãy nhập họ và tên'],
                            maxlength: json['Họ và tên tối đa 250 kí tự']
                        },
                        oncall_phone: {
                            required: json['Hãy nhập số điện thoại'],
                            integer: json['Số điện thoại không hợp lệ'],
                            maxlength: json['Số điện thoại tối đa 10 kí tự']
                        },
                        oncall_address: {
                            maxlength: json['Địa chỉ tối đa 250 kí tự']
                        }
                    },
                });
                if (!form.valid()) {
                    return false;
                }
                $.ajax({
                    url: laroute.route('customer-lead.update-from-oncall'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_lead_id: id,
                        customer_lead_code: $('#oncall_customer_lead_code').val(),
                        full_name: $('#oncall_full_name').val(),
                        gender: $('input[name="oncall_gender"]:checked').val(),
                        phone: $('#oncall_phone').val(),
                        province_id: $('#oncall_province_id').val(),
                        district_id: $('#oncall_district_id').val(),
                        address: $('#oncall_address').val(),
                        // email: $('#oncall_email').val(),
                        // customer_source: $('#oncall_customer_source').val(),
                        // customer_type: $('#oncall_customer_type').val(),
                        // hotline: $('#oncall_hotline').val(),
                        // tax_code: $('#oncall_tax_code').val(),
                        // representative: $('#oncall_representative').val(),
                        // pipeline_code: $('#oncall_pipeline_code').val(),
                        // journey_code: $('#oncall_journey_code').val(),
                        // fanpage: $('#fanpage').val(),
                        // zalo: $('#oncall_zalo').val(),
                    },
                    success: function (res) {
                        if (res.error == false) {
                            layout.saveCare();
                            // swal(res.message, "", "success").then(function (result) {
                            // });
                        } else {
                            swal(res.message, '', "error");
                            return true;
                        }
                    },
                    error: function (res) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                });
            } else {
                form.validate({
                    rules: {
                        oncall_full_name: {
                            required: true
                        },
                        oncall_phone1: {
                            required: true,
                            number: true,
                            minlength: 10,
                            maxlength: 11
                        },
                        // oncall_address: {
                        //     required: true
                        // },
                        // oncall_province_id: {
                        //     required: true
                        // },
                        // oncall_district_id: {
                        //     required: true
                        // },
                        // oncall_tax_code: {
                        //     minlength: 11,
                        //     maxlength: 13
                        // },
                        // oncall_representative: {
                        //     maxlength: 191
                        // },
                        // oncall_hotline: {
                        //     minlength: 10,
                        //     maxlength: 15
                        // },
                        // oncall_customer_group_id: {
                        //     required: true
                        // },
                        // oncall_email: {
                        //     required: true,
                        //     email: true
                        // },
                    },
                    messages: {
                        oncall_full_name: {
                            required: json["Hãy nhập tên khách hàng"]
                        },
                        oncall_phone1: {
                            required: json["Hãy nhập số điện thoại"],
                            number: json["Số điện thoại không hợp lệ"],
                            minlength: json["Tối thiểu 10 số"],
                            maxlength: json["Tối đa 11 số"]
                        },
                        oncall_address: {
                            required: json["Hãy nhập địa chỉ"]
                        },
                        oncall_province_id: {
                            required: json["Hãy chọn tỉnh thành"]
                        },
                        oncall_district_id: {
                            required: json["Hãy chọn quận huyện"]
                        },
                        oncall_tax_code: {
                            minlength: json["Mã số thuế tối thiểu 11 ký tự"],
                            maxlength: json["Mã số thuế tối đa 13 ký tự"]
                        },
                        oncall_representative: {
                            maxlength: json["Người đại diện tối đa 191 ký tự"]
                        },
                        oncall_hotline: {
                            minlength: json["Hotline tối thiểu 10 ký tự"],
                            maxlength: json["Hotline tối đa 15 ký tự"]
                        },
                        oncall_customer_group_id: {
                            required: json["Hãy chọn nhóm khách hàng"]
                        },
                        oncall_email: {
                            required: json["Email là bắt buộc"],
                            email: json['Email không hợp lệ']
                        },
                    },
                });

                if (!form.valid()) {
                    return false;
                }
                var gender = $('input[name="oncall_gender"]:checked').val();
                var customer_group_id = $('#oncall_customer_group_id').val();
                var full_name = $('#oncall_full_name').val();
                var phone1 = $('#oncall_phone1').val();
                var province_id = $('#oncall_province_id').val();
                var district_id = $('#oncall_district_id').val();
                var address = $('#oncall_address').val();
                var email = $('#oncall_email').val();
                var day = $('#oncall_day').val();
                var month = $('#oncall_month').val();
                var year = $('#oncall_year').val();
                var customer_source_id = $('#oncall_customer_source').val();
                var customer_refer_id = $('#oncall_customer_refer_id').val();
                var facebook = $('#oncall_facebook').val();
                var note = $('#oncall_note').val();
                var customer_id = id;
                var is_actived = 0;
                if ($('#oncall_is_actived').is(':checked')) {
                    is_actived = 1;
                }
                var customer_type = $('#oncall_customer_type').val();
                var tax_code = $('#oncall_tax_code').val();
                var representative = $('#oncall_representative').val();
                var hotline = $('#oncall_hotline').val();
                if(customer_type == 'personal'){
                    tax_code = '';
                    representative = '';
                    hotline = '';
                }

                $.ajax({
                    url: laroute.route('admin.customer.update-from-oncall'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        gender: gender,
                        // customer_group_id: customer_group_id,
                        full_name: full_name,
                        phone1: phone1,
                        province_id: province_id,
                        district_id: district_id,
                        address: address,
                        // email: email,
                        day: day,
                        month: month,
                        year: year,
                        // customer_source_id: customer_source_id,
                        // customer_refer_id: customer_refer_id,
                        // facebook: facebook,
                        // is_actived: is_actived,
                        // note: note,
                        // postcode: $('#oncall_postcode').val(),
                        // customer_type: customer_type,
                        // tax_code: tax_code,
                        // representative: representative,
                        // hotline: hotline,
                        id: customer_id,
                    },
                    success: function(res) {
                        if (res.error == false) {
                            layout.saveCare();
                            // swal(res.message, "", "success");
                        } else {
                            swal(res.message, "", "error");
                            return false;
                        }
                    }
                });
            }

        });
    },
    closePopupOncall: function (obj) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json["Thông báo"],
                text: json["Bạn có thật sự muốn thoát?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json["Có"],
                cancelButtonText: json["Không"],
            }).then(function (result) {
                if (result.value) {
                    // window.location.reload();

                    $('#nhandt-modal-oncall').modal('hide');
                    $('#nhandt-my-modal-oncall').html('');

                    $('.modal-backdrop').remove();
                }
            });
        });
    },
};
var layoutWork = {
    search : function() {
        $.ajax({
            url: laroute.route('extension.search-work-list'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#oncall_form-search-support').serialize(),
            success: function (res) {
                if(res.error == false){
                    $('.oncall_list-table-work').empty();
                    $('.oncall_list-table-work').append(res.view);
                }
            }
        })
    },

    searchHistory : function() {
        $.ajax({
            url: laroute.route('extension.search-work-list'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#oncall_form-search-history').serialize(),
            success: function (res) {
                if(res.error == false){
                    $('.oncall_list-table-work-history').empty();
                    $('.oncall_list-table-work-history').append(res.view);
                }
            }
        })
    },

    searchPage : function(page){
        $('#oncall_page_support').val(page);
        layoutWork.search();
    },

    searchPageHistory : function(page){
        $('#oncall_page_history').val(page);
        layoutWork.searchHistory();
    },

    removeSearchWork : function(){
        $('#oncall_page_support').val(1);
        $('#oncall_form-search-support input[type="text"]').val('');
        $('#oncall_form-search-support select').val('').trigger('change');
        layoutWork.search();
    },

    removeSearchWorkHistory: function(){
        $('#oncall_page_history').val(1);
        $('#oncall_oncall_form-search-history input[type="text"]').val('');
        $('#oncall_oncall_form-search-history select').val('').trigger('change');
        layoutWork.searchHistory();
    },

    changeBooking : function(){
        if($('#oncall_is_booking').is(':checked')){
            // $('.block-hide-work').show();
            $('.oncall_checkBookingAdd').prop('disabled',false);
        } else {
            // $('.block-hide-work').hide();
            $('.oncall_checkBookingAdd').prop('disabled',true);
        }
    },

    changeRemind(){
        if($('#oncall_is_remind').is(':checked')){
            $('.oncall_checkRemindAdd').prop('disabled',false);
        } else {
            $('.oncall_checkRemindAdd').prop('disabled',true);
        }
    }

}

var layoutStaffOverview = {
    changeStatus : function (id){
        $.ajax({
            url: laroute.route('manager-work.staff-overview.change-status'),
            data: {id : id, status : $('#oncall_form-change-status').find('#oncall_manage_status_id').val()},
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                swal('Đổi trạng thái thành công','','success').then(function(){
                    // window.location.reload();
                    $('#popup-staff-overview-status').modal('hide');
                    layoutWork.search();
                });
            }
        });
    },
};

uploadImgCk = function (file,parent_comment = null) {
    let out = new FormData();
    out.append('file', file, file.name);

    $.ajax({
        method: 'POST',
        url: laroute.route('customer-lead.upload-file'),
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function (img) {
            if (parent_comment != null){
                $(".oncall_summernote").summernote('insertImage', img['file']);
            } else {
                $(".oncall_summernote").summernote('insertImage', img['file']);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};