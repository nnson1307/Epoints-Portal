var config_service_card = {
    changeStatusQrCode: function (e, id) {
        var qr_code = 0;
        if ($(e).is(':checked')) {
            qr_code = 1;
        }
        $.ajax({
            url: laroute.route('admin.config-print-service-card.change-status-qr'),
            method: "POST",
            data: {
                id: id,
                qr_code: qr_code
            },
            dataType: "JSON"
        }).done(function (data) {

        });
    },
    remove_logo: function (id) {
        $('.append_logo_' + id).empty();
        var tpl = $('#logo-tpl').html();
        tpl = tpl.replace(/{id}/g, id);
        $('.append_logo_' + id).append(tpl);
        $('#background_image_' + id).val('');
        $.ajax({
            url: laroute.route('admin.config-print-service-card.remove-logo'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            }
        });
    },
    submit_edit: function (id) {
        $.ajax({
            url: laroute.route('admin.config-print-service-card.submit-edit'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                name_spa: $('#name_spa_' + id).val(),
                background: $('#background_' + id).val(),
                color: $('#color_' + id).val(),
                id: id,
                logo: $('#logo_' + id).val(),
                background_image: $('#background_image_' + id).val()
            },
            success: function (res) {
                $.getJSON(laroute.route('translate'), function (json) {
                    if (res.success == 1) {
                        swal(json["Lưu thông tin thành công"], "", "success");
                        $('#autotable').PioTable('refresh');
                    }
                });
            }
        });
    },
    remove_background: function (id) {
        $('.append_background_' + id).empty();
        var tpl = $('#background-tpl').html();
        tpl = tpl.replace(/{id}/g, id);
        $('.append_background_' + id).append(tpl);
        $('#logo_').val('');
        $.ajax({
            url: laroute.route('admin.config-print-service-card.remove-background'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            }
        });
    },
    view_after: function (id) {
        $.ajax({
            url: laroute.route('admin.config-print-service-card.view-after'),
            method: "POST",
            data: {
                id: id,
                background: $('#background_' + id).val(),
                color: $('#color_' + id).val(),
                name_spa: $('#name_spa_' + id).val()
            },
            success: function (res) {
                $('#modal_view_render_' + id).empty();
                $('#modal_view_render_' + id).append(res);
            }

        });

    }
};

function uploadLogo(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#logo_img_' + id)
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFileLogo_' + id).prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_config.');

        $.ajax({
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == 0) {
                    $('#logo_' + id).val(res.file);
                    $('.cl_logo_' + id).css('display', 'block');

                }
            }
        });
    }
}

function uploadBackground(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#background_img_' + id)
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFileBackground_' + id).prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_config.');

        $.ajax({
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == 0) {
                    $('#background_image_' + id).val(res.file);
                    $('.cl_background_' + id).css('display', 'block');

                }
            }
        });
    }
}

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.config-print-service-card.list')
});
$(document).ready(function () {

});