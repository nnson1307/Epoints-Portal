var index = {
    _init: function () {
        $(document).ready(function () {
            $('#detail_content').summernote({
                height: 150,
                placeholder: 'Nhập nội dung...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
            $('#send_type').select2({
                placeholder: 'Chọn loại gửi'
            });

            $('#schedule_unit').select2({
                placeholder: 'Đơn vị cộng thêm'
            });

            if ($('#send_type').val() == "immediately") {

            } else if ($('#send_type').val() == "before" || $('#send_type').val() == "after") {
                $('#value').ForceNumericOnly();
            } else if ($('#send_type').val() == "in_time") {
                $("#value").timepicker({
                    minuteStep: 15,
                    defaultTime: "10:00",
                    showMeridian: !1,
                    snapToStep: !0,
                });
            }
        });
    },
    showEdit: function (key) {
        $.ajax({
            url: laroute.route('config.edit'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                key: key
            },
            success: function (res) {
                $('#my-modal').html(res);
                $('#modal-config').modal('show');
            }
        });
    },
    append_para_txa: function (e) {
        var text = e;
        var txtarea = document.getElementById('message');
        var scrollPos = txtarea.scrollTop;
        var caretPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0, caretPos);
        var back = (txtarea.value).substring(txtarea.selectionEnd, txtarea.value.length);
        txtarea.value = front + text + back;
        caretPos = caretPos + text.length;
        txtarea.selectionStart = caretPos;
        txtarea.selectionEnd = caretPos;
        txtarea.focus();
        txtarea.scrollTop = scrollPos;
    },
    append_para_ck: function (e) {
        var text = e;
        $('#detail_content').summernote('pasteHTML', text);
    },
    save: function (key) {
        var form = $('#form-edit');

        form.validate({
            rules: {
                send_type: {
                    required: true,
                },
                title: {
                    required: true,
                    maxlength: 250
                },
                message: {
                    required: true,
                    maxlength: 1000
                },
                detail_content: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Hãy nhập tiêu đề',
                    maxlength: 'Tiêu đề tối đa 250 kí tự'
                },
                message: {
                    required: 'Hãy nhập nội dung thông báo',
                    maxlength: 'Nội dung tối đa 1000 kí tự'
                },
                detail_content: {
                    required: 'Hãy nhập nội dung chi tiết'
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        var continute = true;

        if ($('#send_type').val() == "before" || $('#send_type').val() == "after") {
            if ($('#schedule_unit').val() == '') {
                $('.error_schedule_unit').text('Hãy chọn đơn vị cộng thêm');
                continute = false;
            } else {
                $('.error_schedule_unit').text('');
            }

            if ($('#value').val() == '') {
                $('.error_value').text('Hãy nhập giá trị');
                continute = false;
            } else {
                $('.error_value').text('');
            }
        } else if ($('#send_type').val() == "in_time") {
            if ($('#value').val() == '') {
                $('.error_value').text('Hãy nhập giá trị');
                continute = false;
            } else {
                $('.error_value').text('');
            }
        }


        if (continute == true) {
            $.ajax({
                url: laroute.route('config.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    key: key,
                    send_type: $('#send_type').val(),
                    schedule_unit: $('#schedule_unit').val(),
                    value: $('#value').val(),
                    avatar_old: $('#avatar_old').val(),
                    avatar: $('#avatar').val(),
                    detail_background_old: $('#detail_background_old').val(),
                    detail_background: $('#detail_background').val(),
                    title: $('#title').val(),
                    message: $('#message').val(),
                    detail_content: $('#detail_content').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.href = laroute.route('config');
                            }
                            if (result.value == true) {
                                window.location.href = laroute.route('config');
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal('Chỉnh sửa cấu hình thông báo thất bại', mess_error, "error");
                }
            });
        }
    },
    changeStatus: function (key, obj) {
        var is_active = 0;
        if ($(obj).is(':checked')) {
            is_active = 1;
        }

        $.ajax({
            url: laroute.route('config.change-status'),
            method: "POST",
            dataType: "JSON",
            data: {
                key: key,
                is_active: is_active
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    remove_avatar: function () {
        $('.avatar').empty();
        var tpl = $('#avatar-tpl').html();
        $('.avatar').append(tpl);
        $('.image-size').text('');
        $('.image-capacity').text('');
        $('#avatar_old').val('');
    },
    remove_background: function () {
        $('.background').empty();
        var tpl = $('#background-tpl').html();
        $('.background').append(tpl);
        $('.image-size-bg').text('');
        $('.image-capacity-bg').text('');
        $('#detail_background_old').val('');
    },
    changeType: function (obj) {
        $('#schedule_unit').val('').trigger('change');

        if ($(obj).val() == "immediately") {
            $('#schedule_unit').prop("disabled", true);
            $('#value').prop("disabled", true);
            $('#value').val('');
        } else if ($(obj).val() == "before" || $(obj).val() == "after") {
            $('#schedule_unit').prop("disabled", false);
            $('#div-value').empty();
            var tpl = $('#input-val-tpl').html();
            $('#div-value').append(tpl);
            $('#value').ForceNumericOnly();
        } else if ($(obj).val() == "in_time") {
            $('#schedule_unit').prop("disabled", true);
            $('#div-value').empty();
            var tpl = $('#input-time-tpl').html();
            $('#div-value').append(tpl);

            $("#value").timepicker({
                minuteStep: 15,
                defaultTime: "10:00",
                showMeridian: !1,
                snapToStep: !0,
            });
        }
    }
};

function uploadAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#service_avatar');
        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        //Request data
        form_data.append('file', file_data);
        form_data.append('link', '_notification.');
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
                    if (res.error == 0) {
                        $('#avatar').val(res.file);
                        $('#delete-avatar').css('display', 'block');
                    }
                }
            });
        } else {
            $('.error_img').text('Hình ảnh vượt quá dung lượng cho phép');
        }

    }
}

function uploadBackground(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#detail_background');
        reader.onload = function (e) {
            $('#blahBg').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFileBackground').prop('files')[0];
        var form_data = new FormData();
        //Request data
        form_data.append('file', file_data);
        form_data.append('link', '_notification.');
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
        $('.image-capacity-bg').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format-bg').text(input.files[0].name.split('.').pop().toUpperCase());

        if (Math.round(fsize / 1024) <= 10240) {
            $('.error_bg').text('');
            $.ajax({
                url: laroute.route("admin.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == 0) {
                        $('#detail_background').val(res.file);
                        $('#delete-bg').css('display', 'block');
                    }
                }
            });
        } else {
            $('.error_bg').text('Hình ảnh vượt quá dung lượng cho phép');
        }

    }
}

jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };
