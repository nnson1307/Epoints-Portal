var index = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            arrRange[json["6 Tháng trước"]] = [moment().subtract(6, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#created_at").daterangepicker({
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
            }).on('apply.daterangepicker', function (event) {
                index.changeFilter();
            });

            $('#staff_id').select2({
                placeholder: json['Chọn nhân viên'],
                allowClear: true
            });

            $('#status').select2({
                placeholder: json['Chọn trạng thái'],
                allowClear: true
            });

            $('#history_type').select2({
                placeholder: json['Chọn loại cuộc gọi'],
                allowClear: true
            });

            index.changeFilter();
        });
    },
    changeFilter: function () {
        index.loadChart();
    },
    loadChart: function () {
        $.ajax({
            url: laroute.route('oncall.report-overview.load-chart'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                created_at: $('#created_at').val(),
                staff_id: $('#staff_id').val(),
                status: $('#status').val(),
                history_type: $('#history_type').val()
            },
            success: function (res) {
                $('#totalCall').text(res.total);
                $('#totalSuccess').text(res.success);
                $('#totalFail').text(res.fail);

                if (res.isColumn == 1) {
                    //Load biểu đồ cột
                    index.chartColumn(res.dataChart);
                } else {
                    //Load biểu đồ line
                    index.chartLine(res.dataChart);
                }

                $('.div_table_list_1').html(res.htmlList1);

                //Call ajax load list 1
                $('#div_list_1').PioTable({
                    baseUrl: laroute.route('oncall.report-overview.load-list-1')
                });
            }
        });
    },
    chartColumn: function (data) {
        Highcharts.chart('div_chart_1', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: data.categories
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: ( // theme
                            Highcharts.defaultOptions.title.style &&
                            Highcharts.defaultOptions.title.style.color
                        ) || 'gray'
                    }
                },
            },
            legend: {
                align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>' + data.textTotal + ' : {point.stackTotal}'
            },
            exporting: false,
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            if(this.y===0){
                                return null;
                            }
                            return this.y;
                        }
                    }
                },

            },
            series: data.series,
        });
    },
    chartLine: function (data) {
        Highcharts.chart('div_chart_1', {
            chart: {
                type: 'line'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: data.categories,
            },
            yAxis: {
                title: {
                    text: 'Số lượng cuộc gọi'
                },
                min: 0
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}' + '</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            exporting: false,
            series: data.series
        });
    }
};