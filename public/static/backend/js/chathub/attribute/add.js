var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var attribute = {
    Add: function(){
        var form = $('#form');
        form.validate({
            rules: {
                attribute_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                attribute_status: {
                    required: true
                }
            },
            messages: {
                attribute_name: {
                    required: vali.attribute.create.NAME_REQUIRED,
                    maxlength: vali.attribute.create.NAME_MAX
                },
                attribute_status: {
                    required: vali.attribute.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.attribute.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('chathub.attribute.create'),
            method: 'POST',
            data: { 
                attribute_name: $('#attribute_name').val(),
                entities: $('#entities').val(),
                attribute_status: $('#attribute_status').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.attribute');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.attribute.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    AddNew: function(){
        var form = $('#form');
        
        
        form.validate({
            rules: {
                attribute_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                attribute_status: {
                    required: true
                }
            },
            messages: {
                attribute_name: {
                    required: vali.attribute.create.NAME_REQUIRED,
                    maxlength: vali.attribute.create.NAME_MAX
                },
                attribute_status: {
                    required: vali.attribute.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.attribute.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }
        $.ajax({
            url: laroute.route('chathub.attribute.create'),
            method: 'POST',
            data: { 
                attribute_name: $('#attribute_name').val(),
                entities: $('#entities').val(),
                attribute_status: $('#attribute_status').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.attribute.add');
                    });
                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.attribute.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    Edit: function(){
        var form = $('#form');
        form.validate({
            rules: {
                attribute_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                attribute_status: {
                    required: true
                }
            },
            messages: {
                attribute_name: {
                    required: vali.attribute.create.NAME_REQUIRED,
                    maxlength: vali.attribute.create.NAME_MAX
                },
                attribute_status: {
                    required: vali.attribute.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.attribute.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('chathub.attribute.update'),
            method: 'POST',
            data: { 
                attribute_name: $('#attribute_name').val(),
                entities: $('#entities').val(),
                attribute_status: $('#attribute_status').val(),
                attribute_id: $('#attribute_id').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.attribute');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.attribute.create.EDIT_ERROR, mess_error, "error");
                }
            }
        });
    }
}