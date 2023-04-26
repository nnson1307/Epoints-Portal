var stt = 0;

var follower = {
    add: function () {
        var params = $('#form-add').serialize();
        $.ajax({
            url: laroute.route('zns.follower.add'),
            data: params,
            method: "POST",
            dataType: "JSON",
            success: function (data) {
                if (data.status == 1) {
                    swal(data.message, '', 'success').then(function () {
                        var zalo_campaign_follower_id = $('[name=zalo_campaign_follower_id]').val();
                        if (zalo_campaign_follower_id) {
                            if (zalo_campaign_follower_id == -1) {
                                window.location.href = laroute.route('zns.campaign-follower.add', {zns_template_id: data.zns_template_id});
                            } else {
                                window.location.href = laroute.route('zns.campaign-follower.edit', {id: zalo_campaign_follower_id});
                            }
                        } else {
                            window.location.href = laroute.route('zns.template-follower');

                        }
                    });
                } else {
                    swal(
                        data.message,
                        '',
                        'warning'
                    );
                }
            }
        }).fail(function (error) {
            $('.error').remove();
            $.map(error.responseJSON.errors, function (mess, index) {
                if (index.indexOf('.') !== -1) {
                    let afterDot = index.split('.')[1];
                    let beforeDot = index.split('.')[0];
                    $('[name^="' + beforeDot + '[' + afterDot + '"]').closest('.form-group').append('<div class="mt-3 error">' + mess[0] + '</div>');
                } else {
                    $('[name=' + index + ']').parent().append('<div class="mt-3 error">' + mess[0] + '</div>');
                }
            });
        });
    },
    dropzoneFile: function () {
        Dropzone.options.dropzoneFile = {
            paramName: 'file',
            maxFilesize: 100, // MB
            maxFiles: 1,
            acceptedFiles: ".pdf,.doc,.docx",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            renameFile: function (file) {
                var dt = new Date();
                var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
                var random = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (let z = 0; z < 10; z++) {
                    random += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
            },
            init: function () {
                this.on("sending", function (file, xhr, data) {
                    data.append("link", "_ticket.");
                });

                this.on("success", function (file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('ticket.upload-file') + response);
                    if (response.error == false) {
                        $("#up-file-temp").append("<input type='hidden' class='" + file.upload.filename + "'  name='fileName' value='" + response.file + "'>");
                    }
                });

                this.on('removedfile', function (file, response) {
                    var name = file.upload.filename;
                    $.ajax({
                        url: laroute.route('admin.service.delete-image'),
                        method: "POST",
                        data: {
                            filename: name
                        },
                        success: function () {
                            $("input[class='file_Name']").each(function () {
                                var $this = $(this);
                                if ($this.val() === name) {
                                    $this.remove();
                                }
                            });
                        }
                    });
                });
            }
        };
    },
    modalFile: function () {
        $('#up-file-temp').empty();
        $('#dropzoneFile')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('#dropzoneFile').removeClass('dz-started');

        $('#modal-file-ticket').modal({
            backdrop: 'static',
            keyboard: false
        });
    },
    submitFileticket: function () {
        var checkFile = $('#up-file-temp').find('input[name="fileName"]');
        $.each(checkFile, function () {
            let tpl = $('#tpl-file').html();
            tpl = tpl.replace(/{fileName}/g, $(this).val());
            $('.div_file_ticket').append(tpl);
        });

        $('#modal-file-ticket').modal('hide');
    },
    removeFile: function (obj) {
        $(obj).closest('.div_file').remove();
        $(obj).closest('.div_file').find('[name=file]').remove();
    },
};

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

// $('[name=preview]').keyup(function () {
//     let lengths = $(this).val().length;
//     $('.count-character').text(lengths);
// });
$('.preview-class').keyup(function () {
    let lengths = $(this).val().length;
    $(this).parent().find('.count-character').text(lengths);
});
$('.text-params-coppy').click(function () {
    $('[name="preview"]').val($('[name="preview"]').val() + $(this).attr('data-value'));
    let lengths = $('[name=preview]').val().length;
    $('.count-character').text(lengths);
});
$('[name="type_template_follower"]').change(function () {
    var zalo_campaign_follower_id = $('[name=zalo_campaign_follower_id]').val();
    var params = {'type_template_follower': $(this).val()};
    if (zalo_campaign_follower_id) {
        params = {
            'type_template_follower': $(this).val(),
            'zalo_campaign_follower_id': zalo_campaign_follower_id,
        };
    }
    window.location.href = laroute.route('zns.template-follower.add', params);
});

// UPLOAD ẢNH
function uploadImage(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#ticket_img');
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_ticket.');

        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function () {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());

        if (Math.round(fsize / 1024) <= 10240) {
            $('.error_img').text('');
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == false) {
                        $('#blah').attr('src', res.file);
                        $('[name="image"]').val(res.file);
                    }
                }
            });
        } else {
            $('.error_img').text('Hình ảnh vượt quá dung lượng cho phép');
        }
    }
};
$(document).on('click', '.add-icon-button', function () {
    $(this).closest('.button_item').find('.icon-file').click();
});
$(document).on('change', '.icon-file', function (val) {
    var input = val.target;
    var input_selecttor = $(this);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            input_selecttor.closest('.button_item').find('.icon-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $(this).prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_ticket.');

        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function () {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());

        if (Math.round(fsize / 1024) <= 10240) {
            $('.error_img').text('');
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == false) {
                        input_selecttor.closest('.icon-preview').attr('src', res.file);
                        input_selecttor.closest('.button_item').find('.icon-value').val(res.file);
                    }
                }
            });
        } else {
            $('.error_img').text('Hình ảnh vượt quá dung lượng cho phép');
        }
    }
});
$('.add_type_button').click(function () {
    let type_button = $(this).attr('data-value');
    let stt = $("#list_button .button_item").length + 1;
    if (type_button) {
        var params = {
            "type_button": type_button,
            "stt": stt
        };
        $.ajax({
            url: laroute.route("zns.template_follower.add-button"),
            method: "POST",
            data: params,
            pdataType: "JSON",
            success: function (res) {
                if (res.status == 1) {
                    $('#list_button').append(res.html);
                    $('#list_button').find('.button_item').last().show('slow');
                } else {
                    swal(
                        res.message,
                        '',
                        'warning'
                    );
                }
            }
        });
    }
});
$(document).on("click", '.button_item .remove-button-item', function () {
    $(this).closest('.button_item').fadeOut(300, function () {
        $(this).remove();
        $('.button_item .number_count_button').each(function (index) {
            $(this).text(index + 1);
        });
    });

});
$(document).on("change", '[name^="type_button["]', function () {
    let type_button = $(this).val();
    let stt = $(this).closest('.button_item').index() + 1;
    let current_element = $(this);
    if (type_button) {
        var params = {
            "type_button": type_button,
            "stt": stt
        };
        $.ajax({
            url: laroute.route("zns.template_follower.add-button"),
            method: "POST",
            data: params,
            pdataType: "JSON",
            success: function (res) {
                if (res.status == 1) {
                    current_element.closest('.button_item').replaceWith(res.html);
                    current_element.closest('.button_item').find('.button_item').last().show('slow');
                } else {
                    swal(
                        res.message,
                        '',
                        'warning'
                    );
                }
            }
        });
    }
});