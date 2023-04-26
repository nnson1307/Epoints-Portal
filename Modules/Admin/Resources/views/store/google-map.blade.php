<!DOCTYPE html>
<html>
<head>
    <style>
        #map {
            width: 100%;
            height: 400px;
            background-color: grey;
        }
    </style>
</head>
<body>
    <span>Sẽ tự động lấy vị trí khi nhập đủ thông tin địa chỉ</span>
    <div id="map"></div>
    <script>
        function initMap() {
            var uluru = {lat: 10.7416527 , lng: 106.7197381};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map,
                draggable:true
            });
//            marker.setMap(map);


        }
    </script>
    {{--<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=places&sensor=false"></script>--}}
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCixXdgcp0RgSKbAwKxRLuitpR6yIW1LxQ&callback=initMap">
    </script>

</body>
</html>