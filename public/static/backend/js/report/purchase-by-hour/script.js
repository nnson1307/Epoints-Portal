var reportPurchase = {
    _init: function () {
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
                startDate: moment().subtract(6, "days"),
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
                reportPurchase.loadChart();
            });

            reportPurchase.loadChart();
        });
    },

    loadChart: function () {
        $.ajax({
            url: laroute.route('report.purchase-by-hour.load-chart'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val()
            },
            success:function (res) {
                if (res.error == 0) {
                    chart(res.data, res.totalOrder, res.arrName);
                }
            }
        });
    }
}

function chart(data, totalOrder, arrName) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#container').highcharts({
            chart: {
                type: 'column',
                scrollablePlotArea: {
                    minWidth: 700,
                }
            },
            title: {
                text: json['Tổng số đơn hàng: '] + totalOrder
            },
            xAxis: {
                categories: arrName,
            },
            yAxis: {
                title: {
                    text: json['Phần trăm (%)']
                },
                min: 0
            },
            exporting: {enabled: false},
            tooltip: {
                pointFormat: '<b>{point.y:.2f} %</b>'
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        format: '{point.y:.2f}'
                    }
                }
            },
            legend: {
                enabled: false
            },
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 700
                    },
                    chartOptions: {
                        legend: {
                            enabled: false
                        }
                    }
                }]
            },
            series: [{
                showInLegend: false,
                name: 'Population',
                data: data,
                color: '#4fc4ca'
            }]
        });
    });
}