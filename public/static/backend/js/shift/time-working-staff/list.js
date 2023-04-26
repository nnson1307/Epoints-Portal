var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var list = {
    changeDateType: function (obj) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.get-select-week'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                years: $('#years').val(),
                date_type : $('#date_type').val()
            },
            success: function (res) {
                $('#date_object').empty();
                $('#date_object').append(res.html);
                $('.btn-search').trigger('click');
            }
        });
        // if ($(obj).val() == 'by_week') {
        //     $('#date_object').empty();

        //     var tpl = $('#option-week-tpl').html();
        //     $('#date_object').append(tpl);
        // } else {
        //     $('#date_object').empty();

        //     var tpl = $('#option-month-tpl').html();
        //     $('#date_object').append(tpl);
        // }
    }
};

var index = {
    showModalShift: function (staffId, workingDay, view, focusShiftId = null, isHoliday) {
        if (isHoliday == 1) {
            swal({
                title: jsonLang['Thông báo'],
                text: jsonLang["Ca làm việc rơi vào ngày lễ bạn có muốn thêm ca làm việc cho nhân viên?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: jsonLang['Xác nhận'],
                cancelButtonText: jsonLang['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('shift.time-working-staff.show-pop-shift'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            staff_id: staffId,
                            working_day: workingDay,
                            view: view,
                            focus_shift_id: focusShiftId
                        },
                        success: function (res) {
                            $('#my-modal').html(res.html);
                            $('#modal-add-shift').modal('show');
                            $('.modal-backdrop').hide();
                            $('#autotable-shift-pop').PioTable({
                                baseUrl: laroute.route('shift.time-working-staff.list-shift')
                            });

                            $('#autotable-shift-pop').find('.btn-search').trigger('click');
                        }
                    });
                }
            });
        } else {
            $.ajax({
                url: laroute.route('shift.time-working-staff.show-pop-shift'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    staff_id: staffId,
                    working_day: workingDay,
                    view: view,
                    focus_shift_id: focusShiftId
                },
                success: function (res) {
                    $('#my-modal').html(res.html);
                    $('#modal-add-shift').modal('show');
                    $('.modal-backdrop').hide();
                    $('#autotable-shift-pop').PioTable({
                        baseUrl: laroute.route('shift.time-working-staff.list-shift')
                    });

                    $('#autotable-shift-pop').find('.btn-search').trigger('click');
                }
            });
        }
    },
    chooseShift: function (obj) {
        if ($(obj).is(':checked')) {
            $(obj).closest('.tr_shift').find($('.check_ot')).prop('disabled', false);
            $(obj).closest('.tr_shift').find($('.branch_id')).prop('disabled', false);

            var isOt = 0;

            if ($(obj).closest('.tr_shift').find($('.check_ot')).is(':checked')) {
                isOt = 1;
            }

            $.ajax({
                url: laroute.route('shift.time-working-staff.choose-shift'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    shift_id: $(obj).closest('.tr_shift').find($('.shift_id')).val(),
                    branch_id: $(obj).closest('.tr_shift').find($('.branch_id')).val(),
                    is_ot: isOt,
                    overtime_type: $(obj).closest('.tr_shift').find($('.overtime_type')).val(),
                    timekeeping_coefficient: $(obj).closest('.tr_shift').find($('.timekeeping_coefficient')).val().replace(new RegExp('\\,', 'g'), '')
                }
            });
        } else {
            $(obj).closest('.tr_shift').find($('.check_ot')).prop('disabled', true);
            $(obj).closest('.tr_shift').find($('.branch_id')).prop('disabled', true);
            $(obj).closest('.tr_shift').find($('.overtime_type')).prop('disabled', true);
            $(obj).closest('.tr_shift').find($('.timekeeping_coefficient')).prop('disabled', true);

            $(obj).closest('.tr_shift').find($('.check_ot')).prop('checked', false);


            $.ajax({
                url: laroute.route('shift.time-working-staff.un-choose-shift'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    shift_id: $(obj).closest('.tr_shift').find($('.shift_id')).val()
                }
            });
        }
    },
    updateObjectShift: function (obj) {
        var isOt = 0;

        var is_disable_ot_type = $(obj).closest('.tr_shift').find($('.is_disable_ot_type')).val();

        if ($(obj).closest('.tr_shift').find($('.check_ot')).is(':checked')) {
            isOt = 1;

            if (is_disable_ot_type == 0) {
                $(obj).closest('.tr_shift').find($('.overtime_type')).prop('disabled', false);
            }

            $(obj).closest('.tr_shift').find($('.timekeeping_coefficient')).prop('disabled', false);
        } else {
            $(obj).closest('.tr_shift').find($('.overtime_type')).prop('disabled', true);
            $(obj).closest('.tr_shift').find($('.timekeeping_coefficient')).prop('disabled', true);
        }

        //Cập nhật các giá trị của ca đã chọn
        $.ajax({
            url: laroute.route('shift.time-working-staff.update-object-shift'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                shift_id: $(obj).closest('.tr_shift').find($('.shift_id')).val(),
                branch_id: $(obj).closest('.tr_shift').find($('.branch_id')).val(),
                is_ot: isOt,
                overtime_type: $(obj).closest('.tr_shift').find($('.overtime_type')).val(),
                timekeeping_coefficient: $(obj).closest('.tr_shift').find($('.timekeeping_coefficient')).val().replace(new RegExp('\\,', 'g'), '')
            }
        });
    },
    submitAddShift: function (staffId, workingDay, view) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.add-shift'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: staffId,
                working_day: workingDay,
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-add-shift').modal('hide');

                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');

                                    index.listMyShift(staffId, workingDay);

                                    break;
                            }
                        }
                        if (result.value == true) {
                            $('#modal-add-shift').modal('hide');

                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');

                                    index.listMyShift(staffId, workingDay);

                                    break;
                            }
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    paidOrUnPaidLeave: function (timeWorkingStaffId, type, view) {
        //Nghỉ có lương
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn điều chỉnh không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Có'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('shift.time-working-staff.paid-or-unpaid-leave'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            time_working_staff_id: timeWorkingStaffId,
                            type: type
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");

                                switch (view) {
                                    case 'list':
                                        $('#autotable').PioTable('refresh');
                                        break;
                                    case 'my_shift':
                                        $('#autotable').PioTable('refresh');

                                        index.listMyShift(res.info.staff_id, res.info.working_day);

                                        break;
                                    case 'salary-detail':
                                        $(".frmFilter").submit();
                                        break;
                                }
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    remove: function (timeWorkingStaffId, view) {
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
                        url: laroute.route('shift.time-working-staff.remove-shift'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            time_working_staff_id: timeWorkingStaffId,
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                switch (view) {
                                    case 'list':
                                        $('#autotable').PioTable('refresh');
                                        break;
                                    case 'my_shift':
                                        $('#autotable').PioTable('refresh');

                                        index.listMyShift(res.info.staff_id, res.info.working_day);

                                        break;
                                }
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    isWork: function (timeWorkingStaffId, view) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn điều chỉnh không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Có'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('shift.time-working-staff.is-work'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            time_working_staff_id: timeWorkingStaffId,
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");

                                switch (view) {
                                    case 'list':
                                        $('#autotable').PioTable('refresh');
                                        break;
                                    case 'my_shift':
                                        $('#autotable').PioTable('refresh');

                                        index.listMyShift(res.info.staff_id, res.info.working_day);

                                        break;
                                }
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    showModalMyShift: function (staffId, workingDay) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.show-pop-my-shift'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: staffId,
                working_day: workingDay
            },
            success: function (res) {
                $('#my-modal-my-shift').html(res.html);
                $('#modal-my-shift').modal('show');
                $('.modal-backdrop').hide();
            }
        });
    },
    listMyShift: function (staffId, workingDay) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.list-my-shift'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: staffId,
                working_day: workingDay,
            },
            success: function (res) {
                $('.div_my_shift').html(res.html);
            }
        });
    },
    removeStaffByShift: function (staffId, shiftId, workingDay, workingEndDay) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không nhân viên này không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('shift.time-working-staff.remove-staff-by-shift'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            staff_id: staffId,
                            shift_id: shiftId,
                            start_time: workingDay,
                            end_time: workingEndDay
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");

                                $('.btn-search').trigger('click');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    showModalStaff: function (staffId, shiftId, workingDay, workingEndDay) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.show-pop-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: staffId,
                shift_id: shiftId,
                start_time: workingDay,
                end_time: workingEndDay
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-add-staff').modal('show');

                // $(".m_selectpicker").select2({
                //     width: "100%",
                // });

                $('#autotable-staff-pop').PioTable({
                    baseUrl: laroute.route('shift.time-working-staff.list-staff')
                });

                $('#autotable-staff-pop').find('.btn-search').trigger('click');
            }
        });
    },
    chooseStaff: function (obj) {
        if ($(obj).is(':checked')) {
            $(obj).closest('.tr_shift').find($('.check_ot')).prop('disabled', false);
            $(obj).closest('.tr_shift').find($('.branch_id')).prop('disabled', false);

            var isOt = 0;

            if ($(obj).closest('.tr_shift').find($('.check_ot')).is(':checked')) {
                isOt = 1;
            }

            $.ajax({
                url: laroute.route('shift.time-working-staff.choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    staff_id: $(obj).closest('.tr_shift').find($('.staff_id')).val(),
                    is_ot: isOt,
                    branch_id: $(obj).closest('.tr_shift').find($('.branch_id')).val()
                }
            });
        } else {
            $(obj).closest('.tr_shift').find($('.check_ot')).prop('disabled', true);
            $(obj).closest('.tr_shift').find($('.check_ot')).prop('checked', false);
            $(obj).closest('.tr_shift').find($('.branch_id')).prop('disabled', true);

            $.ajax({
                url: laroute.route('shift.time-working-staff.un-choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    staff_id: $(obj).closest('.tr_shift').find($('.staff_id')).val(),
                }
            });
        }
    },
    updateObjectStaff: function (obj) {
        var isOt = 0;

        if ($(obj).closest('.tr_shift').find($('.check_ot')).is(':checked')) {
            isOt = 1;
        }

        //Cập nhật các giá trị của ca đã chọn
        $.ajax({
            url: laroute.route('shift.time-working-staff.update-object-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: $(obj).closest('.tr_shift').find($('.staff_id')).val(),
                is_ot: isOt,
                branch_id: $(obj).closest('.tr_shift').find($('.branch_id')).val()
            }
        });
    },
    submitAddStaff: function (shiftId, workingDay, workingEndDay, branchId) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.add-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                shift_id: shiftId,
                start_time: workingDay,
                end_time: workingEndDay,
                branch_id: branchId
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-add-staff').modal('hide');

                            $('#autotable').PioTable('refresh');
                        }
                        if (result.value == true) {
                            $('#modal-add-staff').modal('hide');

                            $('#autotable').PioTable('refresh');
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    showTimeWorkingDetail: function (timeWorkingId, view) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.show-time-working-detail'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingId,
                view: view
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-detail').modal('show');
                $('.modal-backdrop').hide();
                $('#timekeeping').select2();

                //Tab thưởng
                $('.autotable_reward').PioTable({
                    baseUrl: laroute.route('shift.time-working-staff.list-recompense')
                });

                $('.autotable_reward').find('.btn-search').trigger('click');

                //Tab phạt
                $('.autotable_punishment').PioTable({
                    baseUrl: laroute.route('shift.time-working-staff.list-recompense')
                });

                $('.autotable_punishment').find('.btn-search').trigger('click');
            }
        });
    },
    saveTimeWorking: function (timeWorkingId, view) {
        var isOt = 0;

        if ($('#is_ot').is(':checked')) {
            isOt = 1;
        }

        $.ajax({
            url: laroute.route('shift.time-working-staff.update-time-working'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingId,
                timekeeping: $('#timekeeping').val(),
                is_ot: isOt,
                note: $('#note').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-detail').modal('hide');

                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');

                                    index.listMyShift(res.info.staff_id, res.info.working_day);

                                    break;
                            }
                        }
                        if (result.value == true) {
                            $('#modal-detail').modal('hide');

                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');

                                    index.listMyShift(res.info.staff_id, res.info.working_day);
                                    break;
                            }
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    showModalTimeAttendance: function (timeWorkingStaffId, view) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.show-pop-time-attendance'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingStaffId,
                view: view
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-time-attendance').modal('show');

                $('.timepicker').datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "dd/mm/yyyy hh:ii",
                    // startDate : new Date()
                });
            }
        });
    },
    submitTimeAttendance: function (timeWorkingStaffId, staffId, workingDay, view) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.submit-time-attendance'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingStaffId,
                check_in_time: $('#check_in_time').val(),
                lock_check_in: $('#lock_check_in').val(),
                check_out_time: $('#check_out_time').val(),
                lock_check_out: $('#lock_check_out').val(),
                check_out_day: $('#check_out_day').val(),
                check_in_day: $('#check_in_day').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-time-attendance').modal('hide');
                            index.listMyShift(staffId, workingDay);
                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');
                                    index.listMyShift(staffId, workingDay);

                                    break;
                            }
                        }
                        if (result.value == true) {
                            $('#modal-time-attendance').modal('hide');
                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');
                                    index.listMyShift(staffId, workingDay);
                                    break;
                            }
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    //Show modal chỉnh sửa
    showModalEdit: function (timeWorkingStaffId, view) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.show-pop-edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingStaffId,
                view: view
            },
            success: function (res) {
                $('#my-modal-my-shift').html(res.html);
                $('#modal-edit').modal('show');
                $('.modal-backdrop').hide();

                $('#branch_id').select2();

                new AutoNumeric.multiple('#timekeeping_coefficient, #min_time_work', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 2,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

                $("#time_start, #time_end").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy hh:ii"
                });
            }
        });
    },
    //Chỉnh sửa ngày làm việc
    submitUpdateTimeWorking: function (timeWorkingStaffId, view) {
        var form = $('#form-edit');

        form.validate({
            rules: {
                branch_id: {
                    required: true
                },
                min_time_work: {
                    required: true
                },
                timekeeping_coefficient: {
                    required: true,
                    min: 1
                },
                time_start: {
                    required: true,
                },
                time_end: {
                    required: true
                },
            },
            messages: {
                branch_id: {
                    required: jsonLang['Hãy chọn chi nhánh']
                },
                min_time_work: {
                    required: jsonLang['Hãy nhập số giờ làm'],
                    min: jsonLang['Số giờ làm tối thiểu 1']
                },
                timekeeping_coefficient: {
                    required: jsonLang['Hãy nhập hệ số công'],
                    min: jsonLang['Hệ số công tối thiểu 1']
                },
                time_start: {
                    required: jsonLang['Hãy nhập thời gian bắt đầu làm việc'],
                },
                time_end: {
                    required: jsonLang['Hãy chọn ngày kết thúc']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('shift.time-working-staff.update-time-working'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingStaffId,
                branch_id: $('#branch_id').val(),
                min_time_work: $('#min_time_work').val().replace(new RegExp('\\,', 'g'), ''),
                timekeeping_coefficient: $('#timekeeping_coefficient').val().replace(new RegExp('\\,', 'g'), ''),
                time_start: $('#time_start').val(),
                time_end: $('#time_end').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-edit').modal('hide');

                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');

                                    // index.listMyShift(res.info.staff_id, res.info.working_day);

                                    break;
                            }
                        }
                        if (result.value == true) {
                            $('#modal-edit').modal('hide');

                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');

                                    // index.listMyShift(res.info.staff_id, res.info.working_day);
                                    break;
                            }
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    //Show modal làm thêm giờ
    showModalOvertime: function (timeWorkingStaffId, view) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.show-pop-overtime'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingStaffId,
                view: view
            },
            success: function (res) {
                $('#my-modal-my-shift').html(res.html);
                $('#modal-overtime').modal('show');
                $('.modal-backdrop').hide();

                $('#branch_id').select2();

                new AutoNumeric.multiple('#timekeeping_coefficient', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 2,
                    eventIsCancelable: true,
                    minimumValue: 0
                });

                $("#time_start, #time_end").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "dd/mm/yyyy hh:ii"
                });
            }
        });
    },
    //Lưu ca làm thêm
    submitTimeKeepingOvertime: function (timeWorkingStaffId, view) {
        var form = $('#form-overtime');

        form.validate({
            rules: {
                branch_id: {
                    required: true
                },
                timekeeping_coefficient: {
                    required: true,
                    min: 1
                },
                time_start: {
                    required: true,
                },
                time_end: {
                    required: true
                },
            },
            messages: {
                branch_id: {
                    required: jsonLang['Hãy chọn chi nhánh']
                },
                timekeeping_coefficient: {
                    required: jsonLang['Hãy nhập hệ số công'],
                    min: jsonLang['Hệ số công tối thiểu 1']
                },
                time_start: {
                    required: jsonLang['Hãy nhập thời gian bắt đầu làm việc'],
                },
                time_end: {
                    required: jsonLang['Hãy chọn ngày kết thúc']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        var isNotCheckIn = 0;

        if ($('#is_not_check_in').is(':checked')) {
            isNotCheckIn = 1;
        }

        $.ajax({
            url: laroute.route('shift.time-working-staff.store-overtime'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingStaffId,
                branch_id: $('#branch_id').val(),
                timekeeping_coefficient: $('#timekeeping_coefficient').val().replace(new RegExp('\\,', 'g'), ''),
                time_start: $('#time_start').val(),
                time_end: $('#time_end').val(),
                is_not_check_in: isNotCheckIn
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-overtime').modal('hide');

                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');

                                    break;
                            }
                        }
                        if (result.value == true) {
                            $('#modal-overtime').modal('hide');

                            switch (view) {
                                case 'list':
                                    $('#autotable').PioTable('refresh');
                                    break;
                                case 'my_shift':
                                    $('#autotable').PioTable('refresh');

                                    break;
                            }
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    //Show pop thêm thưởng - phạt
    showPopRecompense: function (timeWorkingStaffId, type) {
        $.ajax({
            url: laroute.route('shift.time-working-staff.show-pop-create-recompense'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingStaffId,
                type: type
            },
            success: function (res) {
                $('#my-modal-recompense').html(res.html);
                $('#modal-create-recompense').modal('show');

                $('#recompense_id').select2({
                    placeholder: jsonLang['Hãy chọn loại'],
                    width: '100%'
                });

                $('.modal-backdrop').css('display', 'none');

                new AutoNumeric.multiple('#money', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 2,
                    eventIsCancelable: true,
                    minimumValue: 0
                });
            }
        });
    },
    //Lưu hình thức thưởng - phạt
    submitCreateRecompense: function (timeWorkingStaffId, type) {
        var form = $('#form-create-recompense');

        form.validate({
            rules: {
                recompense_id: {
                    required: true
                },
                money: {
                    required: true,
                },
            },
            messages: {
                recompense_id: {
                    required: jsonLang['Hãy chọn loại']
                },
                money: {
                    required: jsonLang['Hãy nhập mức áp dụng']
                },
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('shift.time-working-staff.submit-create-recompense'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time_working_staff_id: timeWorkingStaffId,
                recompense_id: $('#recompense_id').val(),
                money: $('#money').val().replace(new RegExp('\\,', 'g'), '')
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#my-modal-recompense').empty();

                            if (type == 'R') {
                                //Reload tab thưởng
                                $('.autotable_reward').find('.btn-search').trigger('click');
                            } else {
                                //Reload tab phạt
                                $('.autotable_punishment').find('.btn-search').trigger('click');
                            }
                        }
                        if (result.value == true) {
                            $('#my-modal-recompense').empty();

                            if (type == 'R') {
                                //Reload tab thưởng
                                $('.autotable_reward').find('.btn-search').trigger('click');
                            } else {
                                //Reload tab phạt
                                $('.autotable_punishment').find('.btn-search').trigger('click');
                            }
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    //Xoá thưởng - phạt
    removeRecompense: function (timeWorkingRecompenseId, type) {
        swal({
            title: jsonLang['Thông báo'],
            text: jsonLang["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang['Xóa'],
            cancelButtonText: jsonLang['Hủy'],
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('shift.time-working-staff.remove-recompense'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        time_working_staff_recompense_id: timeWorkingRecompenseId
                    },
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");

                            if (type == 'R') {
                                //Reload tab thưởng
                                $('.autotable_reward').find('.btn-search').trigger('click');
                            } else {
                                //Reload tab phạt
                                $('.autotable_punishment').find('.btn-search').trigger('click');
                            }
                        } else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                });
            }
        });
    }
};

var WorkChild = {
    showPopup: function (manage_work_id = null, processorId, typeWork, startDate, startTime, endDate, endTime, view) {
        $.ajax({
            url: laroute.route('manager-work.detail.show-popup-work-child'),
            method: "POST",
            data: {
                manage_work_id: manage_work_id,
                processor_id: processorId,
                manage_type_work_id: typeWork,
                start_date: startDate,
                start_time: startTime,
                end_date: endDate,
                end_time: endTime,
                view: view
            },
            success: function (res) {
                if (res.error == false) {
                    $('#append-add-work').empty();
                    $('#append-add-work').append(res.view);
                    // $('.select2-active').select2({
                    //     dropdownParent: $(this).parent().find
                    // });

                    $('select:not(.normal)').each(function () {
                        $(this).select2({
                            dropdownParent: $(this).parent(),
                            width: '100%'
                        });
                    });

                    $('select[name="manage_tag[]"]').select2({
                        tags: true,
                        width: '100%'
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

                    $(".daterange-input").datepicker({
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
                        startDate: new Date()
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

                    AutoNumeric.multiple('.input-mask,.input-mask-remind', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 0,
                        minimumValue: 0,
                    });

                    AutoNumeric.multiple('#repeat_end_time', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 0,
                        minimumValue: 0,
                    });

                    AutoNumeric.multiple('.progress_input', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: 0,
                        minimumValue: 0,
                        maximumValue: 100,
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
                            onImageUpload: function (files) {
                                for (let i = 0; i < files.length; i++) {
                                    uploadImgCk(files[i]);
                                }
                            }
                        },
                    });
                    WorkAll.changeCustomer(manage_work_id);
                    $('#popup-work').modal('show');

                    $('#popup-work').on('hidden.bs.modal', function (e) {
                        switch (view) {
                            case 'shift':
                                $('#popup-work').modal('hide');
                                $('#append-add-work').empty();
                                break;
                            case 'my_shift':
                                $('#popup-work').modal('hide');
                                $('#append-add-work').empty();

                                break;
                            default:
                                location.reload();
                        }
                    })
                } else {
                    swal.fire(res.message, '', 'error');
                }
            }
        });
    },
    closePopup: function () {
        $('.note-children-container').remove();
    },
    cancelWork: function () {
        $('#append-add-work #popup-work').modal('hide');
        if (typeof $('#view_mode') != 'undefined' && $('#view_mode').val() == 'chathub_popup') {
            WorkChild.processFunctionCancelWork({});
        }

        $('.note-children-container').remove();
    },
    saveWork: function (createNew = 0, view) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-work');
            form.validate({
                rules: {
                    manage_work_title: {
                        required: true,
                        maxlength: 255
                    },
                    manage_type_work_id: {
                        required: true,
                    },
                    // date_start : {
                    //     required: true,
                    // },
                    date_end: {
                        required: true,
                    },
                    processor_id: {
                        required: true,
                    },
                    priority: {
                        required: true,
                    },
                    approve_id: {
                        required: function () {
                            if ($('#is_approve_id').is(':checked')) {
                                return true;
                            }
                            return false;
                        }
                    }

                },
                messages: {
                    manage_work_title: {
                        required: json["Vui lòng nhập tiêu đề"],
                        maxlength: json["Tiêu đề vượt quá 255 ký tự"]
                    },
                    manage_type_work_id: {
                        required: json["Vui lòng chọn loại công việc"],
                    },
                    // date_start : {
                    //     required: "Vui lòng chọn ngày bắt đầu",
                    // },
                    date_end: {
                        required: json["Vui lòng chọn ngày kết thúc"],
                    },
                    processor_id: {
                        required: json["Vui lòng chọn nhân viên thực hiện"],
                    },
                    priority: {
                        required: json["Vui lòng chọn mức độ ưu tiên"],
                    },
                    approve_id: {
                        required: json["Vui lòng chọn nhân viên duyệt"],
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }


            $.ajax({
                url: laroute.route('manager-work.detail.save-child-work'),
                data: $('#form-work').serialize(),
                method: "POST",
                dataType: "JSON",
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, '', 'success').then(function () {
                            if (createNew == 1) {
                                WorkChild.showPopup();
                            } else {
                                switch (view) {
                                    case 'shift':
                                        $('#popup-work').modal('hide');
                                        $('#append-add-work').empty();
                                        break;
                                    case 'my_shift':
                                        $('#popup-work').modal('hide');
                                        $('#append-add-work').empty();

                                        break;
                                    default:
                                        location.reload();
                                }
                            }
                        });
                    } else {
                        swal(res.message, '', 'error');
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
        });
    },

    selectWeekly: function (day) {

        if ($('label.weekly-select-' + day).hasClass('weekly-select-active')) {
            $('label.weekly-select-' + day).removeClass('weekly-select-active');
            $('input.weekly-select-' + day).val('');
        } else {
            $('label.weekly-select-' + day).addClass('weekly-select-active');
            $('input.weekly-select-' + day).val(day);
        }
    },

    selectMonthly: function (day) {

        if ($('label.monthly-select-' + day).hasClass('weekly-select-active')) {
            $('label.monthly-select-' + day).removeClass('weekly-select-active');
            $('input.monthly-select-' + day).val('');
        } else {
            $('label.monthly-select-' + day).addClass('weekly-select-active');
            $('input.monthly-select-' + day).val(day);
        }
    },

    changeRepeat: function () {
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

        $('.block_' + value).show();
    },
    changeRepeatEnd: function () {
        $('.disabled_block').prop('disabled', true);
        var value = $('.repeat_end:checked').val();

        if (value == 'after') {
            $('.repeat_end_type').prop('disabled', false);
            $('.repeat_end_time').prop('disabled', false);
        } else if (value == 'date') {
            $('.repeat_end_full_time').prop('disabled', false);
        }

    },

    approveStaff: function () {
        var value = $('#is_approve_id:checked').val();
        if (value == 1) {
            $('.black_title_not_approve').hide();
            $('.black_title_approve').show();
            var selectStaff = $('#approve_id_select').val();
            var idStaff = $('#id_staff').val();

            if (selectStaff == '') {
                $('#approve_id_select').val(idStaff).trigger('change');
            }

        } else {
            $('.black_title_approve').hide();
            $('.black_title_not_approve').show();
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
};

var WorkAll = {
    changeCustomer: function (manage_work_id = null) {
        var typeCustomer = $('#manage_work_customer_type').val();
        if (typeCustomer == 'deal') {
            $('.text-customer-select').hide();
            $('.text-deal-select').show();
        } else {
            $('.text-customer-select').show();
            $('.text-deal-select').hide();
        }
        $.ajax({
            url: laroute.route('manager-work.detail.change-customer'),
            data: {
                typeCustomer: typeCustomer,
                manage_work_id: manage_work_id
            },
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    $('#customer_id').empty();
                    $('#customer_id').append(res.view);
                    $('#customer_id').select2();
                } else {
                    swal(res.message, '', 'error');
                }
            },
        });
    },

    changeCustomerList: function (manage_work_id = null) {
        var typeCustomer = $('#manage_work_customer_type_list').val();
        $.ajax({
            url: laroute.route('manager-work.detail.change-customer'),
            data: {
                typeCustomer: typeCustomer,
                manage_work_id: manage_work_id
            },
            method: "POST",
            dataType: "JSON",
            success: function (res) {
                if (res.error == false) {
                    $('#select_customer_id').empty();
                    $('#select_customer_id').append(res.view);
                    $('#select_customer_id').select2();
                } else {
                    swal(res.message, '', 'error');
                }
            },
        });
    }
}