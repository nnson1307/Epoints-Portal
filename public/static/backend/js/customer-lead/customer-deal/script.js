var stt = 0;

var contractAnnex = {
    popupAddContractAnnex: function (id, deal_code) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('contract.contract.get-popup-annex'),
                method: "POST",
                dataType: "JSON",
                data: {
                    contract_id: id,
                    deal_code: deal_code
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#add-annex').modal('show');
                    var arrRange = {};
                    arrRange[listDeal.jsonLang["Hôm nay"]] = [moment(), moment()];
                    arrRange[listDeal.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
                    arrRange[listDeal.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
                    arrRange[listDeal.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
                    arrRange[listDeal.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
                    arrRange[listDeal.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
                    $("#annex_effective_date,#annex_expired_date,#annex_sign_date").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        format: "dd/mm/yyyy",
                        startDate: "dateToday"
                    });
                }
            });
        });
    },
    uploadFileCc: function (input) {
        $.getJSON(laroute.route('translate'), function (json) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.readAsDataURL(input.files[0]);
                var file_data = $('#upload_file_cc').prop('files')[0];
                if (file_data.size > 41943040) {
                    swal(listDeal.jsonLang['Tối đa 5MB'], "", "error");
                }
                var actFile = [".pdf", ".doc", ".docx", ".pdf", ".csv", ".xls", ".xlsx"];
                var ext = file_data.name.substring(file_data.name.lastIndexOf("."), file_data.name.length);
                if (jQuery.inArray(ext, actFile) == -1) {
                    swal(listDeal.jsonLang['Vui lòng chọn file đúng định dạng'], "", "error");
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
        });
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
        $.getJSON(laroute.route('translate'), function (json) {
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
                        required: listDeal.jsonLang['Mã phụ lục không được trống'],
                        maxlength: listDeal.jsonLang['Tối đa 191 kí tự']
                    },
                    annex_effective_date: {
                        required: listDeal.jsonLang['Ngày có hiệu lực không được trống'],
                    },
                    annex_sign_date: {
                        required: listDeal.jsonLang['Ngày ký không được trống'],
                    },
                    annex_expired_date: {
                        required: listDeal.jsonLang['Ngày hết hiệu lực không được trống'],
                    },
                    annex_content: {
                        required: listDeal.jsonLang['Nội dung không được trống'],
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
                            swal(listDeal.jsonLang["Thêm phụ lục hợp đồng thành công"], "", "success");
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
                        swal(listDeal.jsonLang['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                    }
                });
            }
            else {
                var dataAnnexLocal = {
                    deal_code: $('#deal_code').val(),
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
                        deal_code: $('#deal_code').val(),
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
                        swal(listDeal.jsonLang['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                    }
                });
            }
        });
    },
    actionUpdateAnnexSaveOrContinue: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
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
                        required: listDeal.jsonLang['Mã phụ lục không được trống'],
                        maxlength: listDeal.jsonLang['Tối đa 191 kí tự']
                    },
                    annex_effective_date: {
                        required: listDeal.jsonLang['Ngày có hiệu lực không được trống'],
                    },
                    annex_sign_date: {
                        required: listDeal.jsonLang['Ngày ký không được trống'],
                    },
                    annex_expired_date: {
                        required: listDeal.jsonLang['Ngày hết hiệu lực không được trống'],
                    },
                    annex_content: {
                        required: listDeal.jsonLang['Nội dung không được trống'],
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
                        swal(listDeal.jsonLang['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
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
                        swal(listDeal.jsonLang['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                    }
                });
            }
        });
    },
    saveInfoContractAnnex: function (e, contractId) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-info');

            var rules = [];
            var messages = [];

            var dataGeneral = {};
            var dataPartner = {};
            var dataPayment = {};
            //QuĂ©t input cá»¥m thĂ´ng tin chung
            $.each($('#group-general'), function () {
                var $tds = $(this).find("input,select,textarea");

                $.each($tds, function () {
                    if ($(this).attr("name") != null) {
                        if ($(this).attr("isValidate") == 1) {
                            //Láº¥y thĂ´ng tin validate
                            switch ($(this).attr("name")) {
                                case 'tab_general_contract_name':
                                    rules[$(this).attr("name")] = {
                                        required: true,
                                        maxlength: 190
                                    };

                                    messages[$(this).attr("name")] = {
                                        required: $(this).attr("keyName") + ' ' + listDeal.jsonLang['không được trống'],
                                        maxlength: $(this).attr("keyName") + ' ' + listDeal.jsonLang['tối đa 190 ký tự']
                                    };
                                    break;
                                default:
                                    rules[$(this).attr("name")] = {
                                        required: true
                                    };

                                    messages[$(this).attr("name")] = {
                                        required: $(this).attr("keyName") + ' ' + listDeal.jsonLang['không được trống']
                                    };
                            }
                        }
                        //Láº¥y dá»¯ liá»‡u cá»§a cĂ¡c trÆ°á»ng
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

            //QuĂ©t input cá»¥m Ä‘á»‘i tĂ¡c
            $.each($('#group-partner'), function () {
                var $tds = $(this).find("input,select,textarea");

                $.each($tds, function () {
                    if ($(this).attr("name") != null) {
                        if ($(this).attr("isValidate") == 1) {
                            //Láº¥y thĂ´ng tin validate
                            switch ($(this).attr("name")) {
                                case 'tab_partner_address':
                                    rules[$(this).attr("name")] = {
                                        maxlength: 190
                                    };

                                    messages[$(this).attr("name")] = {
                                        maxlength: $(this).attr("keyName") + ' ' + listDeal.jsonLang['tối đa 190 ký tự']
                                    };
                                    break;
                                case 'tab_partner_email':
                                    rules[$(this).attr("name")] = {
                                        maxlength: 190
                                    };

                                    messages[$(this).attr("name")] = {
                                        maxlength: $(this).attr("keyName") + ' ' + listDeal.jsonLang['tối đa 190 ký tự']
                                    };
                                    break;
                                default:
                                    rules[$(this).attr("name")] = {
                                        required: true
                                    };

                                    messages[$(this).attr("name")] = {
                                        required: $(this).attr("keyName") + ' ' + listDeal.jsonLang['không được trống']
                                    };
                            }
                        }
                        //Láº¥y dá»¯ liá»‡u cá»§a cĂ¡c trÆ°á»ng
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

            //QuĂ©t input cá»¥m thanh toĂ¡n
            $.each($('#group-payment'), function () {
                var $tds = $(this).find("input,select,textarea");

                $.each($tds, function () {
                    if ($(this).attr("name") != null) {
                        if ($(this).attr("isValidate") == 1) {
                            //Láº¥y thĂ´ng tin validate
                            switch ($(this).attr("name")) {
                                default:
                                    rules[$(this).attr("name")] = {
                                        required: true
                                    };

                                    messages[$(this).attr("name")] = {
                                        required: $(this).attr("keyName") + ' ' + listDeal.jsonLang['không được trống']
                                    };
                            }
                        }
                        //Láº¥y dá»¯ liá»‡u cá»§a cĂ¡c trÆ°á»ng
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
            //QuĂ©t input cá»¥m thĂ´ng tin chung
            $.each($('#group-general'), function () {
                var $tds = $(this).find("input,select,textarea");

                $.each($tds, function () {
                    if ($(this).attr("name") != null) {
                        if ($(this).attr("isValidate") == 1) {
                            //Láº¥y thĂ´ng tin validate
                            switch ($(this).attr("name")) {
                                case 'tab_general_contract_name':
                                    rules[$(this).attr("name")] = {
                                        required: true,
                                        maxlength: 190
                                    };

                                    messages[$(this).attr("name")] = {
                                        required: $(this).attr("keyName") + ' ' + listDeal.jsonLang['không được trống'],
                                        maxlength: $(this).attr("keyName") + ' ' + listDeal.jsonLang['tối đa 190 ký tự']
                                    };
                                    break;
                                default:
                                    rules[$(this).attr("name")] = {
                                        required: true
                                    };

                                    messages[$(this).attr("name")] = {
                                        required: $(this).attr("keyName") + ' ' + listDeal.jsonLang['không được trống']
                                    };
                            }
                        }
                        //Láº¥y dá»¯ liá»‡u cá»§a cĂ¡c trÆ°á»ng
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

            //QuĂ©t input cá»¥m Ä‘á»‘i tĂ¡c
            $.each($('#group-partner'), function () {
                var $tds = $(this).find("input,select,textarea");

                $.each($tds, function () {
                    if ($(this).attr("name") != null) {
                        if ($(this).attr("isValidate") == 1) {
                            //Láº¥y thĂ´ng tin validate
                            switch ($(this).attr("name")) {
                                case 'tab_partner_address':
                                    rules[$(this).attr("name")] = {
                                        maxlength: 190
                                    };

                                    messages[$(this).attr("name")] = {
                                        maxlength: $(this).attr("keyName") + ' ' + listDeal.jsonLang['tối đa 190 ký tự']
                                    };
                                    break;
                                case 'tab_partner_email':
                                    rules[$(this).attr("name")] = {
                                        maxlength: 190
                                    };

                                    messages[$(this).attr("name")] = {
                                        maxlength: $(this).attr("keyName") + ' ' + listDeal.jsonLang['tối đa 190 ký tự']
                                    };
                                    break;
                                default:
                                    rules[$(this).attr("name")] = {
                                        required: true
                                    };

                                    messages[$(this).attr("name")] = {
                                        required: $(this).attr("keyName") + ' ' + listDeal.jsonLang['không được trống']
                                    };
                            }
                        }
                        //Láº¥y dá»¯ liá»‡u cá»§a cĂ¡c trÆ°á»ng
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

            //QuĂ©t input cá»¥m thanh toĂ¡n
            $.each($('#group-payment'), function () {
                var $tds = $(this).find("input,select,textarea");

                $.each($tds, function () {
                    if ($(this).attr("name") != null) {
                        if ($(this).attr("isValidate") == 1) {
                            //Láº¥y thĂ´ng tin validate
                            switch ($(this).attr("name")) {
                                default:
                                    rules[$(this).attr("name")] = {
                                        required: true
                                    };

                                    messages[$(this).attr("name")] = {
                                        required: $(this).attr("keyName") + ' ' + listDeal.jsonLang['không được trống']
                                    };
                            }
                        }
                        //Láº¥y dá»¯ liá»‡u cá»§a cĂ¡c trÆ°á»ng
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
                    swal(listDeal.jsonLang['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                }
            });
        });
    },
    remove: function (e, id) {
        $.getJSON(laroute.route('translate'), function (json) {
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
                    swal(listDeal.jsonLang['Chỉnh sửa hợp đồng thất bại'], mess_error, "error");
                }
            });
        });
    }
};

var listDeal = {
    jsonLang : JSON.parse(localStorage.getItem('tranlate')),
    revoke: function () {
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.revoke'),
            method: 'POST',
            dataType: 'JSON',
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-list-staff').modal('show');
                $('#staff').select2({
                    placeholder: listDeal.jsonLang['Chọn nhân viên']
                });
            }
        });
    },
    submitRevoke: function () {
        
        var form = $('#form-assign');
        form.validate({
            rules: {
                staff: {
                    required: true
                },
            },
            messages: {
                staff: {
                    required: listDeal.jsonLang['Hãy chọn nhân viên bị thu hồi']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        let staff = $("#staff option:selected").val()

        $.ajax({
            url: laroute.route('customer-lead.customer-deal.submit-revoke'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: staff
            },
            success: function (res) {
                if (res.error == false) {
                    $('#modal-list-staff').modal('hide');
                    swal(res.message, "", "success");
                    $('#autotable').PioTable('refresh');
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    
    },
    _init: function () {
        
        $('#pipeline').select2();
        $('#journey').select2();
        $('#branch').select2();
        $('#order_source_id').select2();
        $('#owner').select2();
        $('#compare').select2();

        new AutoNumeric.multiple('#value', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
        $('#pipeline').change(function () {
            $.ajax({
                url: laroute.route('customer-lead.load-option-journey'),
                dataType: 'JSON',
                data: {
                    pipeline_code: $('#pipeline').val(),
                },
                method: 'POST',
                success: function (res) {
                    $('#journey').empty();
                    $('#journey').append('<option value="">' + listDeal.jsonLang['Chọn hành trình'] + '</option>');
                    $.map(res.optionJourney, function (a) {
                        $('#journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                    });
                }
            });
        });
    
        $('#autotable').PioTable({
            baseUrl: laroute.route('customer-lead.customer-deal.list')
        });
    },

    remove: function (deal_id) {
        
        swal({
            title: listDeal.jsonLang['Thông báo'],
            text: listDeal.jsonLang["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: listDeal.jsonLang['Xóa'],
            cancelButtonText: listDeal.jsonLang['Hủy'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('customer-lead.customer-deal.destroy'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        deal_id: deal_id
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                            $('#autotable').PioTable('refresh');

                            if (window.location.href.includes('kan-ban-view')) {
                                let curElement = $(`#kanban_${deal_id}`).parent('div').children('div').length;
                                $(`#kanban_${deal_id}`).parent('div').parent('div').find(".jqx-kanban-column-header-status").html(` (${curElement - 1})`);
                                $(`#kanban_${deal_id}`).remove();
                            } else {
                                location.reload();
                            }


                        } else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                });
            }
        });
    
    },

    detail: function (deal_id) {
        
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.show'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deal_id: deal_id,
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-detail').modal('show');
                var arrRange = {};
                arrRange[listDeal.jsonLang["Hôm nay"]] = [moment(), moment()];
                arrRange[listDeal.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
                arrRange[listDeal.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
                arrRange[listDeal.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

                $(".searchDateForm").daterangepicker({
                    autoUpdateInput: false,
                    autoApply: true,
                    // buttonClasses: "m-btn btn",
                    // applyClass: "btn-primary",
                    // cancelClass: "btn-danger",
                    // startDate: moment().subtract(6, "days"),
                    startDate: moment().startOf("month"),
                    endDate: moment().endOf("month"),
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'DD/MM/YYYY',
                        "applyLabel": listDeal.jsonLang["Đồng ý"],
                        "cancelLabel": listDeal.jsonLang["Thoát"],
                        "customRangeLabel": listDeal.jsonLang['Tùy chọn ngày'],
                        daysOfWeek: [
                            listDeal.jsonLang["CN"],
                            listDeal.jsonLang["T2"],
                            listDeal.jsonLang["T3"],
                            listDeal.jsonLang["T4"],
                            listDeal.jsonLang["T5"],
                            listDeal.jsonLang["T6"],
                            listDeal.jsonLang["T7"]
                        ],
                        "monthNames": [
                            listDeal.jsonLang["Tháng 1 năm"],
                            listDeal.jsonLang["Tháng 2 năm"],
                            listDeal.jsonLang["Tháng 3 năm"],
                            listDeal.jsonLang["Tháng 4 năm"],
                            listDeal.jsonLang["Tháng 5 năm"],
                            listDeal.jsonLang["Tháng 6 năm"],
                            listDeal.jsonLang["Tháng 7 năm"],
                            listDeal.jsonLang["Tháng 8 năm"],
                            listDeal.jsonLang["Tháng 9 năm"],
                            listDeal.jsonLang["Tháng 10 năm"],
                            listDeal.jsonLang["Tháng 11 năm"],
                            listDeal.jsonLang["Tháng 12 năm"]
                        ],
                        "firstDay": 1
                    },
                    ranges: arrRange
                }).on('apply.daterangepicker', function (ev, picker) {
                    var start = picker.startDate.format("DD/MM/YYYY");
                    var end = picker.endDate.format("DD/MM/YYYY");
                    $(this).val(start + " - " + end);
                });
                $('.selectForm').select2();
            }
        });
    
    },

    popupDealCare: function (id) {
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.popup-deal-care'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deal_id: id
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-customer-care').modal('show');

                $('#popup_care_type').select2({
                    placeholder: listDeal.jsonLang['Chọn loại chăm sóc']
                });
                Work.changeBooking();
                $('.select2-active').select2();

                $(".date-timepicker").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    // format: "dd/mm/yyyy",
                    startDate: new Date()
                    // locale: 'vi'
                });


                $(".time-input").timepicker({
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

                $(".daterange-input").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    // format: "dd/mm/yyyy hh:ii",
                    format: "dd/mm/yyyy",
                    // startDate : new Date()
                    // locale: 'vi'
                });

                AutoNumeric.multiple('.input-mask,.input-mask-remind', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 0,
                    minimumValue: 0,
                });

                $('#care_type').select2({
                    placeholder: listDeal.jsonLang['Chọn loại chăm sóc']
                });

                $('.summernote').summernote({
                    placeholder: '',
                    tabsize: 2,
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname', 'fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                    ],
                    callbacks: {
                        onImageUpload: function (files) {
                            for (let i = 0; i < files.length; i++) {
                                uploadImgCk(files[i]);
                            }
                        }
                    },
                });
            }
        });
    },
    popupDealCareEdit: function (id, manage_work_id) {
        
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.popup-deal-care'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deal_id: id,
                manage_work_id: manage_work_id
            },
            success: function (res) {
                $('#modal-detail').css('opacity', 0);
                $('#modal-customer-care').css('opacity', 0);
                $('#popup-work-edit').html(res.html);
                $('#modal-customer-care-edit').modal('show');
                if (res.is_booking == 0) {
                    $('.block-hide-work').hide();
                }
                $('.select2-active').select2();

                $(".date-timepicker").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    // format: "dd/mm/yyyy",
                    startDate: new Date()
                    // locale: 'vi'
                });


                $(".time-input").timepicker({
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

                $(".daterange-input").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    // format: "dd/mm/yyyy hh:ii",
                    format: "dd/mm/yyyy",
                    // startDate : new Date()
                    // locale: 'vi'
                });

                AutoNumeric.multiple('.input-mask,.input-mask-remind', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 0,
                    minimumValue: 0,
                });

                $('#care_type').select2({
                    placeholder: listDeal.jsonLang['Chọn loại chăm sóc']
                });

                $('.summernote').summernote({
                    placeholder: '',
                    tabsize: 2,
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname', 'fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                    ],
                    callbacks: {
                        onImageUpload: function (files) {
                            for (let i = 0; i < files.length; i++) {
                                uploadImgCk(files[i]);
                            }
                        }
                    },
                });
            }
        });
    
    },
    submitDealCare: function (id) {
        
        var form = $('#form-care');

        // form.validate({
        //     rules: {
        //         popup_care_type: {
        //             required: true,
        //         },
        //         popup_content: {
        //             required: true,
        //         },
        //     },
        //     messages: {
        //         popup_care_type: {
        //             required: listDeal.jsonLang['HĂ£y chá»n loáº¡i chÄƒm sĂ³c'],
        //         },
        //         popup_content: {
        //             required: listDeal.jsonLang['HĂ£y nháº­p ná»™i dung chÄƒm sĂ³c']
        //         }
        //     },
        // });
        //
        // if (!form.valid()) {
        //     return false;
        // }

        var is_booking = 0;
        if ($('#is_booking').is(':checked')) {
            is_booking = 1;
        }
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.deal-care'),
            method: 'POST',
            dataType: 'JSON',
            // data: {
            //     care_type: $('#popup_care_type').val(),
            //     content: $('#popup_content').val(),
            //     deal_id: id,
            //     history_id: $('#history_id').val()
            // },
            data: $('#form-care').formSerialize() + '&is_booking=' + is_booking,
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        location.reload();
                        // if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                        //     $('#modal-customer-care').modal('hide');
                        //
                        //     $('.modal-backdrop').remove();
                        // }
                        // if (result.value == true) {
                        //     $('#modal-customer-care').modal('hide');
                        //
                        //     $('.modal-backdrop').remove();
                        // }
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
                console.log(mess_error);
                swal(listDeal.jsonLang['Thêm mới thất bại'], mess_error, "error");
            }
        });
    
    },
    submitDealCareEdit: function (id) {
        
        var form = $('#form-care');

        var is_booking = 0;
        if ($('#is_booking').is(':checked')) {
            is_booking = 1;
        }
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.deal-care'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#form-care-edit').formSerialize() + '&is_booking=' + is_booking,
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        Work.search();
                        $('#modal-detail').css('opacity', 1);
                        $('#modal-customer-care').css('opacity', 1);
                        $('#modal-customer-care-edit').modal('hide');
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
                console.log(mess_error);
                swal(listDeal.jsonLang['Thêm mới thất bại'], mess_error, "error");
            }
        });
    
    },
    closeModalCare: function () {
        $('#modal-customer-care').modal('hide');
        $('.modal-backdrop').remove();
    },
    closeModalCareEdit: function () {
        $('#modal-detail').css('opacity', 1);
        $('#modal-customer-care').css('opacity', 1);
        $('#modal-customer-care-edit').modal('hide');
        $('.modal-backdrop').remove();
    },
    modalCall: function (dealId) {
        $.ajax({
            url: laroute.route('customer-deal.modal-call'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deal_id: dealId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-call').modal('show');
            }
        });
    },
    call: function (dealId, phone) {
        
        $.ajax({
            url: laroute.route('customer-deal.call'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deal_id: dealId,
                phone: phone
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-call').modal('hide');

                            $('#my-modal').html(res.html);
                            $('#modal-customer-care').modal('show');

                            $('#care_type').select2({
                                placeholder: listDeal.jsonLang['Chọn loại chăm sóc']
                            });
                        }
                        if (result.value == true) {
                            $('#modal-call').modal('hide');

                            $('#my-modal').html(res.html);
                            $('#modal-customer-care').modal('show');

                            $('#care_type').select2({
                                placeholder: listDeal.jsonLang['Chọn loại chăm sóc']
                            });
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    
    },

    popupListStaff: function (customer_deal_id) {
        
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.popup-list-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_deal_id: customer_deal_id
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-list-staff').modal('show');
                $('#staff').select2({
                    placeholder: listDeal.jsonLang['Chọn nhân viên']
                });
            }
        });
    
    },
    submitAssignStaff: function () {
        
        var form = $('#form-assign');
        form.validate({
            rules: {
                staff: {
                    required: true
                },
            },
            messages: {
                staff: {
                    required: listDeal.jsonLang['Hãy chọn nhân viên đuọc phân công']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        let staff = $("#staff option:selected").val()

        $.ajax({
            url: laroute.route('customer-lead.customer-deal.save-assign-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: staff,
                customer_deal_id: $('#customer_deal_id').val()
            },
            success: function (res) {
                if (res.error == false) {
                    $('#modal-list-staff').modal('hide');
                    swal(res.message, "", "success");
                    $('#autotable').PioTable('refresh');
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(listDeal.jsonLang['Thêm mới thất bại'], mess_error, "error");
            }
        });
    
    },
    // Thu há»•i 1 lead
    revokeOne: function (id) {
        
        swal({
            title: listDeal.jsonLang['Thông báo'],
            text: listDeal.jsonLang["Bạn có muốn thu hồi không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: listDeal.jsonLang['Thu hồi'],
            cancelButtonText: listDeal.jsonLang['Hủy'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('customer-lead.customer-deal.revoke-one'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_deal_id: id
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                            $('#autotable').PioTable('refresh');
                        } else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                });
            }
        });
    
    },
};

var customerDealCreate = create = {
    popupCreate: function (load, type = '', object_id = '', view = '', data = null) {
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.create'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                load: load,
                object_type: type,
                object_id: object_id,
            },
            success: function (res) {
                if(view != ''){
                    //Từ module call center gọi qua
                    $('#modal-call-center-search').html(res.html);
                }else {
                    $('#my-modal').html(res.html);
                }
                
                $('#modal-create').modal('show');



                new AutoNumeric.multiple('#amount', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
                $("#end_date_expected").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy",
                    startDate: "dateToday"
                });

                $('#type_customer').select2({
                    placeholder: listDeal.jsonLang['Chọn loại khách hàng']
                });

                $('#staff').select2({
                    placeholder: listDeal.jsonLang['Chọn người được phân bổ']
                });
               
                $('#customer_code').select2({
                    placeholder: listDeal.jsonLang['Chọn khách hàng'],
                    ajax: {
                        url: laroute.route('customer-lead.customer-deal.search-customer'),
                        dataType: 'json',
                        delay: 250,
                        type: 'POST',
                        data: function (params) {
                            var query = {
                                type: $('#type_customer').val(),
                                search: params.term,
                                page: params.page || 1
                            };
                            return query;
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 5) < data.count_filtered
                                }
                            };
                        }
                    },
                    minimumInputLength: 0,
                    allowClear: true,
                });
                $('#customer_contact_code').select2({
                    placeholder: listDeal.jsonLang['Chọn liên hệ']
                });

                $('#pipeline_code').select2({
                    placeholder: listDeal.jsonLang['Chọn pipeline']
                });

                $('#journey_code').select2({
                    placeholder: listDeal.jsonLang['Chọn hành trình']
                });

                $('#branch_code').select2({
                    placeholder: listDeal.jsonLang['Chọn chi nhánh']
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

                $('#tag_id').select2({
                    placeholder: listDeal.jsonLang['Chọn tag'],
                    tags: true,
                    createTag: function (newTag) {
                        return {
                            id: newTag.term,
                            text: newTag.term,
                            isNew: true
                        };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.isNew) {
                        $.ajax({
                            type: "POST",
                            url: laroute.route('customer-lead.customer-deal.store-quickly-tag'),
                            data: {
                                tag_name: e.params.data.text
                            },
                            success: function (res) {
                                $('#tag_id').find('[value="' + e.params.data.text + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                            }
                        });
                    }
                });
                $('#order_source').select2({
                    placeholder: listDeal.jsonLang['Chọn nguồn đơn hàng']
                });

                if (type != '') {
                    $('#type_customer').val(type);
                }

                $('#type_customer').select2();

                customerDealCreate.loadContact();

                // $('#probability').ForceNumericOnly();
                new AutoNumeric.multiple('#probability', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 2,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

                if($('#fr_customer_id').length && $('#fr_customer_id').val() != ''){
                    $('.btn_add_pc').hide();
                    $('#type_customer').val('customer').trigger('change');
                    setTimeout(function(){
                        $('#customer_code').append('<option selected value="'+$('#fr_customer_code').val()+'">'+$('#fr_customer_full_name').val()+'</option>');
                        customerDealCreate.loadContact();
                        $('#type_customer').attr('readonly', 'readonly');
                        $('#customer_code').attr('readonly', 'readonly')
                    }, 1500)
                } else if($('#fr_customer_lead_id').length && $('#fr_customer_lead_id').val() != ''){
                    $('.btn_add_pc').hide();
                    $('#type_customer').val('lead').trigger('change');
                    setTimeout(function(){
                        $('#customer_code').append('<option selected value="'+$('#fr_customer_lead_code').val()+'">'+$('#fr_customer_lead_full_name').val()+'</option>');
                        customerDealCreate.loadContact();
                        $('#type_customer').attr('readonly', 'readonly');
                        $('#customer_code').attr('readonly', 'readonly')
                    }, 1500)
                }
            }
        });
    

    },

    loadContact: function () {
        
        $('#check-payment').attr('hidden', true);
        var customer_code = $('#customer_code').val();
        var type_customer = $('#type_customer').val();
        $('#add_phone').val('');
        $('#edit_phone').val('');
        if (customer_code == '' || customer_code == null) {
            $('#customer_contact_code').html('');
            $('#customer_contact_code').select2({
                placeholder: listDeal.jsonLang['Chá»n liĂªn há»‡']
            });
        }
        else {
            $('#customer_contact_code-remove').removeAttr('hidden');
            $('#customer_contact_code').html('');
            $.ajax({
                url: laroute.route('customer-lead.customer-deal.load-option-customer-contact'),
                dataType: 'JSON',
                data: {
                    customer_code: customer_code,
                    type_customer: type_customer
                },
                method: 'POST',
                success: function (result) {
                    if (result.length != 0) {
                        $('#add_phone').val(result.contact_phone);
                        $('#edit_phone').val(result.contact_phone);
                        if (type_customer == 'lead') {
                            if (result.customer_type == 'business') {
                                if (result.representative != null) {
                                    $('#customer_contact_code').append('<option value="">' + result.representative + '</option>');
                                }
                                else {
                                    $('#customer_contact_code').append('<option value="">' + listDeal.jsonLang['Chọn liên hệ'] + '</option>');
                                }

                                $('#customer_contact_code').select2();
                                return;
                            } else {
                                $('#customer_contact_code-remove').attr('hidden', true);
                                return;
                            }
                        }
                        else {
                            $('#customer_contact_code-remove').attr('hidden', true);
                            return;
                        }
                        // $.each(result, function (key, value) {
                        //     $('#customer_contact_code').append('<option value=' + value.customer_contact_code + '>' + value.full_address + '</option>'); // return empty
                        // });
                    }
                    else {
                        $('#customer_contact_code-remove').attr('hidden', true);
                        return;
                    }

                    $('#customer_contact_code').select2({
                        placeholder: listDeal.jsonLang['Chọn liên hệ']
                    });
                }
            });
        }
    
    },
    clearCustomerContact: function () {
        $('#customer_code').html('');
        $('#customer_contact_code').html('');
        $('#add_phone').val('');
        $('#edit_phone').val('');
        $('#customer_contact_code').select2({
            placeholder: listDeal.jsonLang['Chọn liên hệ']
        });
    },

    processFunctionAddCusDeal : function(data){
        $('#modal-create').modal('hide');
        window.close();
        window.postMessage({
            'func': 'addSuccessCustomerDeal',
            'message' : data
        }, "*");
    },

    processFunctionCancelDeal : function(data){
        window.postMessage({
            'func': 'cancelDeal',
            'message' : data
        }, "*");
    },

    cancelDeal :function () {
        $('#modal-create').modal('hide');

        if(typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup'){
            customerDealCreate.processFunctionCancelDeal({});
        }

    },

    save: function () {
        
        var form = $('#form-create');

        form.validate({
            rules: {
                deal_name: {
                    required: true,
                    maxlength: 255
                },
                staff: {
                    required: true
                },
                customer_code: {
                    required: true
                },
                pipeline_code: {
                    required: true
                },
                journey_code: {
                    required: true
                },
                end_date_expected: {
                    required: true
                },
                add_phone: {
                    required: true,
                    integer: true,
                    maxlength: 10
                },
                deal_description: {
                    maxlength: 255
                },
            },
            messages: {
                deal_name: {
                    required: listDeal.jsonLang['Hãy nhập tên deal'],
                    maxlength: listDeal.jsonLang['Tên deal tối đa 255 kí tự']
                },
                staff: {
                    required: listDeal.jsonLang['Hãy chọn người được phân bổ deal']
                },
                customer_code: {
                    required: listDeal.jsonLang['Hãy chọn khách hàng']
                },
                pipeline_code: {
                    required: listDeal.jsonLang['Hãy chọn pipeline']
                },
                journey_code: {
                    required: listDeal.jsonLang['Hãy chọn hành trình khách hàng']
                },
                end_date_expected: {
                    required: listDeal.jsonLang['Hãy chọn ngày kết thúc dự kiến']
                },
                add_phone: {
                    required: listDeal.jsonLang['Hãy nhập số điện thoại'],
                    integer: listDeal.jsonLang['Số điện thoại không hợp lệ'],
                    maxlength: listDeal.jsonLang['Số điện thoại tối đa 10 kí tự']
                },
                deal_description: {
                    maxlength: listDeal.jsonLang['Chi tiết deal tối đa 255 kí tự']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }
        var flag = true;


        // check object
        $.each($('#table_add > tbody').find('.add-object'), function () {

            var object_type = $(this).find($('.object_type')).val();
            var object_code = $(this).find($('.object_code')).val();

            if (object_type == "") {
                $(this).find($('.error_object_type')).text(listDeal.jsonLang['Vui lòng chọn loại sản phẩm']);
                flag = false;
            } else {
                $(this).find($('.error_object_type')).text('');
            }
            if (object_code == "") {
                $(this).find($('.error_object')).text(listDeal.jsonLang['Vui lòng chọn sản phẩm']);
                flag = false;
            } else {
                $(this).find($('.error_object')).text('');
            }
        });

        // Láº¥y danh sĂ¡ch object (náº¿u cĂ³)
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

        if (flag == true) {
            $.ajax({
                url: laroute.route('customer-lead.customer-deal.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    type_customer: $('#type_customer').val(),
                    deal_name: $('#deal_name').val(),
                    staff: $('#staff').val(),
                    customer_code: $('#customer_code').val(),
                    customer_contact_code: $('#customer_contact_code').val(),
                    phone: $('#add_phone').val(),
                    pipeline_code: $('#pipeline_code').val(),
                    journey_code: $('#journey_code').val(),
                    branch_code: $('#branch_code').val(),
                    tag_id: $('#tag_id').val(),
                    order_source_id: $('#order_source').val(),
                    amount: $('#amount').val(),
                    probability: $('#probability').val(),
                    end_date_expected: $('#end_date_expected').val(),
                    deal_description: $('#deal_description').val(),
                    arrObject: arrObject
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {

                            if(typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup'){
                                customerDealCreate.processFunctionAddCusDeal(res.data);
                            } else {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('customer-lead.customer-deal');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('customer-lead.customer-deal');
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
                    swal(listDeal.jsonLang['Thêm thất bại'], mess_error, "error");
                }
            });
        }
    
    },

    popupCreateLead: function (type = 'add') {
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.popup-created-customer-lead'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#my-modal-create-lead').html(res.html);
                type == 'add' ? $('#modal-create').modal('hide') : $('#modal-edit').modal('hide');
                $('#modal-create-lead').modal('show');

                $('#modal-create-lead').on('shown.bs.modal', function (e) {
                    type == 'add' ? $('#modal-create').modal('hide') : $('#modal-edit').modal('hide');
                });
                $('#modal-create-lead').on('hidden.bs.modal', function (e) {
                    type == 'add' ? $('#modal-create').modal('show') : $('#modal-edit').modal('show');
                });

                $('#popup_pipeline_code').select2({
                    placeholder: listDeal.jsonLang['Chọn pipeline']
                });

                $('#popup_journey_code').select2({
                    placeholder: listDeal.jsonLang['Chọn hành trình']
                });

                if(typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup'){
                    $('#popup_full_name').val($('#fr_full_name').val());
                    $('<input>').attr('type','hidden').attr('name','ch_customer_id').attr('id','ch_customer_id')
                        .attr('value',$('#fr_ch_customer_id').val()).appendTo('#form-create-lead');
                }


                $('#popup_pipeline_code').change(function () {
                    $.ajax({
                        url: laroute.route('customer-lead.load-option-journey'),
                        dataType: 'JSON',
                        data: {
                            pipeline_code: $('#popup_pipeline_code').val(),
                        },
                        method: 'POST',
                        success: function (res) {
                            $('.popup_journey').empty();
                            $.map(res.optionJourney, function (a) {
                                $('.popup_journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                            });
                        }
                    });
                });

                $('#popup_customer_source').select2({
                    placeholder: listDeal.jsonLang['Chọn nguồn khách hàng']
                });
                $('#popup_customer_type').select2({
                    placeholder: listDeal.jsonLang['Chọn loại khách hàng']
                });
            }
        });
    
    },
    loadMoreInfo: function (e) {
        if ($(e).val() == 'business') {
            $('.more_info').removeAttr('hidden');
        } else {
            $('.more_info').attr('hidden', true);
        }
    },

    processFunctionAddCusLead : function(data){
        window.postMessage({
            'func': 'addSuccessCustomerLead',
            'message' : data
        }, "*");
    },

    processFunctionCancelCusLead : function(data){
        window.postMessage({
            'func': 'cancelCustomerLead',
            'message' : data
        }, "*");
    },

    cancelLead: function(){
        $('#modal-create-lead').modal('hide');
        if(typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup'){
            customerDealCreate.processFunctionCancelCusLead({});
        }
    },

    saveLead: function () {
        var form = $('#form-create-lead');

       
        form.validate({
            rules: {
                popup_full_name: {
                    required: true,
                    maxlength: 250
                },
                popup_phone: {
                    required: true,
                    integer: true,
                    maxlength: 10
                },
                popup_pipeline_code: {
                    required: true
                },
                popup_journey_code: {
                    required: true
                },
                popup_customer_source: {
                    required: true
                }
            },
            messages: {
                popup_full_name: {
                    required: listDeal.jsonLang['Hãy nhập họ và tên'],
                    maxlength: listDeal.jsonLang['Họ và tên tối đa 250 kí tự']
                },
                popup_phone: {
                    required: listDeal.jsonLang['Hãy nhập số điện thoại'],
                    integer: listDeal.jsonLang['Số điện thoại không hợp lệ'],
                    maxlength: listDeal.jsonLang['Số điện thoại tối đa 10 kí tự']
                },
                popup_pipeline_code: {
                    required: listDeal.jsonLang['Hãy chọn pipeline']
                },
                popup_journey_code: {
                    required: listDeal.jsonLang['Hãy chọn hành trình khách hàng']
                },
                popup_customer_source: {
                    required: listDeal.jsonLang['Hãy chọn nguồn khách hàng']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        let ch_customer_id = null;
        if($('#ch_customer_id').length){
            ch_customer_id = $('#ch_customer_id').val();
        }

        $.ajax({
            url: laroute.route('customer-lead.customer-deal.store-customer-lead'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                full_name: $('#popup_full_name').val(),
                phone: $('#popup_phone').val(),
                pipeline_code: $('#popup_pipeline_code').val(),
                journey_code: $('#popup_journey_code').val(),
                customer_type: $('#popup_customer_type').val(),
                customer_source: $('#popup_customer_source').val(),
                tax_code: $('#popup_tax_code').val(),
                representative: $('#popup_representative').val(),
                ch_customer_id : ch_customer_id
            },
            success: function (res) {
                if (res.error == false) {

                    $('#type_customer').val('lead');
                    $('#add_phone').val($('#popup_phone').val());
                    $('#edit_phone').val($('#popup_phone').val());
                    $('#type_customer').select2();
                    $('#customer_code').append('<option value="' + res.data.customer_lead_code + '" selected>' + res.data.full_name + '</option>');
                    if (res.data.customer_type == 'personal') {
                        $('#customer_contact_code-remove').attr('hidden', true);
                    }
                    else {
                        $('#customer_contact_code-remove').removeAttr('hidden');
                        console.log(res.data.representative, res.data.representative != '');
                        if (res.data.representative != null) {
                            $('#customer_contact_code').append('<option value="' + res.data.representative + '" selected>' + res.data.representative + '</option>');
                        }
                    }
                    swal(res.message, "", "success").then(function (result) {
                        $('#modal-create-lead').modal('hide');
                        // $('#modal-create').modal('show');
                    });

                    if(typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup'){
                        customerDealCreate.processFunctionAddCusLead(res.data);
                    }
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(listDeal.jsonLang['Thêm mới thất bại'], mess_error, "error");
            }
        });
    
    }
}

var viewCusDeal = view = {
    addObject: function () {
        
        stt++;
        var tpl = $('#tpl-object').html();
        tpl = tpl.replace(/{stt}/g, stt);
        $('.append-object').append(tpl);
        $('.object_type').select2({
            placeholder: listDeal.jsonLang['Chọn loại']
        });

        $('.object_code').select2({
            placeholder: listDeal.jsonLang['Chọn đối tượng']
        });

        $(".object_quantity").TouchSpin({
            initval: 1,
            min: 1,
            buttondown_class: "btn btn-metal btn-sm",
            buttonup_class: "btn btn-metal btn-sm"

        });

        // TĂ­nh láº¡i giĂ¡ khi thay Ä‘á»•i sá»‘ lÆ°á»£ng
        $('.object_quantity, .object_discount, .object_price').change(function () {
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

            var amount = ((price * quantity) - loc) > 0 ? ((price * quantity) - loc) : 0;

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

        new AutoNumeric.multiple('#object_discount_' + stt + ', #object_price_' + stt + '', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
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
        
        var object = $(obj).val();
        // product, service, service_card
        $(obj).closest('tr').find('.object_code').prop('disabled', false);
        $(obj).closest('tr').find('.object_code').empty();
        $(obj).closest('tr').find('.object_code').val('').trigger('change');

        $(obj).closest('tr').find('.object_code').select2({
            placeholder: listDeal.jsonLang['Chọn đối tượng'],
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
        }).on('select2:open', function (e) {
            const evt = "scroll.select2";
            $(e.target).parents().off(evt);
            $(window).off(evt);
        });
    
    },

    changeObject: function (obj) {
        var object_type = $(obj).closest('tr').find('.object_type').val();
        var object_code = $(obj).val();
        var stt_row = $(obj).closest('tr').find('.stt_row').val();

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
                //Remove giá trong td
                $('.td_object_price_' + stt_row + '').empty();

                if (Object.keys(result).length === 0) {
                    //Append lại input giá
                    var tplPrice = $('#tpl-object-price').html();
                    tplPrice = tplPrice.replace(/{stt}/g, stt_row);
                    tplPrice = tplPrice.replace(/{price}/g, 0);
                    $('.td_object_price_' + stt_row + '').append(tplPrice);

                    $(obj).closest('tr').find($('.object_amount')).val(formatNumber(Number(0).toFixed(decimal_number)));
                } else {
                    if (object_type == 'product') {
                        //Append lại input giá
                        var tplPrice = $('#tpl-object-price').html();
                        tplPrice = tplPrice.replace(/{stt}/g, stt_row);
                        tplPrice = tplPrice.replace(/{price}/g, result.price);
                        $('.td_object_price_' + stt_row + '').append(tplPrice);

                        // Reset sá»‘ lÆ°á»£ng vá» 1, TĂ­nh láº¡i tiá»n * sá»‘ lÆ°á»£ng
                        $(obj).closest('tr').find('.object_quantity').val(1);

                        var discount = $(obj).closest('tr').find('.object_discount').val().replace(new RegExp('\\,', 'g'), '');
                        var amount = Number(result.price) - discount;
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));

                        $(obj).closest('tr').find('.object_id').val(result.product_child_id);
                    } else if (object_type == 'service') {
                        //Append lại input giá
                        var tplPrice = $('#tpl-object-price').html();
                        tplPrice = tplPrice.replace(/{stt}/g, stt_row);
                        tplPrice = tplPrice.replace(/{price}/g, result.price_standard);
                        $('.td_object_price_' + stt_row + '').append(tplPrice);

                        $(obj).closest('tr').find('.object_quantity').val(1);

                        var discount = $(obj).closest('tr').find('.object_discount').val().replace(new RegExp('\\,', 'g'), '');
                        var amount = Number(result.price_standard) - discount;
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));

                        $(obj).closest('tr').find('.object_id').val(result.service_id);
                    } else if (object_type == 'service_card') {
                        //Append lại input giá
                        var tplPrice = $('#tpl-object-price').html();
                        tplPrice = tplPrice.replace(/{stt}/g, stt_row);
                        tplPrice = tplPrice.replace(/{price}/g, result.price);
                        $('.td_object_price_' + stt_row + '').append(tplPrice);

                        $(obj).closest('tr').find('.object_quantity').val(1);

                        var discount = $(obj).closest('tr').find('.object_discount').val().replace(new RegExp('\\,', 'g'), '');
                        var amount = Number(result.price) - discount;
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));

                        $(obj).closest('tr').find('.object_id').val(result.service_card_id);
                    }
                }

                // TĂ­nh láº¡i tá»•ng tiá»n
                $('#amount').empty();
                $('#amount-remove').html('');
                $('#amount-remove').append(`<input type="text" class="form-control m-input" id="amount" name="amount">`);
                var sum = 0;
                $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                    sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                });
                $('#amount').val(formatNumber(sum.toFixed(decimal_number)));

                new AutoNumeric.multiple('#amount, #object_price_' + stt_row + '', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

                $('.object_price').change(function () {
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

                    var amount = ((price * quantity) - loc) > 0 ? ((price * quantity) - loc) : 0;

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
            }
        });


    },

    openTab: function (id = 1) {
        $('#infor-detail').attr('hidden', true);
        $('#recent-activity').attr('hidden', true);
        $('#info-customer').attr('hidden', true);
        $('#support-customer').attr('hidden', true);
        $('#history-customer').attr('hidden', true);
        $('#comment-customer').attr('hidden', true);
        switch (id) {
            case 1:
                $('#infor-detail').removeAttr('hidden');
                break;
            case 2:
                $('#recent-activity').removeAttr('hidden');
                break;
            case 3:
                $('#info-customer').removeAttr('hidden');
                break;
            case 4:
                $('#support-customer').removeAttr('hidden');
                break;
            case 5:
                $('#history-customer').removeAttr('hidden');
                break;
            case 6:
                $('#comment-customer').removeAttr('hidden');
                DealComment.getListComment();
                break;
        }
    }
}

var edit = {
    popupEdit: function (id, load) {
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deal_id: id,
                load: load
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-edit').modal('show');

                new AutoNumeric.multiple('#amount', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
                $("#end_date_expected, #end_date_actual").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy",
                    startDate: "dateToday"
                });
                $('#staff').select2({
                    placeholder: listDeal.jsonLang['Chọn người được phân bổ']
                });

                $('#customer_code').select2({
                    placeholder: listDeal.jsonLang['Chọn khách hàng'],
                    ajax: {
                        url: laroute.route('customer-lead.customer-deal.search-customer'),
                        dataType: 'json',
                        delay: 250,
                        type: 'POST',
                        data: function (params) {
                            var query = {
                                type: $('#type_customer').val(),
                                search: params.term,
                                page: params.page || 1
                            };
                            return query;
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 5) < data.count_filtered
                                }
                            };
                        }
                    },
                    minimumInputLength: 0,
                    allowClear: true,
                });

                $('#customer_contact_code').select2({
                    placeholder: listDeal.jsonLang['Chọn liên hệ']
                });

                $('#pipeline_code').select2({
                    placeholder: listDeal.jsonLang['Chọn pipeline']
                });

                $('#journey_code').select2({
                    placeholder: listDeal.jsonLang['Chọn hành trình']
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
                $('#branch_code').select2({
                    placeholder: listDeal.jsonLang['Chọn chi nhánh']
                });
                $('#type_customer').select2({
                    placeholder: listDeal.jsonLang['Chọn chi nhánh']
                });


                $('#tag_id').select2({
                    placeholder: listDeal.jsonLang['Chọn tag'],
                    tags: true,
                    createTag: function (newTag) {
                        return {
                            id: newTag.term,
                            text: newTag.term,
                            isNew: true
                        };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.isNew) {
                        $.ajax({
                            type: "POST",
                            url: laroute.route('customer-lead.customer-deal.store-quickly-tag'),
                            data: {
                                tag_name: e.params.data.text
                            },
                            success: function (res) {
                                $('#tag_id').find('[value="' + e.params.data.text + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                            }
                        });
                    }
                });

                $('.object_type').select2({
                    placeholder: listDeal.jsonLang['Chọn loại']
                });

                $('.object_code').select2({
                    placeholder: listDeal.jsonLang['Chọn đối tượng']
                });

                $(".object_quantity").TouchSpin({
                    initval: 1,
                    min: 1,
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"

                });

                $('.object_quantity, .object_discount, .object_price').change(function () {
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
                    var discount = $(this).closest('tr').find('input[name="object_discount"]').val().replace(new RegExp('\\,', 'g'), '');
                    var loc = discount.replace(new RegExp('\\,', 'g'), '');
                    var quantity = $(this).closest('tr').find('input[name="object_quantity"]').val();

                    var amount = ((price * quantity) - loc) > 0 ? ((price * quantity) - loc) : 0;

                    $(this).closest('tr').find('.object_amount').val(formatNumber(amount.toFixed(decimal_number)));


                    $('#amount').empty();
                    var sum = 0;
                    $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
                        sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
                    });
                    $('#amount').val(formatNumber(sum.toFixed(decimal_number)));

                });

                // $('#probability').ForceNumericOnly();
                new AutoNumeric.multiple('.object_discount, #probability, .object_price', {
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
    loadDealOrKanban: function () {
        if (window.location.href.includes('kan-ban-view')) {
            $('#modal-edit').modal('hide');
            kanBanView.loadKanban();
        } else {
            window.location.href = laroute.route('customer-lead.customer-deal');
        }
    },
    save: function () {
        
        var form = $('#form-edit');

        form.validate({
            rules: {
                deal_name: {
                    required: true,
                    maxlength: 255
                },
                staff: {
                    required: true
                },
                customer_code: {
                    required: true
                },
                pipeline_code: {
                    required: true
                },
                journey_code: {
                    required: true
                },
                end_date_expected: {
                    required: true
                },
                edit_phone: {
                    required: true,
                    integer: true,
                    maxlength: 10
                },
                deal_description: {
                    maxlength: 255
                },
            },
            messages: {
                deal_name: {
                    required: listDeal.jsonLang['Hãy nhập tên deal'],
                    maxlength: listDeal.jsonLang['Tên deal tối đa 255 kí tự']
                },
                staff: {
                    required: listDeal.jsonLang['Hãy chọn người được phân bổ deal']
                },
                customer_code: {
                    required: listDeal.jsonLang['Hãy chọn khách hàng']
                },
                pipeline_code: {
                    required: listDeal.jsonLang['Hãy chọn pipeline']
                },
                journey_code: {
                    required: listDeal.jsonLang['Hãy chọn hành trình khách hàng']
                },
                end_date_expected: {
                    required: listDeal.jsonLang['Hãy chọn ngày kết thúc dự kiến']
                },
                edit_phone: {
                    required: listDeal.jsonLang['Hãy nhập số điện thoại'],
                    integer: listDeal.jsonLang['Số điện thoại không hợp lệ'],
                    maxlength: listDeal.jsonLang['Số điện thoại tối đa 10 kí tự']
                },
                deal_description: {
                    maxlength: listDeal.jsonLang['Chi tiết deal tối đa 255 kí tự']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }
        var flag = true;


        // check object
        $.each($('#table_add > tbody').find('.add-object'), function () {

            var object_type = $(this).find($('.object_type')).val();
            var object_code = $(this).find($('.object_code')).val();

            if (object_type == "") {
                $(this).find($('.error_object_type')).text(listDeal.jsonLang['Vui lòng chọn loại sản phẩm']);
                flag = false;
            } else {
                $(this).find($('.error_object_type')).text('');
            }
            if (object_code == "") {
                $(this).find($('.error_object')).text(listDeal.jsonLang['Vui lòng chọn sản phẩm']);
                flag = false;
            } else {
                $(this).find($('.error_object')).text('');
            }
        });

        // Láº¥y danh sĂ¡ch object (náº¿u cĂ³)
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

        if (flag == true) {
            $.ajax({
                url: laroute.route('customer-lead.customer-deal.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    type_customer: $('#type_customer').val(),
                    deal_id: $('#deal_id').val(),
                    deal_code: $('#deal_code').val(),
                    deal_name: $('#deal_name').val(),
                    phone: $('#edit_phone').val(),
                    staff: $('#staff').val(),
                    customer_code: $('#customer_code').val(),
                    customer_contact_code: $('#customer_contact_code').val(),
                    pipeline_code: $('#pipeline_code').val(),
                    journey_code: $('#journey_code').val(),
                    branch_code: $('#branch_code').val(),
                    tag_id: $('#tag_id').val(),
                    order_source_id: $('#order_source').val(),
                    amount: $('#amount').val(),
                    probability: $('#probability').val(),
                    end_date_expected: $('#end_date_expected').val(),
                    end_date_actual: $('#end_date_actual').val(),
                    reason_lose_code: $('#reason_lose_code').val(),
                    deal_description: $('#deal_description').val(),
                    arrObject: arrObject
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if ((result.dismiss == 'esc' || result.dismiss == 'backdrop') || result.value == true) {
                                if (res.check_cannot_create_contract) {
                                    swal({
                                        title: listDeal.jsonLang["Đã tới hành trình tạo hợp đồng, nhưng sản phẩm của deal đã bao gồm sản phẩm có tính kpi và không tính, vì vậy không thể tạo hợp đồng!"],
                                        type: 'warning',
                                    }).then(function (result) {
                                        edit.loadDealOrKanban();
                                    });
                                }
                                else if (res.check_create_contract_annex == 1) {
                                    swal({
                                        title: listDeal.jsonLang["Thông báo"],
                                        text: $('#deal_name').val() + " " + listDeal.jsonLang["đã thành công bạn có muốn gia hạn hợp đồng với deal này không?"],
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: listDeal.jsonLang["Có"],
                                        cancelButtonText: listDeal.jsonLang["Không"]
                                    }).then(function (result) {
                                        if (result.value) {
                                            contractAnnex.popupAddContractAnnex(res.contract_id, res.deal_code);
                                            // window.location.href = '/contract/contract/show/' + res.contract_id;
                                        }
                                        else {
                                            edit.loadDealOrKanban();
                                        }
                                    });
                                }
                                else if (res.check_create_contract == 1) {
                                    swal({
                                        title: listDeal.jsonLang["Thông báo"],
                                        text: listDeal.jsonLang["Bạn có muốn tạo hợp đồng cho deal"] + " " + res.deal_code + " " + listDeal.jsonLang["nĂ y khĂ´ng?"],
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: listDeal.jsonLang["Có"],
                                        cancelButtonText: listDeal.jsonLang["Không"]
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.href = laroute.route('contract.contract.create') + "?type=from_deal&deal_code=" + res.deal_code;
                                        }
                                        else {
                                            edit.loadDealOrKanban();
                                        }
                                    });
                                }
                                else if (res.checkInsertOrder) {
                                    swal({
                                        title: listDeal.jsonLang["Thông báo"],
                                        text: $('#deal_name').val() + " " + listDeal.jsonLang["đã thành công bạn có muốn tạo đơn hàng hoặc thanh toán với deal này không?"],
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: listDeal.jsonLang["Có"],
                                        cancelButtonText: listDeal.jsonLang["Không"]
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.href = '/customer-lead/customer-deal/payment/' + res.deal_id;
                                        }
                                        else {
                                            edit.loadDealOrKanban();
                                        }
                                    });
                                }
                                else {
                                    edit.loadDealOrKanban();
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
                    swal(listDeal.jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
                }
            });
        }
   
    },

    saveCustomer: function () {
        
        var form = $('#form-create-customer');

        form.validate({
            rules: {
                customer_full_name: {
                    required: true
                },
                customer_phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 11
                },
                customer_address: {
                    required: true
                },
                customer_province_id: {
                    required: true
                },
                customer_district_id: {
                    required: true
                }
            },
            messages: {
                customer_full_name: {
                    required: listDeal.jsonLang["Hãy nhập tên khách hàng"]
                },
                customer_phone: {
                    required: listDeal.jsonLang["Hãy nhập số điện thoại"],
                    number: listDeal.jsonLang["Số điện thoại không hợp lệ"],
                    minlength: listDeal.jsonLang["Tối thiểu 10 số"],
                    maxlength: listDeal.jsonLang["Tối đa 11 số"]
                },
                customer_address: {
                    required: listDeal.jsonLang["Hãy nhập địa chỉ"]
                },
                customer_province_id: {
                    required: listDeal.jsonLang["Hãy chọn tỉnh thành"]
                },
                customer_district_id: {
                    required: listDeal.jsonLang["Hãy chọn quận huyện"]
                }
            },
        });

        if (!form.valid()) {
            return false;
        }
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.create-customer-from-deal'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_lead_code: $('#customer_lead_code').val(),
                full_name: $('#customer_full_name').val(),
                day: $('#customer_day').val(),
                month: $('#customer_month').val(),
                year: $('#customer_year').val(),
                gender: $('input[name="customer_gender"]:checked').val(),
                phone: $('#customer_phone').val(),
                province_id: $('#customer_province_id').val(),
                district_id: $('#customer_district_id').val(),
                address: $('#customer_address').val(),
                email: $('#customer_email').val(),
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if ((result.dismiss == 'esc' || result.dismiss == 'backdrop') || result.value == true) {
                            window.location.href = laroute.route('customer-lead.customer-deal');
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
                swal(listDeal.jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    
    }
}

var idClick = '';

var arrOldSaleChecked = [];
var assign = {
    _init: function () {
        
        $('#autotable').PioTable({
            baseUrl: laroute.route('customer-lead.customer-deal.list-lead-not-assign-yet')
        });
        $('#department').select2({
            placeholder: listDeal.jsonLang['Chọn phòng ban']
        }).on('select2:select', function (e) {
            // Bá» check all sale
            $('#checkAllSale').prop("checked", false);

            let arrDepartment = $('#department').val();
            // load option sales
            $.ajax({
                url: laroute.route('customer-lead.load-option-sale'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrayDepartment: arrDepartment,
                },
                success: function (res) {
                    $('#staff').empty();
                    $.map(res.optionStaff, function (a) {
                        // náº¿u Ä‘Ă£ tá»“n táº¡i trong máº£ng arrOldSaleChecked thĂ¬ checked
                        if (!arrOldSaleChecked.includes(a.staff_id)) {
                            console.log(true);
                            $('#staff').append('<option value="' + a.staff_id + '">' + a.full_name + '</option>');
                        } else {
                            console.log(arrOldSaleChecked);
                            $('#staff').append('<option value="' + a.staff_id + '" selected>' + a.full_name + '</option>');
                        }
                    });
                }
            });
        }).on('select2:unselect', function (e) {
            // Bá» check all sale
            $('#checkAllSale').prop("checked", false);

            let arrDepartment = $('#department').val();
            $.ajax({
                url: laroute.route('customer-lead.load-option-sale'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    arrayDepartment: arrDepartment,
                },
                success: function (res) {
                    $('#staff').empty();
                    $.map(res.optionStaff, function (a) {
                        $('#staff').append('<option value="' + a.staff_id + '">' + a.full_name + '</option>');
                    });
                }
            });
        });

        $('#staff').select2({
            placeholder: listDeal.jsonLang['Chọn sale']
        }).on('select2:unselect', function (e) {
            arrOldSaleChecked = $('#staff').val().map(function (i) {
                return parseInt(i, 10);
            });
        }).on('select2:select', function (e) {
            arrOldSaleChecked = $('#staff').val().map(function (i) {
                return parseInt(i, 10);
            });
        });

        $('#pipeline_code').select2({
            placeholder: listDeal.jsonLang['Chọn pipeline']
        });

        $('#journey_code').select2({
            placeholder: listDeal.jsonLang['Chọn hành trình']
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
                    $('#journey_code').empty();
                    $('#journey_code').append('<option value="">' + listDeal.jsonLang['Chọn hành trình'] + '</option>');
                    $.map(res.optionJourney, function (a) {
                        $('#journey_code').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                    });
                }
            });
        });
    
    },
    checkAllSale: function () {
        $('#staff').val('').trigger("change");
        if ($('#checkAllSale').is(':checked')) {
            $('#staff > option').prop("selected", "selected");
            $('#staff').trigger("change");
            arrOldSaleChecked = $('#staff').val().map(function (i) {
                return parseInt(i, 10);
            });
            console.log(arrOldSaleChecked);
        } else {
            arrOldSaleChecked = [];
        }
    },
    chooseAll: function (obj) {
        if ($(obj).is(':checked')) {
            $('.check_one').prop('checked', true);
            let arrCheck = [];
            $('.check_one').each(function () {
                arrCheck.push({
                    deal_id: $(this).parents('label').find('.deal_id').val(),
                    deal_code: $(this).parents('label').find('.deal_code').val(),
                    time_revoke_lead: $(this).parents('label').find('.time_revoke_lead').val()
                });
            });

            $.ajax({
                url: laroute.route('customer-lead.customer-deal.choose-all'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arr_check: arrCheck
                }
            });
        } else {
            $('.check_one').prop('checked', false);

            var arrUnCheck = [];
            $('.check_one').each(function () {
                arrUnCheck.push({
                    deal_id: $(this).parents('label').find('.deal_id').val(),
                    deal_code: $(this).parents('label').find('.deal_code').val(),
                    time_revoke_lead: $(this).parents('label').find('.time_revoke_lead').val()

                });
            });

            $.ajax({
                url: laroute.route('customer-lead.customer-deal.un-choose-all'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arr_un_check: arrUnCheck
                }
            });
        }
    },
    choose: function (obj) {
        if ($(obj).is(":checked")) {
            let dealId = '';
            let dealCode = '';
            let timeRevokeLead = '';
            dealId = $(obj).parents('label').find('.deal_id').val();
            dealCode = $(obj).parents('label').find('.deal_code').val();
            timeRevokeLead = $(obj).parents('label').find('.time_revoke_lead').val();

            $.ajax({
                url: laroute.route('customer-lead.customer-deal.choose'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    deal_id: dealId,
                    deal_code: dealCode,
                    time_revoke_lead: timeRevokeLead,
                }
            });
        } else {
            let dealId = '';
            let dealCode = '';
            let timeRevokeLead = '';
            dealId = $(obj).parents('label').find('.deal_id').val();
            dealCode = $(obj).parents('label').find('.deal_code').val();
            timeRevokeLead = $(obj).parents('label').find('.time_revoke_lead').val();

            $.ajax({
                url: laroute.route('customer-lead.customer-deal.un-choose'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    deal_id: dealId,
                    deal_code: dealCode,
                    time_revoke_lead: timeRevokeLead,
                }
            });
        }
    },
    checkAllLead: function () {
        if ($('#checkAllLead').is(":checked")) {
            $('.check_one').prop('checked', true);
            $.ajax({
                url: laroute.route('customer-lead.customer-deal.check-all-deal'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    is_check_all: 1,
                    search: $('input[name=search]').val(),
                    pipeline_code: $('#pipeline_code option:selected').val(),
                    journey_code: $('#journey_code option:selected').val()
                },
                success: function (res) {
                    $('#autotable').PioTable('refresh');
                }
            });
        } else {
            $('.check_one').prop('checked', false);
            $.ajax({
                url: laroute.route('customer-lead.customer-deal.check-all-deal'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    is_check_all: 0
                },
                success: function (res) {
                    $('#autotable').PioTable('refresh');
                }
            });
        }
    },

    submit: function () {
        
        var form = $('#form-assign');
        form.validate({
            rules: {
                department: {
                    required: true
                },
                staff: {
                    required: true
                },
            },
            messages: {
                department: {
                    required: listDeal.jsonLang['Hãy chọn phòng ban']
                },
                staff: {
                    required: listDeal.jsonLang['Hãy chọn nhân viên bị thu hồi']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        let arrStaff = $("#staff").val();

        $.ajax({
            url: laroute.route('customer-lead.customer-deal.submit-assign'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                arrStaff: arrStaff,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success");
                    // $('#autotable').PioTable('refresh');
                    window.location.href = laroute.route('customer-lead.customer-deal');
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    
    }
}

var kanBanView = {
    _init: function () {
        
        $('#kanban_pipeline_id').select2({
            placeholder: listDeal.jsonLang['Chọn pipeline']
        });
        $('#kanban_type_customer').select2({
            placeholder: listDeal.jsonLang['Chọn loại khách hàng']
        });
        $('#kanban_order_source_id').select2({
            placeholder: listDeal.jsonLang['Chọn nguồn đơn hàng']
        });
        $('#kanban_branch_code').select2({
            placeholder: listDeal.jsonLang['Chọn chi nhánh']
        });
        $('#kanban_care_type').select2({
            placeholder: listDeal.jsonLang['Chọn loại chăm sóc khách hàng']
        });
        $(".m_selectpicker").selectpicker();
        var arrRange = {};
        arrRange[listDeal.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[listDeal.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[listDeal.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[listDeal.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[listDeal.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[listDeal.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $("#kanban_closing_date").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            buttonClasses: "m-btn btn",
            applyClass: "btn-primary",
            cancelClass: "btn-danger",
            maxDate: moment().endOf("day"),
            startDate: moment().startOf("day"),
            endDate: moment().add(1, 'days'),
            locale: {
                format: 'DD/MM/YYYY',
                "applyLabel": listDeal.jsonLang["Đồng ý"],
                "cancelLabel": listDeal.jsonLang["Thoát"],
                "customRangeLabel": listDeal.jsonLang["Tùy chọn ngày"],
                daysOfWeek: [
                    listDeal.jsonLang["CN"],
                    listDeal.jsonLang["T2"],
                    listDeal.jsonLang["T3"],
                    listDeal.jsonLang["T4"],
                    listDeal.jsonLang["T5"],
                    listDeal.jsonLang["T6"],
                    listDeal.jsonLang["T7"]
                ],
                "monthNames": [
                    listDeal.jsonLang["Tháng 1 năm"],
                    listDeal.jsonLang["Tháng 2 năm"],
                    listDeal.jsonLang["Tháng 3 năm"],
                    listDeal.jsonLang["Tháng 4 năm"],
                    listDeal.jsonLang["Tháng 5 năm"],
                    listDeal.jsonLang["Tháng 6 năm"],
                    listDeal.jsonLang["Tháng 7 năm"],
                    listDeal.jsonLang["Tháng 8 năm"],
                    listDeal.jsonLang["Tháng 9 năm"],
                    listDeal.jsonLang["Tháng 10 năm"],
                    listDeal.jsonLang["Tháng 11 năm"],
                    listDeal.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
        });
    
    },
    loadKanban: function () {
        
        $.ajax({
            url: laroute.route('customer-lead.customer-deal.load-kanban-view'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                search: $('#search').val(),
                pipeline_id: $('#kanban_pipeline_id').val(),
                type_customer: $('#kanban_type_customer').val(),
                order_source_id: $('#kanban_order_source_id').val(),
                branch_code: $('#kanban_branch_code').val(),
                care_type: $('#kanban_care_type').val(),
                closing_date: $('#kanban_closing_date').val(),
                select_manage_type_work_id: $('#select_manage_type_work_id').val(),
                dataField: $('#dataField').val(),
                search_manage_type_work_id: $('#search_manage_type_work_id').val(),
            },
            success: function (res) {
                if (res.error == false) {
                    var columns = [];
                    var loadDataSource = [];
                    var loadDataResourceSource = [];
                    var listTotalWork = res.listTotalWork;

                    $.map(res.journey, function (val) {
                        columns.push({
                            text: val.journey_name,
                            dataField: val.journey_code,
                            total: val.total
                        });
                    });

                    $.map(res.customerDeal, function (val) {
                        var hex = '';

                        if (val.default_system == 'new') {
                            hex = '#34bfa3';
                        } else if (val.default_system == 'fail') {
                            hex = '#f4516c';
                        } else if (val.default_system == 'win') {
                            hex = '#5867dd';
                        } else {
                            hex = '#36a3f7';
                        }

                        // var tag = "<i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";

                        if (val.total_work > 0) {
                            var tag = "<span class='badge badge_la-gratipay badge-fix badge-light float-right color-red-fix'>" + val.total_work + "</span><i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                        } else {
                            var tag = "<i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                        }

                        if (res.isCall == 1) {
                            tag = tag + ", <i class='la la-phone'></i>";
                        }
                        let totalDeal = parseFloat(val.amount);
                        loadDataSource.push({
                            id: val.deal_id,
                            state: val.journey_code,
                            name: val.deal_name + "<br/><br/>" + formatNumber(totalDeal.toFixed(decimal_number)) + " " + listDeal.jsonLang["VNĐ"] + "<br/>" + val.closing_date,
                            tags: tag,
                            hex: hex,
                            resourceId: val.customer_lead_id
                        });

                        loadDataResourceSource.push({
                            id: val.deal_id,
                            name: val.deal_name,
                            // image: val.avatar
                        });
                    });

                    if (loadDataSource.length == 0 && loadDataResourceSource.length == 0) {
                        loadDataSource.push({
                            id: 0,
                            state: '',
                            name: '',
                            tags: "<i class='la la-edit'></i>, <i class='la la-trash'></i>",
                            hex: '',
                            resourceId: ''
                        });

                        loadDataResourceSource.push({
                            id: 0,
                            name: 'asd',
                            // image: ''
                        });
                    }
                    kanBanView.loadView(columns, loadDataSource, loadDataResourceSource, listTotalWork);
                }
            }
        });
    
    },
    loadView: function (columns, loadDataSource, loadDataResourceSource, listTotalWork) {
        // $.getJSON(laroute.route('translate'), function (json) {
            var fields = [
                {name: "id", type: "string"},
                {name: "status", map: "state", type: "string"},
                {name: "text", map: "name", type: "string"},
                {name: "tags", type: "string"},
                {name: "color", map: "hex", type: "string"},
                {name: "resourceId", type: "number"}
            ];
            var source =
                {
                    localData: loadDataSource,
                    dataType: "array",
                    dataFields: fields
                };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var resourcesAdapterFunc = function () {
                var resourcesSource =
                    {
                        localData: loadDataResourceSource,
                        dataType: "array",
                        dataFields: [
                            {name: "id", type: "number"},
                            {name: "name", type: "string"},
                            {name: "image", type: "string"},
                            {name: "common", type: "boolean"}
                        ]
                    };
                var resourcesDataAdapter = new $.jqx.dataAdapter(resourcesSource);
                return resourcesDataAdapter;
            };

            $('#kanban').jqxKanban({
                width: getWidth('kanban'),
                height: 550,
                resources: resourcesAdapterFunc(),
                source: dataAdapter,
                columns: columns,
                columnRenderer: function (element, collapsedElement, column) {
                    var columnItems = $("#kanban").jqxKanban('getColumnItems', column.dataField).length;
                    var toTalJourneys = formatNumber(column.total.toFixed(decimal_number)) + listDeal.jsonLang["VNĐ"];
                    // update header's status.
                    element.find(".jqx-kanban-column-header-status").html(" (" + columnItems + ")<br> " + listDeal.jsonLang["Tổng tiền"] + " (" + toTalJourneys + ")");
                    // update collapsed header's status.
                    collapsedElement.find(".jqx-kanban-column-header-status").html(" (" + columnItems + ")");
                    element.find('.img-fluid.icon-header-kanban').parent('div').parent('span').remove();
                    element.children('br').remove()
                    let html = '';
                    element.parent().closest('.jqx-kanban-column').addClass('jqx-kanban-column-show');
                    $.map(listTotalWork[column.dataField], function (val) {
                        if (typeof val.total_work !== "undefined" && val.total_work != '0') {
                            if (column.dataField == $('#dataField').val() && val.manage_type_work_id == $('#search_manage_type_work_id').val()) {
                                html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div background-img " + val.manage_type_work_key + "'><img class='img-fluid icon-header-kanban " + val.manage_type_work_key + "' src='" + val.manage_type_work_icon + "' data-field='" + column.dataField + "' data-type-work-id='" + val.manage_type_work_id + "'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'>" + (val.total_work) + "</span></div></span>");
                            } else {
                                html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div " + val.manage_type_work_key + "'><img class='img-fluid icon-header-kanban " + val.manage_type_work_key + "' src='" + val.manage_type_work_icon + "' data-field='" + column.dataField + "' data-type-work-id='" + val.manage_type_work_id + "'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'>" + (val.total_work) + "</span></div></span>");
                            }
                        } else {
                            if (column.dataField == $('#dataField').val() && val.manage_type_work_id == $('#search_manage_type_work_id').val()) {
                                html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div background-img " + val.manage_type_work_key + "'><img class='img-fluid icon-header-kanban " + val.manage_type_work_key + "' src='" + val.manage_type_work_icon + "' data-field='" + column.dataField + "' data-type-work-id='" + val.manage_type_work_id + "'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'></span></div></span>");
                            } else {
                                html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div " + val.manage_type_work_key + "'><img class='img-fluid icon-header-kanban " + val.manage_type_work_key + "' src='" + val.manage_type_work_icon + "' data-field='" + column.dataField + "' data-type-work-id='" + val.manage_type_work_id + "'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'></span></div></span>");
                            }
                        }
                    });
                    element.prepend(html + "<br>")

                },
                template: "<div class='jqx-kanban-item' id=''>"
                    + "<div class='jqx-kanban-item-color-status'></div>"
                    + "<div style='display: block;' class='jqx-kanban-item-avatar'></div>"
                    + "<div class='jqx-icon jqx-icon-close-white jqx-kanban-item-template-content jqx-kanban-template-icon'></div>"
                    + "<div class='jqx-kanban-item-text'></div>"
                    + "<div style='display: block;' class='jqx-kanban-item-footer'></div>"
                    + "</div>",
            });

            // custom kanbanview by nhandt 13/12/2021
            // off event click, itemMoved, itemAttrClicked of #kanban
            // because when loadKanban, this function will make 3 events click, itemMoved, itemAttrClicked
            $('#kanban').off('itemMoved');
            $('#kanban').off('itemAttrClicked');
            $('#kanban').off('click');
            // end custom 13/12/2021
            //Event kĂ©o tháº£
            $('#kanban').on('itemMoved', function (event) {
                var args = event.args;
                var itemId = args.itemId;
                var oldParentId = args.oldParentId;
                var newParentId = args.newParentId;
                var itemData = args.itemData;
                var oldColumn = args.oldColumn;
                var newColumn = args.newColumn;

                mApp.block("#m_blockui_1_content", {
                    overlayColor: "#000000",
                    type: "loader",
                    state: "success",
                    message: listDeal.jsonLang["Đang tải..."]
                });

                $.ajax({
                    url: laroute.route('customer-lead.customer-deal.update-journey'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        deal_id: itemId,
                        journey_old: oldColumn.dataField,
                        journey_new: newColumn.dataField
                    },
                    success: function (res) {
                        mApp.unblock("#m_blockui_1_content");

                        if (res.error == false) {
                            setTimeout(function () {
                                toastr.success(res.message)
                            }, 60);
                            if (res.check_cannot_create_contract == 1) {
                                swal(listDeal.jsonLang['Đã tới hành trình tạo hợp đồng, nhưng sản phẩm của deal đã bao gồm sản phẩm có tính kpi và không tính, vì vậy không thể tạo hợp đồng!'], '', "info");
                            }
                            else if (res.check_create_contract_annex == 1) {
                                swal({
                                    title: listDeal.jsonLang["Thông báo"],
                                    text: res.deal_name + " " + listDeal.jsonLang["đã thành công bạn có muốn gia hạn hợp đồng với deal này không?"],
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: listDeal.jsonLang["Có"],
                                    cancelButtonText: listDeal.jsonLang["Không"]
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.href = '/contract/contract/show/' + res.contract_id;
                                    }
                                });
                            }
                            else if (res.check_create_contract == 1) {
                                swal({
                                    title: listDeal.jsonLang["Thông báo"],
                                    text: listDeal.jsonLang["Bạn có muốn tạo hợp đồng cho deal"] + " " + res.deal_code + " " + listDeal.jsonLang["này không?"],
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: listDeal.jsonLang["Có"],
                                    cancelButtonText: listDeal.jsonLang["Không"]
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.href = laroute.route('contract.contract.create') + "?type=from_deal&deal_code=" + res.deal_code;
                                    }
                                });
                            }
                            else if (res.checkInsertOrder) {
                                swal({
                                    title: listDeal.jsonLang["Thông báo"],
                                    text: res.deal_name + " " + listDeal.jsonLang["đã thành công bạn có muốn tạo đơn hàng hoặc thanh toán với deal này không?"],
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: listDeal.jsonLang["Có"],
                                    cancelButtonText: listDeal.jsonLang["Không"]
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.href = '/customer-lead/customer-deal/payment/' + res.deal_id;
                                    }
                                });
                            }

                        } else {
                            setTimeout(function () {
                                toastr.error(res.message)
                            }, 60);

                            // custom kanbanview by nhandt 13/12/2021
                            // $('#kanban').remove();
                            $('.parent_kanban').html('');
                            $('.parent_kanban').append('<div id="kanban"></div>');
                            kanBanView.loadKanban();
                            // end custom
                        }

                        // custom kanbanview by nhandt 13/12/2021
                        // $('#kanban').remove();
                        // $('.parent_kanban').append('<div id="kanban"></div>');
                        // kanBanView.loadKanban();
                        // end custom
                    }
                });
            });
            //Láº¥y id button Ä‘Æ°á»£c nháº¥p
            $('#kanban').on('itemAttrClicked', function (event1) {
                var args = event1.args;
                var itemId = args.itemId;
                var attribute = args.attribute; // template, colorStatus, content, keyword, text, avatar

                idClick = itemId;
            });

            //Event click
            $('#kanban').click(function (event) {
                $.each($('#kanban').find('.jqx-kanban-column .jqx-kanban-column-header-collapsed'), function () {
                    // let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');

                    if ($(this).hasClass('jqx-kanban-column-header-collapsed-show-light')){
                        $(this).parent().closest('.jqx-kanban-column').removeClass('jqx-kanban-column-show');
                        $(this).parent().closest('.jqx-kanban-column').addClass('jqx-kanban-column-hidden');
                    } else {
                        $(this).parent().closest('.jqx-kanban-column').removeClass('jqx-kanban-column-hidden');
                        $(this).parent().closest('.jqx-kanban-column').addClass('jqx-kanban-column-show');
                    }
                });

                //Get button nĂ o Ä‘Æ°á»£c nháº¥p
                // event.target.innerText
                if (event.target.className == 'la la-gratipay' || event.target.className == 'badge badge_la-gratipay badge-fix badge-light float-right color-red-fix') {
                    listDeal.popupDealCare(idClick);
                }
                if (event.target.className == 'la la-edit') {
                    edit.popupEdit(idClick, true);
                } else if (event.target.className == 'la la-trash') {
                    listDeal.remove(idClick);
                } else if (event.target.className == 'la la-eye') {
                    listDeal.detail(idClick);
                } else if (event.target.className == 'la la-phone') {
                    listDeal.modalCall(idClick);
                } else if (event.target.className == 'img-fluid icon-header-kanban call' ||
                    event.target.className == 'img-fluid icon-header-kanban email' ||
                    event.target.className == 'img-fluid icon-header-kanban message' ||
                    event.target.className == 'img-fluid icon-header-kanban meeting' ||
                    event.target.className == 'img-fluid icon-header-kanban other') {
                    if (typeof event.target.dataset.field !== "undefined") {

                        var dataField = $('#dataField').val();
                        var search_manage_type_work_id = $('#search_manage_type_work_id').val();

                        if (dataField == event.target.dataset.field && search_manage_type_work_id == event.target.dataset.typeWorkId) {
                            $('#dataField').val('');
                            $('#search_manage_type_work_id').val('');
                        } else {
                            $('#dataField').val(event.target.dataset.field);
                            $('#search_manage_type_work_id').val(event.target.dataset.typeWorkId);
                        }


                        $('#select_manage_type_work_id').val('').trigger('change');
                        // kanBanView.loadKanban();
                    }
                }
            });

        // });
    },
    changePipeline: function () {
        if ($('#select_manage_type_work_id').val() != '') {
            $('#dataField').val('');
            $('#search_manage_type_work_id').val('');
        }
        // custom kanbanview by nhandt 13/12/2021
        // $('#kanban').remove();
        $('.parent_kanban').html('');
        $('.parent_kanban').append('<div id="kanban"></div>');
        kanBanView.loadKanban();
        // end custom
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

uploadImgCk = function (file, parent_comment = null) {
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
            if (parent_comment != null) {
                $(".summernote").summernote('insertImage', img['file']);
            } else {
                $(".summernote").summernote('insertImage', img['file']);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};
uploadImgCk = function (file,parent_comment = null) {
    let out = new FormData();
    out.append('file', file, file.name);

    $.ajax({
        method: 'POST',
        url: laroute.route('manager-work.detail.upload-file'),
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function (img) {
            if (parent_comment != null){
                $(".description_"+parent_comment).summernote('insertImage', img['file']);
            } else {
                $(".description").summernote('insertImage', img['file']);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};

var DealComment = {
    jsontranslate : JSON.parse(localStorage.getItem('tranlate')),
    
    addComment: function () {
      
        var code = $('.description').summernote('code');
        var deal_id = $('#deal_id').val();
        $.ajax({
            url: laroute.route('customer-deal.detail.add-comment'),
            data: {
                deal_id : deal_id,
                description : code
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal({
                        title:  DealComment.jsontranslate['Thêm bình luận thành công'],
                        text: 'Loading...',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                    .then(() => {
                        DealComment.getListComment();
                    });
                } else {
                    swal('',res.message,'error');
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    addCommentChild: function (deal_comment_id) {
        var code = $('.description_'+ deal_comment_id).summernote('code');
        var deal_id = $('#deal_id').val();
        $.ajax({
            url: laroute.route('customer-deal.detail.add-comment'),
            data: {
                deal_comment_id : deal_comment_id,
                deal_id : deal_id,
                description : code
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal({
                        title:  DealComment.jsontranslate['Thêm bình luận thành công'],
                        text: 'Loading...',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                    .then(() => {
                        DealComment.getListComment();
                    });
                   
                } else {
                    swal('',res.message,'error');
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    showFormChat : function (parent_comment) {
        $.ajax({
            url: laroute.route('customer-deal.detail.show-form-comment'),
            data: {
                deal_comment_id : parent_comment,
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.form-chat-message').remove();
                    $(res.view).insertAfter($('.tr_'+parent_comment));
                    $('.description_'+parent_comment).summernote({
                        placeholder: '',
                        tabsize: 2,
                        height: 100,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['fontname', ['fontname', 'fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture']],
                        ],
                        callbacks: {
                            onImageUpload: function(files) {
                                for(let i=0; i < files.length; i++) {
                                    uploadImgCk(files[i],parent_comment);
                                }
                            }
                        },
                    });
                } else {
                    swal('',res.message,'error');
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal('', mess_error, "error");
            }
        });
    },

    getListComment: function(){
        $.ajax({
            url: laroute.route('customer-deal.detail.get-list-comment'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                deal_id : $('#deal_id').val()
            },
            success: function (res) {
                $('#tab-comment').html('');
                if (res.html != null) {
                    $('#tab-comment').append(res.html);
                    registerSummernote('.description', 'Leave a comment', 1000, function(max) {
                        $('.description').text(max)
                    });
                }
            }
        });
    }
}