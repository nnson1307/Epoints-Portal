var stt = 0;

var view = {
    _init: function (view) {
        $(document).ready(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $("#start_day_shift, #end_day_shift").datepicker({
                    language: 'en',
                    orientation: "bottom left", todayHighlight: !0,
                    format: 'dd/mm/yyyy',
                });

                $(".m_selectpicker").select2({
                    width: "100%"
                });

                $('.branch_id').select2({
                    placeholder: json['Chọn vị trí làm việc']
                });

                $('.shift_id').select2({
                    placeholder: json['Chọn ca làm']
                });
            });

            $('#autotable-staff').PioTable({
                baseUrl: laroute.route('shift.work-schedule.list-staff')
            });
        });
    },
    addObjectShift: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var continute = true;

            //check các trường dữ liệu rỗng thì báo lỗi
            $.each($('.list_shift').find(".object_shift"), function () {
                var branchId = $(this).find($('.branch_id')).val();
                var shiftId = $(this).find($('.shift_id')).val();

                if (branchId == '') {
                    $(this).find($('.error_branch_id')).text(json['Hãy chọn vị trí làm việc']);
                    continute = false;
                } else {
                    $(this).find($('.error_branch_id')).text('');
                }

                if (shiftId == '') {
                    $(this).find($('.error_shift_id')).text(json['Hãy chọn ca làm việc']);
                    continute = false;
                } else {
                    $(this).find($('.error_shift_id')).text('');
                }
            });

            if (continute == true) {
                //append div shift
                var tpl = $('#shift-tpl').html();
                // tpl = tpl.replace(/{stt}/g, stt);
                $('.list_shift').append(tpl);

                $('.branch_id').select2({
                    placeholder: json['Chọn vị trí làm việc']
                });

                $('.shift_id').select2({
                    placeholder: json['Chọn ca làm']
                });
            }
        });
    },
    removeObjectShift: function (obj) {
        $(obj).closest('.object_shift').remove();
    },
    showModalStaff: function () {
        $.ajax({
            url: laroute.route('shift.work-schedule.show-pop-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-staff').modal('show');

                $(".m_selectpicker").select2({
                    width: '100%'
                });

                $('#autotable-staff-pop').PioTable({
                    baseUrl: laroute.route('shift.work-schedule.list-staff-pop')
                });
            }
        });
    },
    chooseStaff: function (obj) {
        if ($(obj).is(":checked")) {

            var arrCheck = [];

            arrCheck.push({
                staff_id: $(obj).parents('label').find('.staff_id').val()
            });

            $.ajax({
                url: laroute.route('shift.work-schedule.choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrCheck: arrCheck
                }
            });
        } else {
            var arrUnCheck = [];

            arrUnCheck.push({
                staff_id: $(obj).parents('label').find('.staff_id').val()
            });

            $.ajax({
                url: laroute.route('shift.work-schedule.un-choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrUnCheck: arrUnCheck
                }
            });
        }
    },
    chooseAllStaff: function (obj) {
        if ($(obj).is(':checked')) {
            $('.check_one').prop('checked', true);

            var arrCheck = [];

            $('.check_one').each(function () {
                arrCheck.push({
                    staff_id: $(this).parents('label').find('.staff_id').val(),
                });
            });

            $.ajax({
                url: laroute.route('shift.work-schedule.choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrCheck: arrCheck
                }
            });
        } else {
            $('.check_one').prop('checked', false);

            var arrUnCheck = [];

            $('.check_one').each(function () {
                arrUnCheck.push({
                    staff_id: $(this).parents('label').find('.staff_id').val(),
                });
            });

            $.ajax({
                url: laroute.route('shift.work-schedule.un-choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrUnCheck: arrUnCheck
                }
            });
        }
    },
    submitChooseStaff: function () {
        $.ajax({
            url: laroute.route('shift.work-schedule.submit-choose-staff'),
            dataType: 'JSON',
            method: 'POST',
            success: function (res) {
                $('#modal-staff').modal('hide');

                $('.btn-search').trigger('click');
            }
        });
    },
    removeStaffTr: function (obj, staffId) {
        $.ajax({
            url: laroute.route('shift.work-schedule.remove-staff'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                staff_id: staffId
            },
            success: function (res) {
                $(obj).closest('.tr_staff').remove();

                $('#autotable-staff').PioTable('refresh');
            }
        });
    },
    changeShift: function (obj) {

        $.ajax({
            url: laroute.route('shift.work-schedule.choose-shift'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                shift_id: $(obj).val()
            },
            success: function (res) {
                if (res.info.is_monday == 1) {
                    $(obj).closest('.object_shift').find($('.is_monday')).prop('checked', true);
                } else {
                    $(obj).closest('.object_shift').find($('.is_monday')).prop('checked', false);
                }

                if (res.info.is_tuesday == 1) {
                    $(obj).closest('.object_shift').find($('.is_tuesday')).prop('checked', true);
                } else {
                    $(obj).closest('.object_shift').find($('.is_tuesday')).prop('checked', false);
                }

                if (res.info.is_wednesday == 1) {
                    $(obj).closest('.object_shift').find($('.is_wednesday')).prop('checked', true);
                } else {
                    $(obj).closest('.object_shift').find($('.is_wednesday')).prop('checked', false);
                }

                if (res.info.is_thursday == 1) {
                    $(obj).closest('.object_shift').find($('.is_thursday')).prop('checked', true);
                } else {
                    $(obj).closest('.object_shift').find($('.is_thursday')).prop('checked', false);
                }

                if (res.info.is_friday == 1) {
                    $(obj).closest('.object_shift').find($('.is_friday')).prop('checked', true);
                } else {
                    $(obj).closest('.object_shift').find($('.is_friday')).prop('checked', false);
                }

                if (res.info.is_saturday == 1) {
                    $(obj).closest('.object_shift').find($('.is_saturday')).prop('checked', true);
                } else {
                    $(obj).closest('.object_shift').find($('.is_saturday')).prop('checked', false);
                }

                if (res.info.is_sunday == 1) {
                    $(obj).closest('.object_shift').find($('.is_sunday')).prop('checked', true);
                } else {
                    $(obj).closest('.object_shift').find($('.is_sunday')).prop('checked', false);
                }

                $(obj).closest('.object_shift').find($('.branch_id')).empty();

                $.map(res.branchMap, function (v) {
                    $(obj).closest('.object_shift').find($('.branch_id')).append('<option value="' + v.branch_id +'">' + v.branch_name +'</option>')
                });
            }
        });
    }
};

