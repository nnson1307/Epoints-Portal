
var ChangeTab = {
    tabComment : function (view = 'comment'){
        $.ajax({
            url: laroute.route('manager-work.detail.change-tab-detail-work'),
            data: {
                id : $('#manage_work_id').val(),
                view : view,
                manage_project : $('#manage_project').val()
            },
            method: "POST",
            dataType: "JSON",
            success: function(res) {
                if (res.error == false) {
                    $('.tab_work_detail').empty();
                    $('.tab_work_detail').append(res.view);

                    if (res.tab == 'location' && res.dataLocation.length > 0) {
                        $.each(res.dataLocation, function (k, v) {
                            var map = new google.maps.Map(document.getElementById('map-'+ v['manage_work_location_id'] +''), {
                                center: {lat: parseFloat(v.lat), lng: parseFloat(v.lng)},
                                scrollwheel: false,
                                zoom: 15
                            });

                            new google.maps.Marker({
                                position: {lat: parseFloat(v.lat), lng: parseFloat(v.lng)},
                                map
                            });
                        });
                    }
                } else {
                    swal(res.message,'','error');
                }
            },
        });
    }
}