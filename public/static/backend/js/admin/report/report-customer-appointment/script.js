$(document).ready(function () {
    $("#time-hidden").daterangepicker({
        startDate: moment().subtract(6, "days"),
        endDate: moment(),
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    $('#time').val($("#time-hidden").val());

    $('#branch_id').select2({}).on('select2:select', function (event) {
        var time = $('#time').val();
        var branch_id = event.params.data.id;
        report.filter();
    });
    $.getJSON(laroute.route('translate'), function (json) {
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
            buttonClasses: "m-btn btn",
            applyClass: "btn-primary",
            cancelClass: "btn-danger",
            // maxDate: moment().endOf("day"),
            // startDate: moment().startOf("day"),
            // endDate: moment().add(1, 'days'),
            locale: {
                format: 'DD/MM/YYYY',
                "applyLabel": json["Đồng ý"],
                "cancelLabel": json["Thoát"],
                "customRangeLabel": json["Tùy chọn ngày"],
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
        }).on('apply.daterangepicker', function (ev, picker) {
            var start = picker.startDate.format("DD/MM/YYYY");
            var end = picker.endDate.format("DD/MM/YYYY");
            $(this).val(start + " - " + end);
            var time = $(this).val();
            var branch_id = $('#branch_id').val();
            report.filter();
        });
        if ($('#branch_id').val() != '') {
            report.filter();
        } else {
            index();
        }
    });
});

var report = {
    chart_report: function (dataValue) {

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            
            
            var data =google.visualization.arrayToDataTable(dataValue);
            // var data = google.visualization.arrayToDataTable(dataValue);
            // var options
            $.getJSON(laroute.route('translate'), function (json) {
            // var data = google.visualization.arrayToDataTable(dataValue);
            var options = {
                
                title: json['Số lịch hẹn'],
                titleTextStyle: {
                    color: "#454545",
                    fontSize: 13,
                    bold: true
                },
                legend: {
                    position: 'right',
                    textStyle: {
                        color: '#454545',
                        fontSize: 13,
                        bold: true
                    }
                },
                titlePosition: 'out',
                hAxis: {title: '', titleTextStyle: {color: '#333', fontSize: 12}},
                vAxis: {
                    minValue: 0,
                    format: '0',
                    min: 0
                },
                colors: ['#4fc4cb', '#0198d1', '#f26d7e', '#f8ba05', '#005825', '#f36523'],
                height: "450px",
                width: "80%",
                chartArea: {
                    height: "450px",
                    width: "80%",
                    left: 30
                }
            };
            var chart = new google.visualization.AreaChart(document.getElementById('container'));
            chart.draw(data, options);
        });
           
        }
    },
    report_source_appointment: function (dataValue) {
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(dataValue);

            var options = {
                title: '',
                pieHole: 0.4,
                legend: {position: 'bottom'},
                chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
                enableInteractivity: false,
                colors: ['#4fc4cb', '#e3d500', '#72b3b7', '#12801f', '#a3d9b8', '#f06eaa', '#f8ba05', '#fec689'],

            };

            var chart = new google.visualization.PieChart(document.getElementById('source-appointment'));
            chart.draw(data, options);
        }
    },
    report_gender: function (dataValue) {
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(dataValue);

            var options = {
                title: '',
                pieHole: 0.4,
                legend: {position: 'bottom'},
                colors: ['#3468be', '#e3d500', '#f06eaa'],
                chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
                enableInteractivity: false,
            };

            var chart = new google.visualization.PieChart(document.getElementById('gender'));
            chart.draw(data, options);
        }
    },
    report_customer_source: function (dataValue) {
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(dataValue);

            var options = {
                title: '',
                pieHole: 0.4,
                legend: {position: 'bottom'},
                colors: ['#12801f', '#a3d9b8', '#fec689', '#e3d500', '#f06eaa', '#f8ba05', '#4fc4cb', '#ebebeb', '#72b3b7'],
                chartArea: {left: 0, top: 7, right: 0, width: '50%', height: '75%'},
                enableInteractivity: false,
            };

            var chart = new google.visualization.PieChart(document.getElementById('customer-source'));
            chart.draw(data, options);
        }
    },
    filter: function () {
        $.ajax({
            url: laroute.route('admin.report-customer-appointment.filter-time'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                time: $('#time').val(),
                branch_id: $('#branch_id').val()
            },
            success: function (data) {
                report.chart_report(data['dataValue']);
                report.report_source_appointment(data['dataCustomeAppointmentSource']);
                report.report_gender(data['dataCustomeGender']);
                report.report_customer_source(data['dataCustomeSource']);

            }

        });
    }
};

