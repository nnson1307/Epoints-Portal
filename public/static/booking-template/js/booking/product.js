var listProduct = {
    _init: function () {
        var product_category_id = $('.product-select').val();
        $.ajax({

            url: laroute.route('product.getProductGroup'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                product_category_id:product_category_id,
                page : 1,
                display: display
            },
            success: function (res) {
                $('#list-product').html(res.html);
                // initMap();
            }
        })
    }
};
listProduct._init();

var product = {
    change: function () {
        var product_category_id = $('.product-select').val();
        $.ajax({

            url: laroute.route('product.getProductGroup'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                product_category_id:product_category_id,
                page : 1,
                display: display
            },
            success: function (res) {
                $('#list-product').html(res.html);
                // initMap();
            }
        })
    },
    redirectToIndex: function () {
        $('#frm-detail').submit();
    }
};

var step3 = {
    pageClick: function (page) {
        var product_category_id = $('.product-select').val();
        $.ajax({
            url: laroute.route('product.getProductGroup'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                product_category_id:product_category_id,
                page : page,
                display: display
            },
            success: function (res) {
                $('#list-product').html(res.html);
            }
        });
    },
    firstAndLastPage: function (page) {
        var product_category_id = $('.product-select').val();
        $.ajax({
            url: laroute.route('product.getProductGroup'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                product_category_id:product_category_id,
                page : page,
                display: display
            },
            success: function (res) {
                $('#list-product').html(res.html);
            }
        });
    },
    // filter: function (obj) {
    //     var service_category_id = $('.product-select').val();
    //     $.ajax({
    //         url: laroute.route('service.getServiceGroup'),
    //         method: 'POST',
    //         dataType: 'JSON',
    //         data: {
    //             branch_id: $('input[name=branch]:checked').val(),
    //             service_id: $(obj).val(),
    //             arr_service: arr_service
    //         },
    //         success: function (res) {
    //             $('#list_step3').html(res);
    //
    //         }
    //     })
    // },
    // check_service: function (obj) {
    //     if ($(obj).is(':checked')) {
    //         arr_service.push($(obj).val());
    //     } else {
    //         var i = arr_service.indexOf($(obj).val());
    //         if (i != -1) {
    //             arr_service.splice(i, 1);
    //         }
    //     }
    // }
};