// $('#autotable').PioTable({
//     baseUrl: laroute.route('admin.service-card.list')
// });

var ServiceCard = {
    init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
        $("#created_at").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: [
                    json["CN"],
                    json["T2"],
                    json["T3"],
                    json["T4"],
                    json["T5"],
                    json["T6"],
                    json["T7"]
                ],
                "monthNames": [
                    json["Tháng 1 năm"],
                    json["Tháng 2 năm"],
                    json["Tháng 3 năm"],
                    json["Tháng 4 năm"],
                    json["Tháng 5 năm"],
                    json["Tháng 6 năm"],
                    json["Tháng 7 năm"],
                    json["Tháng 8 năm"],
                    json["Tháng 9 năm"],
                    json["Tháng 10 năm"],
                    json["Tháng 11 năm"],
                    json["Tháng 12 năm"]
                ],
                "firstDay": 1
            }
        });
    });
    },
    remove: function (obj, sid) {
        $.getJSON(laroute.route('translate'), function (json) {
        $(obj).closest('tr').addClass('m-table__row--danger');

        swal({
            title: json['Thông báo'],
            text: json["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: json['Xóa'],
            cancelButtonText: json['Hủy'],
            onClose: function () {
                // remove hightlight row
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.service-card.delete', {id: sid}), function (resp) {
                    if (resp.error == 0) {
                        swal(
                            resp.message,
                            '',
                            'success'
                        );

                        $.ajax({
                            url: laroute.route('admin.service-card.paging'),
                            method: "POST",
                            data: {
                                page: $('#page').val(),
                            },
                            success: function (data) {
                                $('.list-service-card').empty();
                                $('.list-service-card').append(data);
                            }
                        });
                    } else {
                        $.notify({
                            // options
                            message: resp.message
                        }, {
                            // settings
                            type: 'danger'
                        });
                    }

                });
            }
        });
    });
    },
    refresh: function () {
        refresh()
    },
    changeStatus: function (obj, id, action) {
        $.post(laroute.route('admin.service-card.change-status'), {id: id, action: action}, function (data) {
            filter();
        }, 'JSON');
    },
    changeSurcharge: function (obj, id,action) {
        if ($(obj).is(':checked')) {
            $('#is_surcharge').val(1);
        } else {
            $('#is_surcharge').val(0);
        }
        action = $('#is_surcharge').val();
        $.ajax({
            url: laroute.route('admin.service-card.change-status-surcharge'),
            method: "POST",
            data: {id: id, action: action},
            success: function (data) {
                // location.reload(true)
            }
        });
    },
};

ServiceCard.init();

// $(document).ready(function () {
//     $("#tb-service-card").tableHeadFixer({"head": false, "left": 5});
// });


$('select[name=is_actived]').select2().on('select2:select', function () {
    filter();
});
$('select[name=service_card_type]').select2().on('select2:select', function () {
    filter();
});
$('select[name="service_cards$service_card_group_id"]').select2().on('select2:select', function () {
    filter();
});
$('input[name="search_keyword"]').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});
$('input[name="search_keyword"]').bind("enterKey", function (e) {
    filter();
});

//Trang đầu hoặc trang cuối của tất cả thẻ dịch vụ
function firstAndLastPage(o) {
    $.ajax({
        url: laroute.route('admin.service-card.paging'),
        method: "POST",
        data: {page: o},
        success: function (data) {
            $('.list-service-card').empty();
            $('.list-service-card').append(data);
        }
    });
}

//phân trang cho tất cả thẻ dịch vụ.
function pageClick(o) {
    $.ajax({
        url: laroute.route('admin.service-card.paging'),
        method: "POST",
        data: {
            page: $(o).text(),
        },
        success: function (data) {
            $('.list-service-card').empty();
            $('.list-service-card').append(data);
            // $('.list-service-card').bind("DOMSubtreeModified", function () {
            //     $("#tb-service-card").tableHeadFixer({"head": false, "left": 4});
            //
            // });
        }
    });
}

//Trang đầu hoặc trang cuối của kết quả tìm kiếm .
function firstOrLastPageSearch(o) {
    let keyWord = $('input[name="search_keyword"]').val();
    let status = $('select[name=is_actived]').val();
    let cardType = $('select[name=service_card_type]').val();
    let cardGroup = $('select[name="service_cards$service_card_group_id"]').val();
    $.ajax({
        url: laroute.route('admin.service-card.paging-search'),
        method: "POST",
        data: {
            page: o,
            keyWord: keyWord,
            status: status,
            cardType: cardType,
            cardGroup: cardGroup
        },
        success: function (data) {
            $('.list-service-card').empty();
            $('.list-service-card').append(data);
        }
    });
}

//Phân trang cho tìm kiếm
function pageSearchClick(o) {
    let keyWord = $('input[name="search_keyword"]').val();
    let status = $('select[name=is_actived]').val();
    let cardType = $('select[name=service_card_type]').val();
    let cardGroup = $('select[name="service_cards$service_card_group_id"]').val();
    $.ajax({
        url: laroute.route('admin.service-card.paging-search'),
        method: "POST",
        data: {
            page: $(o).text(),
            keyWord: keyWord,
            status: status,
            cardType: cardType,
            cardGroup: cardGroup
        },
        success: function (data) {
            $('.list-service-card').empty();
            $('.list-service-card').append(data);
        }
    });
}

function refresh() {
    $('select[name=is_actived]').val('').trigger('change');
    $('select[name=service_card_type]').val('').trigger('change');
    $('select[name="service_cards$service_card_group_id"]').val('').trigger('change');
    $('input[name="search_keyword"]').val('').trigger('change');
    $.ajax({
        url: laroute.route('admin.service-card.paging'),
        method: "POST",
        data: {page: 1},
        success: function (data) {
            $('.list-service-card').empty();
            $('.list-service-card').append(data);
        }
    });
}

function filter() {
    let keyWord = $('input[name="search_keyword"]').val();
    let status = $('select[name=is_actived]').val();
    let cardType = $('select[name=service_card_type]').val();
    let cardGroup = $('select[name="service_cards$service_card_group_id"]').val();
    if (keyWord == null && status == null && cardType == null && cardGroup == null) {
        refresh()
    } else {
        $.ajax({
            url: laroute.route('admin.service-card.filter'),
            method: "POST",
            data: {
                keyWord: keyWord,
                status: status,
                cardType: cardType,
                cardGroup: cardGroup,
            },
            success: function (data) {
                $('.list-service-card').empty();
                $('.list-service-card').append(data);
            }
        })
    }
}
