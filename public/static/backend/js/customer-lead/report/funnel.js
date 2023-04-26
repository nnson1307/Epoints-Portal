var funnel = {
    chartLead : function (){

        $.ajax({
            url: laroute.route('customer-lead.report.getDataChartLead'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#search-report').serialize(),
            success: function (res) {
                if (res.error == false) {
                    funnel.rawChart('table-report',res.data,res.color);
                    funnel.rawChart('table-report-percent',res.dataPercent,res.colorPercent,'%');
                    $('.totalLead span').text(res.totalLead);
                    $('.convertDeal span').text(res.convertDeal);
                    $('.convertCustomer span').text(res.convertCustomer);
                    $('.convertFail span').text(res.convertFail);
                    $('#table-sale').empty();
                    $('#table-sale').append(res.viewLead);
                    $('#table-source').empty();
                    $('#table-source').append(res.viewSource);
                } else {
                    $('#table-report').empty();
                    $('#table-sale').empty();
                }

            }
        });
    },

    tableLeadSearch : function (page = 1){
        $.ajax({
            url: laroute.route('customer-lead.report.tableLeadSearch'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#search-report').serialize()+'&page='+page,
            success: function (res) {
                console.log(res.data);
                if (res.error == false) {
                    $('#table-sale').empty();
                    $('#table-sale').append(res.view);
                } else {
                    $('#table-report').empty();
                    $('#table-sale').empty();
                }

            }
        });
    },

    tableSourceSearch : function (page = 1){
        $.ajax({
            url: laroute.route('customer-lead.report.tableSourceSearch'),
            method: 'POST',
            dataType: 'JSON',
            data: $('#search-report').serialize()+'&page='+page,
            success: function (res) {
                console.log(res.data);
                if (res.error == false) {
                    $('#table-source').empty();
                    $('#table-source').append(res.view);
                } else {
                    $('#table-report').empty();
                    $('#table-sale').empty();
                    $('#table-source').empty();
                }

            }
        });
    },

    rawChart(id,data,color,text = ''){
        // Set up the chart
        Highcharts.chart(id, {
            chart: {
                type: 'funnel3d',
                options3d: {
                    enabled: true,
                    alpha: 10,
                    depth: 50,
                    viewDistance: 50
                }
            },
            colors: color,
            title: {
                text: ''
            },
            accessibility: {
                screenReaderSection: {
                    beforeChartFormat: '<{headingTagName}>{chartTitle}</{headingTagName}><div>{typeDescription}</div><div>{chartSubtitle}</div><div>{chartLongdesc}</div>'
                }
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b> ({point.y:,.0f}'+text+')',
                        allowOverlap: true,
                        y: 10,
                        x: 10
                    },
                    neckWidth: '30%',
                    neckHeight: '30%',
                    width: '80%',
                    height: '80%'
                }
            },
            series: [{
                // name: 'Unique users',
                name: '',
                // data: [
                //     ['Website visits', 15654],
                //     ['Downloads', 4064],
                //     ['Requested price list', 1987],
                //     ['Invoice sent', 976],
                //     ['Finalized', 846]
                // ]
                data: data
            }]
        });
    },

    changeDepartment: function (){
        $.ajax({
            url: laroute.route("customer-lead.report.changeDepartment"),
            method: "GET",
            data: {
                department_id: $('#department_id option:selected').val(),
            },
            success: function (res) {
                if(res.error == false){
                    $('#staff_id').empty();
                    $('#staff_id').append(res.view);
                } else {
                    swal.fire(res.message,'','error');
                }
            }
        });
    }
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
            autoUpdateInput: true,
            autoApply: true,
            // buttonClasses: "m-btn btn",
            // applyClass: "btn-primary",
            // cancelClass: "btn-danger",

            // maxDate: moment().endOf("day"),
            // startDate: moment().startOf("day"),
            // endDate: moment().add(1, 'days'),
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
        });

    $('#time').val('');
});