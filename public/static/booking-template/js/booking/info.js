var info = {
    _init: function () {
        $.ajax({
            url: laroute.route('booking.spa-info'),
            dataType: 'JSON',
            method: 'POST',
            success: function (res) {
                $('#div-info').html(res.html);
                // initMap();
            }
        })
    }
};
info._init();

function initMap(lat,lng,address) {

    var locations = locations;

    var lat = '10.7434091';
    var lng = '106.7309964';

    // if (locations != '') {
    //     lat = locations[0][1];
    //     lng = locations[0][2];
    // }


    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng(lat, lng),
        mapTypeId: google.maps.MapTypeId.ROADMAP,

    });

    marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, lng),
        map: map,
        icon: {
            url: './static/booking-template/image/icon-map-default.png',
            size: new google.maps.Size(27, 43),
            anchor: new google.maps.Point(13, 43),
            scaledSize: new google.maps.Size(27, 43),
            labelOrigin: new google.maps.Point(13, 47),
            origin: new google.maps.Point(0, 0)
        },
        label: {
            text: '72 Trần Trọng Cung',
            color: "red",
            fontSize: "16px",

        },
        animation: google.maps.Animation.DROP
    });

    map.setZoom(15);


}