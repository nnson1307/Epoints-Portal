$('.check-all-page').click(function (e) {
    $(this).closest('table').find('.check-page').prop('checked', this.checked);
});
$('.check-all-action').click(function (e) {
    $(this).closest('table').find('.check-action').prop('checked', this.checked);
});

var RolePage = {
    checkAll: function (th) {
        var arrayRolePage = new Array();
        $.each($('.id-page'), function () {
            arrayRolePage.push($(this).val());
        });
        var groupId = $('#staffTitleId').val();
        var isCheckAll = 0;
        if ($(th).is(":checked")) {
            isCheckAll = 1;
        }
        $.ajax({
            url: laroute.route('admin.authorization.check-all-role-page'),
            method: "POST",
            data: {
                isCheckAll: isCheckAll,
                groupId: groupId,
                arrayRolePage: arrayRolePage
            },
            success: function (data) {
                $.getJSON(laroute.route('translate'), function (json) {
                toastr.success(json['Thay đổi quyền xem trang thành công'], json['Thông báo']);
                // swal(
                //     'Thay đổi quyền xem trang thành công',
                //     '',
                //     'success'
                // );
                });
            }
        });
    },
    checkPage:function (th) {
        var idPage = $(th).closest('tr').find('.id-page').val();
        var staffTitleId = $('#staffTitleId').val();
        var isCheck = 0;
        if ($(th).is(":checked")) {
            isCheck = 1;
        }
        $.ajax({
            url: laroute.route('admin.authorization.check-each-role-page'),
            method: "POST",
            data: {
                isCheck: isCheck,
                staffTitleId: staffTitleId,
                idPage:idPage
            },
            success: function (data) {
                $.getJSON(laroute.route('translate'), function (json) {
                toastr.success(json['Thay đổi quyền xem trang thành công'], json['Thông báo']);
                });
                // swal(
                //     'Thay đổi quyền xem trang thành công',
                //     '',
                //     'success'
                // );
            }
        });
    }
};

var RoleAction={
    checkAll: function (th) {
        var arrayRoleAction = new Array();
        $.each($('.id-action'), function () {
            arrayRoleAction.push($(this).val());
        });
        var staffTitleId = $('#staffTitleId').val();
        var isCheckAll = 0;
        if ($(th).is(":checked")) {
            isCheckAll = 1;
        }
        $.ajax({
            url: laroute.route('admin.authorization.check-all-role-action'),
            method: "POST",
            data: {
                isCheckAll: isCheckAll,
                staffTitleId: staffTitleId,
                arrayRolePage: arrayRoleAction
            },
            success: function (data) {
                $.getJSON(laroute.route('translate'), function (json) {
                toastr.success(json['Thay đổi quyền chức năng thành công'], json['Thông báo']);
                });
                // swal(
                //     'Thay đổi quyền chức năng thành công',
                //     '',
                //     'success'
                // );
            }
        });
    },
    checkAction:function (th) {
        var idAction = $(th).closest('tr').find('.id-action').val();
        var staffTitleId = $('#staffTitleId').val();
        var isCheck = 0;
        if ($(th).is(":checked")) {
            isCheck = 1;
        }
        $.ajax({
            url: laroute.route('admin.authorization.check-each-role-action'),
            method: "POST",
            data: {
                isCheck: isCheck,
                staffTitleId: staffTitleId,
                idAction:idAction
            },
            success: function (data) {
                $.getJSON(laroute.route('translate'), function (json) {
                toastr.success(json['Thay đổi quyền chức năng thành công'], json['Thông báo']);
                });
                // swal(
                //     'Thay đổi quyền chức năng thành công',
                //     '',
                //     'success'
                // );
            }
        });
    }
};

