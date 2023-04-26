//
// function discountCustomerInput() {
//     $.getJSON(laroute.route('translate'), function (json) {
//         //Tổng tiền
//         var moneyTotal = $("input[name=total_bill]").val();
//         $('#total-money-discount').val();
//         //Phần trăm giảm.
//         var pt = $('.pt-discount').val();
//         var moneyDiscountCustomer = discountCustomer(moneyTotal, pt);
//         $('.span_member_level_discount').text(formatNumber(moneyDiscountCustomer));
//
//         $('#member_level_discount').val(moneyDiscountCustomer.replace(/\D+/g, ''));
//
//         ////Thành tiền.
//         //Tiền giảm theo m
//         // Member level.
//         var memberLevelDiscount = $('#member_level_discount').val().replace(/\D+/g, '');
//         //Giảm giá
//         var discountBill = $('#discount_bill').val().replace(/\D+/g, '');
//         var amountBill = moneyTotal - memberLevelDiscount - discountBill;
//         $('.amount_bill').empty();
//         $('.amount_bill').append(formatNumber(amountBill) + ' ' + json['đ']);
//         $('.amount_bill').append('<input type="hidden" name="amount_bill_input" class="form-control amount_bill_input" value="' + amountBill + '">');
//     });
// }
// setInterval(function() {
//     discountCustomerInput()
// }, 800/* in milliseconds 1s */);