var stt = 0;

var listTimekeeping = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.select').select2();

            $(document).on('click','[name][type]',function () {
                var name = $(this).attr('name');
                var type = $(this).attr('type');
                $('[name="sort[0]"]').val(name);
                $('[name="sort[1]"]').val(type);

                $('.btn-search').click();
            });

            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

            $("#created_at").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",

                // maxDate: moment().endOf("day"),
                // startDate: moment().startOf("day"),
                // endDate: moment().add(1, 'days'),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    // "applyLabel": "Đồng ý",
                    // "cancelLabel": "Thoát",
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            }, function(start, end, label) {
                $('#created_at_filter').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            });

            $('#autotable').PioTable({
                baseUrl: laroute.route('timekeeping.list')
            });
        });
    },
    changeCreate: function (obj) {
        alert('ok');
    },
    remove: function (id, load) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('timekeeping-config.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            timekeeping_config_id: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                if (load == true) {
                                    swal.fire(res.message, "", "success");
                                    $('#autotable').PioTable('refresh');
                                } else {
                                    swal.fire(res.message, "", "success");
                                    $('#autotable').PioTable('refresh');
                                }
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    detail: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('customer-lead.show'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    time_working_staff_id: id,
                    view: 'detail'
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-detail').modal('show');

                    $('#tag_id_detail').select2({
                        placeholder: json['Chọn tag']
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

                    $('#business_clue').select2({
                        placeholder: json['Chọn đầu mối doanh nghiệp']
                    });


                    var arrRange = {};
                    arrRange[json["Hôm nay"]] = [moment(), moment()];
                    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
                    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
                    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

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
                            "applyLabel": json["Đồng ý"],
                            "cancelLabel": json["Thoát"],
                            "customRangeLabel": json['Tùy chọn ngày'],
                            daysOfWeek: [
                                json["CN"],
                                json["T2"],
                                json["T3"],
                                json["T4"],
                                json["T5"],
                                json["T6"],
                                json["T7"]
                            ],
                            "monthNames": [
                                json["Tháng 1 năm"],
                                json["Tháng 2 năm"],
                                json["Tháng 3 năm"],
                                json["Tháng 4 năm"],
                                json["Tháng 5 năm"],
                                json["Tháng 6 năm"],
                                json["Tháng 7 năm"],
                                json["Tháng 8 năm"],
                                json["Tháng 9 năm"],
                                json["Tháng 10 năm"],
                                json["Tháng 11 năm"],
                                json["Tháng 12 năm"]
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
                    $(document).on('click', '#autotable-care a.m-datatable__pager-link', function (event) {
                        event.preventDefault();
                        var page = $(this).attr('data-page');
                        console.log(page);
                        if(page){
                            var code = $('#customer_lead_code').val();
                            listLead.getDataCare(page, code);
                        }
                    });
                    $(document).on('click', '#autotable-deal a.m-datatable__pager-link', function (event) {
                        event.preventDefault();
                        var page = $(this).attr('data-page');
                        if(page){
                            var code = $('#customer_lead_code').val();
                            listLead.getDataDeal(page, code);
                        }
                    });
                    $('.phone').ForceNumericOnly();
                }
            });
        });
    }
};

var create = {
    popupCreate: function (load) {
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('timekeeping.create'),
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


                }
            });
        });
    },
    save: function (load) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');


            var continute = true;

            if (continute == true) {
                $.ajax({
                    url: laroute.route('timekeeping.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        wifi_name: $('[name="wifi_name"]').val(),
                        bssid: $('[name="bssid"]').val(),
                        note: $('[name="note"]').val()
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
                url: laroute.route('timekeeping.edit'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    timekeeping_config_id: id,
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


                }
            });
        });
    },
    save: function (id, load) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            var continute = true;

            if (continute == true) {
                $.ajax({
                    url: laroute.route('timekeeping.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        wifi_name: $('[name="wifi_name"]').val(),
                        bssid: $('[name="bssid"]').val(),
                        note: $('[name="note"]').val(),
                        timekeeping_config_id: $('[name="timekeeping_config_id"]').val()
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
                                    $('#autotable').PioTable('refresh');
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
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#autotable').PioTable({
                baseUrl: laroute.route('customer-lead.list-lead-not-assign-yet')
            });
            $('#department').select2({
                placeholder: json['Chọn phòng ban']
            }).on('select2:select', function (e) {
                // Bỏ check all sale
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
                            // nếu đã tồn tại trong mảng arrOldSaleChecked thì checked
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
                // Bỏ check all sale
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
                placeholder: json['Chọn sale']
            }).on('select2:unselect', function (e) {
                arrOldSaleChecked = $('#staff').val().map(function (i) {
                    return parseInt(i, 10);
                });
            }).on('select2:select', function (e) {
                arrOldSaleChecked = $('#staff').val().map(function (i) {
                    return parseInt(i, 10);
                });
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