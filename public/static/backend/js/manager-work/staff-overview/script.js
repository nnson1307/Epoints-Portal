var DocumentWork = {
    jsonLang: JSON.parse(localStorage.getItem('tranlate')),
    addImage: function() {
        $.getJSON(laroute.route('translate'), function (json) {
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
                dictMaxFilesExceeded: json['Bạn tải quá nhiều file'],
                dictInvalidFileType: json['Tệp không hợp lệ'],
                dictCancelUpload: json['Hủy'],
                dictFileTooBig : json['Bạn tải file có dung lượng lớn ({{filesize}}MiB). Dung lượng tối đa: {{maxFilesize}}MiB.'],
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

};

$(document).ready(function () {
    $('.searchSelect').select2();
    $.getJSON(laroute.route('translate'), function (json) {
        var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".searchDate").daterangepicker({
            autoApply: true,
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
            StaffOverview.changeChartStatus();
        });

        $(".list_dateSelect").daterangepicker({
            autoApply: true,
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
            StaffOverview.priorityWork();
        });
    });

    StaffOverview.changeChartStatus();
    StaffOverview.priorityWork();
    StaffOverview.hotSpotDetection();

});

$.getJSON(laroute.route('translate'), function (json) {
    const chartLabel = [json["Chưa thực hiện"], json["Đang thực hiện"], json["Hoàn thành"], json["Chưa hoàn thành"], json["Quá hạn"]];
});
const chartData = [47, 19, 71, 51, 22];

var StaffOverview = {
    chartColor1 : [
        '#BDD7EE',
        '#5B9BD5',
        '#77C144',
        '#FFC000',
        '#E94343',
        '#C0C0C0',
        '#808080'
    ],
    chartColor2 : [
        '#E94343',
        '#FFC000',
        '#92D050'
    ],

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
                        // window.location.reload();
                        $('#popup-staff-overview-status').modal('hide');
                        StaffOverview.priorityWork();
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
                        $('#popup-staff-overview-process').modal('hide');
                        StaffOverview.priorityWork();
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
                        $('#popup-staff-overview-date').modal('hide');
                        StaffOverview.priorityWork();
                    });
                }
            });
        });
    },


    changeChartStatus : function () {

        $.ajax({
            url: laroute.route('manager-work.staff-overview.search-chart'),
            data: $('.frmFilter_chart').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    if (res.total1 == 0){
                        $('#report_chart_status_text').empty();
                        $('#report_chart_status').remove();
                        $('#report_chart_status_update').html('');
                        $('#report_chart_status_text').html(res.totalText1);
                        $('#report_chart_status_update').html(res.view1);
                    } else {
                        // createChart('report_chart_status', myChart(res.label1,res.data1,StaffOverview.chartColor1));
                        createChart('report_chart_status', myChart(res.label1,res.data1,res.color1));
                        $('#report_chart_status_text').html(res.total1);
                        $('#report_chart_status_update').html(res.view1);
                    }

                    if (res.total2 == 0){
                        $('#report_chart_priority_text').empty();
                        $('#report_chart_priority').remove();
                        $('#report_chart_priority_text_update').html('');
                        $('#report_chart_priority_text').html(res.totalText2);
                        $('#report_chart_priority_text_update').html(res.view2);
                    } else {
                        createChart('report_chart_priority', myChart(res.label2,res.data2,StaffOverview.chartColor2));
                        $('#report_chart_priority_text').html(res.total2);
                        $('#report_chart_priority_text_update').html(res.view2);
                    }
                    StaffOverview.tableWorkLevel();
                    StaffOverview.tableWorkStatus();
                } else {
                    swal('',res.message,'error');
                }
            }
        });
    },

    hotSpotDetection:function () {
        $.ajax({
            url: laroute.route('manager-work.staff-overview.hot-spot-detection'),
            data: $('.frmFilter').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    // $('.list_staff_not_work').empty();
                    $('.list_staff_not_work_start_yet').empty();
                    // $('.list_work_overdue').empty();
                    // $('.list_staff_not_work').append(res.viewStaffNoJob);
                    $('.list_staff_not_work_start_yet').append(res.viewStaffNoStartedJob);
                    // $('.list_work_overdue').append(res.viewListOverdue);
                } else {
                    swal('',res.message,'error');
                }
            }
        });
    },

    priorityWork : function () {
        $.ajax({
            url: laroute.route('manager-work.staff-overview.priority-work'),
            data: $('.frmFilter_list').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.list_priority').empty();
                    $('.list_priority').append(res.view);
                } else {
                    swal('',res.message,'error');
                }
            }
        });
    },

    remindStaffNotStartWork : function () {
        $.ajax({
            url: laroute.route('manager-work.staff-overview.popup-list-staff-not-start-work'),
            data: $('#list_staff_not_start_work').serialize(),
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

                    $('#popup-remind-staff-not-start').modal('show');
                } else {
                    swal('',res.message,'error');
                }
            }
        });
    },

    remindWorkOverdue : function(type = null,manage_work_id = null,key = null){
        var data = $('#form_list_work_overdue').serialize();
        if (manage_work_id != null){
            data = [];
            data.push(manage_work_id);
        }

        $.ajax({
            url: laroute.route('manager-work.staff-overview.popup-list-work-overdue'),
            data: {
                list_work_overdue : data,
                key : key,
                type : type,
                manage_work_id : manage_work_id
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

    addCloseRemind : function () {
        $.ajax({
            url: laroute.route('manager-work.staff-overview.add-remind-list-staff-not-start'),
            data: $('#form-staff-not-start-work').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    swal(res.message,'','success');
                    $('#popup-remind-staff-not-start').modal('hide');
                    $('.list_work_overdue').prop('checked', false);
                    $('.staff_not_start_work').prop('checked', false);
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
                    $('.staff_not_start_work').prop('checked', false);
                    $('.list_work_overdue').prop('checked', false);
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
    tableWorkStatus : function (page = 1){
        $.ajax({
            url: laroute.route('manager-work.staff-overview.table-work-status'),
            data: $('.frmFilter_chart').serialize()+'&page='+page,
            method: "POST",
            success: function(res) {
                if(res.error == false){
                    $('.list-work-status').empty();
                    $('.list-work-status').append(res.view);
                }
            }
        });
    },

    tableWorkLevel : function (page = 1){
        $.ajax({
            url: laroute.route('manager-work.staff-overview.table-work-level'),
            data: $('.frmFilter_chart').serialize()+'&page='+page,
            method: "POST",
            success: function(res) {
                if(res.error == false){
                    $('.list-work-level').empty();
                    $('.list-work-level').append(res.view);
                }
            }
        });
    },
}

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
                position: 'right',
                labels:{
                    boxWidth: 10,
                    padding: 12
                }
            },
        }
    }
}


function createChart(chartId, chartData) {
    console.log($('#'+chartId).length);
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
    showPopup : function(staff_id = null){
        $.ajax({
            url: laroute.route('manager-work.detail.show-popup-work-child'),
            method: "POST",
            data: {
                staff_id : staff_id
            },
            success: function (res) {
                if (res.error == false){
                    $('#append-popup-work').empty();
                    $('#append-popup-work').append(res.view);
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
                        dropdownParent: $('#parent_id').parent(),
                        width: '100%',
                        placeholder: jsonLang['Chọn công việc cha'],
                        ajax: {
                            url: laroute.route('manager-work.getListParentTask'),
                            data: function (params) {
                                return {
                                    search: params.manage_work_title,
                                    manage_project_id: $('#popup-work #popup_manage_project_id').val(),
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
                    $('#append-popup-work #popup-work').modal({
                        backdrop: 'static'
                    });
                    $('#append-popup-work #popup-work').modal('show');
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
    closePopup: function () {
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

            var parent_id = $('#parent_id').val();
            var manage_status_id = $('select[name="manage_status_id"] option:selected').val();
            var total_child = $('#total_child_work').val();
            if (total_child != 0){
                title =  ManagerWork.jsonLang["Công việc có :n công việc con bạn có muốn cập nhật không?"];
                title = title.replace(':n',total_child);
            }
            if (parent_id == '' && manage_status_id == 7){
                swal({
                    title: ManagerWork.jsonLang['Thông báo'],
                    text: title,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: ManagerWork.jsonLang['Cập nhật'],
                    cancelButtonText: ManagerWork.jsonLang['Hủy']

                }).then(function(result) {
                    if (result.value) {
                        WorkChild.saveWorkAction(createNew);
                    }
                });
            } else {
                WorkChild.saveWorkAction(createNew);
            }

        });
    },

    // Cập nhật thêm 1 ajax gọi kiểm tra thời gian công việc và dự án
    saveWorkAction:function (createNew){
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
                    StaffOverview.hotSpotDetection();
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
    }
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