var revenueByBranch = {
    jsonLang: null,
    _init: function() {
        revenueByBranch.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[revenueByBranch.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[revenueByBranch.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[revenueByBranch.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[revenueByBranch.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[revenueByBranch.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[revenueByBranch.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $("#date_revenue_branch").daterangepicker({
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
                "applyLabel": revenueByBranch.jsonLang["Đồng ý"],
                "cancelLabel": revenueByBranch.jsonLang["Thoát"],
                "customRangeLabel": revenueByBranch.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    revenueByBranch.jsonLang["CN"],
                    revenueByBranch.jsonLang["T2"],
                    revenueByBranch.jsonLang["T3"],
                    revenueByBranch.jsonLang["T4"],
                    revenueByBranch.jsonLang["T5"],
                    revenueByBranch.jsonLang["T6"],
                    revenueByBranch.jsonLang["T7"]
                ],
                "monthNames": [
                    revenueByBranch.jsonLang["Tháng 1 năm"],
                    revenueByBranch.jsonLang["Tháng 2 năm"],
                    revenueByBranch.jsonLang["Tháng 3 năm"],
                    revenueByBranch.jsonLang["Tháng 4 năm"],
                    revenueByBranch.jsonLang["Tháng 5 năm"],
                    revenueByBranch.jsonLang["Tháng 6 năm"],
                    revenueByBranch.jsonLang["Tháng 7 năm"],
                    revenueByBranch.jsonLang["Tháng 8 năm"],
                    revenueByBranch.jsonLang["Tháng 9 năm"],
                    revenueByBranch.jsonLang["Tháng 10 năm"],
                    revenueByBranch.jsonLang["Tháng 11 năm"],
                    revenueByBranch.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function(event) {
            revenueByBranch.loadChart();
        });
        $('#branch').select2().on('select2:select', function(event) {
            revenueByBranch.loadChart();
        });
        $('#customer_group').select2().on('select2:select', function(event) {
            revenueByBranch.loadChart();
        });
        revenueByBranch.loadChart();
    
    },

    loadChart: function() {
        $.ajax({
            url: laroute.route('admin.report-revenue.branch.filter'),
            method: 'POST',
            dataType: 'JSON',
            global: false,
            data: {
                time: $('#date_revenue_branch').val(),
                branch:  $('#branch').val(),
                customerGroup: $('#customer_group').val(),
            },
            success: function(res) {
                chart(res.dataSeries, res.arrayCategories);
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
// Biểu đồ cột
function chart(dataSeries, arrayCategories) {
    
        // Create the chart
        $('#branch_container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            colors: ['#ee3b42', '#2f7ed8'],
            xAxis: {
                categories: arrayCategories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: revenueByBranch.jsonLang['Số tiền (VNĐ)']
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + revenueByBranch.jsonLang[' VNĐ'] + '</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: dataSeries
        });
    
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}