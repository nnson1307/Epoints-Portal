var dealCommission = {
    jsonLang: null,
    _init: function () {
        dealCommission.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        
        var arrRange = {};
        arrRange[dealCommission.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[dealCommission.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[dealCommission.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[dealCommission.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[dealCommission.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[dealCommission.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $("#time").daterangepicker({
            autoApply: true,
            maxDate: moment().endOf("day"),
            startDate: moment().subtract(6, "days"),
            endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": dealCommission.jsonLang["Đồng ý"],
                "cancelLabel": dealCommission.jsonLang["Thoát"],
                "customRangeLabel": dealCommission.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    dealCommission.jsonLang["CN"],
                    dealCommission.jsonLang["T2"],
                    dealCommission.jsonLang["T3"],
                    dealCommission.jsonLang["T4"],
                    dealCommission.jsonLang["T5"],
                    dealCommission.jsonLang["T6"],
                    dealCommission.jsonLang["T7"]
                ],
                "monthNames": [
                    dealCommission.jsonLang["Tháng 1 năm"],
                    dealCommission.jsonLang["Tháng 2 năm"],
                    dealCommission.jsonLang["Tháng 3 năm"],
                    dealCommission.jsonLang["Tháng 4 năm"],
                    dealCommission.jsonLang["Tháng 5 năm"],
                    dealCommission.jsonLang["Tháng 6 năm"],
                    dealCommission.jsonLang["Tháng 7 năm"],
                    dealCommission.jsonLang["Tháng 8 năm"],
                    dealCommission.jsonLang["Tháng 9 năm"],
                    dealCommission.jsonLang["Tháng 10 năm"],
                    dealCommission.jsonLang["Tháng 11 năm"],
                    dealCommission.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            loadChart();
        });
        $('#number_deal').select2().on('select2:select', function (event) {
            loadChart();
        });
        $('#deal_id').select2().on('select2:select', function (event) {
            loadChart();
        });
        loadChart();
    
    }
}

function loadChart() {
    var time = $('#time').val();
    var numberDeal = $('#number_deal').val();
    var deal_id = $('#deal_id option:selected').val();
    //Load input hidden export excel
    $('#time_export_detail').val(time);
    $('#time_export_total').val(time);
    $('#deal_id_export_total').val(deal_id);

    $('#time_detail').val(time);
    $('#deal_id_detail').val(deal_id);
    //Load filter export tổng
    $('#export_time_total').val(time);
    $('#export_deal_id_total').val(deal_id);
    //Load filter export chi tiết
    $('#export_time_detail').val(time);
    $('#export_deal_id_detail').val(deal_id);
    $.ajax({
        url: laroute.route('report.deal-commission.filter'),
        method: "POST",
        data: {
            time: time,
            numberDeal: numberDeal,
            dealId: deal_id,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.countListDeal > 5) {
                $('#container').height(res.countListDeal * 50);
            }
            chart(res.arrayCategories, res.dataSeries);
            $('#totalMoney').text(formatNumber(res.totalMoney.toFixed(decimal_number)) + dealCommission.jsonLang[" VNĐ"]);

            $('#number_deal_detail').val(JSON.stringify(res.arrDeal));
            $('#export_number_deal_detail').val(JSON.stringify(res.arrDeal));
            $('#export_number_deal_total').val(JSON.stringify(res.arrDeal));
            $('#autotable').PioTable({
                baseUrl: laroute.route('report.deal-commission.list-detail')
            });

            $('.btn-search').trigger('click');
        }
    });
}

//Biểu đồ.
function chart(arrayCategories, dataSeries) {
    
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
                text: dealCommission.jsonLang['Số tiền (VNĐ)'],
                align: 'high'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:,.'+ decimal_number +'f}' + dealCommission.jsonLang[' VNĐ'] + '</b></td></tr>',
            footerFormat: '</table>',
        },
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
            name: dealCommission.jsonLang['Số tiền (VNĐ)'],
            data: dataSeries
        }]
    });

}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}