function getSum(total, num) {
    return total + num;
}

function index() {
    $.ajax({
        url: laroute.route('admin.report-customer-appointment.load-index'),
        dataType: 'JSON',
        method: 'post',
        data: {
            time: $('#time').val(),
            branch_id: $('#branch_id').val()
        },
        success: function (data) {
            report.chart_report(data['dataValue']);
            report.report_source_appointment(data['dataCustomeAppointmentSource']);
            report.report_gender(data['dataCustomeGender']);
            report.report_customer_source(data['dataCustomeSource']);
        }
    });
}

// function filterBranch(time, branch_id) {
//     $.ajax({
//         url: laroute.route('admin.report-customer-appointment.filter-branch'),
//         dataType: 'JSON',
//         method: 'post',
//         data: {
//             branch_id: branch_id,
//             time: time
//         },
//         success: function (res) {
//             mApp.unblock(".m_blockui_1_content");
//             if (res.time_null == 1) {
//                 var cate = [];
//                 var data_all = [];
//                 var data_new = [];
//                 var data_confirm = [];
//                 var data_wait = [];
//                 var data_cancel = [];
//                 var data_finish = [];
//                 var data_appointment_source = [];
//                 var data_gender = [];
//                 var data_cus_source = [];
//                 $.map(res.month, function (a) {
//                     cate.push('Tháng ' + a)
//                 });
//                 $.map(res.data_column, function (a) {
//                     data_all.push((parseInt(a.number_new) + parseInt(a.number_confirm) + parseInt(a.number_wait) + parseInt(a.number_cancel) + parseInt(a.number_finish)));
//                     data_new.push(a.number_new);
//                     data_confirm.push(a.number_confirm);
//                     data_wait.push(a.number_wait);
//                     data_cancel.push(a.number_cancel);
//                     data_finish.push(a.number_finish);
//                 });
//                 $.map(res.data_appointment_source, function (c) {
//                     data_appointment_source.push({
//                         'name': c.name,
//                         'y': c.number
//                     });
//                 });
//                 $.map(res.data_gender, function (d) {
//                     var name = '';
//                     if (d.gender == 'male') {
//                         name = 'Nam';
//                     } else if (d.gender == 'female') {
//                         name = 'Nữ';
//                     } else {
//                         name = 'Khác';
//                     }
//                     data_gender.push({
//                         'name': name,
//                         'y': d.number
//                     });
//                 });
//                 $.map(res.data_cus_source, function (e) {
//                     data_cus_source.push({
//                         'name': e.name,
//                         'y': e.number
//                     });
//                 });
//                 report.chart_report(cate, data_all, data_new, data_confirm, data_wait, data_cancel, data_finish);
//                 report.report_source_appointment(data_appointment_source);
//                 report.report_gender(data_gender);
//                 report.report_customer_source(data_cus_source);
//             }
//             if (res.time_null == 0) {
//                 var cate = [];
//                 var data_all = [];
//                 var data_new = [];
//                 var data_confirm = [];
//                 var data_wait = [];
//                 var data_cancel = [];
//                 var data_finish = [];
//                 var data_appointment_source = [];
//                 var data_gender = [];
//                 var data_cus_source = [];
//                 $.map(res.date, function (b) {
//                     cate.push(b);
//                 });
//                 $.map(res.data_column, function (a) {
//                     data_all.push((parseInt(a.number_new) + parseInt(a.number_confirm) + parseInt(a.number_wait) + parseInt(a.number_cancel) + parseInt(a.number_finish)));
//                     data_new.push(a.number_new);
//                     data_confirm.push(a.number_confirm);
//                     data_cancel.push(a.number_cancel);
//                     data_finish.push(a.number_finish);
//                     data_wait.push(a.number_wait);
//                 });
//
//                 $.map(res.data_appointment_source, function (c) {
//                     data_appointment_source.push({
//                         'name': c.name,
//                         'y': c.number
//                     });
//                 });
//                 $.map(res.data_gender, function (d) {
//                     var name = '';
//                     if (d.gender == 'male') {
//                         name = 'Nam';
//                     } else if (d.gender == 'female') {
//                         name = 'Nữ';
//                     } else {
//                         name = 'Khác';
//                     }
//                     data_gender.push({
//                         'name': name,
//                         'y': d.number
//                     });
//                 });
//                 $.map(res.data_cus_source, function (e) {
//                     data_cus_source.push({
//                         'name': e.name,
//                         'y': e.number
//                     });
//                 });
//                 report.chart_report(cate, data_all, data_new, data_confirm, data_wait, data_cancel, data_finish);
//                 report.report_source_appointment(data_appointment_source);
//                 report.report_gender(data_gender);
//                 report.report_customer_source(data_cus_source);
//             }
//         }
//     })
// }

