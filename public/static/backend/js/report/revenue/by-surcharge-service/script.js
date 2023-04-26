var revenueBySurService = {
    jsonLang: null,
    _init: function () {
        revenueBySurService.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[revenueBySurService.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[revenueBySurService.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[revenueBySurService.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[revenueBySurService.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[revenueBySurService.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[revenueBySurService.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": revenueBySurService.jsonLang["Đồng ý"],
                "cancelLabel": revenueBySurService.jsonLang["Thoát"],
                "customRangeLabel": revenueBySurService.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    revenueBySurService.jsonLang["CN"],
                    revenueBySurService.jsonLang["T2"],
                    revenueBySurService.jsonLang["T3"],
                    revenueBySurService.jsonLang["T4"],
                    revenueBySurService.jsonLang["T5"],
                    revenueBySurService.jsonLang["T6"],
                    revenueBySurService.jsonLang["T7"]
                ],
                "monthNames": [
                    revenueBySurService.jsonLang["Tháng 1 năm"],
                    revenueBySurService.jsonLang["Tháng 2 năm"],
                    revenueBySurService.jsonLang["Tháng 3 năm"],
                    revenueBySurService.jsonLang["Tháng 4 năm"],
                    revenueBySurService.jsonLang["Tháng 5 năm"],
                    revenueBySurService.jsonLang["Tháng 6 năm"],
                    revenueBySurService.jsonLang["Tháng 7 năm"],
                    revenueBySurService.jsonLang["Tháng 8 năm"],
                    revenueBySurService.jsonLang["Tháng 9 năm"],
                    revenueBySurService.jsonLang["Tháng 10 năm"],
                    revenueBySurService.jsonLang["Tháng 11 năm"],
                    revenueBySurService.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            revenueBySurService.loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            revenueBySurService.loadChart();
        });
        $('#number_service').select2().on('select2:select', function (event) {
            revenueBySurService.loadChart();
        });
        $('#surcharge_service_id').select2().on('select2:select', function (event) {
            revenueBySurService.loadChart();
        });
        revenueBySurService.loadChart();
    
    },

    loadChart: function () {
        var numberService = $('#number_service').val();
        var time = $('#time').val();
        var branch = $('#branch').val();
        var surcharge_service_id = $('#surcharge_service_id option:selected').val();
        $('#time_detail').val(time);
        $('#branch_detail').val(branch);
        $('#surcharge_service_id_detail').val(surcharge_service_id);

        //Load filter export tổng
        $('#export_time_total').val(time);
        $('#export_branch_total').val(branch);
        $('#export_surcharge_service_id_total').val(surcharge_service_id);
        //Load filter export chi tiết
        $('#export_time_detail').val(time);
        $('#export_branch_detail').val(branch);
        $('#export_surcharge_service_id_detail').val(surcharge_service_id);
        
        $.ajax({
            url: laroute.route('admin.report-revenue.surcharge-service.filter'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                numberService: numberService,
                surchargeServiceId: surcharge_service_id,
            },
            success: function (res) {
                if (res.countListObject > 10) {
                    $('#container').height(res.countListObject * 50);
                }
                chart(res.arrayCategories, res.dataSeries);
                $('#totalOrderPaySuccess').text(formatNumber(res.totalRevenue + revenueBySurService.jsonLang[" VNĐ"]));

                $('#number_service_detail').val(JSON.stringify(res.arrService));
                $('#export_number_service_detail').val(JSON.stringify(res.arrService));
                $('#export_number_service_total').val(JSON.stringify(res.arrService));
                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-revenue.surcharge-service.list-detail')
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
                    text: revenueBySurService.jsonLang['Số tiền (VNĐ)'],
                    align: 'high'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + revenueBySurService.jsonLang[' VNĐ'] + '</b></td></tr>',
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
                name: revenueBySurService.jsonLang['Số tiền (VNĐ)'],
                data: dataSeries
            }]
        });
    
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}