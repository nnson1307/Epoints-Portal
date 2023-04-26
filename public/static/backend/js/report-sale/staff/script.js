var reportSale = {
    jsonLang: null,
    getTotal : function () {
        $.ajax({
            url: laroute.route('report-sale-staff.get-total'),
            method: "POST",
            data: {
                time: $('#time').val(),
                staff: $('#staff').val(),
            },
            dataType: "JSON",
            success: function (data) {
                var totalOrderMedium = 0;
                if(data['totalAmount'] > 0 && data['totalCountOrders'] > 0){
                    totalOrderMedium = data['totalAmount'] / data['totalCountOrders'];
                }
                $('#total').text(formatNumber(data['totalAmount'].toFixed(decimal_number)) + " " + reportSale.jsonLang["VNĐ"]);
                $('#totalReceipt').text(formatNumber(data['totalReceipt'].toFixed(decimal_number)) + " " + reportSale.jsonLang["VNĐ"]);
                $('#totalCustomerDept').text(formatNumber(data['totalCustomerDept'].toFixed(decimal_number)) + " " + reportSale.jsonLang["VNĐ"]);
                $('#totalOrderMedium').text(formatNumber(totalOrderMedium.toFixed(decimal_number)) + " " + reportSale.jsonLang["VNĐ"]);
                $('#totalOrderCancel').text(formatNumber(data['totalOrderCancel'].toFixed(decimal_number)));
                $('#totalOrderPay').text(formatNumber(data['totalOrderPay'].toFixed(decimal_number)));
                $('#totalOrderNotPay').text(formatNumber(data['totalOrderNotPay'].toFixed(decimal_number)));
                $('#totalOrder').text(formatNumber(data['totalCountOrders'].toFixed(decimal_number)));
                $('#totalOrderPayhafl').text(formatNumber(data['totalOrderPayHalf'].toFixed(decimal_number)));
                $('#totalCustomer').text(formatNumber(data['totalCustomer'].toFixed(decimal_number)));
            }
        });
    },
    chartSaleAmount: function(global = true){
        $.ajax({
            url: laroute.route('report-sale-staff.get-chart-total'),
            method: "POST",
            global: global,
            data: {
                time: $('#time-chart').val(),
                staff: $('#staff-chart').val(),
                type: $('#type-chart').val(),
            },
            dataType: "JSON",
            success: function (data) {
                if(data != null){
                    reportSale.showChartSaleAmountByDay(data.series, data.categories);
                }
            }
        });
    },
    chartSaleAmountByBranch: function(global = true){
        $.ajax({
            url: laroute.route('report-sale-staff.get-chart-total-by-staff'),
            method: "POST",
            global: global,
            data: {
                time: $('#time-chart').val(),
                staff: $('#staff-chart').val(),
            },
            dataType: "JSON",
            success: function (data) {
                if(data != null){
                    reportSale.showChartSaleAmountByBranch(data.series, data.categories);
                }
            }
        });
    },
    chartTotalOrder: function(global = true){
        $.ajax({
            url: laroute.route('report-sale-staff.get-chart-total-order'),
            method: "POST",
            data: {
                time: $('#time-chart').val(),
                staff: $('#staff-chart').val(),
            },
            global: global,
            dataType: "JSON",
            success: function (data) {
                if(data != null){
                    // console.log(data.series);return;
                    var hBar = data.categories.length > 10 ? 800 : 500
                    $('#container-total-order').highcharts({
                        chart: {
                            type: 'bar',
                            height: hBar,
                            style: {
                                fontFamily: 'Roboto'
                            }
                        },
                        title: {
                            text: ''
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: data.categories,
                            title: {
                                text: null
                            }
                        },
                        yAxis: {
                            title: {
                                useHTML: true,
                                text: ''
                            }
                        },
                        tooltip: {
                            formatter: function() {
                                var formatStr = '<b>' + this.x + '</b><br />';
                                formatStr += '<span style="color:' + this.color + '">●</span>' + reportSale.jsonLang['Nhân viên'] + ' ' + this.series.name + ': <b>' + formatNumber(this.y) + '</b><span></span><br/>';
                                // return 'Ngày: '+ this.x +'<br/><b>'+ formatNumber(this.y) +'</b>' +  reportSale.jsonLang[' VNĐ'] + '<br/>' ;
                                return formatStr;
                            }
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 10,
                                borderWidth: 0,
                            },
                        },
                        credits: {
                            enabled: false
                        },
                        series: data.series
                    });
                }
            }
        });
        return;

    },

    ChartOrdersByBranch: function (global = true) {
        $.ajax({
            url: laroute.route('report-sale-staff.get-chart-total-order-by-staff'),
            method: "POST",
            data: {
                time: $('#time-chart').val(),
                staff: $('#staff-chart').val(),
            },
            global: global,
            dataType: "JSON",
            success: function (data) {
                if(data != null){
                    reportSale.showChartOrdersByBranch(data);
                }
            }
        });
    },

    changeReportChartType: function(e){
        $('#type-chart').val($(e).val());
        reportSale.chartSaleAmount($(e).val());
    },

    showChartSaleAmountByDay: function (dataseries, categories) {
        var hBar = categories.length > 10 ? 800 : 500
        $('#container-total-amount').highcharts({
            chart: {
                type: 'bar',
                height: hBar,
                style: {
                    fontFamily: 'Roboto'
                }
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: categories,
                title: {
                    text: null
                }
            },
            yAxis: {
                title: {
                    useHTML: true,
                    text: ''
                }
            },
            tooltip: {
                formatter: function() {
                    var formatStr = '<b>' + this.x + '</b><br />';
                    formatStr += '<span style="color:' + this.color + '">●</span>' + reportSale.jsonLang['Nhân viên'] + ' ' + this.series.name + ': <b>' + formatNumber(this.y) + '</b><span></span><br/>';
                    // return 'Ngày: '+ this.x +'<br/><b>'+ formatNumber(this.y) +'</b>' +  reportSale.jsonLang[' VNĐ'] + '<br/>' ;
                    return formatStr;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 10,
                    borderWidth: 0,
                },
            },
            credits: {
                enabled: false
            },
            series: dataseries
        });
    },

    showChartSaleAmountByBranch: function (dataseries, categories) {
        var hBar = categories.length > 10 ? 800 : 500;

        $('#container-total-amount-branch').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: categories,
                title: {
                    text: null
                }
            },
            yAxis: {
                title: {
                    useHTML: true,
                    text: ''
                }
            },
            tooltip: {
                formatter: function() {
                    var formatStr = '<span style="color:' + this.color + '">●</span><b>' + reportSale.jsonLang['Nhân viên'] + ' ' + this.x + '</b><br />';
                    formatStr += this.series.name + ': <b>' + formatNumber(this.y) + '</b><span></span><br/>';
                    // return 'Ngày: '+ this.x +'<br/><b>'+ formatNumber(this.y) +'</b>' +  reportSale.jsonLang[' VNĐ'] + '<br/>' ;
                    return formatStr;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 10,
                    borderWidth: 0,
                },
            },
            credits: {
                enabled: false
            },
            series: dataseries
        });

    },

    showChartOrdersByBranch: function (data) {

        $('#container-total-order-branch').highcharts({
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
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
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<span style="color:{point.color}">●</span>' + reportSale.jsonLang['Nhân viên'] + ' {point.name}: {point.y:.0f}%'
                    },
                    showInLegend: true
                }
            },

            tooltip: {
                headerFormat: '',
                pointFormat: '<span style="color:{point.color}">' + reportSale.jsonLang['Nhân viên'] + ' {point.name}</span>: <b>{point.y:.0f}%</b>'
            },

            series: [
                {
                    colorByPoint: true,
                    data: data
                }
            ],

        });
    },


    showModalListOrders: function (type, global = true) {

        $.ajax({
            url: laroute.route('report-sale.show-list-orders'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                order_type : type,
            },
            global: global,
            success: function (res) {
                if (res.html != null) {
                    $('#modal-list-orders').html(res.html);
                    $('#modalListOrders').modal('show');
                } else {
                    Swal.fire(
                        'Thông Báo',
                        'Không có lịch làm việc trong thời gian này',
                        'error'
                    )
                }
                $('#autotable-list-pop').PioTable({
                    baseUrl: laroute.route('report-sale.show-list-orders-action')
                });
                $('#autotable-list-pop').find('.btn-search').trigger('click');
            }
        });
    },
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

