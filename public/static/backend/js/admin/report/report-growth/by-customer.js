$("#time-hidden").daterangepicker({
    startDate: moment().subtract(6, "days"),
    endDate: moment(),
    locale: {
        format: 'DD/MM/YYYY'
    }
});
$('#time').val($("#time-hidden").val());

$('#branch').select2().on('select2:select', function () {
    filter();
});
$.getJSON(laroute.route('translate'), function (json) {
    var monthOneYear = [json['Tháng 1'], json['Tháng 2'], json['Tháng 3'], json['Tháng 4'], json['Tháng 5'], json['Tháng 6'],
    json['Tháng 7'], json['Tháng 8'], json['Tháng 9'], json['Tháng 10'], json['Tháng 11'], json['Tháng 12']];
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

//Trang chủ
function index() {
    $.ajax({
        url: laroute.route('admin.report-growth.customer.index'),
        method: "POST",
        data: {
            time: $('#time').val()
        },
        dataType: "JSON",
        success: function (data) {
            chartGrowthCustomer(data.dataValue);
            chartCustomerGroup(data.dataCustomergroup);

            chartCustomerSource(data.dataCustomeSource);

            //Biểu đồ giới tính
            chartGender(data.dataCustomeGender);
        }
    });
}

index();

function chartGrowthCustomer(dataValule) {
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(
            dataValule
        );
        $.getJSON(laroute.route('translate'), function (json) {
        var options = {
            title: json['Số khách hàng'],
            titleTextStyle: {
                color: "#454545",
                fontSize: 13,
                bold: true
            },
            legend: {
                position: 'right',
                textStyle: {
                    color: '#454545',
                    fontSize: 13,
                    bold:true
                }
            },
            titlePosition: 'out',
            hAxis: {title: '', titleTextStyle: {color: '#333',fontSize:12}},
            vAxis: {
                minValue: 0,
                format: '0',
                min: 0
            },
            colors: ['#4fc4cb', '#0098d1', '#f26d7e', '#f8ba05'],
            height: "450px",
            width: "80%",
            chartArea: {
                height: "450px",
                width: "80%",
                left: 30
            }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart-growth-customer'));
        chart.draw(data, options);
    });
    }
}

//Tất cả chi nhánh.
function chartAllBranch(listBranch, data) {

}

// Biểu đồ theo nhóm khách hàng.
function chartCustomerGroup(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);

        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea:{left:0,top:7,right:0,width:'50%',height:'75%'},
            enableInteractivity: false,
            colors: ['#12801f', '#a3d9b8', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#72b3b7'],

        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-customer'));
        chart.draw(data, options);
    }
}


// Biểu đồ theo giới tính
function chartGender(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);

        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            colors: ['#3468be', '#f06eaa', '#e3d500'],
            chartArea:{left:0,top:7,right:0,width:'50%',height:'75%'},
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-customer-gender'));
        chart.draw(data, options);
    }
}

//Biểu đồ theo nguồn khách hàng.
function chartCustomerSource(dataValue) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);

        var options = {
            title: '',
            pieHole: 0.4,
            legend: {position: 'bottom'},
            chartArea:{left:0,top:7,right:0,width:'50%',height:'75%'},
            enableInteractivity: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart-customer-source'));
        chart.draw(data, options);
    }
}

function filter() { 
    var time = $('#time').val();
    var branch = $('#branch').val();

    if (time == '' && branch == '') {
        index()
    } else {
        $.ajax({
            url: laroute.route('admin.report-growth.customer.filter'),
            method: "POST",
            data: {
                time: time,
                branch: branch,
            },
            dataType: "JSON",
            success: function (data) {
                if (time == '' && branch != '') {
                    chartGrowthCustomer(monthOneYear, data['data1'], data['data2'], data['data3'], data['data4']);

                    //Biểu đồ theo nhóm khách hàng.
                    var arrayListCustomerGroup = new Array();
                    $.each(data['listCustomerGroup'], function (key, value) {
                        var array22 = {};
                        array22.name = value.name;
                        array22.y = value.y;
                        arrayListCustomerGroup.push(array22);
                    });
                    chartCustomerGroup(arrayListCustomerGroup);
                    //
                    //Biểu đồ giới tính
                    var arrayListCustomerGender = new Array();
                    $.each(data['listGender'], function (key, value) {
                        $.getJSON(laroute.route('translate'), function (json) {
                        var array4 = {};
                        if (value.name == 'male') {
                            array4.name = json["Nam"];
                        } else if (value.name == 'female') {
                            array4.name = json["Nữ"];
                        } else {
                            array4.name = json["Khác"];
                        }
                        array4.y = value.y;
                        arrayListCustomerGender.push(array4);
                    });
                    });
                    chartGender(arrayListCustomerGender);

                    //Biểu đồ nguồn khách hàng
                    var arrayListCustomerSource = new Array();
                    $.each(data['listCustomerSource'], function (key, value) {
                        var array33 = {};
                        array33.name = value.name;
                        array33.y = value.y;
                        arrayListCustomerSource.push(array33);
                    });
                    chartCustomerSource(arrayListCustomerSource);
                } else if (time != '' && branch == '') {
                    chartGrowthCustomer(data.dataValue);
                    //Biểu đồ theo nhóm khách hàng.
                    chartCustomerGroup(data.dataCustomergroup);

                    //Biểu đồ giới tính
                    chartGender(data.dataCustomeGender);

                    //Biểu đồ nguồn khách hàng
                    chartCustomerSource(data.dataCustomeSource);

                } else if (time != '' && branch != '') {
                    chartGrowthCustomer(data.dataValue);
                    //Biểu đồ theo nhóm khách hàng.
                    chartCustomerGroup(data.dataCustomergroup);

                    //Biểu đồ giới tính
                    chartGender(data.dataCustomeGender);

                    //Biểu đồ nguồn khách hàng
                    chartCustomerSource(data.dataCustomeSource);
                }
            }
        });
    }
}
////



