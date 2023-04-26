$("#time-hidden").daterangepicker({
    startDate: moment().subtract(6, "days"),
    endDate: moment(),
    locale: {
        format: 'DD/MM/YYYY'
    }
});
$('#time').val($("#time-hidden").val());
$.getJSON(laroute.route('translate'), function (json) {
var monthOneYear = [json['Tháng 1'], json['Tháng 2'], json['Tháng 3'], json['Tháng 4'], json['Tháng 5'], json['Tháng 6'],
json['Tháng 7'], json['Tháng 8'], json['Tháng 9'], json['Tháng 10'], json['Tháng 11'], json['Tháng 12']];
});
// Biểu đồ (cột) dịch vụ.
function chartGrowthService(dataValue) {
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
                minValue: 1,
                format: '0',
            },
            legend: {position: 'none'}

        };

        var chart = new google.charts.Bar(document.getElementById('container'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    });
    }
}

//Biểu đồ khách hàng.
function chartGrowthCustomer(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);

        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#4fc4cb', '#f8ba05'],
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-customer'));
        chart.draw(data, options);
    }
}


// Biểu đồ nhóm dịch vụ
function chartGrowthServiceCategory(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);

        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#4fc4cb', '#f8ba05','#00a652', '#f06eaa', '#f06eaa', '#72b3b7', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-service-category'));
        chart.draw(data, options);
    }
}

//Biểu đồ chi nhánh
function chartGrowthBranch(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);

        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#4fc4cb', '#f8ba05','#00a652', '#f06eaa', '#f06eaa', '#72b3b7', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-branch'));
        chart.draw(data, options);
    }
}

index();

function index() {
    $.ajax({
        url: laroute.route('admin.report-growth.service.index'),
        method: "POST",
        data: {time: $('#time').val()},
        dataType: "JSON",
        success: function (data) {
            $.getJSON(laroute.route('translate'), function (json) {
            chartGrowthService(data['dataQuantity']);

            //Biểu đồ khách hàng
            var dataCustomer = [
                ['Task', 'Hours per Day'],
                [json['Thành viên'], data['totalQuantity'] - data['quantityOddCustomer']],
                [json['Khách hàng vãng lai'], data['quantityOddCustomer']],

            ];
            chartGrowthCustomer(dataCustomer);

            //Biểu đồ nhóm dịch vụ.
            chartGrowthServiceCategory(data['dataServiceGroup']);

            //Biểu đồ chi nhánh.
            chartGrowthBranch(data['dataBranchChart']);
        });
        }
    });
}

$('#service').select2().on('select2:select', function () {
    filter();
});
// Chọn ngày.
$('#time').on('apply.daterangepicker', function (ev, picker) {
    var start = picker.startDate.format("DD/MM/YYYY");
    var end = picker.endDate.format("DD/MM/YYYY");
    $(this).val(start + " - " + end);
    filter();
});
$.getJSON(laroute.route('translate'), function (json) {

    //
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

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
        }
    ).on('apply.daterangepicker', function (ev) {

});
});
function filter() {
    var time = $('#time').val();
    var service = $('#service').val();

    if (time == '' && service == '') {
        index()
    } else {
        $.ajax({
            url: laroute.route('admin.report-growth.service.filter'),
            method: "POST",
            data: {
                time: time,
                service: service,
            },
            dataType: "JSON",
            success: function (data) {
                $.getJSON(laroute.route('translate'), function (json) {
                // $.getJSON(laroute.route('translate'), function (json) {
                if (time != '' && service == '') {
                    //Biểu đồ theo dịch vụ.
                    chartGrowthService(data['dataQuantity']);
                    //Biểu đồ khách hàng
                    var dataCustomer = [
                        ['Task', 'Hours per Day'],
                        [json['Thành viên'], data['totalQuantity'] - data['quantityOddCustomer']],
                        [json['Khách hàng vãng lai'], data['quantityOddCustomer']],

                    ];
                    chartGrowthCustomer(dataCustomer);

                    //Biểu đồ nhóm dịch vụ.
                    chartGrowthServiceCategory(data['dataServiceGroup']);

                    //Biểu đồ chi nhánh.
                    chartGrowthBranch(data['dataBranchChart']);
                } else if (time != '' && service != '') {
                    //Biểu đồ dịch vụ theo ngày đã chọn.
                    chartGrowthService(data['dataQuantity']);

                    //Biểu đồ khách hàng
                    var dataCustomer2 = [
                        ['Task', 'Hours per Day'],
                        [json['Thành viên'], data['quantityAllCustomer'] - data['quantityOddCustomer']],
                        [json['Khách hàng vãng lai'], data['quantityOddCustomer']],

                    ];
                    chartGrowthCustomer(dataCustomer2);

                    //Biểu đồ nhóm dịch vụ.
                    chartGrowthServiceCategory(data['dataServiceGroup']);

                    //Biểu đồ chi nhánh.
                    chartGrowthBranch(data['dataBranchChart']);
                }
            });
            // });
            }
        });
    }
}
//

