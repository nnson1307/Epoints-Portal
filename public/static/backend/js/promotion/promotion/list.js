var list = {
    remove:function (promotionId) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('promotion.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            promotion_id: promotionId
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                $('#autotable').PioTable('refresh');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    changeStatus(promotionId, status) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn thay đổi trạng thái không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Đồng ý'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('promotion.change-status-promotion'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            promotion_id: promotionId,
                            status: status
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                $('#autotable').PioTable('refresh');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    }
};


$('#autotable').PioTable({
    baseUrl: laroute.route('promotion.list')
});