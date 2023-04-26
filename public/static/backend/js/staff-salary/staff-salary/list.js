
var staffSalary = {
    jsontranslate :null,
    saveSalary: function () {
        
        $('.error-staff-salary-weekday').text('');
        $('.error-staff-salary-saturday').text('');
        $('.error-staff-salary-sunday').text('');
        $('.error-staff-salary-holiday').text('');
        $('.error-staff_salary_unit_code').text('');
        $('.error-payment_type').text('');
        $('.error-staff-salary-type').text('');
        $('.error-staff-salary-pay-period').text('');

        var isValid = true;
        var branch_id = 0;
        var staff_salary_weekday = 0;
        var staff_salary_holiday = 0;
        var staff_salary_saturday = 0;
        var staff_salary_sunday = 0;
        var staff_salary_contract = 0;
        var staff_salary_monthly = 0;
        var staff_salary_holiday_type = "";
        var staff_salary_saturday_type = "";
        var staff_salary_sunday_type = "";
        var staff_salary_overtime_weekday = 0;
        var staff_salary_overtime_holiday = 0;
        var staff_salary_overtime_saturday = 0;
        var staff_salary_overtime_sunday = 0;
        var staff_salary_overtime_holiday_type = "";
        var staff_salary_overtime_saturday_type = "";
        var staff_salary_overtime_sunday_type = "";
        var arrAllowance = [];
        var arrBonusMinus = [];
        var arrOvertime = [];
        var staff_salary_unit_code = $("[name=staff_salary_unit_code]").val();
        var payment_type = $("[name=payment_type]").val();
        var staff_salary_type = $('.error-staff-salary-type').text('');
        var salary_pay_period = $('.error-staff-salary-pay-period').text('');

        var message = '';

        if($('#staff_salary_type').val() == 'monthly'){

            $('#tblSalaryMonthly tbody > tr').each(function () {
                branch_id = $(this).find("td:eq(0) input[type='hidden']").val();
                // staff_salary_contract = $(this).find("td:eq(1) input[type='text']").val().replace(',', '');
                staff_salary_monthly = $(this).find("td:eq(1) input[type='text']").val().replace(',', '');
            });
            if (staff_salary_monthly != '') {
                if (parseInt(staff_salary_monthly) <= 0) {
                    $('.error-staff-salary-monthly').css("color", "red");
                    $('.error-staff-salary-monthly').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                    isValid = false;
                }
            } else {
                $('.error-staff-salary-monthly').css("color", "red");
                $('.error-staff-salary-monthly').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                isValid = false;
            }
            // if (staff_salary_contract != '') {
            //     if (parseInt(staff_salary_contract) <= 0) {
            //         $('.error-staff-salary-contact').css("color", "red");
            //         $('.error-staff-salary-contact').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
            //         isValid = false;
            //         message = 'Mức lương phải lớn hơn 0';
            //     }
            // } else {
            //     $('.error-staff-salary-contact').css("color", "red");
            //     $('.error-staff-salary-contact').text(staffSalary.jsontranslate['Chưa điền mức lương']);
            //     $('.error-staff-salary-contact').text('Chưa điền mức lương');
            //     isValid = false;
            //     message = 'Chưa điền mức lương';
            // }
        } else {
            $('#tblSalary tbody > tr').each(function () {
                branch_id = $(this).find("td:eq(0) input[type='hidden']").val();
                staff_salary_weekday = $(this).find("td:eq(1) input[type='text']").val().replace(',', '');
                staff_salary_saturday = $(this).find("td:eq(2) input[type='text']").val().replace(',', '');
                staff_salary_sunday = $(this).find("td:eq(3) input[type='text']").val().replace(',', '');
                staff_salary_holiday = $(this).find("td:eq(4) input[type='text']").val().replace(',', '');
                staff_salary_saturday_type = $(this).find("td:eq(2) input[id='staff_salary_saturday_type']").val();
                staff_salary_sunday_type = $(this).find("td:eq(3) input[id='staff_salary_sunday_type']").val();
                staff_salary_holiday_type = $(this).find("td:eq(4) input[id='staff_salary_holiday_type']").val();

            });

            if (staff_salary_weekday != '') {
                if (parseInt(staff_salary_weekday) <= 0) {
                    $('.error-staff-salary-weekday').css("color", "red");
                    $('.error-staff-salary-weekday').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                    isValid = false;
                }
            } else {
                $('.error-staff-salary-weekday').css("color", "red");
                $('.error-staff-salary-weekday').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                isValid = false;
            }
          
            //Validate mức lương thứ 7
            if (staff_salary_saturday != '') {
                if (staff_salary_saturday_type == 'money') {
                    if (parseInt(staff_salary_saturday) <= 0) {
                        $('.error-staff-salary-saturday').css("color", "red");
                        $('.error-staff-salary-saturday').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                        $('.error-staff-salary-saturday').text('Mức lương phải lớn hơn 0');
                        isValid = false;
                    }
                } else {
                    if (parseInt(staff_salary_saturday) < 100 || parseInt(staff_salary_saturday) > 900) {
                        $('.error-staff-salary-saturday').css("color", "red");
                        $('.error-staff-salary-saturday').text(staffSalary.jsontranslate['Mức lương chỉ từ 100 - 900 %']);
                        $('.error-staff-salary-saturday').text('Mức lương chỉ từ 100 - 900 %');
                        isValid = false;
                    }
                }

            } else {
                $('.error-staff-salary-saturday').css("color", "red");
                $('.error-staff-salary-saturday').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                $('.error-staff-salary-saturday').text('Chưa điền mức lương');
                isValid = false;
            }
            //Validate mức lương thứ cn
            if (staff_salary_sunday != '') {
                if (staff_salary_sunday_type == 'money') {
                    if (parseInt(staff_salary_sunday) <= 0) {
                        $('.error-staff-salary-sunday').css("color", "red");
                        $('.error-staff-salary-sunday').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                        $('.error-staff-salary-sunday').text('Mức lương phải lớn hơn 0');
                        isValid = false;
                    }
                } else {
                    if (parseInt(staff_salary_sunday) < 100 || parseInt(staff_salary_sunday) > 900) {
                        $('.error-staff-salary-sunday').css("color", "red");
                        $('.error-staff-salary-sunday').text(staffSalary.jsontranslate['Mức lương chỉ từ 100 - 900 %']);
                        $('.error-staff-salary-sunday').text('Mức lương chỉ từ 100 - 900 %');
                        isValid = false;
                    }
                }
            } else {
                $('.error-staff-salary-sunday').css("color", "red");
                $('.error-staff-salary-sunday').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                $('.error-staff-salary-sunday').text('Chưa điền mức lương');
                isValid = false;
            }

            //Validate mức lương ngày lễ
            if (staff_salary_holiday != '') {
                if (staff_salary_holiday_type == 'money') {
                    if (parseInt(staff_salary_holiday) <= 0) {
                        $('.error-staff-salary-holiday').css("color", "red");
                        $('.error-staff-salary-holiday').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                        $('.error-staff-salary-holiday').text('Mức lương phải lớn hơn 0');
                        isValid = false;
                    }
                } else {
                    if (parseInt(staff_salary_holiday) < 100 || parseInt(staff_salary_holiday) > 900) {
                        $('.error-staff-salary-holiday').css("color", "red");
                        $('.error-staff-salary-holiday').text(staffSalary.jsontranslate['Mức lương chỉ từ 100 - 900 %']);
                        $('.error-staff-salary-holiday').text('Mức lương chỉ từ 100 - 900 %');
                        isValid = false;
                    }
                }
            } else {
                $('.error-staff-salary-holiday').css("color", "red");
                $('.error-staff-salary-holiday').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                $('.error-staff-salary-holiday').text('Chưa điền mức lương');
                isValid = false;
            }
            //Validate staff_salary_unit_code
            if (staff_salary_unit_code != '') {

            } else {
                $('.error-staff_salary_unit_code').css("color", "red");
                $('.error-staff_salary_unit_code').text(staffSalary.jsontranslate['Chưa chọn đơn vị tiền tệ']);
                isValid = false;
            }

            //Validate payment_type
            if (payment_type != '' && payment_type != 'undefined') {

            } else {
                $('.error-payment_type').css("color", "red");
                $('.error-payment_type').text(staffSalary.jsontranslate['Chưa chọn hình thức trả lương']);
                isValid = false;
            }

            if ($('#staff_salary_type').val() == "" ) {
                $('.error-staff-salary-type').css("color", "red");
                $('.error-staff-salary-type').text(staffSalary.jsontranslate['Chưa chọn loại lương']);
                isValid = false;
            }else {
                $('.error-staff-salary-type').text('');
            }
            if ($('#salary_pay_period').val() == '') {
                $('.error-staff-salary-pay-period').css("color", "red");
                $('.error-staff-salary-pay-period').text(staffSalary.jsontranslate['Chưa chọn kỳ hạn trả lương']);
                isValid = false;
            }else {
                $('.error-staff-salary-pay-period').text('');
            }
        }

        var ckbOvertime = $('input[name="ckbOvertime"]:checked').val();
        if (ckbOvertime == 'on') {
            $('#tblOvertime tbody > tr').each(function () {
                branch_id = $(this).find("td:eq(0) input[type='hidden']").val();
                staff_salary_overtime_weekday = $(this).find("td:eq(1) input[type='text']").val().replace(',', '');
                staff_salary_overtime_saturday = $(this).find("td:eq(2) input[type='text']").val().replace(',', '');
                staff_salary_overtime_sunday = $(this).find("td:eq(3) input[type='text']").val().replace(',', '');
                staff_salary_overtime_holiday = $(this).find("td:eq(4) input[type='text']").val().replace(',', '');
                staff_salary_overtime_holiday_type = $(this).find("#staff_salary_overtime_holiday_type").val();
                staff_salary_overtime_saturday_type = $(this).find("#staff_salary_overtime_saturday_type").val();
                staff_salary_overtime_sunday_type = $(this).find("#staff_salary_overtime_sunday_type").val();

                var obj = {
                    branch_id: branch_id,
                    staff_salary_overtime_weekday: staff_salary_overtime_weekday,
                    staff_salary_overtime_saturday: staff_salary_overtime_saturday,
                    staff_salary_overtime_sunday: staff_salary_overtime_sunday,
                    staff_salary_overtime_holiday: staff_salary_overtime_holiday,
                    staff_salary_overtime_holiday_type: staff_salary_overtime_holiday_type,
                    staff_salary_overtime_saturday_type: staff_salary_overtime_saturday_type,
                    staff_salary_overtime_sunday_type: staff_salary_overtime_sunday_type
                };
                if (staff_salary_overtime_weekday != '') {
                    if (parseInt(staff_salary_overtime_weekday) <= 0) {
                        $('.error-staff-salary-overtime-weekday').css("color", "red");
                        $('.error-staff-salary-overtime-weekday').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                       
                        isValid = false;
                    }
                } else {
                    $('.error-staff-salary-overtime-weekday').css("color", "red");
                    $('.error-staff-salary-overtime-weekday').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                    isValid = false;
                }
                //Validate mức lương thứ 7
                if (staff_salary_overtime_saturday != '') {
                    if (staff_salary_overtime_saturday_type == 'money') {
                        if (parseInt(staff_salary_overtime_saturday) <= 0) {
                            $('.error-staff-salary-overtime-saturday').css("color", "red");
                            $('.error-staff-salary-overtime-saturday').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                            isValid = false;
                        }
                    } else {
                        if (parseInt(staff_salary_overtime_saturday) < 100 || parseInt(staff_salary_overtime_saturday) > 900) {
                            $('.error-staff-salary-overtime-saturday').css("color", "red");
                            $('.error-staff-salary-overtime-saturday').text(staffSalary.jsontranslate['Mức lương chỉ từ 100 - 900 %']);
                            isValid = false;
                        }
                    }

                } else {
                    $('.error-staff-salary-overtime-saturday').css("color", "red");
                    $('.error-staff-salary-overtime-saturday').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                    isValid = false;
                }
                //Validate mức lương thứ cn
                if (staff_salary_overtime_sunday != '') {
                    if (staff_salary_overtime_sunday_type == 'money') {
                        if (parseInt(staff_salary_overtime_sunday) <= 0) {
                            $('.error-staff-salary-overtime-sunday').css("color", "red");
                            $('.error-staff-salary-overtime-sunday').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                            isValid = false;
                        }
                    } else {
                        if (parseInt(staff_salary_overtime_sunday) < 100 || parseInt(staff_salary_overtime_sunday) > 900) {
                            $('.error-staff-salary-overtime-sunday').css("color", "red");
                            $('.error-staff-salary-overtime-sunday').text(staffSalary.jsontranslate['Mức lương chỉ từ 100 - 900 %']);
                            isValid = false;
                        }
                    }
                } else {
                    $('.error-staff-salary-overtime-sunday').css("color", "red");
                    $('.error-staff-salary-overtime-sunday').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                    $('.error-staff-salary-overtime-sunday').text('Chưa điền mức lương');
                    isValid = false;
                }
                //Validate mức lương ngày lễ
                if (staff_salary_overtime_holiday != '') {
                    if (staff_salary_overtime_holiday_type == 'money') {
                        if (parseInt(staff_salary_overtime_holiday) <= 0) {
                            $('.error-staff-salary-overtime-holiday').css("color", "red");
                            $('.error-staff-salary-overtime-holiday').text(staffSalary.jsontranslate['Mức lương phải lớn hơn 0']);
                            isValid = false;
                        }
                    } else {
                        if (parseInt(staff_salary_overtime_holiday) < 100 || parseInt(staff_salary_overtime_holiday) > 900) {
                            $('.error-staff-salary-overtime-holiday').css("color", "red");
                            $('.error-staff-salary-overtime-holiday').text(staffSalary.jsontranslate['Mức lương chỉ từ 100 - 900 %']);
                            isValid = false;
                        }
                    }
                } else {
                    $('.error-staff-salary-overtime-holiday').css("color", "red");
                    $('.error-staff-salary-overtime-holiday').text(staffSalary.jsontranslate['Chưa điền mức lương']);
                    isValid = false;
                }

                //Validate staff_salary_unit_code
                if (staff_salary_unit_code != '') {

                } else {
                    $('.error-staff_salary_unit_code').css("color", "red");
                    $('.error-staff_salary_unit_code').text(staffSalary.jsontranslate['Chưa chọn đơn vị tiền tệ']);
                    isValid = false;
                }

                //Validate payment_type
                if (payment_type != '') {

                } else {
                    $('.error-payment_type').css("color", "red");
                    $('.error-payment_type').text(staffSalary.jsontranslate['Chưa chọn hình thức trả lương']);
                    isValid = false;
                }
                arrOvertime.push(obj);
            });

        } 
        if (isValid) {
            var ckbAllowances = $('input[name="ckbAllowances"]:checked').val();
            if (ckbAllowances == 'on') {
                $("#tblSalaryAllowance tbody tr").each(function () {
                    var _tr = $(this).closest("tr");
                    var id = $("td:eq(0) input[type='hidden']", _tr).val();
                    var salary_allowance_num = $("td:eq(1) input[type='hidden']", _tr).val();
                    var obj = {
                        salary_allowance_id: id,
                        staff_salary_allowance_num: salary_allowance_num
                    };
                    arrAllowance.push(obj)
                });
            }
            var ckbBonusMinus = $('input[name="ckbBonusMinus"]:checked').val();
            if (ckbBonusMinus == 'on') {
                $("#tblBonusMinus tbody tr").each(function () {
                    var _tr = $(this).closest("tr");
                    var salary_bonus_minus_id = $("td:eq(0) input[type='hidden']", _tr).val();
                    var salary_bonus_minus_num = $("td:eq(1) input[type='hidden']", _tr).val();
                    var obj = {
                        salary_bonus_minus_id: salary_bonus_minus_id,
                        staff_salary_bonus_minus_num: salary_bonus_minus_num
                    };
                    arrBonusMinus.push(obj)
                });
            }
            var dataPost = {
                staff_salary_config_id: $("#staff_salary_config_id").val(),
                staff_id: $("#staff_id").val(),
                staff_salary_type_code: $("#staff_salary_type").val(),
                staff_salary_pay_period_code: $("#salary_pay_period").val(),
                branch_id: branch_id,
                staff_salary_weekday: staff_salary_weekday,
                staff_salary_holiday: staff_salary_holiday,
                staff_salary_holiday_type: staff_salary_holiday_type,
                staff_salary_saturday: staff_salary_saturday,
                staff_salary_unit_code: $('[name=staff_salary_unit_code]').val(),
                payment_type: $('[name=payment_type]').val(),
                staff_salary_saturday_type: staff_salary_saturday_type,
                staff_salary_sunday: staff_salary_sunday,
                staff_salary_sunday_type: staff_salary_sunday_type,
                staff_salary_contract : staff_salary_contract,
                staff_salary_monthly : staff_salary_monthly,
                array_allowance: arrAllowance,
                array_bonus_minus: arrBonusMinus,
                array_overtime: arrOvertime
            };
            // console.log(dataPost);return;
            $.ajax({
                url: laroute.route('staff-salary.add'),
                method: 'POST',
                dataType: 'JSON',
                data: dataPost,
                success: function (data) {
                    if (data.status == 1) {
                        swal({
                            title:  data.message,
                            text: 'Redirecting...',
                            type: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                        })
                        .then(() => {
                            window.location.href = laroute.route('admin.staff');
                        });
                    }else {
                        Swal.fire(
                            staffSalary.jsontranslate['Thông Báo'],
                            staffSalary.jsontranslate['Cấu hình thất bại'],
                            'error'
                        )
                    }
                }
            });
        }
    },

    updateReportSalaryDetail : function(id){
        $.ajax({
            url: laroute.route('staff-salary.detail-update'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_salary_id : id
            },
            success: function (data) {
                if (data.status == 1) {
                    swal({
                        title:  staffSalary.jsontranslate['Cập nhật thành công'],
                        text: 'Redirecting...',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                    .then(() => {
                        window.location.reload();
                    });
                }else {
                    Swal.fire(
                        staffSalary.jsontranslate['Thông Báo'],
                        staffSalary.jsontranslate['Cập nhật thất bại'],
                        'error'
                    )
                }
            }
        });
    },
   closeReportSalaryDetail : function(id){
       swal({
           title: staffSalary.jsontranslate['Thông báo'],
           text: staffSalary.jsontranslate['Bạn có muốn chốt bảng lương?'],
           type: 'warning',
           showCancelButton: true,
           confirmButtonText: staffSalary.jsontranslate['Đồng ý'],
           cancelButtonText: staffSalary.jsontranslate['Hủy']
       }).then(function(result) {
           if (result.value) {
               $.ajax({
                   url: laroute.route('staff-salary.detail-close'),
                   method: 'POST',
                   dataType: 'JSON',
                   data: {
                       staff_salary_id : id
                   },
                   success: function (data) {
                       if (data.status == 1) {
                            swal({
                                title:  staffSalary.jsontranslate['Cập nhật thành công'],
                                text: 'Redirecting...',
                                type: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                            })
                            .then(() => {
                                window.location.reload();
                            });

                       }else {
                           Swal.fire(
                               'Thông Báo',
                               staffSalary.jsontranslate['Cập nhật thất bại'],
                               'error'
                           )
                       }
                   }
               });
           }
       });
    }
}
$('#autotable').PioTable({
    baseUrl: laroute.route('staff-salary.list')
});
$(document).ready(function () {
    staffSalary.jsontranslate = JSON.parse(localStorage.getItem('tranlate'));

    // staffSalary.jsontranslate = localStorage.getItem('tranlate');
    $('#staff_salary_type, #salary_template, #salary_pay_period').select2();
    $('#pay_period_date').datepicker({
        rtl: mUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        autoclose: true,
        format: 'dd/mm/yyyy',
    });
  
    $('option').attr('title','');
    $('.select2-selection__rendered').attr('title','');
});