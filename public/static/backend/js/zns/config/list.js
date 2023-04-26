var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
$(document).ready(function() {
    Config.init();
});
var Config = {
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
        /* end init seting */
    },
    changeStatus: function(obj, id, action) {
        var text_warning = "Khi kích hoạt trạng thái mẫu ZNS thì nội dung mẫu tin ZNS sẽ tự động gửi đến khách hàng khi thỏa điều kiện.<br> Mỗi tin ZNS gửi đi có tính phí, bạn có chắc chắn muốn kích hoạt?";
        var button_warning = "Kích hoạt";
        if (action) {
            text_warning = "Mẫu tin ZNS đang được sử dụng.Khi bỏ kích hoạt trạng thái mẫu ZNS thì hệ thống sẽ dừng tự động gửi đến khách hàng khi thỏa điều kiện. Bạn có chắc chắn muốn bỏ kích hoạt ? ";
            button_warning = "Bỏ kích hoạt";
        }
        var checked = true;
        if ($(obj).is(":checked")) {
            var checked = false;
        }
        $(obj).prop('checked', checked);
        swal({
            title: "Thông báo",
            html: text_warning,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: button_warning,
            cancelButtonText: "Hủy"
        }).then(function(result) {
            if (result.value) {
                $.post(laroute.route('zns.config.change-status'), { id: id, action: action }, function(data) {
                    $(obj).prop('checked', !checked);
                    toastr.success("Cập nhật trạng thái thành công", "Thông báo");
                }, 'JSON');
            }
        });

    },
    edit: function(id) {
        $.ajax({
            url: laroute.route('zns.config.edit'),
            data: { id: id },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#edit-config').html(data.html);
                    $("#send-time").timepicker({
                        minuteStep: 15,
                        defaultTime: $('#time-send-birthday').val(),
                        showMeridian: !1,
                        snapToStep: !0,
                    });
                    $('#number-day').selectpicker();
                    $('[name="zns_template_id"]').select2();
                    $('#edit-config').modal('show');
                }
            }
        });

    }
};
$('#edit-config').on('submit', function() {
    $('#edit-config').validate({
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
            let form = $('#edit-config');
            $.ajax({
                url: laroute.route('zns.config.edit-submit'),
                data: form.serialize(),
                method: "POST",
                dataType: "JSON",
                success: function(res) {
                    if (res.status) {
                        $('#edit-config').modal('hide');
                        swal("Chỉnh sửa thành công ",'','success').then(function () {
                            location.reload();
                        });
                    }
                }
            });
        }
    });
    return false;
});
$('#edit-config form').submit(function(e) {
    e.preventDefault();
    console.log(1)

});

$(document).on('change', '#edit-config [name="zns_template_id"]', function() {
    let val = $(this).val();
    if (!val) {
        return;
    }
    $.ajax({
        url: laroute.route('zns.template.get-template'),
        method: "POST",
        data: {
            zns_template_id: val,
            type: 'item'
        },
        success: function(res) {
            if(res.detail){
                $('#edit-config #content_zns').attr('src',res.detail.preview);
            }
        }
    })
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