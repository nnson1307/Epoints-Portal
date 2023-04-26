var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var nextStep1 = false;
var nextStep2 = false;

var allowance = {
    _init: function () {
        $('#autotable-staff').PioTable({
            baseUrl: laroute.route('admin.commission.list-staff')
        });

        $('#autotable-commission').PioTable({
            baseUrl: laroute.route('admin.commission.list-commission')
        });
    },

    //Chọn tất cả nhân viên
    chooseAllStaff: function (obj) {
        if ($(obj).is(':checked')) {
            $('.check_one').prop('checked', true);

            var arrChoose = [];

            $('.check_one').each(function () {
                $(this).closest('.tr_staff').find('.commission_coefficient').prop('disabled', false);

                arrChoose.push({
                    staff_id: $(this).closest('.tr_staff').find('.staff_id').val(),
                    commission_coefficient: $(this).closest('.tr_staff').find('.commission_coefficient').val().replace(new RegExp('\\,', 'g'), '')
                });
            });

            $.ajax({
                url: laroute.route('admin.commission.choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrChoose: arrChoose
                },
                success: function (res) {
                    if (res.number_staff > 0) {
                        nextStep1 = true;
                    } else {
                        nextStep1 = false;
                    }
                }
            });
        } else {
            $('.check_one').prop('checked', false);

            var arrUnChoose = [];

            $('.check_one').each(function () {
                $(this).closest('.tr_staff').find('.commission_coefficient').val(1).prop('disabled', true);

                arrUnChoose.push({
                    staff_id: $(this).closest('.tr_staff').find('.staff_id').val(),
                });
            });

            $.ajax({
                url: laroute.route('admin.commission.un-choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrUnChoose: arrUnChoose
                },
                success: function (res) {
                    if (res.number_staff > 0) {
                        nextStep1 = true;
                    } else {
                        nextStep1 = false;
                    }
                }
            });
        }
    },

    //Chọn 1 nhân viên
    chooseStaff: function (obj) {
        if ($(obj).is(':checked')) {
            $(obj).closest('.tr_staff').find($('.commission_coefficient')).prop('disabled', false);

            var arrChoose = [];

            arrChoose.push({
                staff_id: $(obj).closest('.tr_staff').find($('.staff_id')).val(),
                commission_coefficient: $(obj).closest('.tr_staff').find($('.commission_coefficient')).val().replace(new RegExp('\\,', 'g'), '')
            });

            $.ajax({
                url: laroute.route('admin.commission.choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrChoose: arrChoose
                },
                success: function (res) {
                    if (res.number_staff > 0) {
                        nextStep1 = true;
                    } else {
                        nextStep1 = false;
                    }
                }
            });
        } else {
            $(obj).closest('.tr_staff').find($('.commission_coefficient')).val(1).prop('disabled', true);

            var arrUnChoose = [];

            arrUnChoose.push({
                staff_id: $(obj).closest('.tr_staff').find($('.staff_id')).val()
            });

            $.ajax({
                url: laroute.route('admin.commission.un-choose-staff'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrUnChoose: arrUnChoose
                },
                success: function (res) {
                    if (res.number_staff > 0) {
                        nextStep1 = true;
                    } else {
                        nextStep1 = false;
                    }
                }
            });
        }
    },

    //Update các giá trị của table nhân viên
    updateObjectStaff: function (obj) {
        //Cập nhật các giá trị của nhân viên đã chọn
        $.ajax({
            url: laroute.route('admin.commission.update-object-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_id: $(obj).closest('.tr_staff').find($('.staff_id')).val(),
                commission_coefficient: $(obj).closest('.tr_staff').find($('.commission_coefficient')).val().replace(new RegExp('\\,', 'g'), '')
            }
        });
    },

    //Chọn tất cả hoa hồng
    chooseAllCommission: function (obj) {
        if ($(obj).is(':checked')) {
            $('.check_one_commission').prop('checked', true);

            var arrChooseCommission = [];

            $('.check_one_commission').each(function () {
                $(this).closest('.tr_commission').find('.priority').prop('disabled', false);

                $(this).closest('.tr_commission').find('.priority').select2({
                    width: '100%'
                });

                arrChooseCommission.push({
                    commission_id: $(this).closest('.tr_commission').find('.commission_id').val(),
                    // priority: $(this).closest('.tr_commission').find('.priority').val()
                });
            });

            $.ajax({
                url: laroute.route('admin.commission.choose-commission'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrChoose: arrChooseCommission
                },
                success: function (res) {
                    if (res.number_commission > 0) {
                        nextStep2 = true;
                    } else {
                        nextStep2 = false;
                    }
                }
            });
        } else {
            $('.check_one_commission').prop('checked', false);

            var arrUnChooseCommission = [];

            $('.check_one_commission').each(function () {
                $(this).closest('.tr_commission').find('.priority').val(1).trigger('change').prop('disabled', true);

                arrUnChooseCommission.push({
                    commission_id: $(this).closest('.tr_commission').find('.commission_id').val(),
                });
            });

            $.ajax({
                url: laroute.route('admin.commission.un-choose-commission'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrUnChoose: arrUnChooseCommission
                },
                success: function (res) {
                    if (res.number_commission > 0) {
                        nextStep2 = true;
                    } else {
                        nextStep2 = false;
                    }
                }
            });
        }
    },

    //Chọn 1 hoa hồng
    chooseCommission: function (obj) {
        if ($(obj).is(':checked')) {
            $(obj).closest('.tr_commission').find($('.priority')).prop('disabled', false);

            $(obj).closest('.tr_commission').find($('.priority')).select2({
                width: '100%'
            });

            var arrChooseCommission = [];

            arrChooseCommission.push({
                commission_id: $(obj).closest('.tr_commission').find($('.commission_id')).val(),
                priority: $(obj).closest('.tr_commission').find($('.priority')).val()
            });

            $.ajax({
                url: laroute.route('admin.commission.choose-commission'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrChoose: arrChooseCommission
                },
                success: function (res) {
                    if (res.number_commission > 0) {
                        nextStep2 = true;
                    } else {
                        nextStep2 = false;
                    }
                }
            });
        } else {
            $(obj).closest('.tr_commission').find($('.priority')).val(1).trigger('change').prop('disabled', true);

            var arrUnChooseCommission = [];

            arrUnChooseCommission.push({
                commission_id: $(obj).closest('.tr_commission').find($('.commission_id')).val(),
            });

            $.ajax({
                url: laroute.route('admin.commission.un-choose-commission'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    arrUnChoose: arrUnChooseCommission
                },
                success: function (res) {
                    if (res.number_commission > 0) {
                        nextStep2 = true;
                    } else {
                        nextStep2 = false;
                    }
                }
            });
        }
    },

    //Update các giá trị của table hoa hồng
    updateObjectCommission: function (obj) {
        //Cập nhật các giá trị của hoa hồng đã chọn
        $.ajax({
            url: laroute.route('admin.commission.update-object-commission'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                commission_id: $(obj).closest('.tr_commission').find($('.commission_id')).val(),
                priority: $(obj).closest('.tr_commission').find($('.priority')).val()
            }
        });
    },

    clearFilterStaff: function () {
        $("input[name=search]").val('');

        $("select[name='staffs$staff_type']").val('').trigger('change');
        $("select[name='staffs$branch_id']").val('').trigger('change');
        $("select[name='staffs$department_id']").val('').trigger('change');
        $("select[name='staffs$staff_title_id']").val('').trigger('change');

        $('#autotable-staff').find($('.btn-search-filter')).trigger('click');
    }
};

