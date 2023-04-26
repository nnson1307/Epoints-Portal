var index = {
    _init: function () {
        $('.range-picker').daterangepicker({
            locale: {
                format: 'D/M/Y'
            },
            // "autoApply": true,
        }).on('apply.daterangepicker', function (ev, picker) {
            loadChartStatus();
            $('#chart-time-line').val($('#date-range').val());

            //do something, like clearing an input
            loadChart();
            $('#date_user_time').val($('#date-range').val());
            $('#date_user_brand').val($('#date-range').val());
            $('#date_user_sku_brand').val($('#date-range').val());
            $('#date_user_attr_brand').val($('#date-range').val());
        });
        $('.range-picker').val('');
        loadChart();
        loadChartStatus();
    },
    change_brand_sku: function () {
        $.ajax({
            url: laroute.route('user-management.chart-sku'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                date_range: $('#date-range').val(),
                brand_sku: $('#brand_sku').val()
            },
            success: function (res) {
                totalUserSku(res.total_user_sku);
                $('#brand_sku_input').val($('#brand_sku').val());
            }
        });
    },
    change_brand_attr: function () {
        $.ajax({
            url: laroute.route('user-management.chart-attr'),
            method:'POST',
            dataType:'JSON',
            data:{
                date_range: $('#date-range').val(),
                brand_attr: $('#brand_attr').val()
            },
            success:function (res) {
                totalUserAttr(res.total_user_attr);
                $('#brand_attr_input').val($('#brand_attr').val());
            }
        });
    }
};

function loadChart() {
    $.ajax({
        url: laroute.route('user-management.load-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            date_range: $('#date-range').val(),
            brand_sku: $('#brand_sku').val(),
            brand_attr: $('#brand_attr').val()
        },
        success: function (res) {
            totalUserFollowChart(res.total_user_follow);
            totalUniqueUser(res.total_unique_user);
            totalUserSku(res.total_user_sku);
            totalUserAttr(res.total_user_attr);
            totalUniqueUserBrand(res.total_unique_user_brand);
        }
    });
}

function loadChartStatus() {
    $.ajax({
        url: laroute.route('user-management-status.load-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            date_range: $('#date-range').val(),
        },
        success: function (res) {
            totalOnOffBot(res.total_on_off_bot);
        }
    });
}

//Chart total user follow
function totalUserFollowChart(total_user_follow) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function () {
        chartFollow(total_user_follow);
    });
}

function chartFollow(data_chart) {
    // var data_follow = [];
    // data_follow = [
    //     ['Element', 'Số lượng', {role: 'style'}, {role: 'annotation'}]
    // ];
    // $.map(data_chart, function (item) {
    //     data_follow.push(item);
    // });
    // var data = google.visualization.arrayToDataTable(data_follow);
    //
    // var view = new google.visualization.DataView(data);
    // view.setColumns([0, 1,
    //     {
    //         calc: "stringify",
    //         sourceColumn: 1,
    //         type: "string",
    //         role: "annotation"
    //     },
    //     2]);
    // var options = {
    //    // title: "Số người quan tâm no.Brand",
    //     bar: {groupWidth: "70%"},
    //     legend: {position: "none"},
    //     hAxis: {
    //         title: 'Số lượng',
    //     },
    //     chartArea: {
    //         // leave room for y-axis labels
    //         width: '90%',
    //         height:'290px',
    //     },
    //     // backgroundColor: '#f0f0f0',
    //     colors: [
    //         '#ff2732',
    //     ],
    //     height: 300,
    // };
    // var chart = new google.visualization.ColumnChart(
    //     document.getElementById('total_user_follow'));
    // chart.draw(data, options);
    var arr1 = [];
    var arr2 = [];
    $.map(data_chart, function (data) {
        arr1.push(data[0]);
        arr2.push(data[1]);
    });
    //console.log(arr1);
    Highcharts.chart('total_user_follow', {
        chart: {
            type: 'column',
            plotBackgroundColor: '#d9d9d9',
        },
        title: {
            text: null
        },

        xAxis: {
            categories: arr1,
            title: {
                text: null
            },
            alternateGridColor: '#ffffff',

        },
        yAxis: {
            min: 0,

            title: {
                text: null,
            },
            labels: {
                overflow: 'justify'
            }
        },
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
            name: '',
            data: arr2,
            color: '#FF0000',
        }],
        exporting: {
            allowHTML: true,
            enabled: false
        }
    });
}

//Chart total unique user
function totalUniqueUser(total_unique_user) {
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(function () {
        chartUniqueUser(total_unique_user);
    });
}

function chartUniqueUser(data_chart) {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'X');
    data.addColumn('number', 'Users');
    data.addRows(data_chart);
    var options = {
        hAxis: {
            title: ''
        },
        vAxis: {
            // title: 'UNIQUE USER BY TIME'
        },
        legend: 'top',
        chartArea: {
            // leave room for y-axis labels
            width: '90%',
            height:'300',
        },
        height:'370',

    };

    var chart = new google.visualization.LineChart(document.getElementById('total_unique_user'));
    chart.draw(data, options);
}

//Chart total user unique sku
function totalUserSku(total_user_sku) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function () {
        chartUserSku(total_user_sku);
    });
}

