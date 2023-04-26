var over_view = {
    _init: function () {
        $('.range-picker').daterangepicker({
            locale: {
                format: 'D/M/Y'
            }
        }).on('apply.daterangepicker', function (ev, picker) {
            //do something, like clearing an input
            loadChart();
            $('#date_user').val($('#date-range').val());
            $('#date_message').val($('#date-range').val());
        });
        $('.range-picker').val('');
        loadChart();
    },
    change_brand:function () {
        $.ajax({
            url: laroute.route('dashboard.chart-month'),
            method:'POST',
            dataType:'JSON',
            data:{
                date_range: $('#date-range').val(),
                brand: $('#brand').val()
            },
            success:function (res) {
                totalMessageMonth(res.total_message_month, res.brand_name);
            }
        });
    }
};

function loadChart() {
    $.ajax({
        url: laroute.route('dashboard.load-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            date_range: $('#date-range').val(),
            brand: $('#brand').val()
        },
        success: function (res) {
            $('.total_user').text(res.total_user);
            $('.total_message').text(res.total_message);
            totalMessageBrandChart(res.total_message_brand);
            totalMessageCompletion(res.total_message_attribute);
            totalMessageConfusion(res.total_message_not_response);
            totalMessageMonth(res.total_message_month, res.brand_name);
            totalMessageScale(res.total_message_confusion, res.total_message_target);
            $('.average').text(parseFloat(Math.round(res.total_message) / res.total_message_target).toFixed(2));
            keyword(res.key_word);
        }
    });
}

//Chart total message brand
function totalMessageBrandChart(total_message_brand) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function () {
        chartBrand(total_message_brand);
    });
}
/// chart brand
function chartBrand(data_chart) {
    var base_url_home = location.protocol + "//" + location.hostname + (location.port && ":" + location.port) + "/";
    var countries = [{
        name: 'MILO',
        flag: 197582,
        color: '#d70a14'
    }, {
        name: 'NAN',
        flag: 197604,
        color: '#d70a14'
    }, {
        name: 'Sữa Nước Nestlé',
        flag: 197507,
        color: '#d70a14'
    }, {
        name: 'NBC',
        flag: 197571,
        color: '#d70a14'
    }, {
        name: 'CERELAC',
        flag: 197408,
        color: '#d70a14'
    }, {
        name: 'MAGGI',
        flag: 197375,
        color: '#d70a14'
    },
    {
        name: 'Nutren Junior',
        flag: 20200914,
        color: '#d70a14'
    }
    ];

    function getData(data) {

        return data.map(function (country, i) {
            if(typeof countries[i] != 'undefined'){
                return {
                    name: country[0],
                    y: country[1],
                    color: countries[i].color
                };
            }

        });
    }

    var chart = Highcharts.chart('total_message_brand', {
        chart: {
            type: 'column',
            plotBackgroundColor: '#d9d9d9'
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 0
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            shared: true,
            headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
        },
        title: {
            text: null,
        },
        xAxis: {
            type: 'category',
            alternateGridColor: '#ffffff',
            // color var x  alternateGridColor
            max: data_chart.length -1,
            labels: {
                useHTML: true,
                animate: true,
                formatter: function () {
                    var value = this.value,
                        output;

                    countries.forEach(function (country) {
                        if (country.name === value) {
                            output = country.flag;
                        }
                    });

                    return '<span><img src="'+ base_url_home +'admin/static/admin/images/' + output + '.png" style="text-align: center"/><br></span>';
                }
            }
        },
        yAxis: [{
            showFirstLabel: false,
            title: {
                text: '',
                rotation: 0,
                margin: 0,
            }
        }],
        series: [{
             name: '',
            id: 'main',
            dataSorting: {
                enabled: false,
                matchByName: false
            },
            dataLabels: [{
                enabled: true,
                format: '{point.y:.1f}',
                style: {
                    fontSize: '12px',
                    color:'black',
                }
            }],
            data: getData(data_chart).slice()
        }],
        exporting: {
            allowHTML: true,
            enabled: false
        }
    });
}
// chart brand
//Chart total message attribute
function totalMessageCompletion(total_message_attribute) {
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(function () {
        chartMessageCompletion(total_message_attribute);
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
        height: 315,
        chartArea: {
            // leave room for y-axis labels
            width: '40%',
            height:'100%',
        },
        // backgroundColor: '#f0f0f0',
        colors: [
            '#d70a14',
        ],
    };
    var chart = new google.visualization.BarChart(document.getElementById("total_message_attribute"));
    chart.draw(view, options);
}

//Chart total message not response
function totalMessageConfusion(total_message_not_response) {

    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(function () {
        chartMessageConfusion(total_message_not_response);
    });
}
function chartMessageConfusion(data_chart) {
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
        height: '300',
        chartArea: {
            // leave room for y-axis labels
            width: '40%',
            height:'100%',
        },
        backgroundColor: '#e7e7e7',
        colors: [
            '#ff2732',
        ],
    };
    var chart = new google.visualization.BarChart(document.getElementById("total_message_not_response"));
    chart.draw(view, options);
}

//Chart total message month
function totalMessageMonth(total_message_month, brand) {
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(function () {
        chartMessageMonth(total_message_month, brand);
    });
}

function chartMessageMonth(data_chart, brand) {
   //console.log(data_chart);
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'X');
    data.addColumn('number', 'All');
    if (brand != '' && brand != null) {
        data.addColumn('number', brand);
    }
    data.addRows(data_chart);
    var options = {
        // width: 500,
        chartArea: {
            // leave room for y-axis labels
            width: '85%'
        },
        backgroundColor: '#f0f0f0',
        hAxis: {
            title: ''
        },
        height:300,
        vAxis: {
            title: ''
        },
        legend: 'top'
    };

    var chart = new google.visualization.LineChart(document.getElementById('total_message_month'));
    chart.draw(data, options);
}

//Chart donut total message chia theo tỉ lệ
function totalMessageScale(total_message, total_message_target) {
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(function () {
        chartDonut(total_message, total_message_target);
    });
}

function chartDonut(message_confusion, total_message_target) {
    var pieColors = (function () {
        var colors = [ '#d70a14', '#535353']
        return colors;
    }());

// Build the chart
    Highcharts.chart('total_message_scale', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
            height: 330,
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                colors: pieColors,
                dataLabels: {
                    enabled: true,
                    format: '<br>{point.percentage:.1f} %',
                     distance: -70,
                    style: {
                        fontWeight: 'bold',
                        fontSize:'2rem',
                        color:'#ffffff',
                    },

                    filter: {
                        property: 'percentage',
                        operator: '>',
                        value: 4
                    }
                }
            }
        },
        exporting: {
            allowHTML: true,
            enabled: false
        },
        series: [{
            name: '',
            data: [
                { name: 'Message Completion', y: total_message_target },
                { name: 'Message Confusion', y: message_confusion },
            ]
        }]
    });
}

//Làm tròn số
function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

//Chart KeyWord
function keyword(key) {
    // var data = key.slice(1,30);
    var data=key;
    Highcharts.chart('cloud', {
        chart: {
            height: 330,
        },
        accessibility: {
            screenReaderSection: {
                beforeChartFormat: '<h5>{chartTitle}</h5>' +
                    '<div>{chartSubtitle}</div>' +
                    '<div>{chartLongdesc}</div>' +
                    '<div>{viewTableButton}</div>'
            }
        },
        exporting: {
            allowHTML: true,
            enabled: false
        },
        series: [{
            type: 'wordcloud',
            data: data,
            name: '',
            fontFamily: 'roboto',
        }],
        title: {
            text: ''
        },

    });
}


