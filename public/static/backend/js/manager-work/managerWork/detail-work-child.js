$(document).ready(function () {
    $('.selectForm').select2();
    $.getJSON(laroute.route('translate'), function (json) {
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
    });
    // Remind.search($('#manage_work_id').val());
});
var WorkChild = {
    approveStaff : function () {
        var value = $('#is_approve_id:checked').val();
        if (value == 1){
            $('.black_title_not_approve').hide();
            $('.black_title_approve').show();
            var selectStaff = $('#approve_id_select').val();
            var idStaff = $('#id_staff').val();
            $('#approve_id_select').prop('disabled',false);
            if(selectStaff == ''){
                $('#approve_id_select').val(idStaff).trigger('change');
            }

        }else {
            $('.black_title_approve').hide();
            $('.black_title_not_approve').show();
            $('#approve_id_select').prop('disabled',true);
        }
    },
    showPopup : function(manage_work_id){
        $.ajax({
            url: laroute.route('manager-work.detail.show-popup-work-child'),
            method: "POST",
            data: {
                parent_id : $('#manage_work_id').val(),
                manage_work_id : manage_work_id
            },
            success: function (res) {
                if (res.error == false){

                    $('#block_append').empty();
                    $('#block_append').append(res.view);
                    // $('.select2-active').select2({
                    //     dropdownParent: $(".modal")
                    // });
                    $('select:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent()
                        });
                    });

                    $('select[name="manage_tag[]"]').select2({
                        tags: true,
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

                    $(".date-input").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        pickerPosition: "bottom-left",
                        // format: "dd/mm/yyyy hh:ii",
                        format: "dd/mm/yyyy",
                        // startDate : new Date()
                        // locale: 'vi'
                    });

                    $(".date-timepicker").datetimepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        pickerPosition: "bottom-left",
                        format: "dd/mm/yyyy hh:ii",
                        // format: "dd/mm/yyyy",
                        startDate : new Date()
                        // locale: 'vi'
                    });

                    $(".date-timepicker-repeat").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        pickerPosition: "bottom-left",
                        format: "dd/mm/yyyy",
                        // startDate : new Date()
                        // locale: 'vi'
                    });

                    $("#repeat_time").timepicker({
                        minuteStep: 15,
                        defaultTime: "",
                        showMeridian: !1,
                        snapToStep: !0,
                    });

                    AutoNumeric.multiple('.input-mask,.input-mask-remind',{
                        currencySymbol : '',
                        decimalCharacter : '.',
                        digitGroupSeparator : ',',
                        decimalPlaces: 0,
                        minimumValue: 0,
                    });

                    AutoNumeric.multiple('#repeat_end_time',{
                        currencySymbol : '',
                        decimalCharacter : '.',
                        digitGroupSeparator : ',',
                        decimalPlaces: 0,
                        minimumValue: 0,
                    });

                    AutoNumeric.multiple('.progress_input',{
                        currencySymbol : '',
                        decimalCharacter : '.',
                        digitGroupSeparator : ',',
                        decimalPlaces: 0,
                        minimumValue: 0,
                        maximumValue: 100,
                    });

                    $('.summernote').summernote({
                        placeholder: '',
                        tabsize: 2,
                        height: 200,
                        toolbar: [
                            // ['style', ['style']],
                            ['font', ['bold', 'underline', 'italic']],
                            // ['fontname', ['fontname', 'fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture']],
                        ],
                        callbacks: {
                            onImageUpload: function(files) {
                                for(let i=0; i < files.length; i++) {
                                    uploadImgCkList(files[i]);
                                }
                            }
                        },
                    });

                    var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

                    $('#parent_id').select2({
                        dropdownParent: $('#parent_id').parent(),
                        width: '100%',
                        placeholder: jsonLang['Chọn công việc cha'],
                        // placeholder: 'Chọn tác vụ cha',
                        ajax: {
                            url: laroute.route('manager-work.getListParentTask'),
                            data: function (params) {
                                return {
                                    search: params.manage_work_title,
                                    manage_project_id: $('#popup-work #popup_manage_project_id').val(),
                                    manage_type_work_id: $('#popup-work #popup_manage_type_work_id').val(),
                                    page: params.page || 1,
                                };
                            },
                            dataType: 'json',
                            method: 'POST',
                            processResults: function (data) {
                                data.page = data.page || 1;
                                return {
                                    results: data.data.map(function (item) {
                                        return {
                                            id: item.manage_work_id,
                                            text: item.manage_work_title,
                                        };
                                    }),
                                    pagination: {
                                        more: data.current_page + 1
                                    }
                                };
                            },
                        }
                    });


                    WorkAll.changeCustomer(manage_work_id);
                    $('#block_append #popup-work').modal({
                        backdrop: 'static'
                    });
                    $('#block_append #popup-work').modal('show');
                    DocumentWork.addImage();
                    // $('#popup-work').on('hidden.bs.modal', function () {
                    //     location.reload();
                    // })
                } else {
                    swal.fire(res.message, '', 'error');
                }
            }
        });
    },
    closePopup: function () {
        $('.note-children-container').remove();
    },
    cancelWork : function () {
        $('#block_append #popup-work').modal('hide');
        $('#append-add-work #popup-work').modal('hide');
        if(typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup'){
            WorkChild.processFunctionCancelWork({});
        }

        $('.note-children-container').remove();
    },
    saveWork : function (createNew = 0) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-file');
            form.validate({
                rules: {
                    manage_work_title: {
                        required: true,
                        maxlength : 255
                    },
                    manage_type_work_id:{
                        required: true,
                    },
                    // date_start : {
                    //     required: true,
                    // },
                    date_end : {
                        required: true,
                    },
                    processor_id : {
                        required: true,
                    },
                    priority : {
                        required: true,
                    },
                    approve_id:{
                        required : function () {
                            if ($('#is_approve_id').is(':checked')){
                                return true;
                            }
                            return false;
                        }
                    }

                },
                messages: {
                    manage_work_title: {
                        required: json["Vui lòng nhập tiêu đề"],
                        maxlength : json["Tiêu đề vượt quá 255 ký tự"]
                    },
                    manage_type_work_id:{
                        required: json["Vui lòng chọn loại công việc"],
                    },
                    // date_start : {
                    //     required: "Vui lòng chọn ngày bắt đầu",
                    // },
                    date_end : {
                        required: json["Vui lòng chọn ngày kết thúc"],
                    },
                    processor_id : {
                        required: json["Vui lòng chọn nhân viên thực hiện"],
                    },
                    priority : {
                        required: json["Vui lòng chọn mức độ ưu tiên"],
                    },
                    approve_id : {
                        required: json["Vui lòng chọn nhân viên duyệt"],
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }

            if ($('#popup_manage_project_id').val() === undefined || $('#popup_manage_project_id').val() === ''){
                WorkChild.saveWorkFinish(createNew);
            } else {
                $.ajax({
                    url: laroute.route('manager-work.check-date-work-project'),
                    data: form.serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.error == true){
                            swal({
                                title: ManagerWork.jsonLang['Thông báo'],
                                text: res.title,
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: ManagerWork.jsonLang['Lưu'],
                                cancelButtonText: ManagerWork.jsonLang['Hủy']

                            }).then(function(result) {
                                if (result.value) {
                                    WorkChild.saveWorkFinish(createNew);
                                }
                            });
                        } else {
                            WorkChild.saveWorkFinish(createNew);
                        }
                    },
                });
            }

        });
    },

    saveWorkFinish : function (createNew){
        $.ajax({
            url: laroute.route('manager-work.detail.save-child-work'),
            data: $('#form-file').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    var parent_progress = res.parent_progress;
                    swal(res.message,'','success').then(function () {
                        $('.progress-bar-main').css('width',parent_progress+'%');
                        $('.progress-bar-main-text').text(parent_progress+'%');
                        if (createNew == 1){
                            $('#popup-work').modal('hide');
                            WorkChild.showPopup();
                            // $('#popup-work').on('hiden.bs.modal', function () {
                            //     location.reload();
                            // });
                            // } else {
                            //     location.reload();
                        } else {
                            $('#popup-work').modal('hide');
                        }
                    });
                    WorkChild.search($('#manage_work_id').val());
                } else {
                    swal(res.message,'','error');
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

    changeRepeat : function() {
        var value = $('.repeat_type:checked').val();
        $('.block_weekly').hide();
        $('.block_monthly').hide();
        $('.block_monthly input').val('');
        $('.block_weekly input').val('');
        $('.weekly-select').removeClass('weekly-select-active');
        $('.monthly-select').removeClass('weekly-select-active');
        // $('.weekly-select:first-child').addClass('weekly-select-active');
        // $('#manage_repeat_time_weekly').val(0);
        // $('#manage_repeat_time_monthly').val('').trigger('change');

        $('.block_'+value).show();
    },

    selectWeekly: function (day) {

        if ($('label.weekly-select-'+day).hasClass('weekly-select-active')){
            $('label.weekly-select-'+day).removeClass('weekly-select-active');
            $('input.weekly-select-'+day).val('');
        } else {
            $('label.weekly-select-'+day).addClass('weekly-select-active');
            $('input.weekly-select-'+day).val(day);
        }
    },

    selectMonthly: function (day) {

        if ($('label.monthly-select-'+day).hasClass('weekly-select-active')){
            $('label.monthly-select-'+day).removeClass('weekly-select-active');
            $('input.monthly-select-'+day).val('');
        } else {
            $('label.monthly-select-'+day).addClass('weekly-select-active');
            $('input.monthly-select-'+day).val(day);
        }
    },

    changeRepeatEnd : function () {
        $('.disabled_block').prop('disabled',true);
        var value = $('.repeat_end:checked').val();

        if (value == 'after'){
            $('.repeat_end_type').prop('disabled',false);
            $('.repeat_end_time').prop('disabled',false);
        } else if(value == 'date'){
            $('.repeat_end_full_time').prop('disabled',false);
        }

    },

    approveStaff : function () {
        var value = $('#is_approve_id:checked').val();
        if (value == 1){
            $('.black_title_not_approve').hide();
            $('.black_title_approve').show();
            var selectStaff = $('#approve_id_select').val();
            var idStaff = $('#id_staff').val();
            $('#approve_id_select').prop('disabled',false);
            if(selectStaff == ''){
                $('#approve_id_select').val(idStaff).trigger('change');
            }

        }else {
            $('.black_title_approve').hide();
            $('.black_title_not_approve').show();
            $('#approve_id_select').prop('disabled',true);
        }
    },
    removeRemind : function (manage_work_id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Xoá công việc'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],

            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('manager-work.detail.remove-work'),
                        method: "POST",
                        data: {
                            manage_work_id: manage_work_id
                        },
                        success: function (res) {
                            if (res.error == false){
                                swal.fire(res.message, '', 'success').then(function () {
                                    location.reload();
                                });
                            } else {
                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
        });
    },

    searchPage : function (page){
        WorkChild.search($('#manage_work_id').val(),page);
    },

    search: function (manage_work_id,page = 1) {
        $.ajax({
            url: laroute.route('manager-work.detail.search-work'),
            data: $('#form-search').serialize()+'&manage_project='+$('#manage_project').val()+'&manage_work_id='+manage_work_id+'&page='+page,
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.append-list-remind').empty();
                    $('.append-list-remind').append(res.view);
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
    removeSearchWork:function () {
        $('#manage_status_id_search').val('').trigger('change');
        $('.selectFormSearch').val('').trigger('change');
        $('.searchDateForm').val('');
        $.getJSON(laroute.route('translate'), function (json) {
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
        });
        WorkChild.search($('#manage_work_id').val(),1);
    }
}

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

var WorkAll = {
    changeCustomer : function(manage_work_id = null){
        var typeCustomer = $('#manage_work_customer_type').val();
        if (typeCustomer == 'deal'){
            $('.text-customer-select').hide();
            $('.text-deal-select').show();
        } else {
            $('.text-customer-select').show();
            $('.text-deal-select').hide();
        }
        $.ajax({
            url: laroute.route('manager-work.detail.change-customer'),
            data: {
                typeCustomer : typeCustomer,
                manage_work_id : manage_work_id
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {

                if (res.error == false) {
                    $('#customer_id').empty();
                    $('#customer_id').append(res.view);
                    $('#customer_id').select2();
                    $('#customer_id:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent()
                        });
                    });

                    WorkAll.changeObjectCustomer($('#customer_id'));
                } else {
                    swal(res.message,'','error');
                }
            },
        });
    },

    changeObjectCustomer: function (obj = null) {
        var typeCustomer = $('#manage_work_customer_type').val();

        $('.div_detail_customer').empty();

        if (typeCustomer == 'customer' && $(obj).val() != '') {
            let tpl = $('#detail-customer-tpl').html();
            tpl = tpl.replace(/{url}/g, laroute.route('admin.customer.detail', {id: $(obj).val()}));
            $('.div_detail_customer').append(tpl);
        }
    }
};
