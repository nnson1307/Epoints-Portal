function uploadImage(input) {
    $('.image-info').text('');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#file_name_avatar');
        reader.onload = function (e) {
            $('#blah-add')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        $('.delete-img').show();
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_product.');

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
        var fsize = input.files[0].size;
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());

        $.ajax({
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                imageAvatar.val(data.file);
            }
        });
    }
}

$('.btn-save-image').click(function () {
    let linkImageAvatar = $('#file_name_avatar').val();

    var arrayImage = new Array();
    $('.image-product').each(function () {
        arrayImage.push($(this).val());
    });
    $('.imagoday').empty();
    $('.append-image-poduct').empty();
    $('.bbbbbbbb').show();
    if (linkImageAvatar != "") {
        let $_tpl = $('#JS-template-avatar').html();
        let tpl = $_tpl;
        tpl = tpl.replace(/{link1}/g, linkImageAvatar);
        $('.imagoday').append(tpl);
    }
    $('.aaaaad').show();
    for (let i = 0; i < arrayImage.length; i++) {
        let $_tpl = $('#JS-template-image-product').html();
        let tpl = $_tpl;
        tpl = tpl.replace(/{link2}/g, arrayImage[i]);
        tpl = tpl.replace(/{linkImg}/g,arrayImage[i]);
        $('.append-image-poduct').append(tpl);
    }
    $('#addImage').modal('hide');
});
