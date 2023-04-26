var contractRevenueReport = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#created_at").daterangepicker({
                autoApply: true,
                maxDate: moment().endOf("day"),
                startDate: moment().startOf('month').format('DD/MM/YYYY'),
                endDate: moment().endOf('month').format('DD/MM/YYYY'),
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
            });
            $('#contract_category_id').select2();
            $('#status_code').select2();
            $('#chart_contract_category_id').select2();
            $('#contract_category_id').change(function () {
                $.ajax({
                    url: laroute.route('contract.contract.load-status'),
                    dataType: 'JSON',
                    data: {
                        contract_category_id: $('#contract_category_id').val(),
                    },
                    method: 'POST',
                    global: false,
                    success: function (res) {
                        $('#status_code').empty();
                        $('#status_code').append('<option value="">' + json['Chọn trạng thái'] + '</option>');
                        $.map(res.optionStatus, function (a) {
                            $('#status_code').append('<option value="' + a.status_code + '">' + a.status_name + '</option>');
                        });
                        $('#status_code').trigger('change');
                    }
                });
            });

            $('.btn-search').trigger('click');
            $('#chart_contract_category_id').trigger('change');
        });
    },
    export: function () {
        var params = {
            created_at: $('[name="created_at"]').val(),
            contract_category_id: $('[name="contract_category_id"]').val(),
            status_code: $('[name="status_code"]').val(),
        };
        var url = laroute.route('contract.report.contract-revenue.export') + '?' + $.param(params);
        window.location = url;

    },
    chartContractCare: function(dataSeries, arrayCategories) {
        $.getJSON(laroute.route('translate'), function (json) {
            // Create the chart
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: json['TỔNG GIÁ TRỊ HỢP ĐỒNG THEO CÁC THÁNG']
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
                credits: {
                    enabled: false
                },
                series: dataSeries
            });

            $('#container').removeAttr('style');
        });
    },
    loadChart: function () {
        var data = [];
        $.getJSON(laroute.route('translate'), function (json) {
            var contract_category_id = $('#chart_contract_category_id').val();
            $.ajax({
                url: laroute.route('contract.report.contract-revenue.filter'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    contract_category_id: contract_category_id,
                },
                success:function (res) {
                    var data = [];
                    contractRevenueReport.chartContractCare(res.dataCategories, res.arrayCategories);
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
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#autotable').PioTable({
    baseUrl: laroute.route('contract.report.contract-revenue.list')
});

$('#chart_contract_category_id').on('change', function(e){
    contractRevenueReport.loadChart();
});