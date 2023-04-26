var ticket_1 = 0
var ticket_2 = 0;
var ticket_3 = 0;
var ticket_4 = 0;
var ticket_5 = 0;
var ticket_6 = 0;
var ticket_7 = 0;
var value_1 = 0;
var value_2 = 0;
var value_3 = 0;
var value_4 = 0;
var value_5 = 0;
var value_6 = 0;
var value_7 = 0;
var ticket_total_dasboard_1 = 0;
var ticket_total_dasboard_2 = 0;
var revenueByBranch = {
    _init: function() {
        $.getJSON(laroute.route('translate'), function(json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#time").daterangepicker({
                // autoUpdateInput: false,
                autoApply: true,
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",
                maxDate: moment().endOf("day"),
                startDate: moment().subtract(6, "days"),
                endDate: moment(),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "applyLabel": json["Đồng ý"],
                    "cancelLabel": json["Thoát"],
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            }).on('apply.daterangepicker', function(event) {
                revenueByBranch.loadChart();
            });
            $('[name="ticket_status_id"').select2().on('select2:select', function(event) {
                revenueByBranch.loadChart();
            });
            $('[name="ticket_issue_group_id"').select2().on('select2:select', function(event) {
                revenueByBranch.loadChart();
            });
            $('[name="staff_id"]').select2().on('select2:select', function(event) {
                revenueByBranch.loadChart();
            });
            $('[name="queue_process_id"]').select2().on('select2:select', function(event) {
                revenueByBranch.loadChart();
            });
            revenueByBranch.loadChart();
        });
    },

    loadChart: function() {
        $.ajax({
            url: laroute.route('ticket.get-chart'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                time: $('#time').val(),
                staff_id: $('[name="staff_id"]').val(),
                ticket_issue_group_id: $('[name="ticket_issue_group_id"]').val(),
                queue_process_id: $('[name="queue_process_id"]').val(),
                ticket_status_id: $('[name="ticket_status_id"]').val(),
            },
            success: function(res) {
                chart(res.dataSeries, res.arrayCategories);
                ticket_1 = parseInt(res.quantity[1]); // mới
                ticket_2 = parseInt(res.quantity[2]); // đang
                ticket_3 = parseInt(res.quantity[3]); // đã hoàn thành
                ticket_4 = parseInt(res.quantity[4]); // đóng
                ticket_5 = parseInt(res.quantity[5]); // hủy
                ticket_6 = parseInt(res.quantity[6]); // reopen
                ticket_7 = parseInt(res.quantity[7]); // quá hạn
                // ticket_total_dasboard_2 = parseInt(res.quantity['total']);
                ticket_total_dasboard_2 = parseInt(res.quantity[8]);
                ticket_total_dasboard_1 = ticket_1 + ticket_2 + ticket_3 + ticket_4;
                if (ticket_total_dasboard_1 > 0 || ticket_total_dasboard_2 > 0) {
                    value_1 = parseFloat((100 / ticket_total_dasboard_1) * ticket_1).toFixed(2);
                    value_2 = parseFloat((100 / ticket_total_dasboard_1) * ticket_2).toFixed(2);
                    value_3 = parseFloat((100 / ticket_total_dasboard_1) * ticket_3).toFixed(2);
                    value_4 = parseFloat((100 / ticket_total_dasboard_1) * ticket_4).toFixed(2);
                    value_5 = parseFloat((100 / ticket_total_dasboard_1) * ticket_5).toFixed(2);
                    value_6 = parseFloat((100 / ticket_total_dasboard_2) * ticket_6).toFixed(2);
                    value_7 = parseFloat((100 / ticket_total_dasboard_2) * ticket_7).toFixed(2);
                    value_other = (100 - value_7).toFixed(2);
                } else {
                    value_1 = 100;
                    value_2 = 0;
                    value_3 = 0;
                    value_4 = 0;
                    value_5 = 0;
                    value_6 = 0;
                    value_7 = 0;
                    value_other = 100;
                }
                Dashboard.init();
                $('.table-report').html(res.table)
            }
        });
    },
}
Highcharts.setOptions({
    lang: {
        decimalPoint: '.',
        thousandsSep: ','
    }
});
// Biểu đồ cột
function chart(dataSeries, arrayCategories) {
    $.getJSON(laroute.route('translate'), function(json) {
        Highcharts.chart('container', {

            title: false,
            colors: ['#81ff62', '#f27000', '#0496ec', '#a3a3a3', '#ff0000', '#0496ec', '#8660fa', '#0496ec'],
            yAxis: {
                min: 0,
                title: lang['Số lượng trong ticket']
            },

            xAxis: {
                categories: arrayCategories,
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%e - %b'
                }
            },

            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom'
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                }
            },

            series: dataSeries,

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
    });
}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

