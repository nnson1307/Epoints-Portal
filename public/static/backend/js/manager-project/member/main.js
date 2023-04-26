var jsonLang = JSON.parse(localStorage.getItem('tranlate'));

var member = {
    init: () => {
        member.search();
    },
    showModalAdd: (manage_project_id) => {
        $.ajax({
            url: laroute.route('manager-project.member.show-popup-add-staff'),
            data: {
                manage_project_id : manage_project_id
            },
            method: "POST",
            success: function (res) {
                if (res.error == false) {
                    $('#project-member__add .modal-body').empty();
                    $('#project-member__add .modal-body').append(res.view);

                    $('#list_member').select2();
                    $("#project-member__add").modal("show");
                } else {
                    swal.fire(res.message, '', "error");
                }
            },
        });

    },
    save: (idProject) => {
        let listUser = $("#list_member").val();
        let role = $("input[name='role']:checked").val();

        data = {
            listUser,
            role,
            idProject
        }

        $.ajax({
            url: laroute.route('manager-work.project.member.store'),
            data: data,
            method: "POST",
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, '', "success").then(function () {
                        $("#project-member__add").modal("hide");
                        member.search();
                    });

                } else {
                    swal.fire(res.message, mess_error, "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Thêm thành viên thất bại'], mess_error, "error");
                }
            }
        });

    },
    search: (page = 1) => {
        let department = $("#department").val();
        let staff = $("#staff").val();
        let role = $("#role").val();
        let project = $("#project_id").val();

        data = {
            department,
            staff,
            role,
            project,
            page
        }
        console.log(data);
        $.ajax({
            url: laroute.route('manager-project.member.list'),
            data: data,
            method: "POST",
            success: function (res) {
                if (res.error == false) {
                    $(".table-content").html(res.view);
                }
            },
            error: function (res) {
            }
        });

    },
    reset: () => {
        $("#department").val('');
        $("#department").select2();
        $("#staff").val('');
        $("#staff").select2();
        $("#role").val('');
        $("#role").select2();
        member.search();
    },
    showModalDetail: (projectStaffId) => {
        console.log();
        $.ajax({
            url: laroute.route('manager-project.member.show'),
            data: { projectStaffId },
            method: "POST",
            success: function (res) {
                if (res.error == false) {
                    $("#modal-action__show").html(res.view);
                    $("#member-detail").modal('show');
                }
            },
        });
    },
    showModalEdit: (projectStaffId) => {
        console.log();
        $.ajax({
            url: laroute.route('manager-project.member.edit'),
            data: { projectStaffId },
            method: "POST",
            success: function (res) {
                if (res.error == false) {
                    $("#modal-action__edit").html(res.view);
                    $("#member-edit").modal('show');
                }
            },
        });
    },
    update: (projectStaffId) => {
        let user = $("#member_edit").val();
        let role = $("input[name='role_edit']:checked").val();

        data = {
            user,
            role,
            projectStaffId
        }
        $.ajax({
            url: laroute.route('manager-project.member.update'),
            data: data,
            method: "POST",
            success: function (res) {
                if (res.error == false) {
                    swal.fire(res.message, '', "success").then(function () {
                        $("#member-edit").modal("hide");
                        member.search();
                    });

                } else {
                    swal.fire(res.message, mess_error, "error");
                }
            },
            error: function (res) {
                if (res.responseJSON != undefined) {
                    var mess_error = '';
                    $.map(res.responseJSON.errors, function (a) {
                        mess_error = mess_error.concat(a + '<br/>');
                    });
                    swal.fire(jsonLang['Cập nhật viên thất bại'], mess_error, "error");
                }
            }
        });

    },
    remove: (object,projectId,projectStaffId,staffName) => {
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var text = jsonLang['Bạn xác nhận xoá nhân viên staffName ra khỏi dự án ?'];
        $(object).closest('tr').addClass('m-table__row--danger');
        swal({
            title: jsonLang['Thông báo'],
            text: text.replace('staffName',staffName),
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: jsonLang['Xóa'],
            cancelButtonText: jsonLang['Hủy'],
            onClose: function () {
                $(object).closest('tr').removeClass('m-table__row--danger');
            }
        }).then(function (result) {
            if (result.value) {
                $.post(laroute.route('manager-project.member.remove', { id: projectStaffId,manage_project_id :projectId }), function (res) {
                    if (!res.error) {
                        swal(
                            res.message,
                            '',
                            'success'
                        );
                        member.search();
                    } else {
                        swal(
                            res.message,
                            '',
                            'warning'
                        );
                    }

                });
                
            }
        });
    }
}
member.init();
