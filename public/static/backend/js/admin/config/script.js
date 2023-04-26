var config = {
    addKey: function () {
        $('.list-keyhot').append(function () {
            return '<div id="key'+sum+'">' +
                        ' <input type="text" name="key['+sum+']" class="form-control mb-2 w-50 d-inline" placeholder="Nhập từ khóa">'+
                        '<button type="button" style="margin-left: 5px !important;" onclick="config.removeKey('+sum+')' +
                '"\n' +
                        ' class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"\n' +
                        '  title="Xóa"><i class="la la-trash"></i></button>\n' +
                    ' </div>'
        });
        sum++;
    },

    removeKey: function ($id) {
        $('#key'+$id).remove();
    },
    
    updateKey : function () {
        if ($('#config_id').val() == 13) {
            var value = 0;
            if($('#is_payment_online').is(':checked')) {
                value = 1;
            }

                $.ajax({
                url: laroute.route('admin.config.edit-post-config-general'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    config_id : $('#config_id').val(),
                    value : value
                },
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, '', 'success').then(function () {
                            window.location.href = laroute.route('admin.config.config-general');
                        });
                    } else {
                        swal(res.message, '', 'error');
                    }
                }
            });

        } else if ($('#config_id').val() == 22) {
            var value = 0;
            if($('#is_minus').is(':checked')) {
                value = 1;
            }

            $.ajax({
                url: laroute.route('admin.config.edit-post-config-general'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    config_id : $('#config_id').val(),
                    value : value
                },
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, '', 'success').then(function () {
                            window.location.href = laroute.route('admin.config.config-general');
                        });
                    } else {
                        swal(res.message, '', 'error');
                    }
                }
            });

        } else {
            $.ajax({
                url: laroute.route('admin.config.edit-post-config-general'),
                method: 'POST',
                dataType: 'JSON',
                data: $('#form-update').serialize(),
                success: function (res) {
                    if (!res.error) {
                        swal(res.message, '', 'success').then(function () {
                            window.location.href = laroute.route('admin.config.config-general');
                        });
                    } else {
                        swal(res.message, '', 'error');
                    }
                }
            });
        }
    },
    
    updateBrand : function () {
        $.ajax({
            url: laroute.route('admin.config.edit-post-config-general'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#form-update').serialize(),
            success: function (res) {
                if (!res.error) {
                    swal(res.message, '', 'success').then(function () {
                        window.location.href = laroute.route('admin.config.config-general');
                    });
                } else {
                    swal(res.message, '', 'error');
                }
            }
        });
    }
}

$(document).ready(function () {
    $('input[name="auto_apply_branch"]').change(function () {
        var check = $('input[name="auto_apply_branch"]:checked').val();
        if (check == 1){
            $('.input-number').prop('disabled',false);
        } else {
            $('.input-number').prop('disabled',true);
        }
    });
});

function uploadAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#image');
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_config-general.');

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
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == 0) {
                        $('#logo').val(res.file);
                    }
                }
            });
        } else {
            swal("Hình ảnh vượt quá dung lượng cho phép", "", "error");
        }
    }
}

var edit = {
    _init: function () {
        let type = $('#config_type').val();
        switch (type) {
            case "date":
                $('#value').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true
                });
                break;
            case "time":
                $('#value').timepicker({
                    showMeridian: false
                });
                break;
            case "ckeditor":
                $('.summernote').summernote({
                    height: 200,
                    placeholder: 'Nhập nội dung...',
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                    ]

                });
                break;
        }
    },

    updateConfigByType: function (id, type) {
        // check type -> get config
        let value = '';
        switch (type) {
            case 'text':
            case 'date':
            case 'time':
            case 'option':
                value = $('#value').val();
                break;
            case 'ckeditor':
                value = $('.summernote').summernote('code');
                break;
            case 'boolean':
                if ($('#value').is(':checked')) {
                    value = 1;
                } else {
                    value = 0;
                }
                break;
            case 'image':
                let imageOld = $('#logo_old').val();
                let imageNew = $('#logo').val();
                if (imageNew == null) {
                    value = imageOld;
                } else {
                    value = imageNew;
                }
                break;
        }
        // get config detail
        let arrConfigDetail = {};
        $.each($('.list-config-detail').find(".config-detail"), function () {
            let keyConfigDetail = $(this).find($('.input-config-detail')).attr("name");
            let valueConfigDetail = $(this).find($('.input-config-detail')).val();
            arrConfigDetail[keyConfigDetail] = valueConfigDetail;
        });
        console.log(arrConfigDetail);
        $.ajax({
            url: laroute.route('admin.config.edit-post-config-general'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                config_id: id,
                type: type,
                value: value,
                arrConfigDetail: arrConfigDetail
            },
            success: function (res) {
                if (!res.error) {
                    swal(res.message, '', 'success').then(function () {
                        window.location.href = laroute.route('admin.config.config-general');
                    });
                } else {
                    swal(res.message, '', 'error');
                }
            }
        });
    }
}

var detail = {
    _init: function () {
        let type = $('#config_type').val();
        switch (type) {
            case "date":
                $('#value').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true
                });
                break;
            case "time":
                $('#value').timepicker({
                    showMeridian: false
                });
                break;
            case "ckeditor":
                $('.summernote').summernote({
                    height: 200,
                    placeholder: 'Nhập nội dung...',
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                    ]

                });
                $('.summernote').summernote('disable');
                break;
        }
    },
}