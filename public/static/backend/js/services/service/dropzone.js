Dropzone.options.dropzoneone = {
    url: laroute.route('services.uploads'),
    maxFiles: 1,
    paramName: "services_image",
    uploadMultiple: false,
    acceptedFiles: "image/*",
    headers: {
        "X-CSRF-TOKEN": $("input[name=_token]").val()
    },
    addRemoveLinks: true,
    maxFilesize: 1,
    init: function () {
        this.on('success', function (file, response) {
            if (response.success == 1)
                if ($('#file_image').length) {
                    $("#file_image").val(response.file);
                } else {
                    $("#form").prepend("<input id='file_image' type='hidden' name='services_image' value='" + response.file + "'>");
                }

        });//
        this.on('error', function (file, response) {
            $(file.previewElement).find(".dz-error-message").html(response.message)
        })
        this.on('removedfile', function (file) {
            $.ajax({
                url: laroute.route('services.uploads.delete'),
                method: "POST",
                data: {
                    filename: $("#file_image").val()
                },
                success: function (data) {

                }
            })
        })
    }
}