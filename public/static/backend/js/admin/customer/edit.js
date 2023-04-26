$(document).ready(function() {
    $.getJSON(laroute.route('translate'), function(json) {
        $('#province_id').select2({
            placeholder: json["Chọn tỉnh/thành"],
        }).on('select2:select', function(event) {
            $.ajax({
                url: laroute.route('admin.customer.load-district'),
                dataType: 'JSON',
                data: {
                    id_province: event.params.data.id
                },
                method: 'POST',
                success: function(res) {
                    $('.district').empty();
                    $.map(res.optionDistrict, function(a) {
                        $('.district').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                    });

                }
            });
        });

        $('#ward_id').select2({
            placeholder: json["Chọn phường/xã"],
        });

        $('#district_id').select2({
            placeholder: json["Chọn quận/huyện"],
            ajax: {
                url: laroute.route('admin.customer.load-district'),
                data: function(params) {
                    return {
                        id_province: $('#province_id').val(),
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'JSON',
                method: 'POST',
                processResults: function(res) {
                    res.page = res.page || 1;
                    return {
                        results: res.optionDistrict.map(function(item) {
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

        $('#customer_refer_id').select2({
            placeholder: json["Chọn người giới thiệu"],
            ajax: {
                url: laroute.route('admin.customer.search-customer-refer'),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                },
                processResults: function(response) {
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

        $('#customer_source_id').select2({
            placeholder: json["Chọn nguồn khách hàng"],
        });
        $('#day').select2({
            placeholder: json["Ngày"],
        });
        $('#month').select2({
            placeholder: json["Tháng"],
        });
        $('#year').select2({
            placeholder: json["Năm"],
        });
        $('#customer_type').select2();

        $('#customer_group_id').select2({
            placeholder: json["Hãy chọn nhóm khách hàng"],
        });

        $('.info_type').select2({
            placeholder: json['Hãy chọn loại thông tin'],
        });

        $('.btn-edit').click(function() {
            $('#form-edit').validate({
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
                    // province_id: {
                    //     required: true
                    // },
                    // district_id: {
                    //     required: true
                    // }
                },
                messages: {
                    customer_group_id: {
                        required: json["Hãy chọn nhóm khách hàng"]
                    },
                    full_name: {
                        required: json["Hãy nhập tên khách hàng"]
                    },
                    phone1: {
                        required: json["Hãy nhập số điện thoại"],
                        number: json["Số điện thoại không hợp lệ"],
                        minlength: json["Tối thiểu 10 số"],
                        maxlength: json["Tối đa 11 số"]
                    },
                    address: {
                        required: json["Hãy nhập địa chỉ"]
                    },
                    province_id: {
                        required: json["Hãy chọn tỉnh thành"]
                    },
                    district_id: {
                        required: json["Hãy chọn quận huyện"]
                    },
                    tax_code: {
                        minlength: json["Mã số thuế tối thiểu 11 ký tự"],
                        maxlength: json["Mã số thuế tối đa 13 ký tự"]
                    },
                    representative: {
                        maxlength: json["Người đại diện tối đa 191 ký tự"]
                    },
                    hotline: {
                        minlength: json["Hotline tối thiểu 10 ký tự"],
                        maxlength: json["Hotline tối đa 15 ký tự"]
                    },
                },
                submitHandler: function() {
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
                    var id = $('#customer_id').val();
                    var customer_avatar = $('#customer_avatar').val();
                    var customer_avatar_upload = $('#customer_avatar_upload').val();
                    var is_actived = 0;
                    if ($('#is_actived').is(':checked')) {
                        is_actived = 1;
                    }

                    var imageCustomer = [];
                    var fileCustomer = [];

                    // update 08/11/2021 type customer personal or business
                    var customer_type = $('#customer_type').val();
                    var tax_code = $('#tax_code').val();
                    var representative = $('#representative').val();
                    var hotline = $('#hotline').val();
                    if(customer_type == 'personal'){
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
                                $('.error_email').text(json["Email không hợp lệ'"]);
                                return false;
                            } else {
                                $('.error_email').text('');
                                $.ajax({
                                    url: laroute.route('admin.customer.submitEdit'),
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
                                        is_actived: is_actived,
                                        note: note,
                                        id: id,
                                        customer_avatar: customer_avatar,
                                        customer_avatar_upload: customer_avatar_upload,
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
                                    success: function(res) {
                                        if (res.error == false) {
                                            swal({
                                                title:  json["Chỉnh sửa khách hàng thành công"],
                                                text: 'Redirecting...',
                                                type: 'success',
                                                timer: 1500,
                                                showConfirmButton: false,
                                            })
                                            .then(() => {
                                                if($('#view_mode').val() == 'chathub_popup'){
                                                    customer.processFunctionEditCustomer(res);
                                                }else {
                                                 
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
                                url: laroute.route('admin.customer.submitEdit'),
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
                                    is_actived: is_actived,
                                    note: note,
                                    id: id,
                                    customer_avatar: customer_avatar,
                                    customer_avatar_upload: customer_avatar_upload,
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
                                success: function(res) {
                                    if (res.error == false) {
                                        swal({
                                            title:  json["Chỉnh sửa khách hàng thành công"],
                                            text: 'Redirecting...',
                                            type: 'success',
                                            timer: 1500,
                                            showConfirmButton: false,
                                        })
                                        .then(() => {
                                            if($('#view_mode').val() == 'chathub_popup'){
                                                customer.processFunctionEditCustomer(res);
                                            }else {
                                             
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
        $('.btn-add-phone2').click(function() {
            $('.phone2').show(350);
            $('.btn-add-phone2').hide(350);
        });
        $('.delete-phone').click(function() {
            $('.phone2').hide(350);
            $('.btn-add-phone2').show(350);
            $('#phone2').val('');
        });
    });
});


function onmouseoverAddNew() {
    $('.dropdow-add-new').show();
}


function onmouseoutAddNew() {
    $('.dropdow-add-new').hide();
}


$('.m_selectpicker').selectpicker();

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        // var imageAvatar = $('#customer_avatar_upload');
        reader.onload = function(e) {
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
            success: function(res) {
                if (res.error == 0) {
                    $('#customer_avatar_upload').val(res.file);
                }
            }
        });
    }
}

var stt = 0;

var customer = {
    processFunctionEditCustomer : function(data){

        window.close()
        window.postMessage({
            'func': 'editSuccessCustomer',
            'message' : data
        }, "*");
    },
    add_customer_group: function(close) {
        $('#type_add').val(close);
        $.getJSON(laroute.route('translate'), function(json) {

            var form = $('#form-customer-group');

            form.validate({
                rules: {
                    group_name: {
                        required: true,
                    }
                },
                messages: {
                    group_name: {
                        required: json["Hãy nhập tên nhóm khách hàng"],
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
                success: function(response) {
                    if (response.status == 1) {
                        if (response.close != 0) {
                            $("#add").modal("hide");
                        }
                        $('#form-customer-group')[0].reset();
                        swal(json["Thêm nhóm khách hàng thành công"], "", "success");
                        $('#customer_group_id > option').remove();
                        $.each(response.optionGroup, function(index, element) {
                            $('#customer_group_id').append('<option value="' + index + '">' + element + '</option>')
                        });
                        $('#autotable').PioTable('refresh');
                    } else {
                        $('.error-group-name').text(json["Nhóm khách hàng đã tồn tại"]);
                        $('.error-group-name').css('color', 'red');
                    }
                }
            });
        });
    },
    add_customer_refer: function(close) {
        $.getJSON(laroute.route('translate'), function(json) {
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
                        required: json["Hãy nhập tên người giới thiệu"]
                    },
                    phone1: {
                        required: json["Hãy nhập số điện thoại"],
                        number: json["Số điện thoại không hợp lệ"],
                        minlength: json["Số điện thoại tối thiểu 10 số"],
                        maxlength: json["Số điện thoại tối đa 11 số"]
                    },
                    address: {
                        required: json["Hãy nhập địa chỉ"]
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
                success: function(response) {
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
                        swal(json["Thêm người giới thiệu thành công"], "", "success");
                        $('#autotable').PioTable('refresh');
                    }
                }
            });

        });
    },
    dropzoneCustomer: function() {
        Dropzone.options.dropzoneCustomer = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 20,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
            // renameFile: function(file) {
            //     var dt = new Date();
            //     var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
            //     var random = "";
            //     var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            //     for (let z = 0; z < 10; z++) {
            //         random += possible.charAt(Math.floor(Math.random() * possible.length));
            //     }
            //     return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
            // },
            init: function() {
                this.on("sending", function(file, xhr, data) {
                    data.append("link", "_customer.");
                });

                this.on("success", function(file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

                    if (response.error == 0) {
                        $("#up-image-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='fileName' value='" + response.file + "' typeFile='" + response.type + "'>");
                    }
                });

                this.on('removedfile', function(file, response) {
                    var checkImage = $('#up-image-temp').find('input[name="fileName"]');

                    $.each(checkImage, function() {
                        if ($(this).attr('class') == file.upload.filename) {
                            $(this).remove();
                        }
                    });
                });
            }
        };
    },
    dropzoneFile: function() {
        Dropzone.options.dropzoneFile = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 20,
            acceptedFiles: ".pdf,.doc,.docx,.pdf,.csv,.xls,.xlsx",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
            // renameFile: function(file) {
            //     var dt = new Date();
            //     var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
            //     var random = "";
            //     var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            //     for (let z = 0; z < 10; z++) {
            //         random += possible.charAt(Math.floor(Math.random() * possible.length));
            //     }
            //     return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
            // },
            init: function() {
                this.on("sending", function(file, xhr, data) {
                    data.append("link", "_customer.");
                });

                this.on("success", function(file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

                    if (response.error == 0) {
                        $("#up-file-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='fileName' value='" + response.file + "' typeFile='" + response.type + "'>");
                    }
                });

                this.on('removedfile', function(file, response) {
                    var checkImage = $('#up-file-temp').find('input[name="fileName"]');

                    $.each(checkImage, function() {
                        if ($(this).attr('class') == file.upload.filename) {
                            $(this).remove();
                        }
                    });
                });
            }
        };
    },
    modalImage: function() {
        $('#up-image-temp').empty();
        $('#dropzoneCustomer')[0].dropzone.files.forEach(function(file) {
            file.previewElement.remove();
        });
        $('#dropzoneCustomer').removeClass('dz-started');

        $('#modal-image-customer').modal({
            backdrop: 'static',
            keyboard: false
        });
    },
    removeImage: function(e) {
        $(e).closest('.image-show-child').remove();
    },
    submitImageCustomer: function() {
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
    modalFile: function() {
        $('#up-file-temp').empty();
        $('#dropzoneFile')[0].dropzone.files.forEach(function(file) {
            file.previewElement.remove();
        });
        $('#dropzoneFile').removeClass('dz-started');

        $('#modal-file-customer').modal({
            backdrop: 'static',
            keyboard: false
        });
    },
    submitFileCustomer: function() {
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
    removeFile: function(obj) {
        $(obj).closest('.div_file').remove();
    },
    changeBoolean: function(obj) {
        if ($(obj).is(":checked")) {
            $(obj).val(1);
        } else {
            $(obj).val(0);
        }
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
    }
};

var addressCustomer = {
    changeProvince: function(){
        $.ajax({
            url: laroute.route('admin.order.changeProvince'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                province_id : $('#province_id').val(),
            },
            success: function (res) {
                if (res.error == false){
                    $('#district_id').html(res.view);
                    $('#ward_id').html(res.view1);
                    $('#district_id').select2();
                    $('#ward_id').select2();
                    $('select:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent()
                        });
                    });
                } else {
                    swal(res.message, "", "error");
                }
            }
        });
    },

    changeDistrict: function(){
        $.ajax({
            url: laroute.route('admin.order.changeDistrict'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                district_id : $('#district_id').val(),
            },
            success: function (res) {
                if (res.error == false){
                    $('#ward_id').html(res.view);
                    $('#ward_id').select2();
                    $('select:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent()
                        });
                    });
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
