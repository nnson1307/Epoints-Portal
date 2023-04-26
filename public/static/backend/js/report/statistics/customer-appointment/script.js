var statisticCusAppointment = {
    _init : function () {
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
                statisticCusAppointment.loadChart();
            });
            $('#branch').select2().on('select2:select', function (event) {
                statisticCusAppointment.loadChart();
            });
            statisticCusAppointment.loadChart();
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
            url: laroute.route('admin.report-customer-appointment.load-index'),
            dataType: 'JSON',
            method: 'post',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val()
            },
            success: function (res) {
                chartStatistic(res.dataChartArea);
                chartSourceAppointment(res.dataAppointmentSource);
                chartGender(res.dataGender);
                chartCustomerGroup(res.dataCustomerGroup);

                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-customer-appointment.list-detail')
                });

                $('.btn-search').trigger('click');
            }
        });
    }
}

// START CHART
// Biểu đồ cột lịch hẹn (update 02/02/2021 từ biểu đồ miền)
function chartStatistic(dataValue) {
    google.charts.load('current', {'packages': ['bar']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        $.getJSON(laroute.route('translate'), function (json) {
            var data = google.visualization.arrayToDataTable(dataValue);
            var options = {
                chart: {
                    title: json['Số lịch hẹn'],
                },
                titleTextStyle: {
                    color: "#454545",
                    fontSize: 13,
                    bold: true
                },
                // colors: ['#005e20', '#4fc4cb', '#f26d7e', '#C9E853', '#50D3A1', '#FF0000'],
                bar: {groupWidth: "20%"},
                legend: {
                    position: 'top',
                    textStyle: {
                        color: '#454545',
                        fontSize: 13,
                        bold: true
                    }
                },
            };
            var chart = new google.charts.Bar(document.getElementById('container'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        });
    }
}
// Biểu đồ tròn: nguồn lịch hẹn
function chartSourceAppointment(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#4fc4cb', '#e3d500', '#72b3b7', '#12801f', '#a3d9b8', '#f06eaa', '#f8ba05', '#fec689'],
        };
        var chart = new google.visualization.PieChart(document.getElementById('source-appointment'));
        chart.draw(data, options);
    }
}
// Biểu đồ tròn: giới tính
function chartGender(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            colors: ['#3468be', '#e3d500', '#f06eaa'],
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'}
        };
        var chart = new google.visualization.PieChart(document.getElementById('gender'));
        chart.draw(data, options);
    }
}
// Biểu đồ tròn: nhóm khách hàng (update ngày 02/02/2021, ban đầu là nguồn khách hàng)
function chartCustomerGroup(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            // colors: ['#12801f', '#a3d9b8', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#72b3b7'],
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'}
        };
        var chart = new google.visualization.PieChart(document.getElementById('customer-source'));
        chart.draw(data, options);
    }
}
// END CHART