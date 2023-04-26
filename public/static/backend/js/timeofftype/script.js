var timeofftype = {
    jsontranslate: JSON.parse(localStorage.getItem('tranlate')),
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('timeofftype.list')
        });

        $(".select2").select2();


        $('.btn-add').click(function () {
            $("#form-add").submit();

        });

        $.getJSON(laroute.route('translate'), function (json) {
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
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
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
            });
        });
    },

    modalImage: function () {
        $('#up-image-temp').empty();
        $('#dropzoneCustomer')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('#dropzoneCustomer').removeClass('dz-started');

        $('#modal-image-customer').modal({
            backdrop: 'static',
            keyboard: false
        });
    },

    removeImage: function (e) {
        $(e).closest('.image-show-child').remove();
    },

    submitImageCustomer: function () {
        var checkImage = $('#up-image-temp').find('input[name="fileName"]');

        $.each(checkImage, function () {
            let tpl = $('#tpl-image').html();
            tpl = tpl.replace(/{imageName}/g, $(this).val());
            $('.div_image_customer').append(tpl);
            $('.delete-img-sv').css('display', 'block');
        });

        $('#modal-image-customer').modal('hide');
    },

    create: function (staffId) {
        $.ajax({
            url: laroute.route('timeofftype.store'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_available: staffId
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-create').modal('show');
                $('#staff_id').select2({
                    placeholder: 'Chọn nhân viên'
                });
                new AutoNumeric.multiple('#staff_money, #staff_money', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 0,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });
    },

    edit: function (code) {

        $.ajax({
            url: laroute.route('timeofftype.edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                code: code
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-edit').modal('show');

            }
        });
    },

    update: function (id) {
      
        var data = $('#form-edit').serialize();
        var dataJson = JSON.parse(JSON.stringify(jQuery('#form-edit').serializeArray()));
        var isValid = true;
        dataJson.forEach(element => {
          
            if(element.value == '' || parseInt(element.value) == 0){
                $('#error_' + element.name).css('display', 'block');
                isValid = false;
            }else {
                $('#error_' + element.name).css('display', 'none');
            }
        });
        
        if(!isValid){
            return;
        }
        var data = {
            time_off_type_code : $('#time_off_type_code').val(),
            approve_level_2 : $('#approve_level_2').val(),
            approve_level_3 : $('#approve_level_3').val()
        };
        if($('#ckb_approve_level_1').is(':checked')){
            data['approve_level_1'] =  1;
        }else {
            data['approve_level_1'] = 0;
        }
        $.ajax({
            url: laroute.route('timeofftype.update'),
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (res) {
                if (res.status == 1) {
                    swal({
                        title:  data.message,
                        text: 'Redirecting...',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                        .then(() => {
                            window.location.href = laroute.route('timeofftype.index');
                        });
                    // $('#modal-edit').modal('hide');

                } else {
                    Swal.fire(
                        'Thông Báo',
                        'Cập nhật thất bại',
                        'error'
                    )
                }
            }
        });
    },

    remove: function (id) {
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
                        url: laroute.route('timeofftype.delete'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            customer_info_type_id: id
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
        });
    },

    checkConfig : function(e, id){
        if(id == 'approve_level_2'){
            if(e.checked){
                $('#department_approve2').prop('disabled', false);
                $('#' + id).prop('disabled', false);
            }else {
                $('#department_approve2').prop('disabled', true);
                $('#department_approve2').val('');
                $('#approve_level_2').empty();
                $('#' + id).prop('disabled', true);
                $('#' + id).val('');
            }
        }
        if(id == 'approve_level_3'){
            if(e.checked){
                $('#department_approve3').prop('disabled', false);
                $('#' + id).prop('disabled', false);
            }else {
                $('#department_approve3').prop('disabled', true);
                $('#department_approve3').val('');
                $('#approve_level_3').empty();
                $('#' + id).prop('disabled', true);
                $('#' + id).val('');
            }
        }
        $('#approve_level_2').select2({
            width: "100%",
            placeholder: timeofftype.jsontranslate['Chọn người duyệt'],
           
        });
        $('#approve_level_3').select2({
            width: "100%",
            placeholder: timeofftype.jsontranslate['Chọn người duyệt']
        });
        $('#department_approve2').select2({
            placeholder: timeofftype.jsontranslate['Chọn phòng ban'],
            width: "100%"
        });
        $('#department_approve3').select2({
            placeholder: timeofftype.jsontranslate['Chọn phòng ban'],
            width: "100%"
        });
    },

    checkConfigDefault : function(e, id){
        if(e.checked){
            $('#' + id).val('true');
        }else {
            $('#' + id).val('false');
        }
    },

    getListStaff : function(e, level){
        $.ajax({
            url: laroute.route('timeofftype.get-list-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                department_id  : $(e).val()
            },
            success: function (res) {
                if(level == 2){
                    $('#approve_level_2').empty();
                    $("#approve_level_2").append(
                        "<option value=''" + timeofftype.jsontranslate['Chọn người duyệt'] + "</option>"
                      );
                    $.map(res, function (a) {
                        $('#approve_level_2').append('<option value="' + a.staff_id + '">' + a.full_name + '</option>');
                    });
                    $('#approve_level_2').select2({
                        placeholder: timeofftype.jsontranslate['Chọn người duyệt']
                    });
                }else {
                    $('#approve_level_3').empty();
                    $("#approve_level_3").append(
                        "<option value=''" + timeofftype.jsontranslate['Chọn người duyệt'] + "</option>"
                      );
                    $.map(res, function (a) {
                        $('#approve_level_3').append('<option value="' + a.staff_id + '">' + a.full_name + '</option>');
                    });
                    $('#approve_level_3').select2({
                        placeholder: timeofftype.jsontranslate['Chọn người duyệt']
                    });
                }
                
            }
        });
    }
}

var create = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');
            form.validate({
                rules: {
                    customer_info_type_name_vi: {required: true},
                    customer_info_type_name_en: {required: true}
                },
                messages: {
                    customer_info_type_name_vi: {required: json['Hãy nhập tên loại thông tin kèm theo tiếng Việt']},
                    customer_info_type_name_en: {required: json['Hãy nhập tên loại thông tin kèm theo tiếng Anh']}
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('timeofftype.store'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_info_type_name_vi: $('#customer_info_type_name_vi').val(),
                    customer_info_type_name_en: $('#customer_info_type_name_en').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#autotable').PioTable('refresh');
                            }
                            if (result.value == true) {
                                $('#autotable').PioTable('refresh');
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                    $('#modal-create').modal('hide');
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
    }
};

var edit = {
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-create');

            form.validate({
                rules: {
                    customer_info_type_name_vi: {required: true},
                    customer_info_type_name_en: {required: true}
                },
                messages: {
                    customer_info_type_name_vi: {required: json['Hãy nhập tên loại thông tin kèm theo tiếng Việt']},
                    customer_info_type_name_en: {required: json['Hãy nhập tên loại thông tin kèm theo tiếng Anh']}
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('timeofftype.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_info_type_id: id,
                    customer_info_type_name_vi: $('#customer_info_type_name_vi').val(),
                    customer_info_type_name_en: $('#customer_info_type_name_en').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                $('#autotable').PioTable('refresh');
                            }
                            if (result.value == true) {
                                $('#autotable').PioTable('refresh');
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                    $('#modal-edit').modal('hide');
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                }
            });

        });
    }
}
