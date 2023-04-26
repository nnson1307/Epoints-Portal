var index = {
    _init:function () {
        $('.range-picker').daterangepicker({
            locale: {
                format: 'D/M/Y'
            },
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
        }).on('apply.daterangepicker', function (ev, picker) {
            loadChart();
            $('#date_user_time').val($('#date-range').val());
        });

        loadChart();
    }
}
function loadChart() {
    $.ajax({
        url: laroute.route('message-attribute.load-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            date_range: $('#date-range').val(),
        },
        success: function (res) {
            totalMessageNotResponse(res.total_message_not_response);
        }
    });
}
//Chart Message Attribute Not Response
function totalMessageNotResponse(total_message_not_response) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(function () {
        chartMessageNotResponse(total_message_not_response);
    });
}
function chartMessageNotResponse(data_chart) {
//Call the chart as a variable
    var arr1 = [];
    var arr2 = [];
    $.map(data_chart, function (data) {
        arr1.push(data[0]);
        arr2.push(data[1]);
    });
    var barGraph = Highcharts.chart('chart_attribute_not_response', {
        chart: {
            type: 'bar',
            plotBackgroundColor: '#ffffff',
            height: 1000,
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: arr1,
            staticScale: 50,
            alternateGridColor: '#d9d9d9',

        },
        yAxis: {
            showFirstLabel: false,
            title: {
                text: '',
                rotation: 0,
                margin: 0,
            }
        },
        colors: ['#d70a14'],
        series: [{
            showInLegend:false,
            //Call the data series variable from above
            data: arr2,
        }],

        exporting: {
            allowHTML: true,
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                }
            },
        }
    });

}