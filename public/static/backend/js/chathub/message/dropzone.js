Dropzone.options.dropzoneoneFile = {
    paramName: 'file',
    maxFilesize: 10, // MB
    maxFiles: 5,
    acceptedFiles: ".docx,.txt,.pdf,.pptx,.xlsx,.doc,.ppt",
    // acceptedFiles: ".jpeg,.jpg,.png",
    dictMaxFilesExceeded: 'Bạn tải quá nhiều tệp',
    dictInvalidFileType: 'Tệp không hợp lệ',
    // addRemoveLinks: true,
    headers: {
        "X-CSRF-TOKEN": $('input[name=_token]').val()
    },
    //Change name after upload temp.
    renameFile: function(file) {
        var dt = new Date();
        var time = dt.getTime().toString() + dt.getDate().toString() + (dt.getMonth() + 1).toString() + dt.getFullYear().toString();
        var random = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for (let z = 0; z < 10; z++) {
            random += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        return time + "_" + random + "." + file.name.substr((file.name.lastIndexOf('.') + 1));
    },
    init: function() {

        this.on("sending", function (file, xhr, data) {
            data.append("link", "_chathub.");
        });
        this.on("success", function (file, response) {
            var a = document.createElement('span');
            a.className = "thumb-url btn btn-primary";
            a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

            if (response.error == 0) {
                $("#up-ima").append("<input type='hidden' class='file_Name' id='file_name' name='fileName[]' value='" + response.file + "'>");
            }
        });
        this.on("success", function(file, response) {
            let fileName = file.upload.filename;
            var a = document.createElement('span');
            a.className = "thumb-url btn btn-primary";
            a.setAttribute('data-clipboard-text', laroute.route('message.image-uploads') + response);
            if (file.status == "success") {
                // $("#temp").append("<input class='image-hide' type='hidden' name='fileName[]' value='" + fileName + "'>");
                // $("#array-file-hidden").append("<input class='file-hide dropzone-append-file' type='hidden' value='http://" + window.location.hostname +"/temp_upload/" + fileName + "'>");
                $("#array-file-hidden").append("<input class='file-hide-name dropzone-append-file' type='hidden' value='"+file.name+ "'>");
                $("#array-file-hidden").append("<input class='file-hide dropzone-append-file' type='hidden' value='"+response.file+ "'>");
            }
        });
        this.on("addedfile", function(file) {
            var _this = this;

            /* Maybe display some more file information on your page */
            var removeButton = Dropzone.createElement("<a data-dz-remove " +
                "class='del_thumbnail m--font-primary m-link'>Xóa</a>");

            removeButton.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                // var server_file = $(file.previewTemplate).children('.server_file').text();

                // let fileName = file.name.substr((file.name.indexOf('admin/product/') + 14));
                if (typeof file.upload === "undefined") {

                } else {
                    var name = file.upload.filename;
                    $.ajax({
                        url: laroute.route('message.delete-image-temp'),
                        method: "POST",
                        data: {
                            filename: name
                        },
                        success: function() {
                            $(".file-hide").each(function() {
                                var $this = $(this);
                                if ($this.val() == name) {
                                    $this.remove();
                                }
                            });
                        }
                    })
                }
                _this.removeFile(file);
            });
            file.previewElement.appendChild(removeButton);
        });
        this.on('error', function(file, errorMessage) {
            console.log(errorMessage);
        });
    }
};