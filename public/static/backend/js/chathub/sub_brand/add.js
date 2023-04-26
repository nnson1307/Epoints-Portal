var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
var sub_brand = {
    Add: function(){
        var form = $('#form');
        form.validate({
            rules: {
                sub_brand_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                sub_brand_status: {
                    required: true
                }
            },
            messages: {
                sub_brand_name: {
                    required: vali.sub_brand.create.NAME_REQUIRED,
                    maxlength: vali.sub_brand.create.NAME_MAX
                },
                sub_brand_status: {
                    required: vali.sub_brand.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.sub_brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.sub_brand.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('chathub.sub_brand.create'),
            method: 'POST',
            data: { 
                sub_brand_name: $('#sub_brand_name').val(),
                entities: $('#entities').val(),
                sub_brand_status: $('#sub_brand_status').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.sub_brand');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.sub_brand.create.ADD_ERROR, mess_error, "error");
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
                sub_brand_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                sub_brand_status: {
                    required: true
                }
            },
            messages: {
                sub_brand_name: {
                    required: vali.sub_brand.create.NAME_REQUIRED,
                    maxlength: vali.sub_brand.create.NAME_MAX
                },
                sub_brand_status: {
                    required: vali.sub_brand.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.sub_brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.sub_brand.create.ENTITIES_MAX
                }

            },
        });
        $.ajax({
            url: laroute.route('chathub.sub_brand.create'),
            method: 'POST',
            data: { 
                sub_brand_name: $('#sub_brand_name').val(),
                entities: $('#entities').val(),
                sub_brand_status: $('#sub_brand_status').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.sub_brand.add');
                    });
                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.sub_brand.create.ADD_ERROR, mess_error, "error");
                }
            }
        });
    },
    Edit: function(){
        var form = $('#form');
        form.validate({
            rules: {
                sub_brand_name: {
                    required: true,
                    maxlength: 255
                },
                entities: {
                    // required: true,
                    maxlength: 255
                },
                sub_brand_status: {
                    required: true
                }
            },
            messages: {
                sub_brand_name: {
                    required: vali.sub_brand.create.NAME_REQUIRED,
                    maxlength: vali.sub_brand.create.NAME_MAX
                },
                sub_brand_status: {
                    required: vali.sub_brand.create.STATUS_REQUIRED,
                },
                entities: {
                    // required: vali.sub_brand.create.ENTITIES_REQUIRED,
                    maxlength: vali.sub_brand.create.ENTITIES_MAX
                }

            },
        });
        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('chathub.sub_brand.update'),
            method: 'POST',
            data: { 
                sub_brand_name: $('#sub_brand_name').val(),
                entities: $('#entities').val(),
                sub_brand_status: $('#sub_brand_status').val(),
                sub_brand_id: $('#sub_brand_id').val()
            },
            success: function(res) {
                if (res.error) {
                    swal.fire(res.message, "", "error");
                } else {
                    swal.fire(res.message, "", "success").then(function(res) {
                        window.location.href = laroute.route('chathub.sub_brand');
                    });

                }
            },
            error: function(res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function(a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(vali.sub_brand.create.EDIT_ERROR, mess_error, "error");
                }
            }
        });
    }
}