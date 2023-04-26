var ManageConfig = {
    updateConfigRole : function () {
        $.ajax({
            url: laroute.route('manager-work.manage-config.role-update'),
            data: $('#form-config-role').serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == false) {
                    swal(data.message,'','success').then(function () {
                        window.location.href = laroute.route('manager-work.manage-config.role');
                    });
                } else {
                    swal(data.message,'','error');
                }
            }
        });
    }
}