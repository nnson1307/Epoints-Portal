var vali;
$.ajax({
    url: laroute.route('chathub.validation'),
    method: 'GET',
    async: false,
    success: function(json) {
        vali = json;
    }
});
function uploadImage(input) {
    $('.image-info').text('');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#blah-add')
                .attr('src', e.target.result);
        };  
        reader.readAsDataURL(input.files[0]);
        $( ".s-delete-img" ).removeClass( "d-none" )
    }
}

function deleteAvatar () {
    $('.avatar-temp').empty();
    $('.avatar-temp').append($('#image-avatar-temp').html());
    $('#getFile').val('');
}

var response_element={
    Add: function(){
        var file_data = $('#getFile').prop('files')[0];
        if(file_data){
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: laroute.route('chathub.response_element.upload-image'),
                method: 'POST',
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    $.ajax({
                        url: laroute.route('chathub.response_element.create'),
                        method: 'POST',
                        data: { 
                            title: $('#title').val(),
                            subtitle: $('#subtitle').val(),
                            image_url: "http://" + window.location.hostname+"/"+res,
                            response_button: $('.select2').val(),
                        },
                        success: function(res) {
                            if (res.error) {
                                swal.fire(res.message, "", "error");
                            } else {
                                swal.fire(res.message, "", "success").then(function(res) {
                                    window.location.href = laroute.route('chathub.response_element');
                                });
            
                            }
                        },
                        error: function(res) {
                            if (res.responseJSON != undefined) {
                                var mess_error = '';
                                $.map(res.responseJSON.errors, function(a) {
                                    mess_error = mess_error.concat(a + '<br/>');
                                });
                                swal.fire(vali.response_element.create.ADD_ERROR, mess_error, "error");
                            }
                        }
                    });
                },
                error: function(res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function(a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(vali.response_element.create.ADD_ERROR, mess_error, "error");
                    }
                }
            });
        }else{
            $.ajax({
                url: laroute.route('chathub.response_element.create'),
                method: 'POST',
                data: { 
                    title: $('#title').val(),
                    subtitle: $('#subtitle').val(),
                    response_button: $('.select2').val(),
                },
                success: function(res) {
                    if (res.error) {
                        swal.fire(res.message, "", "error");
                    } else {
                        swal.fire(res.message, "", "success").then(function(res) {
                            window.location.href = laroute.route('chathub.response_element');
                        });
    
                    }
                },
                error: function(res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function(a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(vali.response_element.create.ADD_ERROR, mess_error, "error");
                    }
                }
            });
        }
    },
    AddNew: function(){
        var file_data = $('#getFile').prop('files')[0];
        if(file_data){
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: laroute.route('chathub.response_element.upload-image'),
                method: 'POST',
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    $.ajax({
                        url: laroute.route('chathub.response_element.create'),
                        method: 'POST',
                        data: { 
                            title: $('#title').val(),
                            subtitle: $('#subtitle').val(),
                            image_url: "http://" + window.location.hostname+"/"+res,
                            response_button: $('.select2').val(),
                        },
                        success: function(res) {
                            if (res.error) {
                                swal.fire(res.message, "", "error");
                            } else {
                                swal.fire(res.message, "", "success").then(function(res) {
                                    window.location.href = laroute.route('chathub.response_element.add');
                                });
            
                            }
                        },
                        error: function(res) {
                            if (res.responseJSON != undefined) {
                                var mess_error = '';
                                $.map(res.responseJSON.errors, function(a) {
                                    mess_error = mess_error.concat(a + '<br/>');
                                });
                                swal.fire(vali.response_element.create.ADD_ERROR, mess_error, "error");
                            }
                        }
                    });
                },
                error: function(res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function(a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(vali.response_element.create.ADD_ERROR, mess_error, "error");
                    }
                }
            });
        }else{
            $.ajax({
                url: laroute.route('chathub.response_element.create'),
                method: 'POST',
                data: { 
                    title: $('#title').val(),
                    subtitle: $('#subtitle').val(),
                    response_button: $('.select2').val(),
                },
                success: function(res) {
                    if (res.error) {
                        swal.fire(res.message, "", "error");
                    } else {
                        swal.fire(res.message, "", "success").then(function(res) {
                            window.location.href = laroute.route('chathub.response_element.add');
                        });
    
                    }
                },
                error: function(res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function(a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(vali.response_element.create.ADD_ERROR, mess_error, "error");
                    }
                }
            });
        }
    },
    Update: function(){
        var file_data = $('#getFile').prop('files')[0];
        if(file_data){
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: laroute.route('chathub.response_element.upload-image'),
                method: 'POST',
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    $.ajax({
                        url: laroute.route('chathub.response_element.update'),
                        method: 'POST',
                        data: { 
                            response_element_id: $('#response_element_id').val(),
                            title: $('#title').val(),
                            subtitle: $('#subtitle').val(),
                            image_url: "http://" + window.location.hostname+"/"+res,
                            response_button: $('.select2').val(),
                        },
                        success: function(res) {
                            if (res.error) {
                                swal.fire(res.message, "", "error");
                            } else {
                                swal.fire(res.message, "", "success").then(function(res) {
                                    window.location.href = laroute.route('chathub.response_element');
                                });
            
                            }
                        },
                        error: function(res) {
                            if (res.responseJSON != undefined) {
                                var mess_error = '';
                                $.map(res.responseJSON.errors, function(a) {
                                    mess_error = mess_error.concat(a + '<br/>');
                                });
                                swal.fire(vali.response_element.create.EDIT_ERROR, mess_error, "error");
                            }
                        }
                    });
                },
                error: function(res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function(a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(vali.response_element.create.EDIT_ERROR, mess_error, "error");
                    }
                }
            });
        }else{
            $.ajax({
                url: laroute.route('chathub.response_element.update'),
                method: 'POST',
                data: { 
                    response_element_id: $('#response_element_id').val(),
                    title: $('#title').val(),
                    image_url: $('#image_url').val(),
                    subtitle: $('#subtitle').val(),
                    response_button: $('.select2').val(),
                },
                success: function(res) {
                    if (res.error) {
                        swal.fire(res.message, "", "error");
                    } else {
                        swal.fire(res.message, "", "success").then(function(res) {
                            window.location.href = laroute.route('chathub.response_element');
                        });
    
                    }
                },
                error: function(res) {
                    if (res.responseJSON != undefined) {
                        var mess_error = '';
                        $.map(res.responseJSON.errors, function(a) {
                            mess_error = mess_error.concat(a + '<br/>');
                        });
                        swal.fire(vali.response_element.create.EDIT_ERROR, mess_error, "error");
                    }
                }
            });
            
            
        }
    }
}