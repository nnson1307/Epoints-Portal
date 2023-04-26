$('#autoassess').PioTable({
    baseUrl: laroute.route('fnb.assess.list')
});

var assess = {
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),
    _init : function (){
        $('select').select2();

        var arrRange = {};
        arrRange[assess.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[assess.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[assess.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[assess.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[assess.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[assess.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".daterange_picker").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // maxDate: moment().endOf("day"),
            // startDate:moment().subtract(6, "days"),
            // endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": assess.jsonLang["Đồng ý"],
                "cancelLabel": assess.jsonLang["Thoát"],
                "customRangeLabel": assess.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    assess.jsonLang["CN"],
                    assess.jsonLang["T2"],
                    assess.jsonLang["T3"],
                    assess.jsonLang["T4"],
                    assess.jsonLang["T5"],
                    assess.jsonLang["T6"],
                    assess.jsonLang["T7"]
                ],
                "monthNames": [
                    assess.jsonLang["Tháng 1 năm"],
                    assess.jsonLang["Tháng 2 năm"],
                    assess.jsonLang["Tháng 3 năm"],
                    assess.jsonLang["Tháng 4 năm"],
                    assess.jsonLang["Tháng 5 năm"],
                    assess.jsonLang["Tháng 6 năm"],
                    assess.jsonLang["Tháng 7 năm"],
                    assess.jsonLang["Tháng 8 năm"],
                    assess.jsonLang["Tháng 9 năm"],
                    assess.jsonLang["Tháng 10 năm"],
                    assess.jsonLang["Tháng 11 năm"],
                    assess.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (ev) {

        });

        $(".daterange_picker").val('');

    },

}