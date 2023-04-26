$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#bussiness_id').select2({
            placeholder: json['Hãy chọn ngành nghề kinh doanh']
        });
        $('#districtid').select2({
            placeholder: json['Hãy chọn quận/ huyện']
        });
        $.ajax({
            url: laroute.route('admin.customer.load-district'),
            dataType: 'JSON',
            data: {
                id_province: $('#provinceid').val()
            },
            method: 'POST',
            success: function (res) {
                $('#districtid').empty();
                $.map(res.optionDistrict, function (a) {
                    if ($('#district_hidden').val() == a.id) {
                        $('#districtid').append('<option value="' + a.id + '" selected>' + a.type + ' ' + a.name + '</option>');
                    } else {
                        $('#districtid').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');
                    }
                });

            }
        });
        $('#provinceid').select2({
            placeholder: json['Hãy chọn tỉnh/ thành phố']
        }).on('select2:select', function (ev) {
            $.ajax({
                url: laroute.route('admin.customer.load-district'),
                dataType: 'JSON',
                data: {
                    id_province: ev.params.data.id
                },
                method: 'POST',
                success: function (res) {
                    $('#districtid').empty();
                    $.map(res.optionDistrict, function (a) {
                        $('#districtid').append('<option></option>');
                        $('#districtid').append('<option value="' + a.id + '">' + a.type + ' ' + a.name + '</option>');

                    });

                }
            });
        });
        $('#branch_apply_order').select2({
            placeholder: json['Chọn chi nhánh']
        });
    });
});
var spa_info = {
    submit_add: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-add');
            form.validate({
                rules: {
                    name: {
                        required: true,
                    },
                    address: {
                        required: true
                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 11,
                        number: true
                    },
                    provinceid: {
                        required: true
                    },
                    districtid: {
                        required: true
                    },
                    hot_line: {
                        number: true
                    },
                    code: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: json['Hãy nhập tên chi nhánh']
                    },
                    address: {
                        required: json['Hãy nhập địa chỉ']
                    },
                    phone: {
                        required: json['Hãy nhập số điện thoại'],
                        minlength: json['Tối thiểu 10 số'],
                        number: json['Số điện thoại không hợp lệ'],
                        maxlength: json['Số điện thoại không hợp lệ']
                    },
                    provinceid: {
                        required: json['Hãy chọn tỉnh/thành phố']
                    },
                    districtid: {
                        required: json['Hãy chọn quận/huyện']
                    },
                    hot_line: {
                        number: json['Số hot line không hợp lệ']
                    },
                    code: {
                        required: json['Hãy nhập mã đại diện']
                    }
                },
            });
            if (!form.valid()) {
                return false;
            }
            var check = true;
            var email = $('#email').val()
            if (email != '') {
                if (!isValidEmailAddress(email)) {
                    $('.error_email').text(json['Email không hợp lệ']);
                    check = false;
                    return false;
                } else {
                    check = true;
                }
            } else {
                check = true;
            }
            if (check == true) {
                $.ajax({
                    url: laroute.route('admin.config-page-appointment.submit-add-info'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        name: $('#name').val(),
                        code: $('#code').val(),
                        phone: $('#phone').val(),
                        email: $('#email').val(),
                        hot_line: $('#hot_line').val(),
                        fanpage: $('#fanpage').val(),
                        zalo: $('#zalo').val(),
                        instagram_page: $('#instagram_page').val(),
                        provinceid: $('#provinceid').val(),
                        districtid: $('#districtid').val(),
                        address: $('#address').val(),
                        logo: $('#logo').val(),
                        slogan: $('#slogan').val(),
                        bussiness_id: $('#bussiness_id').val()
                    },
                    success: function (res) {
                        if (res.success == 1) {
                            $('.error_name').text('');
                            swal(json["Thêm đơn vị thành công"], "", "success");
                            window.location = laroute.route('admin.config.page-appointment');
                        }
                        if (res.success == 0) {
                            $('.error_name').text(json['Tên đơn vị đã tồn tại']);
                        }
                    }
                });
            }
        });
    },
    submit_edit: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-edit');
            form.validate({
                rules: {
                    name: {
                        required: true,
                    },
                    address: {
                        required: true
                    },
                    phone: {
                        required: true,
                        // minlength: 10,
                        // maxlength: 11,
                        // number: true
                    },
                    provinceid: {
                        required: true
                    },
                    districtid: {
                        required: true
                    },
                    hot_line: {
                        number: true
                    },
                    code: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: json['Hãy nhập tên chi nhánh']
                    },
                    address: {
                        required: json['Hãy nhập địa chỉ']
                    },
                    phone: {
                        required: json['Hãy nhập số điện thoại'],
                        // minlength: json['Tối thiểu 10 số'],
                        // number: json['Số điện thoại không hợp lệ'],
                        // maxlength: json['Số điện thoại không hợp lệ']
                    },
                    provinceid: {
                        required: json['Hãy chọn tỉnh/thành phố']
                    },
                    districtid: {
                        required: json['Hãy chọn quận/huyện']
                    },
                    hot_line: {
                        number: json['Số hot line không hợp lệ']
                    },
                    code: {
                        required: json['Hãy nhập mã đại diện']
                    }
                },
            });
            if (!form.valid()) {
                return false;
            }
            var check = true;
            var email = $('#email').val();
            var is_part_paid = 0;
            if ($('#is_part_paid').is(':checked')) {
                is_part_paid = 1;
            }
            if (email != '') {
                if (!isValidEmailAddress(email)) {
                    $('.error_email').text(json['Email không hợp lệ']);
                    check = false;
                    return false;
                } else {
                    check = true;
                }
            } else {
                check = true;
            }
            if (check == true) {
                $.ajax({
                    url: laroute.route('admin.config-page-appointment.submit-edit-info'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        id: $('#id_hidden').val(),
                        name: $('#name').val(),
                        code: $('#code').val(),
                        phone: $('#phone').val(),
                        email: $('#email').val(),
                        hot_line: $('#hot_line').val(),
                        fanpage: $('#fanpage').val(),
                        zalo: $('#zalo').val(),
                        instagram_page: $('#instagram_page').val(),
                        provinceid: $('#provinceid').val(),
                        districtid: $('#districtid').val(),
                        address: $('#address').val(),
                        logo: $('#logo').val(),
                        logo_edit: $('#logo_edit').val(),
                        slogan: $('#slogan').val(),
                        bussiness_id: $('#bussiness_id').val(),
                        is_part_paid: is_part_paid,
                        branch_apply_order: $('#branch_apply_order').val(),
                        total_booking_time: $('#total_booking_time').val()
                    },
                    success: function (res) {
                        if (res.success == 1) {
                            $('.error_name').text('');
                            swal(json["Cập nhật đơn vị thành công"], "", "success");
                            window.location.reload();
                        }
                        // if(res.success==0)
                        // {
                        //     $('.error_name').text('Tên đơn vị đã tồn tại');
                        // }
                    }
                });
            }
        });
    },
    remove_avatar: function () {
        $('.avatar').empty();
        var tpl = $('#avatar-tpl').html();
        $('.avatar').append(tpl);

    },
    remove_avatar_edit: function () {
        $('.avatar').empty();
        var tpl = $('#avatar-tpl').html();
        $('.avatar').append(tpl);
        $('#logo_edit').val('');
    },
    remove: function (obj, id) {
        $.getJSON(laroute.route('translate'), function (json) {
            // hightlight row
            $(obj).closest('tr').addClass('m-table__row--danger');

            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
                onClose: function () {
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.config-page-appointment.remove', {id: id}), function () {
                        swal(
                            json['Xóa thành công'],
                            '',
                            'success'
                        );
                        // window.location.reload();
                        $('#autotable').PioTable('refresh');
                    });
                }
            });
        });
    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.config-page-appointment.change-status'),
            method: "POST",
            data: {
                id: id,
                action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
}
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.config-page-appointment.list-info')
});

function uploadImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#logo');
        reader.onload = function (e) {
            $('#blah_info').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFileInfo').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('link', '_config.');

        $.ajax({
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == 0) {
                    $('#logo').val(res.file);
                    $('.delete-img').css('display', 'block');

                }

            }
        });
    }
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}