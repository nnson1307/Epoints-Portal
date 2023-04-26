Highcharts.setOptions({
    lang: {
        numericSymbols: [" Nghìn", " Triệu", " Tỉ", " T", " P", " E"]
    }
});

//Biểu đồ
function visitorData(title, xAxis, data) {
    $('#container').highcharts({
        title: {
            text: 'Báo cáo doanh thu ' + title
        },
        xAxis: {
            categories: xAxis
        },
        yAxis: {
            title: {
                text: 'Số tiền (VNĐ)'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            // series: {
            //     label: {
            //         connectorAllowed: false
            //     },
            //     pointStart: 0
            // }
        },

        series: data,
        exporting: { enabled: false },
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
}

// Chọn ngày.
$('#time').on('apply.daterangepicker', function (ev, picker) {
    var start = picker.startDate.format("DD/MM/YYYY");
    var end = picker.endDate.format("DD/MM/YYYY");
    $(this).val(start + " - " + end);
    let flag = 1;
    $("#year option").each(function () {
        if ($(this).val() == '') {
            flag = 0;
        }
    });
    if (flag == 1) {
        $('#year').prepend('<option value="" selected>Chọn năm</option>');
    }
    $('#year').val('').trigger('change');
    $('#branch').val('').trigger('change');
    $('#service-categoty').val('').trigger('change');
    ajaxHighChart();
});
$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
        $("#time").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            maxDate: moment().endOf("day"),
            startDate: moment().startOf("day"),
            endDate: moment().add(1, 'days'),
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
        }).on('apply.daterangepicker', function (ev) {

        });
});
$('.range_inputs').remove();

//Biểu đồ index.
var monthOneYear = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];

$.ajax({
    url: laroute.route('admin.filter-report-service'),
    method: "POST",
    data: {
        year: $('#year').val(),
    },
    dataType: "JSON",
    success: function (data) {
        let valueMonth = new Array();
        $.each(data['valueMonth'], function (key, value) {
            valueMonth.push(value);
        });
        var data2 = [{
            name: 'Cả năm',
            data: valueMonth
        }];
        visitorData($('#year').val(), monthOneYear, data2);

        $('#totalOrder').text(formatNumber(data['result']['totalQuantity']));
        $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
        $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
        $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
        $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
        $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
        $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
        $('#totalMoneyOrderPayFail').text(formatNumber(data['result']['totalMoneyOrderPayFail']) + json[" VNĐ"]);
    }
});

function ajaxHighChart() {
    let year = $('#year').val();
    let time = $('#time').val();
    let branch = $('#branch').val();
    let serviceCategory = $('#service-categoty').val();
    $.ajax({
        url: laroute.route('admin.filter-report-service'),
        method: "POST",
        data: {
            year: $('#year').val(),
            time: $('#time').val(),
            branch: $('#branch').val(),
            serviceCategory: $('#service-categoty').val(),
        },
        dataType: "JSON",
        success: function (data) {
            $.getJSON(laroute.route('translate'), function (json) {
                if (year != '' && time == '' && branch == '' && serviceCategory == '') {
                    //Năm.
                    let valueMonth = new Array();
                    $.each(data['valueMonth'], function (key, value) {
                        valueMonth.push(value);
                    });
                    let data2 = [{
                        name: json['Cả năm'],
                        data: valueMonth
                    }];
                    visitorData($('#year').val(), monthOneYear, data2);
                    $('#totalOrder').text(formatNumber(data['result']['totalQuantity']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                    $('#totalMoneyOrderPayFail').text(formatNumber(data['result']['totalMoneyOrderPayFail']) + json[" VNĐ"]);

                } else if (year == '' && time != '' && branch == '' && serviceCategory == '') {
                    //Từ ngày đến ngày.
                    let valueDay = new Array();
                    $.each(data['valueDay'], function (key, value) {
                        valueDay.push(value);
                    });
                    let data2 = [{
                        name: 'Theo ngày',
                        data: valueDay
                    }];
                    visitorData($('#year').val(), data['day'], data2);
                    $('#totalOrder').text(formatNumber(data['result']['totalQuantity']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                    $('#totalMoneyOrderPayFail').text(formatNumber(data['result']['totalMoneyOrderPayFail']) + json[" VNĐ"]);

                } else if (year != '' && time == '' && branch != '' && serviceCategory == '') {
                    //Năm và chi nhánh
                    if (data['flag'] == 1) {
                        var arrayGeneral = new Array();
                        for (let i = 0; i < data['chart'].length; i++) {
                            let amountMonth = new Array();
                            for (let j = 1; j < 13; j++) {
                                amountMonth.push(data['chart'][i]['amount'][j]);
                            }
                            let a1 = { name: data['chart'][i]['service_name'], data: amountMonth };
                            arrayGeneral.push(a1);
                        }
                        visitorData($('#year').val(), monthOneYear, arrayGeneral);
                    } else {
                        visitorData($('#year').val(), monthOneYear, [{
                            name: $('#branch').select2('data')[0]['text'],
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        }]);
                    }


                    $('#totalOrder').text(formatNumber(data['result']['totalQuantity']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                    $('#totalMoneyOrderPayFail').text(formatNumber(data['result']['totalMoneyOrderPayFail']) + json[" VNĐ"]);
                } else if (year != '' && time == '' && branch == '' && serviceCategory != '') {
                    //Năm và nhóm dịch vụ
                    if (data['flag'] == 1) {
                        var arrayGeneral = new Array();
                        for (let i = 0; i < data['chart'].length; i++) {
                            let amountMonth = new Array();
                            for (let j = 1; j < 13; j++) {
                                amountMonth.push(data['chart'][i]['amount'][j]);
                            }
                            let a1 = { name: data['chart'][i]['service_name'], data: amountMonth };
                            arrayGeneral.push(a1);
                        }
                        visitorData($('#year').val(), monthOneYear, arrayGeneral);
                    } else {
                        visitorData($('#year').val(), monthOneYear, [{
                            name: $('#service-categoty').select2('data')[0]['text'],
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        }]);
                    }

                    $('#totalOrder').text(formatNumber(data['result']['totalQuantity']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                    $('#totalMoneyOrderPayFail').text(formatNumber(data['result']['totalMoneyOrderPayFail']) + json[" VNĐ"]);
                } else if (year == '' && time != '' && branch != '' && serviceCategory == '') {
                    //Từ ngày tới ngày và chi nhánh.
                    if (data['flag'] == 1) {
                        var arrayGeneral = new Array();
                        for (let i = 0; i < data['valueDay'].length; i++) {
                            let amountMonth = new Array();
                            for (let j = 0; j < data['valueDay'][i]['amount'].length; j++) {
                                amountMonth.push(data['valueDay'][i]['amount'][j]);
                            }
                            let a1 = { name: data['valueDay'][i]['service_name'], data: amountMonth };
                            arrayGeneral.push(a1);
                        }
                        visitorData($('#time').val(), data['day'], arrayGeneral);
                    } else {
                        let amountDay = new Array();
                        for (let i = 0; i < data['day'].length; i++) {
                            amountDay.push(0);
                        }
                        visitorData($('#time').val(), data['day'], [{
                            name: $('#service-categoty').select2('data')[0]['text'],
                            data: amountDay
                        }]);
                    }
                    //Kết quả thống kê.
                    $('#totalOrder').text(formatNumber(data['result']['totalQuantity']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                } else if (year == '' && time != '' && branch == '' && serviceCategory != '') {
                    //Từ ngày tới ngày và nhóm dịch vụ.
                    if (data['flag'] == 1) {
                        var arrayGeneral = new Array();
                        for (let i = 0; i < data['valueDay'].length; i++) {
                            let amountMonth = new Array();
                            for (let j = 0; j < data['valueDay'][i]['amount'].length; j++) {
                                amountMonth.push(data['valueDay'][i]['amount'][j]);
                            }
                            let a1 = { name: data['valueDay'][i]['service_name'], data: amountMonth };
                            arrayGeneral.push(a1);
                        }
                        visitorData($('#time').val(), data['day'], arrayGeneral);
                    } else {
                        let amountDay = new Array();
                        for (let i = 0; i < data['day'].length; i++) {
                            amountDay.push(0);
                        }
                        visitorData($('#time').val(), data['day'], [{
                            name: $('#service-categoty').select2('data')[0]['text'],
                            data: amountDay
                        }]);
                    }
                    //Kết quả thống kê.
                    $('#totalOrder').text(formatNumber(data['result']['totalQuantity']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                } else if (year != '' && time == '' && branch != '' && serviceCategory != '') {
                    //Năm,chi nhánh và nhóm dịch vụ
                    if (data['flag'] == 1) {
                        var arrayGeneral = new Array();
                        for (let i = 0; i < data['chart'].length; i++) {
                            let amountMonth = new Array();
                            for (let j = 1; j < 13; j++) {
                                amountMonth.push(data['chart'][i]['amount'][j]);
                            }
                            let a1 = { name: data['chart'][i]['service_name'], data: amountMonth };
                            arrayGeneral.push(a1);
                        }
                        visitorData($('#year').val(), monthOneYear, arrayGeneral);
                    } else {
                        visitorData($('#year').val(), monthOneYear, [{
                            name: $('#service-categoty').select2('data')[0]['text'],
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        }]);
                    }
                    $('#totalOrder').text(formatNumber(data['result']['totalQuantity']));
                    $('#totalMoney').text(formatNumber(data['result']['totalMoney']) + json[" VNĐ"]);
                    $('#totalOrderPaysuccess').text(formatNumber(data['result']['totalOrderPaysuccess']));
                    $('#totalMoneyOrderPaysuccess').text(formatNumber(data['result']['totalMoneyOrderPaysuccess']) + json[" VNĐ"]);
                    $('#totalOrderNew').text(formatNumber(data['result']['totalOrderNew']));
                    $('#totalMoneyOrderNew').text(formatNumber(data['result']['totalMoneyOrderNew']) + json[" VNĐ"]);
                    $('#totalOrderPayFail').text(formatNumber(data['result']['totalOrderPayFail']));
                }
            });
        }
    });
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$('#year').select2().on('select2:select', function () {
    $('#time').val('').trigger('change');
    $('#branch').val('').trigger('change');
    $('#service-categoty').val('').trigger('change');
    ajaxHighChart();
    $("#year option[value='']").remove();
});
$('#branch').select2().on('select2:select', function () {
    ajaxHighChart();
});
$('#service-categoty').select2().on('select2:select', function () {
    ajaxHighChart();
});
$('#service-categoty').select2();



