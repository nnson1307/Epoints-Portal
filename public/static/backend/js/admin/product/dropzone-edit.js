Dropzone.options.dropzoneImageProductEdit = {
    paramName: 'file',
    maxFilesize: 10, // MB
    maxFiles: 20,
    acceptedFiles: ".jpeg,.jpg,.png",
    dictMaxFilesExceeded: 'Bạn tải quá nhiều hình ảnh',
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
            data.append("link", "_product.");
        });

        this.on("success", function(file, response) {
            let fileName = file.upload.filename;
            var a = document.createElement('span');
            a.className = "thumb-url btn btn-primary";
            a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image') + response);
            if (response.error == 0) {
                $("#array-image-hidden").append("<input class='image-hide dropzone-append-image' type='hidden' value='" + response.file + "'>");
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
                var server_file = $(file.previewTemplate).children('.server_file').text();

                let fileName = file.name.substr((file.name.indexOf('admin/product/') + 14));
                if (typeof file.upload === "undefined") {

                } else {
                    var name = file.upload.filename;
                    $.ajax({
                        url: laroute.route('admin.delete-image-temp'),
                        method: "POST",
                        data: {
                            filename: name
                        },
                        success: function() {
                            $(".image-hide").each(function() {
                                var $this = $(this);
                                if ($this.val() == name) {
                                    $this.remove();
                                }
                            });
                        }
                    })
                }
                _this.removeFile(file);
                // _this.parents('.class-for-delete').find('.div-image-show').remove();
                // $('.exist-image-db').each(function () {
                //     console.log($(this).attr('src'));
                //     var flagsUrl = '{{ URL::asset('+name+') }}';
                //     console.log(flagsUrl);
                //     if ($(this).attr('src') == name) {
                //         // $(this).remove();
                //         console.log($(this).attr('src'));
                //     }
                // })
            });
            file.previewElement.appendChild(removeButton);
        });
        // this.on('removedfile', function (file) {
        //     let fileName = file.name.substr((file.name.indexOf('admin/product/') + 14));
        //     if (typeof file.upload === "undefined") {
        //
        //     } else {
        //         var name = file.upload.filename;
        //         $.ajax({
        //             url: laroute.route('admin.delete-image-temp'),
        //             method: "POST",
        //             data: {
        //                 filename: name
        //             },
        //             success: function () {
        //                 $(".image-hide").each(function () {
        //                     var $this = $(this);
        //                     if ($this.val() == name) {
        //                         $this.remove();
        //                     }
        //                 });
        //             }
        //         })
        //     }
        // });

        // var existingFiles = new Array();
        // $.each($('.exist-image-db'), function () {
        //     existingFiles.push($(this).attr('src'));
        // });
        // for (let i = 0; i < existingFiles.length; i++) {
        //     var mockFile = {
        //         name: existingFiles[i],
        //         size: 10000,
        //         accepted: true,
        //         isNew: false
        //     };
        //     this.emit("addedfile", mockFile);
        //     this.files.push(mockFile);
        //     this.emit('thumbnail', mockFile, existingFiles[i],
        //         this.options.thumbnailWidth,
        //         this.options.thumbnailHeight,
        //         this.options.thumbnailMethod,
        //         true,
        //         function (thumbnail) {
        //             this.emit('thumbnail', mockFile, thumbnail);
        //         }, "anonymous");
        //     this.emit("complete", mockFile);
        // }
    }
};
// Dropzone.options.avatarProduct = {
//     paramName: 'file',
//     maxFilesize: 5, // MB
//     uploadMultiple: false,
//     acceptedFiles: ".jpeg,.jpg,.png,.gif",
//     addRemoveLinks: true,
//     headers: {
//         "X-CSRF-TOKEN": $('input[name=_token]').val()
//     },
//     init: function () {
//         var arrResponse = new Array();
//         this.on("success", function (file, response) {
//             var a = document.createElement('span');
//             a.className = "thumb-url btn btn-primary";
//             a.setAttribute('data-clipboard-text', laroute.route('admin.product-uploads') + response);
//
//             // if (file.status == "success") {
//             //     $("#tempAvatar").append("<input type='hidden' id='avatarImg' value='" + response + "'>");
//             // }
//             if (file.status == "success") {
//                 if (this.files.length > 1) {
//                     this.removeFile(this.files[0]);
//                 }
//                 $('#avatarImg').empty();
//                 $('.avatar-hidden').val(response)
//             }
//
//         });
//         this.on('removedfile', function (file) {
//             $.ajax({
//                 url: laroute.route('admin.delete-image-temp'),
//                 method: "POST",
//                 data: {
//                     filename: file.name
//                 },
//                 success: function () {
//                     $("#tempAvatar").empty();
//                     $('.avatar-hidden').val();
//                 }
//             })
//         });
//
//         //Hiển thị lại hình ảnh đại diện của sản phẩm.
//         var avatar = $('.avatar-hidden').val();
//         if (avatar!=''){
//             var mockFile = {
//                 name: '',
//                 size: 10000,
//                 accepted: true,
//                 isNew: false
//             };
//             this.emit("addedfile", mockFile);
//             this.files.push(mockFile);
//             this.emit('thumbnail', mockFile, avatar,
//                 this.options.thumbnailWidth,
//                 this.options.thumbnailHeight,
//                 this.options.thumbnailMethod,
//                 true,
//                 function (thumbnail) {
//                     this.emit('thumbnail', mockFile, thumbnail);
//                 }, "anonymous");
//             this.emit("complete", mockFile);
//         }
//     }
// };