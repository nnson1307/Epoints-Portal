var indexDebt = {
    detail: function (id) {
        $.ajax({
            url: laroute.route('admin.receipt.detail'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_debt_id: id
            },
            success: function (res) {
                $('#div-detail').html(res.url);
                $('#div-detail').find('#modal-detail').modal({
                    backdrop: 'static', keyboard: false
                });
            }
        });
    },
    receipt: function (id) {
        $.ajax({
            url: laroute.route('admin.receipt.receipt'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                customer_debt_id: id
            },
            success: function (res) {
                $.getJSON(laroute.route('translate'), function (json) {
                    $('#div-receipt').html(res.url);
                    $('#div-receipt').find('#modal-receipt').modal({
                        backdrop: 'static', keyboard: false
                    });
                    //Load sẵn hình thức thanh toán = tiền mặt
                    $('#receipt_type').val('CASH').trigger('change');
                    new AutoNumeric.multiple('#payment_method_CASH', {
                        currencySymbol : '',
                        decimalCharacter : '.',
                        digitGroupSeparator : ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });

                    if ($('#member_money').val() <= 0 || typeof $('#member_money').val() == 'undefined') {
                        $("#receipt_type option[value='MEMBER_MONEY']").remove();
                    } else {
                        $('#receipt_type').append('<option value="MEMBER_MONEY">Tài khoản thành viên</option>');
                    }

                    $('#receipt_type').select2({
                        placeholder: json['Chọn hình thức thanh toán']
                    }).on('select2:select', function (event) {
                        // Lấy id và tên của phương thức thanh toán
                        let methodId = event.params.data.id;
                        let methodName = event.params.data.text;
                        let tpl = $('#payment_method_tpl').html();
                        tpl = tpl.replace(/{label}/g, methodName);
                        tpl = tpl.replace(/{id}/g, methodId);
                        tpl = tpl.replace(/{id}/g, methodId);

                        if(methodId == 'VNPAY'){
                            tpl = tpl.replace(/{displayQrCode}/g, 'block');
                        } else {
                            tpl = tpl.replace(/{displayQrCode}/g, 'none');
                        }

                        if (methodId == 'MEMBER_MONEY') {
                            let money = $('#member_money').val();
                            tpl = tpl.replace(/{money}/g, json['(Còn '] + formatNumber(money) + ')');
                        } else {
                            tpl = tpl.replace(/{money}/g, '*');
                        }

                        $('.payment_method').append(tpl);
                        new AutoNumeric.multiple('#payment_method_' + methodId, {
                            currencySymbol : '',
                            decimalCharacter : '.',
                            digitGroupSeparator : ',',
                            decimalPlaces: decimal_number,
                            eventIsCancelable: true,
                            minimumValue: 0
                        });
                    }).on('select2:unselect', function (event) {
                        // UPDATE 15/03/2021
                        let moneyTobePaid = $('#receipt_amount').val().replace(new RegExp('\\,', 'g'), ''); // tiền phải thanh toán
                        let methodId = event.params.data.id;
                        let amountThis = $('#payment_method_'+ methodId).val().replace(new RegExp('\\,', 'g'), '');
                        $('.payment_method_' + methodId).remove();
                        // tính lại tổng tiền trả (tổng tiền trả ban đầu - tiền unselect)
                        let amountAllOld = $('#amount_all').val().replace(new RegExp('\\,', 'g'), '');
                        let amountAllNew = amountAllOld - amountThis;
                        $('#amount_all').val(formatNumber(amountAllNew.toFixed(decimal_number)));
                        $('.cl_amount_all').text(formatNumber(amountAllNew.toFixed(decimal_number)));
                        // tính lại tiền nợ
                        if (moneyTobePaid - amountAllNew > 0) {
                            $('#amount_rest').val(formatNumber((moneyTobePaid - amountAllNew).toFixed(decimal_number)));
                            $('.cl_amount_rest').text(formatNumber((moneyTobePaid - amountAllNew).toFixed(decimal_number)));
                        } else {
                            $('#amount_rest').val(0);
                            $('.cl_amount_rest').text(0);
                        }
                        // tính lại tiền trả khách
                        if (amountAllNew - moneyTobePaid > 0) {
                            $('#amount_return').val(formatNumber((amountAllNew - moneyTobePaid).toFixed(decimal_number)));
                            $('.cl_amount_return').text(formatNumber((amountAllNew - moneyTobePaid).toFixed(decimal_number)));
                        } else {
                            $('#amount_return').val(0);
                            $('.cl_amount_return').text(0);
                        }
                        // END UPDATE 15/03/2021
                    });
                });
            }
        });
    },
    cancle: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Cảnh báo'],
                text: json['Bạn có muốn hủy công nợ này không'],
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: json['Có'],
                cancelButtonText: json['Không']
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.receipt.cancle'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            customer_debt_id: id
                        },
                        success: function (res) {
                            if (res.error == true) {
                                swal(res.message, "", "error");
                            } else {
                                swal(res.message, "", "success").then(function () {
                                    window.location.reload();
                                });
                            }
                        }
                    });
                }
            });
        });
    },
    submit_receipt: function (id) {
        var receipt_type = $('#receipt_type').val();
        let arrayMethod = {};
        $.each($('.payment_method').find('.method'), function () {
            let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
            let getId = $(this).find("input[name='payment_method']").attr('id');
            let methodCode = getId.slice(15);
            arrayMethod[methodCode] = moneyEachMethod;
        });
        var amount_bill = $('#receipt_amount').val();
        var amount_return = $('#amount_return').val();
        $.getJSON(laroute.route('translate'), function (json) {
            if (receipt_type == '') {
                $('.error_type').text(json['Hãy chọn hình thức thanh toán']);
                return false;
            } else {
                $('.error_type').text('');
            }
            $.ajax({
                url: laroute.route('admin.receipt.submit-receipt'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_debt_id: id,
                    receipt_type: receipt_type,
                    // receipt_cash: receipt_cash,
                    // receipt_atm: receipt_atm,
                    // receipt_visa: receipt_visa,
                    // receipt_money: receipt_money,
                    amount_all: $('#amount_all').val(),
                    array_method: arrayMethod,
                    amount_bill: amount_bill,
                    amount_return: amount_return,
                    member_money: $('#member_money').val(),
                    note: $('#note').val(),
                    receipt_id: $('#debt_receipt_id').val()
                },
                success: function (res) {
                    if (res.error == true) {
                        swal(json["Thanh toán công nợ thất bại"], res.message, "error");
                    } else {
                        swal(json["Thanh toán công nợ thành công"], "", "success");
                        window.location.reload();
                    }
                },
                error: function (res) {
                    swal(json["Thanh toán công nợ thất bại"], "", "error");
                }
            });
        });
    },
    submit_receipt_bill: function (id) {
        var receipt_type = $('#receipt_type').val();
        let arrayMethod = {};
        $.each($('.payment_method').find('.method'), function () {
            let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
            let getId = $(this).find("input[name='payment_method']").attr('id');
            let methodCode = getId.slice(15);
            arrayMethod[methodCode] = moneyEachMethod;
        });
        var amount_bill = $('#receipt_amount').val();
        var amount_return = $('#amount_return').val();
        $.getJSON(laroute.route('translate'), function (json) {
            if (receipt_type == '') {
                $('.error_type').text(json['Hãy chọn hình thức thanh toán']);
                return false;
            } else {
                $('.error_type').text('');
            }
            $.ajax({
                url: laroute.route('admin.receipt.submit-receipt'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    customer_debt_id: id,
                    receipt_type: receipt_type,
                    // receipt_cash: receipt_cash,
                    // receipt_atm: receipt_atm,
                    // receipt_visa: receipt_visa,
                    // receipt_money: receipt_money,
                    amount_all: $('#amount_all').val(),
                    array_method: arrayMethod,
                    amount_bill: amount_bill,
                    amount_return: amount_return,
                    member_money: $('#member_money').val(),
                    note: $('#note').val(),
                    receipt_id: $('#debt_receipt_id').val()
                },
                success: function (res) {
                    if (res.error == true) {
                        swal(json["Thanh toán công nợ thất bại"], res.message, "error");
                    } else {
                        $('#bill-receipt #customer_debt_id').val(id);
                        $('#amount_bill').val(amount_bill);
                        $('#amount_return_bill').val(amount_return);
                        $('#receipt_id').val(res.receipt_id);
                        console.log($('#bill-receipt').serialize());
                        $('#bill-receipt').submit();

                        swal(json["Thanh toán công nợ thành công"], "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.reload();
                            }
                            if (result.value == true) {
                                window.location.reload();
                            }
                        });
                    }
                },
                error: function (res) {
                    swal(json["Thanh toán công nợ thất bại"], "", "error");
                }
            });
        });
    },
    changeAmountReceipt:function (obj) {
        // UPDATE 15/03/2021
        // tính tổng tiền trả
        let total = 0
        $.each($('.payment_method').find('.method'), function () {
            let moneyEachMethod = $(this).find("input[name='payment_method']").val().replace(new RegExp('\\,', 'g'), '');
            total += Number(moneyEachMethod);
        });
        // END UPDATE 15/03/2021
        var amount_all = total;

        $('#amount_all').val(formatNumber(amount_all.toFixed(decimal_number)));
        $('.cl_amount_all').text(formatNumber(amount_all.toFixed(decimal_number)));

        var rest = $('#receipt_amount').val().replace(new RegExp('\\,', 'g'), '');
        if (rest - amount_all > 0) {
            $('#amount_rest').val(formatNumber((rest - amount_all).toFixed(decimal_number)));
            $('.cl_amount_rest').text(formatNumber((rest - amount_all).toFixed(decimal_number)));
            if ($(obj).val() != '') {
                if (rest - amount_all < 0) {
                    $('#amount_return').val(formatNumber((amount_all - rest).toFixed(decimal_number)));
                    $('.cl_amount_return').text(formatNumber((amount_all - rest).toFixed(decimal_number)));
                } else {
                    $('#amount_return').val(0);
                    $('.cl_amount_return').text(0);
                }
            }
        } else {
            if (rest - amount_all == 0){
                $('#amount_rest').val(0);
                $('#amount_return').val(0);
                $('.cl_amount_rest').text(0);
                $('.cl_amount_return').text(0);
            } else {
                $('#amount_rest').val(0);
                $('#amount_return').val(formatNumber((amount_all - rest).toFixed(decimal_number)));
                $('.cl_amount_rest').text(0);
                $('.cl_amount_return').text(formatNumber((amount_all - rest).toFixed(decimal_number)));
            }
        }
    },
    genQrCode: function (obj, methodCode) {
        $.ajax({
            url: laroute.route('admin.receipt.gen-qr-code'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                amount: $(obj).closest('.method').find('input[name="payment_method"]').val().replace(new RegExp('\\,', 'g'), ''),
                payment_method_code: methodCode,
                customer_debt_id: $('#customer_debt_id').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        $('#debt_receipt_id').val(res.receipt_id);
                        window.open(res.url, '_blank');
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    }
};


$('#autotable').PioTable({
    baseUrl: laroute.route('admin.receipt.list')
});

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}