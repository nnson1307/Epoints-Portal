var revenueByStaff = {
    jsonLang: null,
    _init: function () {
        revenueByStaff.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[revenueByStaff.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[revenueByStaff.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[revenueByStaff.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[revenueByStaff.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[revenueByStaff.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[revenueByStaff.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": revenueByStaff.jsonLang["Đồng ý"],
                "cancelLabel": revenueByStaff.jsonLang["Thoát"],
                "customRangeLabel": revenueByStaff.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    revenueByStaff.jsonLang["CN"],
                    revenueByStaff.jsonLang["T2"],
                    revenueByStaff.jsonLang["T3"],
                    revenueByStaff.jsonLang["T4"],
                    revenueByStaff.jsonLang["T5"],
                    revenueByStaff.jsonLang["T6"],
                    revenueByStaff.jsonLang["T7"]
                ],
                "monthNames": [
                    revenueByStaff.jsonLang["Tháng 1 năm"],
                    revenueByStaff.jsonLang["Tháng 2 năm"],
                    revenueByStaff.jsonLang["Tháng 3 năm"],
                    revenueByStaff.jsonLang["Tháng 4 năm"],
                    revenueByStaff.jsonLang["Tháng 5 năm"],
                    revenueByStaff.jsonLang["Tháng 6 năm"],
                    revenueByStaff.jsonLang["Tháng 7 năm"],
                    revenueByStaff.jsonLang["Tháng 8 năm"],
                    revenueByStaff.jsonLang["Tháng 9 năm"],
                    revenueByStaff.jsonLang["Tháng 10 năm"],
                    revenueByStaff.jsonLang["Tháng 11 năm"],
                    revenueByStaff.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            revenueByStaff.loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            revenueByStaff.loadChart();
        });
        $('#number_staff').select2().on('select2:select', function (event) {
            revenueByStaff.loadChart();
        });
        $('#staff_id').select2().on('select2:select', function (event) {
            revenueByStaff.loadChart();
        });
        revenueByStaff.loadChart();
    
    },

    loadChart: function () {
        var time = $('#time').val();
        var branch = $('#branch').val();
        var numberStaff = $('#number_staff').val();
        var staff_id = $('#staff_id option:selected').val();
        $('#time_detail').val(time);
        $('#branch_detail').val(branch);
        $('#staff_id_detail').val(staff_id);

        //Load filter export tổng
        $('#export_time_total').val(time);
        $('#export_branch_total').val(branch);
        $('#export_staff_id_total').val(staff_id);
        //Load filter export chi tiết
        $('#export_time_detail').val(time);
        $('#export_branch_detail').val(branch);
        $('#export_staff_id_detail').val(staff_id);
        $.ajax({
            url: laroute.route('admin.report-revenue.staff.filter'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                numberStaff: numberStaff,
                staffId: staff_id,
            },
            success:function (res) {
                if (res.countListStaff > 5) {
                    $('#container').height(res.countListStaff * 50);
                }
                chart(res.arrayCategories, res.dataSeries);
                revenueByStaff.infoOrderAndMoney(res.total);
                $('#number_staff_detail').val(JSON.stringify(res.arrayStaff));
                $('#export_number_staff_detail').val(JSON.stringify(res.arrayStaff));
                $('#export_number_staff_total').val(JSON.stringify(res.arrayStaff));
                $('#autotable').PioTable({
                    baseUrl: laroute.route('admin.report-revenue.staff.list-detail')
                });

                $('.btn-search').trigger('click');
            }
        });
    },

    // Các thông số tổng tiền, tổng đơn hàng
    infoOrderAndMoney: function (data) {
        $('#totalOrder').text(formatNumber(data['totalOrder'].toFixed(decimal_number)));
            $('#totalMoney').html(formatNumber(data['totalMoney'].toFixed(decimal_number)) + revenueByStaff.jsonLang[" VNĐ"]);
            $('#totalOrderPaySuccess').text(formatNumber(data['totalOrderPaySuccess'].toFixed(decimal_number)));
            $('#totalMoneyOrderPaySuccess').text(formatNumber(data['totalMoneyOrderPaySuccess'].toFixed(decimal_number)) + revenueByStaff.jsonLang[" VNĐ"]);
            $('#totalOrderNew').text(formatNumber(data['totalOrderNew'].toFixed(decimal_number)));
            $('#totalMoneyOrderNew').text(formatNumber(data['totalMoneyOrderNew'].toFixed(decimal_number)) + revenueByStaff.jsonLang[" VNĐ"]);
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
                    text: revenueByStaff.jsonLang['Số tiền (VNĐ)'],
                    align: 'high'
                }
            },
            tooltip: {
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + revenueByStaff.jsonLang[' VNĐ'] + '</b></td></tr>',
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