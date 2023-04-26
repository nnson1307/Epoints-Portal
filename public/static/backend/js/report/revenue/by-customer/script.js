var revenueByCustomer = {
    jsonLang: null,
    _init: function () {
        revenueByCustomer.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[revenueByCustomer.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[revenueByCustomer.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[revenueByCustomer.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[revenueByCustomer.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[revenueByCustomer.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[revenueByCustomer.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": revenueByCustomer.jsonLang["Đồng ý"],
                "cancelLabel": revenueByCustomer.jsonLang["Thoát"],
                "customRangeLabel": revenueByCustomer.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    revenueByCustomer.jsonLang["CN"],
                    revenueByCustomer.jsonLang["T2"],
                    revenueByCustomer.jsonLang["T3"],
                    revenueByCustomer.jsonLang["T4"],
                    revenueByCustomer.jsonLang["T5"],
                    revenueByCustomer.jsonLang["T6"],
                    revenueByCustomer.jsonLang["T7"]
                ],
                "monthNames": [
                    revenueByCustomer.jsonLang["Tháng 1 năm"],
                    revenueByCustomer.jsonLang["Tháng 2 năm"],
                    revenueByCustomer.jsonLang["Tháng 3 năm"],
                    revenueByCustomer.jsonLang["Tháng 4 năm"],
                    revenueByCustomer.jsonLang["Tháng 5 năm"],
                    revenueByCustomer.jsonLang["Tháng 6 năm"],
                    revenueByCustomer.jsonLang["Tháng 7 năm"],
                    revenueByCustomer.jsonLang["Tháng 8 năm"],
                    revenueByCustomer.jsonLang["Tháng 9 năm"],
                    revenueByCustomer.jsonLang["Tháng 10 năm"],
                    revenueByCustomer.jsonLang["Tháng 11 năm"],
                    revenueByCustomer.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            revenueByCustomer.loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            revenueByCustomer.loadChart();
        });
        $('#number_customer').select2().on('select2:select', function (event) {
            revenueByCustomer.loadChart();
        });
        $('#customer_id').select2().on('select2:select', function (event) {
            revenueByCustomer.loadChart();
        });
        revenueByCustomer.loadChart();
    
    },

    loadChart: function () {
        var time = $('#time').val();
        var branch = $('#branch').val();
        var customer_id = $('#customer_id option:selected').val();
        var number_customer = $('#number_customer').val();
        $('#time_detail').val(time);
        $('#branch_detail').val(branch);
        $('#customer_id_detail').val(customer_id);

        //Load filter export tổng
        $('#export_time_total').val(time);
        $('#export_branch_total').val(branch);
        $('#export_customer_id_total').val(customer_id);
        //Load filter export chi tiết
        $('#export_time_detail').val(time);
        $('#export_branch_detail').val(branch);
        $('#export_customer_id_detail').val(customer_id);

        var numberCustomer = $('#number_customer').val();
        $.ajax({
            url: laroute.route('admin.report-revenue.customer.filter'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                numberCustomer: numberCustomer,
                customerId: customer_id
            },
            success:function (res) {
                if (res.countListCustomer > 5) {
                    $('#container').height(res.countListCustomer * 50);
                }
                chart(res.arrayCategories, res.dataSeries);
                revenueByCustomer.infoOrderAndMoney(res.total);
                $('#number_customer_detail').val(JSON.stringify(res.arrayCustomer));
                $('#export_number_customer_detail').val(JSON.stringify(res.arrayCustomer));
                $('#export_number_customer_total').val(JSON.stringify(res.arrayCustomer));
                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-revenue.customer.list-detail')
                });

                $('.btn-search').trigger('click');
            }
        });
    },

    // Các thông số tổng tiền, tổng đơn hàng
    infoOrderAndMoney: function (data) {
        $('#totalOrder').text(formatNumber(data['totalOrder'].toFixed(decimal_number)));
        $('#totalMoney').html(formatNumber(data['totalMoney'].toFixed(decimal_number)) + revenueByCustomer.jsonLang[" VNĐ"]);
        $('#totalOrderPaySuccess').text(formatNumber(data['totalOrderPaySuccess'].toFixed(decimal_number)));
        $('#totalMoneyOrderPaySuccess').text(formatNumber(data['totalMoneyOrderPaySuccess'].toFixed(decimal_number)) + revenueByCustomer.jsonLang[" VNĐ"]);
        $('#totalOrderNew').text(formatNumber(data['totalOrderNew'].toFixed(decimal_number)));
        $('#totalMoneyOrderNew').text(formatNumber(data['totalMoneyOrderNew'].toFixed(decimal_number)) + revenueByCustomer.jsonLang[" VNĐ"]);
    },
}
Highcharts.setOptions({
    lang: {
        decimalPoint: '.',
        thousandsSep: ','
    }
});
// Biểu đồ cột
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
            colors: ['#ee3b42', '#2f7ed8'],
            xAxis: {
                categories: arrayCategories ,
            },
            yAxis: {
                min: 0,
                title: {
                    text: revenueByCustomer.jsonLang['Số tiền (VNĐ)'],
                    align: 'high'
                }
            },
            tooltip: {
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + revenueByCustomer.jsonLang[' VNĐ'] + '</b></td></tr>',
                footerFormat: '</table>',
            },
            exporting: false,
            plotOptions: {
                series: {
                    stacking: 'normal'
                },
                bar: {
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            if (this.point.y == 0) {
                                return '';
                            } else {
                                return formatNumber(this.point.y);
                            }
                        }
                    }
                }
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: dataSeries
        });
    
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}