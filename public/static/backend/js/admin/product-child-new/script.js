AutoNumeric.multiple('#cost, #price',{
    currencySymbol : '',
    decimalCharacter : '.',
    digitGroupSeparator : ',',
    decimalPlaces: decimal_number
});

var list_prod_child = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('admin.product-child-new.list')
        });
    },

    changeActive: function (product_child_id, is_actived) {
        $.ajax({
            url: laroute.route('admin.product-child-new.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                product_child_id: product_child_id,
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    changeSurcharge: function (product_child_id, is_surcharge) {
        $.ajax({
            url: laroute.route('admin.product-child-new.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                product_child_id: product_child_id,
                is_surcharge: is_surcharge
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },

    changeDisplay: function (product_child_id, is_display) {
        $.ajax({
            url: laroute.route('admin.product-child-new.update-status'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                product_child_id: product_child_id,
                is_display: is_display
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    },
    changeRemind: function (obj) {
        if ($(obj).is(':checked')) {
            $('.div_remind_value').css('display', 'block');
            $(obj).val(1);
        } else {
            $('.div_remind_value').css('display', 'none');
            $(obj).val(0);
        }
        //Bật/ tắt giá trị back về 1
        $('#remind_value').val(1);
    }
};

var edit_prod_child = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $("#tag_id").select2({
                placeholder: json['Chọn tag'],
                tags: true,
                // tokenSeparators: [",", " "],
                    createTag: function (newTag) {
                    return {
                        id: 'new:' + newTag.term,
                        text: newTag.term,
                        isNew: true
                    };
                }
            }).on("select2:select", function (e) {
                if (e.params.data.isNew) {
                    // store the new tag:
                    $.ajax({
                        type: "POST",
                        url: laroute.route('admin.product-tag.store'),
                        data: {
                            tag_name: e.params.data.text
                        },
                        success: function (res) {
                            // append the new option element end replace id
                            $('#tag_id').find('[value="' + e.params.data.id + '"]').replaceWith('<option selected value="' + res.tag_id + '">' + e.params.data.text + '</option>');
                        }
                    });
                }
            });
        });
    },
    changeBoolean: function (obj) {
        if ($(obj).is(":checked")) {
            $(obj).val(1);
        } else {
            $(obj).val(0);
        }
    },
    save: function () {
        var arrImageOld = [];
        var arrImageNew = [];
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#edit-product');
            form.validate({
                rules: {
                    product_child_name: {
                        required: true
                    },
                    cost: {
                        required: true
                    },
                    price: {
                        required: true
                    },
                    remind_value: {
                        integer: true,
                        min: 1,
                        required: true
                    }
                },
                messages: {
                    product_child_name: {
                        required: json['Vui lòng nhập tên sản phẩm con.'],
                    },
                    cost: {
                        required: json['Vui lòng nhập giá nhập.'],
                    },
                    price: {
                        required: json['Vui lòng nhập giá bán.'],
                    },
                    remind_value: {
                        integer: json['Kiểu dữ liệu không hợp lệ'],
                        min: json['Số ngày tối thiếu phải lớn hơn 0'],
                        required: json['Hãy nhập số ngày nhắc lại']
                    }
                },
            });

            if (!form.valid()) {
                return false;
            }
            // check is_active
            let is_actived = 0;
            if($('#is_actived').is(":checked")) {
                is_actived = 1;
            }

            // check is_display
            let is_display = 0;
            if($('#is_display').is(":checked")) {
                is_display = 1;
            }
            // check is_applied_kpi
            let is_applied_kpi = 0;
            if($('#is_applied_kpi').is(":checked")) {
                is_applied_kpi = 1;
            }
            if ($('input[name="is_surcharge"]').is(':checked')) {
                $('#is_surcharge').val(1);
            } else {
                $('#is_surcharge').val(0);
            }
            // foreach row image
            $.each($('.image-show').find(".list-image-old"), function () {
                let link = $(this).find($('.product_image')).val();
                arrImageOld.push(link);
            });
            $.each($('.image-show').find(".list-image-new"), function () {
                let link = $(this).find($('.product_image')).val();
                arrImageNew.push(link);
            });
            var arrCustom = {};
            for (let i = 1; i <= 10; i++) {
                let custom = `custom_${i}`;
                if($(`#${custom}`).val() != undefined){
                    arrCustom[custom] = $(`#${custom}`).val();
                }
            }
            $.ajax({
                url: laroute.route('admin.product-child-new.update'),
                data: {
                    arrCustom : arrCustom,
                    product_child_id: $('#product_child_id').val(),
                    product_child_code: $('#product_child_code').val(),
                    product_child_name: $('#product_child_name').val(),
                    product_child_sku: $('#product_child_sku').val(),
                    product_id: $('#product_id').val(),
                    cost: $('#cost').val(),
                    price: $('#price').val(),
                    is_actived: is_actived,
                    is_display: is_display,
                    is_applied_kpi: is_applied_kpi,
                    is_surcharge: $('#is_surcharge').val(),
                    product_avatar: $('#product_avatar').val(),
                    arrImageOld: arrImageOld,
                    arrImageNew: arrImageNew,
                    is_remind: $('#is_remind').val(),
                    remind_value: $('#remind_value').val(),
                    tag_id: $('#tag_id').val(),
                    barcode: $('#barcode').val()
                },
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    if (response.error == false) {
                        swal(response.message, "", "success");
                        window.location = laroute.route('admin.product-child-new');
                    } else {
                        swal(response.message, "", "error")
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm thất bại'], mess_error, "error");
                }
            });

        });
    },
};

var productImage = {
    remove_avatar: function () {
        $('.avatar').empty();
        var tpl = $('#avatar-tpl').html();
        $('.avatar').append(tpl);
        $('.image-format').text('');
        $('.image-size').text('');
        $('.image-capacity').text('');
    },

    image_dropzone: function () {
        $('#addImage').modal('show');
        $('#up-ima').empty();
        $('.dropzone')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('.dropzone').removeClass('dz-started');
    },

    remove_img: function (e) {
        $(e).closest('.image-show-child').remove();
    },

    save_image: function () {
        var arrayImage = new Array();
        $('.file_Name').each(function () {
            arrayImage.push($(this).val());
        });
        // $('.image-show').empty();
        for (let i = 0; i < arrayImage.length; i++) {
            let $_tpl = $('#imgeShow').html();
            let tpl = $_tpl;
            tpl = tpl.replace(/{link}/g, arrayImage[i]);
            tpl = tpl.replace(/{link_hidden}/g, arrayImage[i]);
            $('.image-show').append(tpl);
            $('.delete-img-sv').css('display', 'block');
        }
        $('#addImage').modal('hide');
    }
}

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#product_avatar');
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_product.');

        var fsize = input.files[0].size;
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
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());
        $.getJSON(laroute.route('translate'), function (json) {
            if (Math.round(fsize / 1024) <= 10240) {
                $('.error_img').text('');
                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        if (res.error == 0) {
                            $('#product_avatar').val(res.file);
                            $('.delete-img').css('display', 'block');
                        }

                    }
                });
            } else {
                $('.error_img').text(json['Hình ảnh vượt quá dung lượng cho phép']);
            }
        });
    }
}