$(document).ready(function () {
    $('#birthday').datepicker({
        format: 'dd/mm/yyyy',
        language: 'vi',
    });
    $('#branch_id').select2({
        placeholder: json['Chi nhánh']
    });
    $('#campaign_id').select2({
        placeholder: 'Chọn chiến dịch'
    }).on('select2:select', function (ev) {
        $('.append_type').empty();
        $.ajax({
            url: laroute.route('admin.email.select'),
            dataType: 'JSON',
            method: 'POST',
            data: {
                id: ev.params.data.id
            },
            success: function (res) {
                var tpl = $('#type-tpl').html();
                if (res.item.value != null) {
                    tpl = tpl.replace(/{value}/g, res.item.value);
                } else {
                    tpl = tpl.replace(/{value}/g, '');
                }
                if (res.item.status == 'draft') {
                    tpl = tpl.replace(/{class}/g, 'm-badge m-badge--warning m-badge--wide');
                    tpl = tpl.replace(/{status}/g, json['Lưu nháp']);
                } else {
                    tpl = tpl.replace(/{class}/g, 'm-badge m-badge--success m-badge--wide');
                    tpl = tpl.replace(/{status}/g, json['Đã sử dụng']);
                }
                $('.append_type').append(tpl);

            }
        })
    });
    $('.check_all').click(function () {
        if ($('.check_all').is(":checked")) {
            $('input[name="check"]').prop("checked", true);
        } else {
            $('input[name="check"]').prop("checked", false);
        }
    });
});
$('.m_selectpicker').selectpicker();
var send_mail = {
    modal_customer: function () {
        $('#add-customer').modal('show');
        $('#name').val('');
        $('#birthday').val('');
        $('#gender').val('');
        $('#branch_id').val('');
        $('.customer_list_body').empty();
        $('.error_append').text('');
    },
    search: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('.customer_list_body').empty();
            var data = $('#name').val();
            var birthday = $('#birthday').val();
            var gender = $('#gender').val();
            var branch = $('#branch_id').val();
            $.ajax({
                url: laroute.route('admin.email.search-customer'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    data: data,
                    birthday: birthday,
                    gender: gender,
                    branch: branch
                },
                success: function (res) {
                    $.map(res.arr_data, function (a) {
                        var stts = $('.customer_list_body tr').length;
                        var tpl = $('#customer-list-tpl').html();
                        tpl = tpl.replace(/{stt}/g, stts + 1);
                        tpl = tpl.replace(/{name}/g, a.full_name);
                        tpl = tpl.replace(/{customer_id}/g, a.customer_id);
                        tpl = tpl.replace(/{birthday}/g, a.birthday);
                        if (a.gender == 'male') {
                            tpl = tpl.replace(/{gender}/g, json['Nam']);
                        }
                        else if (a.gender == 'female') {
                            tpl = tpl.replace(/{gender}/g, json['Nữ']);
                        }
                        else {
                            tpl = tpl.replace(/{gender}/g, json['Khác']);
                        }
                        if (a.email != null) {
                            tpl = tpl.replace(/{email}/g, a.email);
                        } else {
                            tpl = tpl.replace(/{email}/g, '');
                        }

                        tpl = tpl.replace(/{branch_name}/g, a.branch_name);
                        $('.customer_list_body').append(tpl);
                    });
                }
            });
        });
    },
    click_append: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var append = [];
            $.each($('.customer_list tr input[name="check"]:checked').parentsUntil("tbody"), function () {
                var $tds = $(this).find("input[name='customer_id']");
                $.each($tds, function () {
                    append.push($(this).val());
                });
            });
            if (append != '') {
                $.ajax({
                    url: laroute.route('admin.email.append-table'),
                    dataTye: 'JSON',
                    method: 'POST',
                    data: {
                        list: append
                    },
                    success: function (res) {
                        $.map(res.data_list, function (a) {
                            var stts = $('.table_list_body tr').length;
                            var tpl = $('#list-send-tpl').html();
                            tpl = tpl.replace(/{stt}/g, stts + 1);
                            tpl = tpl.replace(/{name}/g, a.customer_name);
                            tpl = tpl.replace(/{birthday}/g, a.birthday);
                            if (a.gender == 'male') {
                                tpl = tpl.replace(/{gender_view}/g, json['Nam']);
                                tpl = tpl.replace(/{gender}/g, a.gender);
                            }
                            else if (a.gender == 'female') {
                                tpl = tpl.replace(/{gender_view}/g, json['Nữ']);
                                tpl = tpl.replace(/{gender}/g, a.gender);
                            }
                            else {
                                tpl = tpl.replace(/{gender_view}/g, json['Khác']);
                                tpl = tpl.replace(/{gender}/g, a.gender);
                            }
                            if (a.email != null) {
                                tpl = tpl.replace(/{email}/g, a.email);
                            } else {
                                tpl = tpl.replace(/{email}/g, '');
                            }

                            $('.table_list_body').append(tpl);
                        });
                        $('#add-customer').modal('hide');
                    }
                });
            } else {
                $('.error_append').text(json['Vui lòng chọn khách hàng']);
            }
        });
    },
    remove:function(e){
        $(e).closest('.send').remove();
    },
    send: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            $('#form-type').validate({
                rules: {
                    campaign_id: {
                        required: true,
                    },
                },
                messages: {
                    campaign_id: {
                        required: json['Hãy chọn chiến dịch']
                    },
                },
                submitHandler: function () {
                    var send = [];
                    $.each($('.table_list').find(".send"), function () {
                        var $tds = $(this).find("input");
                        $.each($tds, function () {
                            send.push($(this).val());
                        });
                    });
                    console.log(send);
                    // $.ajax({
                    //    url:laroute.route('admin.email.submit-send-mail'),
                    //    method:'post',
                    //    dataType:'JSON',
                    //    data:{
                    //
                    //    }
                    // });
                }
            });
        });
    },
};