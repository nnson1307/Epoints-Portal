$(document).ready(function () {
    $('#branch_id').selectpicker();
    $('#branch_id').on('change', function(e) {
        var listBranthId = $('#branch_id').val();

        var id = $('#branch_id').data('idservice');
        $.ajax({
            url: laroute.route('admin.service-branch-price.get-branch'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                listid: listBranthId,
                id: id
            },
            success: function(data) {
                $('#table_branch > tbody > tr').remove();
                jQuery.each(data['data'], function (key, val) {
                    $('#table_branch > tbody').append('<tr>' +
                        '<td>' + (parseInt(key) + 1) + '</td>' +
                        '<td>' +  val.branch_name + '</td>' +
                        '<td>' + val.old_price + '</td>' +
                        '<td>' + val.new_price + '</td>' +
                        '</tr>');
                });
            }
        });
    });
});
var service_branch_price = {
    remove: function (obj, id) {
        // hightlight row
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: 'Thông báo',
            text: "Bạn có muốn xóa không?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.service-branch-price.remove', {id: id}), function () {
                    swal(
                        'Xóa thành công',
                        '',
                        'success'
                    );
                    // window.location.reload();
                    $('#autotable').PioTable('refresh');
                });
            }
        });

    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.service-branch-price.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.service-branch-price.list')
});
$("#created_at").daterangepicker({
    autoUpdateInput: false,
    autoApply:true,
    locale: {
        format: 'DD/MM/YYYY'
    }
});