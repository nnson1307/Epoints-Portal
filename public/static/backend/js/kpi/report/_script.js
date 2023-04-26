var Report = {
    changeBranch : function (){
        $.ajax({
            url: laroute.route('report-kpi.changeBranch'),
            method: 'POST',
            data: {
                branch_id : $('.branch_id option:selected').val()
            },
            success: function (res) {
                if (res.error == false){
                    $('.department_id').empty();
                    $('.department_id').append(res.view_department);

                    $('.staff_id').empty();
                    $('.staff_id').append(res.view_staff);
                    Report.changeOption('changeStaff');
                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    },
    changeDepartment : function (){
        $.ajax({
            url: laroute.route('report-kpi.changeDepartment'),
            method: 'POST',
            data: {
                department_id : $('.department_id option:selected').val()
            },
            success: function (res) {
                if (res.error == false){
                    $('.staff_id').empty();
                    $('.staff_id').append(res.view_staff);
                    Report.changeOption('changeStaff');
                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    },

    changeOption : function (value = null){
        if (value != null) {
            $('.date_type').val('this_month').trigger('change');
        }

        if ($('.date_type option:selected').val() == 'select_year'){
            $('.yearpicker-block').show();
        } else {
            $('.yearpicker-block').hide();
        }

        Report.showChartTable();
    },

    showChartTable : function (){
        $.ajax({
            url: laroute.route('report-kpi.showChartTable'),
            method: 'POST',
            data: $('.frmFilter').serialize(),
            success: function (res) {
                Report.chart1(res.data);
                if (res.error == false){
                    if(res.chart == 1){
                        Report.chart1(res.data);
                    } else if(res.chart == 2){
                        Report.chart2(res.data);
                    } else if(res.chart == 3){
                        Report.chart3(res.data);
                    } else if(res.chart == 4){
                        Report.chart4(res.dataUpdate);
                    }
                    $('#insert_table').empty();
                    $('#insert_table').append(res.view);
                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    },

    chart1 : function (data){
        // Create the chart
        Highcharts.chart('insert_chart', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: ''
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: ''
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}%'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px"></span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%<br/>'
            },

            series: [
                {
                    data: data
                }
            ],
        });
    },

    chart2 : function (data){
        Highcharts.chart('insert_chart', {

            chart: {
                type: 'column'
            },

            title: {
                text: ''
            },

            subtitle: {
                text: ''
            },

            legend: {
                align: 'right',
                verticalAlign: 'middle',
                layout: 'vertical',
                enabled:false
            },

            xAxis: {
                categories: data.categories,
                labels: {
                    x: -10
                }
            },

            yAxis: {
                allowDecimals: false,
                title: {
                    text: ''
                }
            },

            series: data.month,

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            align: 'center',
                            verticalAlign: 'bottom',
                            layout: 'horizontal'
                        },
                        yAxis: {
                            labels: {
                                align: 'left',
                                x: 0,
                                y: -5
                            },
                            title: {
                                text: null
                            }
                        },
                        subtitle: {
                            text: null
                        },
                        credits: {
                            enabled: false
                        }
                    }
                }]
            }
        });
    },

    chart3 : function (data){
        Highcharts.chart('insert_chart', {

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
                accessibility: {
                    rangeDescription: ''
                }
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                    pointStart: 1
                }
            },

            series: data,

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
    },

    chart4 : function (data){
        Highcharts.chart('insert_chart', {
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: data.categories
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: data.data
        });
    },

}