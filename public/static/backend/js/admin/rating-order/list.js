$('#autotable').PioTable({
    baseUrl: laroute.route('admin.rating-order.list')
});

var view = {
    clickViewImage: function (linkImage) {
        $.ajax({
            url: laroute.route('admin.rating-order.view-image'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                link: linkImage
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-image').modal('show');
            }
        });
    },
    clickViewVideo: function (linkVideo) {
        $.ajax({
            url: laroute.route('admin.rating-order.view-video'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                link: linkVideo
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-video').modal('show');
            }
        });
    }
};