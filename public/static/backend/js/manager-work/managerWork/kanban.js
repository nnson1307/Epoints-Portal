var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date();
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
$('.btn-refresh').click(function() {
    $(this).closest('form')[0].reset();
    $(this).closest('form').find('.select2-active').val("").trigger("change");
    $(this).closest('form')[0].submit();
});

var rules_validate_setting = {
    is_approve_id: {
        required: false
    },
    manage_work_title: {
        required: true,
        maxlength: 255
    },
    manage_work_id: {
        required: true,
    },
    date_issue: {
        // required: true
    },
    time: {
        required: true,
        pattern: /^[0-9,.]+$/,
        minStrict: 0,

    },
    time_type: {
        required: true
    },
    processor_id: {
        required: true,
    },
    // approve_id: {
    //     required: true,
    // },
    // processor: {
    //     required: true,
    // },
    // parent_id: {
    //     required: true,
    // },
    progress: {
        // required: true,
        pattern: /^[0-9,.]+$/,
        min: 0,
    },
    // description: {
    //     required: true,
    // },
    // manage_project_id: {
    //     required: true,
    // },
    // customer_id: {
    //     required: true,
    // },
    // manage_tag_id: {
    //     required: true,
    // },
    // type_card_work: {
    //     required: true,
    // },
    // priority: {
    //     required: true,
    // },
    // manage_status_id: {
    //     required: true,
    // },
};

var mess_validate_setting = {
    is_approve_id: {},
    manage_work_title: {
        required: 'Vui lòng nhập tiêu đề',
        maxlength : 'Tiêu đề tối đa 255 ký tự'
    },
    manage_work_id: {
        required: 'Vui lòng chọn loại công việc',
    },
    // date_issue: {
    //     required: 'Vui lòng chọn ngày hết hạn',
    // },
    // date_issue_single: {
    //     required: 'Vui lòng chọn ngày hết hạn.',
    // },
    time: {
        required: 'Vui lòng nhập thời lượng',
        pattern : 'Thời lượng không đúng định dạng',
        minStrict : 'Thời lượng không đúng định dạng',
    },
    time_type: {
        required: 'Vui lòng chọn loại thời lượng',
    },
    processor_id: {
        required: 'Vui lòng chọn nhân viên',
    },
    approve_id: {
        required: 'Vui lòng chọn người duyệt',
    },
    processor: {
        required: 'Vui lòng chọn người hỗ trợ',
    },
    parent_id: {
        required: 'Vui lòng chọn công việc cha',
    },
    progress: {
        required: 'Vui lòng nhập tiến độ',
        max: 'Tiến độ không được quá 100%',
        pattern: 'Tiến độ không đúng định dạng',
        minStrict: 'Tiến độ không đúng định dạng',
    },
    description: {
        required: 'Vui lòng nhập mô tả',
    },
    manage_project_id: {
        required: 'Vui lòng chọn công việc',
    },
    customer_id: {
        required: 'Vui lòng chọn khách hàng liên quan',
    },
    manage_tag_id: {
        required: 'Vui lòng chọn tag',
    },
    type_card_work: {
        required: 'Vui lòng chọn loại thẻ công việc',
    },
    priority: {
        required: 'Vui lòng chọn độ ưu tiên',
    },
    manage_status_id: {
        required: 'Vui lòng chọn trạng thái',
    },
};

