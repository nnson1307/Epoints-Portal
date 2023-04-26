$('#autotable').PioTable({
    baseUrl: laroute.route('customer-lead.config-source-lead.list')
});

var config = {
    showPopup : function (id = null){
        $.ajax({
            url: laroute.route('customer-lead.config-source-lead.showpopup'),
            dataType: 'JSON',
            data: {
                id: id,
            },
            method: 'POST',
            success: function (res) {
                if(res.error == false){
                    $('.show-popup').empty();
                    $('.show-popup').append(res.view);
                    $('select').select2();
                    $('#modal-config').modal('show');
                } else {
                    swal.fire(res.message,'','error');
                }
            }

        });
    },

    saveConfig : function (){
        $.ajax({
            url: laroute.route('customer-lead.config-source-lead.saveConfig'),
            dataType: 'JSON',
            data: $('#form-config').serialize(),
            method: 'POST',
            success: function (res) {
                if(res.error == false){
                    swal.fire(res.message,'','success').then(function (){
                        location.reload();
                    });
                } else {
                    swal.fire(res.message,'','error');
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal.fire(mess_error, '', "error");
            }

        });
    },

    remove:function (id){
        $.getJSON(laroute.route('translate'), function (json) {
            swal.fire({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('customer-lead.config-source-lead.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            cpo_customer_lead_config_source_id: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success").then(function (){
                                    $('#autotable').PioTable('refresh')
                                });
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    }
}