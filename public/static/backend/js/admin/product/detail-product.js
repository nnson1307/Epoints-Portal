var detailProduct = {
    showPopup: function (productCode) {
        $.ajax({
            url:laroute.route('admin.product.showPopupSerial'),
            method:"POST",
            data:{
                product_code : productCode
            },
            success:function (data) {
                if(data.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(data.view);
                    $('select').select2();
                    $('#popup-list-serial').modal('show');
                }
            }
        });
    },

    changePageSerial : function(page){
        $('#page_serial').val(page);
        detailProduct.searchSerial();
    },

    searchSerial:function(){
        $.ajax({
            url:laroute.route('admin.product.search-serial'),
            method:"POST",
            data: $('#form-list-serial').serialize(),
            success:function (data) {
                if(data.error == false){
                    $('.block-list-serial').empty();
                    $('.block-list-serial').append(data.view);
                }
            }
        });
    },

    removeSearchSerial : function(){
        $('#page_serial').val(1);
        $('#popup-list-serial select').val('').trigger('change');
        $('#popup-list-serial input').val('');
        detailProduct.searchSerial();
    }
}