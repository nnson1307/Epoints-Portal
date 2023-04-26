$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#day').select2({
            placeholder: json['Ngày'],
            allowClear: true
        });
        $('#month').select2({
            placeholder: json['Tháng'],
            allowClear: true
        });
        $('#year').select2({
            placeholder: json['Năm'],
            allowClear: true
        });
        $("#created_at").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY',
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
            }
        });

    });
});
 
var log = {
    search:function () {
        $('.btn-search').trigger('click');
    },

    popupAnswer: function (id) {
        $.ajax({
            url: laroute.route('admin.log.question-customer.popup-answer-question'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                feedback_question_id: id
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-answer').modal('show');
            }
        });
    },
    popupEditAnswer: function (id) {
        $.ajax({
            url: laroute.route('admin.log.question-customer.popup-edit-answer'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                feedback_answer_id: id
            },
            success: function (res) {
                $('#my-modal').html(res.html);
                $('#modal-answer').modal('show');
            }
        });
    },
    saveAnswer: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-answer');
            form.validate({
                rules: {
                    content: {
                        required: true
                    },
                },
                messages: {
                    content: {
                        required: json['Vui lòng nhập câu trả lời.'],
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('admin.log.question-customer.save-answer'),
                data: {
                    feedback_question_id: $('#feedback_question_id').val(),
                    content: $('#content').val(),
                },
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    if (response.error == false) {
                        swal(response.message, "", "success");
                        window.location = laroute.route('admin.log.question-customer');
                    } else {
                        swal(response.message, "", "error")
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm thất bại'], mess_error, "error");
                }
            });

        });
    },
    removeAnswer: function (id) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: json['Thông báo'],
                text: json["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('admin.log.question-customer.remove-answer'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            feedback_answer_id: id
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                window.location = laroute.route('admin.log.question-customer');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
    updateAnswer: function () {
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form-answer');
            form.validate({
                rules: {
                    content: {
                        required: true
                    },
                },
                messages: {
                    content: {
                        required: json['Vui lòng nhập câu trả lời.'],
                    },
                },
            });

            if (!form.valid()) {
                return false;
            }

            $.ajax({
                url: laroute.route('admin.log.question-customer.update-answer'),
                data: {
                    feedback_answer_id: $('#feedback_answer_id').val(),
                    content: $('#content').val(),
                },
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    if (response.error == false) {
                        swal(response.message, "", "success");
                        window.location = laroute.route('admin.log.question-customer');
                    } else {
                        swal(response.message, "", "error")
                    }
                },
                error: function (res) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal(json['Thêm thất bại'], mess_error, "error");
                }
            });

        });
    }
}

$('#autotable').PioTable({
    baseUrl: laroute.route('admin.log.question-customer.list')
});