var product = {
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),

    _init : function (){
        selectTopping();
    },
    update : function (){
        $.getJSON(laroute.route("translate"), function (json) {
            var form = $("#edit-product");

            form.validate({
                rules: {

                    product_name_en: {
                        required: true,
                        maxlength: 255,
                    },

                },
                messages: {
                    product_name_en: {
                        required: json["Hãy nhập tên sản phẩm (EN)"],
                        maxlength: json["Tên sản phẩm (EN) vượt quá 255 ký tự"],
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }

            var description = $(".summernote").summernote("code");

            $.ajax({
                url: laroute.route("fnb.product.check-name"),
                method: "POST",
                data: {
                    productNameEN: $("#product-name-en").val().trim(),
                    id: $("#idHidden").val(),
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.error == true) {
                        $(".error-product-name-en").text(data.message);
                    } else {
                        $.ajax({
                            url: laroute.route("fnb.product.update"),
                            method: "POST",
                            data: {
                                id: $("#idHidden").val(),
                                productNameEN: $("#product-name-en").val(),
                                description_en: $("#description_en").val(),
                                description_detail_en: description,
                            },
                            success: function (res) {
                                if (res.error == false){
                                    swal(res.message, "", "success").then(function (){
                                        location.reload();
                                    });
                                } else {
                                    swal(res.message, "", "error");
                                }
                            },
                        });
                    }
                }
            })
        });
    },

    changeSelectTopping: function (is_quantity = false,product_child_id = 0){
        if (is_quantity == true){
            var quantity = parseInt($('.product_child_id_'+product_child_id+' .quantity').val());
        } else {
            var quantity = 1;
        }



        if (is_quantity == true){
             product_child_id = $('.product_child_id_'+product_child_id+' .product_child_id').val();
             product_child_name = $('.product_child_id_'+product_child_id+' .product_child_name').val();
        } else {
            product_child_id = $('.list-topping-select').val();
            product_child_name = $('.list-topping-select option:selected').text();
        }

        $.ajax({
            url: laroute.route("fnb.product.add-topping-session"),
            method: "POST",
            data: {
                id: $("#idHidden").val(),
                product_child_id: product_child_id,
                product_child_name: product_child_name,
                quantity : quantity,
                is_quantity : is_quantity
            },
            success: function (res) {
                if (res.error == false){
                    $('.block-list-topping').empty();
                    $('.block-list-topping').append(res.view);
                    $(".quantity").TouchSpin({min: 1});
                    selectTopping();
                }
            },
        });
    },

    removeTopping : function (product_child_id){
        $.ajax({
            url: laroute.route("fnb.product.remove-topping-session"),
            method: "POST",
            data: {
                product_child_id: product_child_id,
            },
            success: function (res) {
                if (res.error == false){
                    $('.block-list-topping').empty();
                    $('.block-list-topping').append(res.view);
                    $(".quantity").TouchSpin({min: 1});
                    selectTopping();
                }
            },
        });
    },

    saveTopping : function (){
        $.ajax({
            url: laroute.route("fnb.product.store-topping"),
            method: "POST",
            data: $('#edit-product').serialize(),
            success: function (res) {
                if (res.error == false){
                    swal(res.message, '', "success").then(function (){
                        location.reload();
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
        });
    }
}

function selectTopping(){
    $('.list-topping-select').select2({
        dropdownParent: $('.list-topping-select').parent(),
        width: '100%',
        placeholder: product.jsonLang['Chọn sản phẩm đính kèm'],
        // placeholder: 'Chọn tác vụ cha',
        ajax: {
            url: laroute.route('fnb.product.get-list-product-child'),
            data: function (params) {
                return {
                    product_child_name: params.term,
                    page: params.page || 1,
                };
            },
            dataType: 'json',
            method: 'POST',
            processResults: function (data) {
                data.page = data.page || 1;
                return {
                    results: data.data.map(function (item) {
                        return {
                            id: item.product_child_id,
                            text: item.product_child_name,
                        };
                    }),
                    pagination: {
                        more: data.current_page + 1
                    }
                };
            },
        }
    }).on('select2:select', function (e) {
        product.changeSelectTopping();
    });
}

uploadImg = function (file) {
    let out = new FormData();
    out.append('file', file, file.name);
    out.append('link', '_product.');

    $.ajax({
        method: 'POST',
        url: laroute.route('admin.upload-image'),
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function (img) {
            $(".summernote").summernote('insertImage', img['file'] , function (image){
                image.css('width', '100%');
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus + " " + errorThrown);
        }
    });
};