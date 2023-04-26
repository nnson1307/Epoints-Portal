var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
var ManageTags = {
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
                    $.post(laroute.route('manager-work.tag.remove', { id: id }), function(res) {
                        if(!res.error){
                            swal(
                                json['Xóa thành công.'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                        } else{
                            swal(
                                json['Tag đã sử dụng, không thể xoá'],
                                '',
                                'warning'
                            );
                        }

                    });
                }
            });
        });
    },
    changeStatus: function(obj, id, action) {
        $.post(laroute.route('manager-work.tag.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function() {},
    addClose: function() {
        $.getJSON(laroute.route('translate'), function(json) {
            let manage_tag_name = $('#modalAdd [name="manage_tag_name"]');
            let error_manage_tag_name = $('#modalAdd .error_manage_tag_name');
            $(".err").css("color", "red");
            if (manage_tag_name.val() == "") {
                error_manage_tag_name.text(json['Vui lòng nhập tên tag']);
            } else if (manage_tag_name.val().length >= 255) {
                error_manage_tag_name.text(json['Tên tag không quá 255 ký tự']);
            } else {
                error_manage_tag_name.text('');
            }
            if (manage_tag_name.val() != "" && manage_tag_name.val().length < 255) {
                $.ajax({
                    url: laroute.route('manager-work.tag.add'),
                    data: {
                        manage_tag_name: manage_tag_name.val(),
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 1) {
                            error_manage_tag_name.text('');
                            swal(
                                json['Thêm tag thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                            $('#modalAdd').modal('hide');
                        } else {
                            error_manage_tag_name.text(json['Tên tag tồn tại']);
                        }
                    }
                });
            }
        });
    },
    edit: function(id) {
        clear();
        $('.error_manage_tag_name').text('');
        $.ajax({
            url: laroute.route('manager-work.tag.edit'),
            data: {
                manage_tag_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalEdit').modal('show');
                $('#modalEdit [name="manage_tag_id_hidden"]').val(data.manage_tag_id);
                $('#modalEdit [name="manage_tag_name"]').val(data.manage_tag_name);
            }
        });
    },
    submitEdit: function() {
        $.getJSON(laroute.route('translate'), function(json) {
            let id = $('#modalEdit [name="manage_tag_id_hidden"]');
            let manage_tag_name = $('#modalEdit [name="manage_tag_name"]');
            let error_manage_tag_name = $('#modalEdit .error_manage_tag_name');
            $(".err").css("color", "red");
            error_manage_tag_name.text('');
            if (manage_tag_name.val() == "") {
                error_manage_tag_name.text(json['Vui lòng nhập tên tag']);
            } else if (manage_tag_name.val().length >= 255) {
                error_manage_tag_name.text(json['Tên tag không quá 255 ký tự']);
            } else {
                error_manage_tag_name.text('');
            }
            if (manage_tag_name.val() != "" && manage_tag_name.val().length < 255) {
                $.ajax({
                    url: laroute.route('manager-work.tag.submit-edit'),
                    data: {
                        manage_tag_id: id.val(),
                        manage_tag_name: manage_tag_name.val(),
                        parameter: 0
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status == 0) {
                            error_manage_tag_name.text(json['Tên tag đã tồn tại']);
                        }
                        if (data.status == 1) {
                            $('#modalEdit .error_manage_tag_name').text('');
                            $('#modalEdit').modal('hide');
                            swal(
                                json['Cập nhật tag thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                        } else if (data.status == 2) {
                            swal(
                                json['Cập nhật tag thất bại'],
                                '',
                                'warning'
                            );
                        }
                    }
                });
            }
        });
    },
    view: function(id) {
        $.ajax({
            url: laroute.route('manager-work.tag.edit'),
            data: {
                manage_tag_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                $('#modalView [name="manage_tag_name"]').val(data['manage_tag_name']);
            }
        });
    },
    clear: function() {
        clear();
    },
    refresh: function() {
        $('input[name="search"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $('.daterange-picker').val('');
        $('.daterange-picker').selectpicker('refresh');
        $('[name="is_active"]').val('').trigger('change');
        $(".btn-search").trigger("click");
    },
    search: function() {
        $(".btn-search").trigger("click");
    },
};

function clear() {
    $('[name="manage_tag_name"]').val('');
    $('.error_manage_tag_name').text('');
}

$('#autotable').PioTable({
    baseUrl: laroute.route('manager-work.tag.list')
});

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $(input).closest('.div_avatar').find('[name="manage_tag_icon"]');
        reader.onload = function(e) {
            $(input).closest('.div_avatar').find('.blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $(input).closest('.div_avatar').find('[id^="getFile"]').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_manager_work.');

        var fsize = input.files[0].size;
        var fileInput = input,
            file = fileInput.files && fileInput.files[0];
        var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function() {
            var imageWidth = img.naturalWidth;
            var imageHeight = img.naturalHeight;

            window.URL.revokeObjectURL(img.src);

            $('.image-size').text(imageWidth + "x" + imageHeight + "px");

        };
        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');

        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());
        console.log(1)
        if (Math.round(fsize / 1024) <= 10240) {
            $('.error_img').text('');
            $.ajax({
                url: laroute.route("manager-work.tag.upload-image"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.success == 1) {
                        imageAvatar.val(res.file);
                        console.log(res)
                        $('.delete-img').css('display', 'block');
                    }
                }
            });
        } else {
            $.getJSON(laroute.route('translate'), function(json) {
                $('.error_img').text(json['Hình ảnh vượt quá dung lượng cho phép']);
            });
        }

    }
}
$(".exportToExcel").click(function(e) {
    $.getJSON(laroute.route('translate'), function(json) {
        swal({
            title: json['Thông báo'],
            text: json["Bạn có muốn Export không?"],
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: json['Export'],
            cancelButtonText: json['Hủy'],
        }).then(function(result) {
            if (result.value) {
                var table = $('#table-config');
                if (table && table.length) {
                    var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                    $(table).table2excel({
                        exclude: ".noExl",
                        name: "Excel",
                        filename: "danh_sach_cong_viec_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                        fileext: ".xls",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true,
                        preserveColors: preserveColors
                    });
                }
            }
        });
    });
});
$('form [name="check_start_date"]').click(function() {
    var id_modal = '#' + $(this).closest('.modal').attr('id');
    if ($(this).is(":checked")) {
        $(id_modal).find('.date-multiple').addClass('d-none').attr('disabled', 'disabled');
        $(id_modal).find('.date-single').removeClass('d-none').removeAttr('disabled');
    } else {
        $(id_modal).find('.date-multiple').removeClass('d-none').removeAttr('disabled');
        $(id_modal).find('.date-single').addClass('d-none').attr('disabled', 'disabled');
    }
});
$('.m_selectpicker').selectpicker();
$('select[name="is_active"]').select2();
$(".date-timepicker").datetimepicker({
    todayHighlight: !0,
    autoclose: !0,
    pickerPosition: "bottom-left",
    format: "dd/mm/yyyy hh:ii",
    // minDate: new Date(),
    // locale: 'vi'
});
$.getJSON(laroute.route('translate'), function (json) {
    var arrRange = {};
    arrRange[json['Hôm nay']] = [moment(), moment()],
        arrRange[json['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
        arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
        arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
        arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
        arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
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
    }).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
    });
    $(".daterange-input").daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        buttonClasses: "m-btn btn",
        applyClass: "btn-primary",
        cancelClass: "btn-danger",
        maxDate: moment().endOf("day"),
        startDate: moment().startOf("day"),
        endDate: moment().add(1, 'days'),
        locale: {
            format: 'DD/MM/YYYY hh:mm',
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
        ranges: {}
    }).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY hh:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY hh:mm'))
    });
});