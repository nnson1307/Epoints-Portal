var vnpay = {
    createQrCode: function(e, object_type = 'order'){
        $.getJSON(laroute.route('translate'), function (json) {
            var money = $(e).parent('div').parent('div').find('[name="payment_method"]').val().replace(new RegExp('\\,', 'g'), '');
            if (money < 5000) {
                swal(json["Vui lòng nhập số tiền hợp lệ (Lớn hơn 5,000 và bé hơn 1 tỷ)"],'','error')
            } else {
                $.ajax({
                    url: laroute.route('admin.order.create-qrcode-vnpay'),
                    data: {
                        money: money,
                        order_id: $('#order_id').val(),
                        order_code: $('#order_code').val(),
                        customer_id: $('#customer_id').val(),
                        object_type: object_type
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (response) {
                        if(!response.error){
                            window.open(response.data.payment_url,'_blank');
                        } else {
                            swal(response.message, '', 'error')
                        }
                    }
                });
            }
        });
    },
};