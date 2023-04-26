$('#autotable').PioTable({
    baseUrl: laroute.route('admin.customer_appointment.list-cancel')
});
$(document).ready(function () {
    $('.select2').select2();
    $.getJSON(laroute.route('translate'), function (json) {
        $("#created_at").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY',
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
            }
        });
    });
    
});
var list_cancel={
    refresh:function () {
        $('input[name="search"]').val('');
        $('input[name="created_at"]').val('');
        $('select[name="customer_appointments$appointment_source_id"]').val('');
        $(".btn-search").trigger("click");
    }
}