var contractCareReport = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Trong năm"]] = [moment().startOf("year"), moment().endOf("year")];
            $("#time").daterangepicker({
                autoApply: true,
                maxDate: moment().endOf("day"),
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
                contractCareReport.loadChart();
            });
            $('#branch_id').select2();
            $('#branch_id').trigger('change');
            $('#department_id').select2();
            $('#staff_id').select2();
        });
    },

    chartContractCare: function(dataSeries, arrayCategories) {
        $.getJSON(laroute.route('translate'), function (json) {
            // Create the chart
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: arrayCategories,
                },
                yAxis:
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
                series: dataSeries
            });

            $('#container').removeAttr('style');
        });
    },
    loadChart: function () {
        var data = [];
        $.getJSON(laroute.route('translate'), function (json) {
            var time = $('#time').val();
            var staff_id = $('#staff_id option:selected').val();
            var department_id = $('#department_id option:selected').val();
            var branch_id = $('#branch_id option:selected').val();
            $.ajax({
                url: laroute.route('contract.report.contract-care.filter'),
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
                    contractCareReport.chartContractCare(res.dataCategories, res.arrayCategories);
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
                contractCareReport.loadChart();
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
                contractCareReport.loadChart();
            }
        });
    });
})
$('#staff_id').on('change', function(e){
    e.preventDefault();
    contractCareReport.loadChart();
})