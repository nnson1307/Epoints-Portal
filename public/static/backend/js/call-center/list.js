var listCallCenter = {
    jsontranslate : JSON.parse(localStorage.getItem('tranlate')),

}

$('#autotable').PioTable({
    baseUrl: laroute.route('call-center.list')
});
var arrRange = {};
    arrRange[listCallCenter.jsontranslate['Hôm nay']] = [moment(), moment()],
        arrRange[listCallCenter.jsontranslate['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
        arrRange[listCallCenter.jsontranslate["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
        arrRange[listCallCenter.jsontranslate["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
        arrRange[listCallCenter.jsontranslate["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
        arrRange[listCallCenter.jsontranslate["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
    $(".daterange-picker").daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        buttonClasses: "m-btn btn",
        applyClass: "btn-primary",
        cancelClass: "btn-danger",
        maxDate: moment().endOf("day"),
        startDate: moment().startOf("day"),
        endDate: moment().add(1, 'days'),
        locale: {
            format: 'DD/MM/YYYY',
            "applyLabel": listCallCenter.jsontranslate["Đồng ý"],
            "cancelLabel": listCallCenter.jsontranslate["Thoát"],
            "customRangeLabel": listCallCenter.jsontranslate["Tùy chọn ngày"],
            daysOfWeek: [
                listCallCenter.jsontranslate["CN"],
                listCallCenter.jsontranslate["T2"],
                listCallCenter.jsontranslate["T3"],
                listCallCenter.jsontranslate["T4"],
                listCallCenter.jsontranslate["T5"],
                listCallCenter.jsontranslate["T6"],
                listCallCenter.jsontranslate["T7"]
            ],
            "monthNames": [
                listCallCenter.jsontranslate["Tháng 1 năm"],
                listCallCenter.jsontranslate["Tháng 2 năm"],
                listCallCenter.jsontranslate["Tháng 3 năm"],
                listCallCenter.jsontranslate["Tháng 4 năm"],
                listCallCenter.jsontranslate["Tháng 5 năm"],
                listCallCenter.jsontranslate["Tháng 6 năm"],
                listCallCenter.jsontranslate["Tháng 7 năm"],
                listCallCenter.jsontranslate["Tháng 8 năm"],
                listCallCenter.jsontranslate["Tháng 9 năm"],
                listCallCenter.jsontranslate["Tháng 10 năm"],
                listCallCenter.jsontranslate["Tháng 11 năm"],
                listCallCenter.jsontranslate["Tháng 12 năm"]
            ],
            "firstDay": 1
        },
        ranges: arrRange
    }).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
    });