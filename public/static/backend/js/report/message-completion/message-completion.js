var index = {
    _init:function () {
        $('.range-picker').daterangepicker({
            locale: {
                format: 'D/M/Y'
            },
        }).on('apply.daterangepicker', function (ev, picker) {
            loadChart();
            $('#date_user_time').val($('#date-range').val());
        });
        $('.range-picker').val('');
        loadChart();

    }
}
function loadChart() {
    $.ajax({
        url: laroute.route('message-completion.load-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            date_range: $('#date-range').val(),
        },
        success: function (res) {
            totalMessageCompletion(res.total_message_not_response);
        }
    });
}
//Chart Message Attribute Not Response
function totalMessageCompletion(total_message_not_response) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(function () {
        chartMessageCompletion(total_message_not_response);
    });
}
function chartMessageCompletion(data_chart) {
    var data_attr = [];
    data_attr = [
        ["Element", "Số lượng", {role: "style"}]
    ];
    $.map(data_chart, function (item) {
        data_attr.push(item);
    });
    var data = google.visualization.arrayToDataTable(data_attr);
    var view = new google.visualization.DataView(data);
    view.setColumns([0, 1,
        {
            calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation"
        },
        2]);

    var options = {
        title: "",
        bar: {groupWidth: "50%"},
        legend: {position: "none"},
        chartArea: {width: '80%'},
        height: 600,
        colors: [
            '#d70a14',
        ],
    };
    var chart = new google.visualization.BarChart(document.getElementById("chart_completion"));
    chart.draw(view, options);
}