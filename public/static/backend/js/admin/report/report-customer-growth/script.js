$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
    Highcharts.setOptions({
        lang: {
            numericSymbols: [json[" ngàn"], json[" triệu"], json[" tỉ"], "T", "P", "E"],
            printChart:json['In biểu đồ'],
            downloadJPEG:json["Tải hình ảnh JPEG"],
            downloadPNG:json["Tải hình ảnh PNG"],
            downloadPDF:json['Tải tệp PDF'],
            downloadSVG:json['Tải hình ảnh vector SVG'],
            downloadCSV:json['Tải tệp CSV'],
            downloadXLS:json['Tải tệp XLS'],
            viewData:json['Xem bảng dữ liệu']
        }
    });
    mApp.block(".m_blockui_1_content", {
        overlayColor: "#000000",
        type: "loader",
        state: "success",
        size: "lg",
        message: json["Đang tải..."]
    });
    $.ajax({
        url: laroute.route('admin.report-customer-growth.load-report'),
        dataType: 'JSON',
        data: {},
        method: 'POST',
        success: function (res) {
            mApp.unblock(".m_blockui_1_content");
            var cus_old = (res.total_customer.number) - (res.total_now.number);
            var data = {
                'name': json['Tất cả chi nhánh'],
                'colorByPoint': true,
                'data': [
                    res.total_customer.number, cus_old, res.total_now.number
                ]
            };
            report.chart_report(res.year, data);
        }
    });
    $('#year').select2({
        placeholder: json['Hãy chọn năm']
    }).on('select2:select', function (event) {
        mApp.block(".m_blockui_1_content", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: json["Đang tải..."]
        });
        $('#time').val('');
        $.ajax({
            url: laroute.route('admin.report-customer-growth.year-branch'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                year: event.params.data.id,
                branch_id: $('#branch_id').val()
            }, success: function (res) {
                mApp.unblock(".m_blockui_1_content");
                var data = '';
                var cus_old = (res.total_year_branch.number) - (res.total_now_year_branch.number);
                if (res.branch_name != '') {
                    data = {
                        'name': res.branch_name,
                        'colorByPoint': true,
                        'data': [
                            res.total_year_branch.number, cus_old, res.total_now_year_branch.number
                        ]
                    };
                } else {
                    data = {
                        'name': json['Tất cả chi nhánh'],
                        'colorByPoint': true,
                        'data': [
                            res.total_year_branch.number, cus_old, res.total_now_year_branch.number
                        ]
                    };
                }
                report.chart_report(res.year, data);

            }
        });
    });

    $('#branch_id').select2({
        placeholder: json['Chọn chi nhánh'],
        allowClear: true
    }).on('select2:select', function (event) {
        var time = $('#time').val();
        if (time == '') {
            mApp.block(".m_blockui_1_content", {
                overlayColor: "#000000",
                type: "loader",
                state: "success",
                message: json["Đang tải..."]
            });
            $.ajax({
                url: laroute.route('admin.report-customer-growth.year-branch'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    year: $('#year').val(),
                    branch_id: $(this).val()
                }, success: function (res) {
                    mApp.unblock(".m_blockui_1_content");
                    var data = '';
                    var cus_old = (res.total_year_branch.number) - (res.total_now_year_branch.number);
                    if (res.branch_name != '') {
                        data = {
                            'name': res.branch_name,
                            'colorByPoint': true,
                            'data': [
                                res.total_year_branch.number, cus_old, res.total_now_year_branch.number
                            ]
                        };
                    } else {
                        data = {
                            'name': json['Tất cả chi nhánh'],
                            'colorByPoint': true,
                            'data': [
                                res.total_year_branch.number, cus_old, res.total_now_year_branch.number
                            ]
                        };
                    }
                    report.chart_report(res.year, data);

                }
            });
        }else{
            mApp.block(".m_blockui_1_content", {
                overlayColor: "#000000",
                type: "loader",
                state: "success",
                message: json["Đang tải..."]
            });
            $.ajax({
                url:laroute.route('admin.report-customer-growth.time-branch'),
                dataType:'JSON',
                method:'POST',
                data:{
                    time:$('#time').val(),
                    branch_id:$(this).val()
                },success:function (res) {
                    mApp.unblock(".m_blockui_1_content");
                    var cus_old = (res.total_time_branch.number) - (res.total_now_branch.number);
                    if (res.branch_name != '') {
                        data = {
                            'name': res.branch_name,
                            'colorByPoint': true,
                            'data': [
                                res.total_time_branch.number,cus_old,res.total_now_branch.number
                            ]
                        };
                    } else {
                        data = {
                            'name': json['Tất cả chi nhánh'],
                            'colorByPoint': true,
                            'data': [
                                res.total_time_branch.number,cus_old,res.total_now_branch.number
                            ]
                        };
                    }
                    report.chart_report(res.time, data);
                }
            });
        }

    }).on('select2:unselect', function (event) {
        var time = $('#time').val();
        if (time == '') {
            mApp.block(".m_blockui_1_content", {
                overlayColor: "#000000",
                type: "loader",
                state: "success",
                message: json["Đang tải..."]
            });
            $.ajax({
                url: laroute.route('admin.report-customer-growth.year-branch'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    year: $('#year').val(),
                    branch_id: $(this).val()
                }, success: function (res) {
                    mApp.unblock(".m_blockui_1_content");

                    var data = '';
                    var cus_old = (res.total_year_branch.number) - (res.total_now_year_branch.number);
                    if (res.branch_name != '') {
                        data = {
                            'name': res.branch_name,
                            'colorByPoint': true,
                            'data': [
                                res.total_year_branch.number, cus_old, res.total_now_year_branch.number
                            ]
                        };
                    } else {
                        data = {
                            'name': json['Tất cả chi nhánh'],
                            'colorByPoint': true,
                            'data': [
                                res.total_year_branch.number, cus_old, res.total_now_year_branch.number
                            ]
                        };
                    }
                    report.chart_report(res.year, data);

                }
            });
        }else{
            mApp.block(".m_blockui_1_content", {
                overlayColor: "#000000",
                type: "loader",
                state: "success",
                message: json["Đang tải..."]
            });
            $.ajax({
                url:laroute.route('admin.report-customer-growth.time-branch'),
                dataType:'JSON',
                method:'POST',
                data:{
                    time:$('#time').val(),
                    branch_id:$(this).val()
                },success:function (res) {
                    mApp.unblock(".m_blockui_1_content");
                    var cus_old = (res.total_time_branch.number) - (res.total_now_branch.number);
                    if (res.branch_name != '') {
                        data = {
                            'name': res.branch_name,
                            'colorByPoint': true,
                            'data': [
                                res.total_time_branch.number,cus_old,res.total_now_branch.number
                            ]
                        };
                    } else {
                        data = {
                            'name': json['Tất cả chi nhánh'],
                            'colorByPoint': true,
                            'data': [
                                res.total_time_branch.number,cus_old,res.total_now_branch.number
                            ]
                        };
                    }
                    report.chart_report(res.time, data);
                }
            });
        }

    });
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

    $("#time").daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        // buttonClasses: "m-btn btn",
        // applyClass: "btn-primary",
        // cancelClass: "btn-danger",

        maxDate: moment().endOf("day"),
        startDate: moment().startOf("day"),
        endDate: moment().add(1, 'days'),
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY',
                // "applyLabel": "Đồng ý",
                // "cancelLabel": "Thoát",
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
    }).on('apply.daterangepicker', function (ev) {
        $('#year').val(null).trigger('change');
        mApp.block(".m_blockui_1_content", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: json["Đang tải..."]
        });
        $.ajax({
            url:laroute.route('admin.report-customer-growth.time-branch'),
            dataType:'JSON',
            method:'POST',
            data:{
                time:$(this).val(),
                branch_id:$('#branch_id').val()
            },success:function (res) {
                mApp.unblock(".m_blockui_1_content");
                var cus_old = (res.total_time_branch.number) - (res.total_now_branch.number);
                if (res.branch_name != '') {
                    data = {
                        'name': res.branch_name,
                        'colorByPoint': true,
                        'data': [
                            res.total_time_branch.number,cus_old,res.total_now_branch.number
                        ]
                    };
                } else {
                    data = {
                        'name': json['Tất cả chi nhánh'],
                        'colorByPoint': true,
                        'data': [
                            res.total_time_branch.number,cus_old,res.total_now_branch.number
                        ]
                    };
                }
                report.chart_report(res.time, data);
            }
        });

    });
    $('#time').val('');
});
});

var report = {
    chart_report: function (year, data) {
        $.getJSON(laroute.route('translate'), function (json) {
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: json['Lượng Khách Hàng  '] + year
            },
            // subtitle: {
            //     text: 'piospa.com'
            // },
            xAxis: {
                categories: [
                    json['Tất cả'],
                    json['Khách hàng cũ'],
                    json['Khách hàng mới'],
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: json['Số lượng khách hàng']
                }
            },
            tooltip: {
                // headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr>' +
                // '<td style="color:{series.color};padding:0">{point.key}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
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
            series: [
                data
            ]
        });
    });
    }
};
