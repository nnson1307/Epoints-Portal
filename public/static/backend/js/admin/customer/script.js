$(document).ready(function () {
    customer.jsonLang = JSON.parse(localStorage.getItem("tranlate")),

        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $('#province_id').val()
            },
            method: 'POST',
            success: function (res) {
                $.map(res.optionDistrict, function (a) {
                    $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                });
                // $('#district_id').val(778);
            }
        });

    $('#province_id').select2({
        placeholder: customer.jsonLang["Chọn tỉnh/thành"],
    });

    $('#ward_id').select2({
        placeholder: customer.jsonLang["Chọn phường/xã"],
    });

    $('#province_id').change(function () {
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $('#province_id').val(),
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

    $('#district_id').select2({
        placeholder: customer.jsonLang["Chọn quận/huyện"],
        ajax: {
            url: laroute.route('admin.customer.load-district'),
            data: function (params) {
                return {
                    id_province: $('#province_id').val(),
                    search: params.term,
                    page: params.page || 1
                };
            },
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

    $('#customer_group_id').select2({
        placeholder: customer.jsonLang["Chọn nhóm khách hàng"],
    });
    $('.op_day').select2({
        placeholder: customer.jsonLang["Ngày"],

    });
    $('#month').select2({
        placeholder: customer.jsonLang["Tháng"],

    });
    $('#year').select2({
        placeholder: customer.jsonLang["Năm"],

    });
    $('#customer_type').select2();
    $("#created_at").daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        locale: {
            format: 'DD/MM/YYYY',
            daysOfWeek: [
                customer.jsonLang["CN"],
                customer.jsonLang["T2"],
                customer.jsonLang["T3"],
                customer.jsonLang["T4"],
                customer.jsonLang["T5"],
                customer.jsonLang["T6"],
                customer.jsonLang["T7"]
            ],
            "monthNames": [
                customer.jsonLang["Tháng 1 năm"],
                customer.jsonLang["Tháng 2 năm"],
                customer.jsonLang["Tháng 3 năm"],
                customer.jsonLang["Tháng 4 năm"],
                customer.jsonLang["Tháng 5 năm"],
                customer.jsonLang["Tháng 6 năm"],
                customer.jsonLang["Tháng 7 năm"],
                customer.jsonLang["Tháng 8 năm"],
                customer.jsonLang["Tháng 9 năm"],
                customer.jsonLang["Tháng 10 năm"],
                customer.jsonLang["Tháng 11 năm"],
                customer.jsonLang["Tháng 12 năm"]
            ],
            "firstDay": 1
        }
    });
    $('#search_birthday').datepicker({
        dataFormat: "dd/mm/yy",
        onSelect: function () {
            $(this).trigger('change');
        }
    });
    $('#customer_refer_id').select2({
        placeholder: customer.jsonLang["Chọn người giới thiệu"],
        ajax: {
            url: laroute.route('admin.customer.search-customer-refer'),
            dataType: 'json',
            delay: 250,
            type: 'POST',
            data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                };
                return query;
            },
            processResults: function (response) {
                console.log(response);
                response.page = response.page || 1;
                return {
                    results: response.search.results,
                    pagination: {
                        more: response.pagination
                    }
                };
            },
            cache: true,
            delay: 250
        },
        allowClear: true
        // minimumInputLength: 3
    });
    $('#addphone').click(function () {
        $('#div-add').empty();
        $("#div-add").append('<input name="phone2" id="phone2" type="text" ' +
            'class="form-control m--margin-top-10" placeholder="Nhập số điện thoại"/>');
    });
    $('#birthday').datepicker({
        format: "dd/mm/yyyy"
    });
    $('#h_birthday').datepicker({
        format: "dd/mm/yyyy"
    });
    $('#h_customer_refer_id').select2({
        placeholder: customer.jsonLang["Nhập thông tin người giới thiệu"],
        ajax: {
            url: laroute.route('admin.customer.search-customer-refer'),
            dataType: 'json',
            delay: 250,
            type: 'POST',
            data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                };
                return query;
            }
        },
        minimumInputLength: 3
    });
    $('#customer_source_id').select2({
        placeholder: customer.jsonLang["Chọn nguồn khách hàng"],
        allowClear: true
    });
    $('#addphone-edit').click(function () {
        if ($("#h_phone2").length == 0) {
            $('#div-add-edit').empty();
            $("#div-add-edit").append('<input name="phone2" id="h_phone2" ' +
                'class="form-control col-lg" placeholder="Nhập số điện thoại"/>');
        }

    });

    $('.btn-delete-img').click(function () {
        $('.img').remove();
        // $(this).remove();
        $('#ima').val('');

    });

    $('.btn-add').click(function () {
        $('#form-add').validate({
            rules: {
                customer_group_id: {
                    required: true
                },
                full_name: {
                    required: true
                },
                phone1: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 11
                },
                tax_code: {
                    minlength: 10,
                    maxlength: 13
                },
                representative: {
                    maxlength: 191
                },
                hotline: {
                    minlength: 10,
                    maxlength: 15
                },
                // address: {
                //     required: true
                // },
                profile_code: {
                    maxlength: 190
                }
            },
            messages: {
                customer_group_id: {
                    required: customer.jsonLang["Hãy chọn nhóm khách hàng"]
                },
                full_name: {
                    required: customer.jsonLang["Hãy nhập tên khách hàng"]
                },
                phone1: {
                    required: customer.jsonLang["Hãy nhập số điện thoại"],
                    number: customer.jsonLang["Số điện thoại không hợp lệ"],
                    minlength: customer.jsonLang["Tối thiểu 10 số"],
                    maxlength: customer.jsonLang["Tối đa 11 số"]
                },
                address: {
                    required: customer.jsonLang["Hãy nhập địa chỉ"]
                },
                tax_code: {
                    minlength: customer.jsonLang["Mã số thuế tối thiểu 11 ký tự"],
                    maxlength: customer.jsonLang["Mã số thuế tối đa 13 ký tự"]
                },
                representative: {
                    maxlength: customer.jsonLang["Người đại diện tối đa 191 ký tự"]
                },
                hotline: {
                    minlength: customer.jsonLang["Hotline tối thiểu 10 ký tự"],
                    maxlength: customer.jsonLang["Hotline tối đa 15 ký tự"]
                },

                profile_code: {
                    maxlength: customer.jsonLang["Mã hồ sơ tối đa 190 kí tự"]
                }
            },
            submitHandler: function () {
                var gender = $('input[name="gender"]:checked').val();
                var customer_group_id = $('#customer_group_id').val();
                var full_name = $('#full_name').val();
                var phone1 = $('#phone1').val();
                var phone2 = $('#phone2').val();
                var province_id = $('#province_id').val();
                var district_id = $('#district_id').val();
                var ward_id = $('#ward_id').val();
                var address = $('#address').val();
                var email = $('#email').val();
                var day = $('#day').val();
                var month = $('#month').val();
                var year = $('#year').val();
                var customer_source_id = $('#customer_source_id').val();
                var customer_refer_id = $('#customer_refer_id').val();
                var facebook = $('#facebook').val();
                var note = $('#note').val();
                var customer_avatar = $('#customer_avatar').val();
                var imageCustomer = [];
                var fileCustomer = [];

                // update 08/11/2021 type customer personal or business
                var customer_type = $('#customer_type').val();
                var tax_code = $('#tax_code').val();
                var representative = $('#representative').val();
                var hotline = $('#hotline').val();
                if (customer_type == 'personal') {
                    tax_code = '';
                    representative = '';
                    hotline = '';
                }

                var continute = true;

                //Lấy hình ảnh kèm theo
                $.each($('.div_image_customer').find('.image-show-child'), function () {
                    imageCustomer.push({
                        'path': $(this).find("input[name='img-link-customer']").val(),
                        'file_name': $(this).find("input[name='img-name-customer']").val(),
                        'type': $(this).find("input[name='img-type-customer']").val()
                    });
                });
                //Lấy file kèm theo
                $.each($('.div_file_customer').find('.div_file'), function () {
                    fileCustomer.push({
                        'path': $(this).find("input[name='file-link-customer']").val(),
                        'file_name': $(this).find("input[name='file-name-customer']").val(),
                        'type': $(this).find("input[name='file-type-customer']").val()
                    });
                });

                if (continute == true) {
                    if (email != '') {
                        if (!isValidEmailAddress(email)) {
                            $('.error_email').text(customer.jsonLang["Email không hợp lệ"]);
                            return false;
                        } else {

                            $('.error_email').text('');
                            $.ajax({
                                url: laroute.route('admin.customer.submitAdd'),
                                dataType: 'JSON',
                                method: 'POST',
                                data: {
                                    gender: gender,
                                    customer_group_id: customer_group_id,
                                    full_name: full_name,
                                    phone1: phone1,
                                    phone2: phone2,
                                    province_id: province_id,
                                    district_id: district_id,
                                    ward_id: ward_id,
                                    address: address,
                                    email: email,
                                    day: day,
                                    month: month,
                                    year: year,
                                    customer_source_id: customer_source_id,
                                    customer_refer_id: customer_refer_id,
                                    facebook: facebook,
                                    note: note,
                                    customer_avatar: customer_avatar,
                                    postcode: $('#postcode').val(),
                                    imageCustomer: imageCustomer,
                                    fileCustomer: fileCustomer,
                                    custom_1: $('#custom_1').val(),
                                    custom_2: $('#custom_2').val(),
                                    custom_3: $('#custom_3').val(),
                                    custom_4: $('#custom_4').val(),
                                    custom_5: $('#custom_5').val(),
                                    custom_6: $('#custom_6').val(),
                                    custom_7: $('#custom_7').val(),
                                    custom_8: $('#custom_8').val(),
                                    custom_9: $('#custom_9').val(),
                                    custom_10: $('#custom_10').val(),
                                    profile_code: $('#profile_code').val()
                                },
                                success: function (res) {
                                    if (res.error == false) {
                                        swal(customer.jsonLang["Thêm khách hàng thành công"], "", "success");
                                        window.location.reload();
                                    } else {
                                        swal(res.message, "", "error");
                                    }
                                }
                            });
                        }
                    } else {
                        $.ajax({
                            url: laroute.route('admin.customer.submitAdd'),
                            dataType: 'JSON',
                            method: 'POST',
                            data: {
                                gender: gender,
                                customer_group_id: customer_group_id,
                                full_name: full_name,
                                phone1: phone1,
                                phone2: phone2,
                                province_id: province_id,
                                district_id: district_id,
                                ward_id: ward_id,
                                address: address,
                                email: email,
                                day: day,
                                month: month,
                                year: year,
                                customer_source_id: customer_source_id,
                                customer_refer_id: customer_refer_id,
                                facebook: facebook,
                                note: note,
                                customer_avatar: customer_avatar,
                                postcode: $('#postcode').val(),
                                imageCustomer: imageCustomer,
                                fileCustomer: fileCustomer,
                                custom_1: $('#custom_1').val(),
                                custom_2: $('#custom_2').val(),
                                custom_3: $('#custom_3').val(),
                                custom_4: $('#custom_4').val(),
                                custom_5: $('#custom_5').val(),
                                custom_6: $('#custom_6').val(),
                                custom_7: $('#custom_7').val(),
                                custom_8: $('#custom_8').val(),
                                custom_9: $('#custom_9').val(),
                                custom_10: $('#custom_10').val(),
                                customer_type: customer_type,
                                tax_code: tax_code,
                                representative: representative,
                                hotline: hotline,
                                profile_code: $('#profile_code').val()
                            },
                            success: function (res) {
                                if (res.error == false) {
                                    swal(customer.jsonLang["Thêm khách hàng thành công"], "", "success");
                                    window.location.reload();
                                } else {
                                    swal(res.message, "", "error");
                                }
                            }
                        });
                    }
                }
            }
        });
    });
    $('.btn-add-close').click(function () {

        $('#form-add').validate({
            rules: {
                customer_group_id: {
                    required: true
                },
                full_name: {
                    required: true
                },
                phone1: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 11
                },
                // address: {
                //     required: true
                // },
                tax_code: {
                    minlength: 10,
                    maxlength: 13
                },
                representative: {
                    maxlength: 191
                },
                hotline: {
                    minlength: 10,
                    maxlength: 15
                },
                profile_code: {
                    maxlength: 190
                }
            },
            messages: {
                customer_group_id: {
                    required: customer.jsonLang["Hãy chọn nhóm khách hàng"]
                },
                full_name: {
                    required: customer.jsonLang["Hãy nhập tên khách hàng"]
                },
                phone1: {
                    required: customer.jsonLang["Hãy nhập số điện thoại"],
                    number: customer.jsonLang["Số điện thoại không hợp lệ"],
                    minlength: customer.jsonLang["Tối thiểu 10 số"],
                    maxlength: customer.jsonLang["Tối đa 10 số"],
                },
                address: {
                    required: customer.jsonLang["Hãy nhập địa chỉ"]
                },
                tax_code: {
                    minlength: customer.jsonLang["Mã số thuế tối thiểu 11 ký tự"],
                    maxlength: customer.jsonLang["Mã số thuế tối đa 13 ký tự"]
                },
                representative: {
                    maxlength: customer.jsonLang["Người đại diện tối đa 191 ký tự"]
                },
                hotline: {
                    minlength: customer.jsonLang["Hotline tối thiểu 10 ký tự"],
                    maxlength: customer.jsonLang["Hotline tối đa 15 ký tự"]
                },

                profile_code: {
                    maxlength: customer.jsonLang["Mã hồ sơ tối đa 190 kí tự"]
                }
            },
            submitHandler: function () {
                var gender = $('input[name="gender"]:checked').val();
                var customer_group_id = $('#customer_group_id').val();
                var full_name = $('#full_name').val();
                var phone1 = $('#phone1').val();
                var phone2 = $('#phone2').val();
                var province_id = $('#province_id').val();
                var district_id = $('#district_id').val();
                var ward_id = $('#ward_id').val();
                var address = $('#address').val();
                var email = $('#email').val();
                var day = $('#day').val();
                var month = $('#month').val();
                var year = $('#year').val();
                var customer_source_id = $('#customer_source_id').val();
                var customer_refer_id = $('#customer_refer_id').val();
                var facebook = $('#facebook').val();
                var note = $('#note').val();
                var customer_avatar = $('#customer_avatar').val();
                var imageCustomer = [];
                var fileCustomer = [];

                // update 08/11/2021 type customer personal or business
                var customer_type = $('#customer_type').val();
                var tax_code = $('#tax_code').val();
                var representative = $('#representative').val();
                var hotline = $('#hotline').val();
                if (customer_type == 'personal') {
                    tax_code = '';
                    representative = '';
                    hotline = '';
                }

                var continute = true;

                //Lấy hình ảnh kèm theo
                $.each($('.div_image_customer').find('.image-show-child'), function () {
                    imageCustomer.push({
                        'path': $(this).find("input[name='img-link-customer']").val(),
                        'file_name': $(this).find("input[name='img-name-customer']").val(),
                        'type': $(this).find("input[name='img-type-customer']").val()
                    });
                });
                //Lấy file kèm theo
                $.each($('.div_file_customer').find('.div_file'), function () {
                    fileCustomer.push({
                        'path': $(this).find("input[name='file-link-customer']").val(),
                        'file_name': $(this).find("input[name='file-name-customer']").val(),
                        'type': $(this).find("input[name='file-type-customer']").val()
                    });
                });

                if (continute == true) {

                    let ch_customer_id = null;
                    if ($('#ch_customer_id').length) {
                        ch_customer_id = $('#ch_customer_id').val();
                    }

                    if (email != '') {
                        if (!isValidEmailAddress(email)) {
                            $('.error_email').text(customer.jsonLang["Email không hợp lệ"]);
                            return false;
                        } else {
                            $('.error_email').text('');
                            $.ajax({
                                url: laroute.route('admin.customer.submitAdd'),
                                dataType: 'JSON',
                                method: 'POST',
                                data: {
                                    gender: gender,
                                    customer_group_id: customer_group_id,
                                    full_name: full_name,
                                    phone1: phone1,
                                    phone2: phone2,
                                    province_id: province_id,
                                    district_id: district_id,
                                    ward_id: ward_id,
                                    address: address,
                                    email: email,
                                    day: day,
                                    month: month,
                                    year: year,
                                    customer_source_id: customer_source_id,
                                    customer_refer_id: customer_refer_id,
                                    facebook: facebook,
                                    note: note,
                                    customer_avatar: customer_avatar,
                                    postcode: $('#postcode').val(),
                                    imageCustomer: imageCustomer,
                                    fileCustomer: fileCustomer,
                                    custom_1: $('#custom_1').val(),
                                    custom_2: $('#custom_2').val(),
                                    custom_3: $('#custom_3').val(),
                                    custom_4: $('#custom_4').val(),
                                    custom_5: $('#custom_5').val(),
                                    custom_6: $('#custom_6').val(),
                                    custom_7: $('#custom_7').val(),
                                    custom_8: $('#custom_8').val(),
                                    custom_9: $('#custom_9').val(),
                                    custom_10: $('#custom_10').val(),
                                    customer_type: customer_type,
                                    tax_code: tax_code,
                                    representative: representative,
                                    hotline: hotline,
                                    profile_code: $('#profile_code').val(),
                                    ch_customer_id: ch_customer_id
                                },
                                success: function (res) {
                                    if (res.error == false) {
                                        swal(customer.jsonLang["Thêm khách hàng thành công"], "", "success").then(function (result) {
                                            if (typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup') {
                                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                                    customer.processFunctionAddCustomer(res.data);
                                                }
                                                if (result.value == true) {
                                                    customer.processFunctionAddCustomer(res.data);
                                                }

                                            } else {
                                                window.location = laroute.route('admin.customer');
                                            }
                                        });


                                    } else {
                                        swal(res.message, "", "error");
                                    }
                                }
                            });
                        }
                    } else {
                        $.ajax({
                            url: laroute.route('admin.customer.submitAdd'),
                            dataType: 'JSON',
                            method: 'POST',
                            data: {
                                gender: gender,
                                customer_group_id: customer_group_id,
                                full_name: full_name,
                                phone1: phone1,
                                phone2: phone2,
                                province_id: province_id,
                                district_id: district_id,
                                ward_id: ward_id,
                                address: address,
                                email: email,
                                day: day,
                                month: month,
                                year: year,
                                customer_source_id: customer_source_id,
                                customer_refer_id: customer_refer_id,
                                facebook: facebook,
                                note: note,
                                customer_avatar: customer_avatar,
                                postcode: $('#postcode').val(),
                                imageCustomer: imageCustomer,
                                fileCustomer: fileCustomer,
                                custom_1: $('#custom_1').val(),
                                custom_2: $('#custom_2').val(),
                                custom_3: $('#custom_3').val(),
                                custom_4: $('#custom_4').val(),
                                custom_5: $('#custom_5').val(),
                                custom_6: $('#custom_6').val(),
                                custom_7: $('#custom_7').val(),
                                custom_8: $('#custom_8').val(),
                                custom_9: $('#custom_9').val(),
                                custom_10: $('#custom_10').val(),
                                customer_type: customer_type,
                                tax_code: tax_code,
                                representative: representative,
                                hotline: hotline,
                                profile_code: $('#profile_code').val(),
                                ch_customer_id: ch_customer_id
                            },
                            success: function (res) {
                                if (res.error == false) {
                                    swal(customer.jsonLang["Thêm khách hàng thành công"], "", "success").then(function (result) {
                                        if (typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup') {
                                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                                customer.processFunctionAddCustomer(res.data);
                                            }
                                            if (result.value == true) {
                                                customer.processFunctionAddCustomer(res.data);
                                            }

                                        } else {
                                            window.location = laroute.route('admin.customer');
                                        }
                                    });
                                } else {
                                    swal(res.message, "", "error");
                                }
                            }
                        });
                    }
                }

            }
        });
    });
    // $('#amount_debt').mask('000,000,000', {reverse: true});


});

