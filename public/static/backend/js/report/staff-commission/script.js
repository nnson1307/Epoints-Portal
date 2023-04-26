var staffCommission = {
    jsonLang: null,
    _init: function () {
        staffCommission.jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        
        var arrRange = {};
        arrRange[staffCommission.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[staffCommission.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[staffCommission.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[staffCommission.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[staffCommission.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[staffCommission.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
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
                "applyLabel": staffCommission.jsonLang["Đồng ý"],
                "cancelLabel": staffCommission.jsonLang["Thoát"],
                "customRangeLabel": staffCommission.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    staffCommission.jsonLang["CN"],
                    staffCommission.jsonLang["T2"],
                    staffCommission.jsonLang["T3"],
                    staffCommission.jsonLang["T4"],
                    staffCommission.jsonLang["T5"],
                    staffCommission.jsonLang["T6"],
                    staffCommission.jsonLang["T7"]
                ],
                "monthNames": [
                    staffCommission.jsonLang["Tháng 1 năm"],
                    staffCommission.jsonLang["Tháng 2 năm"],
                    staffCommission.jsonLang["Tháng 3 năm"],
                    staffCommission.jsonLang["Tháng 4 năm"],
                    staffCommission.jsonLang["Tháng 5 năm"],
                    staffCommission.jsonLang["Tháng 6 năm"],
                    staffCommission.jsonLang["Tháng 7 năm"],
                    staffCommission.jsonLang["Tháng 8 năm"],
                    staffCommission.jsonLang["Tháng 9 năm"],
                    staffCommission.jsonLang["Tháng 10 năm"],
                    staffCommission.jsonLang["Tháng 11 năm"],
                    staffCommission.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (event) {
            loadChart();
        });
        $('#number_staff').select2().on('select2:select', function (event) {
            loadChart();
        });
        $('#staff_id').select2().on('select2:select', function (event) {
            loadChart();
        });
        loadChart();
    
    }
}

function loadChart() {
    var time = $('#time').val();
    var numberStaff = $('#number_staff').val();
    var staff_id = $('#staff_id option:selected').val();
    //Load input hidden export excel
    $('#time_export_detail').val(time);
    $('#export_staff_id_detail').val(staff_id);
    $('#time_export_total').val(time);
    $('#export_staff_id_total').val(staff_id);
    $('#time_detail').val(time);
    $('#staff_id_detail').val(staff_id);
    $.ajax({
        url: laroute.route('admin.report-staff-commission.load-chart'),
        method: "POST",
        data: {
            time: time,
            numberStaff: numberStaff,
            staffId: staff_id,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.countListStaff > 5) {
                $('#container').height(res.countListStaff * 50);
            }
            var dataSerise = [];
            res.dataSeries.forEach( e => {
                dataSerise.push(roundToTwo(e));
            })
            chart(res.arrayCategories, dataSerise);
            $('#totalMoney').text(formatNumber(roundToTwo(res.totalMoney)) + staffCommission.jsonLang[" VNĐ"]);

            $('#number_staff_detail').val(JSON.stringify(res.arrStaff));
            $('#export_number_staff_detail').val(JSON.stringify(res.arrStaff));
            $('#export_number_staff_total').val(JSON.stringify(res.arrStaff));
            $('#autotable').PioTable({
                baseUrl: laroute.route('admin.report-staff-commission.list-detail')
            });

            $('.btn-search').trigger('click');
        }
    });
}

//Biểu đồ.
function chart(arrayCategories, dataSeries) {
    
    $('#container').highcharts({
        chart: {
            type: 'bar',
            marginRight: 50
        },
        title: {
            text: ''
        },
        colors: ['#34bfa3', '#2f7ed8', '#e83e8c', '#ffc107'],
        xAxis: {
            categories: arrayCategories ,
        },
        yAxis: {
            min: 0,
            title: {
                text: staffCommission.jsonLang['Số tiền (VNĐ)'],
                align: 'high'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:,.'+ decimal_number +'f}' + staffCommission.jsonLang[' VNĐ'] + '</b></td></tr>',
            footerFormat: '</table>',
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: staffCommission.jsonLang['Số tiền (VNĐ)'],
            data: dataSeries
        }]
    });

}

//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
function roundToTwo(num) {
    return +(Math.round(num + "e+"+decimal_number+"")  + "e-"+decimal_number+"");
}