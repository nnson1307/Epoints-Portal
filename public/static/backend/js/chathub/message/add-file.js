$('.btn-save-image').click(function() {
    var arrayImage = new Array();
    var array = new Array();
    $('#image-file .message-image').remove();
    $('.image-hide').each(function() {
        // arrayImage.push("http://" + window.location.hostname +'/temp_upload/' + $(this).val());
        arrayImage.push($(this).val());
        array.push($(this).val());
    });
    $('.dz-image-preview').remove();;
    for (let i = 0; i < arrayImage.length; i++) {
        let $_tpl = $('#add-image').html();
        let tpl = $_tpl;
        tpl = tpl.replace(/{link}/g, arrayImage[i]);
        tpl = tpl.replace(/{name}/g, array[i]);
        $('#image-file').append(tpl);
    }
    $('#editImage').modal('hide');
});

$('.btn-save-file').click(function() {
    var arrayFile = new Array();
    var array = new Array();
    $('#image-file .message-file').remove();
    $('.file-hide').each(function() {
        // arrayFile.push("http://" + window.location.hostname +'/temp_upload/' + $(this).val());
        arrayFile.push($(this).val());
        // array.push($(this).val());
    });
    $('.file-hide-name').each(function() {
        // arrayFile.push("http://" + window.location.hostname +'/temp_upload/' + $(this).val());
        array.push($(this).val());
    });

    $('.dz-file-preview').remove();
    for (let i = 0; i < arrayFile.length; i++) {
        let $_tpl = $('#add-file').html();
        let tpl = $_tpl;
        tpl = tpl.replace(/{link}/g, arrayFile[i]);
        tpl = tpl.replace(/{name}/g, array[i]);
        $('#image-file').append(tpl);
    }
    $('#editFile').modal('hide');
});

var Upload = {
    removeImage: function(nameImage, o) {
        $.ajax({
            url: laroute.route('message.delete-image-temp'),
            method: "POST",
            data: {
                filename: nameImage
            },
            success: function() {
                $(o).parents('.message-image').remove();
                $(".image-hide").each(function() {
                    var $this = $(this);
                    if ($this.val() == nameImage) {
                        $this.remove();
                    }
                });
            }
        })
    },
    removeFile: function(nameImage, o) {
        $.ajax({
            url: laroute.route('message.delete-image-temp'),
            method: "POST",
            data: {
                filename: nameImage
            },
            success: function() {
                $(o).parents('.message-file').remove();
                $(".file-hide").each(function() {
                    var $this = $(this);
                    if ($this.val() == nameImage) {
                        $this.remove();
                    }
                });
            }
        })
    }
}