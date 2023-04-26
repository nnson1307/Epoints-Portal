var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var response_content={
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
                    url: laroute.route('chathub.response-content.remove'),
                    method: 'POST',
                    data: {
                        response_content_id:id
                    },
                    success: function(res) {
                        swal(
                            'Xóa thành công',
                            '',
                            'success'
                        ).then(function(){
                            $('#autotable').PioTable('refresh');
                        });
                    },
                    error: function(res) {
                        if (res.responseJSON != undefined) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function(a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal.fire(vali.response_content.create.ADD_ERROR, mess_error, "error");
                        }
                    }
                });
            }
        });
        
    },
    refresh: function () {
        $('input[name="search"]').val('');
        $(".btn-search").trigger("click");
    },
};
$('#autotable').PioTable({
    baseUrl: laroute.route('chathub.response-content.list')
});