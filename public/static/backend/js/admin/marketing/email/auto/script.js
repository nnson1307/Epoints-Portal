$(document).ready(function () {
    $('.carousel').carousel({
        ride: true,
        center: true
    });
});
var auto = {
    modal_setting: function (id) {

        $.ajax({
            url: laroute.route('admin.email-auto.config'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                id: id
            },
            success: function (res) {
                $.getJSON(laroute.route('translate'), function (json) {
                    $('.setting_on').empty();
                    $('.append_pass').empty();
                    $('#config').modal('show');
                    $('#id').val(res.item.id);
                    $('#is_actived').val(res.item.is_actived);
                    if (res.item.is_actived == 1) {
                        $('#is_actived').prop('checked', true);
                        var tpl = $('#type-tpl').html();
                        $('.setting_on').append(tpl);
                        $('#type').select2({
                            placeholder: json['Hãy chọn hình thức gửi email']
                        }).on('select2:select', function (ev) {
                            $('.append_pass').empty();
                            if (ev.params.data.id == 'gmail' || ev.params.data.id == 'clicksend') {
                                var tpl_p = $('#pass-tpl').html();
                                $('.append_pass').append(tpl_p);
                            } else {
                                $('.append_pass').empty();
                            }
                        });
                        $('#type').val(res.item.type).trigger('change');
                        if (res.item.type == 'gmail' || res.item.type == 'clicksend') {
                            var tpl_p = $('#pass-tpl').html();
                            $('.append_pass').append(tpl_p);
                        }
                        $('#email').val(res.item.email);
                        $('#password').val(res.item.password);
                        $('#pass_check').val(res.item.password);
                        $('#name').val(res.item.name_email);
                    } else {
                        $('#is_actived').prop('checked', false);
                    }
                });
            }
        })
    },
    submit_config: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form_config = $('#form-config');
            form_config.validate({
                rules: {
                    type: {
                        required: true,
                    },
                    email: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    name: {
                        required: true
                    }
                },
                messages: {
                    type: {
                        required: json['Hãy chọn hình thức gửi']
                    },
                    email: {
                        required: json['Hãy nhập email/account']
                    },
                    password: {
                        required: json['Hãy nhập mật khẩu']
                    },
                    name: {
                        required: json['Hãy nhập tên đại diện']
                    }

                }
            });
            if (!form_config.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('admin.email-auto.submit-config'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    is_actived: $('#is_actived').val(),
                    type: $('#type').val(),
                    email: $('#email').val(),
                    name_email: $('#name').val(),
                    password: $('#password').val(),
                    id: $('#id').val(),
                    pass_check: $('#pass_check').val()
                },
                success: function (res) {
                    if (res.success == 1) {
                        swal(json["Chỉnh sửa thành công"], "", "success");
                        $('#config').modal('hide');

                    }
                }
            })
        });
    },
    click_auto: function (e) {
        $.getJSON(laroute.route('translate'), function (json) {
            if ($(e).prop('checked')) {
                var tpl = $('#type-tpl').html();
                $('.setting_on').append(tpl);
                $('#type').select2({
                    placeholder: json['Hãy chọn hình thức gửi email']
                }).on('select2:select', function (ev) {
                    if (ev.params.data.id == 'gmail' || ev.params.data.id == 'clicksend') {
                        var tpl_p = $('#pass-tpl').html();
                        $('.append_pass').append(tpl_p);
                    } else {
                        $('.append_pass').empty();
                    }
                });
                $(e).val(1);
                $.ajax({
                    url: laroute.route('admin.email-auto.config'),
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        id: $('#id').val()
                    },
                    success: function (res) {
                        $('.setting_on').empty();
                        $('.append_pass').empty();
                        $('#config').modal('show');
                        $('#id').val(res.item.id);
                        // $('#is_actived').val(res.item.is_actived);
                        var tpl = $('#type-tpl').html();
                        $('.setting_on').append(tpl);
                        $('#type').select2({
                            placeholder: json['Hãy chọn hình thức gửi email']
                        }).on('select2:select', function (ev) {
                            $('.append_pass').empty();
                            if (ev.params.data.id == 'gmail' || ev.params.data.id == 'clicksend') {
                                var tpl_p = $('#pass-tpl').html();
                                $('.append_pass').append(tpl_p);
                            } else {
                                $('.append_pass').empty();
                            }
                        });
                        $('#type').val(res.item.type).trigger('change');
                        if (res.item.type == 'gmail' || res.item.type == 'clicksend') {
                            var tpl_p = $('#pass-tpl').html();
                            $('.append_pass').append(tpl_p);
                        }
                        $('#email').val(res.item.email);
                        $('#password').val(res.item.password);
                        $('#pass_check').val(res.item.password);
                        $('#name').val(res.item.name_email);

                    }
                })
            } else {
                $(e).val(0);
                $('.setting_on').empty();
            }
        });
    },
    changeStatus: function (obj, id) {
        if ($(obj).prop('checked')) {
            $(obj).val(1);
        } else {
            $(obj).val(0);

        }
        $.ajax({
            url: laroute.route('admin.email-auto.change-status'),
            method: "POST",
            data: {
                id: id,
                is_actived: $(obj).val()
            },
            dataType: "JSON"
        }).done(function (data) {

        });
    },
    modal_content: function (id) {
        $.ajax({
            url: laroute.route('admin.email-auto.setting-content'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                id: id
            }, success: function (res) {
                $.getJSON(laroute.route('translate'), function (json) {
                    $('.append_type').empty();
                    $('#tb_para').empty();
                    $('#id_content').val(res.item.id);
                    $('#type_content').val(res.item.key);
                    $('#title').val(res.item.title);
                    $('#setting-content').modal('show');
                    if (res.item.key == 'birthday') {
                        $('#span').text(json['Cấu hình chúc mừng sinh nhật']);
                        var tpl = $('#time-sent-tpl').html();
                        $('.append_type').append(tpl);
                        var form = ['name', 'full_name', 'gender', 'birthday', 'email'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'birthday') {
                                tpl = tpl.replace(/{note}/g, json['Ngày sinh']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'remind_appointment') {
                        $('#span').text('Cấu hình nhắc lịch hẹn');
                        var tpl = $('#time-after-tpl').html();
                        $('.append_type').append(tpl);
                        var form = ['name', 'full_name', 'gender', 'time', 'name_spa', 'email'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'time') {
                                tpl = tpl.replace(/{note}/g, json['Thời gian']);
                            } else if (form[i] == 'name_spa') {
                                tpl = tpl.replace(/{note}/g, json['Tên spa']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'service_card_nearly_expired') {
                        $('#span').text(json['Cấu hình thông báo thẻ dịch vụ sắp hết hạn']);
                        var tpl = $('#day-after-tpl').html();
                        $('.append_type').append(tpl);
                        var form = ['name', 'full_name', 'gender', 'code_card', 'time', 'email'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'time') {
                                tpl = tpl.replace(/{note}/g, json['Thời gian']);
                            } else if (form[i] == 'code_card') {
                                tpl = tpl.replace(/{note}/g, json['Mã thẻ']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'new_appointment') {
                        $('#span').text(json['Cấu hình xác nhận lịch hẹn']);
                        var form = ['name', 'full_name', 'gender', 'day_appointment', 'time_appointment', 'code_appointment',
                            'email', 'name_spa'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'day_appointment') {
                                tpl = tpl.replace(/{note}/g, json['Ngày hẹn']);
                            } else if (form[i] == 'time_appointment') {
                                tpl = tpl.replace(/{note}/g, json['Giờ hẹn']);
                            } else if (form[i] == 'code_appointment') {
                                tpl = tpl.replace(/{note}/g, json['Mã lịch hẹn']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            } else if (form[i] == 'name_spa') {
                                tpl = tpl.replace(/{note}/g, json['Tên spa']);
                            }

                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'cancel_appointment') {
                        $('#span').text(json['Cấu hình thông báo hủy lịch hẹn']);
                        var form = ['name', 'full_name', 'gender', 'code_appointment', 'email', 'name_spa'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'code_appointment') {
                                tpl = tpl.replace(/{note}/g, json['Mã lịch hẹn']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            } else if (form[i] == 'name_spa') {
                                tpl = tpl.replace(/{note}/g, json['Tên spa']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'paysuccess') {
                        $('#span').text(json['Cấu hình thông báo mua hàng thành công']);
                        var form = ['name', 'full_name', 'gender', 'name_spa', 'email', 'order_code'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'name_spa') {
                                tpl = tpl.replace(/{note}/g, json['Tên spa']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            } else if (form[i] == 'order_code') {
                                tpl = tpl.replace(/{note}/g, json['Mã đơn hàng']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'new_customer') {
                        $('#span').text(json['Cấu hình thông báo đăng kí khách hàng mới']);
                        var form = ['name', 'full_name', 'gender', 'name_spa', 'email'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'name_spa') {
                                tpl = tpl.replace(/{note}/g, json['Tên spa']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'service_card_over_number_used') {
                        $('#span').text(json['Cấu hình thông báo thẻ dịch vụ hết số lần sử dụng']);
                        var form = ['name', 'full_name', 'gender', 'card_code', 'email'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'card_code') {
                                tpl = tpl.replace(/{note}/g, json['Mã thẻ']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'service_card_expires') {
                        $('#span').text(json['Cấu hình thông báo thẻ dịch vụ hết hạn']);
                        var form = ['name', 'full_name', 'gender', 'code_card', 'email', 'time'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'name') {
                                tpl = tpl.replace(/{note}/g, json['Tên khách hàng']);
                            } else if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'code_card') {
                                tpl = tpl.replace(/{note}/g, json['Mã thẻ']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            } else if (form[i] == 'time') {
                                tpl = tpl.replace(/{note}/g, json['Thời gian']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'order_success') {
                        $('#span').text(json['Cấu hình thông báo đặt hàng thành công']);
                        var form = ['full_name', 'gender', 'email', 'time'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'email') {
                                tpl = tpl.replace(/{note}/g, json['Email']);
                            } else if (form[i] == 'time') {
                                tpl = tpl.replace(/{note}/g, json['Thời gian']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'active_warranty_card') {
                        $('#span').text(json['Cấu hình thông báo kích hoạt thẻ bảo hành thành công']);
                        var form = ['full_name', 'gender', 'time'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'time') {
                                tpl = tpl.replace(/{note}/g, json['Thời gian']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    } else if (res.item.key == 'is_remind_use') {
                        var form = ['full_name', 'gender', 'object_type', 'object_name', 'time'];
                        for (let i = 0; i < form.length; i++) {
                            var tpl = $('#tb-para-tpl').html();
                            tpl = tpl.replace(/{code}/g, '{' + form[i] + '}');
                            if (form[i] == 'full_name') {
                                tpl = tpl.replace(/{note}/g, json['Họ & tên']);
                            } else if (form[i] == 'gender') {
                                tpl = tpl.replace(/{note}/g, json['Giới tính']);
                            } else if (form[i] == 'object_type') {
                                tpl = tpl.replace(/{note}/g, json['Loại đối tượng']);
                            } else if (form[i] == 'object_name') {
                                tpl = tpl.replace(/{note}/g, json['Tên đối tượng']);
                            } else if (form[i] == 'time') {
                                tpl = tpl.replace(/{note}/g, json['Thời gian']);
                            }
                            $('#tb_para').append(tpl);
                        }
                    }


                    $('#time_sent').val(res.item.time_sent);
                    $('#value_time').val(res.item.value);
                    $('#value_day').val(res.item.value);
                    $("#time_sent").timepicker({
                        minuteStep: 1,
                        showMeridian: !1,
                        snapToStep: !0,
                    });
                    $('#content').summernote({
                        height: 250,
                        placeholder: json['Nhập nội dung'],
                        toolbar: [
                            ['style', ['bold', 'italic', 'underline']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                        ]

                    });
                    $('.note-btn').attr('title', '');
                    $("#content").summernote("code", res.item.content);
                });
            }
        });

    },
    append_para: function (e) {
        var text = $(e).val();
        $('#content').summernote('pasteHTML', text);

    },
    submit_content: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-content');
            form.validate({
                rules: {
                    title: {
                        required: true,
                    },
                    content: {
                        required: true
                    },
                    value_time: {
                        required: true,
                        number: true
                    },
                    value_day: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    title: {
                        required: json['Hãy nhập tiêu đề']
                    },
                    content: {
                        required: json['Hãy nhập nội dung']
                    },
                    value_time: {
                        required: json['Hãy nhập số giờ gửi trước'],
                        number: json['Số giờ gửi trước không hợp lệ']
                    },
                    value_day: {
                        required: json['Hãy nhập số ngày gửi trước'],
                        number: json['Số ngày gửi trước không hợp lệ']
                    }

                }
            });
            if (!form.valid()) {
                return false;
            }
            $.ajax({
                url: laroute.route('admin.email-auto.submit-setting-content'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    type: $('#type_content').val(),
                    id: $('#id_content').val(),
                    title: $('#title').val(),
                    content_email: $('#content').val(),
                    value_time: $('#value_time').val(),
                    value_day: $('#value_day').val(),
                    time_sent: $('#time_sent').val()
                },
                success: function (res) {
                    if (res.error_content == 1) {
                        $('.error_content').text(json['Nội dung không hợp lệ']);
                    } else {
                        $('.error_content').text('');
                    }
                    if (res.success == 1) {
                        swal(json["Chỉnh sửa thành công"], "", "success");
                        $('#setting-content').modal('hide');
                    }
                }
            });
        });
    },
    modal_template: function (id) {
        $.ajax({
            url: laroute.route('admin.email-auto.email-template'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (res) {
                $('.append_carousel').empty();
                $.map(res.template, function (val, k) {

                    var status = '';
                    var tpl = $('#template-tpl').html();
                    if (res.item.email_template_id == val.id) {
                        status = 'active';
                    } else {
                        status = '';
                        if (res.item.email_template_id == null) {
                            if (k == 0) {
                                status = 'active';
                            } else {
                                status = '';
                            }
                        }
                    }
                    tpl = tpl.replace(/{status}/g, status);
                    tpl = tpl.replace(/{image}/g, $('#link_hidden').val() + '/' + val.image);
                    tpl = tpl.replace(/{id}/g, val.id);
                    $('.append_carousel').append(tpl);
                });
                $('#setting-template').modal('show');
            }
        });

    },
    submit_template: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            var email_template_id = $('.active img').attr('alt');
            $.ajax({
                url: laroute.route('admin.email-auto.submit-template'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    email_template_id: email_template_id
                },
                success: function (res) {
                    if (res.success == 1) {
                        swal(json["Chọn template thành công"], "", "success");
                        $('#setting-template').modal('hide');
                    }
                }
            })
        });

    },
    send_mail_test: function () {

    }

}
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.email-auto.list')
});
var BootstrapSwitch = {
    init: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $("#is_actived").bootstrapSwitch({
                onText: json['Bật'],
                offText: json['Tắt']
            }).on('switchChange.bootstrapSwitch', function (ev) {
                // alert(ev.target.checked);
                if (ev.target.checked) {
                    var tpl = $('#type-tpl').html();
                    $('.setting_on').append(tpl);
                    $('#type').select2({
                        placeholder: json['Hãy chọn hình thức gửi email']
                    }).on('select2:select', function (ev) {
                        $('.append_pass').empty();
                        if (ev.params.data.id == 'gmail' || ev.params.data.id == 'clicksend') {
                            var tpl_p = $('#pass-tpl').html();
                            $('.append_pass').append(tpl_p);
                        } else {
                            $('.append_pass').empty();
                        }
                    });
                    $(this).val(1);
                    $.ajax({
                        url: laroute.route('admin.email-auto.config'),
                        dataType: 'JSON',
                        method: 'POST',
                        data: {
                            id: $('#id').val()
                        },
                        success: function (res) {
                            $('.setting_on').empty();
                            $('.append_pass').empty();
                            $('#config').modal('show');
                            $('#id').val(res.item.id);
                            // $('#is_actived').val(res.item.is_actived);
                            var tpl = $('#type-tpl').html();
                            $('.setting_on').append(tpl);
                            $('#type').select2({
                                placeholder: json['Hãy chọn hình thức gửi email']
                            }).on('select2:select', function (ev) {
                                $('.append_pass').empty();
                                if (ev.params.data.id == 'gmail' || ev.params.data.id == 'clicksend') {
                                    var tpl_p = $('#pass-tpl').html();
                                    $('.append_pass').append(tpl_p);
                                } else {
                                    $('.append_pass').empty();
                                }
                            });
                            $('#type').val(res.item.type).trigger('change');
                            if (res.item.type == 'gmail' || res.item.type == 'clicksend') {
                                var tpl_p = $('#pass-tpl').html();
                                $('.append_pass').append(tpl_p);
                            }
                            $('#email').val(res.item.email);
                            $('#password').val(res.item.password);
                            $('#pass_check').val(res.item.password);
                            $('#name').val(res.item.name_email);

                        }
                    })
                } else {
                    $(this).val(0);
                    $('.setting_on').empty();
                }
            });
        });
    }
};
jQuery(document).ready(function () {
    BootstrapSwitch.init()
});