// function filterTime(time, branch_id) {
//     $.ajax({
//         url: laroute.route('admin.report-customer-appointment.filter-time'),
//         dataType: 'JSON',
//         method: 'POST',
//         data: {
//             time: time,
//             branch_id: branch_id
//         },
//         success: function (res) {
//             if (res.branch_id_null == 1) {
//                 var cate = [];
//                 var data_all = [];
//                 var data_new = [];
//                 var data_confirm = [];
//                 var data_wait = [];
//                 var data_cancel = [];
//                 var data_finish = [];
//                 var data_appointment_source = [];
//                 var data_gender = [];
//                 var data_cus_source = [];
//                 $.map(res.branch, function (b) {
//                     cate.push(b);
//                 });
//                 $.map(res.data_column, function (a) {
//                     data_all.push((parseInt(a.number_new) + parseInt(a.number_confirm) + parseInt(a.number_wait) + parseInt(a.number_cancel) + parseInt(a.number_finish)));
//                     data_new.push(a.number_new);
//                     data_confirm.push(a.number_confirm);
//                     data_cancel.push(a.number_cancel);
//                     data_finish.push(a.number_finish);
//                     data_wait.push(a.number_wait);
//                 });
//                 $.map(res.data_appointment_source, function (c) {
//
//                     data_appointment_source.push({
//                         'name': c.name,
//                         'y': c.number
//                     });
//                 });
//                 $.map(res.data_gender, function (d) {
//                     var name = '';
//                     if (d.gender == 'male') {
//                         name = 'Nam';
//                     } else if (d.gender == 'female') {
//                         name = 'Nữ';
//                     } else {
//                         name = 'Khác';
//                     }
//                     data_gender.push({
//                         'name': name,
//                         'y': d.number
//                     });
//                 });
//                 $.map(res.data_cus_source, function (e) {
//                     data_cus_source.push({
//                         'name': e.name,
//                         'y': e.number
//                     });
//                 });
//                 report.chart_report(cate, data_all, data_new, data_confirm, data_wait, data_cancel, data_finish);
//                 report.report_source_appointment(data_appointment_source);
//                 report.report_gender(data_gender);
//                 report.report_customer_source(data_cus_source);
//             }
//             if (res.branch_id_null == 0) {
//                 var cate = [];
//                 var data_all = [];
//                 var data_new = [];
//                 var data_confirm = [];
//                 var data_wait = [];
//                 var data_cancel = [];
//                 var data_finish = [];
//                 var data_appointment_source = [];
//                 var data_gender = [];
//                 var data_cus_source = [];
//                 $.map(res.date, function (b) {
//                     cate.push(b);
//                 });
//                 $.map(res.data_column, function (a) {
//                     data_all.push((parseInt(a.number_new) + parseInt(a.number_confirm) + parseInt(a.number_wait) + parseInt(a.number_cancel) + parseInt(a.number_finish)));
//                     data_new.push(a.number_new);
//                     data_confirm.push(a.number_confirm);
//                     data_wait.push(a.number_wait);
//                     data_cancel.push(a.number_cancel);
//                     data_finish.push(a.number_finish);
//                 });
//
//                 $.map(res.data_appointment_source, function (c) {
//
//                     data_appointment_source.push({
//                         'name': c.name,
//                         'y': c.number
//                     });
//                 });
//                 $.map(res.data_gender, function (d) {
//                     var name = '';
//                     if (d.gender == 'male') {
//                         name = 'Nam';
//                     } else if (d.gender == 'female') {
//                         name = 'Nữ';
//                     } else {
//                         name = 'Khác';
//                     }
//                     data_gender.push({
//                         'name': name,
//                         'y': d.number
//                     });
//                 });
//                 $.map(res.data_cus_source, function (e) {
//                     data_cus_source.push({
//                         'name': e.name,
//                         'y': e.number
//                     });
//                 });
//                 report.chart_report(cate, data_all, data_new, data_confirm, data_wait, data_cancel, data_finish);
//                 report.report_source_appointment(data_appointment_source);
//                 report.report_gender(data_gender);
//                 report.report_customer_source(data_cus_source);
//             }
//
//         }
//     })
// }//
