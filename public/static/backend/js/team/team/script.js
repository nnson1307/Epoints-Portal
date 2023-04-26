var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var list = {
    _init: function () {
        $('#autotable').PioTable({
            baseUrl: laroute.route('team.team.list')
        });
    },
    remove:function (teamId) {
        $.getJSON(laroute.route('translate'), function (json) {
            swal({
                title: jsonLang['Thông báo'],
                text: jsonLang["Bạn có muốn xóa không?"],
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: json['Xóa'],
                cancelButtonText: json['Hủy'],
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: laroute.route('team.team.destroy'),
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            team_id: teamId
                        },
                        success: function (res) {
                            if (res.error == false) {
                                swal.fire(res.message, "", "success");
                                $('#autotable').PioTable('refresh');
                            } else {
                                swal.fire(res.message, '', "error");
                            }
                        }
                    });
                }
            });
        });
    },
};

var view = {
    _init: function () {
        $('#staff_title_id').select2({
            placeholder: jsonLang['Chọn chức vụ']
        });

        $('#department_id').select2({
            placeholder: jsonLang['Chọn thông tin cha']
        });

        $('#staff_id').select2({
            placeholder: jsonLang['Chọn người quản lý']
        });
    },
    changeTitle: function (obj) {
        $.ajax({
            url: laroute.route('team.team.change-title'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                staff_title_id: $(obj).val()
            },
            success: function (res) {
                $('#staff_id').empty();
                
                $.map(res.optionStaff, function (v) {
                    $('#staff_id').append('<option value="'+ v.staff_id+'">'+ v.full_name+'</option>');
                })
            }
        });
    }
};

var create = {
    save: function () {
        var form = $('#form-register');

        form.validate({
            rules: {
                team_name: {
                    required: true,
                    maxlength: 190
                },
                staff_title_id: {
                    required: true
                },
                department_id: {
                    required: true
                },
                staff_id: {
                    required: true
                },

            },
            messages: {
                team_name: {
                    required: jsonLang['Hãy nhập tên nhóm'],
                    maxlength: jsonLang['Tên nhóm tối đa 190 kí tự']
                },
                staff_title_id: {
                    required: jsonLang['Hãy chọn chức vụ']
                },
                department_id: {
                    required: jsonLang['Hãy chọn thông tin nhánh cha']
                },
                staff_id: {
                    required: jsonLang['Hãy chọn người quản lý']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        $.ajax({
            url: laroute.route('team.team.store'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                team_name: $('#team_name').val(),
                staff_title_id: $('#staff_title_id').val(),
                department_id: $('#department_id').val(),
                staff_id: $('#staff_id').val()
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.href = laroute.route('team.team');
                        }
                        if (result.value == true) {
                            window.location.href = laroute.route('team.team');
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(jsonLang['Thêm thất bại'], mess_error, "error");
            }
        });
    }
};

var edit = {
    save: function (teamId) {
        var form = $('#form-edit');

        form.validate({
            rules: {
                team_name: {
                    required: true,
                    maxlength: 190
                },
                staff_title_id: {
                    required: true
                },
                department_id: {
                    required: true
                },
                staff_id: {
                    required: true
                },

            },
            messages: {
                team_name: {
                    required: jsonLang['Hãy nhập tên nhóm'],
                    maxlength: jsonLang['Tên nhóm tối đa 190 kí tự']
                },
                staff_title_id: {
                    required: jsonLang['Hãy chọn chức vụ']
                },
                department_id: {
                    required: jsonLang['Hãy chọn thông tin nhánh cha']
                },
                staff_id: {
                    required: jsonLang['Hãy chọn người quản lý']
                }
            },
        });

        if (!form.valid()) {
            return false;
        }

        var is_actived = 0;
        if ($('#is_actived').is(':checked')) {
            is_actived = 1;
        }

        $.ajax({
            url: laroute.route('team.team.update'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                team_id: teamId,
                team_name: $('#team_name').val(),
                staff_title_id: $('#staff_title_id').val(),
                department_id: $('#department_id').val(),
                staff_id: $('#staff_id').val(),
                is_actived: is_actived
            },
            success: function (res) {
                if (res.error == false) {
                    swal(res.message, "", "success").then(function (result) {
                        if (result.dismiss == 'esc' || result.dismiss == 'backdrop') {
                            window.location.href = laroute.route('team.team');
                        }
                        if (result.value == true) {
                            window.location.href = laroute.route('team.team');
                        }
                    });
                } else {
                    swal(res.message, '', "error");
                }
            },
            error: function (res) {
                var mess_error = '';
                $.map(res.responseJSON.errors, function (a) {
                    mess_error = mess_error.concat(a + '<br/>');
                });
                swal(jsonLang['Chỉnh sửa thất bại'], mess_error, "error");
            }
        });
    }  
};