// $('.m_selectpicker').selectpicker();
var monthOneYear = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];

$("#created_at").empty();
$('#filter').select2();
$('#year').select2();
$('#filter-child').select2();
$('#filter').change(function () {
    $('#flag').val(0);
    if ($(this).val() != "") {
        $.ajax({
            url: laroute.route('admin.report-revenue.get-filter-child'),
            method: "POST",
            data: { filter: $(this).val() },
            dataType: "JSON",
            success: function (data) {
                $('#filter-child').empty();
                $.each(data, function (key, value) {
                    $.getJSON(laroute.route('translate'), function (json) {
                        if (key == '') {
                            $('#filter-child').prepend('<option selected value="">' + json['Tất cả'] + '</option>');
                        } else {
                            $('#filter-child').prepend('<option value="' + key + '">' + value + '</option>');
                        }
                    });
                });
            }
        });
        $("#btn-search").trigger("click");

    } else {
        $('#filter-child').empty();
        $('#filter-child').prepend('<option selected value="">' + json['Tất cả'] + '</option>');
        // $('#filter-child').val('').trigger('change')
        $("#btn-search").trigger("click");
    }
    setValueTotalChart();
});

Highcharts.setOptions({
    lang: {
        numericSymbols: [" Nghìn", " Triệu", " Tỉ", " T", " P", " E"]
    }
});

function visitorData(xxx, year, value, minWidth) {
    $.getJSON(laroute.route('translate'), function (json) {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#container').highcharts({
                chart: {
                    type: 'column',
                    scrollablePlotArea: {
                        minWidth: minWidth,
                    }
                },
                title: {
                    text: 'Báo cáo doanh thu năm ' + year + '</b>'
                },
                xAxis: {
                    categories: xxx
                },
                yAxis: {
                    title: {
                        text: json['Số tiền (VNĐ)']
                    },
                    min: 0
                },
                exporting: { enabled: false },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0"> </td>' +
                        '<td style="padding:0"><b>{point.y}' + json['VNĐ'] + '</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    },
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                legend: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 700
                        },
                        chartOptions: {
                            legend: {
                                enabled: false
                            }
                        }
                    }]
                },
                series: [{
                    showInLegend: false,
                    name: '',
                    data: value
                }]
            });
        });
    });
}

