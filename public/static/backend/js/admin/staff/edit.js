var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

$(document).ready(function () {
    $('#day').select2({
        placeholder: jsonLang['Ngày'],
        allowClear: true
    });

    $('#month').select2({
        placeholder: jsonLang['Tháng'],
        allowClear: true
    });

    $('#year').select2({
        placeholder: jsonLang['Năm'],
        allowClear: true
    });

    $('#staff_type').select2();

    $('#staff_title_id').select2({
        placeholder: jsonLang['Hãy chọn chức vụ']
    });

    $('#branch_id').select2({
        placeholder: jsonLang['Hãy chọn chi nhánh']
    });

    $('#department_id').select2({
        placeholder: jsonLang['Hãy chọn phòng ban']
    });

    $('#team_id').select2({
        placeholder: jsonLang['Hãy chọn nhóm']
    });

    $('#is_admin').select2();

    new AutoNumeric.multiple('#salary, #subsidize, #commission_rate', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });

    $('.btn_edit').click(function () {
        var form = $('#form-edit');
        form.validate({
            rules: {
                full_name: {
                    required: true
                },
                phone1: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 15
                },
                department_id: {
                    required: true
                },
                branch_id: {
                    required: true
                },
                staff_title_id: {
                    required: true
                },
                address: {
                    required: true
                },
                user_name: {
                    required: true
                },
                staff_salary_unit_code: {
                    required: true
                },
                payment_type: {
                    required: true
                },
                password: {
                    minlength: 6
                },
                repass: {
                    minlength: 6,
                    equalTo: "#password"
                },

                bank_name: {
                    maxlength: 190
                },
                bank_branch_name: {
                    maxlength: 190
                }
            },
            messages: {
                full_name: {
                    required: jsonLang["Hãy nhập họ tên"]
                },
                phone1: {
                    required: jsonLang['Hãy nhập số điện thoại'],
                    number: jsonLang['Số điện thoại không hợp lệ'],
                    minlength: jsonLang['Tối thiểu 10 số'],
                    maxlength: jsonLang['Tối đa 15 số']
                },
                department_id: {
                    required: jsonLang['Hãy chọn phòng ban']
                },
                branch_id: {
                    required: jsonLang['Hãy chọn chi nhánh']
                },
                staff_title_id: {
                    required: jsonLang['Hãy chọn chức vụ']
                },
                address: {
                    required: jsonLang['Hãy chọn địa chỉ']
                },
                user_name: {
                    required: jsonLang['Hãy nhập tên tài khoản']
                },
                staff_salary_unit_code: {
                    required: jsonLang['Hãy chọn đơn vị tiền tệ']
                },
                payment_type: {
                    required: jsonLang['Hãy chọn hình thức trả lương']
                },
                password: {
                    minlength: jsonLang['Tối thiểu 6 kí tự']
                },
                repass: {
                    minlength: jsonLang['Tối thiểu 6 kí tự'],
                    equalTo: jsonLang["Nhập lại mật khẩu không đúng"]
                },

                bank_name: {
                    maxlength: jsonLang['Tối đa 190 kí tự']
                },
                bank_branch_name: {
                    maxlength: jsonLang['Tối đa 190 kí tự']
                }
            },
        });
        // submitHandler: function () {
        //     var staff_id = $('#staff_id').val();
        //     var full_name = $('#full_name').val();
        //     var phone = $('#phone1').val();
        //     var gender = $('input[name="gender"]:checked').val();
        //     var branch_id = $('#branch_id').val();
        //     var staff_title_id = $('#staff_title_id').val();
        //     var department_id = $('#department_id').val();
        //     var address = $('#address').val();
        //     var email = $('#email').val();
        //     var day = $('#day').val();
        //     var month = $('#month').val();
        //     var year = $('#year').val();
        //     var user_name = $('#user_name').val();
        //     var is_admin = $('#is_admin').val();
        //     var password = $('#password').val();
        //     var staff_avatar = $('#staff_avatar').val();
        //     var staff_avatar_upload = $('#staff_avatar_upload').val();
        //     var is_actived = 0;
        //     var roleGroup = $('#role-group-id').val();
        //
        //     if ($('#is_actived').is(':checked')) {
        //         is_actived = 1;
        //     }

        if (!form.valid()) {
            return false;
        }
        $('.error_bank_number').text('');

        var staff_id = $('#staff_id').val();
        var full_name = $('#full_name').val();
        var phone = $('#phone1').val();
        var gender = $('input[name="gender"]:checked').val();
        var branch_id = $('#branch_id').val();
        var staff_title_id = $('#staff_title_id').val();
        var department_id = $('#department_id').val();
        var address = $('#address').val();
        var email = $('#email').val();
        var day = $('#day').val();
        var month = $('#month').val();
        var year = $('#year').val();
        var user_name = $('#user_name').val();
        var is_admin = $('#is_admin').val();
        var password = $('#password').val();
        var staff_avatar = $('#staff_avatar').val();
        var staff_avatar_upload = $('#staff_avatar_upload').val();
        var is_actived = 0;
        var roleGroup = $('#role-group-id').val();
        var staffType = $('#staff_type').val();

        if ($('#is_actived').is(':checked')) {
            is_actived = 1;
        }

        if (email != '') {
            if (!isValidEmailAddress(email)) {
                $('.error_email').text('Email không hợp lệ');
                return false;
            } else {
                $('.error_email').text('');
                $.ajax({
                    url: laroute.route('admin.staff.submit-edit'),
                    dataType: 'JSON',
                    data: {
                        staff_id: staff_id,
                        full_name: full_name,
                        phone: phone,
                        gender: gender,
                        branch_id: branch_id,
                        staff_title_id: staff_title_id,
                        department_id: department_id,
                        staff_type: staffType,
                        address: address,
                        email: email,
                        day: day,
                        month: month,
                        year: year,
                        user_name: user_name,
                        is_admin: is_admin,
                        password: password,
                        staff_avatar: staff_avatar,
                        staff_avatar_upload: staff_avatar_upload,
                        is_actived: is_actived,
                        roleGroup: roleGroup,
                        salary: $('#salary').val(),
                        subsidize: $('#subsidize').val(),
                        commission_rate: $('#commission_rate').val(),
                        bank_number: $('#bank_number').val(),
                        bank_name: $('#bank_name').val(),
                        bank_branch_name: $('#bank_branch_name').val(),
                        team_id: $('#team_id').val()
                    },
                    method: 'POST',
                    success: function (res) {

                        if (res.error_birthday == 1) {
                            $('.error_birthday').text(res.message);
                        } else {
                            $('.error_birthday').text('');
                        }
                        if (res.error_user == 1) {
                            $('.error_user').text(res.message);
                        } else {
                            $('.error_user').text('');
                        }
                        if (res.error_bank_number == 1) {
                            $('.error_bank_number').text(res.message);
                        }
                        if (res.success == 1) {
                            swal({
                                title: res.message,
                                text: 'Redirecting...',
                                type: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                            })
                                .then(() => {
                                    window.location.reload();
                                });
                        }
                    }
                });
            }
        } else {
            $.ajax({
                url: laroute.route('admin.staff.submit-edit'),
                dataType: 'JSON',
                method: 'POST',
                data: {
                    staff_id: staff_id,
                    full_name: full_name,
                    phone: phone,
                    gender: gender,
                    branch_id: branch_id,
                    staff_title_id: staff_title_id,
                    department_id: department_id,
                    staff_type: staffType,
                    address: address,
                    email: email,
                    day: day,
                    month: month,
                    year: year,
                    user_name: user_name,
                    is_admin: is_admin,
                    password: password,
                    staff_avatar: staff_avatar,
                    staff_avatar_upload: staff_avatar_upload,
                    is_actived: is_actived,
                    roleGroup: roleGroup,
                    salary: $('#salary').val(),
                    subsidize: $('#subsidize').val(),
                    commission_rate: $('#commission_rate').val(),
                    bank_number: $('#bank_number').val(),
                    bank_name: $('#bank_name').val(),
                    bank_branch_name: $('#bank_branch_name').val(),
                    team_id: $('#team_id').val()
                },
                success: function (res) {
                    console.log(res);
                    if (res.error_birthday == 1) {
                        $('.error_birthday').text(res.message);
                    } else {
                        $('.error_birthday').text('');
                    }
                    if (res.error_user == 1) {
                        $('.error_user').text(res.message);
                    } else {
                        $('.error_user').text('');
                    }
                    if (res.error_bank_number == 1) {
                        $('.error_bank_number').text(res.message);
                    }
                    if (res.success == 1) {
                        swal({
                            title: res.message,
                            text: 'Redirecting...',
                            type: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                        })
                            .then(() => {
                                window.location.reload();
                            });
                    }


                }
            });
        }


    });

    $('.btn_edit_new').click(function () {
        $('#form-edit').validate({
            rules: {
                full_name: {
                    required: true
                },
                phone1: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 15
                },
                department_id: {
                    required: true
                },
                branch_id: {
                    required: true
                },
                staff_title_id: {
                    required: true
                },
                address: {
                    required: true
                },
                user_name: {
                    required: true
                },
                password: {
                    minlength: 6
                },
                repass: {
                    minlength: 6,
                    equalTo: "#password"
                },
                bank_number: {
                    maxlength: 190
                },
                bank_name: {
                    maxlength: 190
                },
                bank_branch_name: {
                    maxlength: 190
                }
            },
            messages: {
                full_name: {
                    required: "Hãy nhập họ tên"
                },
                phone1: {
                    required: 'Hãy nhập số điện thoại',
                    number: 'Số điện thoại không hợp lệ',
                    minlength: 'Tối thiểu 10 số',
                    maxlength: 'Tối đa 15 số'
                },
                department_id: {
                    required: 'Hãy chọn phòng ban'
                },
                branch_id: {
                    required: 'Hãy chọn chi nhánh'
                },
                staff_title_id: {
                    required: 'Hãy chọn chức vụ'
                },
                address: {
                    required: 'Hãy chọn địa chỉ'
                },
                user_name: {
                    required: 'Hãy nhập tên tài khoản'
                },
                password: {

                    minlength: 'Tối thiểu 6 kí tự'
                },
                repass: {
                    minlength: 'Tối thiểu 6 kí tự',
                    equalTo: "Nhập lại mật khẩu không đúng"
                },
                bank_number: {
                    maxlength: jsonLang['Tối đa 190 kí tự']
                },
                bank_name: {
                    maxlength: jsonLang['Tối đa 190 kí tự']
                },
                bank_branch_name: {
                    maxlength: jsonLang['Tối đa 190 kí tự']
                }
            },
            submitHandler: function () {
                var staff_id = $('#staff_id').val();
                var full_name = $('#full_name').val();
                var phone = $('#phone1').val();
                var gender = $('input[name="gender"]:checked').val();
                var branch_id = $('#branch_id').val();
                var staff_title_id = $('#staff_title_id').val();
                var department_id = $('#department_id').val();
                var staffType = $('#staff_type').val();

                var address = $('#address').val();
                var email = $('#email').val();
                var day = $('#day').val();
                var month = $('#month').val();
                var year = $('#year').val();
                var user_name = $('#user_name').val();
                var is_admin = $('#is_admin').val();
                var password = $('#password').val();
                var staff_avatar = $('#staff_avatar').val();
                var staff_avatar_upload = $('#staff_avatar_upload').val();
                var is_inactive = 0;
                if ($('#is_inactive').is(':checked')) {
                    is_inactive = 1;
                }
                if (email != '') {
                    if (!isValidEmailAddress(email)) {
                        $('.error_email').text('Email không hợp lệ');
                        return false;
                    } else {
                        $('.error_email').text('');
                        $.ajax({
                            url: laroute.route('admin.staff.submit-edit'),
                            dataType: 'JSON',
                            data: {
                                staff_id: staff_id,
                                full_name: full_name,
                                phone: phone,
                                gender: gender,
                                branch_id: branch_id,
                                staff_title_id: staff_title_id,
                                department_id: department_id,
                                staff_type: staffType,
                                address: address,
                                email: email,
                                day: day,
                                month: month,
                                year: year,
                                user_name: user_name,
                                is_admin: is_admin,
                                password: password,
                                staff_avatar: staff_avatar,
                                staff_avatar_upload: staff_avatar_upload,
                                is_inactive: is_inactive,
                                bank_number: $('#bank_number').val(),
                                bank_name: $('#bank_name').val(),
                                bank_branch_name: $('#bank_branch_name').val(),
                                team_id: $('#team_id').val()
                            },
                            method: 'POST',
                            success: function (res) {
                                if (res.error_birthday == 1) {
                                    $('.error_birthday').text('Ngày sinh không hợp lệ');
                                } else {
                                    $('.error_birthday').text('');
                                }
                                if (res.error_user == 1) {
                                    $('.error_user').text('Tài khoản đã tồn tại');
                                } else {
                                    $('.error_user').text('');
                                }
                                if (res.error_bank_number == 1) {
                                    $('.error_bank_number').text(res.message);
                                }
                                if (res.success == 1) {
                                    swal("Chỉnh sửa nhân viên thành công", "", "success");
                                    window.location.reload();
                                }
                            }
                        });
                    }
                } else {
                    $.ajax({
                        url: laroute.route('admin.staff.submit-edit'),
                        dataType: 'JSON',
                        data: {
                            staff_id: staff_id,
                            full_name: full_name,
                            phone: phone,
                            gender: gender,
                            branch_id: branch_id,
                            staff_title_id: staff_title_id,
                            department_id: department_id,
                            staff_type: staffType,
                            address: address,
                            email: email,
                            day: day,
                            month: month,
                            year: year,
                            user_name: user_name,
                            is_admin: is_admin,
                            password: password,
                            staff_avatar: staff_avatar,
                            staff_avatar_upload: staff_avatar_upload,
                            is_inactive: is_inactive,
                            bank_number: $('#bank_number').val(),
                            bank_name: $('#bank_name').val(),
                            bank_branch_name: $('#bank_branch_name').val(),
                            team_id: $('#team_id').val()
                        },
                        method: 'POST',
                        success: function (res) {
                            if (res.error_birthday == 1) {
                                $('.error_birthday').text('Ngày sinh không hợp lệ');
                            } else {
                                $('.error_birthday').text('');
                            }
                            if (res.error_user == 1) {
                                $('.error_user').text('Tài khoản đã tồn tại');
                            } else {
                                $('.error_user').text('');
                            }
                            if (res.error_bank_number == 1) {
                                $('.error_bank_number').text(res.message);
                            }
                            if (res.success == 1) {
                                swal("Chỉnh sửa nhân viên thành công", "", "success");
                                window.location.reload();
                            }
                        }
                    });
                }

            }
        });
    });
});

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
            url: laroute.route("admin.upload-image"),
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == 0) {
                    $('#staff_avatar_upload').val(res.file);
                }

            }
        });
    }
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}

