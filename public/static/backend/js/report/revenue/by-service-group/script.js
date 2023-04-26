var revenueByServiceGroup = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                    "applyLabel": json["Đồng ý"],
                    "cancelLabel": json["Thoát"],
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            }).on('apply.daterangepicker', function (event) {
                revenueByServiceGroup.loadChart();
            });
            $('#branch').select2().on('select2:select', function (event) {
                revenueByServiceGroup.loadChart();
            });
            $('#service_category_id').select2().on('select2:select', function (event) {
                revenueByServiceGroup.loadChart();
            });
            revenueByServiceGroup.loadChart();
        });
    },

    loadChart: function () {
        var numberService = $('#number_service').val();
        var time = $('#time').val();
        var branch = $('#branch').val();
        var service_id = $('#service_category_id option:selected').val();
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
        $.getJSON(laroute.route('translate'), function (json) {
            $.ajax({
                url: laroute.route('admin.report-revenue.service-group.filter'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    time: $('#time').val(),
                    branch: $('#branch').val(),
                    numberService: numberService,
                    serviceCategoryId: service_id,
                },
                success: function (res) {
                    if (res.countListObject > 10) {
                        $('#container').height(res.countListObject * 50);
                    }
                    chart(res.arrayCategories, res.dataSeries);
                    $('#totalOrderPaySuccess').text(formatNumber(res.totalRevenue + json[" VNĐ"]));

                    $('#number_service_detail').val(JSON.stringify(res.arrService));
                    $('#export_number_service_detail').val(JSON.stringify(res.arrService));
                    $('#export_number_service_total').val(JSON.stringify(res.arrService));
                    $('#autotable').PioTable({
                        baseUrl: laroute.route('admin.report-revenue.service-group.list-detail')
                    });

                    $('.btn-search').trigger('click');
                }
            });
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
    $.getJSON(laroute.route('translate'), function (json) {
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
                    text: json['Số tiền (VNĐ)'],
                    align: 'high'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + json[' VNĐ'] + '</b></td></tr>',
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
                name: json['Số tiền (VNĐ)'],
                data: dataSeries
            }]
        });
    });
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}