$("#time-hidden").daterangepicker({
    startDate: moment().subtract(6, "days"),
    endDate: moment(),
    locale: {
        format: 'DD/MM/YYYY'
    }
});
$('#time').val($("#time-hidden").val());

Highcharts.setOptions({
    lang: {
        numericSymbols: null
    },
    colors: ['#4fc4cb']
});


//Biểu đồ.
function chart(list, value) {
    Highcharts.chart('container', {
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: list,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<tr><td style="color:#ffffff;padding:0"> </td>' +
            '<td style="padding:0"><b style="color:#000000;">{point.y}'+json['VNĐ']+'</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [{
            showInLegend: false,
            name: '',
            data: value
        }],
        exporting: {enabled: false}
    });
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
    $("#time").daterangepicker({
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
            "applyLabel": json["Đồng ý"],
            "cancelLabel": json["Thoát"],
            "customRangeLabel": json["Tùy chọn ngày"],
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
        },
        ranges: arrRange
    }).on('apply.daterangepicker', function (ev) {

    });
});

function index() {
    $.ajax({
        url: laroute.route('admin.report-revenue.service.index'),
        method: "POST",
        data: {time: $('#time').val()},
        dataType: "JSON",
        success: function (data) {
            $.getJSON(laroute.route('translate'), function (json) {
            $('#container').height(data['countList'] * 50);
            chart(data['list'], data['seriesData']);

            $('#totalOrderPaysuccess').text(formatNumber(data['totalChart']) + json[" VNĐ"]);
            });
        }
    });
}

//Biểu đồ tại trang chủ.
index();

// Chọn ngày.
$('#time').on('apply.daterangepicker', function (ev, picker) {
    var start = picker.startDate.format("DD/MM/YYYY");
    var end = picker.endDate.format("DD/MM/YYYY");
    $(this).val(start + " - " + end);
    filter();
});
$('#branch').select2().on('select2:select', function () {
    filter();
});
$('#number-service').select2().on('select2:select', function () {
    filter();
});

function filter() {
    mApp.block(".load_ajax", {
        overlayColor: "#000000",
        type: "loader",
        state: "success",
        message: json["Đang tải..."],
    });
    var time = $('#time').val();
    var branch = $('#branch').val();
    var numberService = $('#number-service').val();
    if (time == '' && branch == '' && numberService == '') {
        index()
    } else {
        $.ajax({
                url: laroute.route('admin.report-revenue.service.filter'),
                method: "POST",
                data: {
                    time: time,
                    branch: branch,
                    numberService: numberService
                },
                dataType: "JSON",
                success: function (data) {
                    mApp.unblock(".load_ajax");
                    $.getJSON(laroute.route('translate'), function (json) {
                    $('#container').height(data['countList'] * 50);
                    chart(data['list'], data['seriesData']);

                    $('#totalOrderPaysuccess').text(formatNumber(data['totalChart']) + json[" VNĐ"]);});
                }
            }
        );
    }
}
