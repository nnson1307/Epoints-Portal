var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var response_element={
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
                    url: laroute.route('chathub.response_element.delete'),
                    method: 'POST',
                    data: { 
                        response_element_id:id
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
                            swal.fire(vali.response_element.create.ADD_ERROR, mess_error, "error");
                        }
                    }
                });
            }
        });
        
    }
}