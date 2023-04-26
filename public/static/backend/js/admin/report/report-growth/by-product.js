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
$('#product').select2().on('select2:select', function () {
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
    }).on('apply.daterangepicker', function (ev) {

    });
});
// Biểu đồ (miền) sản phẩm.
function chartGrowthProduct(dataValule) {
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(
            dataValule
        );
        $.getJSON(laroute.route('translate'), function (json) {  
        var options = {
            title: json['Số lượng'],
            titleTextStyle: {
                color: "#454545",
                fontSize: 13,
                bold: true
            },
            titlePosition: 'out',
            hAxis: {
                textStyle: {
                    color: "#454545",
                    fontSize: 13,
                    fontWeight: 300
                },
                showTextEvery: 1
            },
            vAxis: {
                minValue: 0,
                format: '0',
                min: 0
            },
            colors: ['#4fc4cb', '#0098d1', '#f26d7e', '#f8ba05'],
            legend: "none",
            height: "450px",
            width: "90%",
            chartArea: {
                height: "450px",
                width: "90%"
            },
            // annotations: {
            //     boxStyle: {
            //         // Color of the box outline.
            //         stroke: '#f00',
            //         // Thickness of the box outline.
            //         strokeWidth: 1,
            //         // x-radius of the corner curvature.
            //         rx: 10,
            //         // y-radius of the corner curvature.
            //         ry: 10,
            //         // Attributes for linear gradient fill.
            //         gradient: {
            //             // Start color for gradient.
            //             color1: '#f00',
            //             // Finish color for gradient.
            //             color2: '#f00',
            //             // Where on the boundary to start and
            //             // end the color1/color2 gradient,
            //             // relative to the upper left corner
            //             // of the boundary.
            //             x1: '0%', y1: '0%',
            //             x2: '100%', y2: '100%',
            //             // If true, the boundary for x1,
            //             // y1, x2, and y2 is the box. If
            //             // false, it's the entire chart.
            //             useObjectBoundingBoxUnits: true
            //         }
            //     }
            // }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('container'));
        chart.draw(data, options);
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
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-customer'));
        chart.draw(data, options);
    }
}


// Biểu đồ nhóm sản phẩm.
function chartGrowthProductCategory(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#3468be', '#e3d500', '#00a652', '#f06eaa', '#f06eaa', '#72b3b7', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-product-category'));
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
            colors: ['#3468be', '#e3d500', '#00a652', '#f06eaa', '#f06eaa', '#72b3b7', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-branch'));
        chart.draw(data, options);
    }
}


index()

function index() {
    $.ajax({
        url: laroute.route('admin.report-growth.product.index'),
        method: "POST",
        data: {
            time: $('#time').val()
        },
        dataType: "JSON",
        success: function (data) {
            $.getJSON(laroute.route('translate'), function (json) {
            chartGrowthProduct(data['dataAreaChartProduct']);

            //Biểu đồ khách hàng
            var dataCustomer = [
                ['Task', 'Hours per Day'],
                [json['Thành viên'], data['totalQuantity'] - data['quantityOddCustomer']],
                [json['Khách hàng vãng lai'], data['quantityOddCustomer']],

            ];
            chartGrowthCustomer(dataCustomer);

            //Biểu đồ nhóm sản phẩm.
            chartGrowthProductCategory(data['dataProductGroup']);

            //Biểu đồ chi nhánh
            chartGrowthBranch(data['dataBranchChart']);
        });
        }
    });
}

function filter() {
    var time = $('#time').val();
    var product = $('#product').val();

    if (time == '' && product == '') {
        index()
    } else {
        $.ajax({
            url: laroute.route('admin.report-growth.product.filter'),
            method: "POST",
            data: {
                time: time,
                product: product,
            },
            dataType: "JSON",
            success: function (data) {
                $.getJSON(laroute.route('translate'), function (json) {
                if (time != '' && product == '') {
                    //Biểu đồ theo sản phẩm.
                    chartGrowthProduct(data['dataAreaChartProduct']);

                    //Biểu đồ khách hàng.
                    var dataCustomer = [
                        ['Task', 'Hours per Day'],
                        [json['Thành viên'], data['totalQuantity'] - data['quantityOddCustomer']],
                        [json['Khách hàng vãng lai'], data['quantityOddCustomer']],

                    ];
                    chartGrowthCustomer(dataCustomer);
                    //Biểu đồ nhóm sản phẩm.
                    chartGrowthProductCategory(data['dataProductGroup']);

                    //Biểu đồ chi nhánh.
                    chartGrowthBranch(data['dataBranchChart']);

                } else if (time != '' && product != '') {
                    //Biểu đồ sản phẩm theo ngày đã chọn.
                    chartGrowthProduct(data['dataAreaChartProduct']);
                    //Biểu đồ khách hàng.
                    var dataCustomer2 = [
                        ['Task', 'Hours per Day'],
                        [json['Thành viên'], data['quantityAllCustomer'] - data['quantityOddCustomer']],
                        [json['Khách hàng vãng lai'], data['quantityOddCustomer']],

                    ];
                    chartGrowthCustomer(dataCustomer2);
                    //Biểu đồ nhóm sản phẩm
                    chartGrowthProductCategory(data['dataProductGroup']);
                    //Biểu đồ chi nhánh.
                    chartGrowthBranch(data['dataBranchChart']);
                }
            });
            }
        })
    }
}
//

