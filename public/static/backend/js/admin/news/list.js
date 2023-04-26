var index = {
    changeStatus: function (id, obj) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('admin.new.change-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                new_id: id,
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success");
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    remove: function (id) {
        // hightlight row
        // $(obj).closest('tr').addClass('m-table__row--danger');

        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function () {
                    // remove hightlight row
                    // $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.new.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            new_id: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal(res.message, "", "success");
                                $('#autotable').PioTable('refresh');
                            } else {
                                swal(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    }
};

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.new.list')
});