var dataArray = new Array();
$('.value-12-month').each(function () {
    dataArray.push(parseInt($(this).val()));
});
visitorData(monthOneYear, new Date().getFullYear(), dataArray);

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$('#btn-search').click(function () {
    let year = $('#year').val();
    let time = $('#time').val();
    if (time != '') {
        year = time.slice(-4);
    }
    let filter = $('#filter').val();
    let filterChild = '';
    if ($('#flag').val() == 1) {
        filterChild = $('#filter-child').val();
    }
    $.ajax({
        url: laroute.route('admin.report-revenue.filter'),
        method: "POST",
        data: {
            year: year,
            time: time,
            filter: filter,
            filterChild: filterChild,
        },
        dataType: "JSON",
        success: function (data) {
            $.getJSON(laroute.route('translate'), function (json) {
                if (year !== '' && time == '' && filter == '') {
                    $('#totalOrder').text(formatNumber(data['totalOrder']));
                    $('#totalMoney').text(formatNumber(data['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['totalOrderPayFail']));
                    $('#totalMoneyOrderPayFail').text(formatNumber(data['totalMoneyOrderPayFail']) + json[" VNĐ"]);
                    let timeOrder = new Array();
                    let value = new Array();
                    $.each(data['timeOrder'], function (key, value) {
                        timeOrder.push(value);
                    });
                    visitorData(monthOneYear, year, timeOrder, 700);
                } else if (filter != '' && time == '') {
                    if (filterChild != '') {
                        $('#totalOrder').text(formatNumber(data['totalOrder']));
                        $('#totalMoney').text(formatNumber(data['totalMoney']) + json[" VNĐ"]);
                        $('#totalOrderPaysuccess').text(formatNumber(data['totalOrderPaysuccess']) + json[" VNĐ"]);
                        $('#totalMoneyOrderPaysuccess').text(formatNumber(data['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                        $('#totalOrderNew').text(formatNumber(data['totalOrderNew']) + json[" VNĐ"]);
                        $('#totalMoneyOrderNew').text(formatNumber(data['totalMoneyOrderNew']) + json[" VNĐ"]);
                        $('#totalOrderPayFail').text(formatNumber(data['totalOrderPayFail']) + json[" VNĐ"]);
                        $('#totalMoneyOrderPayFail').text(formatNumber(data['totalMoneyOrderPayFail']) + json[" VNĐ"]);
                        visitorData(data['name'], year, data['value'], 700);
                    } else {
                        //cho chọn loại doanh thu.
                        if (data['name'].length > 10 && data['value'].length > 10) {
                            let xxx = new Array();
                            let yyy = new Array();

                            for (let i = 0; i < 9; i++) {
                                xxx.push(data['name'][i]);
                            }
                            xxx.push('Khác');

                            for (let j = 0; j < 9; j++) {
                                yyy.push(data['value'][j]);
                            }
                            let other = 0;
                            for (let z = 9; z < data['value'].length; z++) {
                                other += data['value'][z];
                            }
                            yyy.push(other);
                            visitorData(xxx, year, yyy, 700);
                        } else {
                            visitorData(data['name'], year, data['value'], 700);
                        }
                        $('#totalOrder').text(formatNumber(data['totalOrder']));
                        $('#totalMoney').text(formatNumber(data['totalMoney']) + json[" VNĐ"]);
                        $('#totalOrderPaysuccess').text(formatNumber(data['totalOrderPaysuccess']));
                        $('#totalMoneyOrderPaysuccess').text(formatNumber(data['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                        $('#totalOrderNew').text(formatNumber(data['totalOrderNew']));
                        $('#totalMoneyOrderNew').text(formatNumber(data['totalMoneyOrderNew']) + json[" VNĐ"]);
                        $('#totalOrderPayFail').text(formatNumber(data['totalOrderPayFail']));
                        $('#totalMoneyOrderPayFail').text(formatNumber(data['totalMoneyOrderPayFail']) + json[" VNĐ"]);
                    }

                } else if (filter == '') {

                    if (data['day'].length > 10 && data['valueDay'].length > 10) {
                        let xxx = new Array();
                        let yyy = new Array();
                        for (let i = 0; i < 9; i++) {
                            xxx.push(data['day'][i]);
                        }
                        xxx.push('Khác');

                        for (let j = 0; j < 9; j++) {
                            yyy.push(data['valueDay'][j]);
                        }
                        let other = 0;
                        for (let z = 9; z < data['valueDay'].length; z++) {
                            other += data['valueDay'][z];
                        }
                        yyy.push(other);
                        visitorData(xxx, year, yyy, 700);
                    } else {
                        visitorData(data['day'], year, data['valueDay'], 700);
                    }
                    $('#totalOrder').text(formatNumber(data['result']['totalOrder']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                    $('#totalMoneyOrderPayFail').text(formatNumber(data['result']['totalMoneyOrderPayFail']) + json[" VNĐ"]);
                } else if (filter != '' && time != '' && filterChild == '') {
                    if (data['day'].length > 10 && data['valueDay'].length > 10) {
                        let xxx = new Array();
                        let yyy = new Array();
                        for (let i = 0; i < 9; i++) {
                            xxx.push(data['day'][i]);
                        }
                        xxx.push('Khác');

                        for (let j = 0; j < 9; j++) {
                            yyy.push(data['valueDay'][j]);
                        }
                        let other = 0;
                        for (let z = 9; z < data['valueDay'].length; z++) {
                            other += data['valueDay'][z];
                        }
                        yyy.push(other);
                        visitorData(xxx, year, yyy, 700);
                    } else {
                        visitorData(data['day'], year, data['valueDay'], 700);
                    }
                    $('#totalOrder').text(formatNumber(data['result']['totalOrder']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                    $('#totalMoneyOrderPayFail').text(formatNumber(data['result']['totalMoneyOrderPayFail']) + json[" VNĐ"]);
                } else if (filter != '' && time != '' && filterChild != '') {
                    let px = 700;
                    if (data['day'].length > 12) {
                        px = 1920;
                    }
                    visitorData(data['day'], year, data['valueDay'], px);

                    $('#totalOrder').text(formatNumber(data['totalOrder']));
                    $('#totalMoney').text(formatNumber(data['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['totalOrderPayFail']));
                    $('#totalMoneyOrderPayFail').text(formatNumber(data['totalMoneyOrderPayFail']));
                }
            });
        }
    })
});
$('#year').change(function () {
    $('#time').val('');
    $("#filter").val('').trigger("change");
    $("#filter-child").val('').trigger("change");
    $("#btn-search").trigger("click");
});
$('#filter-child').change(function () {
    $('#flag').val(1);
    $("#btn-search").trigger("click");
});

$('#time').on('apply.daterangepicker', function (ev, picker) {
    var start = picker.startDate.format("DD/MM/YYYY");
    var end = picker.endDate.format("DD/MM/YYYY");
    $(this).val(start + " - " + end);

    $("#btn-search").trigger("click");
});

function setValueTotalChart() {
    $.getJSON(laroute.route('translate'), function (json) {
    $('#totalOrder').text($('.totalOrder').val());
    $('#totalMoney').text($('.totalMoney').val() + json[" VNĐ"]);
    $('#totalOrderPaysuccess').text($('.totalOrderPaysuccess').val());
    $('#totalMoneyOrderPaysuccess').text($('.totalMoneyOrderPaysuccess').val() + json[" VNĐ"]);
    $('#totalOrderNew').text($('.totalOrderNew').val());
    $('#totalMoneyOrderNew').text($('.totalMoneyOrderNew').val() + json[" VNĐ"]);
    $('#totalOrderPayFail').text($('.totalOrderPayFail').val());
    $('#totalMoneyOrderPayFail').text($('.totalMoneyOrderPayFail').val() + json[" VNĐ"]);
    });
}
$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
        // Chọn ngày.
        $("#time").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            maxDate: moment().endOf("day"),
            startDate: moment().startOf("day"),
            endDate: moment().add(1, 'days'),
            locale: {
                format: 'DD/MM/YYYY',
                "applyLabel": "Đồng ý",
                "cancelLabel": "Thoát",
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
        }).on('apply.daterangepicker', function (ev) {

        });
});
$('.range_inputs').remove();
// var BootstrapDaterangepicker = function () {
//
//     //== Private functions
//     var demos = function () {
//         // minimum setup
//         $('#m_daterangepicker_1, #m_daterangepicker_1_modal').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary'
//         }, function (start, end, label) {
//             $('#time').change();
//             $('#m_daterangepicker_1 .form-control').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
//         });
//         // input group and left alignment setup
//         $('#m_daterangepicker_2').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary'
//         }, function (start, end, label) {
//             $('#time').change();
//             $('#m_daterangepicker_2 .form-control').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
//         });
//
//         $('#m_daterangepicker_2_modal').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary'
//         }, function (start, end, label) {
//             $('#time').change();
//             $('#m_daterangepicker_2 .form-control').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
//         });
//
//         // left alignment setup
//         $('#m_daterangepicker_3').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary'
//         }, function (start, end, label) {
//             $('#time').change();
//             $('#m_daterangepicker_3 .form-control').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
//         });
//
//         $('#m_daterangepicker_3_modal').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary'
//         }, function (start, end, label) {
//             $('#time').change();
//             $('#m_daterangepicker_3 .form-control').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
//         });
//
//
//         // date & time
//         $('#m_daterangepicker_4').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary',
//
//             timePicker: true,
//             timePickerIncrement: 30,
//             locale: {
//                 format: 'MM/DD/YYYY h:mm A'
//             }
//         }, function (start, end, label) {
//             $('#time').change();
//             $('#m_daterangepicker_4 .form-control').val(start.format('MM/DD/YYYY h:mm A') + ' - ' + end.format('MM/DD/YYYY h:mm A'));
//         });
//
//         // date picker
//         $('#m_daterangepicker_5').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary',
//
//             singleDatePicker: true,
//             showDropdowns: true,
//             locale: {
//                 format: 'MM/DD/YYYY'
//             }
//         }, function (start, end, label) {
//             $('#time').change();
//             $('#m_daterangepicker_5 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
//         });
//
//         // predefined ranges
//         var start = moment().subtract(29, 'days');
//         var end = moment();
//
//         $('#m_daterangepicker_6').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary',
//
//             startDate: start,
//             endDate: end,
//             ranges: {
//                 'Trong ngày': [moment(), moment()],
//                 'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//                 '7 ngày trước': [moment().subtract(6, 'days'), moment()],
//                 '30 ngày trước': [moment().subtract(29, 'days'), moment()],
//                 'Trong tháng': [moment().startOf('month'), moment().endOf('month')],
//                 'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
//             },
//
//             autoUpdateInput: false,
//             autoApply: true,
//             locale: {
//                 format: 'DD/MM/YYYY',
//                 "customRangeLabel": "Tùy chọn ngày",
//                 daysOfWeek: [
//                     "CN",
//                     "T2",
//                     "T3",
//                     "T4",
//                     "T5",
//                     "T6",
//                     "T7"
//                 ],
//                 "monthNames": [
//                     "Tháng 1 năm",
//                     "Tháng 2 năm",
//                     "Tháng 3 năm",
//                     "Tháng 4 năm",
//                     "Tháng 5 năm",
//                     "Tháng 6 năm",
//                     "Tháng 7 năm",
//                     "Tháng 8 năm",
//                     "Tháng 9 năm",
//                     "Tháng 10 năm",
//                     "Tháng 11 năm",
//                     "Tháng 12 năm"
//                 ],
//                 "firstDay": 1
//             }
//         }, function (start, end, label) {
//             $('#time').change();
//             $('#m_daterangepicker_6 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
//         });
//     }
//
//     var validationDemos = function () {
//         // input group and left alignment setup
//         $('#m_daterangepicker_1_validate').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary'
//         }, function (start, end, label) {
//             $('#m_daterangepicker_1_validate .form-control').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
//         });
//
//         // input group and left alignment setup
//         $('#m_daterangepicker_2_validate').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary'
//         }, function (start, end, label) {
//             $('#m_daterangepicker_3_validate .form-control').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
//         });
//
//         // input group and left alignment setup
//         $('#m_daterangepicker_3_validate').daterangepicker({
//             buttonClasses: 'm-btn btn',
//             applyClass: 'btn-primary',
//             cancelClass: 'btn-secondary'
//         }, function (start, end, label) {
//             $('#m_daterangepicker_3_validate .form-control').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
//         });
//     };
//
//     return {
//         // public functions
//         init: function () {
//             demos();
//             validationDemos();
//         }
//     };
// }();
//
// jQuery(document).ready(function () {
//     BootstrapDaterangepicker.init();
// });


// $('#time').change(function () {
//     console.log($('#time').val());
// });