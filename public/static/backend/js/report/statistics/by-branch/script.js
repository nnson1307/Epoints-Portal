var statisticBranch = {
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
                statisticBranch.loadChart();
            });
            $('#branch').select2().on('select2:select', function (event) {
                statisticBranch.loadChart();
            });
            statisticBranch.loadChart();
        });
    },

    loadChart: function () {
        var time = $('#time').val();
        var branch = $('#branch').val();
        $('#time_detail').val(time);
        $('#branch_detail').val(branch);

        //Load filter export tổng
        $('#export_time_total').val(time);
        $('#export_branch_total').val(branch);
        //Load filter export chi tiết
        $('#export_time_detail').val(time);
        $('#export_branch_detail').val(branch);
        $.ajax({
            url: laroute.route('admin.report-growth.branch.filter'),
            method: "POST",
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
            },
            dataType: "JSON",
            success: function (data) {
                console.log(data.dataChartColumn);
                chartGrowthByBranch(data.dataChartColumn);
                chartCustomerGroup(data.dataChartCustomerGroup);
                chartServiceCategory(data.dataChartServiceCategory);
                chartProductCategory(data.dataChartProductCategory);
                chartServiceCardGroup(data.dataChartServiceCardGroup);
                chartServiceCardUsage(data.dataChartServiceCardUsage);
                chartVoucherUsage(data.dataChartVoucherUsage);

                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-growth.branch.list-detail')
                });

                $('.btn-search').trigger('click');
            }
        });
    }
}

// START CHART
// Biểu đồ cột (dịch vụ, sản phẩm, voucher, thẻ dịch vụ) theo chi nhánh
function chartGrowthByBranch(dataValue) {
    google.charts.load('current', {'packages': ['bar']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        $.getJSON(laroute.route('translate'), function (json) {
            var options = {
                legend: {
                    position: 'top',
                    textStyle: {
                        color: '#454545',
                        fontSize: 13,
                        bold: true
                    }
                },
                chart: {
                    title: json['Số lượng'],
                },
                titleTextStyle: {
                    color: "#454545",
                    fontSize: 13,
                    bold: true
                },
                annotations: {
                    boxStyle: {
                        stroke: '#888',
                        strokeWidth: 1,
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
                colors: ['#f8ba05', '#f26d7e', '#4fc4cb', '#0404B4'],
                seriesType: 'bars',
                vAxis: {
                    minValue: 0,
                    format: '#',

                },
            };

            var chart = new google.charts.Bar(document.getElementById('chart-growth-branch'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        });
    }
}

// Biểu đồ nhóm khách hàng
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
            // colors: ['#4fc4cb', '#f8ba05'],
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-customer'));
        chart.draw(data, options);
    }
}

// Biểu đồ nhóm dịch vụ
function chartServiceCategory(dataValue) {
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
            // colors: ['#3468be', '#e3d500', '#00a652', '#f06eaa', '#f06eaa', '#72b3b7', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-service-category'));
        chart.draw(data, options);
    }
}

// Biểu đồ nhóm sản phẩm
function chartProductCategory(dataValue) {
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
            colors: ['#12801f', '#72b3b7', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-product-category'));
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
            colors: ['#00a652', '#f06eaa', '#72b3b7', '#3468be', '#e3d500', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-service-card-group'));
        chart.draw(data, options);
    }
}

// Tỉ lệ sử dụng thẻ dịch vụ
function chartServiceCardUsage(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#7401DF', '#74DF00'],
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-use-service-card'));
        chart.draw(data, options);
    }
}

// Tỉ lệ sử dụng voucher
function chartVoucherUsage(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#4fc4cb', '#F78181'],
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-use-voucher'));
        chart.draw(data, options);
    }
}
// END CHART