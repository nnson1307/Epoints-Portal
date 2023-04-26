
async function asyncSaveOrderV2(){
    $.getJSON(laroute.route('translate'), function (json) {
        const resultPromise = new Promise((res, rej) => {
            var customer_id = $('#customer_id').val();
            var table_subbmit = [];
            $.each($('#table_add').find(".tr_table"), function () {
                var $tds = $(this).find("input,select");
                var $check_amount = $(this).find("input[name='amount']");
                if ($check_amount.val() < 0) {
                    $('.error-table').text(json['Tổng tiền không hợp lệ']);
                    res(false);
                }
                $.each($tds, function () {
                    table_subbmit.push($(this).val().length == 0 ? '' : $(this).val());
                });
            });
            var voucher_bill = $('#voucher_code_bill').val();
            var total_bill = $('input[name="total_bill"]').val();
            var discount_bill = $('input[name="discount_bill"]').val();
            var amount_bill = $('input[name="amount_bill_input"]').val();
            // var loc_total = total_bill.replace(/\D+/g, '');
            // var loc_discount = discount_bill.replace(/\D+/g, '');
            // var discountCauseBill = $('#discount_causes_bill').val();
            $.ajax({
                url: laroute.route('admin.order.submitAdd'),
                data: {
                    customer_id: customer_id,
                    total_bill: total_bill,
                    discount_bill: discount_bill,
                    amount_bill: amount_bill,
                    table_add: table_subbmit,
                    voucher_bill: voucher_bill,
                    refer_id: $('#refer_id').val(),
                    custom_price: $('#custom_price').val(),
                    order_description: $('[name="order_description"]').val()
                },
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    if (response.table_error == 1) {
                        $('.error-table').text(json['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
                        res(false);
                    }
                    if (response.error == true) {
                        res(true);
                    } else {
                        res(false);
                    }
                }
            });
        });
    });

}async function asyncUpdateOrderV2(){
    $.getJSON(laroute.route('translate'), function (json) {
        const resultPromise = new Promise((res, rej) => {
            var continute = true;
            var table_subbmit = [];
            $.each($('#table_add').find(".tr_table"), function () {
                var $tds = $(this).find("input,select");
                var $check_amount = $(this).find("input[name='amount']");
                if ($check_amount.val() < 0) {
                    $('.error-table').text(json['Tổng tiền không hợp lệ']);
                    res(false);
                    continute = false;
                }
                $.each($tds, function () {
                    table_subbmit.push($(this).val().length == 0 ? '' : $(this).val());
                });
            });

            var table_add = [];
            $.each($('#table_add').find(".table_add"), function () {
                var $tds = $(this).find("input,select");
                var $check_amount = $(this).find("input[name='amount']");
                if ($check_amount.val() < 0) {
                    $('.error-table').text(json['Tổng tiền không hợp lệ']);
                    res(false);
                    continute = false;
                }
                $.each($tds, function () {
                    table_add.push($(this).val().length == 0 ? '' : $(this).val());
                });
            });
            var check_service_card = [];
            $.each($('#table_add').find("tbody tr"), function () {
                var $check_amount = $(this).find("input[name='object_type']");
                if ($check_amount.val() == 'service_card') {
                    $.each($check_amount, function () {
                        check_service_card.push($(this).val());
                    });
                }
            });

            if (table_subbmit == '' && table_add == '') {
                $('.error-table').text(json['Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm']);
                res(false);
            } else {
                $('.error-table').text('');
                var total_bill = $('input[name="total_bill"]').val();
                var discount_bill = $('input[name="discount_bill"]').val();
                var voucher_bill = $('#voucher_code_bill').val();
                var amount_bill = $('input[name="amount_bill_input"]').val();

                var delivery_active = 0;
                if ($('#delivery_active').is(':checked')) {
                    delivery_active = 1;
                }

                $.ajax({
                    url: laroute.route('admin.order.submit-edit'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        table_edit: table_subbmit,
                        table_add: table_add,
                        total_bill: total_bill,
                        discount_bill: discount_bill,
                        voucher_bill: voucher_bill,
                        amount_bill: amount_bill,
                        order_id: id,
                        order_code: $('#order_code').val(),
                        refer_id: $('#refer_id').val(),
                        delivery_active: delivery_active,
                        customer_id: $('#customer_id').val(),
                        custom_price: $('#custom_price').val(),
                        order_description: $('[name="order_description"]').val(),
                        discount_member: $('#member_level_discount').val().replace(new RegExp('\\,', 'g'), '')
                    },
                    success: function (res) {
                        if (res.error == true) {
                            res(false);
                        } else {
                            res(true);
                        }
                    }
                });
            }
        });
    });
}
var processOrderV2 = {
};


function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}