function chartUserSku(data_chart) {
    // var data_follow = [];
    // data_follow = [
    //     ['Element', 'Số lượng', {role: 'style'}, {role: 'annotation'}]
    // ];
    // $.map(data_chart, function (item) {
    //     data_follow.push(item);
    // });
    // var data = google.visualization.arrayToDataTable(data_follow);
    //
    // var view = new google.visualization.DataView(data);
    // view.setColumns([0, 1,
    //     {
    //         calc: "stringify",
    //         sourceColumn: 1,
    //         type: "string",
    //         role: "annotation"
    //     },
    //     2]);
    // var options = {
    //     title: "Số lượng unique users Chia theo SKU by Brand",
    //     bar: {groupWidth: "70%"},
    //     legend: {position: "none"},
    //     hAxis: {
    //         title: 'Số lượng',
    //     },
    //     colors: [
    //         '#ff2732',
    //     ],
    //     chartArea: {
    //         // leave room for y-axis labels
    //         width: '90%',
    //         height:'250',
    //     },
    //     height:'370',
    // };
    // var chart = new google.visualization.ColumnChart(
    //     document.getElementById('total_user_sku'));
    // chart.draw(data, options);
    var arr1 = [];
    var arr2 = [];
    $.map(data_chart, function (data) {
        arr1.push(data[0]);
        arr2.push(data[1]);
    });
    //console.log(arr1);
    Highcharts.chart('total_user_sku', {
        chart: {
            type: 'column',
            plotBackgroundColor: '#d9d9d9',
            height: 500,
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            alternateGridColor: '#ffffff',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: ''
        },
        exporting: {
            allowHTML: true,
            enabled: false
        },
        series: [{
            name: '',
            color: '#FF0000',
            data: data_chart,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            },

        }]
    });
}

//Chart total user unique attribute
function totalUserAttr(total_user_attr) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function () {
        chartUserAttr(total_user_attr);
    });
}

function chartUserAttr(data_chart) {
    // var data_follow = [];
    // data_follow = [
    //     ['Element', 'Số lượng', {role: 'style'}, {role: 'annotation'}]
    // ];
    // $.map(data_chart, function (item) {
    //     data_follow.push(item);
    // });
    // var data = google.visualization.arrayToDataTable(data_follow);
    //
    // var view = new google.visualization.DataView(data);
    // view.setColumns([0, 1,
    //     {
    //         calc: "stringify",
    //         sourceColumn: 1,
    //         type: "string",
    //         role: "annotation"
    //     },
    //     2]);
    // var options = {
    //     // title: "Số lượng unique users Chia theo Attribute by Brand",
    //     bar: {groupWidth: "70%"},
    //     legend: {position: "none"},
    //     hAxis: {
    //         title: 'Số lượng',
    //     },
    //     height: 400,
    //     colors: [
    //         '#ff2732',
    //     ],
    //     chartArea: {
    //         // leave room for y-axis labels
    //         width: '90%',
    //         height:'300',
    //     },
    // };
    // var chart = new google.visualization.ColumnChart(
    //     document.getElementById('total_user_attr'));
    // chart.draw(data, options);
    Highcharts.chart('total_user_attr', {
        chart: {
            type: 'column',
            plotBackgroundColor: '#d9d9d9',
            height: 500,
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            alternateGridColor: '#ffffff',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: ''
        },
        exporting: {
            allowHTML: true,
            enabled: false
        },
        series: [{
            name: '',
            color: '#FF0000',
            data: data_chart,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            },

        }]
    });
}
//Chart total unique user brand
function totalUniqueUserBrand(total_unique_user_brand) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function () {
        chartUniqueUserBrand(total_unique_user_brand);
    });
}

// function chartUniqueUserBrand(data_chart) {
//     var data_follow = [];
//     data_follow = [
//         ['Element', 'Số lượng', {role: 'style'}, {role: 'annotation'}]
//     ];
//     $.map(data_chart, function (item) {
//         data_follow.push(item);
//     });
//     var data = google.visualization.arrayToDataTable(data_follow);
//
//     var view = new google.visualization.DataView(data);
//     view.setColumns([0, 1,
//         {
//             calc: "stringify",
//             sourceColumn: 1,
//             type: "string",
//             role: "annotation"
//         },
//         2]);
//     var options = {
//         //title: "Số lượng unique users by Brand",
//         bar: {groupWidth: "70%"},
//         legend: {position: "none"},
//         // hAxis: {
//         //     title: 'Số lượng',
//         // },
//         height: 370,
//         colors: [
//             '#ff2732',
//         ],
//         chartArea: {
//             // leave room for y-axis labels
//             width: '90%',
//             height:'380px',
//         },
//     };
//     var chart = new google.visualization.ColumnChart(
//         document.getElementById('total_unique_user_brand'));
//     chart.draw(data, options);
// }
function chartUniqueUserBrand(data_chart) {
    var base_url_home = location.protocol + "//" + location.hostname + (location.port && ":" + location.port) + "/";
    console.log(base_url_home);
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
        color: '#d70a11'
    },
        {
            name: 'Nutren Junior',
            flag: 20200914,
            color: '#d70a11'
        }
    ];
    data_chart.push(['12312312',6,"",6])
    console.log(data_chart);

    console.log(getData(data_chart).slice());

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

    var chart = Highcharts.chart('total_unique_user_brand', {
        chart: {
            type: 'column',
            plotBackgroundColor: '#d9d9d9',
            height: 450,
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
            max: 5,
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
                enabled: true,
                matchByName: true
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
//Chart On Off Bot
function totalOnOffBot(total_on_off_bot) {
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(function () {
        chartOnOffBot(total_on_off_bot);
    });
}

function chartOnOffBot(data_chart) {
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
        height: 370,
        backgroundColor: '#f0f0f0',
    };

    // var chart = new google.visualization.LineChart(document.getElementById('chart-time-line'));
    // chart.draw(data, options);
}
