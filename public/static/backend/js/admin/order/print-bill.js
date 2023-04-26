//In hóa đơn ở ds đơn hàng
var print_bill = {
    print: function (id) {
        $('#orderiddd').val(id);
        $('#form-order-ss').submit();
    }
};