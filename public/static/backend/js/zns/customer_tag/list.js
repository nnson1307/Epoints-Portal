$(document).ready(function () {
    CustomerTag.init();
});
var CustomerTag = {
    init: function () {
        /* custom lại pagination */
        $('#autotable a.m-datatable__pager-link').click(function (event) {
            var page = $(this).attr('data-page');
            if (!page) {
                page = 1;
            }
            $('.frmFilter [name="page"]').val(page);
            $('.frmFilter').submit();
        });
        pageCustom();
        /* end custom lại pagination */
    },
    add: function () {
        /* custom lại pagination */
        var color_code = $('#form-add [name="color_code"]').val();
        var tag_name = $('#form-add [name="tag_name"]').val();
        $.ajax({
            url: laroute.route('zns.customer-care-tag.add'),
            data: {
                color_code: color_code,
                tag_name: tag_name,
            },
            method: "POST",
            dataType: "JSON",
            success: function (data) {
                if (data.status == 1) {
                    swal(data.message, '', 'success').then(function () {
                        window.location.href = laroute.route('zns.customer-care-tag');
                    });
                } else {
                    swal(data.message, '', 'warning');
                }
            }
        }).fail(function (error) {
            var mess_error = '';
            $.map(error.responseJSON.errors, function (a) {
                mess_error = mess_error.concat(a + '<br/>');
            });
            Swal.fire({
                icon: 'error',
                title: 'Thông báo',
                html: mess_error,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Thử lại!',
            })
        });
        /* end custom lại pagination */
    },
    changeStatus: function (obj, id, action) {
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
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('zns.Template.change-status'), {id: id, action: action}, function (data) {
                    $(obj).prop('checked', !checked);
                }, 'JSON');
            }
        });

    },
    removeAction: function (id) {
        $.ajax({
            url: laroute.route('zns.customer-care-tag.remove'),
            method: "POST",
            data: {
                id: id,
            },
            success: function (data) {
                if (data.status == 1) {
                    swal(data.message, '', 'success').then(function () {
                        window.location.href = laroute.route('zns.customer-care-tag');
                    });
                } else {
                    swal(data.message, '', 'warning');
                }

            }
        });
    },
};
$(".form-color-input .choose-color").change(function () {
    $(this).closest(".form-color-input").find('span').css('background-color', $(this).val());
});
$(".form-color-input .choose-color").change(function () {
    var zalo_customer_tag_id = $(this).closest(".form-color-input").attr('data-id');
    var color_code = $(this).val();
    if (zalo_customer_tag_id) {
        $.ajax({
            url: laroute.route('zns.customer-care-tag.edit-action'),
            method: "POST",
            data: {
                zalo_customer_tag_id: zalo_customer_tag_id,
                color_code: color_code,
            },
            success: function (data) {
                if (data.status == 1) {
                    swal(data.message, '', 'success');
                } else {
                    swal(data.message, '', 'warning');
                }

            }
        });
    }
});

$(".form-color-input span").click(function () {
    $(this).closest(".form-color-input").find('.choose-color').click();
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