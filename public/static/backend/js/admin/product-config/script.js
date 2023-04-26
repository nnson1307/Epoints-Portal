var view = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.input_int').ForceNumericOnly();

            $('#display_view_category').select2();

            $('#type_bundled_product').select2();

            $('#product_category').select2({
                placeholder: json['Chọn loại sản phẩm']
            });
        });
    },
    changeDisplay: function (obj) {
        if ($(obj).is(':checked')) {
            $('.div_type').css('display', 'block');
        } else {
            $('.div_type').css('display', 'none');
        }
    },
    changeType: function (obj) {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.div_detail').empty();

            if ($(obj).val() == 'custom_category') {
                var tpl = $('#custom-tpl').html();
                $('.div_detail').append(tpl);

                $('#product_category').select2({
                    placeholder: json['Chọn loại sản phẩm']
                });
            }
        });
    },
    save: function (configId) {
        $.getJSON(laroute.route('translate'), function (json) {
            var is_display_bundled = 0;
            if ($('#is_display_bundled').is(':checked')) {
                is_display_bundled = 1;
            }

            $.ajax({
                url: laroute.route('admin.product-config.update'),
                method: "POST",
                dataType: 'JSON',
                data: {
                    product_config_id: configId,
                    display_view_category: $('#display_view_category').val(),
                    is_display_bundled: is_display_bundled,
                    type_bundled_product: $('#type_bundled_product').val(),
                    product_category: $('#product_category').val(),
                    limit_item: $('#limit_item').val()
                },
                success: function (res) {
                    if (res.error == false) {
                        swal(res.message, "", "success").then(function (result) {
                            if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                                window.location.reload();
                            }
                            if (result.value == true) {
                                window.location.reload();
                            }
                        });
                    } else {
                        swal(res.message, '', "error");
                    }
                }
            });

        });
    }
};

jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };
