var view = {
    _init: function () {
        $(document).ready(function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $("#description_detail_en").summernote({
                    height: 208,
                    width: 1000,
                    placeholder: json['Nhập nội dung'],
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']]
                    ]
                });
            });
        });
    },
    submitEdit: function (promotionId) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');

            form.validate({
                rules: {
                    promotion_name_en: {
                        required: true,
                        maxlength: 250
                    },
                },
                messages: {
                    promotion_name_en: {
                        required: json['Hãy nhập tên chương trình (EN)'],
                        maxlength: json['Tên chương trình (EN) tối đa 250 kí tự']
                    },

                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('fnb.promotion.update'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    promotion_id: promotionId,
                    promotion_name_en: $('#promotion_name_en').val(),
                    image: $('#image').val(),
                    image_en: $('#image_en').val(),
                    description_en: $('#description_en').val(),
                    description_detail_en: $('#description_detail_en').val(),
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            window.location.href = laroute.route('promotion');
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
                    swal(json['Chỉnh sửa thất bại'], mess_error, "error");
                }
            });
        });
    }
}

function uploadAvatar2(input,lang = 'en') {
    $.getJSON(laroute.route('translate'), function (json) {
        var arr = ['.jpg', '.png', '.jpeg', '.JPG', '.PNG', '.JPEG'];
        var check = 0;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var file_data = $('#getFileEn').prop('files')[0];

            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_promotion.');
            var fsize = input.files[0].size;
            var fileInput = input,
                file = fileInput.files && fileInput.files[0];
            var img = new Image();
            $.map(arr, function (item) {
                if (file_data.name.indexOf(item) != -1) {
                    check = 1;
                }
            })
            if (check == 1) {
                if (Math.round(fsize / 1024) <= 10240) {
                    reader.onload = function (e) {
                        $('#blah_en')
                            .attr('src', e.target.result);

                    };
                    reader.readAsDataURL(input.files[0]);
                    $.ajax({
                        url: laroute.route("admin.upload-image"),
                        method: "POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (res) {
                            $('#image_en').val(res.file);
                        },
                        error: function (res) {
                            swal.fire(json["Hình ảnh không đúng định dạng"], "", "error");
                        }
                    });
                } else {
                    swal.fire(json["Hình ảnh vượt quá dung lượng cho phép"], "", "error");
                }
            } else {
                swal.fire(json["Hình ảnh không đúng định dạng"], "", "error");
            }
        }
    });
}