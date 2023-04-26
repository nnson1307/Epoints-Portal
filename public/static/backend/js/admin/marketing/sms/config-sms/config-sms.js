$('.select-picker').selectpicker();

// $('#is_actived').click(function () {
//     if ($(this).is(':checked')) {
//         $('.m---content').show();
//     } else {
//         $('.m---content').hide();
//     }
// });

var ConfigSms = {
    config: function (button) {
        var modal = $('#modal-config-sms');
        var chooseTime = $('.choose-time');
        var chooseDay = $('.choose-day');
        var chooseHour = $('.choose-hour');
        var sms = $('#sms-type');
        var message = $('#message-content');
        var numberChar = $('#amount-character');
        message.empty();
        let customerName = $('#parameter-customer-name').html();
        let customerFullName = $('#parameter-customer-full-name').html();
        let gender = $('#parameter-customer-gender').html();
        let dayTimeAppointment = $('#parameter-day-time-appointment').html();
        let codeAppointment = $('#parameter-code-appointment').html();
        let nameSpa = $('#parameter-name-spa').html();
        let datetime = $('#parameter-datetime').html();
        let codeCard = $('#parameter-code-card').html();
        let paramOtp = $('#parameter-otp').html();
        let paramObjectType = $('#parameter-object-type').html();
        let paramObjectName = $('#parameter-object-name').html();

        let title = $('.title-setting');
        $('.parameter').empty();
        $('.parameter2').empty();
        title.empty();
        $.getJSON(laroute.route('translate'), function (json) {
            if (button == 'birthday') {
                chooseDay.hide();
                chooseHour.hide();
                chooseTime.show();
                sms.val('birthday');
                message.val($('#message-birthday').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter').append(gender);
                ConfigSms.countCharacter('#message-content');
                $("#send-time").timepicker({
                    minuteStep: 15,
                    defaultTime: $('#time-send-birthday').val(),
                    showMeridian: !1,
                    snapToStep: !0,
                });
                title.text(json['Cài đặt tin nhắn chúc mừng sinh nhật']);
                modal.modal('show');
            } else if (button == 'paysuccess') {
                chooseDay.hide();
                chooseTime.hide();
                chooseHour.hide();
                sms.val('paysuccess');
                message.val($('#message-paysuccess').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter').append(nameSpa);
                $('.parameter2').append(gender);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                title.text(json['Cài đặt tin nhắn thanh toán thành công']);
                modal.modal('show');
            } else if (button == 'new_customer') {
                chooseDay.hide();
                chooseTime.hide();
                chooseHour.hide();
                sms.val('new_customer');
                message.val($('#message-new-customer').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter').append(nameSpa);
                $('.parameter2').append(gender);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                title.text(json['Cài đặt tin nhắn đăng ký thành viên mới']);
                modal.modal('show');
            } else if (button == 'new_appointment') {
                chooseDay.hide();
                chooseTime.hide();
                chooseHour.hide();
                sms.val('new_appointment');
                message.val($('#message-new-calendar').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter').append(gender);
                $('.parameter2').append(dayTimeAppointment);
                $('.parameter2').append(codeAppointment);
                $('.parameter2').append(nameSpa);
                $('.time-hen').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                title.text(json['Cài đặt tin nhắn lịch hẹn mới']);
                modal.modal('show');
            } else if (button == 'cancel_appointment') {
                chooseDay.hide();
                chooseTime.hide();
                chooseHour.hide();
                sms.val('cancel_appointment');
                message.val($('#message-cancel-calendar').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter').append(codeAppointment);
                $('.parameter2').append(gender);
                $('.parameter2').append(nameSpa);

                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                title.text(json['Cài đặt tin nhắn hủy lịch hẹn']);
                modal.modal('show');
            } else if (button == 'remind_appointment') {
                chooseTime.hide();
                chooseDay.hide();
                chooseHour.show();
                sms.val('remind_appointment');
                message.val($('#message-remind-calendar').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter').append(datetime);
                $('.parameter2').append(gender);
                $('.parameter2').append(nameSpa);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                $('#hour').val($('#value-remind-appointment').val());
                $('#hour').selectpicker('refresh');
                title.text(json['Cài đặt tin nhắn nhắc lịch hẹn']);
                modal.modal('show');
            } else if (button == 'service_card_expires') {
                chooseDay.hide();
                chooseTime.hide();
                chooseHour.hide();
                sms.val('service_card_expires');
                message.val($('#message-service-card-expired').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter').append(codeCard);
                $('.parameter2').append(gender);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                title.text(json['Cài đặt tin nhắn thẻ dịch vụ hết hạn']);
                modal.modal('show');
            } else if (button == 'service_card_over_number_used') {
                chooseDay.hide();
                chooseTime.hide();
                chooseHour.hide();
                sms.val('service_card_over_number_used');
                message.val($('#message-service-card-over-number-used').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter').append(codeCard);
                $('.parameter2').append(gender);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                title.text(json['Cài đặt tin nhắn thẻ dịch vụ hết số lần sử dụng']);
                modal.modal('show');
            } else if (button == 'service_card_nearly_expired') {
                chooseTime.hide();
                chooseHour.hide();
                chooseDay.show();
                sms.val('service_card_nearly_expired');
                message.val($('#message-service-card-nearly-expired').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter2').append(gender);
                $('.parameter2').append(datetime);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                $('#number-day').val($('#value-service-card-nearly-expired').val());
                $('#number-day').selectpicker('refresh');
                // $('#number-day').val($('#value-service-card-nearly-expired').val());
                title.text(json['Cài đặt tin nhắn thẻ dịch vụ sắp hết hạn']);
                modal.modal('show');
            } else if (button == 'delivery_note') {
                chooseTime.hide();
                chooseHour.hide();
                chooseDay.show();
                sms.val('delivery_note');
                message.val($('#message-delivery-note').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter2').append(gender);
                $('.parameter2').append(datetime);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                $('#number-day').val($('#value-service-card-nearly-expired').val());
                $('#number-day').selectpicker('refresh');
                title.text(json['Cài đặt tin nhắn tạo phiếu giao hàng']);
                modal.modal('show');
            } else if (button == 'confirm_deliveried') {
                chooseTime.hide();
                chooseHour.hide();
                chooseDay.show();
                sms.val('confirm_deliveried');
                message.val($('#message-confirm-deliveried').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter2').append(gender);
                $('.parameter2').append(datetime);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                $('#number-day').val($('#value-service-card-nearly-expired').val());
                $('#number-day').selectpicker('refresh');
                title.text(json['Cài đặt tin nhắn xác nhận giao hàng thành công']);
                modal.modal('show');
            } else if (button == 'order_success') {
                chooseTime.hide();
                chooseHour.hide();
                chooseDay.show();
                sms.val('order_success');
                message.val($('#message-order-success').val());
                $('.parameter').append(customerName);
                $('.parameter').append(customerFullName);
                $('.parameter2').append(gender);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                $('#number-day').val($('#value-service-card-nearly-expired').val());
                $('#number-day').selectpicker('refresh');
                title.text(json['Cài đặt tin nhắn đặt hàng thành công']);
                modal.modal('show');
            } else if (button == 'active_warranty_card') {
                chooseTime.hide();
                chooseHour.hide();
                chooseDay.hide();
                sms.val('active_warranty_card');
                message.val($('#message-active-warranty-card').val());
                $('.parameter').append(customerFullName);
                $('.parameter2').append(gender);
                $('.parameter2').append(datetime);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                title.text(json['Cài đặt tin nhắn kích hoạt thẻ bảo hành thành công']);
                modal.modal('show');
            } else if (button == 'otp') {
                chooseTime.hide();
                chooseHour.hide();
                chooseDay.hide();
                sms.val('otp');
                message.val($('#message-otp').val());
                $('.parameter').append(paramOtp);
                $('.gioitinh').removeClass('m--margin-left-10');
                ConfigSms.countCharacter('#message-content');
                title.text(json['Cài đặt OTP']);
                modal.modal('show');
            } else if (button == 'is_remind_use') {
                chooseTime.hide();
                chooseHour.hide();
                chooseDay.hide();
                sms.val('is_remind_use');
                message.val($('#message-remind-use').val());
                $('.parameter').append(customerFullName);
                $('.parameter').append(gender);
                $('.parameter').append(datetime);
                $('.parameter').append(paramObjectType);
                $('.parameter2').append(paramObjectName);
                ConfigSms.countCharacter('#message-content');
                title.text(json['Gửi nhắc sử dụng lại dịch vụ/ sản phẩm/ thẻ dịch vụ']);
                modal.modal('show');
            }
        });
        $("#send-time").timepicker({
            minuteStep: 15,
            defaultTime: "12:00:00",
            showMeridian: !1,
            snapToStep: !0,
        });
    },
    insertAtCaret: function (text) {
        var txtarea = document.getElementById('message-content');
        var scrollPos = txtarea.scrollTop;
        var caretPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0, caretPos);
        var back = (txtarea.value).substring(txtarea.selectionEnd, txtarea.value.length);
        txtarea.value = front + text + back;
        caretPos = caretPos + text.length;
        txtarea.selectionStart = caretPos;
        txtarea.selectionEnd = caretPos;
        txtarea.focus();
        txtarea.scrollTop = scrollPos;
    },
    valueParameter: function (o) {
        if (o == "customer-name") {
            ConfigSms.insertAtCaret("{CUSTOMER_NAME}");
        }
        if (o == "customer-birthday") {
            ConfigSms.insertAtCaret("{CUSTOMER_BIRTHDAY}");
        }
        if (o == "customer-gender") {
            ConfigSms.insertAtCaret("{CUSTOMER_GENDER}");
        }
        if (o == "full-name") {
            ConfigSms.insertAtCaret("{CUSTOMER_FULL_NAME}");
        }
        if (o == "day-appointment") {
            ConfigSms.insertAtCaret("{DATETIME_APPOINTMENT}");
        }
        if (o == "time-appointment") {
            ConfigSms.insertAtCaret("{TIME_APPOINTMENT}");
        }
        if (o == "code-appointment") {
            ConfigSms.insertAtCaret("{CODE_APPOINTMENT}");
        }
        if (o == "name-spa") {
            ConfigSms.insertAtCaret("{NAME_SPA}");
        }
        if (o == "datetime") {
            ConfigSms.insertAtCaret("{DATETIME}");
        }
        if (o == "code-card") {
            ConfigSms.insertAtCaret("{CODE_CARD}");
        }

        if (o == "otp") {
            ConfigSms.insertAtCaret("{CODE}");
        }

        if (o == "object_type") {
            ConfigSms.insertAtCaret("{OBJECT_TYPE}");
        }

        if (o == "object_name") {
            ConfigSms.insertAtCaret("{OBJECT_NAME}");
        }

        $('.count-character').text($('#message-content').val().length);
        ConfigSms.countCharacter('#message-content');
    },
    countCharacter: function (o) {
        let flag = true;
        let lengths = $(o).val().length;
        $('.count-character').text(lengths);
        if (lengths > 480) {
            $.getJSON(laroute.route('translate'), function (json) {
                $('.error-count-character').text(json['Vượt quá 480 ký tự.']);
            });
            flag = false;
        } else {
            $('.error-count-character').text('');
        }
        $(o).val(ConfigSms.changeAlias(o));
        return flag;
    },
    changeAlias: function (alias) {
        var str = $(alias).val();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
        str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
        str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
        str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
        str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
        str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
        str = str.replace(/Đ/g, "D");

        return str;
    },
    onKeyDownInput: function (o) {
        $(o).on('keydown', function (e) {
            -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110])
            || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true))
            && (!0 === e.ctrlKey || !0 === e.metaKey)
            || 35 <= e.keyCode && 40 >= e.keyCode
            || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode)
            && e.preventDefault()
        });
    },
    saveChange: function () {
        let active = 0;
        let flag = 1;
        $.getJSON(laroute.route('translate'), function (json) {
            if ($('#is_actived').is(':checked')) {
                if ($('#value').val() == "") {
                    $('.error-value').text(json['Vui lòng nhập Số/Tên brandname']);
                    flag = 0;
                } else {
                    $('.error-value').text('');
                }
                if ($('#account').val() == "") {
                    $('.error-account').text(json['Vui lòng nhập API key  hoặc Username']);
                    flag = 0;
                } else {
                    $('.error-account').text('');
                }
                if ($('#password').val() == "") {
                    $('.error-password').text(json['Vui lòng nhập API key  hoặc Password']);
                    flag = 0;
                } else {
                    $('.error-password').text('');
                }
                if ($('#provider option:selected').val() == 'clicksend') {

                    var value = $('#value').val();
                    if (value.length < 3 || value.length > 11) {
                        $('.error-value').text(json['Số/Tên brandname từ 3 -> 11 ký tự']);
                        flag = 0;
                    } else if (/^[a-zA-Z0-9]*$/.test(value) == false) {
                        $('.error-value').text(json['Số/Tên brandname không có ký tự đặt biệt và không có khoảng trắng']);
                        flag = 0;
                    } else {
                        $('.error-value').text('');
                    }
                }
                active = 1;
            }
            if (flag == 1) {
                $.ajax({
                    url: laroute.route('admin.sms.config'),
                    method: 'POST',
                    data: {
                        brand_name_id: $('#brand_name_id').val(),
                        is_actived: active,
                        provider: $('#provider').val(),
                        type: $('#type').val(),
                        value: $('#value').val(),
                        account: $('#account').val(),
                        password: $('#password').val(),
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.error == 0) {
                            swal(
                                json['Cập nhật cấu hình sms thành công'],
                                '',
                                'success'
                            );
                            $('#modal-config').modal('hide');
                        }
                    }
                });
            }
        });
    },
    getConfig: function (id) {
        $.ajax({
            url: laroute.route('admin.sms.get-config'),
            method: 'POST',
            data: {
                brand_name_id: id
            },
            dataType: 'JSON',
            success: function (data) {
                if (data.is_actived == 1) {
                    $('#is_actived').prop('checked', true);
                }
                $('#provider').val(data.provider)
                $('#provider').selectpicker('refresh');
                $('#type').val(data.type)
                $('#type').selectpicker('refresh');

                $('#value').val(data.value);
                $('#account').val(data.account);
                $('#password').val(data.password);
                $('#brand_name_id').val(data.id);
            }
        })
    },
    activedSmsConfig: function (o, smsType) {
        let actived = 0;
        if ($(o).is(':checked')) {
            actived = 1;
        }
        $.ajax({
            url: laroute.route('admin.sms.active-sms-config'),
            method: 'POST',
            data: {
                smsType: smsType,
                actived: actived
            }
        });
    },
    contentMessageChange: function () {
        ConfigSms.countCharacter('#message-content')
    },
    saveConfigTypeSms: function () {
        let type = $('#sms-type').val();
        let timeSend = $('#send-time').val();
        let numberDay = $('#number-day').val();
        let hour = $('#hour').val();
        let messageContent = $('#message-content').val();

        if (ConfigSms.countCharacter('#message-content') == true) {
            $.ajax({
                url: laroute.route('admin.sms.setting-sms'),
                method: 'POST',
                data: {
                    type: type,
                    timeSend: timeSend,
                    numberDay: numberDay,
                    hour: hour,
                    messageContent: messageContent,
                },
                dataType: 'JSON',
                success: function (data) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (data.error == 0) {
                            swal(json["Cập nhật tin nhắn thành công"], "", "success");
                            $('#modal-config-sms').modal('hide');
                            window.location.reload();
                        } else {
                            swal(json["Cập nhật tin thất bại"], "", "warning");
                        }
                    });
                }
            });
        }
    }
};
// $("#send-time").timepicker({
//     minuteStep: 15,
//     defaultTime: "12:00:00",
//     showMeridian: !1,
//     snapToStep: !0,
// });
$('#choose-coupon').selectpicker();
$('#hour').selectpicker();
// $('#number-day').change(function () {
//     if ($('#number-day').val() == "") {
//         $('#number-day').val(0);
//     }
// });
$('#number-day').selectpicker();

$('.tesssss').click(function(){
    $.ajax({
        url:laroute.route('admin.sms.send-sms-test'),
        method:"POST",
        data:{}
    })
});
var BootstrapSwitch = {
    init: function () {
        $("#is_actived").bootstrapSwitch({
            onText: 'Bật',
            offText: 'Tắt'
        }).on('switchChange.bootstrapSwitch', function (ev) {
            if (ev.target.checked) {
                $('.m---content').show();
            } else {
                $('.m---content').hide();
            }
        });
    }
};
jQuery(document).ready(function () {
    BootstrapSwitch.init()
});
