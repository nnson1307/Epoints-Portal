var reportCustomer = {
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
                autoApply: true,
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
                reportCustomer.loadChart();
            });
            reportCustomer.loadChart();

            $('#type').select2().on('select2:select', function (event) {
                reportCustomer.loadChart();
            });
            $('#product_category').select2().on('select2:select', function (event) {
                reportCustomer.loadChart();
            });
        });
    },

    loadChart: function () {
        $.ajax({
            url: laroute.route('report.customer-by-view-purchase.load-chart'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                product_category: $('#product_category').val(),
                type: $('#type').val(),
                time: $('#time').val()
            },
            success:function (res) {
                if (res.error == 0) {
                    chart(res.dataName, res.dataTotal);
                }
            }
        });
    },
}

function chart(dataName, dataTotal) {
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
                text: ''
            },
            xAxis: {
                categories: dataName,
                // labels: {
                //     rotation: -45,
                //     style: {
                //         fontSize: '13px',
                //         fontFamily: 'Verdana, sans-serif'
                //     }
                // }
            },
            yAxis: {
                title: {
                    text: json['Số lượng sản phẩm']
                },
                min: 0
            },
            exporting: {enabled: false},
            tooltip: {
                headerFormat: '',
                pointFormat: '<tr><td style="color:#ffffff;padding:0"></td>' +
                    '<td style="padding:0"><b style="color:#000000;">{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.0f}'
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
                data: dataTotal,
                color: '#4fc4ca'
            }]
        });
    });
}