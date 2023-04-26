var config_email_template = {
    remove_image: function (id) {
        $('.append_image').empty();
        var tpl = $('#image-tpl').html();
        tpl = tpl.replace(/{id}/g, id);
        $('.append_image').append(tpl);
        $('#image').val('');
        $.ajax({
            url: laroute.route('admin.config-email-template.remove-img'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            }
        });
    },
    submit_edit: function (id) {
        $.ajax({
            url: laroute.route('admin.config-email-template.submit-edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                background_header: $('#background_header').val(),
                color_header: $('#color_header').val(),
                background_body: $('#background_body').val(),
                color_body: $('#color_body').val(),
                background_footer: $('#background_footer').val(),
                color_footer: $('#color_footer').val(),
                image: $('#image').val()
            },
            success:function (res) {
                $.getJSON(laroute.route('translate'), function (json) {
                    if(res.success==1)
                    {
                        swal(json["Lưu thông tin thành công"], "", "success");
                    }
                });
            }
        });
    },
    changeStatusLogo: function (e,id) {
        var logo = 0;
        if ($(e).is(':checked')) {
            logo = 1;
        }
        $.ajax({
            url: laroute.route('admin.config-email-template.change-status-logo'),
            method: "POST",
            data: {
                id: id,
                logo:logo
            },
            dataType: "JSON"
        });
    },
    changeStatusWebsite:function (e,id) {
        var website = 0;
        if ($(e).is(':checked')) {
            website = 1;
        }
        $.ajax({
            url: laroute.route('admin.config-email-template.change-status-website'),
            method: "POST",
            data: {
                id: id,
                website:website
            },
            dataType: "JSON"
        });
    },
    modal_view:function (id) {
        $.ajax({
            url: laroute.route('admin.config-email-template.view-after'),
            method:"POST",
            data:{
                id:id,
                background_header: $('#background_header').val(),
                color_header: $('#color_header').val(),
                background_body: $('#background_body').val(),
                color_body: $('#color_body').val(),
                background_footer: $('#background_footer').val(),
                color_footer: $('#color_footer').val()
            },
            success:function (res) {
                $('.modal_view_render').empty();
                $('.modal_view_render').append(res).find('#modal-view').modal('show');
            }

        });
    }
}

function uploadImage(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFileImage').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_config');

        // form_data.append('id', id);
        $.ajax({
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == 0) {
                    $('#image').val(res.file);
                    $('.cl_image').css('display', 'block');

                }
            }
        });
    }
}