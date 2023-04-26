"use strict";

// Class definition
var page_wizard = 1;
var arr_service = [];
var list_step2 = new KTPortlet('list_step2');
var list_step6 = new KTPortlet('list_step6');
var KTWizard3 = function () {
    // Base elements
    var wizardEl;
    var formEl;
    var validator;
    var wizard;

    // Private functions
    var initWizard = function () {
        // Initialize form wizard
        wizard = new KTWizard('kt_wizard_v3', {
            startStep: 1,
            manualStepForward: false
        });

        // Validation before going to next page
        wizard.on('beforeNext', function (wizardObj) {
            if (validator.form() !== true) {
                wizardObj.stop();  // don't go to the next step
            }
        });

        // Change event
        wizard.on('change', function (wizard) {

            if(wizard.currentStep==1) {
                $('#tab1').attr('class', 'active');
                $('#tab2').attr('class', '');
                $('#tab3').attr('class', '');
                $('#tab4').attr('class', '');
                $('#tab5').attr('class', '');
                $('#tab6').attr('class', '');

            }
            if(wizard.currentStep==2)
            {
                $('#tab1').attr('class','');
                $('#tab2').attr('class','active');
                $('#tab3').attr('class','');
                $('#tab4').attr('class','');
                $('#tab5').attr('class','');
                $('#tab6').attr('class','');

            }
            if(wizard.currentStep==3)
            {
                $('#tab1').attr('class','');
                $('#tab2').attr('class','');
                $('#tab3').attr('class','active');
                $('#tab4').attr('class','');
                $('#tab5').attr('class','');
                $('#tab6').attr('class','');

            }
            if(wizard.currentStep==4)
            {
                $('#tab1').attr('class','');
                $('#tab2').attr('class','');
                $('#tab3').attr('class','');
                $('#tab4').attr('class','active');
                $('#tab5').attr('class','');
                $('#tab6').attr('class','');

            }
            if(wizard.currentStep==5)
            {
                $('#tab1').attr('class','');
                $('#tab2').attr('class','');
                $('#tab3').attr('class','');
                $('#tab4').attr('class','');
                $('#tab5').attr('class','active');
                $('#tab6').attr('class','');

            }
            if(wizard.currentStep==6)
            {
                $('#tab1').attr('class','');
                $('#tab2').attr('class','');
                $('#tab3').attr('class','');
                $('#tab4').attr('class','');
                $('#tab5').attr('class','');
                $('#tab6').attr('class','active');

            }

            KTUtil.scrollTop();
        });


    };


    var initValidation = function () {

        validator = formEl.validate({
            // Validate only visible fields
            ignore: ":hidden",

            // Validation rules
            rules: {
                full_name: {
                    required: true
                },
                phone: {
                    required: true,
                    number:true
                }
            },
            messages:{
                full_name:{
                    required:'Hãy nhập họ & tên'
                },
                phone:{
                    required:'Hãy nhập số điện thoại',
                    number:'Số điện thoại không hợp lệ'
                }
            },

            // Display error
            invalidHandler: function (event, validator) {

                if (validator) {
                    page_wizard--;
                }

                KTUtil.scrollTop();

                // swal.fire({
                //     "title": "",
                //     "text": "There are some errors in your submission. Please correct them.",
                //     "type": "error",
                //     "confirmButtonClass": "btn btn-secondary"
                // });
            },

            // Submit valid form
            submitHandler: function (form) {

            }
        });
    };

    var initSubmit = function () {
        var next = formEl.find('[data-ktwizard-type="action-next"]');
        var prev = formEl.find('[data-ktwizard-type="action-prev"]');
        var btn = formEl.find('[data-ktwizard-type="action-submit"]');

        next.on('click', function (e) {

            if (page_wizard == 1) {
                if ($('input[name=branch]:checked').val() == undefined) {
                    setTimeout(function () {
                        toastr.warning("Vui lòng chọn chi nhánh")
                    }, 60);
                    prev.trigger('click');
                }

            }

            if (page_wizard == 2) {
                if ($('.btn.color-button.time').val() == undefined) {
                    setTimeout(function () {
                        toastr.warning("Vui lòng chọn thời gian đặt lịch")
                    }, 60);
                    prev.trigger('click');
                }
            }

            if (page_wizard == 4) {
                // if ($('input[name=staff]:checked').val() == undefined) {
                //     setTimeout(function () {
                //         toastr.warning("Vui lòng chọn kỹ thuật viên")
                //     }, 60);
                //     prev.trigger('click');
                // }
            }

            if(page_wizard==5)
            {
                KTApp.block(list_step6.getSelf(), {
                    type: 'loader',
                    state: 'brand',
                    message: 'Loading...'
                });
                $.ajax({
                    url:laroute.route('booking.confirm'),
                    method:'POST',
                    dataType:'JSON',
                    data:{
                        branch_id:$('input[name=branch]:checked').val(),
                        branch_name:$('input[name=branch]:checked').data('branch'),
                        staff_name:$('input[name=staff]:checked').data('staff'),
                        date:$('.btn.color-button.time').data('date'),
                        time:$('.btn.color-button.time').val(),
                        customer_name:$('#full_name').val(),
                        phone:$('#phone').val(),
                        email:$('#email').val(),
                        description:$('#description').val(),
                        arr_service:arr_service
                    },
                    success:function (res) {
                        KTApp.unblock(list_step6.getSelf());
                        $('#list_step6').html(res);
                    }
                });
            }

            page_wizard++;
        });

        prev.on('click', function (e) {
            page_wizard--;
        });

        btn.on('click', function (e) {
            e.preventDefault();

            if (validator.form()) {
                // See: src\js\framework\base\app.js
                KTApp.progress(btn);
                //KTApp.block(formEl);

                // See: http://malsup.com/jquery/form/#ajaxSubmit
                formEl.ajaxSubmit({
                    url:laroute.route('booking.submit-booking'),
                    method:'POST',
                    dataType:'JSON',
                    data:{
                        branch_id:$('input[name=branch]:checked').val(),
                        service_id:arr_service,
                        staff_id:$('input[name=staff]:checked').val(),
                        date:$('.btn.color-button.time').data('date'),
                        time:$('.btn.color-button.time').val(),
                        fullname:$('#full_name').val(),
                        phone:$('#phone').val(),
                        email:$('#email').val(),
                        description:$('#description').val(),
                    },
                    success: function (res) {

                        KTApp.unprogress(btn);
                        //KTApp.unblock(formEl);

                        if (res.error == true) {
                            var mess_error = '';
                            $.map(res._error, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal.fire("Đặt lịch thất bại!", mess_error, "error");

                        }else{
                            swal.fire("Đặt lịch thành công!", "", "success").then(function (result) {
                                if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                    window.location.href=laroute.route('booking');
                                }
                                if (result.value == true) {
                                    window.location.href=laroute.route('booking');
                                }
                            });
                        }


                    }
                });
            }
        });
    };

    return {
        // public functions
        init: function () {
            wizardEl = KTUtil.get('kt_wizard_v3');
            formEl = $('#kt_form');

            initWizard();
            initValidation();
            initSubmit();
        }
    };
}();