var rules_validate_form_remind = {
    processor_id_remind: {
        required: true,
    },
    date_remind: {
        required: true,
    },
    // time_remind: {
    //     required: true,
    // },
    // time_type_remind: {
    //     required: true,
    // },
    description_remind: {
        required: true,
    },
};
var mess_validate_form_remind = {
    processor_id_remind: {
        required: 'Vui lòng chọn người nhắc',
    },
    date_remind: {
        required: 'Vui lòng chọn ngày nhắc',
    },
    // time_remind: {
    //     required: 'Vui lòng chọn người nhắc',
    // },
    // time_type_remind: {
    //     required: 'Vui lòng chọn người nhắc',
    // },
    description_remind: {
        required: 'Vui lòng nhập mô tả',
    },
};
var detailCommon = {
    showPopup : function(id){
        $.ajax({
            url: laroute.route('manager-work.detail.show-popup-remind-popup'),
            method: "POST",
            data: {
                manage_work_id : $('#manage_work_id').val(),
                manage_remind_id : id
            },
            success: function (res) {
                if (res.error == false){
                    $('#block_append').empty();
                    $('#block_append').append(res.view);
                    // $('.selectForm').select2();
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
            url: laroute.route('manager-work.staff-overview.add-remind-work'),
            data: $('#form-remind-staff-work').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    if (check == 0){
                        swal(res.message,'','success').then(function () {
                            location.reload();
                        });
                    } else {
                        $('#popup-remind-work').modal('hide');
                        $('.modal-backdrop').remove();
                        $('#block_append').empty();
                        $('#block_append').append(res.view);
                        $('.selectForm').select2();
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
                    }

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
};
var ManagerWork = {

    submitCopy : function (id){
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Sao chép công việc'],
                text: json["Bạn có muốn sao chép không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Đồng ý'],
                cancelButtonText: json['Hủy']
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('manager-work.copy', {id: id}), function () {
                        swal(
                            json['Sao chép công việc thành công.'],
                            '',
                            'success'
                        );
                    });
                }
            });
        });
    },

    approve : function (id){
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Duyệt công việc'],
                text: json["Bạn có muốn duyệt không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Đồng ý'],
                cancelButtonText: json['Hủy']
            }).then(function(result) {
                if (result.value) {
                    $.post(laroute.route('manager-work.approve', { id: id }), function() {
                        swal(
                            json['Duyệt công việc thành công.'],
                            '',
                            'success'
                        ).then((result) => {
                            window.location.reload();
                        });
                    });
                }
            });
        });
    },

    remove: function(obj, id) {
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function(json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function() {
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function(result) {
                if (result.value) {
                    $.post(laroute.route('manager-work.remove', { id: id }), function(res) {
                        if(!res.error){
                            swal(
                                json['Xóa thành công.'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                        } else {
                            swal(
                                json['Công việc không thể xoá'],
                                '',
                                'warning'
                            );
                        }

                    });
                }
            });
        });
    },
    changeStatus: function(obj, id, action) {
        $.post(laroute.route('manager-work.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    showAdd : function(){
        $.getJSON(laroute.route('translate'), function (json) {
            $.post(laroute.route('manager-work.show-add'), {}, function (res) {
                $('#my_modal').html(res.html);

                $('#progress100').on('keyup input paste', function () {
                    if (isNaN($(this).val())) {
                        $(this).val(0);
                    }

                    if ($(this).val() > 100) {
                        $(this).val(100);
                    }
                });

                $('#my_modal .select2.select2-active').each(function () {
                    let placeholder_value = $(this).find("option:first").text() != undefined ? $(this).find("option:first").text() : "Vui lòng chọn";
                    $(this).select2({
                        placeholder: {
                            id: '',
                            text: placeholder_value
                        },
                    });
                });

                $(".date-timepicker").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    minDate: new Date(),
                    // locale: 'vi'
                });

                var date = new Date();
                $(".daterange-input").daterangepicker({
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
                        format: 'DD/MM/YYYY hh:mm',
                        "applyLabel": json["Đồng ý"],
                        "cancelLabel": json["Thoát"],
                        "customRangeLabel": json["Tùy chọn ngày"],
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
                    ranges: {}
                }).on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY hh:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY hh:mm'))
                });


                var Summernote = {
                    init: function () {
                        $.getJSON(laroute.route('translate'), function (json) {
                            $(".summernote").summernote({
                                height: 208,
                                lang: 'vi-VN',
                                placeholder: json['Nhập nội dung...'],
                                toolbar: [
                                    ['style', ['bold', 'italic', 'underline']],
                                    ['fontsize', ['fontsize']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']],
                                    // ['insert', ['link', 'picture']]
                                ]
                            })
                        });
                    }
                };
                jQuery(document).ready(function () {
                    Summernote.init();
                });

                $('#my_modal').modal('show');
            }, 'JSON');
        })
    },

    add: function() {

        $.getJSON(laroute.route('translate'), function(json) {
            var form = $('#my_modal form');
            form.validate({
                rules: rules_validate_setting,
                messages: mess_validate_setting,
            });

            if (!form.valid()) {
                return false;
            }
            if($('#is_approve_id_check').is(":checked")){
                if(!$('#approve_id').val()){
                    swal(
                        json['Vui lòng chọn người duyệt.'],
                        '',
                        'warning'
                    );
                    return;
                }
            }

            if($('#check_start_date_check').is(":checked")){
                if(!$('#date_issue_single').val()){
                    swal(
                        json['Vui lòng chọn ngày hết hạn.'],
                        '',
                        'warning'
                    );
                    return;
                }
            } else {
                if(!$('#date_issue').val()){
                    swal(
                        json['Vui lòng chọn ngày hết hạn.'],
                        '',
                        'warning'
                    );
                    return;
                }
            }
            // var moreinfo = '';
            // $('#form-repeat-modal [name="day_of_week[]"]:checked').map(function() {
            //     // return this.value;
            //     moreinfo += '&' + this.name + '=' + this.value;
            // }).get();
            // $('#form-repeat-modal [name="day_of_month[]"]:checked').map(function() {
            //     // return this.value;
            //     moreinfo += '&' + this.name + '=' + this.value;
            // }).get();
            // moreinfo += '&' + "repeat_time" + '=' + $('#form-repeat-modal [name="repeat_time"]').val();
            // moreinfo += '&' + "manage_type_work_id" + '=' + form.find('[name="manage_type_work_id"]:checked').val();
            // console.log(form.find('[name="manage_type_work_id"]:checked').val())
            // let formData = form.serialize();
            $.ajax({
                url: laroute.route('manager-work.add'),
                method: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(res) {
                    if (res.status == 1) {
                        swal(json["Thêm công việc thành công"], "", "success").then(function(result) {
                            clear();
                            $('#autotable').PioTable('refresh');
                        });
                    } else {
                        swal(json["Thêm ticket thất bại"], '', "error");
                    }
                },
                error: function(res) {
                    swal(json['Chỉnh sửa thất bại'], '', "error");
                }
            });
        });
    },
    addClose: function() {
        $.getJSON(laroute.route('translate'), function(json) {
            var form = $('#my_modal form');
            form.validate({
                rules: rules_validate_setting,
                messages: mess_validate_setting,
            });

            if (!form.valid()) {
                return false;
            }

            if($('#is_approve_id_check').is(":checked")){
                if(!$('#approve_id').val()){
                    swal(
                        json['Vui lòng chọn người duyệt.'],
                        '',
                        'warning'
                    );
                    return;
                }
            }

            if($('#check_start_date_check').is(":checked")){
                if(!$('#date_issue_single').val()){
                    swal(
                        json['Vui lòng chọn ngày hết hạn.'],
                        '',
                        'warning'
                    );
                    return;
                }
            } else {
                if(!$('#date_issue').val()){
                    swal(
                        json['Vui lòng chọn ngày hết hạn.'],
                        '',
                        'warning'
                    );
                    return;
                }
            }

            $.ajax({
                url: laroute.route('manager-work.add'),
                method: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(res) {
                    if (res.status == 1) {
                        swal(json["Thêm công việc thành công"], "", "success").then(function(result) {
                            clear();
                        });
                    } else {
                        swal(json["Thêm công việc thất bại"], '', "error");
                    }
                    $('#modalAdd').modal('hide');
                    $('#autotable').PioTable('refresh');
                },
                error: function(res) {
                    swal(json['Thêm công việc thất bại'], '', "error");
                }
            });
        });
    },
    edit: function(id) {
        $.getJSON(laroute.route('translate'), function (json) {
            clear();
            $.ajax({
                url: laroute.route('manager-work.edit'),
                data: {
                    manage_work_id: id
                },
                method: "POST",
                dataType: 'JSON',
                success: function(res) {
                    $('#my_modal').html(res.html);

                    $('#progress100').on('keyup input paste', function () {
                        if (isNaN($(this).val())) {
                            $(this).val(0);
                        }

                        if ($(this).val() > 100) {
                            $(this).val(100);
                        }
                    });

                    $('#my_modal .select2.select2-active').each(function(){
                        let placeholder_value = $(this).find("option:first").text() != undefined ? $(this).find("option:first").text(): "Vui lòng chọn";
                        $(this).select2({
                            placeholder: {
                                id: '',
                                text: placeholder_value
                            },
                        });
                    });

                    $(".date-timepicker").datetimepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        pickerPosition: "bottom-left",
                        format: "dd/mm/yyyy hh:ii",
                        minDate: new Date(),
                        // locale: 'vi'
                    });

                    var date = new Date();
                    $(".daterange-input").daterangepicker({
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
                            format: 'DD/MM/YYYY hh:mm',
                            "applyLabel": json["Đồng ý"],
                            "cancelLabel": json["Thoát"],
                            "customRangeLabel": json["Tùy chọn ngày"],
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
                        ranges: {}
                    }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY hh:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY hh:mm'))
                    });

                    if(res.data.date_start){
                        $('#my_modal [name="date_issue"]').data('daterangepicker').setStartDate(res.data.date_start);
                        $('#my_modal [name="date_issue"]').data('daterangepicker').setEndDate(res.data.date_end);
                    }


                    var Summernote = {
                        init: function() {
                            $.getJSON(laroute.route('translate'), function(json) {
                                $(".summernote").summernote({
                                    height: 208,
                                    lang: 'vi-VN',
                                    placeholder: json['Nhập nội dung...'],
                                    toolbar: [
                                        ['style', ['bold', 'italic', 'underline']],
                                        ['fontsize', ['fontsize']],
                                        ['color', ['color']],
                                        ['para', ['ul', 'ol', 'paragraph']],
                                        // ['insert', ['link', 'picture']]
                                    ]
                                })
                            });
                        }
                    };
                    jQuery(document).ready(function() {
                        Summernote.init();
                    });

                    $('#my_modal').modal('show');
                    // $('#modalEdit [name="manage_work_id_hidden"]').val(data.manage_work_id);
                    // $('#modalEdit [name="manage_work_title"]').val(data.manage_work_title);
                    // if (data.is_approve_id) {
                    //     $('#modalEdit [name=is_approve_id_check]').click();
                    // }
                    // $('#modalEdit [name="time"]').val(data.time);
                    // $('#modalEdit [name="time_type"]').val(data.time_type).change();
                    // $('#modalEdit [name="manage_type_work_id"]').val(data.manage_type_work_id).change();
                    // if (!data.date_start) {
                    //     $('#modalEdit [name="check_start_date_check"]').click();
                    //     $('#modalEdit [name="date_issue_single"]').val(data.date_end);
                    // } else {
                    //     $('#modalEdit [name="date_issue"]').data('daterangepicker').setStartDate(data.date_start);
                    //     $('#modalEdit [name="date_issue"]').data('daterangepicker').setEndDate(data.date_end);
                    //     $('#modalEdit [name="date_issue"]').val(data.date_start + ' - ' + data.date_end);
                    // }
                    // $('#modalEdit [name="processor_id"]').val(data.processor_id).trigger('change');
                    // $('#modalEdit [name="approve_id"]').val(data.approve_id).trigger('change');
                    // $('#modalEdit [name="processor[]"]').val(data.processor).change();
                    // $('#modalEdit [name="parent_id"]').val(data.parent_id).trigger('change');
                    // $('#modalEdit [name="progress"]').val(data.progress);
                    // $('#modalEdit [name="description"]').val(data.description);
                    // $('#modalEdit [name="manage_project_id"]').val(data.manage_project_id).trigger('change');
                    // $('#modalEdit [name="customer_id"]').val(data.customer_id).trigger('change');
                    // $('#modalEdit [name="manage_tag_id[]"]').val(data.manage_tag_id).change();
                    // $('#modalEdit [name="priority"]').val(data.priority).trigger('change');
                    // $('#modalEdit [name="manage_status_id"]').val(data.manage_status_id).trigger('change');
                    if (res.data.remind) {
                        $.each(res.data.remind, function(index, value) {
                            let $data = {
                                date_remind: {
                                    val: value.date_remind,
                                },
                                processor_id_remind: {
                                    val: value.staff_id,
                                },
                                time_remind: {
                                    val: value.time,
                                },
                                time_type_remind: {
                                    val: value.time_type,
                                },
                                description_remind: {
                                    val: value.description,
                                },
                                processor_name: {
                                    val: value.full_name,
                                },
                            };
                            let remind_item = createTagRemind($data);
                            $('#modalEdit .remind-add').append(remind_item);
                        });
                    }
                    // $("#modalRepeatNotification [name=repeat_type_check][value=" + data.repeat_type + "]").click();
                    // $("#modalRepeatNotification [name=repeat_end_check][value=" + data.repeat_end + "]").click();
                    // $("#modalRepeatNotification [name=repeat_end_time]").val(data.repeat_end_time);
                    // $("#modalRepeatNotification [name=repeat_time]").val(data.repeat_time);
                    // $("#modalRepeatNotification [name=repeat_end_full_time]").val(data.repeat_end_full_time);
                    // $("#modalRepeatNotification [name=repeat_end_type][value=" + data.repeat_end_type + "]").trigger('change');
                    // if (data.repeat_type == "weekly") {
                    //     $.each(data.repeat_times, function(index, value) {
                    //         $('#modalRepeatNotification [name="day_of_weeks[]"][value=' + value + ']').click();
                    //     });
                    // } else if (data.repeat_type == "monthly") {
                    //     $.each(data.repeat_times, function(index, value) {
                    //         $('#modalRepeatNotification [name="day_of_months[]"][value=' + value + ']').click();
                    //     });
                    // }
                }
            })
        })
    },
    submitEdit: function() {
        $.getJSON(laroute.route('translate'), function(json) {
            var form = $('#my_modal form');
            form.validate({
                rules: rules_validate_setting,
                messages: mess_validate_setting,
            });
            if (!form.valid()) {
                return false;
            }

            if($('#is_approve_id_check').is(":checked")){
                if(!$('#approve_id').val()){
                    swal(
                        json['Vui lòng chọn người duyệt.'],
                        '',
                        'warning'
                    );
                    return;
                }
            }

            if($('#check_start_date_check').is(":checked")){
                if(!$('#date_issue_single').val()){
                    swal(
                        json['Vui lòng chọn ngày hết hạn.'],
                        '',
                        'warning'
                    );
                    return;
                }
            } else {
                if(!$('#date_issue').val()){
                    swal(
                        json['Vui lòng chọn ngày hết hạn.'],
                        '',
                        'warning'
                    );
                    return;
                }
            }

            $.ajax({
                url: laroute.route('manager-work.submit-edit'),
                method: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                success: function(res) {
                    if (res.status == 1) {
                        swal(json["Chỉnh sửa công việc thành công"], "", "success").then(function(result) {
                            clear();
                        });
                    } else {
                        swal(json["Chỉnh sửa công việc thất bại"], '', "error");
                    }
                    $('#my_modal').modal('hide');
                    $('#autotable').PioTable('refresh');
                },
                error: function(res) {
                    swal(json['Chỉnh sửa công việc thất bại'], '', "error");
                }
            });
        });
    },
    view: function(id) {
        $.ajax({
            url: laroute.route('manager-work.edit'),
            data: {
                manage_work_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                $('#modalView [name="manage_work_id_hidden"]').val(data.manage_work_id);
                $('#modalView [name="manage_work_title"]').val(data.manage_work_title);
                $('#modalView [name="is_approve_id"]').prop('checked', data.is_approve_id);
                $('#modalView [name="time"]').val(data.manage_work_id);
                $('#modalView [name="time_type"]').val(data.time).trigger('change');
                if (!data.date_start) {
                    $('#modalView [name="check_start_date_check"]').click();
                    $('#modalView [name="date_issue_single"]').val(data.date_end);
                } else {
                    $('#modalView [name="date_issue"]').data('daterangepicker').setStartDate(data.date_start);
                    $('#modalView [name="date_issue"]').data('daterangepicker').setEndDate(data.date_end);
                    $('#modalView [name="date_issue"]').val(data.date_start + ' - ' + data.date_end);
                }
                $('#modalView [name="processor_id"]').val(data.processor_id);
                $('#modalView [name="approve_id"]').val(data.approve_id).trigger('change');
                $('#modalView [name="processor[]"]').val(data.processor).change();
                $('#modalView [name="parent_id"]').val(data.parent_id).trigger('change');
                $('#modalView [name="progress"]').val(data.progress);
                $('#modalView [name="description"]').val(data.description);
                $('#modalView [name="manage_project_id"]').val(data.manage_project_id).trigger('change');
                $('#modalView [name="customer_id"]').val(data.customer_id).trigger('change');
                $('#modalView [name="manage_tag_id[]"]').val(data.manage_tag_id).change();
                $('#modalView [name="priority"]').val(data.priority).trigger('change');
                $('#modalView [name="manage_status_id"]').val(data.manage_status_id).trigger('change');
                if (data.remind) {
                    $.each(data.remind, function(index, value) {
                        let $data = {
                            date_remind: {
                                val: value.date_remind,
                            },
                            processor_id_remind: {
                                val: value.staff_id,
                            },
                            time_remind: {
                                val: value.time,
                            },
                            time_type_remind: {
                                val: value.time_type,
                            },
                            description_remind: {
                                val: value.description,
                            },
                            processor_name: {
                                val: value.full_name,
                            },
                        };
                        let remind_item = createTagRemind($data);
                        $('#modalEdit .remind-add').append(remind_item);
                    });
                }
            }
        })
    },
    clear: function() {
        clear();
    },
    refresh: function() {
        $('input[name="search"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $('.daterange-picker').val('');
        $('.daterange-picker').selectpicker('refresh');
        $('[name="is_active"]').val('').trigger('change');
        $(".btn-search").trigger("click");
        $('[type="radio"]:first').prop('checked', true);
        $('#modalRepeatNotification [type="checkbox"]:first').prop('checked', false);
    },
    search: function() {
        $(".btn-search").trigger("click");
    },
    configSearch: function() {
        $('#modal-config').modal();
    },
    saveConfig: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            let search = $('.config_search [name="search[]"]:checked').map(function() {
                return this.value;
            }).get();
            let column = $('.config_column [name="column[]"]:checked').map(function() {
                return this.value;
            }).get();
            $.ajax({
                url: laroute.route('manager-work.save-config'),
                data: {
                    search: search,
                    column: column,
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 1) {
                        swal(
                            json['Cấu hình thành công'],
                            '',
                            'success'
                        );
                        location.reload();
                    } else {
                        swal(
                            json['Cấu hình thất bại'],
                            '',
                            'warning'
                        );
                    }
                }
            });
        });
    },
    createRemind: function() {
        $('#modalRemind').modal('show');
        $('#modalRemind .error').remove();
    },
    RepeatNotification: function() {
        $('#modalRepeatNotification').modal('show');
    },
    addCloseRemind: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            var form_remind = $('#modalRemind .modal-body');
            let $data = {
                date_remind: {
                    val: form_remind.find('[name="date_remind"]').val(),
                    mess: json["Vui lòng chọn thời gian nhắc nhở"],
                    req: true
                },
                processor_id_remind: {
                    val: form_remind.find('[name="processor_id_remind"]').val(),
                    mess: json["Vui lòng chọn nhân viên nhắc nhở"],
                    req: true
                },
                time_remind: {
                    val: form_remind.find('[name="time_remind"]').val(),
                    mess: json["Vui lòng nhập thời gian sau khi nhắc nhở"],
                    req: false
                },
                time_type_remind: {
                    val: form_remind.find('[name="time_type_remind"]').val(),
                    mess: json["Vui lòng chọn loại thời gian nhắc nhở"],
                    req: false
                },
                description_remind: {
                    val: form_remind.find('[name="description_remind"]').val(),
                    mess: json["Vui lòng nhập mô tả"],
                    req: true
                },
                processor_name: {
                    val: form_remind.find('[name="processor_id_remind"] option:selected').text(),
                    mess: "no mess",
                    req: false
                },
            };
            let check_form = true;
            $.each($data, function (index, value) {
                if (value.req == true && value.val == "") {
                    check_form = false;
                    if (!$(form_remind).find('[name="' + index + '"]').closest('.form-group').find('.error').length) {
                        $(form_remind).find('[name="' + index + '"]').closest('.form-group').append('<span class="error">' + value.mess + '</span>');
                    }
                } else {
                    if ($(form_remind).find('[name="' + index + '"]').closest('.form-group').find('.error').length) {
                        $(form_remind).find('[name="' + index + '"]').closest('.form-group').find('.error').remove();
                    }
                }
            });
            if (check_form) {
                let remind_item = createTagRemind($data);
                $('.modal .remind-add').append(remind_item);
                $('#modalRemind').modal('hide');
                swal(json["Thêm nhắc nhở thành công"], "", "success").then(function (result) {
                    $('#modalRemind .error').remove();
                    $('#modalRemind input').val('');
                    $('#modalRemind textarea').val('');
                    $("form.clear-form select").prop("selectedIndex", 0);
                });
            }
        })
    },
    addRemind: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            var form_remind = $('#modalRemind .modal-body');
            let $data = {
                date_remind: {
                    val: form_remind.find('[name="date_remind"]').val(),
                    mess: json["Vui lòng chọn thời gian nhắc nhở"],
                    req: true
                },
                processor_id_remind: {
                    val: form_remind.find('[name="processor_id_remind"]').val(),
                    mess: json["Vui lòng chọn nhân viên nhắc nhở"],
                    req: true
                },
                time_remind: {
                    val: form_remind.find('[name="time_remind"]').val(),
                    mess: json["Vui lòng nhập thời gian sau khi nhắc nhở"],
                    req: false
                },
                time_type_remind: {
                    val: form_remind.find('[name="time_type_remind"]').val(),
                    mess: json["Vui lòng chọn loại thời gian nhắc nhở"],
                    req: false
                },
                description_remind: {
                    val: form_remind.find('[name="description_remind"]').val(),
                    mess: json["Vui lòng nhập mô tả"],
                    req: true
                },
                processor_name: {
                    val: form_remind.find('[name="processor_id_remind"] option:selected').text(),
                    mess: "no mess",
                    req: false
                },
            };
            let check_form = true;
            $.each($data, function (index, value) {
                if (value.req == true && value.val == "") {
                    check_form = false;
                    if (!$(form_remind).find('[name="' + index + '"]').closest('.form-group').find('.error').length) {
                        $(form_remind).find('[name="' + index + '"]').closest('.form-group').append('<span class="error">' + value.mess + '</span>');
                    }
                } else {
                    if ($(form_remind).find('[name="' + index + '"]').closest('.form-group').find('.error').length) {
                        $(form_remind).find('[name="' + index + '"]').closest('.form-group').find('.error').remove();
                    }
                }
            });
            if (check_form) {
                let remind_item = createTagRemind($data);
                $('.modal .remind-add').append(remind_item);
                swal(json["Thêm nhắc nhở thành công"], "", "success").then(function (result) {
                    $('#modalRemind .error').remove();
                    $('#modalRemind input').val('');
                    $('#modalRemind textarea').val('');
                    $("form.clear-form select").prop("selectedIndex", 0);
                });
            }
        })
    },
    appendRepeat: function() {
        let day_of_week = $('#form-repeat-modal [name="day_of_weeks[]"]:checked').map(function() {
            return this.value;
        }).get();
        let day_of_month = $('#form-repeat-modal [name="day_of_months[]"]:checked').map(function() {
            return this.value;
        }).get();
        $('[name="day_of_week[]"]').val(day_of_week).change();
        $('[name="day_of_month[]"]').val(day_of_month).change();
        let repeat_type = $('[name="repeat_type_check"]:checked').val();
        let repeat_end = $('[name="repeat_end_check"]:checked').val();
        $('[name="repeat_type"]').val(repeat_type);
        $('[name="repeat_end"]').val(repeat_end);
        var $append_form_repeat = $('#form-repeat-modal').html();
        $append_form_repeat = $append_form_repeat.replace(/ id="/g, ' id="tmp-');
        console.log($append_form_repeat);
        $('.repeat-html').html($append_form_repeat);
        swal(
            json["Xong"],
            '',
            'success'
        );
        $('#modalRepeatNotification').modal('hide');
    },

    searchPageListStaff : function (page = 1){
        var manage_work_id = $('#manage_work_id_popup_list_staff').val();
        var search = $('#popup-list-staff .search').val();
        $.ajax({
            url: laroute.route('manager-work.kanban-view.search-page-popup-staff'),
            data: {
                manage_work_id : manage_work_id,
                page : page,
                search : search
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.show-list-staff').empty();
                    $('.show-list-staff').append(res.view);
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

    clearSearchPageListStaff : function (){
        $('#popup-list-staff .search').val('');
        ManagerWork.searchPageListStaff();
    }
};

function clear() {
    $('form.clear-form input').val('');
    $('form.clear-form input[type="checkbox"]').prop('checked', false);
    $('[id$="-error"]').remove();
    $("form.clear-form select").prop("selectedIndex", 1);
    $('.remind-add').html('');
    $('.repeat-html').html('');
}

// $('#autotable').PioTable({
//     baseUrl: laroute.route('manager-work.list')
// });

$(document).on('click', '.remove-custom-remind-item', function() {
    $(this).closest('.remind-item').remove();
});

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $(input).closest('.div_avatar').find('[name="manage_type_work_icon"]');
        reader.onload = function(e) {
            $(input).closest('.div_avatar').find('.blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $(input).closest('.div_avatar').find('.getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_manager_work.');

        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function() {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());
        console.log(1)
        if (Math.round(fsize / 1024) <= 10240) {
            $('.error_img').text('');
            $.ajax({
                url: laroute.route("manager-work.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.success == 1) {
                        imageAvatar.val(res.file);
                        console.log(res)
                        $('.delete-img').css('display', 'block');
                    }
                }
            });
        } else {
            $.getJSON(laroute.route('translate'), function(json) {
                $('.error_img').text(json['Hình ảnh vượt quá dung lượng cho phép']);
            });
        }

    }
}
$(".exportToExcel").click(function(e) {
    $.getJSON(laroute.route('translate'), function(json) {
        swal({
            title: json['Thông báo'],
            text: json["Bạn có muốn Export không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: json['Export'],
            cancelButtonText: json['Hủy'],
        }).then(function(result) {
            if (result.value) {
                var table = $('#table-config');
                if (table && table.length) {
                    var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                    $(table).table2excel({
                        exclude: ".noExl",
                        name: "Excel",
                        filename: "danh_sach_cong_viec_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                        fileext: ".xls",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true,
                        preserveColors: preserveColors
                    });
                }
            }
        });
    });
});
$(document).on('click', '.check_start_date_check', function() {

    var id_modal = '#' + $(this).closest('.modal').attr('id');
    console.log(id_modal)
    if ($(this).is(":checked")) {
        $(id_modal).find('.date-multiple').addClass('d-none').attr('disabled', 'disabled');
        $(id_modal).find('.date-single').removeClass('d-none').removeAttr('disabled');
        $(id_modal).find('[name="check_start_date"]').val(1);
        var newKeys = { 'date_issue': 'date_issue_single' };
        rules_validate_setting = renameKeys(rules_validate_setting, newKeys);
    } else {
        $('.edit-date').val('')
        $(id_modal).find('.date-multiple').removeClass('d-none').removeAttr('disabled');
        $(id_modal).find('.date-single').addClass('d-none').attr('disabled', 'disabled');
        var newKeys = { 'date_issue_single': 'date_issue' };
        // $(id_modal).find('[name="check_start_date"]').val(0);
        rules_validate_setting = renameKeys(rules_validate_setting, newKeys);
    }
});
$('form [name="is_approve_id_check"]').click(function() {
    var id_modal = '#' + $(this).closest('.modal').attr('id');
    if ($(this).is(":checked")) {
        $(id_modal).find('[name="is_approve_id"]').val(1);
        var newKeys = { 'approve_id': 'approve_id' };
        rules_validate_setting.approve_id = { required: true };
    } else {
        $(id_modal).find('[name="is_approve_id"]').val(0);
        delete rules_validate_setting.approve_id;
    }
});

$('#modalRepeatNotification [name="repeat_type_check"]').change(function() {
    /*
     - none
     - daily
     - weekly
     - monthly
    */
    if ($(this).val() == "none") {
        $('.day-of-week').addClass('d-none');
        $('.day-of-month').addClass('d-none');
        $('#modalRepeatNotification [name="repeat_end_check"]').prop('disabled', true);
        $('#modalRepeatNotification [name="repeat_time"]').prop('disabled', true);
    } else if ($(this).val() == "daily") {
        $('.day-of-week').addClass('d-none');
        $('.day-of-month').addClass('d-none');
        $('#modalRepeatNotification [name="repeat_end_check"]').prop('disabled', false);
        $('#modalRepeatNotification [name="repeat_time"]').prop('disabled', false);
    } else if ($(this).val() == "weekly") {
        $('.day-of-week').removeClass('d-none');
        $('.day-of-month').addClass('d-none');
        $('#modalRepeatNotification [name="repeat_end_check"]').prop('disabled', false);
        $('#modalRepeatNotification [name="repeat_time"]').prop('disabled', false);
    } else if ($(this).val() == "monthly") {
        $('.day-of-week').addClass('d-none');
        $('.day-of-month').removeClass('d-none');
        $('#modalRepeatNotification [name="repeat_end_check"]').prop('disabled', false);
        $('#modalRepeatNotification [name="repeat_time"]').prop('disabled', false);
    }
});
$('#modalRepeatNotification [name="repeat_end_check"]').click(function() {
    /*
     - none
     - after
     - date
    */
    if ($(this).val() == "none") {
        $('#modalRepeatNotification [name="repeat_end__time"]').prop('disabled', true);
        $('#modalRepeatNotification [name="repeat_end__type"]').prop('disabled', true);
        $('#modalRepeatNotification [name="repeat_end__full_time"]').prop('disabled', true);
    } else if ($(this).val() == "after") {
        $('#modalRepeatNotification [name="repeat_end_time"]').prop('disabled', false);
        $('#modalRepeatNotification [name="repeat_end_type"]').prop('disabled', false);
        $('#modalRepeatNotification [name="repeat_end_full_time"]').prop('disabled', true);
    } else if ($(this).val() == "date") {
        $('#modalRepeatNotification [name="repeat_end_time"]').prop('disabled', true);
        $('#modalRepeatNotification [name="repeat_end_type"]').prop('disabled', true);
        $('#modalRepeatNotification [name="repeat_end_full_time"]').prop('disabled', false);
    }
});
$('.m_selectpicker').selectpicker();
$('select[name="is_active"]').select2();
$(".date-timepicker").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    // minDate: new Date(),
    // locale: 'vi'
});
$(".date-timepicker2").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    startDate: new Date(),
    // minDate: new Date(),
    // locale: 'vi'
});
$(".date-time").datepicker({
    todayHighlight: !0,
    autoclose: false,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy",
    minDate: new Date(),
    locale: 'vi',
});
$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json['Hôm nay']] = [moment(), moment()],
        arrRange[json['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
    $(".daterange-picker").daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        buttonClasses: "m-btn btn",
        applyClass: "btn-primary",
        cancelClass: "btn-danger",
        maxDate: moment().endOf("day"),
        startDate: moment().startOf("day"),
        endDate: moment().add(1, 'days'),
        locale: {
            format: 'DD/MM/YYYY',
            "applyLabel": json["Đồng ý"],
            "cancelLabel": json["Thoát"],
            "customRangeLabel": json["Tùy chọn ngày"],
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
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
    });
    var date = new Date();
    $(".daterange-input").daterangepicker({
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
            format: 'DD/MM/YYYY hh:mm',
            "applyLabel": json["Đồng ý"],
            "cancelLabel": json["Thoát"],
            "customRangeLabel": json["Tùy chọn ngày"],
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
        ranges: {}
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY hh:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY hh:mm'))
    });
});
var Summernote = {
    init: function() {
        $.getJSON(laroute.route('translate'), function(json) {
            $(".summernote").summernote({
                height: 208,
                lang: 'vi-VN',
                placeholder: json['Nhập nội dung...'],
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    // ['insert', ['link', 'picture']]
                ]
            })
        });
    }
};
jQuery(document).ready(function() {
    Summernote.init();

    $('.date-picker-list').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true
    });

});

