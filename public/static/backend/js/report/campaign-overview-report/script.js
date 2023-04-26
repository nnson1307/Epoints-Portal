var campaignOverviewReport = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#time_overview").daterangepicker({
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
            }).on('apply.daterangepicker', function (event) {
                    campaignOverviewReport.loadChartI();
                });
            $("#time_campaign_detail").daterangepicker({
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
            }).on('apply.daterangepicker', function (event) {
                    campaignOverviewReport.loadChartII();
                });
            campaignOverviewReport.loadChartI();
            campaignOverviewReport.loadChartII();
        });
    },

    loadChartI: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var time = $('#time_overview').val();
            $.ajax({
                url: laroute.route('report.campaign-report.filter'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    time: time,
                },
                success:function (res) {
                    chartCost(res.dataSeries, res.arrayCategories);
                    chartRevenue(res.dataSeriesRevenue, res.arrayCategoriesRevenue);
                    chartCustomerApproach(res.dataChartCustomerApproach);
                    chartDealSuccess(res.dataChartDealSuccess);
                    chartRateRoi(res.dataRoiRate);
                    $('#totalCost').text(formatNumber(res.totalCost.toFixed(decimal_number)) + json["VNĐ"]);
                    $('#totalRevenue').text(formatNumber(res.totalRevenue.toFixed(decimal_number)) + json["VNĐ"]);
                    $('#totalCustomerApproach').text(formatNumber(res.totalChartCustomerApproach.toFixed(decimal_number)));
                    $('#totalDealSuccess').text(formatNumber(res.totalChartDealSuccess.toFixed(decimal_number)));
                    var totalRoi = res.totalCost != 0 ? (res.totalRevenue-res.totalCost)/res.totalCost : 0;
                    totalRoi = totalRoi < 0  ? 0 : totalRoi;
                    $('#totalRateRoi').text(formatNumber(totalRoi.toFixed(2)));
                }
            });
        });
    },
    loadChartII: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var time = $('#time_campaign_detail').val();
            var sms = $('#option_sms option:selected').val();
            var email = $('#option_email option:selected').val();
            var notify = $('#option_notify option:selected').val();
            $.ajax({
                url: laroute.route('report.campaign-report.filter-ii'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    time: time,
                    option_sms: sms,
                    option_email: email,
                    option_notify: notify,
                },
                success:function (res) {
                    $('.sms_total_cost').text(formatNumber(res.totalSmsCost.toFixed(decimal_number)) + json['VNĐ']);
                    $('.email_total_cost').text(formatNumber(res.totalEmailCost.toFixed(decimal_number)) + json['VNĐ']);
                    $('.notify_total_cost').text(formatNumber(res.totalNotifyCost.toFixed(decimal_number)) + json['VNĐ']);
                    $('.sms_total_revenue').text(formatNumber(res.totalSmsRevenue.toFixed(decimal_number)) + json['VNĐ']);
                    $('.email_total_revenue').text(formatNumber(res.totalEmailRevenue.toFixed(decimal_number)) + json['VNĐ']);
                    $('.notify_total_revenue').text(formatNumber(res.totalNotifyRevenue.toFixed(decimal_number)) + json['VNĐ']);
                    $('.sms_deal_success').text(formatNumber(res.dataSmsDealSuccess.toFixed(decimal_number)));
                    $('.email_deal_success').text(formatNumber(res.dataEmailDealSuccess.toFixed(decimal_number)));
                    $('.notify_deal_success').text(formatNumber(res.dataNotifyDealSuccess.toFixed(decimal_number)));
                    $('.sms_roi_rate').text(formatNumber(res.roiSms.toFixed(2)));
                    $('.email_roi_rate').text(formatNumber(res.roiEmail.toFixed(2)));
                    $('.notify_roi_rate').text(formatNumber(res.roiNotify.toFixed(2)));
                    progressCustomer(res);
                    getOptions(res);
                    changeTabII();
                    sms == '' ? chartSms(res.dataChartSms) : chartSmsDetail(res.dataChartSms);
                    email == '' ? chartEmail(res.dataChartEmail) : chartEmailDetail(res.dataChartEmail);
                    notify == '' ? chartNotify(res.dataChartNotify) : chartNotifyDetail(res.dataChartNotify);

                }
            });
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
function chartCost(dataSeries, arrayCategories) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#column-chart-total-cost').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            colors: ['#205AA7', '#EC870E', '#707070'],
            xAxis: {
                categories: arrayCategories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: json['Số tiền (VNĐ)']
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + json[' VNĐ'] + '</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            exporting: false,
            plotOptions: {
                column: {
                    stacking: 'normal',
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: dataSeries
        });
    });
}
function chartRevenue(dataSeries, arrayCategories) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#column-chart-total-revenue').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            colors: ['#205AA7', '#EC870E', '#707070'],
            xAxis: {
                categories: arrayCategories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: json['Số tiền (VNĐ)']
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.2f}' + json[' VNĐ'] + '</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            exporting: false,
            plotOptions: {
                column: {
                    stacking: 'normal',
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: dataSeries
        });
    });
}
function chartCustomerApproach(dataValue){
    $.getJSON(laroute.route('translate'), function (json) {
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(dataValue);

            var options = {
                pieSliceText: 'value',
                title: '',
                pieHole: 0.4,
                legend: {position: 'bottom'},
                colors: ['#ff8000', '#ffbf00', '#008400'],
                chartArea:{left:0,top:7,right:0,width:'50%',height:'75%'},
                // enableInteractivity: false,
            };

            var chart = new google.visualization.PieChart(document.getElementById('pie-chart-total-approach'));
            chart.draw(data, options);
        }
    });
}
function chartDealSuccess(dataValue){
    $.getJSON(laroute.route('translate'), function (json) {
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(dataValue);

            var options = {
                pieSliceText: 'value',
                title: '',
                pieHole: 0.2,
                legend: {position: 'bottom'},
                colors: ['#ffbf00', '#205AA7'],
                chartArea:{left:0,top:7,right:0,width:'50%',height:'75%'},
                // enableInteractivity: false,
            };

            var chart = new google.visualization.PieChart(document.getElementById('pie-chart-deal-successful'));
            chart.draw(data, options);
        }
    });
}
function chartRateRoi(dataValue){
    $.getJSON(laroute.route('translate'), function (json) {
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            console.log(dataValue);
            var data = google.visualization.arrayToDataTable(dataValue);

            var options = {
                pieSliceText: 'value',
                title: '',
                pieHole: 0.4,
                legend: {position: 'bottom'},
                colors: ['#0000ff', '#0080ff', '#808080'],
                chartArea:{left:0,top:7,right:0,width:'50%',height:'75%'},
                // enableInteractivity: false,
            };

            var chart = new google.visualization.PieChart(document.getElementById('pie-chart-roi-convert-rate'));
            chart.draw(data, options);
        }
    });
}

