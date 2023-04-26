var reportSaleCustomer = {
    //lấy file dịch
    jsonLang: null,
    getTotal : function () {  
        $.ajax({
            url: laroute.route('report-sale-customer.get-total'),
            method: "POST",
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                customerGroup: $('#customer_group').val(),
                customerId: $('#customer').val()
            },
            dataType: "JSON",
            success: function (data) {
                var totalOrderMedium = 0;
                if(data['totalAmount'] > 0 && data['totalCountOrders'] > 0){
                    totalOrderMedium = data['totalAmount'] / data['totalCountOrders'];
                }
                $('#total').text(formatNumber(data['totalAmount'].toFixed(decimal_number)) + " " + reportSaleCustomer.jsonLang["VNĐ"]);
                $('#totalReceipt').text(formatNumber(data['totalReceipt'].toFixed(decimal_number)) + " " + reportSaleCustomer.jsonLang["VNĐ"]);
                $('#totalCustomerDept').text(formatNumber(data['totalCustomerDept'].toFixed(decimal_number)) + " " + reportSaleCustomer.jsonLang["VNĐ"]);
                $('#totalOrderMedium').text(formatNumber(totalOrderMedium.toFixed(decimal_number)) + " " + reportSaleCustomer.jsonLang["VNĐ"]);
                $('#totalOrderCancel').text(formatNumber(data['totalOrderCancel'].toFixed(decimal_number)));
                $('#totalOrderPay').text(formatNumber(data['totalOrderPay'].toFixed(decimal_number)));
                $('#totalOrderNotPay').text(formatNumber(data['totalOrderNotPay'].toFixed(decimal_number)));
                $('#totalOrder').text(formatNumber(data['totalCountOrders'].toFixed(decimal_number)));
                $('#totalOrderPayhafl').text(formatNumber(data['totalOrderPayHalf'].toFixed(decimal_number)));
                // $('#totalCustomer').text(formatNumber(data['totalCustomer'].toFixed(decimal_number)));
            }
        });
    },
    chartSaleAmount: function(){
        $.ajax({
            url: laroute.route('report-sale-customer.get-chart-total'),
            method: "POST",
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                customerGroup: $('#customer_group').val(),
                type: $('#type-chart').val(),
            },
            dataType: "JSON",
            success: function (data) {
                if(data != null){
                    reportSaleCustomer.showChartSaleAmountByDay(data.series, data.categories);
                }
            }
        });
    },
    chartSaleAmountByCustomerGroup: function(){
        $.ajax({
            url: laroute.route('report-sale-customer.get-chart-total-by-customer'),
            method: "POST",
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                customerGroup: $('#customer_group').val(),
            },
            dataType: "JSON",
            success: function (data) {
                if(data != null){
                    reportSaleCustomer.showChartSaleAmountByCustomerGroup(data.series, data.categories);
                }
            }
        });
    },
    chartTotalOrder: function(){
        $.ajax({
            url: laroute.route('report-sale-customer.get-chart-total-order'),
            method: "POST",
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                customerGroup: $('#customer_group').val(),
            },
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
                                formatStr += '<span style="color:' + this.color + '">●</span>' + this.series.name + ': <b>' + formatNumber(this.y) + '</b><span></span><br/>';
                                // return 'Ngày: '+ this.x +'<br/><b>'+ formatNumber(this.y) +'</b>' +  reportSaleCustomer.jsonLang[' VNĐ'] + '<br/>' ;
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
    ChartOrdersByCustomer: function () {
        $.ajax({
            url: laroute.route('report-sale-customer.get-chart-total-order-by-customer'),
            method: "POST",
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                customerGroup: $('#customer_group').val(),
            },
            dataType: "JSON",
            success: function (data) {
                if(data != null){
                    reportSaleCustomer.showChartOrdersByCustomer(data);
                }
            }
        });  
    },

    changeReportChartType: function(e){
        $('#type-chart').val($(e).val());
        reportSaleCustomer.chartSaleAmount($(e).val());
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
                    formatStr += '<span style="color:' + this.color + '">●</span>' + this.series.name + ': <b>' + formatNumber(this.y) + '</b><span></span><br/>';
                    // return 'Ngày: '+ this.x +'<br/><b>'+ formatNumber(this.y) +'</b>' +  reportSaleCustomer.jsonLang[' VNĐ'] + '<br/>' ;
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

    showChartSaleAmountByCustomerGroup: function (dataseries, categories) {
        var hBar = categories.length > 10 ? 800 : 500
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
                    var formatStr = '<span style="color:' + this.color + '">●</span><b>' + this.x + '</b><br />';
                    formatStr += this.series.name + ': <b>' + formatNumber(this.y) + '</b><span></span><br/>';
                    // return 'Ngày: '+ this.x +'<br/><b>'+ formatNumber(this.y) +'</b>' +  reportSaleCustomer.jsonLang[' VNĐ'] + '<br/>' ;
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

    showChartOrdersByCustomer: function (data) {
       
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
                        format: '<span style="color:{point.color}">●</span>{point.name}: {point.y:.0f}%'
                    },
                    showInLegend: true
                }
            },
        
            tooltip: {
                headerFormat: '',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}%</b>'
            },
        
            series: [
                {
                    colorByPoint: true,
                    data: data
                }
            ],
            
        });
    }
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

