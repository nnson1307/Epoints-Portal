$('#autotable').PioTable({
    baseUrl: laroute.route('admin.rating.list')
});

var listRating = {
    changeShow: function (id, is_show) {
        $.ajax({
            url: laroute.route('admin.rating.change-show'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                is_show: is_show
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success");
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    }
};