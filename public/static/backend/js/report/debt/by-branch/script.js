var debtByBranch = {
    jsonLang: null,
    _init: function () {
        debtByBranch.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[debtByBranch.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[debtByBranch.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[debtByBranch.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[debtByBranch.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[debtByBranch.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[debtByBranch.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": debtByBranch.jsonLang["Đồng ý"],
                "cancelLabel": debtByBranch.jsonLang["Thoát"],
                "customRangeLabel": debtByBranch.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    debtByBranch.jsonLang["CN"],
                    debtByBranch.jsonLang["T2"],
                    debtByBranch.jsonLang["T3"],
                    debtByBranch.jsonLang["T4"],
                    debtByBranch.jsonLang["T5"],
                    debtByBranch.jsonLang["T6"],
                    debtByBranch.jsonLang["T7"]
                ],
                "monthNames": [
                    debtByBranch.jsonLang["Tháng 1 năm"],
                    debtByBranch.jsonLang["Tháng 2 năm"],
                    debtByBranch.jsonLang["Tháng 3 năm"],
                    debtByBranch.jsonLang["Tháng 4 năm"],
                    debtByBranch.jsonLang["Tháng 5 năm"],
                    debtByBranch.jsonLang["Tháng 6 năm"],
                    debtByBranch.jsonLang["Tháng 7 năm"],
                    debtByBranch.jsonLang["Tháng 8 năm"],
                    debtByBranch.jsonLang["Tháng 9 năm"],
                    debtByBranch.jsonLang["Tháng 10 năm"],
                    debtByBranch.jsonLang["Tháng 11 năm"],
                    debtByBranch.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            loadChart();
        });
        loadChart();
    
    }
}

function loadChart() {
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
    
    $.ajax({
        url: laroute.route('admin.report-debt-branch.load-chart'),
        dataType: 'JSON',
        method: 'POST',
        data: {
            branch: $('#branch').val(),
            time: $('#time').val()
        },
        success: function (res) {
            chart(res.arrayCategories, res.dataSeries);
            $('#amount-debt-money').text(formatNumber(res.amountAll.toFixed(decimal_number)) + debtByBranch.jsonLang[" VNĐ"]);
            $('#total-debt').text(res.totalAll);
            $('#amount-debt-paid').text(formatNumber(res.amountPaid.toFixed(decimal_number)) + debtByBranch.jsonLang[" VNĐ"]);
            $('#total-debt-paid').text(res.totalPaid);
            $('#amount-debt-unpaid').text(formatNumber(res.amountUnPaid.toFixed(decimal_number)) + debtByBranch.jsonLang[" VNĐ"]);
            $('#total-debt-unpaid').text(res.totalUnPaid);

            $('#autotable').PioTable({
                baseUrl: laroute.route('admin.report-debt-branch.list-detail')
            });
            $('.btn-search').trigger('click');
        }
    });

}

//Biểu đồ.
function chart(arrayCategories, dataSeries) {
    
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
        colors: ['#ee3b42', '#2f7ed8'],
        xAxis: {
            categories: arrayCategories
        },
        yAxis: {
            title: {
                text: debtByBranch.jsonLang['Số tiền (VNĐ)']
            },
            min: 0
        },
        exporting: {enabled: false},
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:,.2f}' + debtByBranch.jsonLang[' VNĐ'] + '</b></td></tr>',
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
        series: dataSeries
    });

}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}