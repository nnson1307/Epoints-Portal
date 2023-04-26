var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
$(document).ready(function() {
    SalaryCommissionConfig.init();
});
$.validator.addMethod("valueNotEquals", function(value, element, arg) {
    return arg !== value;
}, "Value must not equal arg.");
var SalaryCommissionConfig = {
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
    remove: function(obj, id) {
        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function(json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function() {
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function(result) {
                if (result.value) {
                    $.post(laroute.route('salary.salary_commission_config.remove', { id: id }), function() {
                        swal(
                            json['Xóa thành công.'],
                            '',
                            'success'
                        );
                        location.reload();
                    });
                }
            });
        });
    },
    changeStatus: function(obj, id, action) {
        $.post(laroute.route('salary.salary_commission_config.change-status'), { id: id, action: action }, function(data) {
            location.reload();
        }, 'JSON');
    },
    addView: function() {
        $.ajax({
            url: laroute.route('salary.salary_commission_config.add-view'),
            data: {},
            method: "POST",
            dataType: 'JSON',
            success: function(res) {
                if (res.error == 0) {
                    $('#modalAdd').html(res.html);
                    new AutoNumeric.multiple('.money_format', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0,
                        maximumValue: 999999999,
                    });

                    new AutoNumeric.multiple(".percent-format", {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0,
                        maximumValue: 100,
                    });

                    $('#modalAdd').modal('show');
                }
            }
        });
    },
    addClose: function() {
        let department_id = $('#modalAdd form [name="department_id"]').val();
        if (department_id) {
            $('.error-department_id').text('');
        } else {
            $('.error-department_id').text('Vui lòng chọn phòng ban');
        }
        if (department_id) {
            $.ajax({
                url: laroute.route('salary.salary_commission_config.add'),
                data: $('#modalAdd form').serialize(),
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.error == 0) {
                        swal(
                            'Thêm cấu hình thành công',
                            '',
                            'success'
                        );
                        location.reload();
                    } else {
                        $('#modalAdd').modal('hide');
                        swal(
                            'Thêm cấu hình không thành công',
                            '',
                            'warning'
                        );
                        clear();
                    }
                }
            });
        }

    },
    edit: function(id) {
        if (!id) {
            return;
        }
        $.ajax({
            url: laroute.route('salary.salary_commission_config.edit'),
            data: {
                id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(res) {
                if (res.error == 0) {
                    $('#modalEdit').html(res.html);
                    new AutoNumeric.multiple('.money_format', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0,
                        maximumValue: 999999999,
                    });
                    new AutoNumeric.multiple(".percent-format", {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0,
                        maximumValue: 100,
                    });

                    $('#modalEdit').modal('show');
                }
            }
        });
    },
    submitEdit: function(id) {
        if (!id) {
            return;
        }
        console.log(id)
        $.ajax({
            url: laroute.route('salary.salary_commission_config.submit-edit'),
            data: $('#modalEdit form').serialize(),
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                console.log($('#modalEdit form').serialize())
                if (data.error == 0) {
                    swal(
                        'Cập nhật cấu hình thành công',
                        '',
                        'success'
                    );
                    location.reload();
                }
                if (data.error == 1) {
                    $('#modalEdit').modal('hide');
                    swal(
                        'Cập nhật cấu hình không thành công',
                        '',
                        'warning'
                    );
                    clear();
                }
            }
        });
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
    }
};

function clear() {

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


$(".month-picker").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    startDate: new Date(),
});
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
/* function */
function pageCustom() {
    var $page = $('.frmFilter [name="page"]').val();
    if (!$page) {
        $page = 1;
    }
    $('#autotable a.m-datatable__pager-link').removeClass('m-datatable__pager-link--active').removeAttr('style');
    $('#autotable a.m-datatable__pager-link[data-page=' + $page + ']').addClass('m-datatable__pager-link--active');
}