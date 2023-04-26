var listBrand = {
    _init: function () {
        $.ajax({

            url: laroute.route('brand.list'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                page : 1,
                display: display
            },
            success: function (res) {
                $('#list-brand').html(res.html);
                // initMap();
            }
        })
    }
};
listBrand._init();

var product = {
    change: function () {
        $.ajax({

            url: laroute.route('brand.list'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                page : 1,
                display: display
            },
            success: function (res) {
                $('#list-brand').html(res.html);
            }
        })
    },
};

var step3 = {
    pageClick: function (page) {
        $.ajax({
            url: laroute.route('brand.list'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                page : page,
                display: display
            },
            success: function (res) {
                $('#list-brand').html(res.html);
            }
        });
    },
    firstAndLastPage: function (page) {
        $.ajax({
            url: laroute.route('brand.list'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                page : page,
                display: display
            },
            success: function (res) {
                $('#list-brand').html(res.html);
            }
        });
    },
};