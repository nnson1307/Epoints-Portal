var member_level = {
        remove: function (obj, id) {

            $(obj).closest('tr').addClass('m-table__row--danger');
            $.getJSON(laroute.route('translate'), function (json) {
                swal({
                    title: json['Thông báo'],
                    text: json["Bạn có muốn xóa không?"],
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: json['Xóa'],
                    cancelButtonText: json['Hủy'],
                    onClose: function () {
                        $(obj).closest('tr').removeClass('m-table__row--danger');
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.post(laroute.route('customer-group.remove', {id: id}), function () {
                            swal(
                                json['Xóa thành công.'],
                                '',
                                'success'
                            );
                            $('#autotable').PioTable('refresh');
                        });
                    }
                });
            });
        },
        changeStatus: function (obj, id, action) {
            $.post(laroute.route('customer-source.change-status'), {id: id, action: action}, function (data) {
                $('#autotable').PioTable('refresh');
            }, 'JSON');
        },
        add: function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $('#form').validate({
                    rules: {
                        name: {
                            required: true
                        },
                        point:{
                            required:true,
                            maxlength:11
                        }
                    },
                    messages: {
                        name: {
                            required: json['Hãy nhập cấp độ']
                        },
                        point:{
                            required:json['Hãy nhập số điểm quy đổi'],
                            maxlength:json['Số điểm không hợp lệ vui lòng kiểm tra lại']
                        }
                    },
                    submitHandler: function () {
                        $.ajax({
                            type: 'post',
                            url: laroute.route('admin.member-level.submitadd'),
                            data: {
                                name: $('#name').val(),
                                point:$('#point').val(),
                                is_actived: $('#is_actived').val(),

                            },
                            dataType: "JSON",
                            success: function (response) {
                                swal(json["Thêm cập độ thành công"], "", "success");
                                $('#autotable').PioTable('refresh');
                            },
                            complete: function () {
                                $('#name').val('');
                                $('#point').val('');
                            }
                        })
                    }
                });
            });
        },
        addClose: function () {
            $.getJSON(laroute.route('translate'), function (json) {
                $('#form').validate({
                    rules: {
                        name: {
                            required: true
                        },
                        point:{
                            required:true,
                            maxlength:11
                        }

                    },
                    messages: {
                        name: {
                            required: json['Hãy nhập cấp độ']
                        },
                        point:{
                            required:json['Hãy nhập số điểm quy đổi'],
                            maxlength:json['Số điểm không hợp lệ vui lòng kiểm tra lại']
                        }
                    },
                    submitHandler: function () {
                        $.ajax({
                            type: 'post',
                            url: laroute.route('admin.member-level.submitadd'),
                            data: {
                                name: $('#name').val(),
                                point:$('#point').val(),
                                is_actived: $('#is_actived').val(),
                            },
                            dataType: "JSON",
                            success: function (response) {
                                swal(json["Thêm cập độ thành công"], "", "success");
                                $("#add1").modal("hide");
                                $('#autotable').PioTable('refresh');
                            },
                            complete:function () {
                                $('#name').val('');
                                $('#point').val('');
                            }
                        })
                    }
                });
            });
        },
        edit: function (id) {
            $.ajax({
                url: laroute.route('admin.shift.edit'),
                data: {
                    shiftId: id
                },
                method: "POST",
                dataType: 'JSON',
                success: function (data) {
                    $('#modalEdit').modal('show');
                    $('#shift-id-hidden').val(data['shift_id']);
                    $('#shift-code-edit').val(data['shift_code']);
                    $('#start-time-edit').val(data['start_time']);
                    $('#end-time-edit').val(data['end_time']);
                    $('#is-actived-edit').val(data['is_actived']);
                }
            })
        },
        submitEdit: function () {
            let id = $('#shift-id-hidden');
            let shiftCode = $('#shift-code-edit');
            let startTime = $('#start-time-edit');
            let endTimes = $('#end-time-edit');
            let isActive = $('#is-actived-edit');
            if (shiftCode.val() != "" && shiftCode.val().length <= 10) {
                if (startTime.val() != "" && endTimes.val() != "") {
                    if (startTime.val() > endTimes.val()) {
                        $(".error-end-time").css("color", "red");
                        $.getJSON(laroute.route('translate'), function (json) {
                        $('.error-end-time').text(json['Vui lòng nhập lại thời gian']);
                        });
                        return false;
                    }
                }
                $.ajax({
                    url: laroute.route('admin.shift.submit-edit'),
                    data: {
                        id: id.val(),
                        shiftCode: shiftCode.val(),
                        startTime: startTime.val(),
                        endTimes: endTimes.val(),
                        isActived: isActive.val()
                    },
                    method: "POST",
                    dataType: 'JSON',
                    success: function (data) {
                        $.getJSON(laroute.route('translate'), function (json) {
                            if (data.status == '') {
                                $('.error-shift-code').text('');
                                $('#modalEdit').modal('hide');
                                swal(
                                    json['Sửa ca thành công'],
                                    '',
                                    'success'
                                );
                                $('#autotable').PioTable('refresh');
                                clear();
                            } else {
                                $(".error-shift-code").css("color", "red");
                                $('.error-shift-code').text(data.status);
                            }
                        });
                    }
                });
            } else {
                $(".error-shift-code").css("color", "red");
                $.getJSON(laroute.route('translate'), function (json) {
                $('.error-shift-code').text(json['Vui lòng nhập lại mã ca']);
                });
            }
        }

        ,
        clear: function () {
            clear();
        }

    }
;

function clear() {
    $('#shift-code').val('');
    $('#start-time').val('');
    $('#end-time').val('');
    $('#is-actived').val(1);
}

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.member-level.list')
});


