var timeoffdays = {
    jsontranslate : JSON.parse(localStorage.getItem('tranlate')),
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('timeoffdays.list')
        });

        $(".select2").select2();

        $('#time_off_days_shift').select2().on('select2:select', function (event) {
            if (event.params.data.id == 'all') {
                $('#time_off_days_shift').val('all').trigger('change');
            } else {
                var arrayChoose = [];

                $.map($('#time_off_days_shift').val(), function (val) {
                    if (val != 'all') {
                        arrayChoose.push(val);
                    }
                });
                $('#time_off_days_shift').val(arrayChoose).trigger('change');
            }
        }).on('select2:unselect', function (event) {
            if ($('#time_off_days_shift').val() == '') {
                $('#time_off_days_shift').val('all').trigger('change');
            }
        });

        $('.btn-add').click(function () {
            $("#form-add").submit();

        });

        
        var arrRange = {};
        arrRange[timeoffdays.jsontranslate["Hôm nay"]] = [moment(), moment()];
        arrRange[timeoffdays.jsontranslate["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[timeoffdays.jsontranslate["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[timeoffdays.jsontranslate["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[timeoffdays.jsontranslate["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[timeoffdays.jsontranslate["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $("#created_at").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "customRangeLabel": timeoffdays.jsontranslate['Tùy chọn ngày'],
                daysOfWeek: [
                    timeoffdays.jsontranslate["CN"],
                    timeoffdays.jsontranslate["T2"],
                    timeoffdays.jsontranslate["T3"],
                    timeoffdays.jsontranslate["T4"],
                    timeoffdays.jsontranslate["T5"],
                    timeoffdays.jsontranslate["T6"],
                    timeoffdays.jsontranslate["T7"]
                ],
                "monthNames": [
                    timeoffdays.jsontranslate["Tháng 1 năm"],
                    timeoffdays.jsontranslate["Tháng 2 năm"],
                    timeoffdays.jsontranslate["Tháng 3 năm"],
                    timeoffdays.jsontranslate["Tháng 4 năm"],
                    timeoffdays.jsontranslate["Tháng 5 năm"],
                    timeoffdays.jsontranslate["Tháng 6 năm"],
                    timeoffdays.jsontranslate["Tháng 7 năm"],
                    timeoffdays.jsontranslate["Tháng 8 năm"],
                    timeoffdays.jsontranslate["Tháng 9 năm"],
                    timeoffdays.jsontranslate["Tháng 10 năm"],
                    timeoffdays.jsontranslate["Tháng 11 năm"],
                    timeoffdays.jsontranslate["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        });
    
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

    create: function (staffId) {
        $.ajax({
            url: laroute.route('timeoffdays.store'),
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

    edit: function (id) {
        $.ajax({
            url: laroute.route('timeoffdays.edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-edit').modal('show');
           
            }
        });
    },

    remove: function (id) {
        
        swal({
            title: timeoffdays.jsontranslate['Thông báo'],
            text: timeoffdays.jsontranslate["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: timeoffdays.jsontranslate['Xóa'],
            cancelButtonText: timeoffdays.jsontranslate['Hủy'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('timeoffdays.delete'),
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
   
    },

    updateStatus: function (id, status) {
        
        swal({
            title: timeoffdays.jsontranslate['Thông báo'],
            text: timeoffdays.jsontranslate["Bạn có muốn thay đổi trạng thái không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: timeoffdays.jsontranslate['Có'],
            cancelButtonText: timeoffdays.jsontranslate['Không'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('timeoffdays.update-status'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        customer_info_type_id: id,
                        status: status
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                        } else {
                            swal.fire(res.message, '', "error");
                        }
                        $('#autotable').PioTable('refresh');
                    }
                });
            } else {
                $('#autotable').PioTable('refresh');
            }
        });
    
    },

    total: function (id) {
        $.ajax({
            url: laroute.route('timeoffdays.total'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_off_type_id : id
            },
            success: function (res) {
                var total = '';
                res.total.forEach( function ( e, i ) {
                    var cl ='badge-primary';
                    if(i % 2 == 1){
                        cl ='badge-success';
                    }
                    total += "<li>"+e.key+": <span class='badge badge-pill "+cl+"'>"+e.value+"</span></li>";
                });
                $("#motngay").show();
                $("#nhieungay").show();
                $("#buoisang").show();
                $("#buoichieu").show();
                if(res.detail.time_off_type_code == '017' || res.detail.time_off_type_code == '018'){
                    $("#date_time").show();
                    $("#nhieungay").hide();
                    $("#buoisang").hide();
                    $("#buoichieu").hide();
                }
                // else if(res.detail.time_off_type_code == '002'){
                //     $("#buoisang").hide();
                //     $("#buoichieu").hide();
                //     $("#time_off_days_time").select2("val", "0");
                // }
                else{
                    $("#date_time").hide();
                    $(".off-day").show();
                    // $("#time_off_days_time").select2("val", "0");
                }
                $("#total_day").html(total);
                $("#note_day").html(res.detail.time_off_type_description);
                $(".day-off-info").show();
                $('#time_off_type_code').val(res.detail.time_off_type_code);
            }
        });
    },

    getListStaffApprove: function (id) {
        $.ajax({
            url: laroute.route('timeoffdays.get-staff-approve'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_off_type_id : id
            },
            success: function (res) {
                $("#lstStaffArpprove").html(res.html);
            }
        });
    },

    listShift: function (start_date, end_date) {
        $.ajax({
            url: laroute.route('timeoffdays.sfshifts.list'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                working_day_start : start_date,
                working_day_end : end_date,
                time_off_days_id : $('#time_off_days_id').val()
            },
            success: function (res) {
                
                var result = '';
                res.forEach( function ( e, i ) {
                    if(e.selected){
                        result += "<option selected value="+e.time_working_staff_id+">"+e.shift_name+"</option>";
                    }else {
                        result += "<option value="+e.time_working_staff_id+">"+e.shift_name+"</option>";
                    }
                    
                });

                $("#time_off_days_shift").html(result);
                $("#time_off_days_shift").trigger('change');
                
            }
        });
    },
    
    approve: function (id) {
        
        swal({
            title: timeoffdays.jsontranslate['Thông báo'],
            text: timeoffdays.jsontranslate["Bạn có muốn duyệt đơn phép không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: timeoffdays.jsontranslate['Có'],
            cancelButtonText: timeoffdays.jsontranslate['Không'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('timeoffdays.approve'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        time_off_days_id: id
                    },
                    success: function (res) {
                        if(!res.error){
                            swal({
                                title:  res.message,
                                text: '...',
                                type: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                            })
                            .then(() => {
                                $('#autotable').PioTable('refresh');
                            });
                        }else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                });
            } else {
                $('#autotable').PioTable('refresh');
            }
        });
    
    },

    unApprove: function (id) {
        
        swal({
            title: timeoffdays.jsontranslate['Thông báo'],
            text: timeoffdays.jsontranslate["Bạn có từ chối đơn phép không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: timeoffdays.jsontranslate['Có'],
            cancelButtonText: timeoffdays.jsontranslate['Không'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('timeoffdays.un-approve'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        time_off_days_id: id
                    },
                    success: function (res) {
                        if(!res.error){
                            swal({
                                title:  res.message,
                                text: '...',
                                type: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                            })
                            .then(() => {
                                $('#autotable').PioTable('refresh');
                            });
                        }else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                });
            } else {
                $('#autotable').PioTable('refresh');
            }
        });
    
    },
}

var create = {
    save: function () {
        var form = $('#form-create');
            form.validate({
                rules: {
                    customer_info_type_name_vi: { required: true },
                    customer_info_type_name_en: {required: true }
                },
                messages: {
                    customer_info_type_name_vi: { required: timeoffdays.jsontranslate['Hãy nhập tên loại thông tin kèm theo tiếng Việt'] },
                    customer_info_type_name_en: { required: timeoffdays.jsontranslate['Hãy nhập tên loại thông tin kèm theo tiếng Anh'] }
                },
            });

            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('timeoffdays.store'),
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
                    swal(timeoffdays.jsontranslate['Thêm mới thất bại'], mess_error, "error");
                }
            });
    }
};

var edit = {
    save: function (id) {
        
        var form = $('#form-create');
        form.validate({
            rules: {
                customer_info_type_name_vi: { required: true },
                customer_info_type_name_en: { required: true }
            },
            messages: {
                customer_info_type_name_vi: { required: timeoffdays.jsontranslate['Hãy nhập tên loại thông tin kèm theo tiếng Việt'] },
                customer_info_type_name_en: { required: timeoffdays.jsontranslate['Hãy nhập tên loại thông tin kèm theo tiếng Anh'] }
            },
        });

        if (!form.valid()) {
            return false;
        }
        $.ajax({
            url: laroute.route('timeoffdays.update'),
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
                swal(timeoffdays.jsontranslate['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    }
}

$('.btn-add').click(function () { 
    var form = $('#form-create');
    form.validate({
        rules: {
            time_off_type_id: { required: true },
            time_off_note :  { required: true }
        },
        messages: {
            // time_off_type_id: { required: timeoffdays.jsontranslate['Chưa chọn loại đơn'] }
            time_off_type_id: timeoffdays.jsontranslate['Chưa chọn loại đơn'],
            time_off_note: timeoffdays.jsontranslate['Chưa điền ghi ghú']
        },
    });
    if (!form.valid()) {
        return false;
    }
    var arrayFile = [];
    $.each($('.div_image_customer').find('.image-show-child'), function () {
        arrayFile.push({
            'path': $(this).find("input[name='img-link-customer']").val(),
            'file_name': $(this).find("input[name='img-name-customer']").val(),
            'type': $(this).find("input[name='img-type-customer']").val()
        });
    });
    var arrStaff = [];
    if($('#staff_id_level1') != null){
        arrStaff.push($('#staff_id_level1').val());
    }
    if($('#staff_id_level2') != null){
        arrStaff.push($('#staff_id_level2').val());
    }
    if($('#staff_id_level3') != null){
        arrStaff.push($('#staff_id_level3').val());
    }
    $.ajax({
        url: laroute.route('timeoffdays.store'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            time_off_type_id : $('#time_off_type_id').val(),
            time_off_days_end: $('#end_date').val(),
            time_off_days_start: $('#start_date').val(),
            time_off_days_time: $('#time_off_days_time').val(),
            time_off_note: $('#note').val(),
            time_off_days_shift : $('#time_off_days_shift').val(),
            time_off_days_staff_approve : arrStaff,
            time_off_days_files : arrayFile,
            select_type_date : $('#select_type_date').val()
            
        },
        success: function (res) {
            if(!res.error){
                swal({
                    title:  res.message,
                    text: '...',
                    type: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                })
                .then(() => {
                    window.location.href = laroute.route('timeoffdays.mylist');
                });
            }else {
                swal(timeoffdays.jsontranslate['Thông báo'], res.message, "error");
            }
        }
    });
});

$('.btn-edit').click(function () { 
    var form = $('#form-edit');
    form.validate({
        rules: {
            time_off_type_id: { required: true },
            time_off_note :  { required: true }
        },
        messages: {
            time_off_type_id: timeoffdays.jsontranslate['Chưa chọn loại đơn'],
            time_off_note: timeoffdays.jsontranslate['Chưa điền ghi ghú']
        },
    });
    if (!form.valid()) {
        return false;
    }
    var arrayFile = [];
    $.each($('.div_image_customer').find('.image-show-child'), function () {
        arrayFile.push({
            'path': $(this).find("input[name='img-link-customer']").val(),
            'file_name': $(this).find("input[name='img-name-customer']").val(),
            'type': $(this).find("input[name='img-type-customer']").val()
        });
    });
    var arrStaff = [];
    if($('#staff_id_level1') != null){
        arrStaff.push($('#staff_id_level1').val());
    }
    if($('#staff_id_level2') != null){
        arrStaff.push($('#staff_id_level2').val());
    }
    if($('#staff_id_level3') != null){
        arrStaff.push($('#staff_id_level3').val());
    }
    this.scroll
    $.ajax({
        url: laroute.route('timeoffdays.update'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            time_off_days_id: $('#time_off_days_id').val(),
            time_off_type_id : $('#time_off_type_id').val(),
            time_off_days_end: $('#end_date').val(),
            time_off_days_start: $('#start_date').val(),
            time_off_days_time: $('#time_off_days_time').val(),
            time_off_note: $('#note').val(),
            time_off_days_shift : $('#time_off_days_shift').val(),
            time_off_days_staff_approve : arrStaff,
            time_off_days_files : arrayFile,
            select_type_date : $('#select_type_date').val()
        },
        success: function (res) {
            if(!res.error){
                swal({
                    title:  res.message,
                    text: '...',
                    type: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                })
                .then(() => {
                    window.location.href = laroute.route('timeoffdays.mylist');
                });
            }else {
                swal(timeoffdays.jsontranslate['Thông báo'], res.message, "error");
            }
        }
    });
});