//== Class definition
var Dashboard = function() {
    //== Support Tickets Chart.
    //** Based on Morris plugin - http://morrisjs.github.io/morris.js/
    var supportTickets1 = function() {
            if ($('#m_chart_support_tickets1').length == 0) {
                return;
            }
            var chart = new Chartist.Pie('#m_chart_support_tickets1', {
                series: [{
                        value: value_1,
                        className: 'custom',
                        meta: {
                            color: '#34bfa3'
                        },

                    },
                    {
                        value: value_2,
                        className: 'custom',
                        meta: {
                            color: '#ffb822'
                        }
                    },
                    {
                        value: value_3,
                        className: 'custom',
                        meta: {
                            color: '#36a3f7'
                        }
                    },
                    {
                        value: value_4,
                        className: 'custom',
                        meta: {
                            color: '#c4c5d6'
                        }
                    },
                ],
                labels: [value_1 + '%', value_2 + '%', value_3 + '%', value_4 + '%']
            }, {
                donut: true,
                donutWidth: 100,
                showLabel: true,
                plugins: [
                    Chartist.plugins.legend({
                        legendNames: [lang['Mới'], lang['Đang xử lý'], lang['Hoàn tất'], lang['Đóng']],
                    })
                ]
            });

            chart.on('draw', function(data) {
                if (data.type === 'slice') {
                    // Get the total path length in order to use for dash array animation
                    var pathLength = data.element._node.getTotalLength();

                    // Set a dasharray that matches the path length as prerequisite to animate dashoffset
                    data.element.attr({
                        'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
                    });

                    // Create animation definition while also assigning an ID to the animation for later sync usage
                    var animationDefinition = {
                        'stroke-dashoffset': {
                            id: 'anim' + data.index,
                            dur: 1000,
                            from: -pathLength + 'px',
                            to: '0px',
                            easing: Chartist.Svg.Easing.easeOutQuint,
                            // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
                            fill: 'freeze',
                            'stroke': data.meta.color
                        }
                    };

                    // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
                    if (data.index !== 0) {
                        animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
                    }

                    // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us

                    data.element.attr({
                        'stroke-dashoffset': -pathLength + 'px',
                        'stroke': data.meta.color
                    });

                    // We can't use guided mode as the animations need to rely on setting begin manually
                    // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
                    data.element.animate(animationDefinition, false);
                }
            });
        }
        //== Support Tickets Chart.
        //** Based on Morris plugin - http://morrisjs.github.io/morris.js/
    var supportTickets2 = function() {
        if ($('#m_chart_support_tickets2').length == 0) {
            return;
        }
        var chart = new Chartist.Pie('#m_chart_support_tickets2', {
            series: [{
                    value: value_7,
                    className: 'custom',
                    meta: {
                        color: '#9816f4'
                    },

                },
                {
                    value: value_other,
                    className: 'custom',
                    meta: {
                        color: '#00c5dc'
                    }
                },
            ],
            labels: [value_7 + '%', value_other + '%']
        }, {
            donut: true,
            donutWidth: 100,
            showLabel: true,
            plugins: [
                Chartist.plugins.legend({
                    legendNames: [lang['Quá hạn'], lang['Khác']],
                })
            ]
        });

        chart.on('draw', function(data) {
            if (data.type === 'slice') {
                // Get the total path length in order to use for dash array animation
                var pathLength = data.element._node.getTotalLength();

                // Set a dasharray that matches the path length as prerequisite to animate dashoffset
                data.element.attr({
                    'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
                });

                // Create animation definition while also assigning an ID to the animation for later sync usage
                var animationDefinition = {
                    'stroke-dashoffset': {
                        id: 'anim' + data.index,
                        dur: 1000,
                        from: -pathLength + 'px',
                        to: '0px',
                        easing: Chartist.Svg.Easing.easeOutQuint,
                        // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
                        fill: 'freeze',
                        'stroke': data.meta.color
                    }
                };

                // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
                if (data.index !== 0) {
                    animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
                }

                // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us

                data.element.attr({
                    'stroke-dashoffset': -pathLength + 'px',
                    'stroke': data.meta.color
                });

                // We can't use guided mode as the animations need to rely on setting begin manually
                // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
                data.element.animate(animationDefinition, false);
            }
        });
    }

    return {
        //== Init demos
        init: function() {
            supportTickets2();
            supportTickets1();
        }
    };
}();

$(document).on('click', '.m-datatable__pager-link', function() {
    var data_page = parseInt($(this).attr('data-page'));
    if (!data_page) {
        return;
    }
    $.ajax({
        url: laroute.route('ticket.get-chart'),
        method: 'POST',
        dataType: 'JSON',
        data: {
            page: data_page,
            time: $('#time').val(),
            staff_id: $('[name="staff_id"]').val(),
            ticket_issue_group_id: $('[name="ticket_issue_group_id"]').val(),
            queue_process_id: $('[name="queue_process_id"]').val(),
            ticket_status_id: $('[name="ticket_status_id"]').val(),
        },
        success: function(res) {
            $('.table-report').html(res.table)
        }
    });
});