var WizardDemo = function () {
    $("#m_wizard");
    var e, r, i = $("#m_form");

    return {
        init: function () {
            var n;
            $("#m_wizard"), i = $("#m_form"), (r = new mWizard("m_wizard", {startStep: 1})).on("beforeNext", function (r) {
                !0 !== e.form() && r.stop()

                if (r.currentStep == 1) {
                    if (nextStep1 == false) {
                        swal(jsonLang['Vui lòng chọn nhân viên'], "", "error");

                        r.stop();
                    }
                } else if (r.currentStep == 2) {
                    if (nextStep2 == false) {
                        swal(jsonLang['Vui lòng chọn hoa hồng'], "", "error");

                        r.stop();
                    }
                }
            }), r.on("change", function (e) {
                mUtil.scrollTop()
            }), r.on("change", function (e) {
                1 === e.getStep()

                if (r.currentStep == 3) {
                    //Load bảng phân bổ
                    $.ajax({
                        url: laroute.route('admin.commission.load-allocation'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {},
                        success: function (res) {
                            $('#m_wizard_form_step_3').empty();
                            $('#m_wizard_form_step_3').html(res.html);

                            $('.allocation_priority').select2({
                                width: '100%'
                            });
                        }
                    });

                }
            }), e = i.validate({
                ignore: ":hidden",
                rules: {},
                messages: {},
                invalidHandler: function (e, r) {

                },
                submitHandler: function (e) {
                    var arrCoefficient = {};
                    var arrCommission = [];

                    $.each($('.tr_coefficient').find(".allocation_coefficient"), function (k, v) {
                        var staff_id = $(this).closest('td').find($('.staff_id')).val();
                        var commission_coefficient = $(this).val().replace(new RegExp('\\,', 'g'), '');

                        arrCoefficient[staff_id] = commission_coefficient;

                    });

                    $.each($('#table-allocation').find(".tr_commission"), function (k, v) {
                        var commission_id = $(this).closest('.tr_commission').find('.commission_id').val();

                        var arrayStaff = [];

                        $.each($(this).closest('.tr_commission').find('.check_commission'), function (k, v) {
                            if ($(this).is(':checked')) {
                                arrayStaff.push({
                                    staff_id: $(this).closest('td').find($('.staff_id')).val()
                                });
                            }
                        });

                        arrCommission.push({
                            commission_id: commission_id,
                            arrayStaff: arrayStaff
                        })
                    });

                    $.ajax({
                        url: laroute.route('admin.commission.allocation.submit'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            arrCoefficient: arrCoefficient,
                            arrCommission: arrCommission
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal(res.message, "", "success").then(function (result) {
                                    if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                        window.location.href = laroute.route('admin.commission');
                                    }
                                    if (result.value == true) {
                                        window.location.href = laroute.route('admin.commission');
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
                            swal(jsonLang['Phân bổ thất bại'], mess_error, "error");
                        }
                    });
                }
            });
        }
    }
}();