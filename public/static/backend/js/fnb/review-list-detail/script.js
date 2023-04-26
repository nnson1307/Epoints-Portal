$('#autotable').PioTable({
    baseUrl: laroute.route('fnb.review-list-detail.list')
});

var requestListDetail = {
    jsonLang: JSON.parse(localStorage.getItem("tranlate")),
    _init : function (){
        $('select').select2();

        var arrRange = {};
        arrRange[requestListDetail.jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[requestListDetail.jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[requestListDetail.jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[requestListDetail.jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[requestListDetail.jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[requestListDetail.jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
        $(".daterange_picker").daterangepicker({
            // autoUpdateInput: false,
            autoApply: true,
            // maxDate: moment().endOf("day"),
            // startDate:moment().subtract(6, "days"),
            // endDate: moment(),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                "applyLabel": requestListDetail.jsonLang["Đồng ý"],
                "cancelLabel": requestListDetail.jsonLang["Thoát"],
                "customRangeLabel": requestListDetail.jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    requestListDetail.jsonLang["CN"],
                    requestListDetail.jsonLang["T2"],
                    requestListDetail.jsonLang["T3"],
                    requestListDetail.jsonLang["T4"],
                    requestListDetail.jsonLang["T5"],
                    requestListDetail.jsonLang["T6"],
                    requestListDetail.jsonLang["T7"]
                ],
                "monthNames": [
                    requestListDetail.jsonLang["Tháng 1 năm"],
                    requestListDetail.jsonLang["Tháng 2 năm"],
                    requestListDetail.jsonLang["Tháng 3 năm"],
                    requestListDetail.jsonLang["Tháng 4 năm"],
                    requestListDetail.jsonLang["Tháng 5 năm"],
                    requestListDetail.jsonLang["Tháng 6 năm"],
                    requestListDetail.jsonLang["Tháng 7 năm"],
                    requestListDetail.jsonLang["Tháng 8 năm"],
                    requestListDetail.jsonLang["Tháng 9 năm"],
                    requestListDetail.jsonLang["Tháng 10 năm"],
                    requestListDetail.jsonLang["Tháng 11 năm"],
                    requestListDetail.jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        }).on('apply.daterangepicker', function (ev) {

        });

        $(".daterange_picker").val('');

    },

    showPopup : function (id){
        $.ajax({
            url: laroute.route("fnb.review-list-detail.show-popup"),
            method: "POST",
            data: {
                id : id
            },
            success: function (res) {
                if (res.error == false){
                    $('.append-popup').empty();
                    $('.append-popup').append(res.view);
                    $('.select-form').select2();
                    $('#popup-review-list-detail').modal('show');
                }
            },
        });
    },

    saveReviewDetail : function (){
        var form = $('#form-review-list-detail');
        form.validate({
            rules: {
                popup_review_list_id: {
                    required: true,
                },
                popup_name: {
                    required: true,
                    maxlength: 191
                },
            },
            messages: {
                popup_review_list_id: {
                    required: 'Vui lòng chọn cấp độ đánh giá',
                },
                popup_name: {
                    required: 'Vui lòng nhập tên đánh giá',
                    maxlength: 'Tên đánh giá vượt quá 191 ký tự',
                },

            },
        });
        if (!form.valid()) {
            return false;
        }
        $.ajax({
            url: laroute.route("fnb.review-list-detail.save-review-list-detail"),
            method: "POST",
            data: $('#form-review-list-detail').serialize(),
            success: function (res) {
                if (res.error == false){
                    swal(res.message, '', "success").then(function (){
                        location.reload();
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
        });
    },

    removeReviewListDetail : function (obj,id){
        swal({
            title: requestListDetail.jsonLang['Thông báo'],
            text: requestListDetail.jsonLang['Bạn xác nhận muốn xóa thông tin . Thông tin đã xóa không thể khôi phục lại.'],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: requestListDetail.jsonLang['Xóa'],
            cancelButtonText: requestListDetail.jsonLang['Hủy'],
            onClose: function() {
                $(obj).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function(result) {
            if (result.value) {
                $.post(laroute.route('fnb.review-list-detail.remove-review-list-detail', { id: id }), function(res) {
                    if(res.error == false){
                        swal(
                            res.message,
                            '',
                            'success'
                        ).then(function (){
                            $('#autotable').PioTable('refresh');
                        });

                    } else {
                        swal(
                            res.message,
                            '',
                            'warning'
                        );
                    }

                });
            }
        });
    }

}