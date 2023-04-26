var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var Report = {
    _init: function () {
        $(document).ready(function () {
            $('#branch_id, #department_id, #team_id, #date_type').select2({
                width: '100%'
            });

            $('.year_picker').datepicker({
                minViewMode: 2,
                format: 'yyyy',
                endDate: "+0y"
            });

            Report.loadData();
        });
    },
    changeBranch: function () {
        $.ajax({
            url: laroute.route('report-kpi.change-branch'),
            method: 'POST',
            data: {
                branch_id: $('#branch_id').val()
            },
            success: function (res) {
                $('#department_id').empty();
                $('#department_id').append('<option value="">' + jsonLang['Tất cả phòng ban'] + '</option>');

                $.each(res.optionDepartment, function (k, v) {
                    $('#department_id').append('<option value="' + v.department_id + '">' + v.department_name + '</option>');
                });

                $('#team_id').empty();
                $('#team_id').append('<option value="">' + jsonLang['Tất cả nhóm'] + '</option>');

                Report.loadData();
            }
        });
    },
    changeDepartment: function () {
        $.ajax({
            url: laroute.route('report-kpi.change-department'),
            method: 'POST',
            data: {
                department_id: $('#department_id').val()
            },
            success: function (res) {
                $('#team_id').empty();
                $('#team_id').append('<option value="">' + jsonLang['Tất cả nhóm'] + '</option>');

                $.each(res.optionTeam, function (k, v) {
                    $('#team_id').append('<option value="' + v.team_id + '">' + v.team_name + '</option>');
                });

                Report.loadData();
            }
        });
    },
    changeTeam: function () {
        Report.loadData();
    },
    changeDateType: function () {
        if ($('#date_type option:selected').val() == 'select_year') {
            $('.yearpicker-block').show();
        } else {
            $('.yearpicker-block').hide();
        }

        Report.loadData();
    },
    changeYearType: function () {
        Report.loadData();
    },
    loadData: function () {
        $.ajax({
            url: laroute.route('report-kpi.load-data'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                branch_id: $('#branch_id').val(),
                department_id: $('#department_id').val(),
                team_id: $('#team_id').val(),
                date_type: $('#date_type').val(),
                year_picker: $('#year_picker').val()
            },
            success: function (res) {
                if (res.chart_type == 'vertical') {
                    Report.chartVerticalColumn(res.data_chart.categories, res.data_chart.series);
                } else if (res.chart_type == 'line') {
                    Report.chartLine(res.data_chart.categories, res.data_chart.series);
                } else if (res.chart_type == 'horizontal') {
                    Report.chartHorizontalColumn(res.data_chart.categories, res.data_chart.series);
                }

                $('#div_table').html(res.html_table);
            }
        });

        // Report.chartVerticalColumn();
    },
    chartVerticalColumn: function (categories, series) {
        Highcharts.chart('div_chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: categories,
                crosshair: true
            },
            yAxis: {
                title: {
                    useHTML: true,
                    text: ''
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.2f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: series
        });
    },
    chartHorizontalColumn: function (categories, series) {
        Highcharts.chart('div_chart', {
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: categories,
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.2f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: series
        });
    },
    chartLine: function (categories, series) {
        Highcharts.chart('div_chart', {

            title: {
                text: ''
            },

            subtitle: {
                text: ''
            },

            yAxis: {
                title: {
                    text: ''
                }
            },

            xAxis: {
                categories: categories,
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.2f} %</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                }
            },

            series: series,

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
    }
};