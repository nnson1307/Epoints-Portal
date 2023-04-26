var reportSynthesis = {
    jsonLang: null,
    _init: function () {
        reportSynthesis.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[reportSynthesis.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[reportSynthesis.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[reportSynthesis.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[reportSynthesis.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[reportSynthesis.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[reportSynthesis.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": reportSynthesis.jsonLang["Đồng ý"],
                "cancelLabel": reportSynthesis.jsonLang["Thoát"],
                "customRangeLabel": reportSynthesis.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    reportSynthesis.jsonLang["CN"],
                    reportSynthesis.jsonLang["T2"],
                    reportSynthesis.jsonLang["T3"],
                    reportSynthesis.jsonLang["T4"],
                    reportSynthesis.jsonLang["T5"],
                    reportSynthesis.jsonLang["T6"],
                    reportSynthesis.jsonLang["T7"]
                ],
                "monthNames": [
                    reportSynthesis.jsonLang["Tháng 1 năm"],
                    reportSynthesis.jsonLang["Tháng 2 năm"],
                    reportSynthesis.jsonLang["Tháng 3 năm"],
                    reportSynthesis.jsonLang["Tháng 4 năm"],
                    reportSynthesis.jsonLang["Tháng 5 năm"],
                    reportSynthesis.jsonLang["Tháng 6 năm"],
                    reportSynthesis.jsonLang["Tháng 7 năm"],
                    reportSynthesis.jsonLang["Tháng 8 năm"],
                    reportSynthesis.jsonLang["Tháng 9 năm"],
                    reportSynthesis.jsonLang["Tháng 10 năm"],
                    reportSynthesis.jsonLang["Tháng 11 năm"],
                    reportSynthesis.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            reportSynthesis.loadChart();
        });
        $('#branch').select2().on('select2:select', function (event) {
            reportSynthesis.loadChart();
        });
        $('#receipt_type').select2().on('select2:select', function (event) {
            reportSynthesis.loadChart();
        });
        $('#payment_type').select2().on('select2:select', function (event) {
            reportSynthesis.loadChart();
        });
        $('#payment_method').select2().on('select2:select', function (event) {
            reportSynthesis.loadChart();
        });
        reportSynthesis.loadChart();
    
    },
    colorOption: ['#2f7ed8', '#0d233a', '#8bbc21', '#910000', '#1aadce',
        '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a'],
    dataDayMonth : [],
    dataPaymentBranch : [],
    dataReceiptBranch:[],
    dataBalanceBranch:[],
    cateDayPaymentBranch : [],
    cateDayReceiptBranch:[],
    cateDayBalanceBranch:[],
    infoMoney: function (data) {
        $('#totalFund').html(formatNumber(data['totalFund'].toFixed(decimal_number)) + reportSynthesis.jsonLang[" VNĐ"]);
            $('#totalReceiptVoucher').html(formatNumber(data['totalReceipt'].toFixed(decimal_number)) + reportSynthesis.jsonLang[" VNĐ"]);
            $('#totalPaymentVoucher').html(formatNumber(data['totalPayment'].toFixed(decimal_number)) + reportSynthesis.jsonLang[" VNĐ"]);
    },
    loadChart: function () {
        $.ajax({
            url: laroute.route('receipt.report.filter'),
            method: "POST",
            data: {
                time: $('#time').val(),
                branch: $('#branch').val(),
                receiptType: $('#receipt_type').val(),
                paymentType: $('#payment_type').val(),
                paymentMethod: $('#payment_method').val(),
            },
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                reportSynthesis.infoMoney(data.calculate);
                reportSynthesis.dataPaymentBranch = data.branch_datatable.branch_payment_chart;
                reportSynthesis.dataReceiptBranch = data.branch_datatable.branch_receipt_chart;
                reportSynthesis.dataBalanceBranch = data.branch_datatable.branch_balance_chart;
                reportSynthesis.cateDayPaymentBranch = data.branch_datatable.branch_payment_cate_day;
                reportSynthesis.cateDayReceiptBranch = data.branch_datatable.branch_receipt_cate_day;
                reportSynthesis.cateDayBalanceBranch = data.branch_datatable.branch_balance_cate_day;
                // chartByBranch();
                showChartByBranch('receipt',data.branch_datatable.branch_receipt_chart,data.branch_datatable.branch_receipt_cate_day);
                tableSummaryByBranch(data.branch_datatable.branch_datatable);

                tableByPaymentMethod(data.payment_method.dataBalance);
                chartByPaymentMethod(data.payment_method.dataChart,data.payment_method.dataChartCate);

                tableByReceiptVoucher(data.receipt_type);
                chartByReceiptVoucher(data.receipt_type);

                tableByPaymentVoucher(data.payment_type);
                chartByPaymentVoucher(data.payment_type);
            }
        });
    }
}

function showChartByBranch(type,dateDefault=null,cateDefault=null){

    
    var data = "";
    var cate = [];
    $('#tableSummaryByBranch').find('.epoint-btn-default').removeClass('epoint-btn-default');
    if(type == 'payment'){
        data = reportSynthesis.dataPaymentBranch;
        cate = reportSynthesis.cateDayPaymentBranch;
        $('#button-payment').removeClass('epoint-btn-blank').addClass('epoint-btn-default');
    }
    else if(type== 'receipt'){
        data = reportSynthesis.dataReceiptBranch;
        cate = reportSynthesis.cateDayReceiptBranch;
        $('#button-receipt').removeClass('epoint-btn-blank').addClass('epoint-btn-default');
    }
    else{
        data = reportSynthesis.dataBalanceBranch;
        cate = reportSynthesis.cateDayBalanceBranch;
        $('#button-balance').removeClass('epoint-btn-blank').addClass('epoint-btn-default');
    }
    if(dateDefault){
        data = dateDefault;
    }
    if(cateDefault){
        cate = cateDefault;
    }
    Highcharts.chart('chartBranch', {
        title: {
            text: reportSynthesis.jsonLang['Biểu đồ dòng tiền theo chi nhánh']
        },
        yAxis: {
            title: {
                text: reportSynthesis.jsonLang['Số tiền (VNĐ)']
            }
        },
        xAxis: {
            categories: cate
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{point.x}</span><br>',
            pointFormat: '<tr><td style="color:#ffffff;padding:0"></td>' +
                '<td style="padding:0">{point.series.name}: <b style="color:#000000;">{point.y} VNĐ</b></td></tr>',
            footerFormat: '</table>',
            useHTML: true
        },
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                }
            }
        },
        series: data,
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

// Biểu đồ dòng tiền theo chi nhánh (line chart)
function chartByBranch() {
    

    Highcharts.chart('chartBranch', {
        title: {
            text: reportSynthesis.jsonLang['Biểu đồ dòng tiền theo chi nhánh']
        },
        yAxis: {
            title: {
                text: reportSynthesis.jsonLang['Số tiền (VNĐ)']
            }
        },
        xAxis: {
            categories: [
                '2/4',
                '2/5',
                '2/6',
                '2/7',
                '2/8',
                '2/9',
                '2/10',
                '2/11'
            ]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<tr><td style="color:#ffffff;padding:0"> </td>' +
                '<td style="padding:0"><b style="color:#000000;">{point.x} VNĐ</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                }
            }
        },
        series: [{
            name: 'Installation',
            data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
        }, {
            name: 'Manufacturing',
            data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
        }, {
            name: 'Sales & Distribution',
            data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
        }, {
            name: 'Project Development',
            data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
        }, {
            name: 'Other',
            data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
        }],
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

// Bảng dòng tiền theo chi nhánh
function tableSummaryByBranch(data){
    
    var stringHtml = "<tbody>";
    var totalAmount = 0;
    data.forEach(e=>totalAmount+= e["balance"]);
    data.forEach(e=>{
        let percent = e["balance"]/totalAmount*100;
        stringHtml += `
            <tr>
                <th>${e["branch_name"]}</th>
                <th>${formatNumber(e["receipt"])}</th>
                <th>${formatNumber(e["payment"])}</th>
                <th>${formatNumber(e["balance"])}</th>
                <th>${percent.toFixed(2)}%</th>
            </tr>
        `;
    })
    stringHtml += `
</tbody>
`;
    $("#tableSummaryByBranch tbody").html('');
    $("#tableSummaryByBranch").append(stringHtml);


}

// Biểu đồ dòng tiền theo hình thức thanh toán (tròn)
function chartByPaymentMethod(data, dataCate) {
    

    Highcharts.chart('chartPaymentMethod', {
        chart: {
            type: 'column'
        },
        title: {
            text: reportSynthesis.jsonLang['Biểu đồ dòng tiền theo phương thức thanh toán']
        },
        yAxis: {
            title: {
                text: reportSynthesis.jsonLang['Số tiền (VNĐ)']
            }
        },
        xAxis: {
            categories: dataCate
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{point.x}</span><br>',
            pointFormat: '<tr><td style="color:#ffffff;padding:0"></td>' +
                '<td style="padding:0">{point.series.name}: <b style="color:#000000;">{point.y} VNĐ</b></td></tr>',
            footerFormat: '</table>',
            useHTML: true
        },
        credits: {
            enabled: false
        },
        series: data
    });

}

// Bảng dòng tiền theo loại phiếu chi
function tableByPaymentMethod(data){
    
    var totalAmount = 0;
    data.forEach(e=>totalAmount+= e["y"]);
    var stringHtml = `
    <table id="table_col_payment_method" class="table-voucher table" style="width:100%;">
        <thead>
            <tr>
                <th style="width:45%;">${reportSynthesis.jsonLang['PTTT']}</th>
                <th style="width:35%;text-align: right;">${reportSynthesis.jsonLang['Số tiền']}</th>
                <th style="text-align: right;">${reportSynthesis.jsonLang['Tỷ lệ']}</th>
            </tr>
        </thead>
        <tbody>`;
    var indexColor = 0;
    data.forEach(e=>{
        let percent = e["y"]/totalAmount*100;
        stringHtml += `
            <tr>
                <td style="width:45%;">
                    <div style="float:left;margin-top: 4px;margin-right: 5px;
                    border-radius: 50%;width: 10px;height: 10px;background:${reportSynthesis.colorOption[indexColor]}">
                    </div>
                    <div>${e["name"]}</div>
                </td>
                <td style="width:35%;text-align: right;">${formatNumber(e["y"])}</td>
                <td style="text-align: right;">${percent.toFixed(2)}%</td>
            </tr>
        `;
        if(indexColor == reportSynthesis.colorOption.length - 1){
            indexColor = 0;
        }
        else indexColor++;
    })
    stringHtml += `</tbody>
    </table>
`;
    $('#tablePaymentMethod').html('');
    $('#tablePaymentMethod').append(stringHtml);


}

// Biểu đồ dòng tiền theo loại phiếu thu (tròn)
function chartByReceiptVoucher(data) {
    

    Highcharts.chart('chartReceiptVoucher', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: reportSynthesis.jsonLang['Biểu đồ dòng tiền theo loại phiếu thu']
        },
        subtitle: {
            style: {
                display: 'none'
            }
        },
        colors: reportSynthesis.colorOption,
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<b>{point.name}: {point.percentage:.2f}% - {point.y} VNĐ</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                }
            }
        },
        series: [{
            name: reportSynthesis.jsonLang['Loại phiếu'],
            colorByPoint: true,
            data:
            data
        }]
    });

}

