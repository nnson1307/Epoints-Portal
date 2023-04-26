var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
$(document).ready(function() {
    Salary.init();
});
var Salary = {
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
    },
    changeStatus: function(obj, id, action) {
        $.post(laroute.route('salary.salary_commission_config.change-status'), { id: id, action: action }, function(data) {
            location.reload();
        }, 'JSON');
    },
    clear: function() {
        clear();
    },
    refresh: function() {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");
    },
    search: function() {
        $(".btn-search").trigger("click");
    },
    addClose: function() {
        $.ajax({
            url: laroute.route('salary.add'),
            data: $("#modalAdd form").serialize(),
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.error == 0) {
                    swal(
                        'Tạo bảng lương thành công',
                        '',
                        'success'
                    );
                    window.location.href = laroute.route('salary.detail', { id: data.id });
                }
            },

        }).fail(function(error) {
            var mess_error = '';
            $.map(error.responseJSON.errors, function(a) {
                mess_error = mess_error.concat(a + '<br/>');
            });
            Swal.fire({
                icon: 'error',
                title: 'Thông báo',
                html: mess_error,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Thử lại!',
            })
        });
        // $('#addForm').validate({
        //     rules: {
        //         salary_period: {
        //             required: true,
        //         },
        //         time: {
        //             required: true,
        //         },
        //         name: {
        //             required: true,
        //             maxlength: 191,
        //         },
        //     },
        //     messages: {
        //         salary_period: {
        //             required: 'Vui lòng chọn kỳ lương',
        //         },
        //         time: {
        //             required: 'Vui lòng chọn thời gian',
        //         },
        //         name: {
        //             required: 'Vui lòng nhập tên bảng lương',
        //             maxlength: 'Tên bảng lương không quá 191 ký tự',
        //         }
        //     },
        //     submitHandler: function() {

        //         return false;
        //     }
        // });

    }
};

function clear() {
    $('.month-picker').val('');
    $('.daterange-input').val('');
    $('[name="name"]').val('');
}

// $('#autotable').PioTable({
//     baseUrl: laroute.route('salary.salary_commission_config')
// });

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
    maxDate: moment().endOf("day"),
    startDate: moment().startOf("day"),
    endDate: moment().add(1, 'days'),
    locale: {
        format: 'DD/MM/YYYY',
        "applyLabel": "Đồng ý",
        "cancelLabel": "Thoát",
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
$(".daterange-input").daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    // buttonClasses: "m-btn btn",
    // applyClass: "btn-primary",
    // cancelClass: "btn-danger",
    // maxDate: moment().endOf("day"),
    startDate: moment().startOf("day"),
    endDate: moment().add(0, 'days'),
    // minDate: moment().startOf("day"),
    locale: {
        format: 'DD/MM/YYYY',
        // "applyLabel": "Đồng ý",
        // "cancelLabel": json["Thoát"],
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
    ranges: {}
}).on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
});

$(".month-picker").datepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "mm/yyyy",
    startView: "months",
    minViewMode: "months",
    language: "vi",
});
$(".month-picker-time-now").datepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "mm/yyyy",
    startView: "months",
    minViewMode: "months",
    language: "vi",
}).datepicker("setDate", new Date());

$(document).on('change', '.format-percent', function() {
    let val = parseFloat($(this).val()).toFixed(1);
    if (isNaN(val)) {
        $(this).val(0);
    } else {
        if (val > 100) {
            $(this).val(parseFloat(100).toFixed(1));
        } else if (val < 0) {
            $(this).val(parseFloat(0).toFixed(1));
        } else {
            $(this).val(val);
        }
    }
});

$("#modalAdd").on('shown.bs.modal', function() {
    let time = $(this).find('.month-picker-time-now').val();
    $(this).find('[name="name"]').val('Bảng lương tháng ' + time);
});

/* function */
function pageCustom() {
    var $page = $('.frmFilter [name="page"]').val();
    if (!$page) {
        $page = 1;
    }
    $('#autotable a.m-datatable__pager-link').removeClass('m-datatable__pager-link--active').removeAttr('style');
    $('#autotable a.m-datatable__pager-link[data-page=' + $page + ']').addClass('m-datatable__pager-link--active');
}