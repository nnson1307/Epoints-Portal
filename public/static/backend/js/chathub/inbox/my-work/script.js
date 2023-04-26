
var MyWork = {

    getListAssign : function () {
        $.ajax({
            url: laroute.route('manager-work.report.get-list-my-work-assign'),
            data: $('.frmFilter_list_my_work_assign').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.listMyWorkAssign').empty();
                    $('.listMyWorkAssign').append(res.view);
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

    workApprove: function (manage_work_id,type) {
        $.ajax({
            url: laroute.route('manager-work.report.work-approve'),
            data: {
                manage_work_id: manage_work_id,
                type : type
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal('',res.message,'success').then(function () {
                        location.reload();
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

    searchRemind:function () {
        $.ajax({
            url: laroute.route('manager-work.report.search-remind'),
            data: $('.frmFilter_list_remind').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.listRemind').empty();
                    $('.listRemind').append(res.view);
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
    changeSort: function () {
        var sort = $('#sort_date_remind').val();
        if (sort == 'DESC'){
            $('#sort_date_remind').val('ASC');
            $('.frmFilter_list_remind i').removeClass('fa-sort-numeric-up');
            $('.frmFilter_list_remind i').addClass('fa-sort-numeric-down');
        } else {
            $('#sort_date_remind').val('DESC');
            $('.frmFilter_list_remind i').removeClass('fa-sort-numeric-down');
            $('.frmFilter_list_remind i').addClass('fa-sort-numeric-up');
        }
        MyWork.searchRemind();
    },

    removeRemind: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Xoá nhắc nhở'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],

            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('manager-work.report.remove-remind'),
                        data: $('.frmFilter_list_remind').serialize(),
                        method: "POST",
                        dataType: "JSON",
                        success: function(res) {
                            if (res.error == false) {
                                swal('',res.message,'success').then(function () {
                                    location.reload();
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

                }
            });
        });
    },
    showPopupRemind : function () {
        $.ajax({
            url: laroute.route('manager-work.report.show-popup-remind-popup'),
            method: "POST",
            data: {},
            success: function (res) {
                if (res.error == false){
                    $('#append-popup').empty();
                    $('#append-popup').append(res.view);
                    // $(".selectForm").select2({
                    //     // dropdownParent: $("#popup-remind-work")
                    //     dropdownParent: $(".modal")
                    // });
                    $('select:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent()
                        });
                    });
                    $(".date-timepicker").datetimepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        pickerPosition: "bottom-left",
                        format: "dd/mm/yyyy hh:ii",
                        startDate : new Date()
                        // locale: 'vi'
                    });

                    AutoNumeric.multiple('.input-mask',{
                        currencySymbol : '',
                        decimalCharacter : '.',
                        digitGroupSeparator : ',',
                        decimalPlaces: 0,
                        minimumValue: 0
                    });
                    $('#popup-remind-work').modal('show');
                    // $('#popup-remind-work').on('hidden.bs.modal', function (e) {
                    //     location.reload();
                    // });
                } else {
                    swal.fire(res.message, '', 'error');
                }
            }
        });
    },

    addCloseRemind : function (check) {
        $.ajax({
            url: laroute.route('manager-work.report.add-remind-work'),
            data: $('#form-remind-staff-work').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    MyWork.searchRemind();
                    swal(res.message,'','success').then(function () {
                        // location.reload();
                        $('#popup-remind-work').modal('hide');
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

    sortMyWork:function (classKey,key,value) {
        console.log(value);
        $('.'+classKey).val('');
        if (value == '' || value == 'DESC'){
            $('#'+key).val('ASC');
        } else {
            $('#'+key).val('DESC');
        }
        MyWork.getList();
    },
    sortMyWorkAssign:function (classKey,key,value) {
        $('.'+classKey).val('');
        if (value == '' || value == 'DESC'){
            $('#'+key).val('ASC');
        } else {
            $('#'+key).val('DESC');
        }
        MyWork.getListAssign();
    },

    showPopup : function(){
        $.ajax({
            url: laroute.route('manager-work.detail.show-popup-work-child'),
            method: "POST",
            data: {},
            success: function (res) {
                if (res.error == false){
                    $('#append-add-work').empty();
                    $('#append-add-work').append(res.view);
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
                        startDate : new Date()
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
                        width: '100%',
                        placeholder: jsonLang['Chọn công việc cha'],
                        ajax: {
                            url: laroute.route('manager-work.getListParentTask'),
                            data: function (params) {
                                return {
                                    search: params.manage_work_title,
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

                    WorkAll.changeCustomer();
                    $('#append-add-work #popup-work').modal({
                        backdrop: 'static'
                    });
                    $('#append-add-work #popup-work').modal('show');
                    DocumentWork.addImage();
                    // $('#popup-work').on('hidden.bs.modal', function (e) {
                    //     location.reload();
                    // })
                } else {
                    swal.fire(res.message, '', 'error');
                }
            }
        });
    },

    tableMyWork : function (page = 1){
        $.ajax({
            url: laroute.route('manager-work.report.table-my-work'),
            data: {page : page},
            method: "POST",
            success: function(res) {
                if(res.error == false){
                    $('.list-my-work').empty();
                    $('.list-my-work').append(res.view);
                }
            }
        });
    },
    tableWorkSupport : function (page = 1){
        $.ajax({
            url: laroute.route('manager-work.report.table-work-support'),
            data: {page : page},
            method: "POST",
            success: function(res) {
                if(res.error == false){
                    $('.list-work-support').empty();
                    $('.list-work-support').append(res.view);
                }
            }
        });
    }
}

var WorkChild = {
    approveStaff: function () {
        var value = $('#is_approve_id:checked').val();
        if (value == 1) {
            $('.black_title_not_approve').hide();
            $('.black_title_approve').show();
            var selectStaff = $('#approve_id_select').val();
            var idStaff = $('#id_staff').val();
            $('#approve_id_select').prop('disabled',false);
            if (selectStaff == '') {
                $('#approve_id_select').val(idStaff).trigger('change');
            }

        } else {
            $('.black_title_approve').hide();
            $('.black_title_not_approve').show();
            $('#approve_id_select').prop('disabled',true);
        }
    },
    showPopup : function(){
        $.ajax({
            url: laroute.route('manager-work.detail.show-popup-work-child'),
            method: "POST",
            data: {},
            success: function (res) {
                if (res.error == false){
                    $('#append-add-work').empty();
                    $('#append-add-work').append(res.view);
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

                    $(".daterange-input").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        pickerPosition: "bottom-left",
                        // format: "dd/mm/yyyy hh:ii",
                        format: "dd/mm/yyyy",
                        startDate : new Date()
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
                        startDate : new Date()
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
                            onImageUpload: function(files) {
                                for(let i=0; i < files.length; i++) {
                                    uploadImgCk(files[i]);
                                }
                            }
                        },
                    });
                    $('#append-add-work #popup-work').modal({
                        backdrop: 'static'
                    });
                    $('#append-add-work #popup-work').modal('show');
                    $('#append-add-work #popup-work').on('hidden.bs.modal', function (e) {
                        // location.reload();
                    });
                    DocumentWork.addImage();
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
        $('#append-add-work #popup-work').modal('hide');
        if(typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup'){
            WorkChild.processFunctionCancelWork({});
        }

        $('.note-children-container').remove();
    },
    saveWork : function (createNew = 0) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-work');
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
            data: $('#form-work').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal(res.message,'','success').then(function () {
                        if (createNew == 1){
                            $('#popup-work').modal('hide');
                            WorkChild.showPopup();
                        } else {
                            // location.reload();
                            $('#popup-work').modal('hide');
                        }
                    });
                    MyWork.getList();
                    $('.listMyWorkAssign').empty();
                    MyWork.getListAssign();
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
}
var StaffOverview = {
    popupChangeStatus : function(id){

        $.ajax({
            url: laroute.route('manager-work.staff-overview.popup-status'),
            data: {id : id},
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                $('#vund_popup').html(res.html);
                $('#popup-staff-overview-status').modal('show');
            }
        });
    },

    popupChangeProcess : function (id){
        $.ajax({
            url: laroute.route('manager-work.staff-overview.popup-process'),
            data: {id : id},
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                $('#vund_popup').html(res.html);
                $('#popup-staff-overview-process').modal('show');
            }
        });
    },

    popupChangeDate : function(id){
        $.ajax({
            url: laroute.route('manager-work.staff-overview.popup-date'),
            data: {id : id},
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                $('#vund_popup').html(res.html);
                $(".date-timepicker").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    startDate : new Date()
                    // locale: 'vi'
                });
                $('#popup-staff-overview-date').modal('show');
            }
        });
    },

    changeStatus : function (id){
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('manager-work.staff-overview.change-status'),
                data: {id : id, status : $('#form-change-status').find('#manage_status_id').val()},
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    swal(json['Đổi trạng thái thành công'],'','success').then(function(){
                        window.location.reload();
                        // $('#popup-staff-overview-status').modal('hide');
                        // setTimeout(function(){
                        //     $('#vund_popup').html('')
                        //     // ;
                        //     MyWork.getList();
                        //     MyWork.getListAssign();
                        //     MyWork.searchRemind();
                        // }, 500)

                    });
                }
            });
        });
    },

    changeProcess : function (id){
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('manager-work.staff-overview.change-process'),
                data: {id : id, progress : $('#form-change-process').find('#progress').val()},
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    swal(json['Đổi Tiến độ thành công'],'','success').then(function(){
                        window.location.reload();
                        // $('#popup-staff-overview-process').modal('hide');
                        // $('#vund_popup').html('');
                        // MyWork.getList();
                        // MyWork.getListAssign();
                        // MyWork.searchRemind();
                    });
                }
            });
        });
    },

    changeDate : function (id){
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('manager-work.staff-overview.change-date'),
                data: {id : id, date : $('#form-change-date').find('#date_end').val()},
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    swal(json['Đổi ngày hết hạn thành công'],'','success').then(function(){
                        window.location.reload();
                        // $('#popup-staff-overview-date').modal('hide');
                        // $('#vund_popup').html('');
                        // MyWork.getList();
                        // MyWork.getListAssign();
                        // MyWork.searchRemind();
                    });
                }
            });
        });
    },
    remindWorkOverdue : function(manage_work_id = null,key = null){
        var data = $('#form_list_work_overdue').serialize();
        if (manage_work_id != null){
            data = [];
            data.push(manage_work_id);
        }

        $.ajax({
            url: laroute.route('manager-work.staff-overview.popup-list-work-overdue'),
            data: {
                list_work_overdue : data,
                key : key
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('#append-popup').empty();
                    $('#append-popup').append(res.view);
                    $('.select2-active').select2({
                        dropdownParent: $(".modal")
                    });
                    $(".date-timepicker").datetimepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        pickerPosition: "bottom-left",
                        format: "dd/mm/yyyy hh:ii",
                        startDate : new Date()
                        // locale: 'vi'
                    });

                    AutoNumeric.multiple('.input-mask',{
                        currencySymbol : '',
                        decimalCharacter : '.',
                        digitGroupSeparator : ',',
                        decimalPlaces: 0,
                        minimumValue: 0
                    });

                    $('#popup-remind-work-overdue').modal('show');
                } else {
                    swal('',res.message,'error');
                }
            }
        });
    },

    addCloseRemindWorkOverdue : function () {
        $.ajax({
            url: laroute.route('manager-work.staff-overview.add-remind-work-overdue'),
            data: $('#form-work-overdue').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal(res.message,'','success');
                    $('#popup-remind-work-overdue').modal('hide');

                    MyWork.searchRemind();
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
    }
}

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
    },
}

