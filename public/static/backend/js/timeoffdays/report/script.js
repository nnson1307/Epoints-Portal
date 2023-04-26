var Report = {
    changeBranch: function (obj, id){
        $.ajax({
            url: laroute.route('report-kpi.changeBranch'),
            method: 'POST',
            data: {
                branch_id : $('.branch_id option:selected').val()
            },
            success: function (res) {
                if (res.error == false){
                    
                    if (id == 'branch_id'){
                        $('.department_id').empty();
                        $('.department_id').append(res.view_department);

                        Report.reportByType(obj);
                    }else{
                        $('.department_id_precious').empty();
                        $('.department_id_precious').append(res.view_department);
                        
                        Report.reportByPrecious(obj);
                    }
                    
                    // 

                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    },
    changeDepartment: function (obj, id){
        $.ajax({
            url: laroute.route('report-kpi.changeDepartment'),
            method: 'POST',
            data: {
                department_id : $('.department_id option:selected').val()
            },
            success: function (res) {
                if (res.error == false){
                    if (id == 'department_id') {
                        Report.reportByType(obj);
                    } else {
                        Report.reportByPrecious(obj);
                    }
                    // 
                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    },

    reportByTopTen: function(obj){
        var month = $('.month-top-ten option:selected').val()

        $.get('/timeoffdays/report/report-by-top-ten-ajax', { month: month }, function (res) {

            Highcharts.chart('insert_chart2', {
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
                    categories: res.categories,
                    crosshair: true
                },
                yAxis: {
                    title: {
                        useHTML: true,
                        text: ''
                    }
                },
                tooltip: {

                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [
                    {
                        data: res.data
                    },
                ]
            });

        });
    },

    reportByPrecious: function (obj) {
        var precious = $('#precious option:selected').val();
        var branch_id = $('.branch_id_precious option:selected').val();
        var department_id = $('.department_id_precious option:selected').val();  

        $.get('/timeoffdays/report/report-by-precious-ajax', { 
                branch_id: branch_id, 
                department_id: department_id, 
                precious: precious }, 
            function (res) {

                Highcharts.chart('insert_chart1', {

                    chart: {
                        type: 'column'
                    },

                    title: {
                        text: ''
                    },

                    xAxis: {
                        categories: res.categories,
                    },

                    yAxis: {
                        allowDecimals: false,
                        min: 0,
                        title: {
                            text: 'Count medals'
                        }
                    },

                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.x + '</b><br/>' +
                                this.series.name + ': ' + this.y + '<br/>' +
                                'Total: ' + this.point.stackTotal;
                        }
                    },

                    plotOptions: {
                        column: {
                            stacking: 'normal'
                        }
                    },

                    series: res.data,

                });


        });
    },

    reportByType: function (obj) {
        var month = $('.month_type option:selected').val();
        var branch_id = $('.branch_id option:selected').val();
        var department_id = $('.department_id option:selected').val();

        $.get('/timeoffdays/report/report-by-type-ajax', { 
                branch_id: branch_id, 
                department_id: department_id, 
                month: month 
            }, function (res) {

            Highcharts.chart('insert_chart', {
                chart: {
                    styledMode: true
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },
                series:
                    [
                        {
                            type: 'pie',
                            allowPointSelect: true,
                            keys: ['name', 'y', 'selected', 'sliced'],
                            data: res.data,
                            showInLegend: true
                        }
                    ]
            });

        });
    },
}