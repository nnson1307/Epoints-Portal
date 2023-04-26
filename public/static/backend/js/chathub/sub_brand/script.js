var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var sub_brand={
    remove: function(obj, id){
        swal({
            title: vali.notification.TITLE,
            text: vali.notification.TEXT,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: vali.notification.CONFIRM,
            cancelButtonText: vali.notification.CANCEL,
            onClose: function () {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route('chathub.sub_brand.delete'),
                    method: 'POST',
                    data: { 
                        sub_brand_id:id
                    },
                    success: function(res) {
                        if (res.error) {
                            swal.fire(res.message, "", "error");
                        } else {
                            swal.fire(res.message, "", "success").then(function(res) {
                                $('#autotable').PioTable('refresh');
                            });
        
                        }
                    },
                    error: function(res) {
                        if (res.responseJSON != undefined) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function(a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal.fire(vali.sub_brand.create.ADD_ERROR, mess_error, "error");
                        }
                    }
                });
            }
        });
        
    }
}