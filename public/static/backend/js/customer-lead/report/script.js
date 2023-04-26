var lead = {
    jsonLang : null,
    renderTableReportCS: function () {
        var pipeline_code = $('#pipeline').val();
        var customer_source_id = $('#customer_source_id option:selected').val();
        $('#time_export').val($('#time').val());
        $('#pipeline_code_export').val(pipeline_code);
        $('#customer_source_id_export').val(customer_source_id);
        $.ajax({
            url: laroute.route('customer-lead.report.lead-render-view-report-cs'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                pipeline_code: pipeline_code,
                customer_source_id: customer_source_id,
                time: $('#time').val()
            },
            success: function (res) {
                $('#table-report').html(res.html);
            }
        });
    },
    renderTableReportStaff: function () {
        var pipeline_code = $('#pipeline').val();
        var staff_id = $('#staff_id').val();
        $('#time_export').val($('#time').val());
        $('#pipeline_code_export').val(pipeline_code);
        $('#staff_id_export').val(staff_id);

        $.ajax({
            url: laroute.route('customer-lead.report.lead-render-view-report-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                pipeline_code: pipeline_code,
                staff_id: staff_id,
                time: $('#time').val()
            },
            success: function (res) {
                $('#table-report').html(res.html);
            }
        });
    },
    renderPopupReportCS: function (source_id = '', journey_code = '') {
        if($('#customer_source_id').val() != ''){
            source_id = $('#customer_source_id').val();
        }
        $.ajax({
            url: laroute.route("customer-lead.report.lead-render-popup-report-cs"),
            method: "GET",
            data: {
                source_id: source_id,
                journey_code: journey_code,
                time: $('#time').val(),
                pipeline_code: $('#pipeline option:selected').val(),
            },
            success: function (res) {
                $("#modal-lead-report-cs").html(res);
                $("#modal-lead-report-cs").find("#lead-report-cs").modal();
                $('#autotable-report-cs').PioTable({
                    baseUrl: laroute.route('customer-lead.report.list-lead-render-popup-report-cs')
                });
                $('.btn-search').trigger('click');
            }
        });
    },
    renderPopupReportConvert: function (source_id = '', journey_code = '') {
        if($('#customer_source_id').val() != ''){
            source_id = $('#customer_source_id').val();
        }
        $.ajax({
            url: laroute.route("customer-lead.report.lead-render-popup-report-convert"),
            method: "GET",
            data: {
                source_id: source_id,
                journey_code: journey_code,
                time: $('#time').val(),
                pipeline_code: $('#pipeline option:selected').val(),
            },
            success: function (res) {
                $("#modal-lead-report-convert").html(res);
                $("#modal-lead-report-convert").find("#lead-report-cs").modal();
                $('#autotable-report-cs').PioTable({
                    baseUrl: laroute.route('customer-lead.report.list-lead-render-popup-report-convert')
                });
                $('.btn-search').trigger('click');
            }
        });
    },
    renderPopupReportStaff: function (staff_id = '', journey_code = '') {

        if($('#staff_id').val() != ''){
            staff_id = $('#staff_id').val();
        }
        $.ajax({
            url: laroute.route("customer-lead.report.lead-render-popup-report-cs"),
            method: "GET",
            data: {
                staff_id: staff_id,
                journey_code: journey_code,
                time: $('#time').val(),
                pipeline_code: $('#pipeline option:selected').val(),
            },
            success: function (res) {
                $("#modal-lead-report-cs").html(res);
                $("#modal-lead-report-cs").find("#lead-report-cs").modal();
                $('#autotable-report-cs').PioTable({
                    baseUrl: laroute.route('customer-lead.report.list-lead-render-popup-report-cs')
                });
                $('.btn-search').trigger('click');
            }
        });
    }
};

var deal = {
    renderPopupReportDealStaff: function (staff_id = '', journey_code = '') {
        if($('#staff_id').val() != ''){
            staff_id = $('#staff_id').val();
        }
        $.ajax({
            url: laroute.route("customer-lead.report.deal-render-popup-report-staff"),
            method: "GET",
            data: {
                staff_id: staff_id,
                journey_code: journey_code,
                time: $('#time').val(),
                pipeline_code: $('#pipeline option:selected').val(),
            },
            success: function (res) {
                $("#modal-deal-report-staff").html(res);
                $("#modal-deal-report-staff").find("#deal-report-staff").modal();
                $('#autotable-report-cs').PioTable({
                    baseUrl: laroute.route('customer-lead.report.list-deal-render-popup-report-staff')
                });
                $('.btn-search').trigger('click');
            }
        });
    },
    renderTableReportCS: function () {
        var pipeline_code = $('#pipeline').val();

        $.ajax({
            url: laroute.route('customer-lead.report.deal-render-view-report-cs'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                pipeline_code: pipeline_code
            },
            success: function (res) {
                console.log(res.html);
                $('#table-report').html(res.html);
            }
        });
    },
    renderTableReportStaff: function () {
        var pipeline_code = $('#pipeline').val();
        var staff_id = $('#staff_id').val();

        $('#time_export').val($('#time').val());
        $('#pipeline_code_export').val(pipeline_code);
        $('#staff_id_export').val(staff_id);

        $.ajax({
            url: laroute.route('customer-lead.report.deal-render-view-report-staff'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                pipeline_code: pipeline_code,
                staff_id: staff_id,
                time: $('#time').val()
            },
            success: function (res) {
                $('#table-report').html(res.html);
            }
        });
    }
}

var convert = {
    renderTableReport: function () {
        var pipeline_code = $('#pipeline').val();
        var customer_source_id = $('#customer_source_id').val();

        $('#time_export').val($('#time').val());
        $('#pipeline_code_export').val(pipeline_code);
        $('#customer_source_id_export').val(customer_source_id);
        $.ajax({
            url: laroute.route('customer-lead.report.render-report-convert'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                pipeline_code: pipeline_code,
                customer_source_id: customer_source_id,
                time: $('#time').val()
            },
            success: function (res) {
                $('#table-report').html(res.html);
            }
        });
    }
};
$( document ).ready(function() {
    lead.jsonLang = JSON.parse(localStorage.getItem('tranlate'))
    
    var arrRange = {};
    arrRange[lead.jsonLang["Hôm nay"]] = [moment(), moment()];
    arrRange[lead.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[lead.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[lead.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[lead.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[lead.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
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
                "customRangeLabel": lead.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    lead.jsonLang["CN"],
                    lead.jsonLang["T2"],
                    lead.jsonLang["T3"],
                    lead.jsonLang["T4"],
                    lead.jsonLang["T5"],
                    lead.jsonLang["T6"],
                    lead.jsonLang["T7"]
                ],
                "monthNames": [
                    lead.jsonLang["Tháng 1 năm"],
                    lead.jsonLang["Tháng 2 năm"],
                    lead.jsonLang["Tháng 3 năm"],
                    lead.jsonLang["Tháng 4 năm"],
                    lead.jsonLang["Tháng 5 năm"],
                    lead.jsonLang["Tháng 6 năm"],
                    lead.jsonLang["Tháng 7 năm"],
                    lead.jsonLang["Tháng 8 năm"],
                    lead.jsonLang["Tháng 9 năm"],
                    lead.jsonLang["Tháng 10 năm"],
                    lead.jsonLang["Tháng 11 năm"],
                    lead.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        });

    $('#time').val('');

});


