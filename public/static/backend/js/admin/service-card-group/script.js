$('#autotable').PioTable({
    baseUrl: laroute.route('admin.service-card-group.list')
});

var serviceCardGroup = {
    clearAdd: function () {
        $('#name').val('');
        $('#description').val('');
    },
    add: function (param) {
        var name = $('#name');
        var description = $('#description');
        var error = $('.error-name');
        $.getJSON(laroute.route('translate'), function (json) {
        if (name.val() != '') {
            error.text('');
            $.ajax({
                url: laroute.route('admin.service-card-group.submit-add'),
                method: 'POST',
                data: {
                    name: name.val(),
                    description: description.val()
                },
                success: function (response) {
                    if (response.error == 1) {
                        error.text(json['Nhóm thẻ dịch vụ đã tồn tại']);
                    } else {
                        swal(
                            json['Thêm nhãn hiệu sản phẩm thành công'],
                            '',
                            'success'
                        );
                        if (param == 0) {
                            $('#modalAdd').modal('hide');
                        } else {
                            name.val('');
                            description.val('');
                            error.text('');
                        }
                        $('#autotable').PioTable('refresh');
                    }
                }
            });
        } else {
            error.text(json['Vui lòng nhập tên nhóm thẻ'])
        }
    });
    },
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
                    $.post(laroute.route('admin.service-card-group.remove', {id: id}), function () {
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
    edit: function (id) {
        $('.error-edit-name').text('');
        var name = $('#edit-name');
        var description = $('#edit-description');
        var groupIdHidden = $('#groupIdHidden');
        $.ajax({
            url: laroute.route('admin.service-card-group.edit'),
            method: 'POST',
            data: {
                id: id,
            },
            success: function (response) {
                name.val(response.name);
                description.val(response.description);
                groupIdHidden.val(response.service_card_group_id);
                $('#modalEdit').modal('show');
            }
        });
    },
    submitEdit: function () {
        var name = $('#edit-name');
        var description = $('#edit-description');
        var groupIdHidden = $('#groupIdHidden');
        let errorName = $('.error-edit-name');
        $.getJSON(laroute.route('translate'), function (json) {
        if (name.val()!=null){
            errorName.text('');
            $.ajax({
                url: laroute.route('admin.service-card-group.submit-edit'),
                method: 'POST',
                data: {
                    id: groupIdHidden.val(),
                    name: name.val(),
                    description: description.val(),
                },
                success: function (response) {
                    if (response.error == 1) {
                        errorName.text(json['Nhóm thẻ dịch vụ đã tồn tại']);
                    }else{
                        swal(
                            json['Chỉnh sửa nhóm thẻ dịch vụ thành công'],
                            '',
                            'success'
                        );
                        $('#autotable').PioTable('refresh');
                        $('#modalEdit').modal('hide');
                    }
                }
            });
        } else{
            errorName.text(json['Vui lòng nhập tên nhóm thẻ']);
        }
    });
    }
};