var revenueByBranch = {
    jsonLang: null,
    _init: function () {
        revenueByBranch.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[revenueByBranch.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[revenueByBranch.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[revenueByBranch.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[revenueByBranch.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[revenueByBranch.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[revenueByBranch.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
        }).on('apply.daterangepicker', function (event) {
                revenueByBranch.loadChart();
            });
        $('#branch').select2().on('select2:select', function (event) {
            revenueByBranch.loadChart();
        });
        $('#customer_group').select2().on('select2:select', function (event) {
            revenueByBranch.loadChart();
        });
        revenueByBranch.loadChart();
    
    },

    loadChart: function () {
        var time = $('#time').val();
        var branch = $('#branch').val();
        var customer_group = $('#customer_group').val();
        $('#time_detail').val(time);
        $('#branch_detail').val(branch);
        $('#customer_group_detail').val(customer_group);

        //Load filter export tổng
        $('#export_time_total').val(time);
        $('#export_branch_total').val(branch);
        $('#export_customer_group_total').val(customer_group);
        //Load filter export chi tiết
        $('#export_time_detail').val(time);
        $('#export_branch_detail').val(branch);
        $('#export_customer_group_detail').val(customer_group);

        $.ajax({
            url: laroute.route('admin.report-revenue.branch.filter'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                customerGroup: $('#customer_group').val(),
            },
            success:function (res) {
                chart(res.dataSeries, res.arrayCategories);
                chartPaymentMethod(res.dataByReceiptType);
                revenueByBranch.infoOrderAndMoney(res.total);
                // revenueByBranch.dataInTable(res.dataList);
                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-revenue.branch.list-detail-branch')
                });

                $('.btn-search').trigger('click');
            }
        });
    },
    // Các thông số tổng tiền, tổng đơn hàng
    infoOrderAndMoney: function (data) {
        $('#totalOrder').text(formatNumber(data['totalOrder'].toFixed(decimal_number)));
            $('#totalMoney').html(formatNumber(data['totalMoney'].toFixed(decimal_number)) + revenueByBranch.jsonLang[" VNĐ"]);
            $('#totalOrderPaySuccess').text(formatNumber(data['totalOrderPaySuccess'].toFixed(decimal_number)));
            $('#totalMoneyOrderPaySuccess').text(formatNumber(data['totalMoneyOrderPaySuccess'].toFixed(decimal_number)) + revenueByBranch.jsonLang[" VNĐ"]);
            $('#totalOrderNew').text(formatNumber(data['totalOrderNew'].toFixed(decimal_number)));
            $('#totalMoneyOrderNew').text(formatNumber(data['totalMoneyOrderNew'].toFixed(decimal_number)) + revenueByBranch.jsonLang[" VNĐ"]);
    },

    // Các thông số trong table
    dataInTable: function (data) {
        $('#branch-table').find('tbody').empty();
        $.each(data, function (index, value) {
            console.log(index, value);
            $('#branch-table').find('tbody').append(
                "<tr>" +
                "<td>" + value.branchName + "</td>" +
                "<td>" + value.totalOrder + "</td>" +
                "<td>" + value.totalMoney.toFixed(2) + "</td>" +
                "<td>" + value.totalMoneyDiscount + "</td>" +
                "<td>" + value.totalMoneyShip + "</td>" +
                "</tr>"
            );
        });
    }
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
        $('#container').highcharts({
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
            exporting: false,
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

// Biều đồ tròn: theo phương thức thanh toán
function chartPaymentMethod(data){
   
    $('#chart-payment-method').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: revenueByBranch.jsonLang['Báo cáo doanh thu chi nhánh theo phương thức thanh toán']
        },
        colors: ['#2f7ed8', '#0d233a', '#8bbc21', '#910000', '#1aadce',
            '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a'],
        accessibility: {
            announceNewData: {
                enabled: true
            },
            point: {
                valueSuffix: '%'
            }
        },
        exporting: false,
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.2f}%'
                }
            },
            pie: {
                showInLegend: true
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.sum_type}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },
        series: [
            {
                name: "Browsers",
                colorByPoint: true,
                data: data
            }
        ]
    });

}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
