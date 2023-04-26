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
        url: laroute.route('word-cloud.load-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            date_range: $('#date-range').val(),
        },
        success: function (res) {
            keyword(res.key_word);
        }
    });
}
//Chart KeyWord
function keyword(key) {
    //console.log(key);
    var data = key;
    console.log(data);
    Highcharts.chart('word-cloud', {
        chart: {
            height: 600,
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
            minFontSize:5,
            fontFamily: 'roboto',
        }],
        title: {
            text: ''
        },

    });
}
