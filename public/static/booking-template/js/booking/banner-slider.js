var bannerSlider = {
    _init: function () {
        $.ajax({
            url: laroute.route('booking.banner-slider'),
            dataType: 'JSON',
            method: 'POST',
            success: function (res) {
                $('#div-banner-slider').html(res.html);
                // initMap();
            }
        })
    }
};

var infoSpa = {
    _init: function () {
        $.ajax({
            url: laroute.route('booking.name-spa'),
            dataType: 'JSON',
            method: 'POST',
            success: function (res) {
                $('#name-spa').html(res.html);
                // initMap();
            }
        })
    }
};
infoSpa._init();
bannerSlider._init();