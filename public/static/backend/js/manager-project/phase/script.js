var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
var Phase = {
    _init : function (){
        $('.select2').select2();

        $("#m_datepicker_1").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            buttonClasses: "m-btn btn",
            applyClass: "btn-primary",
            cancelClass: "btn-danger",
            // maxDate: moment().endOf("day"),
            startDate: moment().startOf("day"),
            endDate: moment().add(0, 'days'),
            // minDate: moment().startOf("day"),
            locale: {
                format: 'DD/MM/YYYY',
                "applyLabel": jsonLang["Đồng ý"],
                "cancelLabel": jsonLang["Thoát"],
                "customRangeLabel": jsonLang["Tùy chọn ngày"],
                daysOfWeek: [
                    jsonLang["CN"],
                    jsonLang["T2"],
                    jsonLang["T3"],
                    jsonLang["T4"],
                    jsonLang["T5"],
                    jsonLang["T6"],
                    jsonLang["T7"]
                ],
                "monthNames": [
                    jsonLang["Tháng 1 năm"],
                    jsonLang["Tháng 2 năm"],
                    jsonLang["Tháng 3 năm"],
                    jsonLang["Tháng 4 năm"],
                    jsonLang["Tháng 5 năm"],
                    jsonLang["Tháng 6 năm"],
                    jsonLang["Tháng 7 năm"],
                    jsonLang["Tháng 8 năm"],
                    jsonLang["Tháng 9 năm"],
                    jsonLang["Tháng 10 năm"],
                    jsonLang["Tháng 11 năm"],
                    jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: {}
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
        });

        $("#m_datepicker_2").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            buttonClasses: "m-btn btn",
            applyClass: "btn-primary",
            cancelClass: "btn-danger",
            // maxDate: moment().endOf("day"),
            startDate: moment().startOf("day"),
            endDate: moment().add(0, 'days'),
            // minDate: moment().startOf("day"),
            locale: {
                format: 'DD/MM/YYYY',
                "applyLabel": jsonLang["Đồng ý"],
                "cancelLabel": jsonLang["Thoát"],
                "customRangeLabel": jsonLang["Tùy chọn ngày"],
                daysOfWeek: [
                    jsonLang["CN"],
                    jsonLang["T2"],
                    jsonLang["T3"],
                    jsonLang["T4"],
                    jsonLang["T5"],
                    jsonLang["T6"],
                    jsonLang["T7"]
                ],
                "monthNames": [
                    jsonLang["Tháng 1 năm"],
                    jsonLang["Tháng 2 năm"],
                    jsonLang["Tháng 3 năm"],
                    jsonLang["Tháng 4 năm"],
                    jsonLang["Tháng 5 năm"],
                    jsonLang["Tháng 6 năm"],
                    jsonLang["Tháng 7 năm"],
                    jsonLang["Tháng 8 năm"],
                    jsonLang["Tháng 9 năm"],
                    jsonLang["Tháng 10 năm"],
                    jsonLang["Tháng 11 năm"],
                    jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: {}
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
        });
    },

    addContentPhase : function (){
        phaseTmp++;
        let tpl = $('#tpl-phase').html();
        tpl = tpl.replace(/{n}/g, phaseTmp);
        $('.add-phase').append(tpl);

        $('.select2').select2();
        $('.block_'+phaseTmp+' .date_start').datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd/mm/yyyy',
        });
        $('.block_'+phaseTmp+' .date_end').datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd/mm/yyyy',
        });

    },

    removeContentPhase : function (n){
        $('.block_'+n).remove();
    },

    submitAdd : function (projectId,type = null){
        var phase = [];

        var check = 1;

        var main_manage_project_id = $('#main_manage_project_id').val();
        var saveTemplate = type;

        $.each($('.add-phase').find('.block'),function (){
            var manage_project_id = $(this).find($('.manage_project_id')).val();
            var name = $(this).find($('.name')).val();
            var pic = $(this).find($('.pic')).val();
            var date_start = $(this).find($('.date_start')).val();
            var date_end = $(this).find($('.date_end')).val();

            if (name == '' || name.length > 191 || pic == ''){
                check = 0;
            }

            phase.push({
                manage_project_id:manage_project_id,
                name:name,
                pic:pic,
                date_start:date_start,
                date_end:date_end
            });


        });

        if (check == 0){
            swal.fire(jsonLang['Vui lòng điền đủ tên giai đoạn và người chịu trách nhiệm và tên giai đoạn nhỏ hơn 191 ký tự'], "", "warning");
            return ;
        }

        data = {
            main_manage_project_id,
            phase,
            saveTemplate
        };
        // gửi data
        $.ajax({
            url: laroute.route('manager-project.phase.store'),
            data: data,
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success").then(function () {
                        window.location.href = res.link;
                    });
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(mess_error, '', "error");
                }
            }
        });
    },

    deletePhase : function (obj,manage_project_phase_id){
        $(obj).closest('tr').addClass('m-table__row--danger');

        var title = jsonLang["Bạn có muốn xóa không?"];

        swal({
            title: jsonLang['Thông báo'],
            text: title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang['Xóa'],
            cancelButtonText: jsonLang['Hủy'],
            onClose: function() {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function(result) {
            if (result.value) {
                $.post(laroute.route('manager-project.phase.remove', { manage_project_phase_id: manage_project_phase_id }), function(res) {
                    if (res.error == false) {
                        swal.fire(res.message, "", "success").then(function () {
                            location.reload();
                        });
                    } else {
                        swal.fire(res.message, '', "error");
                    }

                });
            }
        });
    },

    showPopup : function (manage_project_phase_id){
        $.post(laroute.route('manager-project.phase.showPopup', { manage_project_phase_id: manage_project_phase_id }), function(res) {
            if (res.error == false) {
                $('.append-popup').empty();
                $('.append-popup').append(res.view);
                $('#popup-phase').modal('show');

                $('.select2').select2();
                $('.date_start').datepicker({
                    todayHighlight: true,
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                });
                $('.date_end').datepicker({
                    todayHighlight: true,
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                });

            } else {
                swal.fire(res.message, '', "error");
            }

        });
    },

    updatePhase : function (){
        $.post(laroute.route('manager-project.phase.update', $('#form-phase').serialize()), function(res) {
            if (res.error == false) {
                swal.fire(res.message, "", "success").then(function () {
                    location.reload();
                });
            } else {
                swal.fire(res.message, '', "error");
            }

        });

        $.ajax({
            url: laroute.route('manager-project.phase.update'),
            data: $('#form-phase').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success").then(function () {
                        location.reload();
                    });
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(mess_error, '', "error");
                }
            }
        });
    },

    changeSample : function (manage_phase_group_code){
        $.ajax({
            url: laroute.route('manager-project.phase.change-sample'),
            data: {
                manage_phase_group_code : manage_phase_group_code
            },
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    $('.add-phase').empty();
                    $('.add-phase').append(res.view);
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },

    applyTemplate : function (){
        var manage_project_id = $('#manage_project_id').val();
        var template = $('input[name="template"]').val();
        window.location.href = laroute.route('manager-project.phase.add',{id : manage_project_id,template : template });
    }
}