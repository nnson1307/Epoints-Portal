$("#time-hidden").daterangepicker({
    startDate: moment().subtract(6, "days"),
    endDate: moment(),
    locale: {
        format: 'DD/MM/YYYY'
    }
});
$('#time').val($("#time-hidden").val());

var monthOneYear = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];


$('#branch').select2().on('select2:select', function () {
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
function ChartGrowthByBranch(dataValue) {
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
            bar: {groupWidth: "20%"},
            vAxis: {
                minValue: 0,
                format: '0',
            },

        };

        var chart = new google.charts.Bar(document.getElementById('chart-growth-branch'));

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
            colors: ['#3468be', '#e3d500', '#00a652', '#f06eaa', '#f06eaa', '#72b3b7', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-service-category'));
        chart.draw(data, options);
    }
}

// Biểu đồ nhóm sản phẩm
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
            colors: ['#12801f', '#72b3b7', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-product-category'));
        chart.draw(data, options);
    }
}


function index() {
    $.ajax({
        url: laroute.route('admin.report-growth.branch.index'),
        method: "POST",
        data: {
            time: $('#time').val()
        },
        dataType: "JSON",
        success: function (data) {
            ChartGrowthByBranch(data['dataQuantity']);
            //Biểu đồ khách hàng.
            var dataCustomer = [
                ['Task', 'Hours per Day'],
                ['Thành viên', data['quantityAllCustomer'] - data['quantityOddCustomer']],
                ['Khách hàng vãng lai', data['quantityOddCustomer']],

            ];
            chartGrowthCustomer(dataCustomer);

            //Biểu đồ nhóm dịch vụ.
            chartGrowthServiceCategory(data['dataserviceCategorys']);

            //Biểu đồ nhóm sản phẩm.
            chartGrowthProductCategory(data['dataproductCategory']);

            //Biểu đồ nhóm thẻ dịch vụ.
            chartServiceCardGroup(data['dataServiceCardGroup']);

            //Biểu đồ tỉ lệ sử dụng voucher.
            var dataUserVoucher = [
                ['Task', 'Hours per Day'],
                ['Voucher', data['totalUseVoucher']],
                ['Tổng đơn hàng', data['totalOrder']],

            ];
            chartGrowthUseVoucher(dataUserVoucher);

            //Biểu đồ tỉ lệ sử dụng thẻ dịch vụ.
            var dataUserServiceCard = [
                ['Task', 'Hours per Day'],
                ['Thẻ dịch vụ', data['totalUseServiceCard']],
                ['Tổng đơn hàng', data['totalOrderDetail'] - data['totalUseServiceCard']],

            ];
            chartGrowthUseServiceCard(dataUserServiceCard);
        }
    });
}
if ($('#branch').val() != '') {
    filter();
} else {
    index();
}
function filter() {
    var time = $('#time').val();
    var branch = $('#branch').val();

    if (time == '' && branch == '') {
        index()
    } else {
        $.ajax({
            url: laroute.route('admin.report-growth.branch.filter'),
            method: "POST",
            data: {
                time: time,
                branch: branch,
            },
            dataType: "JSON",
            success: function (data) {
                ChartGrowthByBranch(data['dataQuantity']);
                //Biểu đồ khách hàng.
                var dataCustomer = [
                    ['Task', 'Hours per Day'],
                    ['Thành viên', data['quantityAllCustomer'] - data['quantityOddCustomer']],
                    ['Khách hàng vãng lai', data['quantityOddCustomer']],

                ];
                chartGrowthCustomer(dataCustomer);

                //Biểu đồ nhóm dịch vụ.
                chartGrowthServiceCategory(data['dataserviceCategorys']);

                chartGrowthProductCategory(data['dataproductCategory']);

                //Biểu đồ nhóm thẻ dịch vụ.
                chartServiceCardGroup(data['dataServiceCardGroup']);

                //Biểu đồ tỉ lệ sử dụng voucher.
                var dataUserVoucher = [
                    ['Task', 'Hours per Day'],
                    ['Voucher', data['totalUseVoucher']],
                    ['Tổng hóa đơn', data['totalOrder']],

                ];
                chartGrowthUseVoucher(dataUserVoucher);

                //Biểu đồ tỉ lệ sử dụng thẻ dịch vụ.
                var dataUserServiceCard = [
                    ['Task', 'Hours per Day'],
                    ['Thẻ dịch vụ', data['totalUseServiceCard']],
                    ['Tổng đơn hàng', data['totalOrderDetail'] - data['totalUseServiceCard']],

                ];
                chartGrowthUseServiceCard(dataUserServiceCard);
            }
        });
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
            legend: {position: 'bottom'},
            chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
            colors: ['#00a652', '#f06eaa', '#72b3b7', '#3468be', '#e3d500', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#a3d9b8'],
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-service-card-group'));
        chart.draw(data, options);
    }
}

//Biểu đồ tỉ lệ sử dụng voucher.
function chartGrowthUseVoucher(dataValue) {
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
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-use-voucher'));
        chart.draw(data, options);
    }
}

//Biểu đồ tỉ lệ sử dụng thẻ dịch vụ.
function chartGrowthUseServiceCard(dataValue) {
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
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-use-service-card'));
        chart.draw(data, options);
    }
}

//
