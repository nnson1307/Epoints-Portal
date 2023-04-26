var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
$('.select2').select2();
$('#m_datepicker_1,#m_datepicker_2').datepicker({
    rtl: mUtil.isRTL(),
    todayHighlight: true,
    orientation: "bottom left",
    todayHighlight: !0,
    format: "dd/mm/yyyy",
    // autoclose: !0,
    // format: "dd/mm/yyyy hh:ii",
    // startDate : new Date()
});
var Summernote = {
    init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $(".summernote").summernote({
                height: 208,
                placeholder: json['Nhập nội dung'],
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ]
            })
        });
    }
};
jQuery(document).ready(function () {
    Summernote.init()
    $('.note-btn').attr('title', '');
});
var view = {
    changeType: function (obj) {
        if ($(obj).val() == "CPI") {
            $(".group_accountable_by").hide();
            // $(".option_1_order_value").hide();
            // $(".option_2_order_value").hide();
            // $(".option_3_order_value").hide();
            $(".steps_2").show();
            $(".steps_3").hide();
            $(".condition_commission_CPI").show();
            $(".condition_commission_none").hide();
            $(".condition_commission_gtdh_sdh").hide();
        } else {
            $(".group_accountable_by").show();
            $(".steps_3").show();
            $(".steps_2").hide();
            $(".condition_commission_gtdh_sdh").show();
            $(".condition_commission_CPI").hide();
            $(".condition_commission_none").hide();
        }
    },

}

    function uploadImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
            var file_data = $('#getFile').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('link', '_staff.');

            $.ajax({
                url: laroute.route("referral.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.error == 0) {
                        $('#commission_avatar').val(res.file);
                    }
                }
            });
        }
    }
