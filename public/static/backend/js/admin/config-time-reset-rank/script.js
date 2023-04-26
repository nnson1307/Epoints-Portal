var index = {
    _init: function () {

    },
    edit: function (id) {
        $.ajax({
            url: laroute.route('admin.time-reset-rank.edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (res) {
                $('#my-popup').html(res.url);
                $('#my-popup').find('#modal-edit').modal({
                    backdrop: 'static', keyboard: false
                });
                $('#type').select2({
                    placeholder: 'Chọn khoảng cách tháng'
                });
            }
        });
    },
    submit_edit: function (id) {
        var form = $('#form-edit');

        form.validate({
            rules: {
                value: 'required',
            },
            messages: {
                value: {
                    required: 'Hãy nhập tháng thiết lập'
                },
            }
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('admin.time-reset-rank.submit-edit'),
            method: 'POST',
            dataType:'JSON',
            data:{
                id : id,
                value : $('#value').val()
            },
            success:function (res) {
                if (res.error == true) {
                    swal("Chỉnh sửa thất bại", "", "error");
                } else {
                    swal("Chỉnh sửa thành công", "", "success");
                    window.location.reload();
                }
            }
        });
    }
};

$(document).ready(function () {
    index._init();
});

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.time-reset-rank.list')
});