$("#time-hidden").daterangepicker({
    startDate: moment().subtract(6, "days"),
    endDate: moment(),
    locale: {
        format: 'DD/MM/YYYY'
    }
});
$('#time').val($("#time-hidden").val());

Highcharts.setOptions({
    lang: {
        numericSymbols: null
    },
    colors: ['#4fc4cb']
});

//Biểu đồ.
function chart(xxx, data) {
    $.getJSON(laroute.route('translate'), function (json) {
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
                categories: xxx
            },
            yAxis: {
                title: {
                    text: json['Số tiền (VNĐ)']
                },
                min: 0
            },
            exporting: {enabled: false},
            tooltip: {
                headerFormat: '',
                pointFormat: '<tr><td style="color:#ffffff;padding:0"> </td>' +
                '<td style="padding:0"><b style="color:#000000;">{point.y}'+ json['VNĐ']+'</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                bar: {
                    dataLabels: {
                        enabled: true
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
                name: '',
                data: data
            }]
        });
    });
}

function chartPaymentMethod(data){
    $('#chart-payment-method').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Báo cáo doanh thu chi nhánh theo phương thức thanh toán'
        },
        accessibility: {
            announceNewData: {
                enabled: true
            },
            point: {
                valueSuffix: '%'
            }
        },

        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.2f}%'
                }
            },
            pie: {
                showInLegend: true
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.sum_type}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },
        series: [
            {
                name: "Browsers",
                colorByPoint: true,
                data: data
            }
        ]
    });
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
    // Chọn ngày.
    $("#time").daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        // buttonClasses: "m-btn btn",
        // applyClass: "btn-primary",
        // cancelClass: "btn-danger",

        maxDate: moment().endOf("day"),
        startDate: moment().startOf("day"),
        endDate: moment().add(1, 'days'),
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY',
            // "applyLabel": "Đồng ý",
            // "cancelLabel": "Thoát",
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
function index() {
    mApp.block(".load_ajax", {
        overlayColor: "#000000",
        type: "loader",
        state: "success",
        message: json["Đang tải..."],
    });
    $.ajax({
        url: laroute.route('admin.report-revenue.branch.index'),
        method: "POST",
        data: {time: $('#time').val()},
        dataType: "JSON",
        success: function (data) {
            $.getJSON(laroute.route('translate'), function (json) {
            mApp.unblock(".load_ajax");
            chart(data['list'], data['seriesData']);
            chartPaymentMethod(data['totalMoneyByReceiptType']);
            $('#totalOrder').text(formatNumber(data['totalChart']['totalOrder'].toFixed(decimal_number)));
            $('#totalMoney').html(formatNumber(data['totalChart']['totalMoney'].toFixed(decimal_number)) + json["VNĐ"]);
            $('#totalOrderPaysuccess').text(formatNumber(data['totalChart']['totalOrderPaysuccess'].toFixed(decimal_number)));
            $('#totalMoneyOrderPaysuccess').text(formatNumber(data['totalChart']['totalMoneyOrderPaysuccess'].toFixed(decimal_number)) + json["VNĐ"]);
            $('#totalOrderNew').text(formatNumber(data['totalChart']['totalOrderNew'].toFixed(decimal_number)));
            $('#totalMoneyOrderNew').text(formatNumber(data['totalChart']['totalMoneyOrderNew'].toFixed(decimal_number)) + json["VNĐ"]);
            $('#totalOrderPayFail').text(formatNumber(data['totalChart']['totalOrderPayFail'].toFixed(decimal_number)));
            $('#totalMoneyOrderPayFail').text(formatNumber(data['totalChart']['totalMoneyOrderPayFail'].toFixed(decimal_number)) + json["VNĐ"]);
        });}
    });
}

if ($('#branch').val() != "") {
    filter();
} else {
    index();
}

if ($('#customer_group').val() != "") {
    filter();
} else {
    index();
}

// Chọn ngày.
$('#time').on('apply.daterangepicker', function (ev, picker) {
    var start = picker.startDate.format("DD/MM/YYYY");
    var end = picker.endDate.format("DD/MM/YYYY");
    $(this).val(start + " - " + end);
    filter();
});
$('#branch').select2().on('select2:select', function () {
    if ($(this).val() != '') {
        filter();
    } else {
        index();
    }

});
$('#customer_group').select2().on('select2:select', function () {
    if ($(this).val() != '') {
        filter();
    } else {
        index();
    }

});
var monthOneYear = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];

