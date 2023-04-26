var stt = 0;

var create = {
    popupCreate: function (load) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('customer-lead.create'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    load: load
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-create').modal('show');

                    $("#tag_id").select2({
                        placeholder: json['Chọn tag'],
                        tags: true,
                        tokenSeparators: [",", " "],
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
                                url: laroute.route('customer-lead.tag.store'),
                                data: {
                                    tag_name: e.params.data.text
                                },
                                success: function (res) {
                                    // append the new option element end replace id
                                    $('#tag_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                                }
                            });
                        }
                    });


                    $('#pipeline_code').select2({
                        placeholder: json['Chọn pipeline']
                    });

                    $('#journey_code').select2({
                        placeholder: json['Chọn hành trình']
                    });

                    $('#customer_type_create').select2({
                        placeholder: json['Chọn loại khách hàng']
                    });

                    $('#customer_source').select2({
                        placeholder: json['Chọn nguồn khách hàng']
                    });

                    $('#business_clue').select2({
                        placeholder: json['Chọn đầu mối doanh nghiệp']
                    });

                    // $('.phone').ForceNumericOnly();

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

                    $('#sale_id').select2({
                        placeholder: json['Chọn nhân viên được phân bổ']
                    });

                    $('#province_id').select2({
                        placeholder: json['Chọn tỉnh/thành']
                    });

                    $('#district_id').select2({
                        placeholder: json['Chọn quận/huyện']
                    });
                }
            });
        });
    },
    save: function (load) {
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

            if (continute == true) {
                $.ajax({
                    url: laroute.route('customer-lead.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
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
                        district_id: $('#district_id').val(),
                        custom_1: $('#custom_1').val(),
                        custom_2: $('#custom_2').val(),
                        custom_3: $('#custom_3').val(),
                        custom_4: $('#custom_4').val(),
                        custom_5: $('#custom_5').val(),
                        custom_6: $('#custom_6').val(),
                        custom_7: $('#custom_7').val(),
                        custom_8: $('#custom_8').val(),
                        custom_9: $('#custom_9').val(),
                        custom_10: $('#custom_10').val()
                    },
                    success: function (res) {
                        if (res.error == false) {
                            if (load == true) {
                                swal(res.message, "", "success").then(function (result) {
                                    if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                        $('#modal-create').modal('hide');
                                    }
                                    if (result.value == true) {
                                        $('#modal-create').modal('hide');
                                    }
                                });

                                $('#kanban').remove();
                                $('.parent_kanban').append('<div id="kanban"></div>');
                                kanBanView.loadKanban();
                            } else {
                                swal(res.message, "", "success").then(function (result) {
                                    if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                        $('#modal-create').modal('hide');
                                    }
                                    if (result.value == true) {
                                        $('#modal-create').modal('hide');
                                    }
                                });
                                $('#autotable').PioTable('refresh');
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
                        swal(json['Thêm mới thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
};

var edit = {
    popupEdit: function (id, load) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('customer-lead.edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_lead_id: id,
                    load: load,
                    view: 'edit'
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-edit').modal('show');

                    $("#tag_id").select2({
                        placeholder: json['Chọn tag'],
                        tags: true,
                        tokenSeparators: [",", " "],
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
                                url: laroute.route('customer-lead.tag.store'),
                                data: {
                                    tag_name: e.params.data.text
                                },
                                success: function (res) {
                                    // append the new option element end replace id
                                    $('#tag_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                                }
                            });
                        }
                    });

                    $('#pipeline_code').select2({
                        placeholder: json['Chọn pipeline']
                    });

                    $('#customer_type').select2({
                        placeholder: json['Chọn loại khách hàng']
                    });

                    $('#journey_code').select2({
                        placeholder: json['Chọn hành trình']
                    });

                    $('#customer_source').select2({
                        placeholder: json['Chọn nguồn khách hàng']
                    });

                    $('#business_clue').select2({
                        placeholder: json['Chọn đầu mối doanh nghiệp']
                    });

                // $('.phone').ForceNumericOnly();
                    $('#province_id').select2({
                        placeholder: json['Chọn tỉnh/thành']
                    });

                    $('#district_id').select2({
                        placeholder: json['Chọn quận/huyện']
                    });
                }
            });
        });
    },
    save: function (id, load) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

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

            if (continute == true) {
                $.ajax({
                    url: laroute.route('customer-lead.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        full_name: $('#full_name').val(),
                        phone: $('#phone').val(),
                        gender: $('input[name="gender"]:checked').val(),
                        address: $('#address').val(),
                        avatar: $('#avatar').val(),
                        email: $('#email').val(),
                        tag_id: $('#tag_id').val(),
                        pipeline_code: $('#pipeline_code').val(),
                        journey_code: $('#journey_code').val(),
                        customer_type: $('#customer_type').val(),
                        hotline: $('#hotline').val(),
                        fanpage: $('#fanpage').val(),
                        zalo: $('#zalo').val(),
                        customer_lead_id: id,
                        customer_lead_code: $('#customer_lead_code').val(),
                        arrPhoneAttack: arrPhoneAttack,
                        arrEmailAttack: arrEmailAttack,
                        arrFanpageAttack: arrFanpageAttack,
                        arrContact: arrContact,
                        tax_code: $('#tax_code').val(),
                        representative: $('#representative').val(),
                        customer_source: $('#customer_source').val(),
                        business_clue: $('#business_clue').val(),
                        province_id: $('#province_id').val(),
                        district_id: $('#district_id').val(),
                        custom_1: $('#custom_1').val(),
                        custom_2: $('#custom_2').val(),
                        custom_3: $('#custom_3').val(),
                        custom_4: $('#custom_4').val(),
                        custom_5: $('#custom_5').val(),
                        custom_6: $('#custom_6').val(),
                        custom_7: $('#custom_7').val(),
                        custom_8: $('#custom_8').val(),
                        custom_9: $('#custom_9').val(),
                        custom_10: $('#custom_10').val()
                    },
                    success: function (res) {
                        if (res.error == false) {
                            if (res.create_deal == 1) {
                                swal(res.message, "", "success").then(function (result) {
                                    if (result.dismiss == 'esc' || result.dismiss == 'backdrop' || result.dismiss == 'overlay') {
                                        edit.showConfirmCreateDeal(res.lead_id);
                                    }
                                    if (result.value == true) {
                                        edit.showConfirmCreateDeal(res.lead_id);
                                    }
                                });

                            } else {
                                swal(res.message, "", "success").then(function (result) {
                                    if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                        $('#modal-edit').modal('hide');
                                    }
                                    if (result.value == true) {
                                        $('#modal-edit').modal('hide');
                                    }
                                });

                                if (load == true) {
                                    $('#kanban').remove();
                                    $('.parent_kanban').append('<div id="kanban"></div>');
                                    kanBanView.loadKanban();

                                } else {
                                    window.location.reload();
                                }
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
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                });
            }
        });
    },
    showConfirmCreateDeal: function (leadId) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn tạo deal không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Có'],
                cancelButtonText: json['Không'],
            }).then(function (result) {
                if (result.value) {
                    edit.createDealAuto(leadId);
                } else {
                    $('#modal-edit').modal('hide');
                }
            });
        });
    },
    //Tạo deal tự động
    createDealAuto: function (leadId) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('customer-lead.create-deal-auto'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_lead_id: leadId
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-create').modal('show');

                    $("#end_date_expected").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        format: "dd/mm/yyyy",
                        startDate: "dateToday"
                    });
                    $('#staff').select2({
                        placeholder: json['Chọn người sở hữu']
                    });

                    new AutoNumeric.multiple('#auto-deal-amount', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                    $('#customer_code').select2({
                        placeholder: json['Chọn khách hàng'],
                    });

                    $('#customer_contact_code').select2({
                        placeholder: json['Chọn liên hệ']
                    });

                    $('#pipeline_code').select2({
                        placeholder: json['Chọn pipeline']
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

                    $('#journey_code').select2({
                        placeholder: json['Chọn hành trình']
                    });
                    $('#customer_contact_code').select2();

                    new AutoNumeric.multiple('#amount', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                    $('#tag_id').select2({
                        placeholder: json['Chọn tag'],
                        tags: true,
                        createTag: function(newTag) {
                            return {
                                id: newTag.term,
                                text: newTag.term,
                                isNew : true
                            };
                        }
                    }).on("select2:select", function(e) {
                        if(e.params.data.isNew){
                            $.ajax({
                                type: "POST",
                                url: laroute.route('customer-lead.customer-deal.store-quickly-tag'),
                                data: {
                                    tag_name: e.params.data.text
                                },
                                success: function (res) {
                                    $('#tag_id').find('[value="'+e.params.data.text+'"]').replaceWith('<option selected value="'+ res.tag_id  +'">'+e.params.data.text+'</option>');
                                }
                            });
                        }
                    });
                    $('#order_source').select2({
                        placeholder: json['Chọn nguồn đơn hàng']
                    });

                    $('#probability').ForceNumericOnly();
                }
            });
        });

    }
};

