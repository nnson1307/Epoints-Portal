var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var index = {
    remove: function (obj, id) {
        $(obj).closest('tr').addClass('m-table__row--danger');
            swal({
                title:  jsonLang['Thông báo'],
                text:  jsonLang["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText:  jsonLang['Xóa'],
                cancelButtonText:  jsonLang['Hủy'],
                onClose: function () {
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.order.remove', {id: id}), function () {
                        swal(
                             jsonLang['Xóa thành công'],
                            '',
                            'success'
                        );
                        // window.location.reload();
                        $('#autotable').PioTable('refresh');
                    });
                }
            });
    },

    apply_branch: function (id) {
        $.ajax({
            url: laroute.route('admin.order.apply-branch'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                order_id: id
            },
            success: function (res) {
                $('#my-modal').html(res.url);
                $('#modal-apply-branch').modal('show');
                $('#branch_id').select2({
                    placeholder:  jsonLang['Chọn chi nhánh']
                });
            }
        });
    },
}
$('#autotable').PioTable({
    baseUrl: laroute.route('fnb.orders.list')
});