$(document).ready(function (){
    reportSaleCustomer.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
    var arrRange = {};
        arrRange[reportSaleCustomer.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[reportSaleCustomer.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[reportSaleCustomer.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[reportSaleCustomer.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[reportSaleCustomer.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[reportSaleCustomer.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": reportSaleCustomer.jsonLang["Đồng ý"],
                "cancelLabel": reportSaleCustomer.jsonLang["Thoát"],
                "customRangeLabel": reportSaleCustomer.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    reportSaleCustomer.jsonLang["CN"],
                    reportSaleCustomer.jsonLang["T2"],
                    reportSaleCustomer.jsonLang["T3"],
                    reportSaleCustomer.jsonLang["T4"],
                    reportSaleCustomer.jsonLang["T5"],
                    reportSaleCustomer.jsonLang["T6"],
                    reportSaleCustomer.jsonLang["T7"]
                ],
                "monthNames": [
                    reportSaleCustomer.jsonLang["Tháng 1 năm"],
                    reportSaleCustomer.jsonLang["Tháng 2 năm"],
                    reportSaleCustomer.jsonLang["Tháng 3 năm"],
                    reportSaleCustomer.jsonLang["Tháng 4 năm"],
                    reportSaleCustomer.jsonLang["Tháng 5 năm"],
                    reportSaleCustomer.jsonLang["Tháng 6 năm"],
                    reportSaleCustomer.jsonLang["Tháng 7 năm"],
                    reportSaleCustomer.jsonLang["Tháng 8 năm"],
                    reportSaleCustomer.jsonLang["Tháng 9 năm"],
                    reportSaleCustomer.jsonLang["Tháng 10 năm"],
                    reportSaleCustomer.jsonLang["Tháng 11 năm"],
                    reportSaleCustomer.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
            
        }).on('apply.daterangepicker', function (event) {
            reportSaleCustomer.getTotal();
            reportSaleCustomer.chartSaleAmount('amount');
            reportSaleCustomer.chartTotalOrder();
            reportSaleCustomer.chartSaleAmountByCustomerGroup();
            reportSaleCustomer.ChartOrdersByCustomer();
        });
        $('#branch').select2().on('select2:select', function (event) {
            reportSaleCustomer.getTotal();
            reportSaleCustomer.chartSaleAmount('amount');
            reportSaleCustomer.chartTotalOrder();
            reportSaleCustomer.chartSaleAmountByCustomerGroup();
            reportSaleCustomer.ChartOrdersByCustomer();
        });
      
        $('#customer_group').select2().on('select2:select', function (event) {
            $('#customer').empty();
            var html = '<option value="">Chọn khách hàng</option>';
            reportSaleCustomer.getTotal();
            reportSaleCustomer.chartSaleAmount('amount');
            reportSaleCustomer.chartTotalOrder();
            reportSaleCustomer.chartSaleAmountByCustomerGroup();
            reportSaleCustomer.ChartOrdersByCustomer();
            $.ajax({
                url: laroute.route('report-sale-customer.get-customer'),
                method: "POST",
                data: {
                    customer_group_id: $('#customer_group').val(),
                },
                dataType: "JSON",
                success: function (data) {
                    if(data.results != null){
                        data.results.forEach(element => {
                            html += '<option value="' + element.id +'">' + element.text + '</option>'
                        });
                    }
                    $('#customer').html(html);
                    $('#customer').select2();
                }
            });  
        });
        $('#customer').select2().on('select2:select', function (event) {
            reportSaleCustomer.getTotal();
        });
       
        reportSaleCustomer.getTotal();
        reportSaleCustomer.chartSaleAmount('amount');
        reportSaleCustomer.chartTotalOrder();
        reportSaleCustomer.chartSaleAmountByCustomerGroup();
        reportSaleCustomer.ChartOrdersByCustomer();
});