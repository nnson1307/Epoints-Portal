$(document).ready(function () {
    $.getJSON(laroute.route('translate'), function (json) {
        $('#btnLuu').click(function () {
            var formEdit = $('#formEdit');
            formEdit.validate({
            rules: {
                name: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: json['Hãy nhập nhóm dịch vụ']
                }

            },
            submitHandler: function () {
                var id = $('#hhidden').val();
                let isActive=0;
                if ($('#h_is_actived').is(":checked")) {
                    isActive=1;
                }
                console.log($('#h_description').val());
                $.ajax({
                    type: 'POST',
                    url: laroute.route('admin.service_category.submitEdit'),
                    data: {
                        id: id,
                        name: $('#h_name').val(),
                        description: $('#h_description').val(),
                        is_actived: isActive
                    },
                    dataType: "JSON",
                    success: function (response) {
                        if (response.status == 1) {
                            $('.error-name').text('');
                            $("#editForm").modal("hide");
                            swal(json["Cập nhật nhóm dịch vụ thành công"], "", "success");
                            $('#autotable').PioTable('refresh');
                        } else {
                            $('.error-name').text(json['Nhóm dịch vụ đã tồn tại']);
                            $('.error-name').css('color', 'red');
                        }
                    }
                })
            }
        });
    });
    });
});
var service_category = {

    remove: function (obj, id) {
        // hightlight row
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
                    // remove hightlight row
                    $(obj).closest('tr').removeClass('m-table__row--danger');
                }
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('admin.service_category.remove', {id: id}), function () {
                        swal(
                            json['Xóa thành công'],
                            '',
                            'success'
                        );
                        // window.location.reload();
                        $('#autotable').PioTable('refresh');
                    });
                }
            });
        });
    },
    changeStatus: function (obj, id, action) {
        $.ajax({
            url: laroute.route('admin.service_category.change-status'),
            method: "POST",
            data: {
                id: id, action: action
            },
            dataType: "JSON"
        }).done(function (data) {
            $('#autotable').PioTable('refresh');
        });
    },
    edit: function (id) {

        $.ajax({
            type: 'POST',
            url: laroute.route('admin.service_category.edit'),
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (response) {
                $('#editForm').modal("show");
                $('#hhidden').val(response["service_category_id"]);
                $('#h_name').val(response["name"]);
                $('#h_description').val(response["description"]);
                $('#h_is_actived').val(response["is_actived"]);
                $('.error-name').text('');
                if (response["is_actived"]==1){
                    $('#h_is_actived').prop('checked', true);
                }else {
                    $('#h_is_actived').prop('checked', false);
                }
            }


        });
    },
    add: function (close) {
        $('#type_add').val(close);
        $.getJSON(laroute.route('translate'), function (json) {
            var form = $('#form');
            form.validate({
                rules: {
                    name: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: json['Hãy nhập nhóm dịch vụ']
                    }
                }
            });

            if (!form.valid()) {
                return false;
            }

            var input = $('#type_add').val();
            var group_name = $('#group_name').val();
            var name = $('#name');
            var des = $('#description');
            // var is_actived = $('#is_actived');
            var input = $('#type_add');
            $.ajax({
                url: laroute.route('admin.service_category.submitAdd'),
                    data: {
                        name: name.val(),
                        description: des.val(),
                        is_actived: 1,
                        close: input.val()
                    },
                    method: 'POST',
                    dataType: "JSON",
                success: function (response) {
                    if (response.status == 1) {
                        if (response.close != 0) {
                            $("#add").modal("hide");
                        }
                        $('#form')[0].reset();
                        $('.error-name').text('');
                        swal(json["Thêm nhóm dịch vụ thành công"], "", "success");
                        $('#autotable').PioTable('refresh');
                    } else {
                        $('.error-name').text(json['Nhóm dịch vụ đã tồn tại']);
                        $('.error-name').css('color', 'red');

                    }
                }
            });
        });
        // $('#form').validate({
        //     rules: {
        //         name: {
        //             required: true,
        //         }

        //     },
        //     messages: {
        //         name: {
        //             required: 'Hãy nhập nhóm dịch vụ'
        //         }
        //     },submitHandler: function () {
        //         var name = $('#name');
        //         var des = $('#description');
        //         // var is_actived = $('#is_actived');
        //         var input = $('#type_add');
        //         $.ajax({
        //             url: laroute.route('admin.service_category.submitAdd'),
        //             data: {
        //                 name: name.val(),
        //                 description: des.val(),
        //                 is_actived: 1,
        //                 close: input.val()
        //             },
        //             method: 'POST',
        //             dataType: "JSON",
        //             success: function (response) {
        //                 $.getJSON(laroute.route('translate'), function (json) {
        //                     if (response.status == 1) {
        //                         if (response.close != 0) {
        //                             $("#add").modal("hide");
        //                         }
        //                         $('#form')[0].reset();
        //                         $('.error-name').text('');
        //                         swal(json["Thêm nhóm dịch vụ thành công"], "", "success");
        //                         $('#autotable').PioTable('refresh');
        //                     } else {
        //                         $('.error-name').text(json['Nhóm dịch vụ đã tồn tại']);
        //                         $('.error-name').css('color', 'red');

        //                     }
        //                 });
        //             }
        //         })
        //     }
        // });

    },
    refresh: function () {
        $('input[name="search_keyword"]').val('');
        $('.m_selectpicker').val('');
        $('.m_selectpicker').selectpicker('refresh');
        $(".btn-search").trigger("click");
    }


};
$('#autotable').PioTable({
    baseUrl: laroute.route('admin.service_category.list')
});
