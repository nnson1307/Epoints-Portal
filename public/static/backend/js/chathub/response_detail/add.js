var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var response_detail = {
    Add: function(){
        $.ajax({
            url: laroute.route('chathub.response_detail.create'),
            method: 'POST',
            data: { 
                brand: $('#brand').val(),
                sub_brand: $('#sub_brand').val(),
                sku: $('#sku').val(),
                attribute: $('#attribute').val(),
                response_content: $('#response_content').val(),
                response_element_id: $('#response_element_id').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.response_detail');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.response_detail.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    AddNew: function(){
        $.ajax({
            url: laroute.route('chathub.response_detail.create'),
            method: 'POST',
            data: { 
                brand: $('#brand').val(),
                sub_brand: $('#sub_brand').val(),
                sku: $('#sku').val(),
                attribute: $('#attribute').val(),
                response_content: $('#response_content').val(),
                response_element_id: $('#response_element_id').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.response_detail.add');
                    });
                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.response_detail.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    // Edit: function(){
    //     $.ajax({
    //         url: laroute.route('chathub.response_detail.update'),
    //         method: 'POST',
    //         data: { 
    //             brand: $('#brand').val(),
    //             sub_brand: $('#sub_brand').val(),
    //             sku: $('#sku').val(),
    //             attribute: $('#attribute').val(),
    //             response_content: $('#response_content').val(),
    //             response_element_id: $('#response_element_id').val()
    //         },
    //         success: function(res) {
    //             if (res.error) {
    //                 swal.fire(res.message, "", "error");
    //             } else {
    //                 swal.fire(res.message, "", "success").then(function(res) {
    //                     window.location.href = laroute.route('chathub.response_detail');
    //                 });

    //             }
    //         },
    //         error: function(res) {
    //             if (res.responseJSON != undefined) {
    //                 var mess_error = '';
    //                 $.map(res.responseJSON.errors, function(a) {
    //                     mess_error = mess_error.concat(a + '<br/>');
    //                 });
    //                 swal.fire(vali.response_detail.create.ADD_ERROR, mess_error, "error");
    //             }
    //         }
    //     });
    // }
}