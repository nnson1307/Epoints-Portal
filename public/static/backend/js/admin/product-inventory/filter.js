$('#search-warehouse').click(function () {
    let warehouse = $('.search-warehouse').val();
    if (warehouse != "") {
        $.ajax({
            url: laroute.route('admin.product-inventory.search-by-warehouse'),
            method: "POST",
            data: {warehouse: warehouse},
            success: function (data) {
                $('.list-product-inventory').empty();
                $('.list-product-inventory').append(data);
                $('#search-keyword').val('');
            }
        });
    }
    $.ajax({
        url: laroute.route('admin.product-inventory.list-inventory-input'),
        method: "POST",
        data: {
            searchWarehouse: warehouse
        },
        success: function (data) {
            $('#input-inventory').empty();
            $('#input-inventory').append(data);
            let a = "";
            for (let i = 0; i < warehouse.length; i++) {
                a += warehouse[i] + ",";
            }
            var newStr = a.substring(0, a.length - 1);
            $('#inventory-input-warehouse').val(newStr);
        }
    });
    $.ajax({
        url: laroute.route('admin.product-inventory.list-inventory-output'),
        method: "POST",
        data: {
            searchWarehouse: warehouse
        },
        success: function (data) {
            $('#output-inventory').empty();
            $('#output-inventory').append(data);
            let a = "";
            for (let i = 0; i < warehouse.length; i++) {
                a += warehouse[i] + ",";
            }
            var newStr = a.substring(0, a.length - 1);
            $('#inventory-output-warehouse').val(newStr);
        }
    });
    $.ajax({
        url: laroute.route('admin.product-inventory.list-inventory-checking'),
        method: "POST",
        data: {
            searchWarehouse: warehouse
        },
        success: function (data) {
            $('#inventory-checking').empty();
            $('#inventory-checking').append(data);
            let a = "";
            for (let i = 0; i < warehouse.length; i++) {
                a += warehouse[i] + ",";
            }
            var newStr = a.substring(0, a.length - 1);
            $('#inventory-checking-warehouse').val(newStr);
        }
    });
    $.ajax({
        url: laroute.route('admin.product-inventory.list-inventory-transfer'),
        method: "POST",
        data: {
            searchWarehouse: warehouse
        },
        success: function (data) {
            $('#inventory-transfer').empty();
            $('#inventory-transfer').append(data);
            let a = "";
            for (let i = 0; i < warehouse.length; i++) {
                a += warehouse[i] + ",";
            }
            var newStr = a.substring(0, a.length - 1);
            $('#inventory-transfer-warehouse').val(newStr);
        }
    });
});
$('#search').click(function () {
    if ($('#search-keyword').val() == "" && $('.search-warehouse').val() == "") {
        listAllProductInventory();
    }
    if ($('#search-keyword').val().trim() !== "") {
        let keyword = $('#search-keyword').val().trim();
        let warehouse = $('.search-warehouse').val();
        $.ajax({
            url: laroute.route('admin.product-inventory.search-by-product'),
            method: "POST",
            data: {
                keyword: keyword,
                warehouse: warehouse
            },
            success: function (data) {
                $('.list-product-inventory').empty();
                $('.list-product-inventory').append(data);
            }
        })
    }
    $('#keyword-hidden').val($('#search-keyword').val().trim());
});

function listAllProductInventory() {
    $(".list-product-inventory").load(location.href + " .list-product-inventory", "");
}

// $('#search-keyword').keyup(function (e) {
//     if (e.keyCode == 13) {
//         $(this).trigger("enterKey");
//     }
// });
$('#search-keyword').bind("enterKey", function (e) {
    $("#search").trigger("click");
});
function filterProduct() {
    $("#search").trigger("click");
}

function keyword() {
    var keyword = $('#search-keyword').val();
    $('#hidden-keyword').val(keyword);
}


