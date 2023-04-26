$('#autotable-transfer').PioTable({
    baseUrl: laroute.route('admin.inventory-transfer.list')
});
// $("#created_at4").daterangepicker({
//     autoUpdateInput: false,
//     autoApply:true,
//     locale: {
//         format: 'DD/MM/YYYY',
//         daysOfWeek: [
//             "CN",
//             "T2",
//             "T3",
//             "T4",
//             "T5",
//             "T6",
//             "T7"
//         ],
//         "monthNames": [
//             "Tháng 1 năm",
//             "Tháng 2 năm",
//             "Tháng 3 năm",
//             "Tháng 4 năm",
//             "Tháng 5 năm",
//             "Tháng 6 năm",
//             "Tháng 7 năm",
//             "Tháng 8 năm",
//             "Tháng 9 năm",
//             "Tháng 10 năm",
//             "Tháng 11 năm",
//             "Tháng 12 năm"
//         ],
//         "firstDay": 1
//     }
// });
var InventoryTransfer = {
    remove: function (obj, id) {

        $(obj).closest('tr').addClass('m-table__row--danger');
        $.getJSON(laroute.route('translate'), function (json) {
        swal({
            title: json['Thông báo'],
            text: json["Bạn có muốn xóa không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: json['Xóa'],
            cancelButtonText: json['Hủy'],
            onClose: function () {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('admin.inventory-transfer.remove', {id: id}), function () {
                    swal(
                        json['Xóa thành công.'],
                        '',
                        'success'
                    );
                    $('#autotable-transfer').PioTable('refresh');
                });
            }
        });
    });
    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $('#created_at4').val('');
        $(".btn-search").trigger("click");
    },
    search: function () {
        $(".btn-search").trigger("click");
    },
    removeAllInput: function (thi) {
        $(thi).val('');
    },
    pageClick:function (page) {
        $.ajax({
            url:laroute.route('admin.inventory-transfer.paging-detail'),
            method:"POST",
            data:{
                page:page,
                id:$('#id').val(),
            },
            success:function (data) {
                $('.table-content').empty();
                $('.table-content').append(data);
            }
        });
    }
};
$('.m_selectpicker').select2();

$.getJSON(laroute.route('translate'), function (json) {

    //
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
$("#created_at4").daterangepicker({
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
        "applyLabel": json["Đồng ý"],
        "cancelLabel": json["Thoát"],
        "customRangeLabel": json["Tùy chọn ngày"],
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
}).on('apply.daterangepicker', function (ev) {

});
});