function renameKeys(obj, newKeys) {
    const keyValues = Object.keys(obj).map(key => {
        const newKey = newKeys[key] || key;
        return {
            [newKey]: obj[key]
        };
    });
    return Object.assign({}, ...keyValues);
}

function createTagRemind($data) {
    var remind_item = $('#remind-item').html();
    remind_item = remind_item.replace(/{date_remind}/g, $data.date_remind.val);
    remind_item = remind_item.replace(/{processor_id_remind}/g, $data.processor_id_remind.val);
    remind_item = remind_item.replace(/{time_remind}/g, $data.time_remind.val);
    remind_item = remind_item.replace(/{time_type_remind}/g, $data.time_type_remind.val);
    remind_item = remind_item.replace(/{description_remind}/g, $data.description_remind.val);
    remind_item = remind_item.replace(/{processor_name}/g, $data.processor_name.val);
    return remind_item;
}
$.validator.addMethod('minStrict', function (value, el, param) {
    return value > param;
});

var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var WorkChild = {
    // approveStaff : function () {
    //     var value = $('#is_approve_id:checked').val();
    //     if (value == 1){
    //         $('.black_title_not_approve').hide();
    //         $('.black_title_approve').show();
    //         var selectStaff = $('#approve_id_select').val();
    //         var idStaff = $('#id_staff').val();
    //
    //         if(selectStaff == ''){
    //             $('#approve_id_select').val(idStaff).trigger('change');
    //         }
    //
    //     }else {
    //         $('.black_title_approve').hide();
    //         $('.black_title_not_approve').show();
    //     }
    // },
    // showPopup : function(manage_work_id = null){
    //     $.ajax({
    //         url: laroute.route('manager-work.detail.show-popup-work-child'),
    //         method: "POST",
    //         data: {
    //             manage_work_id : manage_work_id
    //         },
    //         success: function (res) {
    //             if (res.error == false){
    //                 $('#append-add-work').empty();
    //                 $('#append-add-work').append(res.view);
    //                 // $('.select2-active').select2({
    //                 //     dropdownParent: $(".modal")
    //                 // });
    //                 $('select:not(.normal)').each(function () {
    //                     $(this).select2({
    //                         dropdownParent: $(this).parent()
    //                     });
    //                 });
    //                 $(".time-input").timepicker({
    //                     todayHighlight: !0,
    //                     autoclose: !0,
    //                     pickerPosition: "bottom-left",
    //                     // format: "dd/mm/yyyy hh:ii",
    //                     format: "HH:ii",
    //                     defaultTime: "",
    //                     showMeridian: false,
    //                     minuteStep: 5,
    //                     snapToStep: !0,
    //                     // startDate : new Date()
    //                     // locale: 'vi'
    //                 });
    //
    //                 $(".daterange-input").datepicker({
    //                     todayHighlight: !0,
    //                     autoclose: !0,
    //                     pickerPosition: "bottom-left",
    //                     // format: "dd/mm/yyyy hh:ii",
    //                     format: "dd/mm/yyyy",
    //                     // startDate : new Date()
    //                     // locale: 'vi'
    //                 });
    //
    //                 $(".date-timepicker").datetimepicker({
    //                     todayHighlight: !0,
    //                     autoclose: !0,
    //                     pickerPosition: "bottom-left",
    //                     format: "dd/mm/yyyy hh:ii",
    //                     // format: "dd/mm/yyyy",
    //                     startDate : new Date()
    //                     // locale: 'vi'
    //                 });
    //
    //                 $(".date-timepicker-repeat").datepicker({
    //                     todayHighlight: !0,
    //                     autoclose: !0,
    //                     pickerPosition: "bottom-left",
    //                     format: "dd/mm/yyyy",
    //                     // startDate : new Date()
    //                     // locale: 'vi'
    //                 });
    //
    //                 $("#repeat_time").timepicker({
    //                     minuteStep: 15,
    //                     defaultTime: "",
    //                     showMeridian: !1,
    //                     snapToStep: !0,
    //                 });
    //
    //                 AutoNumeric.multiple('.input-mask,.input-mask-remind',{
    //                     currencySymbol : '',
    //                     decimalCharacter : '.',
    //                     digitGroupSeparator : ',',
    //                     decimalPlaces: 0,
    //                     minimumValue: 0,
    //                 });
    //
    //                 AutoNumeric.multiple('.progress_input',{
    //                     currencySymbol : '',
    //                     decimalCharacter : '.',
    //                     digitGroupSeparator : ',',
    //                     decimalPlaces: 0,
    //                     minimumValue: 0,
    //                     maximumValue: 100,
    //                 });
    //
    //                 AutoNumeric.multiple('#repeat_end_time',{
    //                     currencySymbol : '',
    //                     decimalCharacter : '.',
    //                     digitGroupSeparator : ',',
    //                     decimalPlaces: 0,
    //                     minimumValue: 0,
    //                 });
    //
    //                 $('.summernote').summernote({
    //                     placeholder: '',
    //                     tabsize: 2,
    //                     height: 200,
    //                     toolbar: [
    //                         ['style', ['style']],
    //                         ['font', ['bold', 'underline', 'clear']],
    //                         ['fontname', ['fontname', 'fontsize']],
    //                         ['color', ['color']],
    //                         ['para', ['ul', 'ol', 'paragraph']],
    //                         ['table', ['table']],
    //                         ['insert', ['link', 'picture']],
    //                     ],
    //                     callbacks: {
    //                         onImageUpload: function(files) {
    //                             for(let i=0; i < files.length; i++) {
    //                                 uploadImgCk(files[i]);
    //                             }
    //                         }
    //                     },
    //                 });
    //
    //                 WorkAll.changeCustomer(manage_work_id);
    //
    //                 $('#popup-work').modal('show');
    //                 $('#popup-work').on('hidden.bs.modal', function (e) {
    //                     location.reload();
    //                 })
    //             } else {
    //                 swal.fire(res.message, '', 'error');
    //             }
    //         }
    //     });
    // },
    // saveWork : function (createNew = 0) {
    //     var form = $('#form-work');
    //     form.validate({
    //         rules: {
    //             manage_work_title: {
    //                 required: true,
    //                 maxlength : 255
    //             },
    //             manage_type_work_id:{
    //                 required: true,
    //             },
    //             // date_start : {
    //             //     required: true,
    //             // },
    //             date_end : {
    //                 required: true,
    //             },
    //             processor_id : {
    //                 required: true,
    //             },
    //             priority : {
    //                 required: true,
    //             },
    //             approve_id:{
    //                 required : function () {
    //                     if ($('#is_approve_id').is(':checked')){
    //                         return true;
    //                     }
    //                     return false;
    //                 }
    //             }
    //
    //         },
    //         messages: {
    //             manage_work_title: {
    //                 required: "Vui lòng nhập tiêu đề",
    //                 maxlength : "Tiêu đề vượt quá 255 ký tự"
    //             },
    //             manage_type_work_id:{
    //                 required: "Vui lòng chọn loại công việc",
    //             },
    //             // date_start : {
    //             //     required: "Vui lòng chọn ngày bắt đầu",
    //             // },
    //             date_end : {
    //                 required: "Vui lòng chọn ngày kết thúc",
    //             },
    //             processor_id : {
    //                 required: "Vui lòng chọn nhân viên thực hiện",
    //             },
    //             priority : {
    //                 required: "Vui lòng chọn mức độ ưu tiên",
    //             },
    //             approve_id : {
    //                 required: "Vui lòng chọn nhân viên duyệt",
    //             },
    //         },
    //     });
    //
    //     if (!form.valid()) {
    //         return false;
    //     }
    //
    //
    //     $.ajax({
    //         url: laroute.route('manager-work.detail.save-child-work'),
    //         data: $('#form-work').serialize(),
    //         method: "POST",
    //         dataType: "JSON",
    //         success: function(res) {
    //             if (res.error == false) {
    //                 swal(res.message,'','success').then(function () {
    //                     if (createNew == 1){
    //                         WorkChild.showPopup();
    //                     } else {
    //                         location.reload();
    //                     }
    //                 });
    //             } else {
    //                 swal(res.message,'','error');
    //             }
    //         },
    //         error: function (res) {
    //             var mess_error = '';
    //             $.map(res.responseJSON.errors, function (a) {
    //                 mess_error = mess_error.concat(a + '<br/>');
    //             });
    //             swal('', mess_error, "error");
    //         }
    //     });
    // },
    //
    // selectWeekly: function (day) {
    //
    //     if ($('label.weekly-select-'+day).hasClass('weekly-select-active')){
    //         $('label.weekly-select-'+day).removeClass('weekly-select-active');
    //         $('input.weekly-select-'+day).val('');
    //     } else {
    //         $('label.weekly-select-'+day).addClass('weekly-select-active');
    //         $('input.weekly-select-'+day).val(day);
    //     }
    // },
    //
    // selectMonthly: function (day) {
    //
    //     if ($('label.monthly-select-'+day).hasClass('weekly-select-active')){
    //         $('label.monthly-select-'+day).removeClass('weekly-select-active');
    //         $('input.monthly-select-'+day).val('');
    //     } else {
    //         $('label.monthly-select-'+day).addClass('weekly-select-active');
    //         $('input.monthly-select-'+day).val(day);
    //     }
    // },
    //
    // changeRepeat : function() {
    //     var value = $('.repeat_type:checked').val();
    //     $('.block_weekly').hide();
    //     $('.block_monthly').hide();
    //     $('.block_monthly input').val('');
    //     $('.block_weekly input').val('');
    //     $('.weekly-select').removeClass('weekly-select-active');
    //     $('.monthly-select').removeClass('weekly-select-active');
    //     // $('.weekly-select:first-child').addClass('weekly-select-active');
    //     // $('#manage_repeat_time_weekly').val(0);
    //     // $('#manage_repeat_time_monthly').val('').trigger('change');
    //
    //     $('.block_'+value).show();
    // },
    // changeRepeatEnd : function () {
    //     $('.disabled_block').prop('disabled',true);
    //     var value = $('.repeat_end:checked').val();
    //
    //     if (value == 'after'){
    //         $('.repeat_end_type').prop('disabled',false);
    //         $('.repeat_end_time').prop('disabled',false);
    //     } else if(value == 'date'){
    //         $('.repeat_end_full_time').prop('disabled',false);
    //     }
    //
    // },

    showPopup : function(manage_work_id = null){
        $.ajax({
            url: laroute.route('manager-work.detail.show-popup-work-child'),
            method: "POST",
            data: {
                manage_work_id : manage_work_id
            },
            success: function (res) {
                if (res.error == false){
                    $('#append-add-work').empty();
                    $('#append-add-work').append(res.view);
                    // $('.select2-active').select2({
                    //     dropdownParent: $(this).parent().find
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
                    $('#popup-work').modal('show');
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
                    data: $('#form-work').serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.error == true){
                            swal({
                                title: jsonLang['Thông báo'],
                                text: res.title,
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: jsonLang['Lưu'],
                                cancelButtonText: jsonLang['Hủy']

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
                            location.reload();
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

    //Show popup chọn nhân viên hỗ trợ
    showPopStaff: function () {
        $.ajax({
            url: laroute.route('manager-work.show-pop-staff-support'),
            method: 'POST',
            dataType: 'JSON',
            data: {

            },
            success: function (res) {
                $('#my-modal-staff').html(res.html);
                $('#modal-add-staff').modal('show');

                $(".m_selectpicker").select2({
                    width: "100%"
                });

                $('#autotable-staff-pop').PioTable({
                    baseUrl: laroute.route('manager-work.list-staff-support')
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
                url: laroute.route('manager-work.choose-staff-support'),
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
                url: laroute.route('manager-work.un-choose-staff-support'),
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
                url: laroute.route('manager-work.choose-staff-support'),
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
                url: laroute.route('manager-work.un-choose-staff-support'),
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
            url: laroute.route('manager-work.submit-choose-staff-support'),
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

                    $.each(res.data, function (k, v) {
                        let tpl = $('#staff-support-tpl').html();
                        tpl = tpl.replace(/{staff_name}/g, v.full_name);
                        tpl = tpl.replace(/{staff_id}/g, v.staff_id);
                        $('.div_staff_support').append(tpl);
                    });
                } else {
                    //Báo lỗi
                    swal(res.message, '', "error");
                }
            }
        });
    },

    //Xoá nhân viên hỗ trợ đã chọn
    removeStaffSupport: function (obj, staffId) {
        $.ajax({
            url: laroute.route('manager-work.remove-staff-support'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: staffId
            },
            success: function (res) {
                $(obj).closest('.span_parent_close').remove();
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
                } else {
                    swal(res.message,'','error');
                }
            },
        });
    },

    changeCustomerList : function(customer_id = null){
        var typeCustomer = $('#manage_work_customer_type_list').val();

        $.ajax({
            url: laroute.route('manager-work.detail.change-customer'),
            data: {
                typeCustomer : typeCustomer,
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('#select_customer_id').empty();
                    $('#select_customer_id').append(res.view);
                    if (customer_id != null){
                        $('#select_customer_id').val(customer_id).trigger('change');
                    }

                    $('#select_customer_id').select2();
                } else {
                    swal(res.message,'','error');
                }
            },
        });
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