$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json["Hôm nay"]] = [moment(), moment()];
    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
    $("#date_apply").daterangepicker({
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
            "customRangeLabel": json['Tùy chọn ngày'],
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
    }).on('apply.daterangepicker', function (ev, picker) {
        var start = picker.startDate.format("DD/MM/YYYY");
        var end = picker.endDate.format("DD/MM/YYYY");
        $('#created_at').val(start + " - " + end);
        search.choosePerfomer();
    });

    $("#date_apply").val("");
});
var statechange = {
    change: function (id, job) {
        Swal.fire({
            title: jsonLang['Thông báo'],
            text: 'Hành động này không thể hoàn tác',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: jsonLang['Tiếp tục'],
            cancelButtonText: jsonLang['Hủy']
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: laroute.route("referral.stateChange"),
                    method: "POST",
                    data: {
                        id: id,
                        job: job
                    },
                    success: function (res) {
                        if (res.error == true) {
                            swal("Lỗi", res.message , "error")
                        } else {
                            window.location.href = res.link;
                        }
                    }
                })
            }
        })
    }
}
var search = {
    choosePerfomer: function () {
        $.ajax({
            url: laroute.route("referral.historyCommission"),
            method: "POST",
            data: {
                perfomer: $("#perfomer").val(),
                created_at: $("#created_at").val(),
                referral_program_id: $("#referral_program_id").val(),
            },
            success: function (res) {
                if (res.error == false) {
                    $('#history').html(res.view);
                } else {
                    swal.fire(res.message, jsonLang['Kiểm tra lại dữ liệu nhập!'], 'error');
                }
            }
        })
    }
}
function check() {
    let type = document.getElementById('type').value;
    if ( _.isEmpty(type)){
        Swal.fire({
            type: 'error',
            title: 'Lỗi',
            text: 'Vui lòng chọn loại tiêu chí',
        })
    }
}
function uploadImage(input) {
    $('.image-info').text('');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#file_name_avatar');
        reader.onload = function (e) {
            $('#blah-add')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        $('.delete-img').show();
        var file_data = $('#getFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_product.');

        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function () {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        var fsize = input.files[0].size;
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());

        $.ajax({
            url: laroute.route("referral.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                imageAvatar.val(data.file);
                $("#image").val(data.file)

            }
        });
    }
}
var referral = {
    chooseOrderPrice: function () {
        $.ajax({
            url: laroute.route("referral.saveInfoCommission"),
            method: "POST",
            data: $("#info-commission").serialize(),
            success: function (res) {
                if (res.error == true) {
                    swal("Lỗi", res.message , "error").then(function () {
                    });
                } else{
                    window.location.href = res.link;
                }
            }
        })
    },
    chooseCPI: function () {
        $.ajax({
            url: laroute.route("referral.saveInfoCommission"),
            method: "POST",
            data: $("#info-commission").serialize(),
            success: function (res) {
                if (res.error == true) {
                    swal("Lỗi", res.message , "error").then(function () {
                    });
                }else{
                    window.location.href = res.link;
                }
            }
        })
    }
}
var change = {
    chooseTypeCommondity: function () {
        $.ajax({
            url: laroute.route("referral.getGroupCommodity"),
            method: "POST",
            data: $("#type-commodity"),
            success: function (res) {
                if (res.error == false) {
                    $('#group-commodity').empty();
                    $('#group-commodity').append(res.view);
                    $(".choose-all").hide();
                } else {
                    swal.fire(res.message, 'Kiểm tra lại dữ liệu nhập!', 'error');
                }
            }
        })
    },
    chooseGroupCommodity: function () {
        $.ajax({
            url: laroute.route("referral.getCommodity"),
            method: "POST",
            data: {
                type: $("#type-commodity").val(),
                group_commodity: $("#group-commodity").val(),
                referral_program_id: $('#referral_program_id').val()
            },
            success: function (res) {
                if (res.error == false) {
                    $('#commodity').empty();
                    $('#commodity').append(res.view);
                } else {
                    swal.fire(res.message, ' Kiểm tra lại dữ liệu nhập!', 'error');
                }
            }
        })
    },
    addCommodity: function () {
        $.ajax({
            url: laroute.route("referral.addCommodity"),
            method: "POST",
            data: {
                type: $("#type-commodity").val(),
                group_commodity: $("#group-commodity").val(),
                commodity: $('#commodity').val(),
                referral_program_id: $('#referral_program_id').val()
            },
            success: function (res) {
                if (res.error == false) {
                    $('#commodity_table').html(res.view);
                    change.chooseGroupCommodity();
                } else {
                    swal.fire(res.message, ' Kiểm tra lại dữ liệu nhập!', 'error');
                }
            }
        })
    },
}
var commodity = {
    delete: function (obj, idCommodity) {
        let data = {
            idCommodity: idCommodity,
            referral_program_id: $('#referral_program_id').val()
        };
        $.ajax({
            url: laroute.route("referral.deleteCommodity"),
            method: "POST",
            data: data,
            success: function (res) {
                if (res.error == false) {
                    window.location.href = laroute.route("referral.chooseOrderPrice", {'id': $('#referral_program_id').val()})
                } else {
                    swal.fire(res.message, ' Xóa không thành công!', 'error');
                }
            }
        })
    },

    changePageProduct: function (page = 1) {

        $.ajax({
            url: laroute.route("referral.changePageProduct"),
            method: "POST",
            data: {
                referral_program_id: $('#referral_program_id').val(),
                page: page
            },
            success: function (res) {
                if (res.error == false) {
                    $('#commodity_table').html(res.view);
                } else {
                    swal.fire(res.message, ' Kiểm tra lại dữ liệu nhập!', 'error');
                }
            }
        })
    }
}
var edit ={
    editCommission: function () {
        $.ajax({
            url: laroute.route("referral.editCommission"),
            method: "POST",
            data: {
                referral_program_id: $('#referral_program_id').val()
            },
            success: function (res) {
                window.location.href = res.link;
            }
        })
    },
    nextStep3: function () {
        $.ajax({
            url: laroute.route("referral.step3ChooseOrderPrice"),
            method: "POST",
            data: {
                referral_program_id: $('#referral_program_id').val()
            },
            success: function (res) {
                window.location.href = res.link;
            }
        })
    }
}
$('.select2').select2();
new AutoNumeric.multiple('#max-order,#order-commission-value',  {
    currencySymbol: '',
    decimalCharacter: '.',
    digitGroupSeparator: ',',
    decimalPlaces: 0
});
var CommissionCondition = {
    save: function (id) {
        $.ajax({
            url: laroute.route("referral.saveConditonOrderPrice"),
            method: "POST",
            data: $("#condition-order-price").serialize(),
            success: function (res) {
                if (res.error == true) {
                    swal("Lỗi", res.message, "error");
                } else {
                    swal("Lưu thành công!", "Nhấn OK để tiếp tục!", "success").then(function () {
                        window.location.href = laroute.route("referral.editMultiLevelConfig", {id : id})
                    });
                }
            }
        });
    },
    change: function (obj) {
        if (obj== "percent") {
            $("#input_max_order").show();
            $('#commission_type_condition').val('percent');
        } else {
            $("#input_max_order").hide();
            $('#commission_type_condition').val('money')
        }
    },
};
if( $('#commission_type_condition').val()  == 'percent'){
    $(".commission_type_percent").trigger("click");
}else{
    $(".commission_type_money").trigger("click");
}

