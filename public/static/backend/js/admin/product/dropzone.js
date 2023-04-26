Dropzone.options.dropzoneImageProduct = {
    paramName: 'file',
    maxFilesize: 10, // MB
    maxFiles: 20,
    acceptedFiles: ".jpeg,.jpg,.png",
    dictMaxFilesExceeded:'Bạn tải quá nhiều hình ảnh',
    dictInvalidFileType:'Tệp không hợp lệ',
    // addRemoveLinks: true,
    headers: {
        "X-CSRF-TOKEN": $('input[name=_token]').val()
    },
    //Change name after upload temp.
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
            data.append("link", "_product.");
        });

        var arrResponse = new Array();
        this.on("success", function (file, response) {
            console.log(file, response);
            let fileName = file.upload.filename;
            var a = document.createElement('span');
            a.className = "thumb-url btn btn-primary";
            a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image') + response);

            if (response.error == 0) {
                $("#temp").append("<input class='image-product' type='hidden' name='fileName[]' value='" + response.file + "'>");
            }

        });
        $("input[name='fileName[]']").each(function () {
            arrResponse.push($(this).val());
        });
        this.on("addedfile", function (file) {
            var _this = this;

            /* Maybe display some more file information on your page */
            var removeButton = Dropzone.createElement("<a data-dz-remove " +
                "class='del_thumbnail m--font-primary m-link'>Xóa</a>");

            removeButton.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                var server_file = $(file.previewTemplate).children('.server_file').text();
                var name = file.upload.filename;
                $.ajax({
                    url: laroute.route('admin.delete-image-temp'),
                    method: "POST",
                    data: {
                        filename: name
                    },
                    success: function () {
                        $(".image-product").each(function () {
                            var $this = $(this);
                            if ($this.val() == name) {
                                $this.remove();
                            }
                        });

                        $('.append-image-poduct').empty();
                        $('.append-image-poduct').empty();
                    }
                })
                _this.removeFile(file);
            });
            file.previewElement.appendChild(removeButton);
        });
    }
};

Dropzone.options.avatarProduct = {
    paramName: 'file',
    maxFilesize: 5, // MB
    uploadMultiple: false,
    acceptedFiles: ".jpeg,.jpg,.png,.gif",
    addRemoveLinks: true,
    headers: {
        "X-CSRF-TOKEN": $('input[name=_token]').val()
    },
    init: function () {
        var arrResponse = new Array();
        this.on("success", function (file, response) {
            var a = document.createElement('span');
            a.className = "thumb-url btn btn-primary";
            a.setAttribute('data-clipboard-text', laroute.route('admin.product-uploads') + response);

            if (file.status == "success") {
                $('#tempAvatar').empty();
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
                $('#avatarImg').val(response)
            }

        });
        this.on('removedfile', function (file) {
            $.ajax({
                url: laroute.route('admin.delete-image-temp'),
                method: "POST",
                data: {
                    filename: file.name
                },
                success: function () {
                    $("#tempAvatar").empty();
                    $('#avatarImg').val('');
                }
            })
        });
    }
};