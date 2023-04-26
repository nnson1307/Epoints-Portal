var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
$(document).ready(function() {
    Template.init();
});
var Template = {
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
        /* end init seting */
    },
    changeStatus: function(obj, id, action) {
        var text_warning = "Khi kích hoạt trạng thái mẫu ZNS thì nội dung mẫu tin ZNS sẽ tự động gửi đến khách hàng khi thỏa điều kiện. <br>Mỗi tin ZNS gửi đi có tính phí, bạn có chắc chắn muốn kích hoạt?";
        var button_warning = "Kích hoạt";
        if (action) {
            text_warning = "Mẫu tin ZNS đang được sử dụng.Khi bỏ kích hoạt trạng thái mẫu ZNS thì hệ thống sẽ dừng tự động gửi đến khách hàng khi thỏa điều kiện.Bạn có chắc chắn muốn bỏ kích hoạt ? ";
            button_warning = "Bỏ kích hoạt";
        }
        var checked = true;
        if ($(obj).is(":checked")) {
            var checked = false;
        }
        $(obj).prop('checked', checked);
        swal({
            title: "Thông báo",
            text: text_warning,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: button_warning,
            cancelButtonText: "Hủy"
        }).then(function(result) {
            if (result.value) {
                $.post(laroute.route('zns.Template.change-status'), { id: id, action: action }, function(data) {
                    $(obj).prop('checked', !checked);
                }, 'JSON');
            }
        });

    },
    edit: function(id) {
        $.ajax({
            url: laroute.route('zns.template.edit'),
            data: { id: id },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#edit-Template').html(data.html);
                    $("#send-time").timepicker({
                        minuteStep: 15,
                        defaultTime: $('#time-send-birthday').val(),
                        showMeridian: !1,
                        snapToStep: !0,
                    });
                    $('#number-day').selectpicker();
                    $('#edit-Template').modal('show');
                }
            }
        });

    },
    synchronized: function() {
        $.ajax({
            url: laroute.route('zns.template.synchronized'),
            data: {},
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    swal(
                        'Đồng bộ thành công',
                        '',
                        'success'
                    );
                    location.reload();
                }
            }
        });
    },
    cloneModal: function(id) {
        $('#confirm-clone [name=zns_template_id]').val(id);
        $('#confirm-clone').modal('show');
    },
    cloneAction: function(id) {
        $.ajax({
            url: laroute.route('zns.template-follower.clone-action'),
            method: "POST",
            data: {
                zns_template_id: $('#confirm-clone [name=zns_template_id]').val(),
                template_name: $('#confirm-clone [name=template_name]').val(),
            },
            success: function (res) {
                swal("Sao chép thành công",'','success').then(function () {
                    window.location.href = laroute.route('zns.template-follower');
                });
            }
        }).fail(function (error) {
            $('.error').remove();
            $.map(error.responseJSON.errors, function (mess, index) {
                if (index.indexOf('.') !== -1) {
                    let afterDot = index.split('.')[1];
                    let beforeDot = index.split('.')[0];
                    $('#confirm-clone [name^="' + beforeDot + '["]').eq(afterDot).closest('.form-group').append('<div class="mt-3 error">' + mess[0] + '</div>');
                } else {
                    $('#confirm-clone [name=' + index + ']').parent().append('<div class="mt-3 error">' + mess[0] + '</div>');
                }
            });
        });;
    },
};
$('#edit-Template').on('submit', function() {
    $('#edit-Template').validate({
        rules: {
            name: {
                required: true,
                maxlength: 191,
            },
            zns_template_id: {
                required: true,
            },
            hint: {
                maxlength: 191,
            },
        },
        messages: {
            name: {
                required: 'Tên mẫu là trường bắt buộc nhập',
                maxlength: 'Tên mẫu không quá 191 ký tự',
            },
            zns_template_id: {
                required: 'Tên mẫu ZNS là trường bắt buộc nhập',
            },
            hint: {
                required: 'Ghi chú không quá 191 ký tự',
            }
        },
        submitHandler: function() {
            let form = $('#edit-Template');
            $.ajax({
                url: laroute.route('zns.Template.edit-submit'),
                data: form.serialize(),
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    if (res.status) {
                        $('#edit-Template').modal('hide');
                        location.reload();
                    }
                }
            });
        }
    });
    return false;
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