var create = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-register');

            form.validate({
                rules: {
                    work_schedule_name: {
                        required: true,
                        maxlength: 190
                    },
                    start_day_shift: {
                        required: true,
                    },
                    end_day_shift: {
                        required: true
                    }
                },
                messages: {
                    work_schedule_name: {
                        required: json['Hãy nhập tên lịch làm việc'],
                        maxlength: json['Tên lịch làm việc tối đa 190 kí tự']
                    },
                    start_day_shift: {
                        required: json['Hãy chọn ngày bắt đầu'],
                    },
                    end_day_shift: {
                        required: json['Hãy chọn ngày kết thúc']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var continute = true;

            var listShift = [];

            $.each($('.list_shift').find(".object_shift"), function () {
                var branchId = $(this).find($('.branch_id')).val();
                var shiftId = $(this).find($('.shift_id')).val();
                var isMonday = 0;
                var isTuesday = 0;
                var isWednesday = 0;
                var isThursday = 0;
                var isFriday = 0;
                var isSaturday = 0;
                var isSunday = 0;
                var isOt = 0;

                if (branchId == '') {
                    $(this).find($('.error_branch_id')).text(json['Hãy chọn vị trí làm việc']);
                    continute = false;
                } else {
                    $(this).find($('.error_branch_id')).text('');
                }

                if (shiftId == '') {
                    $(this).find($('.error_shift_id')).text(json['Hãy chọn ca làm việc']);
                    continute = false;
                } else {
                    $(this).find($('.error_shift_id')).text('');
                }

                if ($(this).find($('.is_monday')).is(':checked')) {
                    isMonday = 1;
                }

                if ($(this).find($('.is_tuesday')).is(':checked')) {
                    isTuesday = 1;
                }

                if ($(this).find($('.is_wednesday')).is(':checked')) {
                    isWednesday = 1;
                }

                if ($(this).find($('.is_thursday')).is(':checked')) {
                    isThursday = 1;
                }

                if ($(this).find($('.is_friday')).is(':checked')) {
                    isFriday = 1;
                }

                if ($(this).find($('.is_saturday')).is(':checked')) {
                    isSaturday = 1;
                }

                if ($(this).find($('.is_sunday')).is(':checked')) {
                    isSunday = 1;
                }

                if ($(this).find($('.is_ot')).is(':checked')) {
                    isOt = 1;
                }

                listShift.push({
                    branch_id: branchId,
                    shift_id: shiftId,
                    is_monday: isMonday,
                    is_tuesday: isTuesday,
                    is_wednesday: isWednesday,
                    is_thursday: isThursday,
                    is_friday: isFriday,
                    is_saturday: isSaturday,
                    is_sunday: isSunday,
                    is_ot: isOt
                });
            });

            if (listShift.length <= 0) {
                swal(json['Vui lòng chọn ca làm việc'], '', 'error');
                return false;
            }

            if (continute == true) {
                $.ajax({
                    url: laroute.route('shift.work-schedule.store'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        work_schedule_name: $('#work_schedule_name').val(),
                        start_day_shift: $('#start_day_shift').val(),
                        end_day_shift: $('#end_day_shift').val(),
                        repeat: $('input[name=repeat]:checked').val(),
                        note: $('#note').val(),
                        listShift: listShift
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('shift.work-schedule');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('shift.work-schedule');
                                }
                            });
                        } else {
                            swal(res.message, '', "error");
                        }
                    },
                    error: function (res) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Phân ca thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
};

var edit = {
    save: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    work_schedule_name: {
                        required: true,
                        maxlength: 190
                    },
                    start_day_shift: {
                        required: true,
                    },
                    end_day_shift: {
                        required: true
                    }
                },
                messages: {
                    work_schedule_name: {
                        required: json['Hãy nhập tên lịch làm việc'],
                        maxlength: json['Tên lịch làm việc tối đa 190 kí tự']
                    },
                    start_day_shift: {
                        required: json['Hãy chọn ngày bắt đầu'],
                    },
                    end_day_shift: {
                        required: json['Hãy chọn ngày kết thúc']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }

            var continute = true;

            var listShift = [];

            $.each($('.list_shift').find(".object_shift"), function () {
                var branchId = $(this).find($('.branch_id')).val();
                var shiftId = $(this).find($('.shift_id')).val();
                var isMonday = 0;
                var isTuesday = 0;
                var isWednesday = 0;
                var isThursday = 0;
                var isFriday = 0;
                var isSaturday = 0;
                var isSunday = 0;
                var isOt = 0;

                if (branchId == '') {
                    $(this).find($('.error_branch_id')).text(json['Hãy chọn vị trí làm việc']);
                    continute = false;
                } else {
                    $(this).find($('.error_branch_id')).text('');
                }

                if (shiftId == '') {
                    $(this).find($('.error_shift_id')).text(json['Hãy chọn ca làm việc']);
                    continute = false;
                } else {
                    $(this).find($('.error_shift_id')).text('');
                }

                if ($(this).find($('.is_monday')).is(':checked')) {
                    isMonday = 1;
                }

                if ($(this).find($('.is_tuesday')).is(':checked')) {
                    isTuesday = 1;
                }

                if ($(this).find($('.is_wednesday')).is(':checked')) {
                    isWednesday = 1;
                }

                if ($(this).find($('.is_thursday')).is(':checked')) {
                    isThursday = 1;
                }

                if ($(this).find($('.is_friday')).is(':checked')) {
                    isFriday = 1;
                }

                if ($(this).find($('.is_saturday')).is(':checked')) {
                    isSaturday = 1;
                }

                if ($(this).find($('.is_sunday')).is(':checked')) {
                    isSunday = 1;
                }

                if ($(this).find($('.is_ot')).is(':checked')) {
                    isOt = 1;
                }

                listShift.push({
                    branch_id: branchId,
                    shift_id: shiftId,
                    is_monday: isMonday,
                    is_tuesday: isTuesday,
                    is_wednesday: isWednesday,
                    is_thursday: isThursday,
                    is_friday: isFriday,
                    is_saturday: isSaturday,
                    is_sunday: isSunday,
                    is_ot: isOt
                });
            });

            if (listShift.length <= 0) {
                swal(json['Vui lòng chọn ca làm việc'], '', 'error');
                return false;
            }

            if (continute == true) {
                $.ajax({
                    url: laroute.route('shift.work-schedule.update'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        work_schedule_id: id,
                        work_schedule_name: $('#work_schedule_name').val(),
                        start_day_shift: $('#start_day_shift').val(),
                        end_day_shift: $('#end_day_shift').val(),
                        repeat: $('input[name=repeat]:checked').val(),
                        note: $('#note').val(),
                        listShift: listShift
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal(res.message, "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href = laroute.route('shift.work-schedule');
                                }
                                if (result.value == true) {
                                    window.location.href = laroute.route('shift.work-schedule');
                                }
                            });
                        } else {
                            swal(res.message, '', "error");
                        }
                    },
                    error: function (res) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function (a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                    }
                });
            }
        });
    }
};