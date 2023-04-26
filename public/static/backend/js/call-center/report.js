var reportCustomerRequest = {
    jsonLang: null,
    chartRequestCustomer: function(global = true){
        $.ajax({
            url: laroute.route('report-call-center.get-chart-month'),
            method: "POST",
            global: global,
            data: {
                months: $('#months').val(),
                years: $('#years').val(),
            },
            dataType: "JSON",
            success: function (result) {
                if(result != null){
                    reportCustomerRequest.showchartRequestCustomer(result.data, result.categories);
                }
            }
        });
    },
    chartRequestCustomerByStaff: function(global = true){
        $.ajax({
            url: laroute.route('report-call-center.get-chart-month-by-staff'),
            method: "POST",
            global: global,
            data: {
                months: $('#months_staff').val(),
                years: $('#years_staff').val(),
            },
            dataType: "JSON",
            success: function (result) {
                if(result != null){
                    reportCustomerRequest.showchartRequestCustomerByStaff(result.data);
                }
            }
        });
    },
    showchartRequestCustomer: function (dataseries, categories) {
        $('#chart-request-customer').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'SỐ LƯỢNG YÊU CẦU TIẾP NHẬN - THEO NGÀY'
            },
            // subtitle: {
            //     text: 'Source: WorldClimate.com'
            // },
            xAxis: {
                categories: categories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: null,
                // title: {
                //     text: 'Rainfall (mm)'
                // }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<b>: {point.y:1f}</b>',
                // footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
               
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: '',
                data: dataseries
            }
        ]});
    },
    showchartRequestCustomerByStaff: function (dataseries) {
        $('#chart-request-customer-by-staff').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'SỐ LƯỢNG YÊU CẦU TIẾP NHẬN - THEO NHÂN VIÊN'
            },
            // subtitle: {
            //     text: 'Source: WorldClimate.com'
            // },
            xAxis: {
                // categories: categories,
                type: 'category',
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: null,
                // title: {
                //     text: 'Rainfall (mm)'
                // }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<b>: {point.y:1f}</b>',
                // footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                series: {
                    pointWidth: 20
                },
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: '',
                data: dataseries
            }
        ]});
    },
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

$(document).ready(function (){
    reportCustomerRequest.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
    $('#years').select2().on("change", function(e) {
        reportCustomerRequest.chartRequestCustomer();
    });
    $('#months').select2().on("change", function(e) {
        reportCustomerRequest.chartRequestCustomer();
    });
    $('#years_staff').select2().on("change", function(e) {
        reportCustomerRequest.chartRequestCustomerByStaff();
    });
    $('#months_staff').select2().on("change", function(e) {
        reportCustomerRequest.chartRequestCustomerByStaff();
    });
    reportCustomerRequest.chartRequestCustomer();
    reportCustomerRequest.chartRequestCustomerByStaff();
});