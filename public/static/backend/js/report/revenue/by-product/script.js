var revenueByProduct = {
    jsonLang: null,
    _init: function () {
        revenueByProduct.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[revenueByProduct.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[revenueByProduct.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[revenueByProduct.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[revenueByProduct.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[revenueByProduct.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[revenueByProduct.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": revenueByProduct.jsonLang["Đồng ý"],
                "cancelLabel": revenueByProduct.jsonLang["Thoát"],
                "customRangeLabel": revenueByProduct.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    revenueByProduct.jsonLang["CN"],
                    revenueByProduct.jsonLang["T2"],
                    revenueByProduct.jsonLang["T3"],
                    revenueByProduct.jsonLang["T4"],
                    revenueByProduct.jsonLang["T5"],
                    revenueByProduct.jsonLang["T6"],
                    revenueByProduct.jsonLang["T7"]
                ],
                "monthNames": [
                    revenueByProduct.jsonLang["Tháng 1 năm"],
                    revenueByProduct.jsonLang["Tháng 2 năm"],
                    revenueByProduct.jsonLang["Tháng 3 năm"],
                    revenueByProduct.jsonLang["Tháng 4 năm"],
                    revenueByProduct.jsonLang["Tháng 5 năm"],
                    revenueByProduct.jsonLang["Tháng 6 năm"],
                    revenueByProduct.jsonLang["Tháng 7 năm"],
                    revenueByProduct.jsonLang["Tháng 8 năm"],
                    revenueByProduct.jsonLang["Tháng 9 năm"],
                    revenueByProduct.jsonLang["Tháng 10 năm"],
                    revenueByProduct.jsonLang["Tháng 11 năm"],
                    revenueByProduct.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            revenueByProduct.loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            revenueByProduct.loadChart();
        });
        $('#number_product').select2().on('select2:select', function (event) {
            revenueByProduct.loadChart();
        });
        revenueByProduct.loadChart();
   
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
        var numberProduct = $('#number_product').val();
       
        $.ajax({
            url: laroute.route('admin.report-revenue.product.filter'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                numberProduct: numberProduct,
            },
            success: function (res) {
                if (res.countListObject > 10) {
                    $('#container').height(res.countListObject * 50);
                }
                chart(res.arrayCategories, res.dataSeries);
                $('#totalOrderPaySuccess').text(formatNumber(res.totalRevenue + revenueByProduct.jsonLang[" VNĐ"]));

                $('#number_product_detail').val(JSON.stringify(res.arrProduct));
                $('#export_number_product_detail').val(JSON.stringify(res.arrProduct));
                $('#export_number_product_total').val(JSON.stringify(res.arrProduct));
                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-revenue.product.list-detail')
                });

                $('.btn-search').trigger('click');
            }
        });
    
    },
}
Highcharts.setOptions({
    lang: {
        decimalPoint: '.',
        thousandsSep: ','
    }
});
function chart(arrayCategories, dataSeries) {
    
        // Create the chart
        $('#container').highcharts({
            chart: {
                type: 'bar',
                marginRight: 50
            },
            title: {
                text: ''
            },
            colors: ['#34bfa3', '#2f7ed8', '#e83e8c', '#ffc107'],
            xAxis: {
                categories: arrayCategories ,
            },
            yAxis: {
                min: 0,
                title: {
                    text: revenueByProduct.jsonLang['Số tiền (VNĐ)'],
                    align: 'high'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + revenueByProduct.jsonLang[' VNĐ'] + '</b></td></tr>',
                footerFormat: '</table>',
            },
            exporting: false,
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: revenueByProduct.jsonLang['Số tiền (VNĐ)'],
                data: dataSeries
            }]
        });
    
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}