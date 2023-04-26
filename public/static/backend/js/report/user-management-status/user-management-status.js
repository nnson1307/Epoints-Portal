var index = {
    _init: function () {
        $('.range-picker').daterangepicker({
            locale: {
                format: 'D/M/Y'
            },
            // "autoApply": true,
        }).on('apply.daterangepicker', function (ev, picker) {
            //do something, like clearing an input
            loadChart();
        });
        $('.range-picker').val('');
        loadChart();
    },
};

function loadChart() {
    $.ajax({
        url: laroute.route('user-management-status.load-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            date_range: $('#date-range').val(),
        },
        success: function (res) {
            totalOnOffBot(res.total_on_off_bot);
            totalClickLink(res.total_user_click_link);
            totalUniqueUserClickLink(res.total_unique_user_click_link);
        }
    });
}

//Chart On Off Bot
function totalOnOffBot(total_on_off_bot) {
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(function () {
        chartOnOffBot(total_on_off_bot);
    });
}

function chartOnOffBot(data_chart) {
    console.log(data_chart);
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'X');
    data.addColumn('number', 'On Bot');
    data.addColumn('number', 'Off Bot');
    data.addRows(data_chart);
    var options = {
        hAxis: {
            title: ''
        },
        vAxis: {
            title: ''
        },
        legend: 'top',
        chartArea: {
            // leave room for y-axis labels
            width: '90%',
            height:'300',
        },
        height: '370',
        backgroundColor: '#f0f0f0',
    };

    var chart = new google.visualization.LineChart(document.getElementById('chart-time-line'));
    chart.draw(data, options);
}
//Chart Total User Click Link
function totalClickLink(total_click_link) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(function () {
        chartTotalClickLink(total_click_link);
    });
}
function chartTotalClickLink(data_chart) {
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
        title: "Total User Click Link",
        bar: {groupWidth: "50%"},
        legend: {position: "none"},
        chartArea: {width: '100%'},
        // height: 600,
    };
    var chart = new google.visualization.BarChart(document.getElementById("chart-total-click-link"));
    chart.draw(view, options);
}
//Chart Total Unique User Click Link
function totalUniqueUserClickLink(total_unique_user_click_link) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(function () {
        chartTotalUniqueUserClickLink(total_unique_user_click_link);
    });
}
function chartTotalUniqueUserClickLink(data_chart) {
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
        title: "Total Unique User Click Link",
        bar: {groupWidth: "50%"},
        legend: {position: "none"},
        chartArea: {width: '100%'},
        // height: 600,
    };
    var chart = new google.visualization.BarChart(document.getElementById("chart-total-unique-user-click-link"));
    chart.draw(view, options);
}