function refresh() {
    $('#branch').val('').trigger('change');
    $('#status').val('').trigger('change');
    $('#staff').val('').trigger('change');
    $('input[name=search_keyword]').val('');
    $('#time').val('');
    filter();
}

$('#branch').select2().on('select2:select', function () {
    filter();
});
$('#status').select2().on('select2:select', function () {
    filter();
});
$('#staff').select2().on('select2:select', function () {
    filter();
});
$('input[name=search_keyword]').keyup(function (e) {
    if (e.keyCode == 13) {
        $(this).trigger("enterKey");
    }
});
$('input[name=search_keyword]').bind("enterKey", function (e) {
    filter();
});
// Chọn ngày.
$('#time').on('apply.daterangepicker', function (ev, picker) {
    var start = picker.startDate.format("DD/MM/YYYY");
    var end = picker.endDate.format("DD/MM/YYYY");
    $(this).val(start + " - " + end);
    filter();
});
$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
    $("#time").daterangepicker({
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

function filter() {
    let keyWord = $('input[name=search_keyword]').val();
    let status = $('#status').val();
    let branch = $('#branch').val();
    let staff = $('#staff').val();
    let time = $('#time').val();

    $.ajax({
        url: laroute.route('admin.service-card.sold.filter'),
        method: "POST",
        data: {
            cardType: 'service',
            keyWord: keyWord,
            status: status,
            branch: branch,
            staff: staff,
            time: time
        },
        success: function (data) {
            $('.list-card').empty();
            $('.list-card').append(data);
        }
    })
}

// $('#autotable').PioTable({
//     baseUrl: laroute.route('admin.service-card.sold.detail-paginate')
// });

function pageClickDetailCardSold(val) {
    $.ajax({
        url: laroute.route('admin.service-card.sold.detail-paginate'),
        method: "POST",
        data: {
            page: val,
            code: $('#code').val()
        },
        success: function (data) {
            $('.list-history').empty();
            $('.list-history').append(data);
        }
    });
}

function pageClick(page) {
    $.ajax({
        url: laroute.route('admin.service-card.sold.paginate'),
        method: "POST",
        data: {
            page: page,
            cardType: 'service'
        },
        success: function (data) {
            $('.list-card').empty();
            $('.list-card').append(data);

        }
    });
}

function pageClickFilter(page) {
    let keyWord = $('input[name=search_keyword]').val();
    let status = $('#status').val();
    let branch = $('#branch').val();
    let staff = $('#staff').val();
    let time = $('#time').val();

    $.ajax({
        url: laroute.route('admin.service-card.sold.paging-search'),
        method: "POST",
        data: {
            cardType: 'service',
            keyWord: keyWord,
            status: status,
            branch: branch,
            staff: staff,
            time: time,
            page: page
        },
        success: function (data) {
            $('.list-card').empty();
            $('.list-card').append(data);
        }
    });
}
function notEnterInput(thi) {
    $(thi).val('');
}
$('.cancelBtn').removeClass('btn-danger');
$('.cancelBtn').addClass('btn-metal ss--btn');

$('.applyBtn').removeClass('btn-primary');
$('.applyBtn').addClass('ss--button-cms-piospa ss--btn');

if ($('tbody tr').length == 0) {
    $('.null-data').text('0');
}
$(".date-picker-expire").datepicker({
    format: "dd/mm/yyyy",
    startDate: '+1d',
    language: 'vi',
});
$("#number-using-not-limit").click(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        let value = $(this).prop('checked');
        if (value) {
            $('#number_using').attr('disabled', 'disabled');
            $('#expired_date').attr('disabled', 'disabled');
            $('#count_using').attr('disabled', 'disabled');
            $('#minus_using').val(json['Không giới hạn']);
        } else {
            $('#number_using').removeAttr('disabled');
            $('#expired_date').removeAttr('disabled');
            $('#count_using').removeAttr('disabled');
            $('#minus_using').val($('#number_using').val() - $('#count_using').val());
        }
    });
});
$(document).on('keyup', '#number_using', function () {
    $.getJSON(laroute.route('translate'), function (json) {
        var checkNumb = $.isNumeric($('#number_using').val());
        var checkCount = $.isNumeric($('#count_using').val());
        if (checkNumb && checkCount) {
            let result = $(this).val() - $('#count_using').val();
            if (result > -1) {
                $('#count_using_div').next('span').remove();
                $('#minus_using').val(result);
            } else {
                $('#count_using_div').next('span').remove();
                $('#count_using_div').after('<span class="form-control-feedback text-danger">' + json['Số lần sử dụng phải ít hơn số thẻ dịch vụ'] + '</span>');
                $('#minus_using').val('0');
            }
        }
    });
});
$(document).on('keyup', '#count_using', function () {
    $.getJSON(laroute.route('translate'), function (json) {
        var checkNumb = $.isNumeric($('#number_using').val());
        var checkCount = $.isNumeric($('#count_using').val());
        if (checkNumb && checkCount) {
            let result = $('#number_using').val() - $(this).val();
            if (result > -1) {
                $('#count_using_div').next('span').remove();
                $('#minus_using').val(result);
            } else {
                $('#count_using_div').next('span').remove();
                $('#count_using_div').after('<span class="form-control-feedback text-danger">' + json['Số lần sử dụng phải ít hơn số thẻ dịch vụ'] + '</span>');
                $('#minus_using').val('0');
            }
        }
    });
});
var serviceCard = {
    save: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-submit');
            form.validate({
                rules: {
                    number_using: {
                        number: true,
                        min: 1,
                        required: true
                    },
                    count_using: {
                        number: true,
                        min: 0,
                        required: true
                    },
                    note: {
                        required: true
                    },
                    expired_date: {
                        required: true
                    }
                },
                messages: {
                    number_using: {
                        number: json['Vui lòng chỉ nhập số'],
                        min: json['Vui lòng chỉ nhập số và tối thiểu là 1'],
                        required: json['Vui lòng nhập số lần sử dụng']
                    },
                    count_using: {
                        number: json['Vui lòng chỉ nhập số'],
                        min: json['Vui lòng chỉ nhập số và tối thiểu là 0'],
                        required: json['Vui lòng nhập số lần đã sử dụng']
                    },
                    note: {
                        required: json['Vui lòng nhập ghi chú']
                    },
                    expired_date: {
                        required: json['Vui lòng chọn hạn sử dụng']
                    }
                }
            });
            if (!form.valid()) {
                return;
            }

            form.submit();
        });

    },
    // bảo lưu
    reserve: function (code) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn bảo lưu không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Có'],
                cancelButtonText: json['Không'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.service-card.sold.reserve'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            card_code: code
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                            window.location.reload();
                        }
                    });
                } else {
                    window.location.reload();
                }
            });
        });
    },

    // Mở bảo lưu
    openReservation: function (code) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn mở bảo lưu không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Có'],
                cancelButtonText: json['Không'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.service-card.sold.open-reserve'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            card_code: code
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                            window.location.reload();
                        }
                    });
                } else {
                    window.location.reload();
                }
            });
        });
    }
};