// Bảng dòng tiền theo loại phiếu chi
function tableByReceiptVoucher(data){
   
    var totalAmount = 0;
    data.forEach(e=>totalAmount+= e["y"]);
    var stringHtml = `
    <table id="table_pie_receipt" class="table-voucher table" style="width:100%;">
        <thead>
            <tr>
                <th style="width:45%;">${reportSynthesis.jsonLang['Loại phiếu thu']}</th>
                <th style="width:35%;text-align: right;">${reportSynthesis.jsonLang['Số tiền']}</th>
                <th style="text-align: right;">${reportSynthesis.jsonLang['Tỷ lệ']}</th>
            </tr>
        </thead>
        <tbody>`;
    var indexColor = 0;
    data.forEach(e=>{
        let percent = e["y"]/totalAmount*100;
        stringHtml += `
            <tr>
                <td style="width:45%;">
                    <div style="float:left;margin-top: 4px;margin-right: 5px;
                    border-radius: 50%;width: 10px;height: 10px;background:${reportSynthesis.colorOption[indexColor]}">
                    </div>
                    <div>${e["name"]}</div>
                </td>
                <td style="width:35%;text-align: right;">${formatNumber(e["y"])}</td>
                <td style="text-align: right;">${percent.toFixed(2)}%</td>
            </tr>
        `;
        if(indexColor == reportSynthesis.colorOption.length - 1){
            indexColor = 0;
        }
        else indexColor++;
    })
    stringHtml += `</tbody>
    </table>
`;
    $('#tableReceiptVoucher').html('');
    $('#tableReceiptVoucher').append(stringHtml);


}

// Biểu đồ dòng tiền theo loại phiếu chi (tròn)
function chartByPaymentVoucher(data) {
    

    Highcharts.chart('chartPaymentVoucher', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: reportSynthesis.jsonLang['Biểu đồ dòng tiền theo loại phiếu chi']
        },
        subtitle: {
            text: '',
            style: {
                display: 'none'
            }
        },
        colors: reportSynthesis.colorOption,
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<b>{point.name}: {point.percentage:.2f}% - {point.y} VNĐ</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                }
            }
        },
        series: [{
            name: reportSynthesis.jsonLang['Loại phiếu'],
            colorByPoint: true,
            data: data
        }]
    });

}

// Bảng dòng tiền theo loại phiếu chi
function tableByPaymentVoucher(data){
    
    var totalAmount = 0;
    data.forEach(e=>totalAmount+= e["y"]);
    var stringHtml = `
    <table id="table_pie_payment" class="table-voucher table" style="width:100%;">
        <thead>
            <tr>
                <th style="width:45%;">${reportSynthesis.jsonLang['Loại phiếu chi']}</th>
                <th style="width:35%;text-align: right;">${reportSynthesis.jsonLang['Số tiền']}</th>
                <th style="text-align: right;">${reportSynthesis.jsonLang['Tỷ lệ']}</th>
            </tr>
        </thead>
        <tbody>`;
    var indexColor = 0;
    data.forEach(e=>{
        let percent = e["y"]/totalAmount*100;
        stringHtml += `
            <tr>
                <td style="width:45%;">
                    <div style="float:left;margin-top: 4px;margin-right: 5px;
                    border-radius: 50%;width: 10px;height: 10px;background:${reportSynthesis.colorOption[indexColor]}">
                    </div>
                    <div>${e["name"]}</div>
                </td>
                <td style="width:35%;text-align: right;">${formatNumber(e["y"])}</td>
                <td style="text-align: right;">${percent.toFixed(2)}%</td>
            </tr>
        `;
        if(indexColor == reportSynthesis.colorOption.length - 1){
            indexColor = 0;
        }
        else indexColor++;
    })
    stringHtml += `</tbody>
    </table>
`;
    $('#tablePaymentVoucher').html('');
    $('#tablePaymentVoucher').append(stringHtml);


}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}