function chartSms(dataValue) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#combi_sms_detail').highcharts({
            exporting: false,
            title: {
                text: ''
            },
            xAxis: {
                categories: dataValue.arrayCategories
            },
            yAxis: [
                { // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: '',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            },
                { // Secondary yAxis
                title: {
                    text: '',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value} VNĐ',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }
            ],
            series: dataValue.dataSeries
        })
    });

}
function chartSmsDetail(dataValue) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#combi_sms_detail').highcharts({
            exporting: false,
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: ''
            },
            xAxis: [{
                categories: dataValue.arrayCategories,
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Số lượng đơn hàng',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Doanh thu',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value} VNĐ',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Doanh thu',
                type: 'column',
                yAxis: 1,
                data: dataValue.dataColumn,
                tooltip: {
                    valueSuffix: ' VNĐ'
                }

            }, {
                name: 'Số lượng đơn hàng',
                type: 'spline',
                data: dataValue.dataSpline,
                tooltip: {
                    valueSuffix: ''
                }
            }]
        })
    });

}

function chartEmail(dataValue) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#combi_email_detail').highcharts({
            exporting: false,
            title: {
                text: ''
            },
            xAxis: {
                categories: dataValue.arrayCategories
            },
            yAxis: [
                { // Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: '',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    }
                },
                { // Secondary yAxis
                    title: {
                        text: '',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} VNĐ',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }
            ],
            series: dataValue.dataSeries
        })
    });

}
function chartEmailDetail(dataValue) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#combi_email_detail').highcharts({
            exporting: false,
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: ''
            },
            xAxis: [{
                categories: dataValue.arrayCategories,
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Số lượng đơn hàng',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Doanh thu',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value} VNĐ',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Doanh thu',
                type: 'column',
                yAxis: 1,
                data: dataValue.dataColumn,
                tooltip: {
                    valueSuffix: ' VNĐ'
                }

            }, {
                name: 'Số lượng đơn hàng',
                type: 'spline',
                data: dataValue.dataSpline,
                tooltip: {
                    valueSuffix: ''
                }
            }]
        })
    });

}

