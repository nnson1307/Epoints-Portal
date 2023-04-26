$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var PrintBill = {
    printBill: function () {
        $.ajax({
            url: laroute.route('admin.receipt.save-print-bill'),
            method: "POST",
            data: {
                customer_debt_id: $('#customer_debt_id').val()
            },
            async:false,
            success: function (data) {
                if (typeof data.error === "undefined" || data.error == '') {
                    $('.error-print-bill').empty();
                    $("#PrintArea").print();
                    window.onafterprint = function(e){
                        $(window).off('mousemove', window.onafterprint);
                        location.reload();
                    };
                    setTimeout(function(){
                        $(window).one('mousemove', window.onafterprint);
                    }, 100);
                } else {
                    $('.error-print-bill').text(data.error);
                }
            }
        });
    },
    back:function () {
        window.top.close();
    }
};