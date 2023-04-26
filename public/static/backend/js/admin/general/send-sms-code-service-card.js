var ORDERGENERAL = {
    sendEachSmsServiceCard: function (code) {

        $('.hidden-code-sercard').val(code);

        swal({
            title: 'Gửi SMS',
            text: "Bạn có muốn gửi sms không?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Có',
            cancelButtonText: 'Không',
        }).then(function (willDelete) {
            var orderId = $('.hiddenOrderIdss').val();
            var arrayCode = new Array();
            arrayCode.push(code);

            $.ajax({
                url: laroute.route('admin.sms.send-code-service-card'),
                method: 'POST',
                data: {
                    orderId: orderId,
                    arrayCode: arrayCode,
                    phone: 0
                },
                success: function (response) {
                    if (response['error'] == 'notphone') {
                        $('#modal-enter-phone-number').modal('show');
                    } else {
                        $('#modal-enter-phone-number').modal('hide');
                        swal("Gửi tin nhắn thành công", "", "success");
                    }
                }
            })
        });
    },
    enterPhoneNumber: function (o) {
        $(o).on('keydown', function (e) {
            -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110])
            || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true))
            && (!0 === e.ctrlKey || !0 === e.metaKey)
            || 35 <= e.keyCode && 40 >= e.keyCode
            || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode)
            && e.preventDefault()
        });
    },
    testPhoneNumber: function (number) {
        if (number.length >= 10 && number.length < 13) {
            return true;
        } else {
            return false;
        }
    },
    sendSms: function () {
        var orderId = $('.hiddenOrderIdss').val();
        var code = $('.hidden-code-sercard').val();
        var arrayCode = new Array();
        var type = $('.hidden-type-sms').val();

        arrayCode.push(code);

        var phone = $('#enter-phone-number').val();

        if (ORDERGENERAL.testPhoneNumber(phone) == true) {
            $('.error-enter-phone-number').text("");
            $.ajax({
                url: laroute.route('admin.sms.send-code-service-card'),
                method: 'POST',
                data: {
                    orderId: orderId,
                    arrayCode: arrayCode,
                    phone: phone,
                    type: type
                },
                success: function (response) {
                    $('#modal-enter-phone-number').modal('hide');
                    swal(
                        'Gửi sms thành công',
                        '',
                        'success'
                    );
                }
            })
        } else {
            $('.error-enter-phone-number').text("Nhập lại SĐT")
        }
    },
    sendAllCodeCard: function () {
        $('.hidden-type-sms').val('all');
        swal({
            title: 'Gửi SMS',
            text: "Bạn có muốn gửi sms khôngs?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Có',
            cancelButtonText: 'Không',
        }).then(function (willDelete) {
            var orderId = $('.hiddenOrderIdss').val();
            $.ajax({
                url: laroute.route('admin.sms.send-all-code-service-card'),
                method: 'POST',
                data: {
                    orderId: orderId,
                    phone: 0
                },
                success: function (response) {
                    if (response['error'] == 'notphone') {
                        $('#modal-enter-phone-number').modal('show');
                    } else {
                        $('#modal-enter-phone-number').modal('hide');
                        swal("Gửi tin nhắn thành công", "", "success");
                    }
                }
            })
        });
    },
    sendSmsAndPrint: function () {
        //Gửi sms.
        ORDERGENERAL.sendAllCodeCard();
    }
};

// $("input[name=price]").on('keyup', function () {
//     var n = parseInt($(this).val().replace(/\D/g, ''), 10);
//     if (typeof n == 'number' && Number.isInteger(n))
//         $(this).val(n);
//     else {
//         $(this).val("");
//     }
// });
