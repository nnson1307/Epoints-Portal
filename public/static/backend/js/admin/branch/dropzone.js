Dropzone.options.dropzoneone = {
    paramName: 'file',
    maxFilesize: 5, // MB
    maxFiles: 20,
    acceptedFiles: ".jpeg,.jpg,.png,.gif",
    addRemoveLinks: true,
    headers: {
        "X-CSRF-TOKEN": $('input[name=_token]').val()
    },
    dictRemoveFile: 'Xóa',
    dictMaxFilesExceeded: 'Bạn tải quá nhiều hình ảnh',
    dictInvalidFileType: 'Tệp không hợp lệ',
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
            data.append("link", "_branch.");
        });

        this.on("success", function (file, response) {
            var a = document.createElement('span');
            a.className = "thumb-url btn btn-primary";
            a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));
            if (response.error === 0) {
                $("#up-ima").append("<input type='hidden' class='file_Name' id='file_name' name='fileName[]' value='" + response.file + "'>");
            }

        });

        this.on('removedfile', function (file, response) {
            var name = file.upload.filename;
            $.ajax({
                url: laroute.route('admin.branch.delete-img'),
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

// Dropzone.options.dropzonetwo = {
//     paramName: 'file',
//     maxFilesize: 5, // MB
//     maxFiles: 10,
//     acceptedFiles: ".jpeg,.jpg,.png,.gif",
//     addRemoveLinks: true,
//     headers: {
//         "X-CSRF-TOKEN": $('input[name=_token]').val()
//     },
//     init: function () {
//         this.on("success", function (file, response) {
//             var a = document.createElement('span');
//             a.className = "thumb-url btn btn-primary";
//             a.setAttribute('data-clipboard-text', laroute.route('admin.service.uploads'));
//             if (file.status === "success") {
//                 $("#up-image-edit").append("<input type='hidden' class='file_Name' name='service_image[]' value='" + response + "'>");
//             }
//
//         });
//         this.on('removedfile', function (file) {
//             var name = file.upload.filename;
//             $.ajax({
//                 url: laroute.route('admin.service.delete-image'),
//                 method: "POST",
//                 data: {
//                     // filename: b.toString()
//                     filename: name
//                 },
//                 success: function (data) {
//                     $(".file_Name").each(function () {
//                         var $this =$(this);
//                         if ($this.val() === name) {
//                             $this.remove();
//                         }
//                     });
//                 }
//             });
//
//
//
//         });
//
//         // var arr=[];
//         // $.each(load,function () {
//         //    arr.push($(this).attr('src'));
//         // });
//         //console.log(arr);
//         // var a = $('#up-image').find('input[type="hidden"]');
//         // var b = [];
//         // $.each(a, function () {
//         //     b.push($(this).val());
//         // });
//         // for (let i = 0; i < b.length; i++) {
//         //     if (b[i] != "") {
//         //         var file = {
//         //             name: 'service_avatar',
//         //             size: 10000,
//         //             dataURL: b[i],
//         //         };
//         //         this.emit("addedfile", file);
//         //         this.files.push(file);
//         //         // this.createThumbnailFromUrl(file, file.dataURL, callback, crossOrigin);
//         //         this.emit('thumbnail', file, '/'+file.dataURL,
//         //             this.options.thumbnailWidth,
//         //             this.options.thumbnailHeight,
//         //             this.options.thumbnailMethod,
//         //             true,
//         //             function (thumbnail) {
//         //                 this.emit('thumbnail', file, thumbnail);
//         //             }, "anonymous");
//         //         this.emit("complete", file);
//         //         var existingFileCount = 1;
//         //         this.options.maxFiles = this.options.maxFiles - existingFileCount;
//         //     }
//         // }
//
//         // var image=$('#ima').val();
//
//     }
// }
// Dropzone.options.dropzoneone = {
//     url: laroute.route('admin.service.uploads'),
//     maxFiles: 10,
//     paramName: "service_image",
//     uploadMultiple: false,
//     acceptedFile: 'image/*',
//     headers: {
//         "X-CSRF-TOKEN": $('input[name=_token]').val()
//     },
//     addRemoveLinks: true,
//     maxFilesize: 3,
//     init: function () {
//         this.on('success', function (file, response) {
//             if (response.success == 1) {
//                     $("#up-ima").prepend("<input id='file_image' type='hidden' name='service_image' value='" + response.file + "'>");
//             }
//
//         });
//         this.on('error', function (file, response) {
//             $(file.previewElement).find(".dz-error-message").html(response.message)
//         });
//
//         this.on('removedfile', function (file) {
//             var up_img=$('#up-ima').find('input[name="service_image"]');
//             var arr=[];
//             $.each(up_img,function () {
//                 arr.push($(this).val());
//             });
//             console.log(arr);
//             $.ajax({
//                 url: laroute.route('admin.customer.delete'),
//                 method: "POST",
//                 data: {
//                     filename: $("#file_image").val()
//                 },
//                 success: function (data) {
//
//                 }
//             });
//
//         });
//     }
// };

