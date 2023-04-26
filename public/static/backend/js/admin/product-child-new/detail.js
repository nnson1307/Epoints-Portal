var detailProduct = {
    getListInventoryChecking : function () {
        $.ajax({
            url: laroute.route('admin.product-child-new.get-list-inventory'),
            method: "POST",
            data: {
                product_code: $('#product_child_code').val(),
                warehouse_id : $('#warehouse_id').val(),
                page : $('#page_inventory').val()

            },
            dataType: "JSON",
            success: function (res) {
                if(res.error == false){
                    $('.block-list-inventory').empty();
                    $('.block-list-inventory').append(res.view);
                }
            }
        });
    },

    changePageSerial : function(page){
        $('#page_inventory').val(page);
        detailProduct.getListInventoryChecking();
    },

    showPopup : function(warehouse_id,product_code){
        $.ajax({
            url: laroute.route('admin.product-child-new.show-popup-serial'),
            method: "POST",
            data: {
                warehouse_id : warehouse_id,
                product_code : product_code
            },
            dataType: "JSON",
            success: function (res) {
                if(res.error == false){
                    $('#showPopup').empty();
                    $('#showPopup').append(res.view);
                    $('#popup-list-serial').modal('show');
                    $('#popup-list-serial select').select2();
                }
            }
        });
    },

    getListSerial : function(){
        $.ajax({
            url: laroute.route('admin.product-child-new.get-list-serial-popup'),
            method: "POST",
            data: $('#form-list-serial').serialize(),
            dataType: "JSON",
            success: function (res) {
                if(res.error == false){
                    $('.block-list-serial').empty();
                    $('.block-list-serial').append(res.view);
                }
            }
        });
    },

    changePageSerialPopup : function(page){
        $('#popup-list-serial #page_serial').val(page);
        detailProduct.getListSerial();
    },

    removeSearchSerialPopup : function(){
        $('#popup-list-serial #page_serial').val(1);
        $('#popup-list-serial select').val('').trigger('change');
        $('#popup-list-serial input[type="text"]').val('');
        detailProduct.getListSerial();
    }



}

$('document').ready(function(){
    detailProduct.getListInventoryChecking();
})