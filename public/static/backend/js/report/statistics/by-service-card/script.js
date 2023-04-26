var statisticServiceCard = {
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
            }).on('apply.daterangepicker', function (event) {
                statisticServiceCard.loadChart();
            });
            $('#service_card').select2().on('select2:select', function (event) {
                statisticServiceCard.loadChart();
            });
            statisticServiceCard.loadChart();
        });
    },

    loadChart: function () {
        var time = $('#time').val();
        var service_card = $('#service_card').val();
        $('#time_detail').val(time);
        $('#service_card_detail').val(service_card);

        //Load filter export tổng
        $('#export_time_total').val(time);
        $('#export_service_card_total').val(service_card);
        //Load filter export chi tiết
        $('#export_time_detail').val(time);
        $('#export_service_card_detail').val(service_card);
        $.ajax({
            url: laroute.route('admin.report-growth.service-card.filter'),
            method: "POST",
            data: {
                time: $('#time').val(),
                serviceCard: $('#service_card').val(),
            },
            dataType: "JSON",
            success: function (data) {
                chartGrowthServiceCard(data.dataChartColumn);
                chartCustomerGroup(data.dataChartCustomerGroup);
                chartServiceCardGroup(data.dataChartServiceCardGroup);
                chartBranch(data.dataChartBranch);

                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-growth.service-card.list-detail')
                });

                $('.btn-search').trigger('click');
            }
        });
    }
}

function chartGrowthServiceCard(dataValue) {
    google.charts.load('current', {'packages': ['bar']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        $.getJSON(laroute.route('translate'), function (json) {
            var options = {
                chart: {
                    title: json['Số lần sử dụng'],
                },
                titleTextStyle: {
                    color: "#454545",
                    fontSize: 13,
                    bold: true
                },
                annotations: {
                    boxStyle: {
                        stroke: '#888',
                        rx: 10,
                        ry: 10,
                        gradient: {
                            color1: '#fbf6a7',
                            color2: '#33b679',
                            x1: '0%', y1: '0%',
                            x2: '100%', y2: '100%',
                            useObjectBoundingBoxUnits: true
                        }
                    },
                    textStyle: {
                        fontName: 'Times-Roman',
                        fontSize: 12,
                        bold: true,
                        italic: true
                    }
                },
                colors: ['#4fc4cb'],
                bar: {groupWidth: "30%"},
                vAxis: {
                    minValue: 0,
                    format: '#',

                },
                hAxis: {
                    minValue: 0,
                    format: '#',
                },
                legend: {position: 'none'}

            };

            var chart = new google.charts.Bar(document.getElementById('container'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        });
    }
}

//Biểu đồ nhóm khách hàng.
function chartCustomerGroup(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        var options = {
            title: '',
            pieHole: 0.4,
            pieSliceTextStyle: {
                color: 'black',
            },
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8','#12801f', '#72b3b7', '#fec689', '#e3d500']
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-customer'));
        chart.draw(data, options);
    }
}

// Biểu đồ nhóm thẻ dịch vụ
function chartServiceCardGroup(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);

        var options = {
            title: '',
            pieHole: 0.4,
            pieSliceTextStyle: {
                color: 'black',
            },
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8','#12801f', '#72b3b7', '#fec689', '#e3d500']
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-service-category'));
        chart.draw(data, options);
    }
}

//Biểu đồ chi nhánh
function chartBranch(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        var options = {
            title: '',
            pieHole: 0.4,
            pieSliceTextStyle: {
                color: 'black',
            },
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#12801f', '#f8ba05', '#4fc4cb', '#f06eaa', '#72b3b7', '#fec689', '#e3d500', '#ebebeb', '#a3d9b8']
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-branch'));
        chart.draw(data, options);
    }
}
