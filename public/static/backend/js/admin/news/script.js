var create = {
    _init: function () {
        $('#description_detail_vi').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for(let i=0; i < files.length; i++) {
                        uploadImgCkListVi(files[i]);
                    }
                }
            },
        });

        $('#description_detail_en').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for(let i=0; i < files.length; i++) {
                        uploadImgCkListEn(files[i]);
                    }
                }
            },
        });

        $('#product').select2({
            placeholder: 'Chọn sản phẩm liên quan'
        }).on('select2:select', function (event) {
            if (event.params.data.id == 0) {
                $('#product').val(0).trigger('change');
            } else {
                var arrayChoose = [];
                $.map($('#product').val(), function (val) {
                    if (val != 0) {
                        arrayChoose.push(val);
                    }
                });
                $('#product').val(arrayChoose).trigger('change');
            }
        }).on('select2:unselect', function (event) {
            if ($('#product').val() == '') {
                $('#product').val(0).trigger('change');
            }
        });

        $('#service').select2({
            placeholder: 'Chọn dịch vụ liên quan'
        }).on('select2:select', function (event) {
            if (event.params.data.id == 0) {
                $('#service').val(0).trigger('change');
            } else {
                var arrayChoose = [];
                $.map($('#service').val(), function (val) {
                    if (val != 0) {
                        arrayChoose.push(val);
                    }
                });
                $('#service').val(arrayChoose).trigger('change');
            }
        }).on('select2:unselect', function (event) {
            if ($('#service').val() == '') {
                $('#service').val(0).trigger('change');
            }
        });
    },
    remove_avatar: function () {
        $('.avatar').empty();
        var tpl = $('#avatar-tpl').html();
        $('.avatar').append(tpl);
        $('.image-format').text('');
        $('.image-size').text('');
        $('.image-capacity').text('');
        $('#image_old').val('');
    },
    store: function () {
        var form = $('#form-register');

        form.validate({
            rules: {
                title_vi: {
                    required: true,
                    maxlength: 250
                },
                title_en: {
                    required: true,
                    maxlength: 250
                },
                description_vi: {
                    required: true,
                    maxlength: 250
                },
                description_en: {
                    required: true,
                    maxlength: 250
                }
            },
            messages: {
                title_vi: {
                    required: 'Hãy nhập tiêu đề VI',
                    maxlength: 'Tiêu đề VI tối đa 250 kí tự'
                },
                title_en: {
                    required: 'Hãy nhập tiêu đề EN',
                    maxlength: 'Tiêu đề EN tối đa 250 kí tự'
                },
                description_vi: {
                    required: 'Hãy nhập nội dung VI',
                    maxlength: 'Nội dung VI tối đa 250 kí tự'
                },
                description_en: {
                    required: 'Hãy nhập nội dung EN',
                    maxlength: 'Nội dung EN tối đa 250 kí tự'
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('admin.new.store'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                title_vi: $('#title_vi').val(),
                title_en: $('#title_en').val(),
                description_vi: $('#description_vi').val(),
                description_en: $('#description_en').val(),
                product: $('#product').val(),
                service: $('#service').val(),
                description_detail_vi: $('#description_detail_vi').val(),
                description_detail_en: $('#description_detail_en').val(),
                image: $('#image').val(),
                image_app: $('input[name=image_app]').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.href = laroute.route('admin.new');
                        }
                        if (result.value == true) {
                            window.location.href = laroute.route('admin.new');
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
                swal(json['Thêm bài viết thất bại'], mess_error, "error");
            }
        });
    }
};

var edit = {
    _init: function () {
        $('#description_detail_vi').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for(let i=0; i < files.length; i++) {
                        uploadImgCkListVi(files[i]);
                    }
                }
            },
        });

        $('#description_detail_en').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for(let i=0; i < files.length; i++) {
                        uploadImgCkListEn(files[i]);
                    }
                }
            },
        });

        $('#product').select2({
            placeholder: 'Chọn sản phẩm liên quan'
        }).on('select2:select', function (event) {
            if (event.params.data.id == 0) {
                $('#product').val(0).trigger('change');
            } else {
                var arrayChoose = [];
                $.map($('#product').val(), function (val) {
                    if (val != 0) {
                        arrayChoose.push(val);
                    }
                });
                $('#product').val(arrayChoose).trigger('change');
            }
        }).on('select2:unselect', function (event) {
            if ($('#product').val() == '') {
                $('#product').val(0).trigger('change');
            }
        });

        $('#service').select2({
            placeholder: 'Chọn dịch vụ liên quan'
        }).on('select2:select', function (event) {
            if (event.params.data.id == 0) {
                $('#service').val(0).trigger('change');
            } else {
                var arrayChoose = [];
                $.map($('#service').val(), function (val) {
                    if (val != 0) {
                        arrayChoose.push(val);
                    }
                });
                $('#service').val(arrayChoose).trigger('change');
            }
        }).on('select2:unselect', function (event) {
            if ($('#service').val() == '') {
                $('#service').val(0).trigger('change');
            }
        });
    },
    save: function (id) {
        var form = $('#form-edit');

        form.validate({
            rules: {
                title_vi: {
                    required: true,
                    maxlength: 250
                },
                title_en: {
                    required: true,
                    maxlength: 250
                },
                description_vi: {
                    required: true,
                    maxlength: 250
                },
                description_en: {
                    required: true,
                    maxlength: 250
                }
            },
            messages: {
                title_vi: {
                    required: 'Hãy nhập tiêu đề VI',
                    maxlength: 'Tiêu đề VI tối đa 250 kí tự'
                },
                title_en: {
                    required: 'Hãy nhập tiêu đề EN',
                    maxlength: 'Tiêu đề EN tối đa 250 kí tự'
                },
                description_vi: {
                    required: 'Hãy nhập nội dung VI',
                    maxlength: 'Nội dung VI tối đa 250 kí tự'
                },
                description_en: {
                    required: 'Hãy nhập nội dung EN',
                    maxlength: 'Nội dung EN tối đa 250 kí tự'
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        var is_actived = 0;
        if ($("#is_actived").is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('admin.new.update'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                new_id: id,
                title_vi: $('#title_vi').val(),
                title_en: $('#title_en').val(),
                description_vi: $('#description_vi').val(),
                description_en: $('#description_en').val(),
                product: $('#product').val(),
                service: $('#service').val(),
                description_detail_vi: $('#description_detail_vi').val(),
                description_detail_en: $('#description_detail_en').val(),
                image: $('#image').val(),
                image_app: $('input[name=image_app]').val(),
                image_old: $('#image_old').val(),
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.href = laroute.route('admin.new');
                        }
                        if (result.value == true) {
                            window.location.href = laroute.route('admin.new');
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
                swal('Chỉnh sửa bài viết thất bại', mess_error, "error");
            }
        });
    }
};

function uploadImage(input) {
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
        form_data.append('link', '_news.');

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
                        $('#image').val(res.file);
                        $('.delete-img').css('display', 'block');

                    }

                }
            });
        } else {
            $('.error_img').text('Hình ảnh vượt quá dung lượng cho phép');
        }
    }
}

