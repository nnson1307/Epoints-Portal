var index = {
    _init:function () {

    },
    modal_file:function () {
        $('#modal-excel').modal('show');
        $('#show').val('');
        $('input[type=file]').val('');
    },
    import:function () {
        mApp.block(".modal-body", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: "Xin vui lòng chờ..."
        });

        var file_data = $('#file_excel').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            url: laroute.route("admin.customer.import-excel"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                mApp.unblock(".modal-body")
                if (res.success == 1) {
                    $('#modal-excel').modal('hide');
                    swal(res.message, "", "success");
                    window.location.reload();
                }
            }
        });
    },
    showNameFile:function(){
        var fileNamess=$('input[type=file]').val();
        $('#show').val(fileNamess);
    },
};

index._init();