uploadImgCkList = function (file,parent_comment = null) {
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
            // if (parent_comment != null){
            //     $(".summernote").summernote('insertImage', img['file']);
            // } else {
            //     $(".summernote").summernote('insertImage', img['file']);
            // }
            if (parent_comment != null){
                // $(".summernote").summernote('insertImage', img['file']);
                $(".summernote").summernote('insertImage', img['file'] , function ($image){
                    $image.css('width', '100%');
                });
            } else {
                // $(".summernote").summernote('insertImage', img['file']);
                $(".summernote").summernote('insertImage', img['file'] , function ($image){
                    $image.css('width', '100%');
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};

function createChart(chartId, chartData) {
    if ($('#'+chartId).length == 0){
        $('#'+chartId+'_text').before('<canvas id="'+chartId+'" style="width: 100%; height: 100%;margin:auto;"></canvas>');
    }
    const ctx = document.getElementById(chartId);
    const myChart = new Chart(ctx, {
        type: chartData.type,
        data: chartData.data,
        options: chartData.options,
    });

};

function myChart(chartLabel,chartData,chartColor) {
    return {
        type: 'doughnut',
        data : {
            labels: chartLabel,
            datasets: [{
                data: chartData,
                backgroundColor: chartColor,
                borderColor: chartColor,
                borderWidth: 1
            }]
        },
        options: {
            animation: {
                animateScale: true,
                animateRotate: true
            },
            responsive: true,
            maintainAspectRatio: false,

            legend: {
                display : false,
                position: 'left',
                labels:{
                    boxWidth: 10,
                    padding: 12
                }
            },
        }
    }
}

var DocumentWork = {
    jsonLang: JSON.parse(localStorage.getItem('tranlate')),
    addImage: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#dropzoneImageWork').dropzone({
                paramName: 'file',
                timeout:180000,
                maxFilesize: 10, // MB
                maxFiles: 1000,
                // acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                // parallelUploads: 1,
                // headers: {
                //     "X-CSRF-TOKEN": $('input[name=_token]').val()
                // },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dictRemoveFile: 'Xóa',
                dictMaxFilesExceeded: json['Bạn tải quá nhiều file'],
                dictInvalidFileType: json['Tệp không hợp lệ'],
                dictCancelUpload: json['Hủy'],
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
                    var random = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    for (let z = 0; z < 10; z++) {
                        random += possible.charAt(Math.floor(Math.random() * possible.length));
                    }
                    return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
                },
                init: function() {
                    this.on("success", function(file, response) {
                        const typeImage = ["image/bmp", "image/gif", "image/vnd.microsoft.icon", "image/jpeg", "image/png", "image/svg+xml", "image/tiff", "image/webp"];
                        let fileName = file.upload.filename;
                        // var a = document.createElement('span');
                        // a.className = "thumb-url btn btn-primary";
                        // a.setAttribute('data-clipboard-text', laroute.route('manager-work.detail.upload-file') + response);
                        if (file.status === "success") {
                            //Xóa image trong dropzone
                            $('#dropzoneImageWork')[0].dropzone.files.forEach(function(file) {
                                file.previewElement.remove();
                            });
                            $('#dropzoneImageWork').removeClass('dz-started');
                            //Append vào div image
                            var n = $('.image-show-work').length;
                            if (typeImage.indexOf(file.type) != -1) {
                                let tpl = $('#imageShowWork').html();
                                tpl = tpl.replace(/{n}/g, n+1);
                                tpl = tpl.replace(/{link_work}/g, response.file);
                                tpl = tpl.replace(/{link_hidden_work}/g, response.file);
                                // tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                                tpl = tpl.replace(/{file_name_work}/g, file.name);
                                // $('#file_name').val(file.upload.filename);
                                // $('#upload-image').empty();
                                $('#upload-image-work').append(tpl);
                                $('#path_work').val(response.file);
                                // $('#file_type').val('image');
                            } else {
                                let tpl = $('#imageShowFileWork').html();
                                tpl = tpl.replace(/{n}/g, n+1);
                                tpl = tpl.replace(/{link_work}/g, response.file);
                                tpl = tpl.replace(/{link_hidden_work}/g, response.file);
                                // tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                                tpl = tpl.replace(/{file_name_work}/g, file.name);
                                // $('#file_name').val(file.upload.filename);
                                // $('#upload-image').empty();

                                $('#upload-image-work').append(tpl);
                                $('#path_work').val(response.file);
                                // $('#file_type').val('file');
                            }
                        }
                    });
                    this.on('removedfile', function(file, response) {
                        var name = file.upload.filename;
                        $.ajax({
                            url: laroute.route('admin.service.delete-image'),
                            method: "POST",
                            data: {

                                filename: name
                            },
                            success: function() {
                                $("input[class='file_Name']").each(function() {
                                    var $this = $(this);
                                    if ($this.val() === name) {
                                        $this.remove();
                                    }
                                });

                            }
                        });
                    });
                    this.on('sending', function(file, xhr, formData) {
                        /*Called just before each file is sent*/
                        xhr.ontimeout = (() => {
                            /*Execute on case of timeout only*/
                            swal(DocumentWork.jsonLang['Quá thời gian upload'],'','error');
                        });
                    });
                }
            });
        });
    },

    removeImage: function(obj) {
        $(obj).closest('.image-show-work').remove();
        $('#path_work').val('');
    },

}

function scrollBlock() {
    $( ".scroll").sortable({
        revert: true,
        update: function( event, ui ) {
            var block = [];
            $.each($('.scroll').find(".m-portlet"), function () {
                var key_block = $(this).attr('data-key-block');
                block.push({
                    key_block : key_block,
                })
            });

            $.ajax({
                url: laroute.route('manager-work.report.my-work-update-block'),
                method: "POST",
                dataType: "JSON",
                data: {
                    block: block,
                    route : $('#routeName').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal.fire(res.message, '', "success");
                    } else {
                        swal.fire(res.message, '', "error");
                    }
                }
            });
        }
    });
}