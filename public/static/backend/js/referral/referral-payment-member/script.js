var referralPaymentMember = {

    referral_payment_id : null,

    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('referral.referral-payment-member.list', {id : referralPaymentMember.referral_payment_id}),
            perPage: 25
        });

    },

    clickAll : function (){
        let checked = $('#select-all').is(':checked');;
        if(checked) {
            console.log(2);
            // Iterate each checkbox
            $('.checkbox-all').each(function() {
                this.checked = true;
            });
        } else {
            console.log(1);
            $('.checkbox-all').each(function() {
                this.checked = false;
            });
        }
    },

    reject : function (referral_payment_member_id){
        jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        Swal.fire({
            title: jsonLang['Từ chối thanh toán'],
            html : jsonLang['Bạn xác nhận từ chối thanh toán , thao tác này sẽ không hoàn lại được sau khi thực hiện'],
            buttonsStyling: false,

            confirmButtonText: jsonLang['Xác nhận'],
            confirmButtonClass: "btn btn-primary btn-hover-brand mt-0",
            reverseButtons: true,
            showCancelButton: true,
            cancelButtonText: jsonLang['Hủy'],
            cancelButtonClass: "btn btn-secondary btn-hover-brand mt-0"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('referral.referral-payment-member.rejectPayment'),
                    method: 'POST',
                    data: {
                        referral_payment_member_id: referral_payment_member_id,
                    },
                    success: function (res) {
                        if (res.error == false) {
                            Swal.fire(res.message,'', "success").then(function () {
                                location.reload();
                            });
                        } else {
                            Swal.fire(res.message, '', "error");
                        }
                    }
                });
            }
        })
    },

    rejectAll : function (referral_payment_member_id){
        jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        Swal.fire({
            title: jsonLang['Từ chối thanh toán'],
            html : jsonLang['Bạn xác nhận từ chối thanh toán , thao tác này sẽ không hoàn lại được sau khi thực hiện'],
            buttonsStyling: false,

            confirmButtonText: jsonLang['Xác nhận'],
            confirmButtonClass: "btn btn-primary btn-hover-brand mt-0",
            reverseButtons: true,
            showCancelButton: true,
            cancelButtonText: jsonLang['Hủy'],
            cancelButtonClass: "btn btn-secondary btn-hover-brand mt-0"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('referral.referral-payment-member.rejectPayment'),
                    method: 'POST',
                    data: {
                        referral_payment_member_id: $('input[name^="referral_payment_member_id"]').serializeArray(),
                    },
                    success: function (res) {
                        if (res.error == false) {
                            Swal.fire(res.message,'', "success").then(function () {
                                location.reload();
                            });
                        } else {
                            Swal.fire(res.message, '', "error");
                        }
                    }
                });
            }
        })
    },
}