var serviceCardSoldImage = {
    image_dropzone: function (orderCode, type, key) {
        $('#addImage').modal('show');
        $('#up-ima').empty();
        // Append image
        $.ajax({
            url: laroute.route('admin.service-card.sold.service-card.image-for-carousel'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                cardCode: $('#code').val(),
                orderCode: orderCode,
                type: type
            },
            success: function (res) {
                $('#up-ima').empty();
                $.map(res, function (val, k) {
                    var tpl = $('#imageOld').html();
                    tpl = tpl.replace(/{link}/g, val.link);
                    tpl = tpl.replace(/{id_image}/g, val.service_card_sold_image_id);
                    $('#up-ima').append(tpl);
                    $('.delete-img-sv').css('display', 'block');
                });
            }
        });
        //
        $('#order_code').val(orderCode);
        $('#type').val(type);
        $('#key').val(key);
        $('.dropzone')[0].dropzone.files.forEach(function (file) {
            file.previewElement.remove();
        });
        $('.dropzone').removeClass('dz-started');
    },

    save_image: function () {
        var arrayImage = [];
        $('.file_Name').each(function () {
            arrayImage.push($(this).val());
        });
        console.log(arrayImage);
        let arrImageNew = [];
        for (let i = 0; i < arrayImage.length; i++) {
            arrImageNew.push(arrayImage[i]);
        }
        // Lấy những ảnh còn lại (delete whereNotIn)
        let arrImageOld = [];
        $.each($('#up-ima').find(".list-image-old"), function () {
            let id = $(this).find($('.service_card_sold_image')).val();
            arrImageOld.push(id);
        });
        // Check tối đa 3 ảnh
        if (arrImageNew.length + arrImageOld.length > 3) {
            swal('Tối đa 3 ảnh', "", "warning");
            return false;
        }
        $('#addImage').modal('hide');
        // Lưu vào db
        $.ajax({
            url: laroute.route('admin.service-card.sold.service-card.save-image'),
            data: {
                customerServiceCardCode: $('#code').val(),
                type: $('#type').val(),
                orderCode: $('#order_code').val(),
                arrayImage: arrImageNew,
                arrayImageOld: arrImageOld
            },
            method: 'POST',
            dataType: "JSON",
            success: function (response) {
                if (response.error == false) {
                    // Append image to list
                    let key = $('#key').val();
                    if(response.type == 'before') {
                        $('.image-show-before-' + key).empty();
                        $.map(response.data, function (val, k) {
                            var tpl = $('#append-image').html();
                            tpl = tpl.replace(/{link}/g, val.link);
                            $('.image-show-before-' + key).append(tpl);
                        });
                    } else {
                        $('.image-show-after-' + key).empty();
                        $.map(response.data, function (val, k) {
                            var tpl = $('#append-image').html();
                            tpl = tpl.replace(/{link}/g, val.link);
                            console.log(tpl);
                            $('.image-show-after-' + key).append(tpl);
                        });
                    }
                    // Popup
                    swal(response.message, "", "success");
                } else {
                    swal(response.message, "", "error")
                }
            }
        });
    },

    remove_img: function (e) {
        $(e).closest('.image-show-child').remove();
    },

    modal_carousel: function (cardCode, orderCode, type) {
        $.ajax({
            url: laroute.route('admin.service-card.sold.service-card.image-for-carousel'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                cardCode: cardCode,
                orderCode: orderCode,
                type: type
            },
            success: function (res) {
                $('.append_carousel').empty();
                $.map(res, function (val, k) {
                    var status = '';
                    var tpl = $('#template-tpl').html();
                    if (k == 0) {
                        status = 'active';
                    } else {
                        status = '';
                    }
                    tpl = tpl.replace(/{status}/g, status);
                    tpl = tpl.replace(/{image}/g, val.link);
                    $('.append_carousel').append(tpl);
                });
                $('#setting-template').modal('show');
            }
        });

    },
};

var serviceCardSold = {
    // modal cộng dồn
    modalAccrual: function (cardCode) {
        $.ajax({
            url: laroute.route('admin.service-card.sold.service-card.modal-accrual-scs'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                cardCode: cardCode
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-create').modal('show');
            }
        });
    },
    // submit cộng dồn
    submitAccrual: function (cardCode) {
        let cardToAccrual = $('input[name="card_can_accrual"]:checked').val();
        if (cardToAccrual == null || typeof cardToAccrual === "undefined" ) {
            cardToAccrual = '';
        }
        if (cardToAccrual !== '') {
            $('.err-choose-card').text('');
            $.ajax({
                url: laroute.route('admin.service-card.sold.service-card.submit-accrual-scs'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    cardCode: cardCode,
                    cardToAccrual: cardToAccrual
                },
                success: function (res) {
                    if (res.error == false) {
                        swal.fire(res.message, "", "success");
                    } else {
                        swal.fire(res.message, '', "error");
                    }
                    window.location.reload();
                }
            });
        } else {
            $('.err-choose-card').text('Không có thẻ nào được chọn')
        }
    }
}