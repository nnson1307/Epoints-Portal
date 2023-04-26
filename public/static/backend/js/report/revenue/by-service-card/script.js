var revenueByServiceCard = {
    jsonLang: null,
    _init: function () {
        revenueByServiceCard.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[revenueByServiceCard.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[revenueByServiceCard.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[revenueByServiceCard.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[revenueByServiceCard.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[revenueByServiceCard.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[revenueByServiceCard.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": revenueByServiceCard.jsonLang["Đồng ý"],
                "cancelLabel": revenueByServiceCard.jsonLang["Thoát"],
                "customRangeLabel": revenueByServiceCard.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    revenueByServiceCard.jsonLang["CN"],
                    revenueByServiceCard.jsonLang["T2"],
                    revenueByServiceCard.jsonLang["T3"],
                    revenueByServiceCard.jsonLang["T4"],
                    revenueByServiceCard.jsonLang["T5"],
                    revenueByServiceCard.jsonLang["T6"],
                    revenueByServiceCard.jsonLang["T7"]
                ],
                "monthNames": [
                    revenueByServiceCard.jsonLang["Tháng 1 năm"],
                    revenueByServiceCard.jsonLang["Tháng 2 năm"],
                    revenueByServiceCard.jsonLang["Tháng 3 năm"],
                    revenueByServiceCard.jsonLang["Tháng 4 năm"],
                    revenueByServiceCard.jsonLang["Tháng 5 năm"],
                    revenueByServiceCard.jsonLang["Tháng 6 năm"],
                    revenueByServiceCard.jsonLang["Tháng 7 năm"],
                    revenueByServiceCard.jsonLang["Tháng 8 năm"],
                    revenueByServiceCard.jsonLang["Tháng 9 năm"],
                    revenueByServiceCard.jsonLang["Tháng 10 năm"],
                    revenueByServiceCard.jsonLang["Tháng 11 năm"],
                    revenueByServiceCard.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            revenueByServiceCard.loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            revenueByServiceCard.loadChart();
        });
        $('#number_service_card').select2().on('select2:select', function (event) {
            revenueByServiceCard.loadChart();
        });
        $('#service_card_id').select2().on('select2:select', function (event) {
            revenueByServiceCard.loadChart();
        });
        revenueByServiceCard.loadChart();
    
    },

    loadChart: function () {
        var numberServiceCard = $('#number_service_card').val();
        var time = $('#time').val();
        var branch = $('#branch').val();
        var service_card_id = $('#service_card_id option:selected').val();
        $('#time_detail').val(time);
        $('#branch_detail').val(branch);
        $('#service_card_id_detail').val(service_card_id);

        //Load filter export tổng
        $('#export_time_total').val(time);
        $('#export_branch_total').val(branch);
        $('#export_service_card_id_total').val(service_card_id);
        //Load filter export chi tiết
        $('#export_time_detail').val(time);
        $('#export_branch_detail').val(branch);
        $('#export_service_card_id_detail').val(service_card_id);
        
        $.ajax({
            url: laroute.route('admin.report-revenue.service-card.filter'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                numberServiceCard: numberServiceCard,
                serviceCardId: service_card_id,
            },
            success: function (res) {
                if (res.countListObject > 10) {
                    $('#container').height(res.countListObject * 50);
                }
                chart(res.arrayCategories, res.dataSeries);
                $('#totalOrderPaySuccess').text(formatNumber(res.totalRevenue + revenueByServiceCard.jsonLang[" VNĐ"]));

                $('#number_service_card_detail').val(JSON.stringify(res.arrServiceCard));
                $('#export_number_service_card_detail').val(JSON.stringify(res.arrServiceCard));
                $('#export_number_service_card_total').val(JSON.stringify(res.arrServiceCard));
                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-revenue.service-card.list-detail')
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
                    text: revenueByServiceCard.jsonLang['Số tiền (VNĐ)'],
                    align: 'high'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + revenueByServiceCard.jsonLang[' VNĐ'] + '</b></td></tr>',
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
                name: revenueByServiceCard.jsonLang[' Số tiền (VNĐ)'],
                data: dataSeries
            }]
        });
    
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}