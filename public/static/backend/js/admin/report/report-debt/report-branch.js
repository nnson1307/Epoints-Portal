$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {

        //
        var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

        $("#time").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            // buttonClasses: "m-btn btn",
            // applyClass: "btn-primary",
            // cancelClass: "btn-danger",

            maxDate: moment().endOf("day"),
            startDate: moment().startOf("day"),
            endDate: moment().add(1, 'days'),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                // "applyLabel": "Đồng ý",
                // "cancelLabel": "Thoát",
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
            ranges: arrRange,
        }).on('apply.daterangepicker', function (ev) {
            loadChart();
        });
    });
    

    $('#branch').select2().on('select2:select', function (event) {
        loadChart();
    });

    loadChart();

});

function loadChart() {
    mApp.block(".load_ajax", {
        overlayColor: "#000000",
        type: "loader",
        state: "success",
        message: json["Đang tải..."],
    });
    $.ajax({
        url:laroute.route('admin.report-debt-branch.load-chart'),
        dataType:'JSON',
        method:'POST',
        data:{
            branch: $('#branch').val(),
            time: $('#time').val()
        },
        success:function (res) {
            mApp.unblock(".load_ajax");
            chart(res.branch_name, res.amount_branch);
            $('#amount-debt-money').text(formatNumber(res.amount_all));
            $('#total-debt').text(res.total_all);
            $('#amount-debt-paid').text(formatNumber(res.amount_paid));
            $('#total-debt-paid').text(res.total_paid);
            $('#amount-debt-unpaid').text(formatNumber(res.amount_unpaid));
            $('#total-debt-unpaid').text(res.total_unpaid);
            $('#amount-cancel').text(formatNumber(res.amount_cancel));
            $('#total-cancel').text(res.total_cancel);
        }
    });
}

//Biểu đồ.
function chart(name, data) {
    $.getJSON(laroute.route('translate'), function (json) {
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
        xAxis: {
            categories: name
        },
        yAxis: {
            title: {
                text: json['Số tiền (VNĐ)']
            },
            min: 0
        },
        exporting: {enabled: false},
        tooltip: {
            headerFormat: '',
            pointFormat: '<tr><td style="color:#ffffff;padding:0"> </td>' +
                '<td style="padding:0"><b style="color:#000000;">{point.y} VNĐ</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            bar: {
                dataLabels: {
                    enabled: true
                }
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
        series: [{
            showInLegend: false,
            name: '',
            data: data
        }]
    });
});
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

// Chọn ngày.
$('#time').on('apply.daterangepicker', function (ev, picker) {
    var start = picker.startDate.format("DD/MM/YYYY");
    var end = picker.endDate.format("DD/MM/YYYY");
    $(this).val(start + " - " + end);
    loadChart();
});