$('.js-example-data-ajax').select2({
    placeholder: "Chọn nhóm quyền",
});

var staff = {
    changeDepartment: function () {
        $.ajax({
            url: laroute.route('admin.staff.change-department'),
            method: 'POST',
            dataType: 'JSON',
            data: {
                department_id: $('#department_id').val()
            },
            success: function (res) {
                $('#team_id').empty();
                $('#team_id').append('<option></option>');

                $.each(res.optionTeam, function (k, v) {
                    $('#team_id').append('<option value="' + v.team_id + '">' + v.team_name + '</option>');
                });
            }
        });
    }
};

var staffTitle = {
    add: function (parameter) {
        var staffTitleName = $('#modalAdd #staff_title_name');
        var staffTitleDescription = $('#modalAdd #staff_title_description');
        var errorStaffTitleName = $('#modalAdd .error-staff_title_name');

        if (staffTitleName.val() == '') {
            errorStaffTitleName.text(jsonLang['Vui lòng nhập tên chức vụ']);
        } else {
            errorStaffTitleName.text('');
            $.ajax({
                url: laroute.route('admin.staff-title.submitadd'),
                method: "POST",
                data: {
                    staffTitleName: staffTitleName.val(),
                    staffTitleDescription: staffTitleDescription.val(),
                },
                success: function (data) {
                    if (data.status == 1) {
                        if (parameter == 0) {
                            $('#modalAdd').modal('hide');
                        }
                        swal(
                            jsonLang['Thêm chức vụ thành công'],
                            '',
                            'success'
                        );
                        staffTitleName.val('');
                        staffTitleDescription.val('');
                        $('#staff_title_id > option').remove();
                        $('#staff_title_id').append('<option></option>');
                        $.each(data.optionStaffTitle, function (index, element) {
                            $('#staff_title_id').append('<option value="' + index + '">' + element + '</option>')
                        });
                        $('#autotable').PioTable('refresh');
                    } else {
                        errorStaffTitleName.text(jsonLang['Chức vụ đã tồn tại']);
                    }
                }
            });
        }

    },
};
var Department = {
    add: function () {
        $(".department-name").css("color", "red");
        let departmentName = $('#department_name');
        let check = 0;

        if ($('#is_inactive').is(':checked')) {
            check = 1;
        }
        if (departmentName.val() != "") {
            $.ajax({
                url: laroute.route('admin.department.add'),
                data: {
                    departmentName: departmentName.val(),
                    isInActive: check
                },
                method: "POST",
                dataType: "JSON",
                success: function (data) {

                    if (data.status == 1) {
                        swal(
                            jsonLang['Thêm phòng ban thành công'],
                            '',
                            'success'
                        );
                        clearModalAdd();
                        $(".department-name").text('');
                        $('#autotable').PioTable('refresh');

                        $('#department_id > option').remove();
                        $('#department_id').append('<option></option>');
                        $.each(data.optionDepartment, function (index, element) {
                            $('#department_id').append('<option value="' + index + '">' + element + '</option>')
                        });
                    }
                    if (data.status == 0) {
                        $(".department-name").text(jsonLang['Phòng ban đã tồn tại']);
                    }
                }
            });

        } else {
            $('.department-name').text(jsonLang['Vui lòng nhập tên phòng ban']);
        }

    },
    addClose: function () {
        $(".department-name").css("color", "red");
        let departmentName = $('#department_name');
        let check = 0;

        if ($('#is_inactive').is(':checked')) {
            check = 1;
        }
        if (departmentName.val() != "") {
            $.ajax({
                url: laroute.route('admin.department.add'),
                data: {
                    departmentName: departmentName.val(),
                    isInActive: check
                },
                method: "POST",
                dataType: "JSON",
                success: function (data) {
                    if (data.status == 1) {
                        $('#autotable').PioTable('refresh');
                        swal(
                            jsonLang['Thêm phòng ban thành công'],
                            '',
                            'success'
                        );
                        $('#modalAddPartment').modal('hide');
                        clearModalAdd();
                        $("#department_name").text('');

                        $('#department_id > option').remove();
                        $('#department_id').append('<option></option>');
                        $.each(data.optionDepartment, function (index, element) {
                            $('#department_id').append('<option value="' + index + '">' + element + '</option>')
                        });
                    }
                    if (data.status == 0) {
                        $(".department-name").text(jsonLang['Phòng ban đã tồn tại']);
                    }
                }
            });

        } else {
            $('.department-name').text(jsonLang['Vui lòng nhập tên phòng ban']);
        }

    },
};

function onmouseoverAddNew() {
    $('.dropdow-add-new').show();
}

function onmouseoutAddNew() {
    $('.dropdow-add-new').hide();
}

function clearModalAdd() {
    $('#modalAddPartment #department_name').val('');
    $('#modalAddPartment #is_inactive').val('1');
    $('#modalAddPartment .department-name').text('');
}