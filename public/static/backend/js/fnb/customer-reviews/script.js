$('#autotable').PioTable({
    baseUrl: laroute.route('fnb.customer-review.list')
});

var customerReviews = {
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),
    _init : function (){
        $('select').select2();

        var arrRange = {};
        arrRange[customerReviews.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[customerReviews.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[customerReviews.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[customerReviews.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[customerReviews.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[customerReviews.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".daterange_picker").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // maxDate: moment().endOf("day"),
            // startDate:moment().subtract(6, "days"),
            // endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": customerReviews.jsonLang["Đồng ý"],
                "cancelLabel": customerReviews.jsonLang["Thoát"],
                "customRangeLabel": customerReviews.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    customerReviews.jsonLang["CN"],
                    customerReviews.jsonLang["T2"],
                    customerReviews.jsonLang["T3"],
                    customerReviews.jsonLang["T4"],
                    customerReviews.jsonLang["T5"],
                    customerReviews.jsonLang["T6"],
                    customerReviews.jsonLang["T7"]
                ],
                "monthNames": [
                    customerReviews.jsonLang["Tháng 1 năm"],
                    customerReviews.jsonLang["Tháng 2 năm"],
                    customerReviews.jsonLang["Tháng 3 năm"],
                    customerReviews.jsonLang["Tháng 4 năm"],
                    customerReviews.jsonLang["Tháng 5 năm"],
                    customerReviews.jsonLang["Tháng 6 năm"],
                    customerReviews.jsonLang["Tháng 7 năm"],
                    customerReviews.jsonLang["Tháng 8 năm"],
                    customerReviews.jsonLang["Tháng 9 năm"],
                    customerReviews.jsonLang["Tháng 10 năm"],
                    customerReviews.jsonLang["Tháng 11 năm"],
                    customerReviews.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (ev) {

        });

        $(".daterange_picker").val('');

    },

}