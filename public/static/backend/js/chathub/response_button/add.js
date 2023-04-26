var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var response_button = {
    Add: function(){
        $.ajax({
            url: laroute.route('chathub.response_button.create'),
            method: 'POST',
            data: { 
                title: $('#title').val(),
                type: $('#type').val(),
                url: $('#url').val(),
                payload: $('#payload').val(),
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.response_button');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.response_button.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    AddNew: function(){
        $.ajax({
            url: laroute.route('chathub.response_button.create'),
            method: 'POST',
            data: { 
                title: $('#title').val(),
                type: $('#type').val(),
                url: $('#url').val(),
                payload: $('#payload').val(),
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.response_button.add');
                    });
                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.response_button.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    Edit: function(){
        $.ajax({
            url: laroute.route('chathub.response_button.update'),
            method: 'POST',
            data: { 
                title: $('#title').val(),
                type: $('#type').val(),
                url: $('#url').val(),
                payload: $('#payload').val(),
                response_button_id: $('#response_button_id').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.response_button');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.response_button.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    }
}