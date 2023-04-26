var ProductDeleteImageAdd = {
    deleteAvatar: function () {
        var avatarTemp = $('#file_name_avatar');
        $.ajax({
            url: laroute.route("admin.product.delete-image-temp2"),
            method: "POST",
            data: {img: avatarTemp.val()},
            success: function (data) {
                $('.avatar-temp').empty();
                avatarTemp.val('');
                $('.avatar-temp').append($('#image-avatar-temp').html());
            }
        });
    },
    deleteImageProduct: function (thi) {
        let img = $(thi).parents('.append-image-poduct').find('.valuelinkimg').val().replace("temp_upload/", "");
        ;

        $('#temp').find('input[name="fileName[]"]').each(function () {
            if ($(this).val() == img) {
                $(this).remove();
            }
        });
        $.ajax({
            url: laroute.route("admin.product.delete-image-temp2"),
            method: "POST",
            data: {img: img},
            success: function (data) {
                $(thi).closest('.delete-tempss').remove();
            }
        });
    },
    clearDropzone: function () {
        $('.dropzone')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });

        $('.dropzone').removeClass('dz-started');
    }
};

var ProductDeleteImageEdit = {
    deleteAvatar: function (image, $this) {
        // $($this).parents('.class-for-delete').find('.div-image-show').remove();
        $('#blah-edit').attr('src', $('#link-image-fault').val());
        $('#avatar-exist').val('');
        $($this).parents('.avatar-temp').find('.delete-img-show').addClass('delete-img');
        $.ajax({
            url: laroute.route("admin.product.delete-image-temp2"),
            method: "POST",
            data: {img: $('#file_name_avatar').val()},
            success: function (data) {
                $('#file_name_avatar').val('')
            }
        });
    },
    removeImage: function (image, $t) {
        $.ajax({
            url: laroute.route('product.delete-image-by-productId-link'),
            method: "POST",
            data: {
                productId: $('#idHidden').val(),
                link: image
            },
            dataType: "JSON",
            success: function (data) {
                if (data.error == 0) {
                    $('.image-hide').each(function () {
                        if ($(this).val() == image) {
                            $(this).remove();
                        }
                    });
                    $('.dropzone-append-image').each(function () {
                        if ('temp_upload/' + $(this).val() == image) {
                            $(this).remove();
                        }
                    });
                }
            }
        });
        $($t).parents('.class-for-delete').find('.div-image-show').remove();
    }
};