var index = {
    importExcel: function () {
        $('#modal-excel').modal('show');
        $('#show').val('');
        $('input[type=file]').val('');
    },
    importSubmit: function () {
        mApp.block(".modal-body", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: "Xin vui lòng chờ..."
        });

        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        console.log(file_data);
        console.log(form_data);
        $.ajax({
            url: laroute.route("customer-lead.import-excel"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                mApp.unblock(".modal-body");
                if (res.success == 1) {
                    swal(res.message, "", "success");
                    $('#autotable').PioTable('refresh');

                    if (res.number_error > 0) {
                        $('.export_error').css('display', 'block');
                        $('#data_error').empty();

                        $.map(res.data_error, function (val) {
                            var tpl = $('#tpl-data-error').html();
                            tpl = tpl.replace(/{full_name}/g, val.full_name);
                            tpl = tpl.replace(/{phone}/g, val.phone);
                            tpl = tpl.replace(/{phone_attack}/g, val.phone_attack);
                            tpl = tpl.replace(/{birthday}/g, val.birthday);
                            tpl = tpl.replace(/{province_name}/g, val.province_name);
                            tpl = tpl.replace(/{district_name}/g, val.district_name);
                            tpl = tpl.replace(/{gender}/g, val.gender);
                            tpl = tpl.replace(/{email}/g, val.email);
                            tpl = tpl.replace(/{email_attach}/g, val.email_attach);
                            tpl = tpl.replace(/{address}/g, val.address);
                            tpl = tpl.replace(/{customer_type}/g, val.customer_type);
                            tpl = tpl.replace(/{pipeline}/g, val.pipeline);
                            tpl = tpl.replace(/{customer_source}/g, val.customer_source);
                            tpl = tpl.replace(/{business_clue}/g, val.business_clue);
                            tpl = tpl.replace(/{fanpage}/g, val.fanpage);
                            tpl = tpl.replace(/{fanpage_attack}/g, val.fanpage_attack);
                            tpl = tpl.replace(/{zalo}/g, val.zalo);
                            tpl = tpl.replace(/{tag}/g, val.tag);
                            tpl = tpl.replace(/{sale_id}/g, val.sale_id);
                            tpl = tpl.replace(/{tax_code}/g, val.tax_code);
                            tpl = tpl.replace(/{representative}/g, val.representative);
                            tpl = tpl.replace(/{hotline}/g, val.hotline);
                            tpl = tpl.replace(/{error}/g, val.error);
                            $('#data_error').append(tpl);
                        });

                        //Download file lỗi sẵn
                        $( "#form-error" ).submit();
                    } else {
                        $('.export_error').css('display', 'none');
                        $('#data_error').empty();
                    }
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    fileName: function () {
        var fileNamess = $('input[type=file]').val();
        $('#show').val(fileNamess);
    },
    closeModalImport: function () {
        $('#modal-excel').modal('hide');
        $('#autotable').PioTable('refresh');
    },
};

var numberPhone = 0;
var numberEmail = 0;
var numberFanpage = 0;
var numberContact = 0;

var view = {
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
    }
};

var detail = {
    convertCustomer: function (lead_id, flag) {
        $.getJSON(laroute.route('translate'), function (json) {
            // flag = 0: chuyển đổi KH k tạo deal, flag = 1: chuyển đổi KH có tạo deal
            // update is_convert = 1
            if (flag == 0) {
                $.ajax({
                    url: laroute.route('convert-customer-no-deal'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_lead_id: lead_id
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('customer-lead');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('customer-lead');
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
                        swal(json['Chuyển đổi thất bại'], mess_error, "error");
                    }
                });
            }
            else if (flag == 1) {

                $.ajax({
                    url: laroute.route('customer-lead.create-deal'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_lead_id: lead_id
                    },
                    success: function (res) {
                        $('#my-modal').html(res.html);
                        $('#modal-detail').modal('hide');
                        $('#modal-create').modal('show');

                        $("#end_date_expected").datepicker({
                            todayHighlight: !0,
                            autoclose: !0,
                            format: "dd/mm/yyyy",
                            startDate: "dateToday"
                        });
                        $('#staff').select2({
                            placeholder: json['Chọn người sở hữu']
                        });

                        new AutoNumeric.multiple('#auto-deal-amount', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });
                        $('#customer_code').select2({
                            placeholder: json['Chọn khách hàng'],
                        });

                        $('#customer_contact_code').select2({
                            placeholder: json['Chọn liên hệ']
                        });

                        $('#pipeline_code').select2({
                            placeholder: json['Chọn pipeline']
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
                                    var today = moment().format('DD/MM/YYYY');
                                    var new_date = moment(today , "DD/MM/YYYY");
                                    new_date.add(parseInt(res.time_revoke_lead), 'days');
                                    new_date = new_date.format('DD/MM/YYYY');
                                    $('#end_date_expected').val(new_date);
                                    $.map(res.optionJourney, function (a) {
                                        $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                    });
                                }
                            });
                        });

                        $('#journey_code').select2({
                            placeholder: json['Chọn hành trình']
                        });
                        $('#customer_contact_code').select2();

                        new AutoNumeric.multiple('#amount', {
                            currencySymbol: '',
                            decimalCharacter: '.',
                            digitGroupSeparator: ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });
                        $('#tag_id').select2({
                            placeholder: json['Chọn tag'],
                            tags: true,
                            createTag: function(newTag) {
                                return {
                                    id: newTag.term,
                                    text: newTag.term,
                                    isNew : true
                                };
                            }
                        }).on("select2:select", function(e) {
                            if(e.params.data.isNew){
                                $.ajax({
                                    type: "POST",
                                    url: laroute.route('customer-lead.customer-deal.store-quickly-tag'),
                                    data: {
                                        tag_name: e.params.data.text
                                    },
                                    success: function (res) {
                                        $('#tag_id').find('[value="'+e.params.data.text+'"]').replaceWith('<option selected value="'+ res.tag_id  +'">'+e.params.data.text+'</option>');
                                    }
                                });
                            }
                        });
                        $('#order_source').select2({
                            placeholder: json['Chọn nguồn đơn hàng']
                        });

                        $('#probability').ForceNumericOnly();
                        $('#pipeline_code').trigger('change');
                        var fn = $('#deal_name').val();
                        var pipName = $('#pipeline_code option:selected').text();
                        $('#deal_name').val(pipName.trim() + '_' + fn);
                    }
                });
            }
        });
    },
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
        // Tính lại tổng tiền
        $('#auto-deal-amount').empty();
        $('#auto-deal-amount-remove').html('');
        $('#auto-deal-amount-remove').append(`<input type="text" class="form-control m-input" id="auto-deal-amount" name="auto-deal-amount">`);
        var sum = 0;
        $.each($('#table_add > tbody').find('input[name="object_amount"]'), function () {
            sum += Number($(this).val().replace(new RegExp('\\,', 'g'), ''));
        });
        $('#auto-deal-amount').val(formatNumber(sum.toFixed(decimal_number)));
        new AutoNumeric.multiple('#auto-deal-amount', {
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
            }).on('select2:open', function (e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
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

                        var discount = $(obj).closest('tr').find('.object_discount').val().replace(new RegExp('\\,', 'g'), '');
                        var amount = Number(result.price) - discount;
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));

                        $(obj).closest('tr').find('.object_id').val(result.product_child_id);
                    } else if (object_type == 'service') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price_standard).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_quantity').val(1);

                        var discount = $(obj).closest('tr').find('.object_discount').val().replace(new RegExp('\\,', 'g'), '');
                        var amount = Number(result.price) - discount;
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));

                        $(obj).closest('tr').find('.object_id').val(result.service_id);
                    } else if (object_type == 'service_card') {
                        $(obj).closest('tr').find($('.object_price')).val(formatNumber(Number(result.price).toFixed(decimal_number)));
                        $(obj).closest('tr').find('.object_quantity').val(1);

                        var discount = $(obj).closest('tr').find('.object_discount').val().replace(new RegExp('\\,', 'g'), '');
                        var amount = Number(result.price) - discount;
                        $(obj).closest('tr').find('.object_amount').val(formatNumber(Number(amount > 0 ? amount : 0).toFixed(decimal_number)));

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

    createDeal: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');

            form.validate({
                rules: {
                    deal_name: {
                        required: true
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
                },
                messages: {
                    deal_name: {
                        required: json['Hãy nhập tên deal']
                    },
                    staff: {
                        required: json['Hãy chọn người sở hữu deal']
                    },
                    customer_code: {
                        required: json['Hãy chọn khách hàng']
                    },
                    pipeline_code: {
                        required: json['Hãy chọn pipeline']
                    },
                    journey_code: {
                        required: json['Hãy chọn hành trình khách hàng']
                    },
                    end_date_expected: {
                        required: json['Hãy chọn ngày kết thúc dự kiến']
                    },
                    add_phone: {
                        required: json['Hãy nhập số điện thoại'],
                        integer: json['Số điện thoại không hợp lệ'],
                        maxlength: json['Số điện thoại tối đa 10 kí tự']
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
                var object_code = $(this).find($('.object_id')).val();

                if (object_type == "") {
                    $(this).find($('.error_object_type')).text(json['Vui lòng chọn loại sản phẩm']);
                    flag = false;
                } else {
                    $(this).find($('.error_object_type')).text('');
                }
                if (object_code == "") {
                    $(this).find($('.error_object')).text(json['Vui lòng chọn sản phẩm']);
                    flag = false;
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

            if (flag == true) {
                $.ajax({
                    url: laroute.route('customer-lead.customer-deal.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        deal_name: $('#deal_name').val(),
                        staff: $('#staff').val(),
                        customer_code: $('#customer_code').val(),
                        customer_contact_code: $('#customer_contact_code').val(),
                        pipeline_code: $('#pipeline_code').val(),
                        journey_code: $('#journey_code').val(),
                        tag_id: $('#tag_id').val(),
                        order_source_id: $('#order_source').val(),
                        phone: $('#add_phone').val(),
                        amount: $('#auto-deal-amount').val(),
                        probability: $('#probability').val(),
                        end_date_expected: $('#end_date_expected').val(),
                        deal_description: $('#deal_description').val(),
                        deal_type_code: $('#deal_type_code').val(),
                        type_customer: $('#type_customer').val(),
                        arrObject: arrObject
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('customer-lead.customer-deal');
                                }
                                if (result.value == true) {
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
                        swal(json['Thêm thất bại'], mess_error, "error");
                    }
                });
            }
        });
    },

    changeTab: function (tabName) {
        switch (tabName) {
            case 'info':
                $('#div-info').css('display', 'block');
                $('#div-care').css('display', 'none');
                $('#div-deal').css('display', 'none');
                $('#div-support').css('display', 'none');
                break;

            case 'care':
                $('#div-info').css('display', 'none');
                $('#div-care').css('display', 'block');
                $('#div-deal').css('display', 'none');
                $('#div-support').css('display', 'none');
                break;

            case 'deal':
                $('#div-info').css('display', 'none');
                $('#div-care').css('display', 'none');
                $('#div-support').css('display', 'none');
                $('#div-deal').css('display', 'block');
                break;
            case 'support':
                $('#div-info').css('display', 'none');
                $('#div-care').css('display', 'none');
                $('#div-deal').css('display', 'none');
                $('#div-support').css('display', 'block');
                break;

        }
    }
};

var arrOldSaleChecked = [];
var assign = {
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
                    customer_lead_id: $(this).parents('label').find('.customer_lead_id').val(),
                    customer_lead_code: $(this).parents('label').find('.customer_lead_code').val(),
                    time_revoke_lead: $(this).parents('label').find('.time_revoke_lead').val()
                });
            });

            $.ajax({
                url: laroute.route('customer-lead.choose-all'),
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
                    customer_lead_id: $(this).parents('label').find('.customer_lead_id').val(),
                    customer_lead_code: $(this).parents('label').find('.customer_lead_code').val(),
                    time_revoke_lead: $(this).parents('label').find('.time_revoke_lead').val()

                });
            });

            $.ajax({
                url: laroute.route('customer-lead.un-choose-all'),
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
            let customerLeadId = '';
            let customerLeadCode = '';
            let timeRevokeLead = '';
            customerLeadId = $(obj).parents('label').find('.customer_lead_id').val();
            customerLeadCode = $(obj).parents('label').find('.customer_lead_code').val();
            timeRevokeLead = $(obj).parents('label').find('.time_revoke_lead').val();

            $.ajax({
                url: laroute.route('customer-lead.choose'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_lead_id: customerLeadId,
                    customer_lead_code: customerLeadCode,
                    time_revoke_lead: timeRevokeLead,
                }
            });
        } else {
            let customerLeadId = '';
            let customerLeadCode = '';
            let timeRevokeLead = '';
            customerLeadId = $(obj).parents('label').find('.customer_lead_id').val();
            customerLeadCode = $(obj).parents('label').find('.customer_lead_code').val();
            timeRevokeLead = $(obj).parents('label').find('.time_revoke_lead').val();

            $.ajax({
                url: laroute.route('customer-lead.un-choose'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_lead_id: customerLeadId,
                    customer_lead_code: customerLeadCode,
                    time_revoke_lead: timeRevokeLead,
                }
            });
        }
    },
    checkAllLead: function () {
        if ($('#checkAllLead').is(":checked")) {
            $('.check_one').prop('checked', true);
            $.ajax({
                url: laroute.route('customer-lead.check-all-lead'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    is_check_all: 1,
                    search: $('input[name=search]').val(),
                    customer_source: $('#customer_source option:selected').val()
                },
                success: function (res) {
                    $('#autotable').PioTable('refresh');
                }
            });
        } else {
            $('.check_one').prop('checked', false);
            $.ajax({
                url: laroute.route('customer-lead.check-all-lead'),
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
        $.getJSON(laroute.route('translate'), function (json) {
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
                        required: json['Hãy chọn phòng ban']
                    },
                    staff: {
                        required: json['Hãy chọn nhân viên bị thu hồi']
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }

            let arrStaff = $("#staff").val();

            $.ajax({
                url: laroute.route('customer-lead.submit-assign'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrStaff: arrStaff,
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success");
                        // $('#autotable').PioTable('refresh');
                        window.location.href = laroute.route('customer-lead');
                    } else {
                        swal(res.message, '', "error");
                    }
                }
            });
        });
    }
}

