
$(document).ready(function () {
    $('.select').select2();
    $(".date-picker").datepicker({
        todayHighlight: !0,
        autoclose: !0,
        format: "dd/mm/yyyy"
    });
    $.getJSON(laroute.route('translate'), function (json) {
        var arrRange = {};
        arrRange[json["Hôm nay"]] = [moment(), moment()];
        arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $("#expired_date,#warranty_end_date").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
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
        $("#created_at").daterangepicker({
            autoApply: true,
            startDate: moment().startOf('month').format('DD/MM/YYYY'),
            endDate: moment().endOf('month').format('DD/MM/YYYY'),
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
        }).on('apply.daterangepicker', function (event) {
        });
        $('#contract_category_id').change(function () {
            $.ajax({
                url: laroute.route('contract.contract.load-status'),
                dataType: 'JSON',
                data: {
                    contract_category_id: $('#contract_category_id').val(),
                },
                method: 'POST',
                global: false,
                success: function (res) {
                    $('#status_code').empty();
                    $('#status_code').append('<option value="">' + json['Chọn trạng thái'] + '</option>');
                    $.map(res.optionStatus, function (a) {
                        $('#status_code').append('<option value="' + a.status_code + '">' + a.status_name + '</option>');
                    });
                    $('#status_code').trigger('change');
                }
            });
        });
        $('.btn-search').trigger('click');
    });
});

var contractDetail = {
    export: function () {
        var params = {
            created_at: $('[name="created_at"]').val(),
            contract_category_id: $('[name="contract_category_id"]').val(),
            status_code: $('[name="status_code"]').val(),
            partner_object: $('[name="partner_object"]').val(),
            expired_date: $('[name="expired_date"]').val(),
            warranty_end_date: $('[name="warranty_end_date"]').val(),
        };
        var url = laroute.route('contract.report.contract-detail.export') + '?' + $.param(params);
        window.location = url;

    },
};
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#autotable').PioTable({
    baseUrl: laroute.route('contract.report.contract-detail.list')
});