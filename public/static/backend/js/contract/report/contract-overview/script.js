var contractOverviewReport = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Trong năm"]] = [moment().startOf("year"), moment().endOf("year")];
            $("#time").daterangepicker({
                autoApply: true,
                // maxDate: moment().endOf("day"),
                startDate: moment().startOf('year').format('DD/MM/YYYY'),
                endDate: moment().endOf('year').format('DD/MM/YYYY'),
                locale: {
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
                contractOverviewReport.loadChart();
            });
            $('#branch_id').select2();
            $('#branch_id').trigger('change');
            $('#department_id').select2();
            $('#staff_id').select2();
        });
    },

    chartContractCare: function(dataSeries, arrayCategories) {
        $.getJSON(laroute.route('translate'), function (json) {
            var chart = new Highcharts.Chart({

                chart: {
                    renderTo: 'container',
                    defaultSeriesType: 'column'
                },

                title: {
                    text: ''
                },

                xAxis: {
                    categories: arrayCategories
                },
                yAxis: [
                    { // Primary yAxis
                        labels: {
                            format: '{value}',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        },
                        title: {
                            text: '',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        }
                    },
                    { // Secondary yAxis
                        title: {
                            text: '',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        labels: {
                            format: '{value} VNĐ',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        opposite: true
                    }
                ],

                tooltip: {
                    formatter: function () {
                        return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y + '<br/>';
                    }
                },

                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: dataSeries
            });

            $('#container').removeAttr('style');
        });
    },
    loadChart: function () {
        var data = [];
        // contractOverviewReport.chartContractCare([],[]);
        $.getJSON(laroute.route('translate'), function (json) {
            var time = $('#time').val();
            var staff_id = $('#staff_id option:selected').val();
            var department_id = $('#department_id option:selected').val();
            var branch_id = $('#branch_id option:selected').val();
            $.ajax({
                url: laroute.route('contract.report.contract-overview.filter'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    time: time,
                    staff_id: staff_id,
                    department_id: department_id,
                    branch_id: branch_id,
                },
                success:function (res) {
                    var data = [];
                    $('#countTotalContract').text(formatNumber(res.countTotalContract.toFixed(0)));
                    $('#countValidated').text(formatNumber(res.countValidated.toFixed(0)));
                    $('#countLiquidated').text(formatNumber(res.countLiquidated.toFixed(0)));
                    $('#countWaitingLiquidation').text(formatNumber(res.countWaitingLiquidation.toFixed(0)));
                    $('#amountTotalContract').text(formatNumber(res.amountTotalContract.toFixed(decimal_number)));
                    $('#amountValidated').text(formatNumber(res.amountValidated.toFixed(decimal_number)));
                    $('#amountLiquidated').text(formatNumber(res.amountLiquidated.toFixed(decimal_number)));
                    $('#amountWaitingLiquidation').text(formatNumber(res.amountWaitingLiquidation.toFixed(decimal_number)));
                    contractOverviewReport.chartContractCare(res.dataChart.dataCategories, res.dataChart.arrayCategories);
                }
            });
        });
    },
}
Highcharts.setOptions({
    lang: {
        decimalPoint: '.',
        thousandsSep: ','
    },
    exporting: false
});
//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$('#department_id').on('change', function(e){
    $.getJSON(laroute.route('translate'), function (json) {
        e.preventDefault();
        $.ajax({
            url: laroute.route('contract.report.contract-care.load-staff'),
            dataType: 'JSON',
            data: {
                department_id: $('#department_id option:selected').val(),
                branch_id: $('#branch_id option:selected').val(),
            },
            method: 'POST',
            success: function (res) {
                $('#staff_id').empty();
                $('#staff_id').append('<option value="">' + json['Tất cả nhân viên'] + '</option>');
                $.map(res.optionStaffs, function (a) {
                    $('#staff_id').append('<option value="' + a.staff_id + '">' + a.full_name + '</option>');
                });
                contractOverviewReport.loadChart();
            }
        });
    });
})
$('#branch_id').on('change', function(e){
    $.getJSON(laroute.route('translate'), function (json) {
        e.preventDefault();
        $.ajax({
            url: laroute.route('contract.report.contract-care.load-department'),
            dataType: 'JSON',
            data: {
                branch_id: $('#branch_id option:selected').val(),
            },
            method: 'POST',
            success: function (res) {
                $('#department_id').empty();
                $('#department_id').append('<option value="">' + json['Tất cả phòng ban'] + '</option>');
                $.map(res.optionDepartment, function (a) {
                    $('#department_id').append('<option value="' + a.department_id + '">' + a.department_name + '</option>');
                });
                contractOverviewReport.loadChart();
            }
        });
    });
})
$('#staff_id').on('change', function(e){
    e.preventDefault();
    contractOverviewReport.loadChart();
})