
var All = {
    status: null,
    queue: null,
    pioTable: null,
    year: null,
    jsonLang: null,
    start: function () {
        All.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        $('.m_selectpicker').selectpicker();

        $('#m_year').on('change', function () {
            All.year = this.value;
            All.orderChart('', All.year);

            $('#m_month').val('').selectpicker('refresh')
        });

        $('#m_month').on('change', function () {
            var month = this.value;
            All.orderChart(month, All.year);
        });

        var a = moment(),
            t = moment();

        var arrRange = {};
        arrRange[All.jsonLang['Hôm nay']] = [moment(), moment()],
            arrRange[All.jsonLang['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
            arrRange[All.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
            arrRange[All.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
            arrRange[All.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
            arrRange[All.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]

        // $("#m_daterangepicker_6").daterangepicker({
        //     buttonClasses: "m-btn btn",
        //     applyClass: "btn-primary",
        //     cancelClass: "btn-secondary",
        //     autoclose: true,
        //     startDate: a,
        //     endDate: t,
        //     autoApply: true,
        //     locale: {
        //         format: 'DD/MM/YYYY',
        //         "applyLabel": "Đồng ý",
        //         "cancelLabel": "Thoát",
        //         "customRangeLabel": All.jsonLang["Tùy chọn ngày"],
        //         daysOfWeek: [
        //             All.jsonLang["CN"],
        //             All.jsonLang["T2"],
        //             All.jsonLang["T3"],
        //             All.jsonLang["T4"],
        //             All.jsonLang["T5"],
        //             All.jsonLang["T6"],
        //             All.jsonLang["T7"]
        //         ],
        //         "monthNames": [
        //             All.jsonLang["Tháng 1 năm"],
        //             All.jsonLang["Tháng 2 năm"],
        //             All.jsonLang["Tháng 3 năm"],
        //             All.jsonLang["Tháng 4 năm"],
        //             All.jsonLang["Tháng 5 năm"],
        //             All.jsonLang["Tháng 6 năm"],
        //             All.jsonLang["Tháng 7 năm"],
        //             All.jsonLang["Tháng 8 năm"],
        //             All.jsonLang["Tháng 9 năm"],
        //             All.jsonLang["Tháng 10 năm"],
        //             All.jsonLang["Tháng 11 năm"],
        //             All.jsonLang["Tháng 12 năm"]
        //         ],
        //         "firstDay": 1
        //     },
        //     ranges: arrRange
        // }, function (a, t, n) {
        //     $("#m_daterangepicker_6 .form-control").val(a.format("DD/MM/YYYYY") + " - " + t.format("DD/MM/YYYY"));
        //     All.salesChart(a.format('YYYY-MM-DD'), t.format('YYYY-MM-DD'));
        // });

        // $("#m_daterangepicker_7").daterangepicker({
        //     buttonClasses: "m-btn btn",
        //     applyClass: "btn-primary",
        //     cancelClass: "btn-secondary",
        //     autoclose: true,
        //     startDate: a,
        //     endDate: t,
        //     autoApply: true,
        //     locale: {
        //         format: 'DD/MM/YYYY',
        //         // "applyLabel": "Đồng ý",
        //         // "cancelLabel": "Thoát",
        //         "customRangeLabel": All.jsonLang["Tùy chọn ngày"],
        //         daysOfWeek: [
        //             All.jsonLang["CN"],
        //             All.jsonLang["T2"],
        //             All.jsonLang["T3"],
        //             All.jsonLang["T4"],
        //             All.jsonLang["T5"],
        //             All.jsonLang["T6"],
        //             All.jsonLang["T7"]
        //         ],
        //         "monthNames": [
        //             All.jsonLang["Tháng 1 năm"],
        //             All.jsonLang["Tháng 2 năm"],
        //             All.jsonLang["Tháng 3 năm"],
        //             All.jsonLang["Tháng 4 năm"],
        //             All.jsonLang["Tháng 5 năm"],
        //             All.jsonLang["Tháng 6 năm"],
        //             All.jsonLang["Tháng 7 năm"],
        //             All.jsonLang["Tháng 8 năm"],
        //             All.jsonLang["Tháng 9 năm"],
        //             All.jsonLang["Tháng 10 năm"],
        //             All.jsonLang["Tháng 11 năm"],
        //             All.jsonLang["Tháng 12 năm"]
        //         ],
        //         "firstDay": 1
        //     },
        //     ranges: arrRange
        // }, function (a, t, n) {
        //     $("#m_daterangepicker_7 .form-control").val(a.format("DD/MM/YYYY") + " - " + t.format("DD/MM/YYYY"));
        //     All.topSalesChart(a.format('YYYY-MM-DD'), t.format('YYYY-MM-DD'));
        // });
        $("#date_sale").daterangepicker({
            buttonClasses: "m-btn btn",
            applyClass: "btn-primary",
            cancelClass: "btn-secondary",
            autoclose: true,
            maxDate: moment().endOf("day"),
            startDate: moment().subtract(6, "days"),
            endDate: moment(),
            autoApply: true,
            locale: {
              format: 'DD/MM/YYYY',
              "applyLabel": "Đồng ý",
              "cancelLabel": "Thoát",
              "customRangeLabel": All.jsonLang["Tùy chọn ngày"],
              daysOfWeek: [
                All.jsonLang["CN"],
                All.jsonLang["T2"],
                All.jsonLang["T3"],
                All.jsonLang["T4"],
                All.jsonLang["T5"],
                All.jsonLang["T6"],
                All.jsonLang["T7"]
              ],
              "monthNames": [
                All.jsonLang["Tháng 1 năm"],
                All.jsonLang["Tháng 2 năm"],
                All.jsonLang["Tháng 3 năm"],
                All.jsonLang["Tháng 4 năm"],
                All.jsonLang["Tháng 5 năm"],
                All.jsonLang["Tháng 6 năm"],
                All.jsonLang["Tháng 7 năm"],
                All.jsonLang["Tháng 8 năm"],
                All.jsonLang["Tháng 9 năm"],
                All.jsonLang["Tháng 10 năm"],
                All.jsonLang["Tháng 11 năm"],
                All.jsonLang["Tháng 12 năm"]
              ],
              "firstDay": 1
            },
            ranges: arrRange
          }, function (a, t, n) {
            $("#date_sale").val(a.format("DD/MM/YYYY") + " - " + t.format("DD/MM/YYYY"));
            All.salesChart();
          });
    
    },
    appointmentChart: function () {
        $.get(laroute.route('dashbroad.appointment-by-date'), function (res) {
            AmCharts.makeChart("m_appointments", {
                "type": "serial",
                "addClassNames": true,
                "theme": "light",
                "autoMargins": true,
                "marginLeft": 30,
                "marginRight": 8,
                "marginTop": 10,
                "marginBottom": 26,
                "balloon": {
                    "adjustBorderColor": false,
                    "horizontalPadding": 10,
                    "verticalPadding": 8,
                    "color": "#fff"
                },
                "dataProvider": res,
                "valueAxes": [{
                    "axisAlpha": 0,
                    "position": "left",
                }],
                "startDuration": 1,
                "graphs": [{
                    "alphaField": "alpha",
                    "balloonText": "<span style='font-size:12px;'>[[title]] [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                    "fillAlphas": 1,
                    "title": "Ngày",
                    "type": "column",
                    "valueField": "appointment",
                    "dashLengthField": "dashLengthColumn",
                }],
                "categoryField": "date",
                "categoryAxis": {
                    "gridPosition": "start",
                    "axisAlpha": 0,
                    "tickLength": 0

                }
            });

        });
    },
    orderChart: function (month, year) {
        $.post(laroute.route('dashbroad.order-by-month-year'), {month: month, year: year}, function (res) {
            AmCharts.makeChart("m_orders", {
                "type": "serial",
                "addClassNames": true,
                "theme": "light",
                "autoMargins": false,
                "marginLeft": 50,
                "marginRight": 8,
                "marginTop": 10,
                "marginBottom": 26,
                "balloon": {
                    "adjustBorderColor": false,
                    "horizontalPadding": 10,
                    "verticalPadding": 8,
                    "color": "#ffffff"
                },
                "dataProvider": res,
                "valueAxes": [{
                    "axisAlpha": 0,
                    "position": "left"
                }],
                "startDuration": 1,
                "graphs": [{
                    "alphaField": "alpha",
                    "balloonText": "<span style='font-size:12px;'>[[category]]<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                    "fillAlphas": 1,
                    "title": "Tháng",
                    "type": "column",
                    "valueField": "order",
                    "dashLengthField": "dashLengthColumn"
                }],
                "categoryField": "month",
                "categoryAxis": {
                    "gridPosition": "start",
                    "axisAlpha": 0,
                    "tickLength": 0
                }
            });

        });
    },
    salesChart: function ($form, $to) {
        var dateSelect = $('#date_sale').val();
        $.post(laroute.route('dashbroad.order-by-object-type'), {date: dateSelect}, function (res) {
            AmCharts.makeChart("m_amcharts_8", {
                "theme": "light",
                "type": "serial",
                "dataProvider": res,
                "valueAxes": [{
                    "id": "distanceAxis",
                    "unit": All.jsonLang["VNĐ"],
                    "position": "left",
                },
                    {
                        "id": "durationAxis",
                        "axisAlpha": 0,
                        "gridAlpha": 0,
                        // "inside": true,
                        "position": "right"
                    }],
                "startDuration": 1,
                "graphs": [{
                    "balloonText": All.jsonLang['Số lượng'] + ": <b>[[value]]</b>",
                    "fillAlphas": 0.9,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "valueField": "quantity",
                    "valueAxis": "durationAxis"
                }, {
                    "balloonText":  All.jsonLang['Tổng tiền'] + ": <b>[[value]] " +All.jsonLang['VNĐ'] +"</b>  ",
                    "fillAlphas": 0.9,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "clustered": false,
                    "columnWidth": 0.5,
                    "valueField": "amount",
                    "valueAxis": "distanceAxis"
                }],
                "plotAreaFillAlphas": 0.1,
                "categoryField": "type",
                "categoryAxis": {
                    "gridPosition": "start"
                }

            });

        });
    },
    topSalesChart: function ($form, $to) {
        $.post(laroute.route('dashbroad.get-top-service'), {formDate: $form, toDate: $to}, function (res) {
            AmCharts.makeChart("m_amcharts_5", {
                "theme": "light",
                "type": "serial",
                "dataProvider": res,
                "valueAxes": [{
                    "id": "distanceAxis",
                    "amount": " VNĐ",
                    "position": "left",
                },
                    {
                        "id": "durationAxis",
                        "axisAlpha": 0,
                        "gridAlpha": 0,
                        // "inside": true,
                        "position": "right"
                    }],
                "startDuration": 1,
                "graphs": [{
                    "balloonText":  All.jsonLang['Số lượng'] + ": <b>[[value]]</b>",
                    "fillAlphas": 0.9,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "valueField": "used",
                    "valueAxis": "durationAxis"
                }, {
                    "balloonText": All.jsonLang['Tổng tiền'] + ": <b>[[value]] "+All.jsonLang['VNĐ']+"</b>  ",
                    "fillAlphas": 0.9,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "clustered": false,
                    "columnWidth": 0.5,
                    "valueField": "amount",
                    "valueAxis": "distanceAxis"
                }],
                "rotate": true,
                "plotAreaFillAlphas": 0.1,
                "categoryField": "name",
                "categoryAxis": {
                    "gridPosition": "start"
                }

            });

        });
    },
    dashboardTicket: function () {
        $.ajax({
            url: laroute.route('dashbroad.get-dashboard-ticket'),
            method: 'POST',
            dataType: 'JSON',
            data: {
            },
            success: function(res) {
                var ticket_new = res.ticketDashboad.newTicket;
                var ticket_processing = res.ticketDashboad.inprocessTicket;
                var ticket_out_of_date = res.ticketDashboad.expiredTicket;
                var total = res.ticketDashboad.total;
                var value_new = res.ticketDashboad.newTicketPercent;
                var value_processing = res.ticketDashboad.inprocessTicketPercent;
                var value_out_of_date = res.ticketDashboad.expiredTicketPercent;
                $('.dashboard_ticket_total').text(total);
                $('.dashboard_ticket_new_percent').text(value_new);
                $('.dashboard_ticket_inprogress_percent').text(value_processing);
                $('.dashboard_ticket_expired_percent').text(value_out_of_date);
                $('.dashboard_ticket_new_ticket').text(ticket_new);
                $('.dashboard_ticket_inprocess_ticket').text(ticket_processing);
                $('.dashboard_ticket_expired_ticket').text(ticket_out_of_date);


                if ($('#m_chart_support_tickets2').length == 0) {
                    return;
                }

                var chart = new Chartist.Pie('#m_chart_support_tickets2', {
                    series: [{
                        value: value_new,
                        className: 'custom',
                        meta: {
                            color: '#eb7c31' //mApp.getColor('brand')
                        },

                    },
                        {
                            value: value_processing,
                            className: 'custom',
                            meta: {
                                color: '#44a581' //mApp.getColor('accent')
                            }
                        },
                        {
                            value: value_out_of_date,
                            className: 'custom',
                            meta: {
                                color: '#7e7e7e' //mApp.getColor('warning')
                            }
                        }
                    ],
                    labels: [value_new + '%', value_processing + '%', value_out_of_date + '%']
                }, {
                    donut: true,
                    donutWidth: 35,
                    showLabel: true,
                });

                chart.on('draw', function (data) {
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
        });
        //
    },

    
};


All.start();
All.appointmentChart();
All.orderChart();
All.salesChart();
All.topSalesChart();
All.dashboardTicket();