var idClick = '';

var kanBanView = {
    loadKanban: function(){
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('customer-lead.load-kan-ban-view'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    pipeline_id: $('#pipeline_id').val(),
                    customer_type: $('#customer_type_filter').val(),
                    search: $('#search').val(),
                    select_manage_type_work_id: $('#select_manage_type_work_id').val(),
                    dataField: $('#dataField').val(),
                    search_manage_type_work_id: $('#search_manage_type_work_id').val(),
                    created_at: $('#created_at').val(),
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
                                dataField: val.journey_code
                            });
                        });

                        $.map(res.customerLead, function (val) {
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

                            var fullName = '';
                            var phone = '';
                            var email = '';
                            var sale_name = '';

                            if (val.full_name != null) {
                                fullName = val.full_name;
                            }

                            if (val.phone != null) {
                                phone = val.phone;
                            }

                            if (val.email != null) {
                                email = val.email;
                            }

                            if (val.sale_name != null) {
                                sale_name = json['Người được phân bổ'] + ': ' + val.sale_name
                            }

                            if (val.total_work > 0) {
                                var tag = "<span class='badge badge_la-gratipay badge-fix badge-light float-right color-red-fix'>" + val.total_work + "</span><i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                            } else {
                                var tag = "<i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>, <i class='la la-eye'></i>";
                            }


                            if (res.isCall == 1) {
                                tag = tag + ", <i class='la la-phone'></i>";
                            }

                            loadDataSource.push({
                                id: val.customer_lead_id,
                                state: val.journey_code,
                                name: fullName + "<br/><br/>" + phone + "<br/>" + email + "<br/>" + sale_name,
                                tags: tag,
                                hex: hex,
                                resourceId: val.customer_lead_id
                            });

                            loadDataResourceSource.push({
                                id: val.customer_lead_id,
                                name: val.full_name,
                                image: val.avatar,
                                total_work: val.total_work,
                            });
                        });

                        if (loadDataSource.length == 0 && loadDataResourceSource.length == 0) {
                            loadDataSource.push({
                                id: 0,
                                state: '',
                                name: '',
                                tags: "<i class='la la-gratipay'></i>, <i class='la la-edit'></i>, <i class='la la-trash'></i>",
                                hex: '',
                                resourceId: ''
                            });

                            loadDataResourceSource.push({
                                id: 0,
                                name: 'asd',
                                image: ''
                            });
                        }
                        kanBanView.loadView(columns, loadDataSource, loadDataResourceSource, listTotalWork);
                    }
                }
            });
        });
    },
    loadView: function (columns, loadDataSource, loadDataResourceSource,listTotalWork) {
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
                // update header's status.
                element.find(".jqx-kanban-column-header-status").html(" (" + columnItems + ")");

                // update collapsed header's status.
                collapsedElement.find(".jqx-kanban-column-header-status").html(" (" + columnItems + ")");
                element.find('.img-fluid.icon-header-kanban').parent('div').parent('span').remove();
                element.children('br').remove()
                var html = '';
                element.parent().closest('.jqx-kanban-column').addClass('jqx-kanban-column-show');
                $.map(listTotalWork[column.dataField], function (val) {
                    if (typeof val.total_work !== "undefined" && val.total_work != '0'){
                        if (column.dataField == $('#dataField').val() && val.manage_type_work_id == $('#search_manage_type_work_id').val()){
                            html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div background-img "+val.manage_type_work_key+"'><img class='img-fluid icon-header-kanban "+val.manage_type_work_key+"' src='"+val.manage_type_work_icon+"' data-field='"+column.dataField+"' data-type-work-id='"+val.manage_type_work_id+"'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'>"+(val.total_work)+"</span></div></span>");
                        } else {
                            html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div "+val.manage_type_work_key+"'><img class='img-fluid icon-header-kanban "+val.manage_type_work_key+"' src='"+val.manage_type_work_icon+"' data-field='"+column.dataField+"' data-type-work-id='"+val.manage_type_work_id+"'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'>"+(val.total_work)+"</span></div></span>");
                        }
                    } else {
                        if (column.dataField == $('#dataField').val() && val.manage_type_work_id == $('#search_manage_type_work_id').val()){
                            html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div background-img "+val.manage_type_work_key+"'><img class='img-fluid icon-header-kanban "+val.manage_type_work_key+"' src='"+val.manage_type_work_icon+"' data-field='"+column.dataField+"' data-type-work-id='"+val.manage_type_work_id+"'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'></span></div></span>");
                        } else {
                            html += ("<span class='jqx-kanban-column-header-icon jqx-kanban-column-header-status-light'><div class='float-right img-fluid mr-4 position-relative base-div "+val.manage_type_work_key+"'><img class='img-fluid icon-header-kanban "+val.manage_type_work_key+"' src='"+val.manage_type_work_icon+"' data-field='"+column.dataField+"' data-type-work-id='"+val.manage_type_work_id+"'><span class='badge badge-fix badge-light color-red-fix' style='background:transparent'></span></div></span>");
                        }
                    }
                });
                element.prepend(html + '<br>');
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

        //Event kéo thả
        $('#kanban').on('itemMoved', function (event) {
            var args = event.args;
            var itemId = args.itemId;
            var oldParentId = args.oldParentId;
            var newParentId = args.newParentId;
            var itemData = args.itemData;
            var oldColumn = args.oldColumn;
            var newColumn = args.newColumn;

            $.ajax({
                url: laroute.route('customer-lead.update-journey'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_lead_id: itemId,
                    journey_old: oldColumn.dataField,
                    journey_new: newColumn.dataField,
                    pipeline_id: $('#pipeline_id').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        if (res.create_deal == 1) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop' || result.dismiss == 'overlay') {
                                    edit.showConfirmCreateDeal(res.lead_id);
                                }
                                if (result.value == true) {
                                    edit.showConfirmCreateDeal(res.lead_id);
                                }
                            });
                        }

                        setTimeout(function () {
                            toastr.success(res.message)
                        }, 60);

                    } else {
                        setTimeout(function () {
                            toastr.error(res.message)
                        }, 60);

                        $('#kanban').remove();
                        $('.parent_kanban').append('<div id="kanban"></div>');
                        kanBanView.loadKanban();
                    }
                }
            });
        });

        //Lấy id button được nhấp
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
            //Get button nào được nhấp
            // event.target.innerText
            if (event.target.className == 'la la-gratipay' || event.target.className == 'badge badge_la-gratipay badge-fix badge-light float-right color-red-fix') {
                listLead.popupCustomerCare(idClick);
            } else if (event.target.className == 'la la-edit') {
                edit.popupEdit(idClick, true);
            } else if (event.target.className == 'la la-trash') {
                listLead.remove(idClick, true);
            } else if (event.target.className == 'la la-eye') {
                listLead.detail(idClick);
            } else if (event.target.className == 'la la-phone') {
                listLead.modalCall(idClick);
            } else if(event.target.className == 'img-fluid icon-header-kanban call' ||
                event.target.className == 'img-fluid icon-header-kanban email' ||
                event.target.className == 'img-fluid icon-header-kanban message' ||
                event.target.className == 'img-fluid icon-header-kanban meeting' ||
                event.target.className == 'img-fluid icon-header-kanban other'
            ){

                if(typeof event.target.dataset.field !== "undefined"){
                    var dataField = $('#dataField').val();
                    var search_manage_type_work_id = $('#search_manage_type_work_id').val();
                    if(dataField == event.target.dataset.field && search_manage_type_work_id == event.target.dataset.typeWorkId){
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
    },
    changePipeline: function () {
        if($('#select_manage_type_work_id').val() != ''){
            $('#dataField').val('');
            $('#search_manage_type_work_id').val('');
        }

        $('#kanban').remove();
        $('.parent_kanban').append('<div id="kanban"></div>');

        kanBanView.loadKanban();
    },
    closeModalDeal: function () {
        $('#modal-detail').modal('hide');
        window.location.reload();
    }
};

function uploadAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#image');
        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_customer-lead.');
        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function () {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());

        if (Math.round(fsize / 1024) <= 10240) {
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == 0) {
                        $('#avatar').val(res.file);
                    }
                }
            });
        } else {
            swal("Hình ảnh vượt quá dung lượng cho phép", "", "error");
        }
    }
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

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

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