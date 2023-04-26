
var salaryTempalte = {
    jsontranslate : null,
    addSalaryAllowance: function () {
       
        var isValid = true;
        var salary_allowance_id = 0;
        var salary_allowance_num = "";
        var arrayData = [];
        $('.error-salaryAllowance').text("");
        $('.error-allowance-num').text("");
        if($('#salaryAllowance').val() == ''){
            $('.error-salaryAllowance').css("color", "red");
            $('.error-salaryAllowance').text(jsonLang['Chưa chọn loại phụ cấp']);
            isValid = false
           
        }
        $("#tblSalaryAllowance tbody tr").each(function () {
            var _tr = $(this).closest("tr");
            salary_allowance_id = $("td:eq(0) input[type='hidden']", _tr).val();
            salary_allowance_num = $("td:eq(1)", _tr).text();
            arrayData.push(salary_allowance_id);

        });
        arrayData.forEach(item => {
            if(item == $("#salaryAllowance").val()){
                $('.error-salaryAllowance').css("color", "red");
                $('.error-salaryAllowance').text(jsonLang['Loại phụ cấp này đã tồn tại']);
                isValid = false
            }
        })
        if($("#salary_allowance_num_add").val() == ''){
            $('.error-allowance-num-add').css("color", "red");
            $('.error-allowance-num-add').text(jsonLang['Chưa điền số tiền hưởng phụ cấp']);
            isValid = false
        }else {
            if(parseInt($("#salary_allowance_num_add").val()) <= 0){
                $('.error-allowance-num-add').css("color", "red");
                $('.error-allowance-num-add').text(jsonLang['Số tiền phụ cấp phải lớn hơn 0']);
                isValid = false
            }
        }
        if(isValid){
            $.ajax({
                url: laroute.route('salary.get-row-allowance'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    salary_allowance_num : $("#salary_allowance_num_add").val(),
                    salary_allowance : $("#salaryAllowance").val(),
                    salary_allowance_text : $("#salaryAllowance option:selected").text(),
                    unitText : $( "#staff_salary_unit_code option:selected" ).text(),
                },
                success: function (res) {
                    if (res.html != null) {
                        $('#tblSalaryAllowance').append(res.html);
                        $('#modalAllowancesAdd').modal('hide');
                        $('#modal-allowances-add').html('');
                        $('.modal-backdrop').remove();

                    }
                }
            });
        }
    },

    addSalaryBonusMinus: function () {
        var isValid = true;
        var salary_bonus_minus_id = 0;
        var salary_bonus_minus_num = "";
        var arrayData = [];
        $('.error-salaryBonusMinus').text("");
        $('.error-bonus-minus-num').text("");
        $("#tblBonusMinus tbody tr").each(function () {
            var _tr = $(this).closest("tr");
            salary_bonus_minus_id = $("td:eq(0) input[type='hidden']", _tr).val();
            salary_bonus_minus_num = $("td:eq(1)", _tr).text();
            arrayData.push(salary_bonus_minus_id);
        });
        arrayData.forEach(item => {
            if(item == $("#salaryBonusMinus").val()){
                $('.error-salaryBonusMinus').css("color", "red");
                $('.error-salaryBonusMinus').text(jsonLang['Loại thưởng / phạt này đã tồn tại']);
                isValid = false
            }
        })
        if($("#salary_bonus_minus_num").val() == ''){
            $('.error-bonus-minus-num').css("color","red");
            $('.error-bonus-minus-num').text(jsonLang['Chưa điền số tiền thưởng / phạt']);
            isValid = false
        }else {
            if(parseInt($("#salary_bonus_minus_num").val()) <= 0){
                $('.error-bonus-minus-num').css("color", "red");
                $('.error-bonus-minus-num').text(jsonLang['Số tiền thưởng / phạt phải lớn hơn 0']);
                isValid = false
            }
        }
        if(isValid){
            $.ajax({
                url: laroute.route('salary.get-row-bonus-minus'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    salary_bonus_minus_num : $("#salary_bonus_minus_num").val(),
                    salary_bonus_minus : $("#salaryBonusMinus").val(),
                    salary_bonus_minus_text : $("#salaryBonusMinus option:selected").text(),
                },
                success: function (res) {
                    if (res.html != null) {
                        $('#tblBonusMinus').append(res.html);
                        $('#modalSalaryBonusMinusAdd').modal('hide');
                        $('#modal-salary-bonus-minus-add').html('');
                        $('.modal-backdrop').remove();

                    }
                }
            });
        }
    },
    removeCell: function (e){
        $(e).closest('tr').remove();
    },
    checkBonusMinus: function () {
        var ckbBonusMinus = $('input[name="ckbBonusMinus"]:checked').val();
        if (ckbBonusMinus == 'on') {
            $('#divBonusMinus').css('display', 'block');
        } else {
            $('#divBonusMinus').css('display', 'none');
        }
    },
    checkOvertime: function () {
        var ckbOvertime = $('input[name="ckbOvertime"]:checked').val();
        if (ckbOvertime == 'on') {
            $('#tblSalaryOvertime').css('display', 'block');
        } else {
            $('#tblSalaryOvertime').css('display', 'none');
        }
    },
    checkAllowances: function () {
        var ckbAllowances = $('input[name="ckbAllowances"]:checked').val();
        if (ckbAllowances == 'on') {
            $('#tblAllowances').css('display', 'block');
        } else {
            $('#tblAllowances').css('display', 'none');
        }
    },
    checkStaffSalarySaturday: function (type) {
        $('#staff_salary_saturday_type').val(type);
    },
    checkStaffSalarySunday: function (type) {
        $('#staff_salary_sunday_type').val(type);
    },
    checkStaffSalaryHoliday: function (type) {
        $('#staff_salary_holiday_type').val(type);
    },
    checkStaffSalaryOvertimeSaturday: function (type) {
        $('#staff_salary_overtime_saturday_type').val(type);
    },
    checkStaffSalaryOvertimeSunday: function (type) {
        $('#staff_salary_overtime_sunday_type').val(type);
    },
    checkStaffSalaryOvertimeHoliday: function (type) {
        $('#staff_salary_overtime_holiday_type').val(type);
    },
    getSalaryRow: function () {
        $.ajax({
            url: laroute.route('salary.get-row'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.html != null) {

                    $('#tblSalary').append(res.html);

                }
            }
        });
    },
    showModalTemplateAdd: function () {
        $.ajax({
            url: laroute.route('salary-template.modal-add'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.html != null) {
                    $('#modal-template-salary-add').html(res.html);
                    $('#modalSalaryTemplateAdd').modal('show');
                } else {
                    Swal.fire(
                        jsonLang['Thông Báo'],
                        jsonLang['Có lỗi xảy ra'],
                        'error'
                    )
                }

            }
        });
    },
    showModalAllowancesAdd: function () {
        $.ajax({
            url: laroute.route('salary-allowances.modal-add'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                 unit : $( "#staff_salary_unit_code option:selected" ).text(),
            },
            success: function (res) {
                if (res.html != null) {
                    $('#modal-allowances-add').html(res.html);
                    $('#modalAllowancesAdd').modal('show');
                } else {
                    Swal.fire(
                        jsonLang['Thông Báo'],
                        jsonLang['Có lỗi xảy ra'],
                        'error'
                    )
                }

            }
        });
    },
    showModalBonusMinusAdd: function () {
        $.ajax({
            url: laroute.route('salary-bonus-minus.modal-add'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                if (res.html != null) {
                    $('#modal-salary-bonus-minus-add').html(res.html);
                    $('#modalSalaryBonusMinusAdd').modal('show');
                } else {
                    Swal.fire(
                        jsonLang['Thông Báo'],
                        jsonLang['Có lỗi xảy ra'],
                        'error'
                    )
                }

            }
        });
    },
    changeStaffSalaryType : function (e){
        $.ajax({
            url: laroute.route('salary-template.change-staff-salary-type'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                salary_type : $(e).val(),
                branch_id : $('#branch_id').val()
            },
            success: function (res) {
                if (res.html != null) {
                    $('#tblSalaryType').html(res.html);
                    var html = '';
                    if($(e).val() == 'monthly'){
                        html = '<option value="pay_month">' + jsonLang['Hàng tháng'] + '</option>';
                    }else {
                        html = '<option value="pay_week">' + jsonLang['Hàng tuần'] + '</option>';
                        html += '<option value="pay_month">' + jsonLang['Hàng tháng'] + '</option>';
                    }
                    $('#salary_pay_period').html(html);
                    $('#salary_pay_period').select2({
                        placeholder:jsonLang['Chọn kỳ hạn trả lương'],
                        width: '100%'
                    });
                  
                    salaryTempalte.chooseUnitAndType(true);
                }
            }
        });
    },
    chooseUnitAndType: function (loadDefault = false) {
        var salaryTypeName = $("#staff_salary_type  option:selected").text();
        var salaryUnitName = $("#staff_salary_unit_code  option:selected").text();

        $('.salary-unit-name').text(salaryUnitName);
        $('.salary-type-name').text(salaryTypeName);

        $('.text_type_default').text(salaryUnitName + '/ ' + salaryTypeName);
        $('.text_type_overtime').text(salaryUnitName + '/ ' + jsonLang['Giờ']);
        //Lấy value của loại lương
        var salaryTypeCode = $('#staff_salary_type').val();

        if (loadDefault == false) {
            if (salaryTypeCode == 'monthly') {
                //Theo tháng
                $('#staff_salary_pay_period_code').val('pay_month').trigger('change').attr('disabled', true);

                $('.salary_not_month').remove();
            } else {
                $('#staff_salary_pay_period_code').val('').trigger('change').attr('disabled', false);

                if (!$(".salary_not_month")[0]) {
                    //Class này không tồn tại thì mới append zo
                    var tpl = $('#head-table-default-tpl').html();
                    $('#table_default > thead > tr').append(tpl);

                    var tpl = $('#body-table-default-tpl').html();
                    $('#table_default > tbody > tr').append(tpl);

                    var tpl = $('#head-table-overtime-tpl').html();
                    $('#table_overtime > thead > tr').append(tpl);

                    var tpl = $('#body-table-overtime-tpl').html();
                    $('#table_overtime > tbody > tr').append(tpl);

                }
            }
        }
      
    },
    changeStaffSalaryTemplate : function (e){
        if( !$(e).val() ) return false;
        $.ajax({
            url: laroute.route('salary-template.change-staff-salary-template'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_salary_template_id : $('[name=staff_salary_template_id]').val(),
                // staff_salary_config_id: $('[name=staff_salary_config_id]').val()
            },
            success: function (res) {
                if (res.staff_salary_type != null) {
                    $('[name=staff_salary_type_code]').val(res.staff_salary_type_code);
                    $('[name=staff_salary_type_code]').trigger('change');
                }
                if (res.payment_type != null) {
                    $('[name=payment_type]').val(res.payment_type);
                    $('[name=payment_type]').trigger('change');
                }
                if (res.staff_salary_unit_code != null) {
                    $('[name=staff_salary_unit_code]').val(res.staff_salary_unit_code);
                    $('[name=staff_salary_unit_code]').trigger('change');
                }
                if (res.staff_salary_pay_period_code != null) {
                    $('[name=staff_salary_pay_period_code]').val(res.staff_salary_pay_period_code);
                    $('[name=staff_salary_pay_period_code]').trigger('change');
                }
                if (res.html != null) {
                    $('.tab-thiet-lap-luong').html(res.html);
                    $('[name=salary_pay_period]').select2({
                        placeholder:jsonLang['Chọn kỳ hạn trả lương'],
                        width: '100%'
                    });
                    $('[name=payment_type]').select2({
                        placeholder:jsonLang['Chọn hình thức trả lương'],
                        width: '100%'
                    });
                    $('[name=staff_salary_template_id]').select2({
                        placeholder:jsonLang['Chọn mẫu áp dụng'],
                        width: 'calc(100% - 30px)'
                    });
                    $('[name=staff_salary_type]').select2({
                        placeholder:jsonLang['Chọn loại lương'],
                        width: '100%'
                    });
                    $('#staff_salary_unit_code').select2({
                        placeholder:jsonLang['Chọn đơn vị tiền tệ'],
                        width: '100%'
                    });

                    
                }
                salaryTempalte.chooseUnitAndType(true);
               
            }
        });

        return false;
    }

}
$('#autotable').PioTable({
    baseUrl: laroute.route('holiday.list')
});
$(document).ready(function () {
    salaryTempalte.jsontranslate = localStorage.getItem('tranlate');
    var ckbAllowances = $('input[name="ckbAllowances"]:checked').val();
    if (ckbAllowances == 'on') {
        $('#tblAllowances').css('display', 'block');
    } else {
        $('#tblAllowances').css('display', 'none');
    }
    var ckbBonusMinus = $('input[name="ckbBonusMinus"]:checked').val();
    if (ckbBonusMinus == 'on') {
        $('#divBonusMinus').css('display', 'block');
    } else {
        $('#divBonusMinus').css('display', 'none');
    }
    var ckbOvertime = $('input[name="ckbOvertime"]:checked').val();
    if (ckbOvertime == 'on') {
        $('#tblSalaryOvertime').css('display', 'block');
    } else {
        $('#tblSalaryOvertime').css('display', 'none');
    }
   
});

function parseDate(str) {
    var mdy = str.split('/');
    return new Date(mdy[2], mdy[1], mdy[0]);
}