function chartNotify(dataValue) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#combi_notify_detail').highcharts({
            exporting: false,
            title: {
                text: ''
            },
            xAxis: {
                categories: dataValue.arrayCategories
            },
            yAxis: [
                { // Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: '',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    }
                },
                { // Secondary yAxis
                    title: {
                        text: '',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} VNĐ',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }
            ],
            series: dataValue.dataSeries
        })
    });

}
function chartNotifyDetail(dataValue) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#combi_notify_detail').highcharts({
            exporting: false,
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: ''
            },
            xAxis: [{
                categories: dataValue.arrayCategories,
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Số lượng đơn hàng',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Doanh thu',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value} VNĐ',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Doanh thu',
                type: 'column',
                yAxis: 1,
                data: dataValue.dataColumn,
                tooltip: {
                    valueSuffix: ' VNĐ'
                }

            }, {
                name: 'Số lượng đơn hàng',
                type: 'spline',
                data: dataValue.dataSpline,
                tooltip: {
                    valueSuffix: ''
                }
            }]
        })
    });

}

function changeTab(tabName) {
    $('.active').css('color','#6f727d')
    switch (tabName) {
        case 'sms':
            $('#div-sms').css('display', 'block');
            $('#div-email').css('display', 'none');
            $('#div-notify').css('display', 'none');
            $('#option_sms').next().css('display', 'block');
            $('#option_email').next().css('display', 'none');
            $('#option_notify').next().css('display', 'none');
            break;

        case 'email':
            $('#div-sms').css('display', 'none');
            $('#div-email').css('display', 'block');
            $('#div-notify').css('display', 'none');
            $('#option_email').select2();
            $('#option_sms').next().css('display', 'none');
            $('#option_email').next().css('display', 'block');
            $('#option_notify').next().css('display', 'none');
            break;

        case 'notification':
            $('#div-sms').css('display', 'none');
            $('#div-email').css('display', 'none');
            $('#div-notify').css('display', 'block');
            $('#option_notify').select2();
            $('#option_sms').next().css('display', 'none');
            $('#option_email').next().css('display', 'none');
            $('#option_notify').next().css('display', 'block');
            break;

    }
}
function changeTabII() {
    switch ($('#id_ne>li>a.nav-link.active.son').text()) {
        case 'SMS':
            $('#option_sms').select2();
            $('#option_sms').next().css('display', 'block');
            $('#option_email').next().css('display', 'none');
            $('#option_notify').next().css('display', 'none');
            break;

        case 'EMAIL':
            $('#option_email').select2();
            $('#option_sms').next().css('display', 'none');
            $('#option_email').next().css('display', 'block');
            $('#option_notify').next().css('display', 'none');
            break;

        case 'NOTIFICATION':
            $('#option_notify').select2();
            $('#option_sms').next().css('display', 'none');
            $('#option_email').next().css('display', 'none');
            $('#option_notify').next().css('display', 'block');
            break;

    }
}
function progressCustomer(res){
    $.getJSON(laroute.route('translate'), function (json) {
        var s_sum_lead = res.mDataSmsLog[0].sum_lead ?? 0;
        var s_sum_customer = res.mDataSmsLog[0].sum_customer ?? 0;
        var totalSms = parseInt(s_sum_lead) + parseInt(s_sum_customer);
        rateLead = s_sum_lead / totalSms * 100;
        rateCustomer = 100 - rateLead;
        if (totalSms == 0) {
            rateLead = 50;
            rateCustomer = 50;
        }
        $('.sms_customer_approach').text(formatNumber(totalSms.toFixed(decimal_number)));
        var htmlSms = `
                             <div class="progress-bar bg-success" role="progressbar" style="width: ${rateLead}%" aria-valuenow="${s_sum_lead}" aria-valuemin="0" aria-valuemax="${totalSms}">${s_sum_lead} Lead</div>
                             <div class="progress-bar" role="progressbar" style="width: ${rateCustomer}%" aria-valuenow="${s_sum_customer}" aria-valuemin="0" aria-valuemax="${totalSms}">${s_sum_customer} KH</div>
                        `;
        $('#progress-sms').html('');
        $('#progress-sms').html(htmlSms);
        var e_sum_lead = res.mDataEmailLog[0].sum_lead ?? 0;
        var e_sum_customer = res.mDataEmailLog[0].sum_customer ?? 0;
        var totalEmail = parseInt(e_sum_lead) + parseInt(e_sum_customer);
        rateLead = e_sum_lead / totalEmail * 100;
        rateCustomer = 100 - rateLead;
        if (totalEmail == 0) {
            rateLead = 50;
            rateCustomer = 50;
        }
        $('.email_customer_approach').text(formatNumber(totalEmail.toFixed(decimal_number)));
        var htmlEmail = `
                             <div class="progress-bar bg-success" role="progressbar" style="width: ${rateLead}%" aria-valuenow="${e_sum_lead}" aria-valuemin="0" aria-valuemax="${totalEmail}">${e_sum_lead} Lead</div>
                             <div class="progress-bar" role="progressbar" style="width: ${rateCustomer}%" aria-valuenow="${e_sum_customer}" aria-valuemin="0" aria-valuemax="${totalEmail}">${e_sum_customer} KH</div>
                        `;
        $('#progress-email').html('');
        $('#progress-email').html(htmlEmail);
        var totalNotify = res.mDataNotifyLog[0].sum_customer;
        rateLead = 0;
        rateCustomer = 100;
        $('.notify_customer_approach').text(formatNumber(totalNotify.toFixed(decimal_number)));
        var htmlNotify = `
                             <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="${totalNotify}">0 Lead</div>
                             <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="${res.mDataNotifyLog[0].sum_customer}" aria-valuemin="0" aria-valuemax="${totalNotify}">${res.mDataNotifyLog[0].sum_customer} KH</div>
                        `;
        $('#progress-notify').html('');
        $('#progress-notify').html(htmlNotify);
    });
}
function getOptions(res) {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#option_sms').empty('');
        $('#option_email').empty('');
        $('#option_notify').empty('');
        $('.option_sms').append('<option value="">' + json["Chọn chiến dịch"] + '</option>');
        $('.option_email').append('<option value="">' + json["Chọn chiến dịch"] + '</option>');
        $('.option_notify').append('<option value="">' + json["Chọn chiến dịch"] + '</option>');
        $.map(res.optionSms, function (a) {
            if(res.filter.option_sms && res.filter.option_sms == a.campaign_id){
                $('.option_sms').append('<option value="' + a.campaign_id + '" selected>' + a.name + '</option>');
            }
            else{
                $('.option_sms').append('<option value="' + a.campaign_id + '">' + a.name + '</option>');
            }
        });
        $.map(res.optionEmail, function (a) {
            if(res.filter.option_email && res.filter.option_email == a.campaign_id){
                $('.option_email').append('<option value="' + a.campaign_id + '" selected>' + a.name + '</option>');
            }
            else{
                $('.option_email').append('<option value="' + a.campaign_id + '">' + a.name + '</option>');
            }
        });
        $.map(res.optionNotify, function (a) {
            if(res.filter.option_notify && res.filter.option_notify == a.notification_template_id){
                $('.option_notify').append('<option value="' + a.notification_template_id + '" selected>' + a.title + '</option>');
            }
            else{
                $('.option_notify').append('<option value="' + a.notification_template_id + '">' + a.title + '</option>');
            }
        });
    });

}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$('#option_sms').on('change', function(e){
    e.preventDefault();
    campaignOverviewReport.loadChartII();
})
$('#option_email').on('change', function(e){
    e.preventDefault();
    campaignOverviewReport.loadChartII();
})
$('#option_notify').on('change', function(e){
    e.preventDefault();
    campaignOverviewReport.loadChartII();
})