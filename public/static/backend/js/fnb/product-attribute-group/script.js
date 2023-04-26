var productAttributeGroupFNB = {
    edit: function (id) {
        $.ajax({
            url: laroute.route('fnb.product-attribute-group.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('.append-popup').empty();
                $('.append-popup').append(data.view);
                $('#modalEditGroup').modal('show');

            }
        })
    },

    submitEdit: function () {
        $.ajax({
                url: laroute.route('fnb.product-attribute-group.update'),
                data: $('#product-attribute-group-edit').serialize(),
                method: "POST",
                success: function (res) {
                    if(res.error == false){
                        swal(res.message, '', "success").then(function (){
                            $('#modalEditGroup').modal('hide');
                            $('#autotable').PioTable('refresh');
                        });

                    }else{
                        swal(res.message, '', "error");
                    }
                },
                error: function (response) {
                    var mess_error = '';
                    $.map(response.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(mess_error, '', "error");
                }

            }
        );
    },
}