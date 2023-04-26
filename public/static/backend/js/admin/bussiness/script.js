$(document).ready(function () {

});
var bussiness = {
    modal_add: function () {
        $('#modal-add').modal('show');
    },
    submit_add: function (type) {
        $('#type_add').val(type);
        $('#form-add').validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: 'Hãy nhập tên ngành nghề'
                }
            },
            submitHandler: function () {
                $.ajax({
                    url: laroute.route('admin.bussiness.submit-add'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        name: $('#name').val(),
                        description: $('#description').val(),
                        type: $('#type_add').val()
                    },
                    success: function (res) {
                        if (res.success == 1) {
                            swal("Thêm ngành nghề thành công", "", "success");
                            $('#autotable').PioTable('refresh');
                            if (res.type == 1) {
                                $("#modal-add").modal("hide");
                            }
                            $('#name').val('');
                            $('#description').val('');
                            $('.error_name').text('');
                        }
                        if(res.success==0)
                        {
                            $('.error_name').text('Tên ngành nghề đã tồn tại');
                        }
                    }
                });
            }
        });
    },
    modal_edit: function (id) {
        $.ajax({
            url: laroute.route('admin.bussiness.edit'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                id: id
            },
            success: function (res) {
                if (res.item.is_actived == 1) {
                    $('#is_actived').prop('checked', true);
                } else {
                    $('#is_actived').prop('checked', false);
                }
                $('#name_edit').val(res.item.name);
                $('#description_edit').val(res.item.description);
                $('#bussiness_id').val(res.item.id);
                $('.error_name_edit').text('');
                $('#modal-edit').modal('show');
            }
        })
    },
    submit_edit: function () {
        $('#form-edit').validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: 'Hãy nhập tên ngành nghề'
                }
            },
            submitHandler: function () {
                var is_actived = 0;
                if ($('#is_actived').is(':checked')) {
                    is_actived = 1;
                }
                $.ajax({
                    url: laroute.route('admin.bussiness.submit-edit'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        name: $('#name_edit').val(),
                        description: $('#description_edit').val(),
                        is_actived: is_actived,
                        id: $('#bussiness_id').val()
                    },
                    success: function (res) {
                        if (res.success == 1) {
                            swal("Cập nhật ngành nghề thành công", "", "success");
                            $('#autotable').PioTable('refresh');
                            $("#modal-edit").modal("hide");
                        }
                        if(res.success==0)
                        {
                            $('.error_name_edit').text('Tên ngành nghề đã tồn tại');
                        }
                    }
                });
            }
        });
    },
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
                $.post(laroute.route('admin.bussiness.remove', {id: id}), function () {
                    swal(
                        'Xóa thành công',
                        '',
                        'success'
                    );
                    $('#autotable').PioTable('refresh');
                });
            }
        });

    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.bussiness.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
}
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.bussiness.list')
});