uploadImgCkListVi = function (file,parent_comment = null) {
    let out = new FormData();
    out.append('file', file, file.name);

    $.ajax({
        method: 'POST',
        url: laroute.route('manager-work.detail.upload-file'),
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function (img) {
            if (parent_comment != null){
                // $(".summernote").summernote('insertImage', img['file']);
                $("#description_detail_vi").summernote('insertImage', img['file'] , function ($image){
                    $image.css('width', '100%');
                });
            } else {
                // $(".summernote").summernote('insertImage', img['file']);
                $("#description_detail_vi").summernote('insertImage', img['file'] , function ($image){
                    $image.css('width', '100%');
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};

uploadImgCkListEn = function (file,parent_comment = null) {
    let out = new FormData();
    out.append('file', file, file.name);

    $.ajax({
        method: 'POST',
        url: laroute.route('manager-work.detail.upload-file'),
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function (img) {
            if (parent_comment != null){
                // $(".summernote").summernote('insertImage', img['file']);
                $("#description_detail_en").summernote('insertImage', img['file'] , function ($image){
                    $image.css('width', '100%');
                });
            } else {
                // $(".summernote").summernote('insertImage', img['file']);
                $("#description_detail_en").summernote('insertImage', img['file'] , function ($image){
                    $image.css('width', '100%');
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};