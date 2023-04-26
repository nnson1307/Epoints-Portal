var list = {
    jsonLang: null,
    changeDateType: function (obj) {
        if ($(obj).val() == 'by_week') {
            $('#date_object').empty();

            var tpl = $('#option-week-tpl').html();
            $('#date_object').append(tpl);
        } else {
            $('#date_object').empty();

            var tpl = $('#option-month-tpl').html();
            $('#date_object').append(tpl);
        }
    },
    getReportBudgetBranchList : function() {
        $.ajax({
            url: laroute.route('staff-salary.repor-list'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#m_report_chart').empty();
                $('#m_report_list').html(res.html);
                $('#autotable').PioTable({
                    baseUrl: laroute.route('staff-salary.report-budget-branch.list')
                });
                $(".m_selectpicker").select2({
                    width: "100%"
                }).on('select2:select', function () {
                    $('.btn-search').trigger('click');
                });
        
                $('.btn-search').trigger('click');
            }
        });
    },

    getReportBudgetBranchChart : function() {
        $.ajax({
            url: laroute.route('staff-salary.repor-chart'),
            method: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (res) {
                $('#m_report_list').empty();
                $('#m_report_chart').html(res.html);
                // $('#autotable').PioTable({
                //     baseUrl: laroute.route('staff-salary.report-budget-branch.list-chart')
                // });
                $(".m_selectpicker").select2({
                    width: "100%"
                }).on('select2:select', function () {
                    list.getReportChart();
                });
                list.getReportChart();
                // $('.btn-search').trigger('click');
            }
        });
    },
    getReportChart: function() {
        $.ajax({
            url: laroute.route('staff-salary.report-budget-branch.list-chart'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                date_type: $('#date_type').val(),
                date_object : $('#date_object').val(),
                branch_id: $(".select2-multiple2").val()
            },
            success: function (res) {
                list.showChartMoney(res.category, res.seriesMoney);
                list.showChartTime(res.category, res.seriesTime);
            }
        });
    },
    showChartMoney : function (dataCategories, dataSeries) {
        $('#container-money').highcharts({
            data: {
                table: 'datatable'
            },
            chart: {
                type: 'column',
                height: 600,
            },
            title: {
                text: list.jsonLang['BÁO CÁO NGÂN SÁCH']
            },
          
            xAxis: {
                categories: dataCategories,
                crosshair: true
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: ''
                }
            },
            tooltip: {
                formatter: function () {
                  return '<b>' + this.series.name + '</b> : ' + formatNumber(this.point.y) + list.jsonLang[' VNĐ'];
                }
              },
       
            series: dataSeries
        });
    },
    showChartTime : function (dataCategories, dataSeries) {
        $('#container-time').highcharts({
            data: {
                table: 'datatable'
            },
            chart: {
                type: 'column',
                height: 600,
            },
            title: {
                text: list.jsonLang['BÁO CÁO SỐ GIỜ LÀM VIỆC']
            },
          
            xAxis: {
                categories: dataCategories,
                crosshair: true
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: ''
                }
            },
            tooltip: {
                formatter: function () {
                  return '<b>' + this.series.name + '</b> : ' + formatNumber(this.point.y) + 'h';
                }
              },
       
            series: dataSeries
        });
    }
};
//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}
$(document).ready(function() {
    list.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
    list.getReportBudgetBranchChart();
})