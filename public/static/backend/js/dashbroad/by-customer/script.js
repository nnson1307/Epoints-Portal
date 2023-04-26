var statisticCustomer = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#statistics_customer_time").daterangepicker({
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
                statisticCustomer.loadChart();
            });
            $('#statistics_customer_branch').select2().on('select2:select', function (event) {
                statisticCustomer.loadChart();
            });
            statisticCustomer.loadChart();
        });
        statisticCustomer.getCustomerRequestToday();
    },

    loadChart: function () {
        var time = $('#statistics_customer_time').val();
        var branch = $('#statistics_customer_branch').val();
        $('#statistics_customer_time_detail').val(time);
        $('#statistics_customer_branch_detail').val(branch);

        //Load filter export tổng
        $('#statistics_customer_export_time_total').val(time);
        $('#statistics_customer_export_branch_total').val(branch);
        //Load filter export chi tiết
        $('#statistics_customer_export_time_detail').val(time);
        $('#statistics_customer_export_branch_detail').val(branch);
        $.ajax({
            url: laroute.route('admin.report-growth.customer.filter'),
            method: "POST",
            data: {
                time: $('#statistics_customer_time').val(),
                branch: $('#statistics_customer_branch').val()
            },
            dataType: "JSON",
            success: function (res) {
                chartCustomer(res.chartCustomer);

                $('#statistics_customer_autotable').PioTable({
                    baseUrl: laroute.route('admin.report-growth.customer.list-detail')
                });

                $('.statistics_customer_btn-search').trigger('click');
            }
        });
    },

    getCustomerRequestToday: function () {
        
        $.ajax({
            url: laroute.route('dashbroad.get-customer-request-today'),
            method: 'POST',
            dataType: 'JSON',
            data:{
                page: 1
            },
            success: function (res) {
                if (res.html != null) {
                    $('#lstCustomerRequestToday').html(res.html);
                }else {
                    Swal.fire(
                        callCenter.jsontranslate['Thông Báo'],
                        callCenter.jsontranslate['Có lỗi xảy ra, hãy thử lại sau!'],
                        'error'
                    )
                }

            }
        });
    },
}

function chartCustomer(dataValue) {
    google.charts.load('current', {'packages': ['bar']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataValue);
        $.getJSON(laroute.route('translate'), function (json) {
            var options = {
                legend: {
                    position: 'top',
                    textStyle: {
                        color: '#454545',
                        fontSize: 13,
                        bold: true
                    }
                },
                chart: {
                    title: json['Số lượng'],
                },
                titleTextStyle: {
                    color: "#454545",
                    fontSize: 13,
                    bold: true
                },
                annotations: {
                    boxStyle: {
                        stroke: '#888',
                        strokeWidth: 1,
                        rx: 10,
                        ry: 10,
                        gradient: {
                            color1: '#fbf6a7',
                            color2: '#33b679',
                            x1: '0%', y1: '0%',
                            x2: '100%', y2: '100%',
                            useObjectBoundingBoxUnits: true
                        }
                    },
                    textStyle: {
                        fontName: 'Times-Roman',
                        fontSize: 12,
                        bold: true,
                        italic: true
                    }
                },
                colors: ['#f8ba05', '#f26d7e', '#4fc4cb', '#0404B4'],
                bar: {groupWidth: "20%"},
                vAxis: {
                    format: '#',
                    viewWindow: {min: 0},
                    tick: [1,2,3,4,5,6]
                },
            };
            var chart = new google.charts.Bar(document.getElementById('statistics-customer-chart-growth-customer'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        });
    }
}

