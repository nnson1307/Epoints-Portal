$('#autotable-rule-setting-other').PioTable({
    baseUrl: laroute.route('admin.config-page-appointment.list-setting-other')
});
$('#autotable-booking-extra').PioTable({
    baseUrl: laroute.route('admin.config-page-appointment.list-booking-extra')
});
var other_extra = {
    edit_day: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-other-day');
            form.validate({
                rules: {
                    day: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    day: {
                        required: json['Hãy nhập số ngày được đặt xa nhất'],
                        number: json['Số ngày không hợp lệ']
                    }
                },
            });
            if (!form.valid()) {
                return false;
            }
            var day = $('input[name="day"]').val();
            $.ajax({
                url: laroute.route('admin.config-page-appointment.submit-edit-day'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    day: day
                },
                success: function (res) {
                    if (res.success == 1) {
                        swal(json["Cập nhật thời gian đặt lịch thành công"], "", "success");
                        $('#autotable-rule-setting-other').PioTable('refresh');
                    }
                }
            });
        });
    },
    change_status_setting_other: function (obj, id) {
        var is_actived = 0;
        if ($(obj).is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('admin.config-page-appointment.change-status-setting-other'),
            method: "POST",
            data: {
                id: id,
                is_actived: is_actived
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable-rule-setting-other').PioTable('refresh');
        });
    },
    edit_extra: function (e, id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var value = $(e).closest(".item_extra").find('.value').val();
            $.ajax({
                url: laroute.route('admin.config-page-appointment.submit-edit-booking-extra'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    value: value,
                    id: id
                },
                success: function (res) {
                    if (res.success == 1) {
                        swal(json["Cập nhật thành công"], "", "success");
                        $('#autotable-booking-extra').PioTable('refresh');
                    }
                }
            });
        });
    },
    remove_img: function (id) {
        $('.avatar_share_fb').empty();
        var tpl = $('#img-share-tpl').html();
        tpl = tpl.replace(/{id}/g, id);
        $('.avatar_share_fb').append(tpl);
        $('#avarta_fb_hidden').val('');
        $.ajax({
            url: laroute.route('admin.config-page-appointment.remove-img-fb'),
            method:'POST',
            dataType:'JSON',
            data:{
                id:id
            }
        });
    }
}

function uploadImageFB(input, id) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var imageAvatar = $('#banner_img');
        reader.onload = function (e) {
            $('#blah_share_fb')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var file_data = $('#getFileShareFB').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('id', id);
        $.ajax({
            url: laroute.route("admin.config-page-appointment.upload-img-fb"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.success == 1) {
                    $('#blah_share_fb_hidden').val(res.file);
                    $('.delete-img').css('display', 'block');
                    $('#autotable-booking-extra').PioTable('refresh');
                }

            }
        });
    }
}
