var WorkProject = {
    jsonLang: JSON.parse(localStorage.getItem('tranlate')),
    
    _init : function (){
        $('#support').selectpicker();

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

        if(typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup'){
            $(".summernote").summernote("code", $('#fr_message_chat').val());
            if($('#fr_customer_id').val() && $('#fr_customer_id').val() != ''){
                $('#manage_work_customer_type').val('customer').trigger('change');
                setTimeout(function(){
                    $('#customer_id').val($('#fr_customer_id').val()).trigger('change');
                }, 1000);

            } else {
                $('#manage_work_customer_type').val('lead').trigger('change');
                setTimeout(function(){
                    $('#customer_id').val($('#fr_customer_lead_id').val()).trigger('change');
                }, 1500);

            }

        }

        $('select:not(.normal)').each(function () {
            if ($(this).attr('id') != 'support') {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            }
        });

        // var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

        $('#parent_id').select2({
            dropdownParent: $('#parent_id').parent(),
            width: '100%',
            placeholder: WorkProject.jsonLang['Chọn công việc cha'],
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

        WorkProject.changeCustomer();

        DocumentWork.addImage();
    },

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

                    WorkProject.changeObjectCustomer($('#customer_id'));
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
            $('#approve_id_select').val('').trigger('change');
            $('#approve_id_select').prop('disabled',true);
        }
    },

    changeParentTask : function () {
        var parentId = $('#parent_id').val();
        var is_parent_number = $('#form-work-detail #is_parent_number').val();
        if (is_parent_number == 0) {
            if (parentId != '') {
                $('.disabled-parent-text').val(' ');
                $('.disabled-parent-select').val($(".disabled-parent-select option:first").val());
                $('.select-parent').prop('checked', true);
                $('.disabled-parent').prop('disabled', true);
                $('.disabled-parent-text').prop('disabled', true);
                $('.disabled-parent-select').prop('disabled', true);
            } else {
                $('.disabled-parent').prop('disabled', false);
                $('.disabled-parent-select').prop('disabled', false);
                $('.disabled-parent-text').prop('disabled', false);
            }
        }
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
                WorkProject.saveWorkFinish(createNew);
            } else {
                $.ajax({
                    url: laroute.route('manager-work.check-date-work-project'),
                    data: form.serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.error == true){
                            swal({
                                title: WorkProject.jsonLang['Thông báo'],
                                text: res.title,
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: WorkProject.jsonLang['Lưu'],
                                cancelButtonText: WorkProject.jsonLang['Hủy']

                            }).then(function(result) {
                                if (result.value) {
                                    WorkProject.saveWorkFinish(createNew);
                                }
                            });
                        } else {
                            WorkProject.saveWorkFinish(createNew);
                        }
                    },
                });
            }
        });
    },

    saveWorkFinish : function (createNew){
        $.ajax({
            url: laroute.route('manager-project.work.detail.save-child-work'),
            data: $('#form-work').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal(res.message,'','success').then(function () {
                        if (createNew == 1){
                            location.reload();
                        } else {
                            window.location.href = laroute.route('manager-project.work',{manage_project_id : $('#group_manage_project_id').val()})
                        }
                    });
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

    //Show popup chọn nhân viên hỗ trợ
    showPopStaff: function () {
        $.ajax({
            url: laroute.route('manager-project.work.show-pop-staff-support'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                manage_project_id : $('#group_manage_project_id').val()
            },
            success: function (res) {
                $('#my-modal-staff').html(res.html);
                $('#modal-add-staff').modal('show');

                $(".m_selectpicker").select2({
                    width: "100%"
                });

                $('#autotable-staff-pop').PioTable({
                    baseUrl: laroute.route('manager-project.work.list-staff-support')
                });
            }
        });
    },

    //Tắt popup chọn nhân viên hỗ trợ
    chosePopStaff: function () {
        $('#modal-add-staff').modal('hide');

        $('#my-modal-staff').empty();
        $('.modal-backdrop').hide();
    },

    //Chọn tất cả nhân viên hỗ trợ
    chooseAllStaffSupport: function (obj) {
        if ($(obj).is(':checked')) {
            $('.check_one').prop('checked', true);

            var arrChoose = [];

            $('.check_one').each(function () {
                $(this).closest('.tr_staff_support').find('.staff_id').prop('disabled', false);

                arrChoose.push({
                    staff_id: $(this).closest('.tr_staff_support').find('.staff_id').val(),
                });
            });

            $.ajax({
                url: laroute.route('manager-project.work.choose-staff-support'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrChoose: arrChoose
                },
                success: function (res) {

                }
            });
        } else {
            $('.check_one').prop('checked', false);

            var arrUnChoose = [];

            $('.check_one').each(function () {
                $(this).closest('.tr_staff_support').find('.staff_id').val(1).prop('disabled', true);

                arrUnChoose.push({
                    staff_id: $(this).closest('.tr_staff_support').find('.staff_id').val(),
                });
            });

            $.ajax({
                url: laroute.route('manager-project.work.un-choose-staff-support'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrUnChoose: arrUnChoose
                },
                success: function (res) {

                }
            });
        }
    },

    //Chọn 1 nhân viên hỗ trợ
    chooseStaffSupport: function (obj) {
        if ($(obj).is(':checked')) {
            var arrChoose = [];

            arrChoose.push({
                staff_id: $(obj).closest('.tr_staff_support').find($('.staff_id')).val(),
            });

            $.ajax({
                url: laroute.route('manager-project.work.choose-staff-support'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrChoose: arrChoose
                },
                success: function (res) {

                }
            });
        } else {
            var arrUnChoose = [];

            arrUnChoose.push({
                staff_id: $(obj).closest('.tr_staff_support').find($('.staff_id')).val()
            });

            $.ajax({
                url: laroute.route('manager-project.work.un-choose-staff-support'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrUnChoose: arrUnChoose
                },
                success: function (res) {

                }
            });
        }
    },

    //Submit chọn nhân viên hỗ trợ
    submitChooseStaffSupport: function () {
        $.ajax({
            url: laroute.route('manager-project.work.submit-choose-staff-support'),
            method: 'POST',
            dataTYpe: 'JSON',
            data: {

            },
            success: function (res) {
                if (res.error == false) {
                    $('.div_staff_support').empty();

                    swal(res.message, '', "success");

                    $('#modal-add-staff').modal('hide');

                    $('#my-modal-staff').empty();
                    $('.modal-backdrop').hide();

                    var staffName = "";

                    $.each(res.data, function (k, v) {
                        $('.div_staff_support').append('<input type="hidden" name="support[]" value="'+ v.staff_id +'">');

                        var comma = '';

                        if (k + 1 < res.data.length) {
                            comma = ', ';
                        }

                        staffName += v.full_name + comma;
                    });

                    $('.div_staff_support').append('<textarea class="form-control" disabled rows="5">'+ staffName +'</textarea>');
                } else {
                    //Báo lỗi
                    swal(res.message, '', "error");
                }
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

}


var DocumentWork = {
    jsonLang: JSON.parse(localStorage.getItem('tranlate')),
    addImage: function() {

        $('#dropzoneImageWork').dropzone({
            paramName: 'file',
            timeout:180000,
            maxFilesize: 1024, // MB
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
            dictMaxFilesExceeded: WorkProject.jsonLang['Bạn tải quá nhiều file'],
            dictInvalidFileType: WorkProject.jsonLang['Tệp không hợp lệ'],
            dictCancelUpload: WorkProject.jsonLang['Hủy'],
            dictFileTooBig : WorkProject.jsonLang['Bạn tải file có dung lượng lớn ({{filesize}}MiB). Dung lượng tối đa: {{maxFilesize}}MiB.'],
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

    },

    removeImage: function(obj) {
        $(obj).closest('.image-show-work').remove();
        $('#path_work').val('');
    },

}

$(document).ready(function (){
    $('#dropzoneImageWork').dropzone({
        paramName: 'file',
        timeout:180000,
        maxFilesize: 1024, // MB
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
        dictMaxFilesExceeded: WorkProject.jsonLang['Bạn tải quá nhiều file'],
        dictInvalidFileType: WorkProject.jsonLang['Tệp không hợp lệ'],
        dictCancelUpload: WorkProject.jsonLang['Hủy'],
        dictFileTooBig : WorkProject.jsonLang['Bạn tải file có dung lượng lớn ({{filesize}}MiB). Dung lượng tối đa: {{maxFilesize}}MiB.'],
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
                    //XĂ³a image trong dropzone
                    $('#dropzoneImage')[0].dropzone.files.forEach(function(file) {
                        file.previewElement.remove();
                    });
                    $('#dropzoneImage').removeClass('dz-started');
                    //Append vĂ o div image
                    if (typeImage.indexOf(file.type) != -1) {
                        let tpl = $('#imageShowWork').html();
                        tpl = tpl.replace(/{link}/g, response.file);
                        tpl = tpl.replace(/{link_hidden}/g, response.file);
                        // tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                        tpl = tpl.replace(/{file_name}/g, file.name);
                        // $('#file_name').val(file.upload.filename);
                        // $('#upload-image').empty();
                        if ($('.manage_document_file_id').length){
                            $('#upload-image').empty();
                        }
                        $('#upload-image').append(tpl);
                        $('#path').val(response.file);
                        // $('#file_type').val('image');
                    } else {
                        let tpl = $('#imageShowFileWork').html();
                        tpl = tpl.replace(/{link}/g, response.file);
                        tpl = tpl.replace(/{link_hidden}/g, response.file);
                        // tpl = tpl.replace(/{file_name}/g, file.upload.filename);
                        tpl = tpl.replace(/{file_name}/g, file.name);
                        // $('#file_name').val(file.upload.filename);
                        // $('#upload-image').empty();
                        if ($('.manage_document_file_id').length){
                            $('#upload-image').empty();
                        }
                        $('#upload-image').append(tpl);
                        $('#path').val(response.file);
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
                    swal(WorkProject.jsonLang['Quá thời gian upload'],'','error');
                });
            });
        }
    });
})