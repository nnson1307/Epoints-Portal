var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var post = {
    addKey: function(id) {
        $.ajax({
            url: laroute.route('chathub.post.add-key'),
            method: 'POST',
            data: { id },
            success: function(res) {
                $('#add-post').html(res);
                $('#modal-default').modal();
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire("", mess_error, "error");
                }
            }
        });
    },
    updateKey: function(id) {
        $.ajax({
            url: laroute.route('chathub.post.update-key'),
            method: 'POST',
            data: {
                id: id,
                brand: $('#brand').val(),
                sku: $('#sku').val(),
                sub_brand: $('#sub_brand').val(),
                attribute: $('#attribute').val(),
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    $('#modal-default').modal('hide');
                    swal.fire(res.message, "", "success");
                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire("", mess_error, "error");
                }
            }
        });
    },
    subcribe: function(id) {
        $.ajax({
            url: laroute.route('chathub.post.subcribe'),
            method: 'POST',
            data: { id },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success");
                    $('#active-' + id).removeClass('btn-success');
                    $('#active-' + id).addClass('btn-warning');
                    $('#active-' + id).html(vali.post.UNSUBCRIBE);
                    $('#active-' + id).removeAttr('onclick').off('click').on('click', function() {
                        post.unsubcribe(id)
                    });
                }
            },error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire("", mess_error, "error");
                }
            }
        });
    },
    unsubcribe: function(id) {
        $.ajax({
            url: laroute.route('chathub.post.subcribe'),
            method: 'POST',
            data: { id },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success");
                    $('#active-' + id).addClass('btn-success');
                    $('#active-' + id).removeClass('btn-warning');
                    $('#active-' + id).html(vali.post.SUBCRIBE);
                    $('#active-' + id).removeAttr('onclick').off('click').on('click', function() {
                        post.subcribe(id)
                    });
                }
            },error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire("", mess_error, "error");
                }
            }

        });
    }
}
