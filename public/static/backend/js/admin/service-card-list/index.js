$('#autotable').PioTable({
    baseUrl: laroute.route('admin.service-card-list.list')
});

function refresh() {
    $('input[name="search_keyword"]').val('');
    $('select[name="service_cards$service_card_type"]').val('').trigger('change');
    $.ajax({
        url: laroute.route('admin.service-card-list.paging'),
        method: "POST",
        data: {page: 1},
        success: function (data) {
            $('.list-service-card-list').empty();
            $('.list-service-card-list').append(data);
        }
    });
}

$('.m_selectpicker').selectpicker();
$(document).ready(function () {
    $("#tb-service-card-list").tableHeadFixer({"head": false, "left": 4});
});

//phân trang cho tất cả sản phẩm.
function pageClick(o) {
    $.ajax({
        url: laroute.route('admin.service-card-list.paging'),
        method: "POST",
        data: {page: $(o).text()},
        success: function (data) {
            $('.list-service-card-list').empty();
            $('.list-service-card-list').append(data);
        }
    });
}

//Trang đầu hoặc trang cuối của tất cả sản phẩm
function firstAndLastPage(o) {
    $.ajax({
        url: laroute.route('admin.service-card-list.paging'),
        method: "POST",
        data: {page: o},
        success: function (data) {
            $('.list-service-card-list').empty();
            $('.list-service-card-list').append(data);
        }
    });
}

$('.btn-search').click(function () {
    let keyWord = $('input[name="search_keyword"]').val();
    let type = $('select[name="service_cards$service_card_type"]').val();
    let branch = $('select[name="branches$branch_id"]').val();
    if (keyWord == '' && type == '' && branch == '') {
        refresh();
    } else {
        $.ajax({
            url: laroute.route('service-card-list.filter'),
            method: "POST",
            data: {
                keyWord: keyWord,
                type: type,
                branch: branch,
            },
            success: function (data) {
                $('.list-service-card-list').empty();
                $('.list-service-card-list').append(data);
            }
        })
    }
});

$('input[name="search_keyword"]').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});
$('input[name="search_keyword"]').bind("enterKey", function (e) {
    $(".btn-search").trigger("click");
});


$('select[name="service_cards$service_card_type"]').select2(
).on("select2:select", function (e) {
    $(".btn-search").trigger("click");
}).on("select2:unselect", function (e) {
    $(".btn-search").trigger("click");
});

//Trang đầu hoặc trang cuối của kết quả tìm kiếm sản phẩm
function firstOrLastPageSearch(o) {
    $.ajax({
        url: laroute.route('admin.service-card-list.paging-search'),
        method: "POST",
        data: {
            page: o,
            keyWord: $('input[name="search_keyword"]').val(),
            type: $('select[name="service_cards$service_card_type"]').val()
        },
        success: function (data) {
            $('.list-service-card-list').empty();
            $('.list-service-card-list').append(data);
        }
    });
}

//Phân trang cho tìm kiếm.
function pageSearchClick(o) {
    $.ajax({
        url: laroute.route('admin.service-card-list.paging-search'),
        method: "POST",
        data: {
            page: $(o).text(),
            keyWord: $('input[name="search_keyword"]').val(),
            type: $('select[name="service_cards$service_card_type"]').val()
        },
        success: function (data) {
            $('.list-service-card-list').empty();
            $('.list-service-card-list').append(data);
        }
    });
}
// $('.selectpicker').selectpicker();