function filter() {
    mApp.block(".load_ajax", {
        overlayColor: "#000000",
        type: "loader",
        state: "success",
        message: json["Đang tải..."],
    });
    var time = $('#time').val();
    var branch = $('#branch').val();
    var customer_group = $('#customer_group').val();
    $.ajax({
        url: laroute.route('admin.report-revenue.branch.filter'),
        method: "POST",
        data: {
            time: time,
            branch: branch,
            customer_group: customer_group,
        },
        dataType: "JSON",
        success: function (data) {
            mApp.unblock(".load_ajax");
            chartPaymentMethod(data['totalMoneyByReceiptType']);
            if (time == '' && branch != '') {
                $.getJSON(laroute.route('translate'), function (json) {
                chart(monthOneYear, data['seriesData']);
                $('#totalOrder').text(formatNumber(data['totalChart']['totalOrder'].toFixed(decimal_number)));
                $('#totalMoney').text(formatNumber(data['totalChart']['totalMoney'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderPaysuccess').text(formatNumber(data['totalChart']['totalOrderPaysuccess'].toFixed(decimal_number)));
                $('#totalMoneyOrderPaysuccess').text(formatNumber(data['totalChart']['totalMoneyOrderPaysuccess'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderNew').text(formatNumber(data['totalChart']['totalOrderNew'].toFixed(decimal_number)));
                $('#totalMoneyOrderNew').text(formatNumber(data['totalChart']['totalMoneyOrderNew'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderPayFail').text(formatNumber(data['totalChart']['totalOrderPayFail'].toFixed(decimal_number)));
                $('#totalMoneyOrderPayFail').text(formatNumber(data['totalChart']['totalMoneyOrderPayFail'].toFixed(decimal_number)) + json["VNĐ"]);
            });} else if (time != '' && branch == '') {
                $.getJSON(laroute.route('translate'), function (json) {
                chart(data['list'], data['seriesData']);
                $('#totalOrder').text(formatNumber(data['totalChart']['totalOrder'].toFixed(decimal_number)));
                $('#totalMoney').text(formatNumber(data['totalChart']['totalMoney'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderPaysuccess').text(formatNumber(data['totalChart']['totalOrderPaysuccess'].toFixed(decimal_number)));
                $('#totalMoneyOrderPaysuccess').text(formatNumber(data['totalChart']['totalMoneyOrderPaysuccess'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderNew').text(formatNumber(data['totalChart']['totalOrderNew'].toFixed(decimal_number)));
                $('#totalMoneyOrderNew').text(formatNumber(data['totalChart']['totalMoneyOrderNew'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderPayFail').text(formatNumber(data['totalChart']['totalOrderPayFail'].toFixed(decimal_number)));
                $('#totalMoneyOrderPayFail').text(formatNumber(data['totalChart']['totalMoneyOrderPayFail'].toFixed(decimal_number)) + json["VNĐ"]);
            });} else if (time != '' && branch != '') {
                if (data['list'].length > 31 && data['seriesData'].length > 31) {
                    let xxx = new Array();
                    let yyy = new Array();

                    for (let i = 0; i < 31; i++) {
                        xxx.push(data['list'][i]);
                    }
                    xxx.push('Khác');

                    for (let j = 0; j < 31; j++) {
                        yyy.push(data['seriesData'][j]);
                    }
                    let other = 0;
                    for (let z = 31; z < data['seriesData'].length; z++) {
                        other += data['seriesData'][z];
                    }
                    yyy.push(other);
                    chart(xxx, yyy);
                } else {
                    chart(data['list'], data['seriesData']);
                }
                $.getJSON(laroute.route('translate'), function (json) {
                $('#totalOrder').text(formatNumber(data['totalChart']['totalOrder'].toFixed(decimal_number)));
                $('#totalMoney').text(formatNumber(data['totalChart']['totalMoney'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderPaysuccess').text(formatNumber(data['totalChart']['totalOrderPaysuccess'].toFixed(decimal_number)));
                $('#totalMoneyOrderPaysuccess').text(formatNumber(data['totalChart']['totalMoneyOrderPaysuccess'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderNew').text(formatNumber(data['totalChart']['totalOrderNew'].toFixed(decimal_number)));
                $('#totalMoneyOrderNew').text(formatNumber(data['totalChart']['totalMoneyOrderNew'].toFixed(decimal_number)) + json["VNĐ"]);
                $('#totalOrderPayFail').text(formatNumber(data['totalChart']['totalOrderPayFail'].toFixed(decimal_number)));
                $('#totalMoneyOrderPayFail').text(formatNumber(data['totalChart']['totalMoneyOrderPayFail'].toFixed(decimal_number)) + json["VNĐ"]);
                });
            }
        }
    });
}