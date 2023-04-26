var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var sku = {
    Add: function(){
        var form = $('#form');
        form.validate({
            rules: {
                sku_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                sku_status: {
                    required: true
                }
            },
            messages: {
                sku_name: {
                    required: vali.sku.create.NAME_REQUIRED,
                    maxlength: vali.sku.create.NAME_MAX
                },
                sku_status: {
                    required: vali.sku.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.sku.create.ENTITIES_REQUIRED,
                    maxlength: vali.sku.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('chathub.sku.create'),
            method: 'POST',
            data: { 
                sku_name: $('#sku_name').val(),
                entities: $('#entities').val(),
                sku_status: $('#sku_status').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.sku');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.sku.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    AddNew: function(){
        var form = $('#form');
        
        if (!form.valid()) {
            return false;
        }
        form.validate({
            rules: {
                sku_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                sku_status: {
                    required: true
                }
            },
            messages: {
                sku_name: {
                    required: vali.sku.create.NAME_REQUIRED,
                    maxlength: vali.sku.create.NAME_MAX
                },
                sku_status: {
                    required: vali.sku.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.sku.create.ENTITIES_REQUIRED,
                    maxlength: vali.sku.create.ENTITIES_MAX
                }

            },
        });
        $.ajax({
            url: laroute.route('chathub.sku.create'),
            method: 'POST',
            data: { 
                sku_name: $('#sku_name').val(),
                entities: $('#entities').val(),
                sku_status: $('#sku_status').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.sku.add');
                    });
                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.sku.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    Edit: function(){
        var form = $('#form');
        form.validate({
            rules: {
                sku_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                sku_status: {
                    required: true
                }
            },
            messages: {
                sku_name: {
                    required: vali.sku.create.NAME_REQUIRED,
                    maxlength: vali.sku.create.NAME_MAX
                },
                sku_status: {
                    required: vali.sku.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.sku.create.ENTITIES_REQUIRED,
                    maxlength: vali.sku.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('chathub.sku.update'),
            method: 'POST',
            data: { 
                sku_name: $('#sku_name').val(),
                entities: $('#entities').val(),
                sku_status: $('#sku_status').val(),
                sku_id: $('#sku_id').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.sku');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.sku.create.EDIT_ERROR, mess_error, "error");
                }
            }
        });
    }
}