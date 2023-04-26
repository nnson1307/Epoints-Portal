var dropAdd = Dropzone.options.dropzoneone = {
    url: laroute.route('admin.staff.uploads'),
    maxFiles: 1,
    paramName: "staff_avatar",
    uploadMultiple: false,
    acceptedFile: 'image/*',
    headers: {
        "X-CSRF-TOKEN": $('input[name=_token]').val()
    },
    addRemoveLinks: true,
    maxFilesize: 3,
    init: function () {
        this.on('success', function (file, response) {
            if (response.success == 1) {
                if ($('#file_image').length) {
                    document.getElementById('file_image').value = response.file;
                } else {
                    $("#form").prepend("<input id='file_image' type='hidden' name='staff_avatar' value='" + response.file + "'>");
                }
            }


        });
        this.on('error', function (file, response) {
            $(file.previewElement).find(".dz-error-message").html(response.message)
        });
        this.on('removedfile', function (file) {
            $.ajax({
                url: laroute.route('admin.staff.delete'),
                method: "POST",
                data: {
                    filename: $("#file_image").val()
                },
                success: function (data) {

                }
            });
            $("input[name='staff_avatar']").each(function () {
                var $this = $(this);
                if ($this.val() === $("#file_image").val()) {
                    $this.val('');
                }
            });
        });
    }
};
// Dropzone.options.dropzonetwo = {
//
//     url: laroute.route('admin.staff.uploads'),
//     maxFiles: 5,
//     paramName: "staff_avatar",
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
//                 if ($('#file_image').length) {
//                     document.getElementById('file_image').value = response.file;
//                 } else {
//                     $("#form").prepend("<input id='file_image' type='hidden' name='staff_avatar' value='" + response.file + "'>");
//                 }
//             }
//
//
//         });
//         this.on('error', function (file, response) {
//             $(file.previewElement).find(".dz-error-message").html(response.message)
//         });
//         this.on('removedfile', function (file) {
//             $.ajax({
//                 url: laroute.route('admin.staff.delete'),
//                 method: "POST",
//                 data: {
//                     filename: $("#file_image").val()
//                 },
//                 success: function (data) {
//
//                 }
//             })
//         });
//
//
//         //lấy URL hình từ db là load dc
//         var image=$('#ima').val();
//
//
//         var file = {
//             name: 'staff_avatar',
//             size: 10000,
//             dataURL:"/"+ image,
//         };
//         // var myDropzone=this;
//         this.emit("addedfile", file);
//         this.files.push(file);
//         // this.createThumbnailFromUrl(file, file.dataURL, callback, crossOrigin);
//         this.emit('thumbnail', file, file.dataURL,
//             this.options.thumbnailWidth,
//             this.options.thumbnailHeight,
//             this.options.thumbnailMethod,
//             true,
//             function (thumbnail) {
//                 this.emit('thumbnail', file, thumbnail);
//
//             }, "anonymous");
//
//         this.emit("complete", file);
//         var existingFileCount = 1;
//         this.options.maxFiles = this.options.maxFiles - existingFileCount;
//
//
//     }
// };
