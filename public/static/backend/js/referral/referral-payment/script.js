var referralPayment = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('referral.referral-payment.list'),
            perPage: 25
        });
    }
}
