var serviceStaff = {
    jsonLang: null,
    _init: function () {
        serviceStaff.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
            
        var arrRange = {};
        arrRange[serviceStaff.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[serviceStaff.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[serviceStaff.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[serviceStaff.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[serviceStaff.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[serviceStaff.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": serviceStaff.jsonLang["Đồng ý"],
                "cancelLabel": serviceStaff.jsonLang["Thoát"],
                "customRangeLabel": serviceStaff.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    serviceStaff.jsonLang["CN"],
                    serviceStaff.jsonLang["T2"],
                    serviceStaff.jsonLang["T3"],
                    serviceStaff.jsonLang["T4"],
                    serviceStaff.jsonLang["T5"],
                    serviceStaff.jsonLang["T6"],
                    serviceStaff.jsonLang["T7"]
                ],
                "monthNames": [
                    serviceStaff.jsonLang["Tháng 1 năm"],
                    serviceStaff.jsonLang["Tháng 2 năm"],
                    serviceStaff.jsonLang["Tháng 3 năm"],
                    serviceStaff.jsonLang["Tháng 4 năm"],
                    serviceStaff.jsonLang["Tháng 5 năm"],
                    serviceStaff.jsonLang["Tháng 6 năm"],
                    serviceStaff.jsonLang["Tháng 7 năm"],
                    serviceStaff.jsonLang["Tháng 8 năm"],
                    serviceStaff.jsonLang["Tháng 9 năm"],
                    serviceStaff.jsonLang["Tháng 10 năm"],
                    serviceStaff.jsonLang["Tháng 11 năm"],
                    serviceStaff.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            serviceStaff.loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            serviceStaff.loadChart();
        });
        $('#number_product').select2().on('select2:select', function (event) {
            serviceStaff.loadChart();
        });
        $('#staff_id').select2().on('select2:select', function (event) {
            serviceStaff.loadChart();
        });
        serviceStaff.loadChart();
    
    },

    loadChart: function () {
        var numberLoad = $('#number_load').val();
        var time = $('#time').val();
        var branch = $('#branch').val();
        var staff_id = $('#staff_id option:selected').val();
        //Load filter list chi tiết
        $('#time_detail').val(time);
        $('#branch_detail').val(branch);
        $('#staff_id_detail').val(staff_id);
        //Load filter export tổng
        $('#time_export_total').val(time);
        $('#branch_export_total').val(branch);
        $('#staff_id_export_total').val(staff_id);
        //Load filter export chi tiết
        $('#time_export_detail').val(time);
        $('#branch_export_detail').val(branch);
        $('#staff_id_export_detail').val(staff_id);

        
        $.ajax({
            url: laroute.route('admin.report-service-staff.load-chart'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                staffId: staff_id,
                // numberLoad: numberLoad,
            },
            success: function (res) {
                if (res.dataSeries.length > 10) {
                    $('#load-chart').height(res.dataSeries.length * 50);
                }
                chart(res.arrayCategories, res.dataSeries);

                $('#totalOrderPaySuccess').text(formatNumber(res.totalAmount + serviceStaff.jsonLang[" VNĐ"]));

                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-service-staff.list-detail')
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
        $('#load-chart').highcharts({
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
                    text: serviceStaff.jsonLang['Số tiền (VNĐ)'],
                    align: 'high'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.'+ decimal_number +'f}' + serviceStaff.jsonLang[' VNĐ'] + '</b></td></tr>',
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
                name: serviceStaff.jsonLang['Số tiền (VNĐ)'],
                data: dataSeries
            }]
        });
    
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}