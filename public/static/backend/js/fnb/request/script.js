$('#autotable').PioTable({
    baseUrl: laroute.route('fnb.request.list')
});

var request = {
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),
    _init : function (){
        $('select').select2();

        var arrRange = {};
        arrRange[request.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[request.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[request.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[request.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[request.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[request.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".daterange_picker").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // maxDate: moment().endOf("day"),
            // startDate:moment().subtract(6, "days"),
            // endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": request.jsonLang["Đồng ý"],
                "cancelLabel": request.jsonLang["Thoát"],
                "customRangeLabel": request.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    request.jsonLang["CN"],
                    request.jsonLang["T2"],
                    request.jsonLang["T3"],
                    request.jsonLang["T4"],
                    request.jsonLang["T5"],
                    request.jsonLang["T6"],
                    request.jsonLang["T7"]
                ],
                "monthNames": [
                    request.jsonLang["Tháng 1 năm"],
                    request.jsonLang["Tháng 2 năm"],
                    request.jsonLang["Tháng 3 năm"],
                    request.jsonLang["Tháng 4 năm"],
                    request.jsonLang["Tháng 5 năm"],
                    request.jsonLang["Tháng 6 năm"],
                    request.jsonLang["Tháng 7 năm"],
                    request.jsonLang["Tháng 8 năm"],
                    request.jsonLang["Tháng 9 năm"],
                    request.jsonLang["Tháng 10 năm"],
                    request.jsonLang["Tháng 11 năm"],
                    request.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (ev) {

        });

        $(".daterange_picker").val('');

    },

}