var ServiceDetail = {
    init: function () {

        var table = $('#autotable').PioTable({
            baseUrl: laroute.route('admin.service-card.detail-list')
        });

        $("#actived_date").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        $("#created_at").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        // $('#created_at').on('apply.daterangepicker', function(ev, picker) {
        //     var start = picker.startDate.format("DD/MM/YYYY");
        //     var end = picker.endDate.format("DD/MM/YYYY");
        //
        //     $('#created_at').val(start+ " - "+end);
        // });
        //
        // $('#actived_date').on('apply.daterangepicker', function(ev, picker) {
        //     var start = picker.startDate.format("DD/MM/YYYY");
        //     var end = picker.endDate.format("DD/MM/YYYY");
        //
        //     $('#actived_date').val(start+ " - "+end);
        // });
    }
};

ServiceDetail.init();

//Trang đầu hoặc trang cuối của tất cả thẻ dịch vụ
function firstAndLastPage(o) {
    console.log(o)
    $.ajax({
        url: laroute.route('admin.service-card.paging-detail-all-card'),
        method: "POST",
        data: {
            page: o,
            idCard: $('#idCard').val()
        },
        success: function (data) {
            $('.list-service-card').empty();
            $('.list-service-card').append(data);
        }
    });
}

//phân trang cho tất cả thẻ dịch vụ.
function pageClick(o) {
    $.ajax({
        url: laroute.route('admin.service-card.paging-detail-all-card'),
        method: "POST",
        data: {
            page: $(o).text(),
            idCard: $('#idCard').val()
        },
        success: function (data) {
            $('.list-service-card').empty();
            $('.list-service-card').append(data);
        }
    });
}

var CardUsed = {
    firstAndLastPage: function (o) {
        $.ajax({
            url: laroute.route('admin.service-card.paging-detail-card-use'),
            method: "POST",
            data: {
                page: o,
                idCard: $('#idCard').val()
            },
            success: function (data) {
                $('#card-used').empty();
                $('#card-used').append(data);
            }
        });
    },
    pageClick: function (o) {
        console.log($(o).text());
        $.ajax({
            url: laroute.route('admin.service-card.paging-detail-card-use'),
            method: "POST",
            data: {
                page: $(o).text(),
                idCard: $('#idCard').val()
            },
            success: function (data) {
                $('#card-used').empty();
                $('#card-used').append(data);
            }
        });
    }
};