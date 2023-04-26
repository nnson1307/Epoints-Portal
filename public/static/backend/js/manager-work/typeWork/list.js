var x = "";
var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for (let z = 0; z < 10; z++) {
    x += possible.charAt(Math.floor(Math.random() * possible.length));
}
var d = new Date()
var code = x + d.getFullYear() + d.getMonth() + d.getHours() + d.getMinutes();
var TypeWork = {
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
                    $.ajax({
                        url: laroute.route('manager-work.type-work.remove'),
                        data: {
                            id: id
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function(res) {
                            if (res.error == false){
                                swal(
                                    res.message,
                                    '',
                                    'success'
                                );
                                $('#autotable').PioTable('refresh');
                            } else {
                                swal(
                                    res.message,
                                    '',
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });
    },
    changeStatus: function(obj, id, action) {
        $.post(laroute.route('manager-work.type-work.change-status'), { id: id, action: action }, function(data) {
            $('#autotable').PioTable('refresh');
        }, 'JSON');
    },
    add: function() {},
    addClose: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            let manage_type_work_name = $('#modalAdd [name="manage_type_work_name"]');
            let error_manage_type_work_name = $('#modalAdd .error_manage_type_work_name');
            $(".err").css("color", "red");
            if (manage_type_work_name.val() == "") {
                error_manage_type_work_name.text(json['Vui lòng nhập tên loại công việc']);
            } else if (manage_type_work_name.val().length >= 255) {
                error_manage_type_work_name.text(json['Tên loại công việc không quá 255 ký tự']);
            } else {
                error_manage_type_work_name.text('');
            }
            let manage_type_work_icon = $('#modalAdd [name="manage_type_work_icon"]');
            let error_manage_type_work_icon = $('#modalAdd .error_manage_type_work_icon');
            // if (manage_type_work_icon.val() == "") {
            //     error_manage_type_work_icon.text(json['Vui lòng chọn icon']);
            // } else {
            //     error_manage_type_work_icon.text('');
            // }
            // if (manage_type_work_name.val() != "" && manage_type_work_name.val().length < 255 && manage_type_work_icon.val() != "") {
            if (manage_type_work_name.val() != "" && manage_type_work_name.val().length < 255) {
                $.ajax({
                    url: laroute.route('manager-work.type-work.add'),
                    data: {
                        manage_type_work_name: manage_type_work_name.val(),
                        image: manage_type_work_icon.val(),
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 1) {
                            error_manage_type_work_name.text('');
                            swal(
                                json['Thêm loại công việc thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                            $('#modalAdd').modal('hide');
                        } else {
                            error_manage_type_work_name.text(json['Tên dự đã tồn tại']);
                        }
                    }
                });
            }
        });
    },
    edit: function(id) {
        clear();
        $('.error_manage_type_work_name').text('');
        $.ajax({
            url: laroute.route('manager-work.type-work.edit'),
            data: {
                manage_type_work_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalEdit').modal('show');
                $('#modalEdit [name="manage_type_work_id_hidden"]').val(data.manage_type_work_id);
                $('#modalEdit [name="manage_type_work_name"]').val(data.manage_type_work_name);
                if (data.manage_type_work_icon) {
                    $('#modalEdit [name="manage_type_work_icon"]').val(data.manage_type_work_icon);
                    $('#modalEdit .blah').attr('src', data.manage_type_work_icon_full);
                }
            }
        });
    },
    submitEdit: function() {
        $.getJSON(laroute.route('translate'), function (json) {
            let id = $('#modalEdit [name="manage_type_work_id_hidden"]');
            let manage_type_work_name = $('#modalEdit [name="manage_type_work_name"]');
            let error_manage_type_work_name = $('#modalEdit .error_manage_type_work_name');
            $(".err").css("color", "red");
            error_manage_type_work_name.text('');
            if (manage_type_work_name.val() == "") {
                error_manage_type_work_name.text(json['Vui lòng nhập tên loại công việc']);
            } else if (manage_type_work_name.val().length >= 255) {
                error_manage_type_work_name.text(json['Tên loại công việc không quá 255 ký tự']);
            } else {
                error_manage_type_work_name.text('');
            }
            let manage_type_work_icon = $('#modalEdit [name="manage_type_work_icon"]');
            let error_manage_type_work_icon = $('#modalEdit .error_manage_type_work_icon');
            if (manage_type_work_icon.val() == "") {
                error_manage_type_work_icon.text(json['Vui lòng chọn icon']);
            } else {
                error_manage_type_work_icon.text('');
            }
            if (manage_type_work_name.val() != "" && manage_type_work_name.val().length < 255 && manage_type_work_icon.val() != "") {
                $.ajax({
                    url: laroute.route('manager-work.type-work.submit-edit'),
                    data: {
                        manage_type_work_id: id.val(),
                        manage_type_work_name: manage_type_work_name.val(),
                        image: manage_type_work_icon.val(),
                        parameter: 0
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status == 0) {
                            error_manage_type_work_name.text(json['Tên loại công việc đã tồn tại']);
                        }
                        if (data.status == 1) {
                            $('#modalEdit .error_manage_type_work_name').text('');
                            $('#modalEdit').modal('hide');
                            swal(
                                json['Cập nhật loại công việc thành công'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                            clear();
                        } else if (data.status == 2) {
                            swal(
                                json['Cập nhật loại công việc thất bại'],
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
            url: laroute.route('manager-work.type-work.edit'),
            data: {
                manage_type_work_id: id
            },
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                $('#modalView').modal('show');
                $('#modalView [name="manage_type_work_name"]').val(data['manage_type_work_name']);
                $('#modalView .blah').attr('src', data.manage_type_work_icon_full);
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
    }
};

function clear() {
    $('[name="manage_type_work_name"]').val('');
    $('.error_manage_type_work_name').text('');
    $('[name="manage_type_work_icon"]').val('');
    $('.error_manage_type_work_icon').text('');
    $('.blah').attr('src', img_default);
}

$('#autotable').PioTable({
    baseUrl: laroute.route('manager-work.type-work.list')
});

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
$('.m_selectpicker').selectpicker();
$('select[name="is_active"]').select2();

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
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
    });
});

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $(input).closest('.div_avatar').find('[name="manage_type_work_icon"]');
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
                url: laroute.route("manager-work.detail.upload-file"),
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {
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