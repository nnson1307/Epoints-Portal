$('.class-select2').select2();

$(".class-number").on('keyup', function () {
    var n = parseInt($(this).val().replace(/\D/g, ''), 10);
    if (typeof n == 'number' && Number.isInteger(n))
        $(this).val(n);
    else {
        $(this).val("");
    }
});

$('#is_print_reply').change(function () {
    if ($('#is_print_reply').is(':checked')){
       $('#print_time').prop('disabled',false);
        $('#print_time').val('');
    }else {
        $('#print_time').val(1);
        $('#print_time').prop('disabled',true);
    }
});

var Config={
    onKeyDownInputNumber:function (o) {
        $(o).on('keydown', function (e) {
            -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110])
            || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true))
            && (!0 === e.ctrlKey || !0 === e.metaKey)
            || 35 <= e.keyCode && 40 >= e.keyCode
            || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode)
            && e.preventDefault()
        });
    }
};

// $('.save-change').click(function () {
//    $.ajax({
//        url:laroute.route('admin.config-print-bill.submitEdit'),
//        method:"POST",
//        data:{
//            template:$('#template').val(),
//            printed_sheet:$('#printed_sheet').val(),
//            is_print_reply:$('#is_print_reply').val(),
//            print_time:$('#print_time').val(),
//            is_show_logo:$('#is_show_logo').val(),
//            is_show_unit:$('#is_show_unit').val(),
//            is_show_address:$('#is_show_address').val(),
//            is_show_phone:$('#is_show_phone').val(),
//            is_show_order_code:$('#is_show_order_code').val(),
//            is_show_cashier:$('#is_show_cashier').val(),
//            is_show_customer:$('#is_show_customer').val(),
//            is_show_datetime:$('#is_show_datetime').val(),
//            is_show_footer:$('#is_show_footer').val(),
//        },
//        success:function (data) {
//
//        }
//    })
// });
