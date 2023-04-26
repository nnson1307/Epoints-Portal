var index = {
    _init:function () {
        loadChart();
    }
}
function loadChart() {
    $.ajax({
        url: laroute.route('message-attribute-other.load-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
        },
        success: function (res) {
            totalMessageOther(res.total_message_other);
        }
    });
}
//Chart Message Attribute Other
function totalMessageOther(total_message_other) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(function () {
        chartMessageOther(total_message_other);
    });
}
function chartMessageOther(data_chart) {

    var arr1 = [];
    var arr2 = [];
    var arr3 = [];
    $.map(data_chart, function (data) {
        arr1.push(data[0]);
        arr3.push(data[0]);
        arr2.push(data[1]);
    });
    var barGraph = Highcharts.chart('chart_attribute_other', {
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
                maxWidth: 200
            },
             lineWidth: 3,
        },
        colors: ['#d70a14'],
        tooltip: {
            split: true
        },
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
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
    });
}
