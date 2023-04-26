var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var brand = {
    Add: function(){
        var form = $('#form');
        form.validate({
            rules: {
                brand_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                brand_status: {
                    required: true
                }
            },
            messages: {
                brand_name: {
                    required: vali.brand.create.NAME_REQUIRED,
                    maxlength: vali.brand.create.NAME_MAX
                },
                brand_status: {
                    required: vali.brand.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.brand.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('chathub.brand.create'),
            method: 'POST',
            data: { 
                brand_name: $('#brand_name').val(),
                entities: $('#entities').val(),
                brand_status: $('#brand_status').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.brand');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.brand.create.ADD_ERROR, mess_error, "error");
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
                brand_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                brand_status: {
                    required: true
                }
            },
            messages: {
                brand_name: {
                    required: vali.brand.create.NAME_REQUIRED,
                    maxlength: vali.brand.create.NAME_MAX
                },
                brand_status: {
                    required: vali.brand.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.brand.create.ENTITIES_MAX
                }

            },
        });
        $.ajax({
            url: laroute.route('chathub.brand.create'),
            method: 'POST',
            data: { 
                brand_name: $('#brand_name').val(),
                entities: $('#entities').val(),
                brand_status: $('#brand_status').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.brand.add');
                    });
                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.brand.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    Edit: function(){
        var form = $('#form');
        form.validate({
            rules: {
                brand_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                brand_status: {
                    required: true
                }
            },
            messages: {
                brand_name: {
                    required: vali.brand.create.NAME_REQUIRED,
                    maxlength: vali.brand.create.NAME_MAX
                },
                brand_status: {
                    required: vali.brand.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.brand.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('chathub.brand.update'),
            method: 'POST',
            data: { 
                brand_name: $('#brand_name').val(),
                entities: $('#entities').val(),
                brand_status: $('#brand_status').val(),
                brand_id: $('#brand_id').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.brand');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.brand.create.EDIT_ERROR, mess_error, "error");
                }
            }
        });
    }
}