var stt = 0;

var customer = {
    jsonLang: null,
    processFunctionAddCustomer: function (data) {
        window.close();

        const bc = new BroadcastChannel('addSuccessCustomer');
        if ($('#ch_customer_id').length) {
            data.ch_customer_id = $('#ch_customer_id').val();
        }

        bc.postMessage(data);
    },

    remove: function (obj, id) {

        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: customer.jsonLang["Thông báo"],
            text: customer.jsonLang["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: customer.jsonLang["Xóa"],
            cancelButtonText: customer.jsonLang["Hủy"],
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.customer.remove', {id: id}), function () {
                    swal(
                        customer.jsonLang["Xóa thành công"],
                        '',
                        'success'
                    );
                    // window.location.reload();
                    $('#autotable').PioTable('refresh');
                });
            }
        });

    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.customer.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
    add_customer_group: function (close) {
        $('#type_add').val(close);


        var form = $('#form-customer-group');

        form.validate({
            rules: {
                group_name: {
                    required: true,
                }
            },
            messages: {
                group_name: {
                    required: customer.jsonLang["Hãy nhập tên nhóm khách hàng"],
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        var input = $('#type_add').val();
        var group_name = $('#group_name').val();
        $.ajax({
            url: laroute.route('admin.customer.add-customer-group'),
            data: {
                group_name: group_name,
                close: input
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.status == 1) {
                    if (response.close != 0) {
                        $("#add").modal("hide");
                    }
                    $('#form-customer-group')[0].reset();
                    swal(customer.jsonLang["Thêm nhóm khách hàng thành công"], "", "success");
                    $('#customer_group_id > option').remove();
                    $.each(response.optionGroup, function (index, element) {
                        $('#customer_group_id').append('<option value="' + index + '">' + element + '</option>')
                    });
                    $('#autotable').PioTable('refresh');
                } else {
                    $('.error-group-name').text(customer.jsonLang["Nhóm khách hàng đã tồn tại"]);
                    $('.error-group-name').css('color', 'red');
                }
            }
        });

    },
    add_customer_refer: function (close) {

        $('#type_add').val(close);

        var form = $('#form_refer');

        form.validate({
            rules: {
                full_name: {
                    required: true
                },
                phone1: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 11
                },
                address: {
                    required: true
                },
            },
            messages: {
                full_name: {
                    required: customer.jsonLang["Hãy nhập tên người giới thiệu"]
                },
                phone1: {
                    required: customer.jsonLang["Hãy nhập số điện thoại"],
                    number: customer.jsonLang["Số điện thoại không hợp lệ"],
                    minlength: customer.jsonLang["Số điện thoại tối thiểu 10 số"],
                    maxlength: customer.jsonLang["Số điện thoại tối đa 11 số"]
                },
                address: {
                    required: customer.jsonLang["Hãy nhập địa chỉ"]
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        var input = $('#type_add').val();
        var full_name = $('#full_name_refer').val();
        var phone1 = $('#phone1_refer').val();
        var address = $('#address_refer').val();
        var gender = $('#gender_refer').val();

        $.ajax({
            url: laroute.route('admin.customer.add-customer-refer'),
            data: {
                full_name: full_name,
                phone1: phone1,
                address: address,
                // gender: gender,
                close: input
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.phone_error == 1) {
                    $('.error_phone').text(response.message);
                } else {
                    $('.error_phone').text('');
                }
                if (response.status == 1) {
                    if (response.close != 0) {
                        $("#add_customer_refer").modal("hide");
                    }
                    $('#form_refer')[0].reset();
                    swal(customer.jsonLang["Thêm người giới thiệu thành công"], "", "success");
                    $('#autotable').PioTable('refresh');
                }
            }
        });


    },
    refresh: function () {
        $('input[name="search"]').val('');
        $('input[name="created_at"]').val('');
        $('select[name="customers$customer_group_id"]').val('');
        $('select[name="customers$gender"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");
    },
    active: function (id) {

        $('.customer_id').val(id);
        $('.error-code').text('');
        $('.error-tb').text('');
        $('#code_search').val('');
        $('#tb-card > tbody').empty();
        $('#active-modal').modal("show");
        $('#code_search').keydown(function () {
            $('.error-code').text('');
        });
        $('#check').click(function () {
            $.ajax({
                url: laroute.route('admin.customer.check-card'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    card: $('#code_search').val()
                },
                success: function (res) {
                    if (res.data_card == '') {
                        $('.error-code').text(customer.jsonLang["Mã thẻ dịch vụ không tồn tại"]);
                    } else {
                        var check = true;
                        $.each($('#tb-card tbody tr'), function () {
                            let codeHidden = $(this).find("input[name='code']");
                            let value = codeHidden.val();
                            let code = res.data_card.card_code;
                            if (value == code) {
                                check = false;
                            }
                        });
                        if (check == true) {
                            var tpl = $('#tb-card-tpl').html();
                            tpl = tpl.replace(/{code}/g, res.data_card.card_code);
                            tpl = tpl.replace(/{name_code}/g, res.data_card.name_code);
                            tpl = tpl.replace(/{day_active}/g, res.day);
                            tpl = tpl.replace(/{service_card_id}/g, res.data_card.service_card_id);
                            tpl = tpl.replace(/{service_card_list_id}/g, res.data_card.service_card_list_id);
                            if (res.data_card.expired_day != 0) {
                                tpl = tpl.replace(/{day_expiration}/g, res.data_card.expired_day);
                            } else {
                                tpl = tpl.replace(/{day_expiration}/g, customer.jsonLang["Không giới hạn"]);
                            }
                            if (res.data_card.card_type == 'money') {
                                tpl = tpl.replace(/{name_type}/g, customer.jsonLang["Tiền mặt"]);
                                tpl = tpl.replace(/{type}/g, res.data_card.card_type);
                                tpl = tpl.replace(/{price_td}/g, formatNumber(res.data_card.money));
                                tpl = tpl.replace(/{price}/g, res.data_card.money);
                                tpl = tpl.replace(/{number_using}/g, 1);
                            } else {
                                tpl = tpl.replace(/{name_type}/g, res.data_card.name_sv);
                                tpl = tpl.replace(/{type}/g, res.data_card.card_type);
                                tpl = tpl.replace(/{price_td}/g, 0);
                                tpl = tpl.replace(/{price}/g, 0);
                                tpl = tpl.replace(/{number_using}/g, res.data_card.number_using);
                            }
                            $('#tb-card > tbody').append(tpl);
                            $('#code_search').val('');
                            $('.remove').click(function () {
                                $(this).closest('.tr-card').remove()
                            });
                        }

                    }
                }
            })
        });

    },
    click_active: function () {

        var tb_card = [];
        $.each($('#tb-card').find(".tr-card"), function () {
            var $tds = $(this).find("td input");
            $.each($tds, function () {
                tb_card.push($(this).val());
            });

        });
        $.ajax({
            url: laroute.route('admin.customer.submitAcitve'),
            dataType: 'JSON',
            method: 'post',
            data: {
                table_card: tb_card,
                customer_id: $('.customer_id').val()
            },
            success: function (res) {
                if (res.card_null == 1) {
                    $('.error-tb').text(customer.jsonLang['Vui lòng nhập mã dịch vụ để kích hoạt']);
                } else {
                    $('.error-tb').text('');
                }
                if (res.success == 1) {
                    $('#active-modal').modal("hide");
                    swal(customer.jsonLang["Kích hoạt thẻ dịch vụ thành công"], "", "success");
                    $('#autotable').PioTable('refresh');
                }
            }
        });


    },
    enterDebt: function () {

        var amountDebt = $('#amount_debt').val().replace(new RegExp('\\,', 'g'), '');
        var error = $('.error-amount-debt');
        if (amountDebt == '') {
            error.text(customer.jsonLang["Vui lòng nhập số tiền."])
        } else {
            $.ajax({
                url: laroute.route('admin.customer.enter-debt'),
                method: "POST",
                data: {
                    idCustomer: id_customer,
                    amountDebt: amountDebt,
                    note: $('#note').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        $('#m_modal_1').modal('hide');
                        swal(customer.jsonLang["Nhập công nợ thành công"], "", "success");
                        window.location.reload();
                    }
                }
            });
        }

    },
    dropzoneCustomer: function () {
        Dropzone.options.dropzoneCustomer = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 20,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
            /*renameFile: function (file) {
                var dt = new Date();
                var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
                var random = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (let z = 0; z < 10; z++) {
                    random += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
            },*/
            init: function () {
                this.on("sending", function (file, xhr, data) {
                    data.append("link", "_customer.");
                });

                this.on("success", function (file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

                    if (response.error == 0) {
                        $("#up-image-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='fileName' value='" + response.file + "' typeFile='" + response.type + "'>");
                    }
                });

                this.on('removedfile', function (file, response) {
                    var checkImage = $('#up-image-temp').find('input[name="fileName"]');

                    $.each(checkImage, function () {
                        if ($(this).attr('class') == file.upload.filename) {
                            $(this).remove();
                        }
                    });
                });
            }
        };
    },
    dropzoneFile: function () {
        Dropzone.options.dropzoneFile = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 20,
            acceptedFiles: ".pdf,.doc,.docx,.pdf,.csv,.xls,.xlsx",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
            // renameFile: function (file) {
            //     var dt = new Date();
            //     var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
            //     var random = "";
            //     var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            //     for (let z = 0; z < 10; z++) {
            //         random += possible.charAt(Math.floor(Math.random() * possible.length));
            //     }
            //     return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
            // },
            init: function () {
                this.on("sending", function (file, xhr, data) {
                    data.append("link", "_customer.");
                });

                this.on("success", function (file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

                    if (response.error == 0) {
                        $("#up-file-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='fileName' value='" + response.file + "' typeFile='" + response.type + "'>");
                    }
                });

                this.on('removedfile', function (file, response) {
                    var checkImage = $('#up-file-temp').find('input[name="fileName"]');

                    $.each(checkImage, function () {
                        if ($(this).attr('class') == file.upload.filename) {
                            $(this).remove();
                        }
                    });
                });
            }
        };
    },
    modalImage: function () {
        $('#up-image-temp').empty();
        $('#dropzoneCustomer')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('#dropzoneCustomer').removeClass('dz-started');

        $('#modal-image-customer').modal({
            backdrop: 'static', keyboard: false
        });
    },
    removeImage: function (e) {
        $(e).closest('.image-show-child').remove();
    },
    submitImageCustomer: function () {
        var checkImage = $('#up-image-temp').find('input[name="fileName"]');

        $.each(checkImage, function () {
            let tpl = $('#tpl-image').html();
            tpl = tpl.replace(/{imageLink}/g, $(this).val());
            tpl = tpl.replace(/{imageName}/g, $(this).attr('class'));
            tpl = tpl.replace(/{imageType}/g, $(this).attr('typeFile'));
            $('.div_image_customer').append(tpl);
            $('.delete-img-sv').css('display', 'block');
        });

        $('#modal-image-customer').modal('hide');
    },
    modalFile: function () {
        $('#up-file-temp').empty();
        $('#dropzoneFile')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('#dropzoneFile').removeClass('dz-started');

        $('#modal-file-customer').modal({
            backdrop: 'static', keyboard: false
        });
    },
    submitFileCustomer: function () {
        var checkFile = $('#up-file-temp').find('input[name="fileName"]');

        $.each(checkFile, function () {
            let tpl = $('#tpl-file').html();
            tpl = tpl.replace(/{fileLink}/g, $(this).val());
            tpl = tpl.replace(/{fileName}/g, $(this).attr('class'));
            tpl = tpl.replace(/{fileType}/g, $(this).attr('typeFile'));

            $('.div_file_customer').append(tpl);
        });

        $('#modal-file-customer').modal('hide');
    },
    removeFile: function (obj) {
        $(obj).closest('.div_file').remove();
    },
    changeBoolean: function (obj) {
        if ($(obj).is(":checked")) {
            $(obj).val(1);
        } else {
            $(obj).val(0);
        }
    },
    showModalBranch: function (customerId) {
        $.ajax({
            url: laroute.route('admin.customer.modal-customer-branch'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: customerId
            },
            success: function (res) {
                $('#show-modal').html(res.url);
                $('#show-modal').find('#modal-customer-branch').modal({
                    backdrop: 'static', keyboard: false
                });

                $('#customer_branch_id').select2({
                    placeholder: customer.jsonLang['Chọn chi nhánh']
                });
            }
        });
    },
    saveCustomerBranch: function (customerId) {
        $.ajax({
            url: laroute.route('admin.customer.save-customer-branch'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: customerId,
                branch_id: $('#customer_branch_id').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success");
                    $('#autotable').PioTable('refresh');

                    $('#modal-customer-branch').modal('hide');
                } else {
                    swal(res.message, "", "error");
                }
            }
        });
    },
    append_parameter: function (obj) {
        var text = obj;

        var txtarea = document.getElementById('note');
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
    searchList: function () {
        let search = $("input[name='search']").val();
        let customer_group_id = $("select[name='customers$customer_group_id']").val();
        let created_at = $("input[name='created_at']").val();

        $('#search_export').val(search);
        $('#customer_group_id_export').val(customer_group_id);
        $('#created_at_export').val(created_at);
    },

    //In công nợ
    printBillDebt: function (customerId) {
        $('#customer_id_bill_debt').val(customerId);
        $('#form-customer-debt').submit();
    },

    //Show pop thanh toán nhanh công nợ
    popQuickReceiptDebt: function (customerId, totalDebt) {
        $.ajax({
            url: laroute.route('admin.customer.pop-quick-receipt-debt'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: customerId,
                totalDebt: totalDebt
            },
            success: function (res) {
                $('#div-receipt').html(res.url);
                $('#div-receipt').find('#modal-receipt').modal({
                    backdrop: 'static', keyboard: false
                });
                //Load sẵn hình thức thanh toán = tiền mặt
                $('#receipt_type').val('CASH').trigger('change');

                new AutoNumeric.multiple('#payment_method_CASH', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

                if ($('#member_money').val() <= 0 || typeof $('#member_money').val() == 'undefined') {
                    $("#receipt_type option[value='MEMBER_MONEY']").remove();
                } else {
                    $('#receipt_type').append('<option value="MEMBER_MONEY">' + customer.jsonLang['Tài khoản thành viên'] + '</option>');
                }

                $('#receipt_type').select2({
                    placeholder: customer.jsonLang['Chọn hình thức thanh toán']
                }).on('select2:select', function (event) {
                    // Lấy id và tên của phương thức thanh toán
                    let methodId = event.params.data.id;
                    let methodName = event.params.data.text;
                    let tpl = $('#payment_method_tpl').html();
                    tpl = tpl.replace(/{label}/g, methodName);
                    tpl = tpl.replace(/{id}/g, methodId);
                    tpl = tpl.replace(/{id}/g, methodId);

                    if (methodId == 'VNPAY') {
                        tpl = tpl.replace(/{displayQrCode}/g, 'block');
                    } else {
                        tpl = tpl.replace(/{displayQrCode}/g, 'none');
                    }

                    if (methodId == 'MEMBER_MONEY') {
                        let money = $('#member_money').val();
                        tpl = tpl.replace(/{money}/g, customer.jsonLang['(Còn '] + formatNumber(money) + ')');
                    } else {
                        tpl = tpl.replace(/{money}/g, '*');
                    }

                    $('.payment_method').append(tpl);
                    new AutoNumeric.multiple('#payment_method_' + methodId, {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                }).on('select2:unselect', function (event) {
                    // UPDATE 15/03/2021
                    let moneyTobePaid = $('#receipt_amount').val().replace(new RegExp('\\,', 'g'), ''); // tiền phải thanh toán
                    let methodId = event.params.data.id;
                    let amountThis = $('#payment_method_' + methodId).val().replace(new RegExp('\\,', 'g'), '');
                    $('.payment_method_' + methodId).remove();
                    // tính lại tổng tiền trả (tổng tiền trả ban đầu - tiền unselect)
                    let amountAllOld = $('#amount_all').val().replace(new RegExp('\\,', 'g'), '');
                    let amountAllNew = amountAllOld - amountThis;
                    $('#amount_all').val(formatNumber(amountAllNew.toFixed(decimal_number)));
                    $('.cl_amount_all').text(formatNumber(amountAllNew.toFixed(decimal_number)));
                    // tính lại tiền nợ
                    if (moneyTobePaid - amountAllNew > 0) {
                        $('#amount_rest').val(formatNumber((moneyTobePaid - amountAllNew).toFixed(decimal_number)));
                        $('.cl_amount_rest').text(formatNumber((moneyTobePaid - amountAllNew).toFixed(decimal_number)));
                    } else {
                        $('#amount_rest').val(0);
                        $('.cl_amount_rest').text(0);
                    }
                    // tính lại tiền trả khách
                    if (amountAllNew - moneyTobePaid > 0) {
                        $('#amount_return').val(formatNumber((amountAllNew - moneyTobePaid).toFixed(decimal_number)));
                        $('.cl_amount_return').text(formatNumber((amountAllNew - moneyTobePaid).toFixed(decimal_number)));
                    } else {
                        $('#amount_return').val(0);
                        $('.cl_amount_return').text(0);
                    }
                    // END UPDATE 15/03/2021
                });

            }
        });
    },

    //Submit thanh toán nhanh công nợ
    submitQuickReceiptDebt: function (customerId, isPrint, viewPrint) {
        var receipt_type = $('#receipt_type').val();
        let arrayMethod = {};
        $.each($('.payment_method').find('.method'), function () {
            let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
            let getId = $(this).find("input[name='payment_method']").attr('id');
            let methodCode = getId.slice(15);
            arrayMethod[methodCode] = moneyEachMethod;
        });
        var totalDebt = $('#receipt_amount').val().replace(new RegExp('\\,', 'g'), '');
        var amount_return = $('#amount_return').val().replace(new RegExp('\\,', 'g'), '');


        if (receipt_type == '') {
            $('.error_type').text(json['Hãy chọn hình thức thanh toán']);
            return false;
        } else {
            $('.error_type').text('');
        }

        $.ajax({
            url: laroute.route('admin.customer.submit-quick-receipt-debt'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: customerId,
                receipt_type: receipt_type,
                array_method: arrayMethod,
                total_debt: totalDebt,
                total_amount_paid: $('#amount_all').val().replace(new RegExp('\\,', 'g'), ''),
                amount_return: amount_return,
                member_money: $('#member_money').val(),
                note: $('#note').val(),
            },
            success: function (res) {
                if (res.error == true) {
                    swal(customer.jsonLang["Thanh toán công nợ thất bại"], res.message, "error");
                } else {
                    if (isPrint == 1 && viewPrint == 'debt') {
                        $('#customer_id_bill_debt').val(customerId);
                        $('#form-customer-debt').submit();
                    }

                    swal(customer.jsonLang["Thanh toán công nợ thành công"], "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.reload();
                        }
                        if (result.value == true) {
                            window.location.reload();
                        }
                    });

                    window.location.reload();
                }
            },
            error: function (res) {
                swal(customer.jsonLang["Thanh toán công nợ thất bại"], "", "error");
            }
        });
    }
};

var detail = {
    _init: function () {

    },
    modal_process_card: function () {

        $.ajax({
            url: laroute.route('admin.customer.modal-process-card'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id_customer
            },
            success: function (res) {
                $('#my-popup').html(res.url);
                $('#my-popup').find('#modal-process-card').modal({
                    backdrop: 'static', keyboard: false
                });
                $('#service_card_id').select2({
                    placeholder: customer.jsonLang["Chọn thẻ dịch vụ"]
                });
                $('#actived_date').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'vi',
                    // startDate: '0d'
                });
                $('#expired_date').datepicker({
                    format: 'dd/mm/yyyy',
                    language: 'vi',
                    // startDate: '0d'
                });
            }
        });

    },
    choose_service_card: function (obj) {
        $.ajax({
            url: laroute.route('admin.customer.choose-service-card'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                service_card_id: $(obj).val()
            },
            success: function (res) {
                $('#actived_date').val('').prop("disabled", false);
                $('#expired_date').val('').prop("disabled", true);

                if (res.service_card.service_card_type == 'money') {
                    $('#count_using').val(1).prop("disabled", true);
                    $('#end_using').val(0).prop("disabled", true);
                } else {
                    $('#count_using').val('').prop("disabled", false);
                    $('#end_using').val('').prop("disabled", false);
                }
            }
        });
    },
    change_active_date: function (obj) {
        $.ajax({
            url: laroute.route('admin.customer.change-active-date'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                service_card_id: $('#service_card_id').val(),
                actived_date: $(obj).val()
            },
            success: function (res) {
                $('#expired_date').prop("disabled", false);
                if (res.expired_date != 0) {
                    $('#expired_date').val(res.expired_date);
                }
            }
        });
    },
    submit_process_card: function (id) {

        var form = $('#form-process-card');

        form.validate({
            rules: {
                service_card_id: 'required',
                actived_date: 'required',
                expired_date: 'required',
                count_using: {
                    number: true,
                    required: true
                },
                end_using: {
                    number: true,
                    required: true
                }
            },
            messages: {
                service_card_id: {
                    required: customer.jsonLang["Hãy chọn thẻ dịch vụ"]
                },
                actived_date: {
                    required: customer.jsonLang["Hãy chọn ngày kích hoạt"],
                },
                expired_date: {
                    required: customer.jsonLang["Hãy chọn ngày hết hạn"],
                },
                count_using: {
                    required: customer.jsonLang["Hãy nhập số lần sử dụng"],
                    number: customer.jsonLang["Số lần sử dụng không hợp lệ"],
                },
                end_using: {
                    required: customer.jsonLang["Hãy nhập số lần còn lại"],
                    number: customer.jsonLang["Số lần sử dụng không hơp lệ"]
                },
            }
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('admin.customer.submit-process-card'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                customer_id: id,
                service_card_id: $('#service_card_id').val(),
                actived_date: $('#actived_date').val(),
                expired_date: $('#expired_date').val(),
                count_using: $('#count_using').val(),
                end_using: $('#end_using').val()
            },
            success: function (res) {
                if (res.error == true) {
                    swal(customer.jsonLang["Tạo thẻ liệu trình thất bại"], "", "error");
                } else {
                    swal(customer.jsonLang["Tạo thẻ liệu trình thành công"], "", "success");
                    window.location.reload();
                }
            },
            error: function (res) {
                swal(customer.jsonLang["Tạo thẻ liệu trình thất bại"], "", "error");
            }
        });

    },
    modal_commission: function (id, commission_money) {

        $.ajax({
            url: laroute.route('admin.customer.commission'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: id,
                commission_money: commission_money
            },
            success: function (res) {
                $('#my-popup').html(res.url);
                $('#my-popup').find('#modal-commission').modal({
                    backdrop: 'static', keyboard: false
                });
                $('#type').select2({
                    placeholder: customer.jsonLang["Chọn hình thức quy đổi"]
                });

                new AutoNumeric.multiple('#money', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: decimal_number,
                    minimumValue: 0
                });
            }
        });

    },
    submit_commission: function (id) {

        var form = $('#form-commission');

        form.validate({
            rules: {
                money: {
                    required: true
                },
                type: {
                    required: true
                }
            },
            messages: {
                money: {
                    required: customer.jsonLang["Hãy nhập tiền quy đổi"]
                },
                type: {
                    required: customer.jsonLang["Hãy chọn hình thức quy đổi"]
                }
            }
        });
        if (!form.valid()) {
            return false;
        }
        $.ajax({
            url: laroute.route('admin.customer.submit-commission'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                money: $('#money').val().replace(new RegExp('\\,', 'g'), ''),
                type: $('#type').val(),
                note: $('#note').val(),
                customer_id: id
            },
            success: function (res) {
                if (res.error == true) {
                    swal(customer.jsonLang["Quy đổi tiền thất bại"], "", "error");
                } else {
                    swal(customer.jsonLang["Quy đổi tiền thành công"], "", "success");
                    window.location.reload();
                }
            },
            error: function (res) {
                swal(customer.jsonLang["Quy đổi tiền thất bại"], "", "error");
            }
        });

    },
    zoomImage: function (link) {
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        modal.style.display = "block";
        modalImg.src = link;
        captionText.innerHTML = '';

    },
    closeModalZoom: function () {
        // Get the modal
        var modal = document.getElementById("myModal");

        modal.style.display = "none";
    },
    usingCard: function (cardCode) {
        $.ajax({
            url: laroute.route('admin.customer.using-card'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                card_code: cardCode
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.reload();
                        }
                        if (result.value == true) {
                            window.location.reload();
                        }
                    });
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    loadTab: function (tabView, customerId) {
        $.ajax({
            url: laroute.route('admin.customer.load-tab-detail'),
            method: "POST",
            dataType: "JSON",
            data: {
                tab_view: tabView,
                customer_id: customerId
            },
            success: function (res) {
                $('.tab_detail').html(res.view);

                switch (tabView) {
                    case 'order':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.order.list-order-customer')
                        });

                        $('.btn-search-order').trigger('click');

                        break;
                    case 'debt':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.receipt.list-customer-dept')
                        });

                        $('.btn-search-debt').trigger('click');

                        break;
                    case 'loyalty':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.customer.list-loyalty')
                        });

                        $('.btn-search-commission-log').trigger('click');

                        break;
                    case 'receipt':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.customer.get-list-receipt')
                        });

                        $('.btn-search-receipt').trigger('click');

                        break;
                    case 'customer_care':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.customer.get-list-customer-real-care')
                        });

                        $('.btn-search-care').trigger('click');

                        break;
                    case 'warranty_card':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('warranty-card.list')
                        });

                        $('.btn-search-warranty-card').trigger('click');

                        break;

                    case 'contact':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.customer.list-person-contact')
                        });

                        $('.btn-search-contact').trigger('click');

                        break;

                    case 'note':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.customer.list-note')
                        });

                        $('.btn-search-note').trigger('click');

                        break;

                    case 'file':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.customer.list-file')
                        });

                        $('.btn-search-file').trigger('click');

                        break;
                    case 'deal':
                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('admin.customer.list-deals')
                        });

                        $('.btn-search-deals').trigger('click');

                        break;
                }
            }
        });
    },
    loadComment: function (tabView, customerId) {

    },

    //Show pop thêm người liên hệ
    popCreatePersonContact: function (customerId) {
        $.ajax({
            url: laroute.route('admin.customer.pop-create-person-contact'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: customerId
            },
            success: function (res) {
                $('#my-modal').html(res.html);

                $('#modal-person-contact').modal('show');

                $('#staff_title').select2({
                    placeholder: customer.jsonLang['Chọn chức vụ']
                });
            }
        });
    },

    //Submit thêm người liên hệ
    storePersonContact: function (customerId) {
        var form = $('#form-person-contact');

        form.validate({
            rules: {
                person_name: {
                    required: true,
                    maxlength: 190
                },
                person_phone: {
                    required: true,
                    maxlength: 20,
                    number: true
                },
                person_email: {
                    maxlength: 190
                },
            },
            messages: {
                person_name: {
                    required: customer.jsonLang['Hãy nhập tên người liên hệ'],
                    maxlength: customer.jsonLang['Tên người liên hệ tối đa 190 kí tự'],
                },
                person_phone: {
                    required: customer.jsonLang['Hãy nhập số điện thoại'],
                    maxlength: customer.jsonLang['Số điện thoại tối đa 20 kí tự'],
                    number: customer.jsonLang["Số điện thoại không hợp lệ"],
                },
                person_email: {
                    maxlength: customer.jsonLang['Email tối đa 190 kí tự'],
                },
            }
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('admin.customer.store-person-contact'),
            method: 'POST',
            dataType: "JSON",
            data: {
                person_name: $('#person_name').val(),
                person_phone: $('#person_phone').val(),
                person_email: $('#person_email').val(),
                staff_title_id: $('#staff_title_id').val(),
                customer_id: customerId
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");

                    $('#modal-person-contact').modal('hide');

                    $('.btn-search-contact').trigger('click');
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(customer.jsonLang['Thêm mới thất bại'], mess_error, "error");
            }
        });
    },

    //Show pop chỉnh sửa người liên hệ
    popEditPersonContact: function (personContactId) {
        $.ajax({
            url: laroute.route('admin.customer.pop-edit-person-contact'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_person_contact_id: personContactId
            },
            success: function (res) {
                $('#my-modal').html(res.html);

                $('#modal-person-contact').modal('show');

                $('#staff_title').select2({
                    placeholder: customer.jsonLang['Chọn chức vụ']
                });
            }
        });
    },

    //Submit thêm người liên hệ
    updatePersonContact: function (personContactId, customerId) {
        var form = $('#form-person-contact');

        form.validate({
            rules: {
                person_name: {
                    required: true,
                    maxlength: 190
                },
                person_phone: {
                    required: true,
                    maxlength: 20,
                    number: true
                },
                person_email: {
                    maxlength: 190
                },
            },
            messages: {
                person_name: {
                    required: customer.jsonLang['Hãy nhập tên người liên hệ'],
                    maxlength: customer.jsonLang['Tên người liên hệ tối đa 190 kí tự'],
                },
                person_phone: {
                    required: customer.jsonLang['Hãy nhập số điện thoại'],
                    maxlength: customer.jsonLang['Số điện thoại tối đa 20 kí tự'],
                    number: customer.jsonLang["Số điện thoại không hợp lệ"],
                },
                person_email: {
                    maxlength: customer.jsonLang['Email tối đa 190 kí tự'],
                },
            }
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('admin.customer.update-person-contact'),
            method: 'POST',
            dataType: "JSON",
            data: {
                person_name: $('#person_name').val(),
                person_phone: $('#person_phone').val(),
                person_email: $('#person_email').val(),
                staff_title_id: $('#staff_title_id').val(),
                customer_person_contact_id: personContactId,
                customer_id: customerId
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");

                    $('#modal-person-contact').modal('hide');

                    $('.btn-search-contact').trigger('click');
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(customer.jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    },

    //Show pop thêm ghi chú
    popCreateNote: function (customerId) {
        $.ajax({
            url: laroute.route('admin.customer.pop-create-note'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: customerId
            },
            success: function (res) {
                $('#my-modal').html(res.html);

                $('#modal-note').modal('show');
            }
        });
    },

    //Thêm ghi chú
    storeNote: function (customerId) {
        var form = $('#form-note');

        form.validate({
            rules: {
                note: {
                    required: true,

                },
            },
            messages: {
                note: {
                    required: customer.jsonLang['Hãy nhập ghi chú'],
                },
            }
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('admin.customer.store-note'),
            method: 'POST',
            dataType: "JSON",
            data: {
                note: $('#note_detail').val(),
                customer_id: customerId
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");

                    $('#modal-note').modal('hide');

                    $('.btn-search-note').trigger('click');
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(customer.jsonLang['Thêm mới thất bại'], mess_error, "error");
            }
        });
    },

    //Show pop thêm ghi chú
    popEditNote: function (noteId) {
        $.ajax({
            url: laroute.route('admin.customer.pop-edit-note'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_note_id: noteId
            },
            success: function (res) {
                $('#my-modal').html(res.html);

                $('#modal-note').modal('show');
            }
        });
    },

    //Chỉnh sửa ghi chú
    updateNote: function (noteId) {
        var form = $('#form-note');

        form.validate({
            rules: {
                note: {
                    required: true,

                },
            },
            messages: {
                note: {
                    required: customer.jsonLang['Hãy nhập ghi chú'],
                },
            }
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('admin.customer.update-note'),
            method: 'POST',
            dataType: "JSON",
            data: {
                note: $('#note_detail').val(),
                customer_note_id: noteId
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");

                    $('#modal-note').modal('hide');

                    $('.btn-search-note').trigger('click');
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(customer.jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    },

    //Show pop thêm tập tin
    popCreateFile: function (customerId) {
        $.ajax({
            url: laroute.route('admin.customer.pop-create-file'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: customerId
            },
            success: function (res) {
                $('#my-modal').html(res.html);

                $('#modal-file').modal('show');
            }
        });
    },

    uploadFile: function (input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            var file_data = $('#upload_tab_file').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_customer.');
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == 0) {
                        $('#customer_tab_file').append(`
                            <div class="col-lg-12">
                                <a target="_blank" href="${res.file}" value="${res.file}" type="${res.type}" name="customer_files[]" class="ss--text-black" download="${file_data.name}">${file_data.name}</a>
                                <a href="javascript:void(0)" onclick="detail.removeFile(this)"><i class="la la-trash"></i></a>
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

    //Thêm tập tin
    storeFile: function (customerId) {
        var arrayFile = [];

        var nFile = $('[name="customer_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                arrayFile.push({
                    'path': $('[name="customer_files[]"]')[i].href,
                    'file_name': $('[name="customer_files[]"]')[i].text,
                    'type': $('[name="customer_files[]"]')[i].type
                });
            }
        }

        $.ajax({
            url: laroute.route('admin.customer.store-file'),
            method: 'POST',
            dataType: "JSON",
            data: {
                note: $('#note_file').val(),
                customer_id: customerId,
                arrayFile: arrayFile
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");

                    $('#modal-file').modal('hide');

                    $('.btn-search-file').trigger('click');
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(customer.jsonLang['Thêm mới thất bại'], mess_error, "error");
            }
        });
    },

    //Show pop chỉnh sửa tập tin
    popEditFile: function (fileId) {
        $.ajax({
            url: laroute.route('admin.customer.pop-edit-file'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_file_id: fileId
            },
            success: function (res) {
                $('#my-modal').html(res.html);

                $('#modal-file').modal('show');
            }
        });
    },

    //Chỉnh sửa tập tin
    updateFile: function (fileId) {
        var arrayFile = [];

        var nFile = $('[name="customer_files[]"]').length;
        if (nFile > 0) {
            for (let i = 0; i < nFile; i++) {
                arrayFile.push({
                    'path': $('[name="customer_files[]"]')[i].href,
                    'file_name': $('[name="customer_files[]"]')[i].text,
                    'type': $('[name="customer_files[]"]')[i].type
                });
            }
        }

        $.ajax({
            url: laroute.route('admin.customer.update-file'),
            method: 'POST',
            dataType: "JSON",
            data: {
                note: $('#note_file').val(),
                customer_file_id: fileId,
                arrayFile: arrayFile
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");

                    $('#modal-file').modal('hide');

                    $('.btn-search-file').trigger('click');
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(customer.jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    }
};


$('#autotable').PioTable({
    baseUrl: laroute.route('admin.customer.list')
});


function onmouseoverAddNew() {
    $('.dropdow-add-new').show();
}


function onmouseoutAddNew() {
    $('.dropdow-add-new').hide();
}


$('.btn-add-phone2').click(function () {
    $('.phone2').show(350);
    $('.btn-add-phone2').hide(350);
});
$('.delete-phone').click(function () {
    $('.phone2').hide(350);
    $('#phone2').val('');
    $('.btn-add-phone2').show(350);
});
$('.m_selectpicker').selectpicker();

$('.add-new-info').click(function () {
    if ($('.hidden-add-info').val() == 0) {
        $('.add-info').show(350);
        $('.hidden-add-info').val(1);
    } else {
        $('.add-info').hide(350);
        $('.hidden-add-info').val(0);
    }

});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function isValidDate(year, month, day) {
    var d = new Date(year, month, day);
    if (d.getFullYear() == year && d.getMonth() == month && d.getDate() == day) {
        return true;
    }
    return false;
}

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#customer_avatar');
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);

        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_customer.');

        $.ajax({
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == 0) {
                    $('#customer_avatar').val(res.file);
                }
            }
        });
    }
}

var addressCustomer = {
    changeProvince: function () {
        $.ajax({
            url: laroute.route('admin.order.changeProvince'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                province_id: $('#province_id').val(),
            },
            success: function (res) {
                if (res.error == false) {
                    $('#district_id').html(res.view);
                    $('#ward_id').html(res.view1);
                    $('#district_id').select2();
                    $('#ward_id').select2();

                    // $('select:not(.normal)').each(function () {
                    //     $(this).select2({
                    //         dropdownParent: $(this).parent()
                    //     });
                    // });
                } else {
                    swal(res.message, "", "error");
                }
            }
        });
    },

    changeDistrict: function () {
        $.ajax({
            url: laroute.route('admin.order.changeDistrict'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                district_id: $('#district_id').val(),
            },
            success: function (res) {
                if (res.error == false) {
                    $('#ward_id').html(res.view);
                    $('#ward_id').select2();
                    // $('select:not(.normal)').each(function () {
                    //     $(this).select2({
                    //         dropdownParent: $(this).parent()
                    //     });
                    // });
                } else {
                    swal(res.message, "", "error");
                }
            }
        });
    },
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