$(document).ready(function (){
    reportSale.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
    var arrRange = {};
    arrRange[reportSale.jsonLang["Hôm nay"]] = [moment(), moment()];
    arrRange[reportSale.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[reportSale.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[reportSale.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[reportSale.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[reportSale.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
            "applyLabel": reportSale.jsonLang["Đồng ý"],
            "cancelLabel": reportSale.jsonLang["Thoát"],
            "customRangeLabel": reportSale.jsonLang['Tùy chọn ngày'],
            daysOfWeek: [
                reportSale.jsonLang["CN"],
                reportSale.jsonLang["T2"],
                reportSale.jsonLang["T3"],
                reportSale.jsonLang["T4"],
                reportSale.jsonLang["T5"],
                reportSale.jsonLang["T6"],
                reportSale.jsonLang["T7"]
            ],
            "monthNames": [
                reportSale.jsonLang["Tháng 1 năm"],
                reportSale.jsonLang["Tháng 2 năm"],
                reportSale.jsonLang["Tháng 3 năm"],
                reportSale.jsonLang["Tháng 4 năm"],
                reportSale.jsonLang["Tháng 5 năm"],
                reportSale.jsonLang["Tháng 6 năm"],
                reportSale.jsonLang["Tháng 7 năm"],
                reportSale.jsonLang["Tháng 8 năm"],
                reportSale.jsonLang["Tháng 9 năm"],
                reportSale.jsonLang["Tháng 10 năm"],
                reportSale.jsonLang["Tháng 11 năm"],
                reportSale.jsonLang["Tháng 12 năm"]
            ],
            "firstDay": 1
        },
        ranges: arrRange

    }).on('apply.daterangepicker', function (event) {
        reportSale.getTotal();
    });

    $('#staff').select2().on('select2:select', function (event) {
        reportSale.getTotal();
    });

    $("#time-chart").daterangepicker({
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
            "applyLabel": reportSale.jsonLang["Đồng ý"],
            "cancelLabel": reportSale.jsonLang["Thoát"],
            "customRangeLabel": reportSale.jsonLang['Tùy chọn ngày'],
            daysOfWeek: [
                reportSale.jsonLang["CN"],
                reportSale.jsonLang["T2"],
                reportSale.jsonLang["T3"],
                reportSale.jsonLang["T4"],
                reportSale.jsonLang["T5"],
                reportSale.jsonLang["T6"],
                reportSale.jsonLang["T7"]
            ],
            "monthNames": [
                reportSale.jsonLang["Tháng 1 năm"],
                reportSale.jsonLang["Tháng 2 năm"],
                reportSale.jsonLang["Tháng 3 năm"],
                reportSale.jsonLang["Tháng 4 năm"],
                reportSale.jsonLang["Tháng 5 năm"],
                reportSale.jsonLang["Tháng 6 năm"],
                reportSale.jsonLang["Tháng 7 năm"],
                reportSale.jsonLang["Tháng 8 năm"],
                reportSale.jsonLang["Tháng 9 năm"],
                reportSale.jsonLang["Tháng 10 năm"],
                reportSale.jsonLang["Tháng 11 năm"],
                reportSale.jsonLang["Tháng 12 năm"]
            ],
            "firstDay": 1
        },
        ranges: arrRange

    }).on('apply.daterangepicker', function (event) {
        reportSale.chartSaleAmount();
        reportSale.chartTotalOrder();
        reportSale.chartSaleAmountByBranch();
        reportSale.ChartOrdersByBranch();
    });

    $('#staff-chart').select2().on('select2:select', function (event) {
        reportSale.chartSaleAmount();
        reportSale.chartTotalOrder();
        reportSale.chartSaleAmountByBranch();
        reportSale.ChartOrdersByBranch();
    });
    $('#department').select2().on('select2:select', function (event) {

    });
    reportSale.getTotal();
    reportSale.chartSaleAmount('amount', false);
    reportSale.chartTotalOrder(false);
    reportSale.chartSaleAmountByBranch(false);
    reportSale.ChartOrdersByBranch(false);
});