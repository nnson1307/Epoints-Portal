$('#choose-warehouse').select2({
        placeholder: "Chọn kho",
        allowClear: true,
        tags: true,
        createTag: function (tag) {
            return {
                id: tag.term,
                text: tag.term,
                isNew: true
            };
        }
    }
);
$('select[name="suppliers$supplier_id"]').select2();
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.product-inventory.list')
});

function getHistory(code) {
    $('.modal-history').empty();
    $.ajax({
        url: laroute.route('admin.product-inventory.history'),
        method: "POST",
        data: {code: code},
        success: function (data) {
            $('.modal-history').append(data);
        }
    });
}

$(document).ready(function () {
    // $("#tb-product-inventory").tableHeadFixer({"head": false, "left": 4});
});

var History={
    pageClick:function (page) {
        $.ajax({
            url: laroute.route('admin.product-inventory.paging-history'),
            method: "POST",
            data: {
                code: $('#code').val(),
                page:page,
            },
            success: function (data) {
                $('.table-content').empty();
                $('.table-content').append(data);
            }
        });
    }
};


