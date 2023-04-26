$(document).ready(function () {
    Highcharts.setOptions({
        lang: {
            numericSymbols: null
        },
        colors: ['#4fc4cb']
    });
    $.getJSON(laroute.route('translate'), function (json) {
        var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $("#time").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // buttonClasses: "m-btn btn",
            // applyClass: "btn-primary",
            // cancelClass: "btn-danger",
            maxDate: moment().endOf("day"),
            startDate:moment().subtract(6, "days"),
            endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": json["Đồng ý"],
                "cancelLabel": json["Thoát"],
                "customRangeLabel": json['Tùy chọn ngày'],
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
            loadChart();
        });

        var monthOneYear = [json['Tháng 1'], json['Tháng 2'], json['Tháng 3'], json['Tháng 4'], json['Tháng 5'], json['Tháng 6'],
            json['Tháng 7'], json['Tháng 8'], json['Tháng 9'], json['Tháng 10'], json['Tháng 11'], json['Tháng 12']];

        $('#number-staff').select2().on('select2:select', function () {
            loadChart();
        });

        //Biểu đồ tại trang chủ.
        loadChart();
    });
});

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function loadChart() {
    var time = $('#time').val();
    var numberStaff = $('#number-staff').val();
    mApp.block(".load_ajax", {
        overlayColor: "#000000",
        type: "loader",
        state: "success",
        message: "Đang tải...",
    });
    $.ajax({
        url: laroute.route('admin.report-staff-commission.load-chart'),
        method: "POST",
        data: {
            time: time,
            numberStaff: numberStaff,
        },
        dataType: "JSON",
        success: function (res) {
            mApp.unblock(".load_ajax");
            $('#container').height(res['countList'] * 50);
            chart(res['list'], res['seriesData']);
            $.getJSON(laroute.route('translate'), function (json) {
                $('#totalMoney').text(formatNumber(res['totalMoney'].toFixed(decimal_number)) + json["VNĐ"]);
            });
        }
    });
}

//Biểu đồ.
function chart(list, value) {
    $.getJSON(laroute.route('translate'), function (json) {
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
                    '<td style="padding:0"><b style="color:#000000;">{point.y}'+ json['VNĐ']+'</b></td></tr>',
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
    });

}