var step1 = {
    _init: function () {
        $('#province_id').select2({
            placeholder: 'Chọn tỉnh/thành'
        });
        $('#district_id').select2({
            placeholder: 'Chọn quận/huyện'
        });

        $('#province_id').change(function () {
            $.ajax({
                url: laroute.route('get-district'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    province_id: $(this).val()
                },
                success: function (res) {
                    $('#district_id').empty();
                    $('#district_id').append('<option></option>');
                    $.map(res, function (value, key) {
                        $('#district_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            })
        });
    },
    filter: function () {
        $.ajax({
            url: laroute.route('booking.filter-branch'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                province_id: $('#province_id').val(),
                district_id: $('#district_id').val()
            },
            success: function (res) {
                $('.list-branch').html(res);
            }
        });
    },
    check_branch: function (branch_id) {
        $.ajax({
            url: laroute.route('booking.check-branch'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                branch_id: branch_id
            },
            success: function (res) {

                //step service
                $('#service_id').empty();
                $('#service_id').append('<option></option>');
                $.map(res.optionService, function (a) {
                    $('#service_id').append('<option value="' + a.service_id + '">' + a.service_name + '</option>');
                });
                $('#list_step3').html(res.view_service);

                arr_service = [];

                //step kỹ thuật viên
                $('#list_step4').html(res.view_staff);

            }
        });
    }
};

var step_time = {
    click_time: function (obj, date) {
        $(obj).attr('class', 'btn color-button time btn-pill btn-sm');

        $.each($('#table-time').find(".tr-table"), function () {
            var button = $(this).find("button.btn.color-button.time");
            $.each(button, function () {

                if ($(this).data('date') != date || $(this).val() != $(obj).val()) {
                    $(this).attr('class', 'btn btn-light btn-elevate btn-pill btn-sm');
                }

            });
        });
    },
    preAndNextPage: function (page) {
        KTApp.block(list_step2.getSelf(), {
            type: 'loader',
            state: 'brand',
            message: 'Loading...'
        });

        $.ajax({
            url: laroute.route('booking.paging-time'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                page: page
            },
            success: function (res) {
                KTApp.unblock(list_step2.getSelf());
                $('.list_step2').html(res);
            }
        });
    }
};
var step3 = {
    _init: function () {
        $('#service_id').select2({
            placeholder: 'Chọn dịch vụ',
            allowClear: true
        });
    },
    pageClick: function (page) {
        $.ajax({
            url: laroute.route('booking.paging-service'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                branch_id: $('input[name=branch]:checked').val(),
                page: page,
                arr_service: arr_service
            },
            success: function (res) {
                $('#list_step3').html(res);
            }
        });
    },
    firstAndLastPage: function (page) {
        $.ajax({
            url: laroute.route('booking.paging-service'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                branch_id: $('input[name=branch]:checked').val(),
                page: page,
                arr_service: arr_service
            },
            success: function (res) {
                $('#list_step3').html(res);
            }
        });
    },
    filter: function (obj) {
        $.ajax({
            url: laroute.route('booking.filter-service'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                branch_id: $('input[name=branch]:checked').val(),
                service_id: $(obj).val(),
                arr_service: arr_service
            },
            success: function (res) {
                $('#list_step3').html(res);

            }
        })
    },
    check_service: function (obj) {
        if ($(obj).is(':checked')) {
            arr_service.push($(obj).val());
        } else {
            var i = arr_service.indexOf($(obj).val());
            if (i != -1) {
                arr_service.splice(i, 1);
            }
        }
    }
};
var step4 = {
    _init: function () {

    }
};

jQuery(document).ready(function () {
    KTWizard3.init();
    step1._init();
    step3._init();
    step4._init();



});