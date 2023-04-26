var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var listCard = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('warranty-card.list')
        });

        var arrRange = {};
        arrRange[jsonLang["Hôm nay"]] = [moment(), moment()];
        arrRange[jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
        arrRange[jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
        arrRange[jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
        arrRange[jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
        arrRange[jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

        $("#created_at").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            // buttonClasses: "m-btn btn",
            // applyClass: "btn-primary",
            // cancelClass: "btn-danger",
            // maxDate: moment().endOf("day"),
            // startDate: moment().startOf("day"),
            // endDate: moment().add(1, 'days'),
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                // "applyLabel": "Đồng ý",
                // "cancelLabel": "Thoát",
                "customRangeLabel": jsonLang['Tùy chọn ngày'],
                daysOfWeek: [
                    jsonLang["CN"],
                    jsonLang["T2"],
                    jsonLang["T3"],
                    jsonLang["T4"],
                    jsonLang["T5"],
                    jsonLang["T6"],
                    jsonLang["T7"]
                ],
                "monthNames": [
                    jsonLang["Tháng 1 năm"],
                    jsonLang["Tháng 2 năm"],
                    jsonLang["Tháng 3 năm"],
                    jsonLang["Tháng 4 năm"],
                    jsonLang["Tháng 5 năm"],
                    jsonLang["Tháng 6 năm"],
                    jsonLang["Tháng 7 năm"],
                    jsonLang["Tháng 8 năm"],
                    jsonLang["Tháng 9 năm"],
                    jsonLang["Tháng 10 năm"],
                    jsonLang["Tháng 11 năm"],
                    jsonLang["Tháng 12 năm"]
                ],
                "firstDay": 1
            },
            ranges: arrRange
        });
    },
    cancel: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: jsonLang['Thông báo'],
                text: jsonLang["Bạn có muốn huỷ không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: jsonLang['Có'],
                cancelButtonText: jsonLang['Không'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('warranty-card.cancel'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            warrantyCardId: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location = laroute.route('warranty-card');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    active: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: jsonLang['Thông báo'],
                text: jsonLang["Bạn có muốn kích hoạt không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: jsonLang['Có'],
                cancelButtonText: jsonLang['Không'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('warranty-card.active'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            warrantyCardId: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                $('#autotable').PioTable('refresh');
                                $('.tab_detail').PioTable('refresh');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    }
}

var edit = {
    _init: function () {
        $(".summernote").summernote({height: 110});
        $('.summernote').summernote('disable');
        $('#status').select2();
        if ($('#status').val() != 'new') {
            $('#status').prop('disabled', true);
        }
        // $('#object_serial').ForceNumericOnly();
    },
    save: function (code) {
        // Get array image
        let arrImageOld = [];
        let arrImageNew = [];
        // foreach row image
        $.each($('.image-show').find(".list-image-old"), function () {
            let link = $(this).find($('.product_image')).val();
            arrImageOld.push(link);
        });
        $.each($('.image-show').find(".list-image-new"), function () {
            let link = $(this).find($('.product_image')).val();
            arrImageNew.push(link);
        });

        $.ajax({
            url: laroute.route('warranty-card.update'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                warrantyCardCode: code,
                packedId: $('#packed_id').val(),
                status: $('#status').val(),
                objectSerial: $('#object_serial').val(),
                objectNote: $('#object_note').val(),
                arrImageOld: arrImageOld,
                arrImageNew: arrImageNew,
            },
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, "", "success");
                    window.location = laroute.route('warranty-card');
                } else {
                    swal.fire(res.message, '', "error");
                }
            }
        });
    }
};

var detailCard = {
    loadTab: function (tabView, warrantyCardId) {
        $.ajax({
            url: laroute.route('warranty-card.load-tab-detail'),
            method: "POST",
            dataType: "JSON",
            data: {
                tab_view: tabView,
                warranty_card_id: warrantyCardId
            },
            success: function (res) {
                $('.tab_detail').html(res.view);

                switch (tabView) {
                    case 'info':
                        $(".summernote").summernote({height: 110});
                        $('.summernote').summernote('disable');
                        $('#status').select2();
                        if ($('#status').val() != 'new') {
                            $('#status').prop('disabled', true);
                        }

                        break;
                    case 'maintenance':
                        var arrRange = {};
                        arrRange[jsonLang["Hôm nay"]] = [moment(), moment()];
                        arrRange[jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
                        arrRange[jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
                        arrRange[jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
                        arrRange[jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
                        arrRange[jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

                        $("#created_at, #date_estimate_delivery").daterangepicker({
                            autoUpdateInput: true,
                            autoApply: true,
                            // buttonClasses: "m-btn btn",
                            // applyClass: "btn-primary",
                            // cancelClass: "btn-danger",
                            // maxDate: moment().endOf("day"),
                            // startDate: moment().startOf("day"),
                            // endDate: moment().add(1, 'days'),
                            locale: {
                                cancelLabel: 'Clear',
                                format: 'DD/MM/YYYY',
                                // "applyLabel": "Đồng ý",
                                // "cancelLabel": "Thoát",
                                "customRangeLabel": jsonLang['Tùy chọn ngày'],
                                daysOfWeek: [
                                    jsonLang["CN"],
                                    jsonLang["T2"],
                                    jsonLang["T3"],
                                    jsonLang["T4"],
                                    jsonLang["T5"],
                                    jsonLang["T6"],
                                    jsonLang["T7"]
                                ],
                                "monthNames": [
                                    jsonLang["Tháng 1 năm"],
                                    jsonLang["Tháng 2 năm"],
                                    jsonLang["Tháng 3 năm"],
                                    jsonLang["Tháng 4 năm"],
                                    jsonLang["Tháng 5 năm"],
                                    jsonLang["Tháng 6 năm"],
                                    jsonLang["Tháng 7 năm"],
                                    jsonLang["Tháng 8 năm"],
                                    jsonLang["Tháng 9 năm"],
                                    jsonLang["Tháng 10 năm"],
                                    jsonLang["Tháng 11 năm"],
                                    jsonLang["Tháng 12 năm"]
                                ],
                                "firstDay": 1
                            },
                            ranges: arrRange
                        });

                        $("#created_at, #date_estimate_delivery").val("");

                        $('.select2').select2();

                        $('.tab_detail').PioTable({
                            baseUrl: laroute.route('maintenance.list')
                        });

                        $('.btn-search').trigger('click');

                        break
                }
            }
        });
    }
};

var productImage = {
    imageDropzone: function () {
        $('#addImage').modal('show');
        $('#up-ima').empty();
        $('.dropzone')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('.dropzone').removeClass('dz-started');
    },

    removeImage: function (e) {
        $(e).closest('.image-show-child').remove();
    },

    saveImage: function () {
        var arrayImage = new Array();
        $('.file_Name').each(function () {
            arrayImage.push($(this).val());
        });
        for (let i = 0; i < arrayImage.length; i++) {
            let $_tpl = $('#imgeShow').html();
            let tpl = $_tpl;
            tpl = tpl.replace(/{link}/g, arrayImage[i]);
            tpl = tpl.replace(/{link_hidden}/g, arrayImage[i]);
            $('.image-show').append(tpl);
            $('.delete-img-sv').css('display', 'block');
        }
        $('#addImage').modal('hide');
    }
}

jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };