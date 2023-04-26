$('#autotable-rule-menu').PioTable({
    baseUrl: laroute.route('admin.config-page-appointment.list-rule-menu')
});
$('#autotable-rule-booking').PioTable({
    baseUrl: laroute.route('admin.config-page-appointment.list-rule-booking')
});
var menu_booking = {
    change_status_menu: function (obj, id) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }
        $.ajax({
            url: laroute.route('admin.config-page-appointment.change-status-menu'),
            method: "POST",
            data: {
                id: id,
                is_actived: is_actived
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable-rule-menu').PioTable('refresh');
        });
    },
    edit_position: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var id = new Array();
            $('.id_menu').each(function () {
                id.push($(this).val());
            });
            var position = new Array();
            $('.position').each(function () {
                position.push($(this).val());
            });
            $.ajax({
                url: laroute.route('admin.config-page-appointment.submit-edit-rule-menu'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    position: position
                },
                success: function (res) {
                    if (res.success == 1) {
                        swal(json["Cập nhật vị trí thành công"], "", "success");
                        $('#autotable-rule-menu').PioTable('refresh');
                    }
                }
            });
        });
    },
    change_status_booking: function (obj, id) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }
        $.ajax({
            url: laroute.route('admin.config-page-appointment.change-status-booking'),
            method: "POST",
            data: {
                id: id,
                is_actived: is_actived
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable-rule-booking').PioTable('refresh');
        });
    },
}
$(document).ready(function () {
    $(".position").on('keyup', function () {
        var id = $(this).closest("tr").find('.id_menu').val();
        var n = parseInt($(this).val().replace(/\D/g, ''), 10);
        if (typeof n == 'number' && Number.isInteger(n))
            $(this).val(n.toLocaleString());
        else {
            $(this).val("");
        }
    });
});
