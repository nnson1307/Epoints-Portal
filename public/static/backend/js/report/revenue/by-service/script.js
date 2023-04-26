var revenueByService = {
    jsonLang: null,
    _init: function () {
        revenueByService.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var arrRange = {};
        arrRange[revenueByService.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[revenueByService.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[revenueByService.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[revenueByService.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[revenueByService.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[revenueByService.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": revenueByService.jsonLang["Đồng ý"],
                "cancelLabel": revenueByService.jsonLang["Thoát"],
                "customRangeLabel": revenueByService.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    revenueByService.jsonLang["CN"],
                    revenueByService.jsonLang["T2"],
                    revenueByService.jsonLang["T3"],
                    revenueByService.jsonLang["T4"],
                    revenueByService.jsonLang["T5"],
                    revenueByService.jsonLang["T6"],
                    revenueByService.jsonLang["T7"]
                ],
                "monthNames": [
                    revenueByService.jsonLang["Tháng 1 năm"],
                    revenueByService.jsonLang["Tháng 2 năm"],
                    revenueByService.jsonLang["Tháng 3 năm"],
                    revenueByService.jsonLang["Tháng 4 năm"],
                    revenueByService.jsonLang["Tháng 5 năm"],
                    revenueByService.jsonLang["Tháng 6 năm"],
                    revenueByService.jsonLang["Tháng 7 năm"],
                    revenueByService.jsonLang["Tháng 8 năm"],
                    revenueByService.jsonLang["Tháng 9 năm"],
                    revenueByService.jsonLang["Tháng 10 năm"],
                    revenueByService.jsonLang["Tháng 11 năm"],
                    revenueByService.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            revenueByService.loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            revenueByService.loadChart();
        });
        $('#number_service').select2().on('select2:select', function (event) {
            revenueByService.loadChart();
        });
        $('#service_id').select2().on('select2:select', function (event) {
            revenueByService.loadChart();
        });
        $('#service_category_id').select2().on('select2:select', function (event) {
            revenueByService.loadChart();
        });
        revenueByService.loadChart();
    
    },

    loadChart: function () {
        var numberService = $('#number_service').val();
        var time = $('#time').val();
        var branch = $('#branch').val();
        var service_id = $('#service_id option:selected').val();
        var serviceCategoryId = $('#service_category_id option:selected').val();
        $('#time_detail').val(time);
        $('#branch_detail').val(branch);
        $('#service_id_detail').val(service_id);

        //Load filter export tổng
        $('#export_time_total').val(time);
        $('#export_branch_total').val(branch);
        $('#export_service_id_total').val(service_id);
        //Load filter export chi tiết
        $('#export_time_detail').val(time);
        $('#export_branch_detail').val(branch);
        $('#export_service_id_detail').val(service_id);
        
        $.ajax({
            url: laroute.route('admin.report-revenue.service.filter'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                numberService: numberService,
                serviceId: service_id,
                serviceCategoryId: serviceCategoryId,
            },
            success: function (res) {
                if (res.countListObject > 10) {
                    $('#container').height(res.countListObject * 50);
                }
                chart(res.arrayCategories, res.dataSeries);
                $('#totalOrderPaySuccess').text(formatNumber(res.totalRevenue + revenueByService.jsonLang[" VNĐ"]));

                $('#number_service_detail').val(JSON.stringify(res.arrService));
                $('#export_number_service_detail').val(JSON.stringify(res.arrService));
                $('#export_number_service_total').val(JSON.stringify(res.arrService));
                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-revenue.service.list-detail')
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
                    text: revenueByService.jsonLang['Số tiền (VNĐ)'],
                    align: 'high'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + revenueByService.jsonLang[' VNĐ'] + '</b></td></tr>',
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
                name: revenueByService.jsonLang['Số tiền (VNĐ)'],
                data: dataSeries
            }]
        });
    
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}