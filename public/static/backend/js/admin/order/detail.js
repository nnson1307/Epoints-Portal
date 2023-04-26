var detail = {
    addDelivery: function () {
        $.ajax({
            url: laroute.route('delivery.store-delivery'),
            data: {
                order_id: $('#order_id').val(),
                customer_id: $('#customer_id').val(),
                contact_name: $('#contact_name').val(),
                contact_phone: $('#contact_phone').val(),
                contact_address: $('#contact_address').val(),
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.error == false) {
                    swal(response.message, "", "success");
                    location.reload();
                } else {
                    swal(response.message, "", "error")
                }
            }
        });
    },
    showModalImage: function (type) {
        if (type == 'before') {
            $('#modal-image-before').modal({
                backdrop: 'static', keyboard: false
            });
        } else if(type == 'after') {
            $('#modal-image-after').modal({
                backdrop: 'static', keyboard: false
            });
        }
    },
    dropzoneBefore: function () {
        Dropzone.options.dropzoneBefore = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 100,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
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
                    data.append("link", "_order.");
                });

                this.on("success", function (file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

                    if (response.error == 0) {
                        //Xoá ảnh ra khỏi dropzone
                        file.previewElement.remove();
                        $('#dropzoneBefore').removeClass('dz-started');
                        //Lưu ảnh vừa up vào temp
                        let tpl = $('#tpl-image').html();
                        tpl = tpl.replace(/{imageName}/g, response.file);
                        $('#up-image-before-temp').append(tpl);
                    }
                });

                this.on('removedfile', function (file, response) {
                    var checkImage = $('#up-image-before-temp').find('input[name="fileName"]');

                    $.each(checkImage, function () {
                        if ($(this).attr('class') == file.upload.filename) {
                            $(this).remove();
                        }
                    });
                });
            }
        };
    },
    dropzoneAfter: function () {
        Dropzone.options.dropzoneAfter = {
            paramName: 'file',
            maxFilesize: 10, // MB
            maxFiles: 100,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": $('input[name=_token]').val()
            },
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
                    data.append("link", "_order.");
                });

                this.on("success", function (file, response) {
                    var a = document.createElement('span');
                    a.className = "thumb-url btn btn-primary";
                    a.setAttribute('data-clipboard-text', laroute.route('admin.upload-image'));

                    if (response.error == 0) {
                        //Xoá ảnh ra khỏi dropzone
                        file.previewElement.remove();
                        $('#dropzoneAfter').removeClass('dz-started');
                        //Lưu ảnh vừa up vào temp
                        let tpl = $('#tpl-image').html();
                        tpl = tpl.replace(/{imageName}/g, response.file);
                        $('#up-image-after-temp').append(tpl);
                    }
                });

                this.on('removedfile', function (file, response) {
                    var checkImage = $('#up-image-after-temp').find('input[name="fileName"]');

                    $.each(checkImage, function () {
                        if ($(this).attr('class') == file.upload.filename) {
                            $(this).remove();
                        }
                    });
                });
            }
        };
    },
    removeImage: function (obj) {
        $(obj).closest('.image-show-child').remove();
    },
    saveImage: function (orderCode, type) {
        var arrImage = [];

        if (type == 'before') {
            //Lấy hình ảnh trước
            $.each($('#up-image-before-temp').find('input[name="img-order"]'), function () {
                arrImage.push($(this).val());
            });
        } else if(type == 'after') {
            //Lấy hình ảnh sau
            $.each($('#up-image-after-temp').find('input[name="img-order"]'), function () {
                arrImage.push($(this).val());
            });
        }

        $.ajax({
            url: laroute.route('admin.order.save-image'),
            method: "POST",
            dataType: "JSON",
            data: {
                order_code: orderCode,
                type: type,
                arrImage: arrImage
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            $('#modal-image-before').modal('hide');
                            window.location.reload();
                        }
                        if (result.value == true) {
                            $('#modal-image-before').modal('hide');
                            window.location.reload();
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            }
        });
    },
    zoomImage: function (link) {
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        modal.style.display = "block";
        modalImg.src = link;
        captionText.innerHTML = '';

    },
    closeModalZoom: function () {
        // Get the modal
        var modal = document.getElementById("myModal");

        modal.style.display = "none";
    },

    showPopupSerial : function(order_detail_id,product_code){
        $.ajax({
            url: laroute.route('admin.order.showPopupSerial'),
            data: {
                order_detail_id: order_detail_id,
                product_code : product_code,
                type_view : 'detail'
            },
            method: 'POST',
            dataType: "JSON",
            success: function (res) {
                if(res.error == false){
                    $('#showPopup').html(res.view);
                    $('#popup-list-serial').modal('show');
                } else {
                    swal(res.message, "", "error");
                }
            }
        })
    }
};

var order = {
    searchSerial : function(){
        $.ajax({
            url: laroute.route('admin.order.searchSerial'),
            data: $('#form-list-serial').serialize(),
            method: 'POST',
            dataType: "JSON",
            success: function (res) {
                if(res.error == false){
                    $('.block-list-serial').html(res.view);
                } else {
                    swal(res.message, "", "error");
                }
            }
        })
    },

    changePageSerial: function(page){
        $('#page_serial').val(page);
        order.searchSerial();
    },

    removeSearchSerial : function(){
        $('.page_serial').val(page);
        $('#serial_search').val('');
        order.searchSerial();
    },
}