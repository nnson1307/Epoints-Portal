var performanceReport = {
    _init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Tất cả 12 tháng"]] = [moment().startOf('year').format('DD/MM/YYYY'), moment().endOf('year').format('DD/MM/YYYY')];
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#time_overview").daterangepicker({
                autoApply: true,
                maxDate: moment().endOf("day"),
                startDate: moment().startOf('year').format('DD/MM/YYYY'),
                endDate: moment().endOf('year').format('DD/MM/YYYY'),
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
                var starttime = moment().startOf('year').format('DD/MM/YYYY');
                var endtime = moment().endOf("day").format('DD/MM/YYYY');
                if ($('#time_overview').val() == starttime + " - " + endtime) {
                    $('#time_overview').val(json["Tất cả 12 tháng"])
                }
                performanceReport.loadChartI();
            });
            $('#department_id').select2();
            $('#branch_code').select2();
            $('#staff_id').select2();
            $('#department_id').trigger('change');
            var starttime = moment().startOf('year').format('DD/MM/YYYY');
            var endtime = moment().endOf("day").format('DD/MM/YYYY');
            if ($('#time_overview').val() == starttime + " - " + endtime) {
                $('#time_overview').val(json["Tất cả 12 tháng"])
            }
        });
    },

    loadChartI: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var time = $('#time_overview').val();
            var staff_id = $('#staff_id option:selected').val();
            var department_id = $('#department_id option:selected').val();
            var branch_code = $('#branch_code option:selected').val();
            $.ajax({
                url: laroute.route('report.performance-report.filter'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    time: time,
                    staff_id: staff_id,
                    department_id: department_id,
                    branch_code: branch_code,
                },
                success:function (res) {
                    $('.total_revenue').text(formatNumber(res.totalRevenue.toFixed(decimal_number)) + json['VNĐ']);
                    progressCustomer(res);
                    $('.total_lead_convert').text(formatNumber(res.dataLog.sum_lead_convert));
                    $('.deal_success').text(formatNumber(res.totalDealSuccess));
                    var totalStaff = res.dataStaff.length;
                    $('#total_staff').text(json["TỔNG NHÂN VIÊN"] + " ("+totalStaff+")");
                    loadListStaff(res.dataStaff);
                    chartTotalDepartment(res.dataTotalRevenue)
                    chartTotalLeadConvert(res.dataTotalRate);
                    if(staff_id != ""){
                        $('#total_department').text($('#staff_id option:selected').text().trim());
                    }
                    else{
                        $('#total_department').text(json['TỔNG PHÒNG BAN']);
                    }
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

function changeTab(tabName) {
    $('.active').css('color','#6f727d')
    switch (tabName) {
        case 'sms':
            $('#div-sms').css('display', 'block');
            $('#div-email').css('display', 'none');
            break;

        case 'email':
            $('#div-sms').css('display', 'none');
            $('#div-email').css('display', 'block');
            break;

    }
}
// Biểu đồ cột
function chartTotalDepartment(dataValue) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#chart_total_revenue').highcharts({
            exporting: false,
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: dataValue.arrayCategories
            },
            colors: ['#EC870E', '#205AA7'],
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
                }
            },
            series: dataValue.dataSeries
        });
    });
}
function chartTotalLeadConvert(dataValue) {
    $.getJSON(laroute.route('translate'), function (json) {
        // Create the chart
        $('#chart_total_lead_convert').highcharts({
            exporting: false,
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: dataValue.arrayCategories
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: dataValue.dataSeries
        });
    });
}

function progressCustomer(res){
    $.getJSON(laroute.route('translate'), function (json) {
        var sum_lead = res.dataLog.sum_lead ?? 0;
        var sum_customer = res.dataLog.sum_customer ?? 0;
        var total = parseInt(sum_lead) + parseInt(sum_customer);
        var rateLead = sum_lead / sum_customer * 100;
        var rateCustomer = 100 - rateLead;
        if (total == 0) {
            rateLead = 50;
            rateCustomer = 50;
        }
        if(rateLead < 20){
            rateLead = 20;
            rateCustomer = 80;
        }
        if(rateCustomer < 20){
            rateLead = 80;
            rateCustomer = 20;
        }
        $('.customer_approach').text(formatNumber(total));
        var html = `
                             <div class="progress-bar bg-success" role="progressbar" style="width: ${rateLead}%" aria-valuenow="${sum_lead}" aria-valuemin="0" aria-valuemax="${total}">${sum_lead} Lead</div>
                             <div class="progress-bar" role="progressbar" style="width: ${rateCustomer}%" aria-valuenow="${sum_customer}" aria-valuemin="0" aria-valuemax="${total}">${sum_customer} KH</div>
                        `;
        $('#progress-sms').html('');
        $('#progress-sms').html(html);
    });
}
function loadListStaff(lstStaff){
    $.getJSON(laroute.route('translate'), function (json) {
        var defaultAvatar = "https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947";
        $('#list_staff_scroll').empty();
        $.map(lstStaff, function (a) {
            $('#list_staff_scroll').append(`
                <div class="col-lg-4">
                    <div class="m-widget19__pic">
                         <img class="m--bg-metal m-image img-sd img_staff" data-type="${a.staff_id}" onclick="clickStaff(this,${a.staff_id});" style="border-radius:50%;" id="blah" src="${a.staff_avatar != null ? a.staff_avatar : defaultAvatar}" alt="Hình ảnh" width="220px" height="220px">
                         <div>${a.full_name}</div>
                         <div>${a.sum_lead_convert} / ${a.sum_lead}</div>
                    </div>                
                </div>
            `);
        });
        $(`.img_staff[data-type="${$('#staff_id option:selected').val()}"]`).attr('data-type','0')
    });
}
function clickStaff(e,id){
    if($(e).attr('data-type') == id){
        $('#staff_id').val(id);
    }
    else{
        $('#staff_id').val('');
    }
    $('#staff_id').select2();
    $('#staff_id').trigger('change');
}
//Hàm định dạng số.
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$('#department_id').on('change', function(e){
    $.getJSON(laroute.route('translate'), function (json) {
        e.preventDefault();
        $.ajax({
            url: laroute.route('report.performance-report.filter-staff'),
            dataType: 'JSON',
            data: {
                department_id: $('#department_id option:selected').val(),
                branch_code: $('#branch_code option:selected').val(),
            },
            method: 'POST',
            success: function (res) {
                $('#staff_id').empty();
                $('#staff_id').append('<option value="">' + json['Chọn nhân viên'] + '</option>');
                $.map(res.optionStaffs, function (a) {
                    $('#staff_id').append('<option value="' + a.staff_id + '">' + a.full_name + '</option>');
                });
            }
        });
        performanceReport.loadChartI();
    });
})
$('#branch_code').on('change', function(e){
    $.getJSON(laroute.route('translate'), function (json) {
        e.preventDefault();
        $.ajax({
            url: laroute.route('report.performance-report.filter-staff'),
            dataType: 'JSON',
            data: {
                department_id: $('#department_id option:selected').val(),
                branch_code: $('#branch_code option:selected').val(),
            },
            method: 'POST',
            success: function (res) {
                $('#staff_id').empty();
                $('#staff_id').append('<option value="">' + json['Chọn nhân viên'] + '</option>');
                $.map(res.optionStaffs, function (a) {
                    $('#staff_id').append('<option value="' + a.staff_id + '">' + a.full_name + '</option>');
                });
            }
        });
        performanceReport.loadChartI();
    });
})
$('#staff_id').on('change', function(e){
    e.preventDefault();
    performanceReport.loadChartI();
})