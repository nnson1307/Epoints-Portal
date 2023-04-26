var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
$(document).ready(function() {
    Params.init();
});
var Params = {
    init: function() {
        /* custom lại pagination */
        $('#autotable a.m-datatable__pager-link').click(function(event) {
            var page = $(this).attr('data-page');
            if (!page) {
                page = 1;
            }
            $('.frmFilter [name="page"]').val(page);
            $('.frmFilter').submit();
        });
        pageCustom();
        /* end custom lại pagination */
        /* init seting */
        $('.m_selectpicker').selectpicker();
        $('select[name="is_actived"]').select2();
        var arrRange = {};
        arrRange['Hôm nay'] = [moment(), moment()],
            arrRange['Hôm qua'] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
            arrRange["7 ngày trước"] = [moment().subtract(6, "days"), moment()],
            arrRange["30 ngày trước"] = [moment().subtract(29, "days"), moment()],
            arrRange["Trong tháng"] = [moment().startOf("month"), moment().endOf("month")],
            arrRange["Tháng trước"] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
        $(".daterange-picker").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            buttonClasses: "m-btn btn",
            applyClass: "btn-primary",
            cancelClass: "btn-danger",
            // maxDate: moment().endOf("day"),
            startDate: moment().startOf("day"),
            endDate: moment().add(1, 'days'),
            locale: {
                format: 'DD/MM/YYYY',
                "applyLabel": "Đồng ý",
                "cancelLabel": json["Thoát"],
                "customRangeLabel": "Tùy chọn ngày",
                daysOfWeek: [
                    "CN",
                    "T2",
                    "T3",
                    "T4",
                    "T5",
                    "T6",
                    "T7"
                ],
                "monthNames": [
                    "Tháng 1 năm",
                    "Tháng 2 năm",
                    "Tháng 3 năm",
                    "Tháng 4 năm",
                    "Tháng 5 năm",
                    "Tháng 6 năm",
                    "Tháng 7 năm",
                    "Tháng 8 năm",
                    "Tháng 9 năm",
                    "Tháng 10 năm",
                    "Tháng 11 năm",
                    "Tháng 12 năm"
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
        });
        /* end init seting */
    },
    edit: function(params_id) {
        $.ajax({
            url: laroute.route('zns.params.edit'),
            data: { params_id: params_id },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#edit-params').html(data.html);
                    $('#edit-params').modal('show');
                }
            }
        });
    }
};


/* function */
function pageCustom() {
    var $page = $('.frmFilter [name="page"]').val();
    if (!$page) {
        $page = 1;
    }
    $('#autotable a.m-datatable__pager-link').removeClass('m-datatable__pager-link--active').removeAttr('style');
    $('#autotable a.m-datatable__pager-link[data-page=' + $page + ']').addClass('m-datatable__pager-link--active');
}
$('#edit-params').submit(function(e) {
    let form = $('#edit-params');
    $.ajax({
        url: laroute.route('zns.params.edit-submit'),
        data: form.serialize(),
        method: "POST",
        dataType: "JSON",
        success: function(res) {
            if (res.status) {
                $('#edit-params').modal('hide');
                location.reload();
            }
        }
    });
    return false;
});