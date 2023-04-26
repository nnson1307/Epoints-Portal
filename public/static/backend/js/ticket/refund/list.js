var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
$(document).ready(function() {
    Refund.init();
});
var Refund = {
    init: function() {
        var add_popup = {
            rules: {
                staff_id: {
                    required: true
                },
                approve_id: {
                    required: true
                }
            },
            messages: {
                staff_id: {
                    required: 'Vui lòng chọn nhân viên hoàn ứng',
                },
                approve_id: {
                    required: 'Vui lòng chọn người duyệt phiếu hoàn ứng',
                }
            },
        };
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
        arrRange[lang['Hôm nay']] = [moment(), moment()],
            arrRange[lang['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
            arrRange[lang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
            arrRange[lang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
            arrRange[lang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
            arrRange[lang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
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
                "applyLabel": lang["Đồng ý"],
                "cancelLabel": lang["Thoát"],
                "customRangeLabel": lang["Tùy chọn ngày"],
                daysOfWeek: [
                    lang["CN"],
                    lang["T2"],
                    lang["T3"],
                    lang["T4"],
                    lang["T5"],
                    lang["T6"],
                    lang["T7"]
                ],
                "monthNames": [
                    lang["Tháng 1 năm"],
                    lang["Tháng 2 năm"],
                    lang["Tháng 3 năm"],
                    lang["Tháng 4 năm"],
                    lang["Tháng 5 năm"],
                    lang["Tháng 6 năm"],
                    lang["Tháng 7 năm"],
                    lang["Tháng 8 năm"],
                    lang["Tháng 9 năm"],
                    lang["Tháng 10 năm"],
                    lang["Tháng 11 năm"],
                    lang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
        });
        /* clear form */
        $('#add-popup').on('hidden.bs.modal', function(e) {
            $(this).find('[name="staff_id"]').val('').trigger('change');
            $(this).find('[name="approve_id"]').val('').trigger('change');
            $(this).find('[id$="-error"]').remove();
        });
        $('#add-popup').on('show.bs.modal', function(e) {
            $(this).find('[name="staff_id"]').val(user_id).trigger('change');
        });
        /* clear load queue */
        $('#add-popup [name="staff_id"]').change(function() {
            let staff_id = $(this).val();
            if (!staff_id) {
                return;
            }
            $.ajax({
                url: laroute.route('ticket.refund.load-queue-by-staff'),
                data: { staff_id: staff_id },
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    if (res.status == 1) {
                        if (res.queue_name) {
                            $('#add-popup [name="queue_name"]').val(res.queue_name);
                        } else {
                            $('#add-popup [name="queue_name"]').val('');
                        }
                    }
                }
            });
        });
        /* xử lý submit */
        $('#add-popup').submit(function() {
            let form = $(this);
            $(form).validate({
                rules: add_popup.rules,
                messages: add_popup.messages
            });
            if (!$(form).valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('ticket.refund.add'),
                data: form.serialize(),
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    if (res.error == 0) {
                        location.href = laroute.route('ticket.refund.add-view', { id: res.refund_id });
                    }
                }
            });
            return false;
        });
        /* end init seting */
    },
    addPopup: function() {
        $('#form-add-refund').validate({
            rules: {
                ticket_code: {
                    required: true,
                },
                import_file: {
                    required: false,
                    accept: false
                },
                description: {
                    required: true,
                    maxlength: 255,
                    minlength: 1
                },
            },
            messages: {
                ticket_code: {
                    required: lang['Mã ticket là trường bắt buộc nhập'],
                },
                description: {
                    required: lang['Mô tả là trường bắt buộc nhập'],
                    maxlength: lang['Mô tả không quá 255 ký tự'],
                }
            },
            submitHandler: function() {
                $.ajax({
                    url: laroute.route('ticket.refund.add'),
                    data: $("#form-add-refund").serialize(),
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 1) {
                            swal(
                                lang['Thêm vật tư thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                            $('#modalAdd').modal('hide');
                        }
                    }
                });
                return false;
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