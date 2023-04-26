var GetList = {
    getListInventoryInput: function () {
        $.ajax({
            url: laroute.route('admin.product-inventory.list-inventory-input'),
            method: "POST",
            data: {
                searchWarehouse: $('.search-warehouse').val()
            },
            success: function (data) {
                $('#input-inventory').empty();
                $('#input-inventory').append(data);
                $('#inventory-input-warehouse').val(strWarehouse());
            }
        });
    },
    getListInventoryOutput: function () {
        $.ajax({
            url: laroute.route('admin.product-inventory.list-inventory-output'),
            method: "POST",
            data: {
                searchWarehouse: $('.search-warehouse').val()
            },
            success: function (data) {
                $('#output-inventory').empty();
                $('#output-inventory').append(data);
                $('#inventory-output-warehouse').val(strWarehouse());
            }
        });
    },
    getListInventoryChecking: function () {
        $.ajax({
            url: laroute.route('admin.product-inventory.list-inventory-checking'),
            method: "POST",
            data: {
                searchWarehouse: $('.search-warehouse').val()
            },
            success: function (data) {
                $('#inventory-checking').empty();
                $('#inventory-checking').append(data);
                $('#inventory-checking-warehouse').val(strWarehouse())
            }
        });
    },
    getListInventoryTransfer: function () {
        $.ajax({
            url: laroute.route('admin.product-inventory.list-inventory-transfer'),
            method: "POST",
            data: {
                searchWarehouse: $('.search-warehouse').val()
            },
            success: function (data) {
                $('#inventory-transfer').empty();
                $('#inventory-transfer').append(data);
                $('#inventory-transfer-warehouse').val(strWarehouse())
            }
        });
    },
    listProductInventory: function (page = 1) {
        var keyword = $('#search-keyword').val();
        $.ajax({
            url: laroute.route('admin.product-inventory.listProductInventory'),
            method: "POST",
            data: {
                keyword: keyword,
                page: page,
            },
            success: function (res) {
                $('.list-product-inventory').html(res);
            }
        });
    },
    valKeyword: function (o) {
        var keyword = $(o).val();
        $('#hidden-keyword').val(keyword);
    },
    getInventoryConfig: function () {
        $.ajax({
            url: laroute.route('admin.product-inventory.config'),
            method: "POST",
            data: {},
            success: function (data) {
                $('#inventory-config').empty();
                $('#inventory-config').append(data);
                $('#branch').select2();
            }
        });
    },
    saveInventoryConfig: function () {
        $.ajax({
            url: laroute.route('admin.product-inventory.save-config'),
            method: "POST",
            data: {
                branchId: $('#branch').val()
            },
            success: function (response) {
                if (response.error == false) {
                    swal(response.message, "", "success");
                    window.location = laroute.route('admin.product-inventory');
                } else {
                    swal(response.message, "", "error")
                }
            }
        });
    }
};

function strWarehouse() {
    let warehouse = $('.search-warehouse').val();
    let a = "";
    for (let i = 0; i < warehouse.length; i++) {
        a += warehouse[i] + ",";
    }
    var newStr = a.substring(0, a.length - 1);
    return newStr;
}
//phân trang cho tất cả sản phẩm.
function pageClick(o) {
    $.ajax({
        url: laroute.route('admin.product-inventory.paging'),
        method: "POST",
        data: {page: $(o).text()},
        success: function (data) {
            $('.list-product-inventory').empty();
            $('.list-product-inventory').append(data);
        }
    });
}
//Trang đầu hoặc trang cuối của tất cả sản phẩm
function firstAndLastPage(o) {
    $.ajax({
        url: laroute.route('admin.product-inventory.paging'),
        method: "POST",
        data: {page: o},
        success: function (data) {
            $('.list-product-inventory').empty();
            $('.list-product-inventory').append(data);
        }
    });
}

//Phân trang cho tìm kiếm sản phẩm.
function pageSearchClick(o) {
    $.ajax({
        url: laroute.route('admin.product-inventory.paging-search'),
        method: "POST",
        data: {
            page: $(o).text(),
            keyword:  $('#keyword-hidden').val(),
        },
        success: function (data) {
            $('.list-product-inventory').empty();
            $('.list-product-inventory').append(data);
        }
    });
}
//Trang đầu hoặc trang cuối của kết quả tìm kiếm sản phẩm
function firstOrLastPageSearch(o) {
    $.ajax({
        url: laroute.route('admin.product-inventory.paging-search'),
        method: "POST",
        data: {
            page: o,
            keyword: $('#keyword-hidden').val(),
        },
        success: function (data) {
            $('.list-product-inventory').empty();
            $('.list-product-inventory').append(data);
        }
    });
}
function refreshProductInventory() {
    $('#search-keyword').val('');
    $("#search").trigger("click");
}
function search() {
    $(".btn-search").trigger("click");
}
GetList.listProductInventory();

